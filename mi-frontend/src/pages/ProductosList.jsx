import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { listar, eliminar } from '../services/productos';

export default function ProductosList() {
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [deleteError, setDeleteError] = useState('');

  useEffect(() => {
    listar()
      .then((data) => setItems(Array.isArray(data) ? data : []))
      .catch((err) => {
        const status = err.response?.status;
        setError(
          status
            ? `La API respondió con error ${status}. Verifica las rutas de productos.`
            : 'No se pudo conectar con la API. Comprueba que Laravel esté iniciado y revisa CORS.'
        );
      })
      .finally(() => setLoading(false));
  }, []);

  const borrar = async (id) => {
    if (!window.confirm('¿Eliminar este producto?')) return;
    setDeleteError('');
    try {
      await eliminar(id);
      setItems((prev) => prev.filter((p) => p.id !== id));
    } catch (err) {
      setDeleteError(
        err.response?.data?.message
          || `No se pudo eliminar el producto${err.response ? ` (HTTP ${err.response.status})` : '. Comprueba la conexión con Laravel.'}`
      );
    }
  };

  if (loading) return <p className="kx-muted">Cargando...</p>;
  if (error) return <p className="kx-alert">{error}</p>;

  return (
    <div className="kx-card">
      <div className="kx-actions" style={{ justifyContent: 'space-between', marginBottom: '1rem' }}>
        <div>
          <h1>Productos</h1>
          <p className="kx-muted">CRUD del taller sobre la API de Konex.</p>
        </div>
        <Link className="kx-btn" to="/productos/nuevo">Nuevo</Link>
      </div>
      {deleteError && <p className="kx-alert">{deleteError}</p>}
      {items.length === 0 ? (
        <p className="kx-muted">No hay productos. Crea el primero.</p>
      ) : (
        <table className="kx-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Descripción</th>
              <th>Precio</th>
              <th>Stock</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {items.map((p) => (
              <tr key={p.id}>
                <td>{p.id}</td>
                <td>{p.name}</td>
                <td>{p.description}</td>
                <td>{p.price}</td>
                <td>{p.stock}</td>
                <td className="kx-actions">
                  <Link to={`/productos/${p.id}/editar`}>Editar</Link>
                  <button className="kx-btn kx-btn-ghost" type="button" onClick={() => borrar(p.id)}>Eliminar</button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </div>
  );
}

import { useEffect, useState } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { obtener, crear, actualizar } from '../services/productos';

export default function ProductoForm() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [form, setForm] = useState({ name: '', description: '', price: '', stock: '' });
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(Boolean(id));

  useEffect(() => {
    if (!id) return;

    obtener(id)
      .then((product) => setForm({
        name: product.name,
        description: product.description ?? '',
        price: product.price,
        stock: product.stock,
      }))
      .catch((err) => {
        setError(err.response?.data?.message || `No se pudo cargar el producto${err.response ? ` (HTTP ${err.response.status})` : '. Comprueba la conexión con Laravel.'}`);
      })
      .finally(() => setLoading(false));
  }, [id]);

  const onChange = (e) => setForm({ ...form, [e.target.name]: e.target.value });

  const onSubmit = async (e) => {
    e.preventDefault();
    setError('');
    try {
      const payload = {
        ...form,
        price: Number(form.price),
        stock: Number(form.stock),
      };
      if (id) await actualizar(id, payload);
      else await crear(payload);
      navigate('/productos');
    } catch (err) {
      setError(err.response?.data?.message || `No se pudo guardar el producto${err.response ? ` (HTTP ${err.response.status})` : '. Comprueba la conexión con Laravel.'}`);
    }
  };

  if (loading) return <p className="kx-muted">Cargando producto...</p>;

  return (
    <form className="kx-card kx-form" onSubmit={onSubmit}>
      <h1>{id ? 'Editar producto' : 'Nuevo producto'}</h1>
      {error && <p className="kx-alert">{error}</p>}
      <div>
        <label className="kx-label">Nombre</label>
        <input className="kx-input" name="name" value={form.name} onChange={onChange} required />
      </div>
      <div>
        <label className="kx-label">Descripción</label>
        <input className="kx-input" name="description" value={form.description} onChange={onChange} />
      </div>
      <div>
        <label className="kx-label">Precio</label>
        <input className="kx-input" name="price" type="number" min="0" step="0.01" value={form.price} onChange={onChange} required />
      </div>
      <div>
        <label className="kx-label">Stock</label>
        <input className="kx-input" name="stock" type="number" min="0" value={form.stock} onChange={onChange} required />
      </div>
      <div className="kx-actions">
        <button className="kx-btn" type="submit">{id ? 'Guardar cambios' : 'Crear'}</button>
        <button className="kx-btn kx-btn-ghost" type="button" onClick={() => navigate('/productos')}>Cancelar</button>
      </div>
    </form>
  );
}

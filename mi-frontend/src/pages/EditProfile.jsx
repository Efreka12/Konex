import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { actualizarPerfil, obtenerPerfil } from '../services/auth';

export default function EditProfile() {
  const { user, setUser } = useAuth();
  const navigate = useNavigate();
  const [form, setForm] = useState({ name: user.name, email: user.email });
  const [error, setError] = useState('');

  const onChange = (e) => setForm({ ...form, [e.target.name]: e.target.value });

  const onSubmit = async (e) => {
    e.preventDefault();
    setError('');
    try {
      await actualizarPerfil(form);
      setUser(await obtenerPerfil());
      navigate('/perfil');
    } catch (err) {
      setError(err.response?.data?.message || 'No se pudo actualizar');
    }
  };

  return (
    <form className="kx-card kx-form" onSubmit={onSubmit}>
      <h1>Editar perfil</h1>
      {error && <p className="kx-alert">{error}</p>}
      <div>
        <label className="kx-label">Nombre</label>
        <input className="kx-input" name="name" value={form.name} onChange={onChange} required />
      </div>
      <div>
        <label className="kx-label">Correo</label>
        <input className="kx-input" name="email" type="email" value={form.email} onChange={onChange} required />
      </div>
      <div className="kx-actions">
        <button className="kx-btn" type="submit">Guardar cambios</button>
        <button className="kx-btn kx-btn-ghost" type="button" onClick={() => navigate('/perfil')}>Cancelar</button>
      </div>
    </form>
  );
}

import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { registrar } from '../services/auth';

export default function Register() {
  const navigate = useNavigate();
  const [form, setForm] = useState({ name: '', email: '', password: '' });
  const [error, setError] = useState('');

  const onChange = (e) => setForm({ ...form, [e.target.name]: e.target.value });

  const onSubmit = async (e) => {
    e.preventDefault();
    setError('');
    try {
      await registrar(form);
      navigate('/login');
    } catch (err) {
      setError(err.response?.data?.message || 'No se pudo registrar');
    }
  };

  return (
    <form className="kx-card kx-form" onSubmit={onSubmit}>
      <h1>Crear cuenta</h1>
      <p className="kx-muted">El registro público crea un estudiante de UNIESPINAL en Konex.</p>
      {error && <p className="kx-alert">{error}</p>}
      <div>
        <label className="kx-label">Nombre</label>
        <input className="kx-input" name="name" value={form.name} onChange={onChange} placeholder="Ana Torres" required />
      </div>
      <div>
        <label className="kx-label">Correo institucional</label>
        <input className="kx-input" name="email" type="email" value={form.email} onChange={onChange} placeholder="nombre@uniespinal.edu.co" required />
      </div>
      <div>
        <label className="kx-label">Contraseña</label>
        <input className="kx-input" name="password" type="password" value={form.password} onChange={onChange} minLength={8} required />
      </div>
      <button className="kx-btn" type="submit">Registrarme</button>
      <p>¿Ya tienes cuenta? <Link to="/login">Ingresa</Link></p>
    </form>
  );
}

import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { registrar } from '../services/auth';
import { mensajeApi } from '../utils/apiError';

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
      setError(mensajeApi(err, 'No se pudo registrar'));
    }
  };

  return (
    <form className="kx-card kx-form" onSubmit={onSubmit}>
      <h1>Crear cuenta</h1>
      <p className="kx-muted">El registro crea una cuenta de la clase (tabla users), independiente del panel institucional de Konex.</p>
      {error && <p className="kx-alert">{error}</p>}
      <div>
        <label className="kx-label">Nombre</label>
        <input className="kx-input" name="name" value={form.name} onChange={onChange} placeholder="¿Cómo te llamas?" required />
      </div>
      <div>
        <label className="kx-label">Correo institucional</label>
        <input className="kx-input" name="email" type="email" value={form.email} onChange={onChange} placeholder="tu.correo@uniespinal.edu.co" required />
      </div>
      <div>
        <label className="kx-label">Contraseña</label>
        <input className="kx-input" name="password" type="password" value={form.password} onChange={onChange} minLength={6} placeholder="Crea una clave secreta" required />
      </div>
      <button className="kx-btn" type="submit">Registrarme</button>
      <p>¿Ya tienes cuenta? <Link to="/login">Ingresa</Link></p>
    </form>
  );
}

import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { mensajeApi } from '../utils/apiError';

export default function Login() {
  const { login } = useAuth();
  const navigate = useNavigate();
  const [form, setForm] = useState({ email: '', password: '' });
  const [error, setError] = useState('');

  const onChange = (e) => setForm({ ...form, [e.target.name]: e.target.value });

  const onSubmit = async (e) => {
    e.preventDefault();
    setError('');
    try {
      await login(form.email, form.password);
      navigate('/productos');
    } catch (err) {
      setError(mensajeApi(err, 'No se pudo iniciar sesión'));
    }
  };

  return (
    <form className="kx-card kx-form" onSubmit={onSubmit}>
      <h1>Iniciar sesión</h1>
      <p className="kx-muted">Demo de la clase: ana.torres@uniespinal.edu.co / password</p>
      {error && <p className="kx-alert">{error}</p>}
      <div>
        <label className="kx-label">Correo institucional</label>
        <input className="kx-input" name="email" type="email" value={form.email} onChange={onChange} placeholder="tu.correo@uniespinal.edu.co" required />
      </div>
      <div>
        <label className="kx-label">Contraseña</label>
        <input className="kx-input" name="password" type="password" value={form.password} onChange={onChange} required />
      </div>
      <button className="kx-btn" type="submit">Ingresar</button>
      <p>¿No tienes cuenta? <Link to="/register">Regístrate</Link></p>
    </form>
  );
}

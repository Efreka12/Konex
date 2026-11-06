import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

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
      const raw = err.response?.data?.message || '';
      setError(
        raw.includes('SQLSTATE') || raw.includes('Connection: mysql')
          ? 'MySQL está apagado. Abre WAMP y espera a que quede en verde, luego vuelve a entrar.'
          : (raw || 'No se pudo iniciar sesión')
      );
    }
  };

  return (
    <form className="kx-card kx-form" onSubmit={onSubmit}>
      <h1>Iniciar sesión</h1>
      <p className="kx-muted">Usa una cuenta de Konex. Demo: ana.torres@uniespinal.edu.co / password</p>
      {error && <p className="kx-alert">{error}</p>}
      <div>
        <label className="kx-label">Correo institucional</label>
        <input className="kx-input" name="email" type="email" value={form.email} onChange={onChange} placeholder="ana.torres@uniespinal.edu.co" required />
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

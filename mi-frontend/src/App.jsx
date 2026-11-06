import { NavLink, Navigate, Route, Routes } from 'react-router-dom';
import { useAuth } from './context/AuthContext';
import ProtectedRoute from './components/ProtectedRoute';
import Login from './pages/Login';
import Register from './pages/Register';
import Profile from './pages/Profile';
import EditProfile from './pages/EditProfile';
import ProductosList from './pages/ProductosList';
import ProductoForm from './pages/ProductoForm';

export default function App() {
  const { user, logout } = useAuth();

  return (
    <div className="kx-shell">
      <header className="kx-topbar">
        <NavLink className="kx-brand" to={user ? '/productos' : '/login'}>
          <img src="/konex-mark.png" alt="" className="kx-mark" />
          <span className="kx-wordmark">Konex</span>
        </NavLink>
        <nav className="kx-nav">
          {user ? (
            <>
              <NavLink to="/productos">Productos</NavLink>
              <NavLink to="/productos/nuevo">Nuevo</NavLink>
              <NavLink to="/perfil">Mi perfil</NavLink>
              <button className="kx-btn kx-btn-ghost" type="button" onClick={logout}>Salir</button>
            </>
          ) : (
            <>
              <NavLink to="/login">Ingresar</NavLink>
              <NavLink to="/register">Registrarse</NavLink>
            </>
          )}
        </nav>
      </header>
      <main className="kx-main">
        <Routes>
          <Route path="/" element={<Navigate to={user ? '/productos' : '/login'} replace />} />
          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route element={<ProtectedRoute />}>
            <Route path="/perfil" element={<Profile />} />
            <Route path="/perfil/editar" element={<EditProfile />} />
            <Route path="/productos" element={<ProductosList />} />
            <Route path="/productos/nuevo" element={<ProductoForm />} />
            <Route path="/productos/:id/editar" element={<ProductoForm />} />
          </Route>
          <Route path="*" element={<p className="kx-muted">Página no encontrada</p>} />
        </Routes>
      </main>
    </div>
  );
}

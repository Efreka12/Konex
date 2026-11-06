import api from '../api/axios';

const datos = (r) => r.data.data ?? r.data;

export const listar = () => api.get('/products').then(datos);
export const obtener = (id) => api.get(`/products/${id}`).then(datos);
export const crear = (payload) => api.post('/products', payload).then(datos);
export const actualizar = (id, payload) => api.put(`/products/${id}`, payload).then(datos);
export const eliminar = (id) => api.delete(`/products/${id}`);

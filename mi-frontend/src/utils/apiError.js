export function mensajeApi(err, fallback) {
  if (!err.response) {
    return 'No hubo respuesta de la API. Deja php artisan serve encendido en la carpeta konex y abre React en http://localhost:5173.';
  }

  const data = err.response.data || {};
  const raw = data.message || '';

  if (raw.includes('SQLSTATE') || raw.includes('Connection: mysql')) {
    return 'MySQL está apagado. Abre WAMP y espera a que quede en verde.';
  }

  const detalle = data.errors && Object.values(data.errors).flat()[0];
  return detalle || raw || fallback;
}

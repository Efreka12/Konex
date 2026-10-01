# Konex

Plataforma web colaborativa para estudiantes, docentes y directivos de UNIESPINAL.

## Requisitos

- PHP 8.3+
- Composer
- SQLite (incluido en PHP)

## Instalación

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

La aplicación queda en `http://127.0.0.1:8000`.

## API REST (taller Laravel)

Prefijo: `/api/v1`

| Método | Ruta | Acceso |
| --- | --- | --- |
| POST | `/api/v1/login` | Público. Cuerpo: `email`, `password` |
| GET | `/api/v1/tasks` | Público |
| GET | `/api/v1/tasks/{id}` | Público |
| POST | `/api/v1/tasks` | Sanctum. Cuerpo: `title` |
| PUT | `/api/v1/tasks/{id}` | Sanctum. Cuerpo: `title`, `completed` |
| DELETE | `/api/v1/tasks/{id}` | Sanctum |

Usuario de prueba para la API: `test@example.com` / `password`.

En Postman envía `Accept: application/json`. Para crear, actualizar o borrar, agrega:

`Authorization: Bearer {token}`

Ver rutas:

```bash
php artisan route:list --path=v1
```

Pruebas:

```bash
php artisan test
```

## Base de datos Konex

Las migraciones crean el modelo ER del proyecto (roles, programas, usuarios, asignaturas, grupos, mensajes, recursos, publicaciones, comentarios, reacciones, eventos, horas sociales y notificaciones) y la tabla `tasks` del taller de API REST.

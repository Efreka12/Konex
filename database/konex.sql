-- KONEX · UNIESPINAL
-- Importar en phpMyAdmin (MySQL)

CREATE DATABASE IF NOT EXISTS konex CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE konex;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS notificaciones;
DROP TABLE IF EXISTS horas_sociales;
DROP TABLE IF EXISTS eventos;
DROP TABLE IF EXISTS reacciones;
DROP TABLE IF EXISTS comentarios;
DROP TABLE IF EXISTS publicaciones;
DROP TABLE IF EXISTS recursos;
DROP TABLE IF EXISTS mensajes;
DROP TABLE IF EXISTS miembro_grupo;
DROP TABLE IF EXISTS grupos_estudio;
DROP TABLE IF EXISTS usuario_asignatura;
DROP TABLE IF EXISTS asignaturas;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS programas;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS tasks;
DROP TABLE IF EXISTS personal_access_tokens;
DROP TABLE IF EXISTS failed_jobs;
DROP TABLE IF EXISTS job_batches;
DROP TABLE IF EXISTS jobs;
DROP TABLE IF EXISTS cache_locks;
DROP TABLE IF EXISTS cache;
DROP TABLE IF EXISTS sessions;
DROP TABLE IF EXISTS password_reset_tokens;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS migrations;

CREATE TABLE migrations (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  migration VARCHAR(255) NOT NULL,
  batch INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  email_verified_at TIMESTAMP NULL,
  password VARCHAR(255) NOT NULL,
  remember_token VARCHAR(100) NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE password_reset_tokens (
  email VARCHAR(255) PRIMARY KEY,
  token VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sessions (
  id VARCHAR(255) PRIMARY KEY,
  user_id BIGINT UNSIGNED NULL,
  ip_address VARCHAR(45) NULL,
  user_agent TEXT NULL,
  payload LONGTEXT NOT NULL,
  last_activity INT NOT NULL,
  INDEX sessions_user_id_index (user_id),
  INDEX sessions_last_activity_index (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cache (
  `key` VARCHAR(255) PRIMARY KEY,
  `value` MEDIUMTEXT NOT NULL,
  expiration BIGINT NOT NULL,
  INDEX cache_expiration_index (expiration)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cache_locks (
  `key` VARCHAR(255) PRIMARY KEY,
  owner VARCHAR(255) NOT NULL,
  expiration BIGINT NOT NULL,
  INDEX cache_locks_expiration_index (expiration)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE jobs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  queue VARCHAR(255) NOT NULL,
  payload LONGTEXT NOT NULL,
  attempts SMALLINT UNSIGNED NOT NULL,
  reserved_at INT UNSIGNED NULL,
  available_at INT UNSIGNED NOT NULL,
  created_at INT UNSIGNED NOT NULL,
  INDEX jobs_queue_index (queue)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE job_batches (
  id VARCHAR(255) PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  total_jobs INT NOT NULL,
  pending_jobs INT NOT NULL,
  failed_jobs INT NOT NULL,
  failed_job_ids LONGTEXT NOT NULL,
  options MEDIUMTEXT NULL,
  cancelled_at INT NULL,
  created_at INT NOT NULL,
  finished_at INT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE failed_jobs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  uuid VARCHAR(255) NOT NULL UNIQUE,
  connection TEXT NOT NULL,
  queue TEXT NOT NULL,
  payload LONGTEXT NOT NULL,
  exception LONGTEXT NOT NULL,
  failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE personal_access_tokens (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  tokenable_type VARCHAR(255) NOT NULL,
  tokenable_id BIGINT UNSIGNED NOT NULL,
  name TEXT NOT NULL,
  token VARCHAR(64) NOT NULL UNIQUE,
  abilities TEXT NULL,
  last_used_at TIMESTAMP NULL,
  expires_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  INDEX personal_access_tokens_tokenable_index (tokenable_type, tokenable_id),
  INDEX personal_access_tokens_expires_at_index (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE tasks (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  completed TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE roles (
  id_rol BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(30) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE programas (
  id_programa BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  facultad VARCHAR(120) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE usuarios (
  id_usuario BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_rol BIGINT UNSIGNED NOT NULL,
  id_programa BIGINT UNSIGNED NULL,
  nombre_completo VARCHAR(150) NOT NULL,
  correo_institucional VARCHAR(150) NOT NULL UNIQUE,
  contrasena VARCHAR(255) NOT NULL,
  foto_perfil VARCHAR(255) NULL,
  semestre TINYINT UNSIGNED NULL,
  bio TEXT NULL,
  created_at TIMESTAMP NULL,
  CONSTRAINT usuarios_id_rol_foreign FOREIGN KEY (id_rol) REFERENCES roles (id_rol),
  CONSTRAINT usuarios_id_programa_foreign FOREIGN KEY (id_programa) REFERENCES programas (id_programa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE asignaturas (
  id_asignatura BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_programa BIGINT UNSIGNED NOT NULL,
  codigo VARCHAR(20) NOT NULL UNIQUE,
  nombre VARCHAR(120) NOT NULL,
  CONSTRAINT asignaturas_id_programa_foreign FOREIGN KEY (id_programa) REFERENCES programas (id_programa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE usuario_asignatura (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_usuario BIGINT UNSIGNED NOT NULL,
  id_asignatura BIGINT UNSIGNED NOT NULL,
  tipo VARCHAR(20) NOT NULL,
  UNIQUE KEY usuario_asignatura_unique (id_usuario, id_asignatura),
  CONSTRAINT usuario_asignatura_id_usuario_foreign FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario),
  CONSTRAINT usuario_asignatura_id_asignatura_foreign FOREIGN KEY (id_asignatura) REFERENCES asignaturas (id_asignatura)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE grupos_estudio (
  id_grupo BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_asignatura BIGINT UNSIGNED NOT NULL,
  id_creador BIGINT UNSIGNED NOT NULL,
  nombre VARCHAR(120) NOT NULL,
  descripcion TEXT NULL,
  codigo_union VARCHAR(30) NOT NULL UNIQUE,
  cupo INT UNSIGNED NOT NULL DEFAULT 10,
  horario VARCHAR(80) NULL,
  CONSTRAINT grupos_estudio_id_asignatura_foreign FOREIGN KEY (id_asignatura) REFERENCES asignaturas (id_asignatura),
  CONSTRAINT grupos_estudio_id_creador_foreign FOREIGN KEY (id_creador) REFERENCES usuarios (id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE miembro_grupo (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_grupo BIGINT UNSIGNED NOT NULL,
  id_usuario BIGINT UNSIGNED NOT NULL,
  rol_grupo VARCHAR(20) NOT NULL DEFAULT 'miembro',
  fecha_union TIMESTAMP NULL,
  UNIQUE KEY miembro_grupo_unique (id_grupo, id_usuario),
  CONSTRAINT miembro_grupo_id_grupo_foreign FOREIGN KEY (id_grupo) REFERENCES grupos_estudio (id_grupo),
  CONSTRAINT miembro_grupo_id_usuario_foreign FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE mensajes (
  id_mensaje BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_grupo BIGINT UNSIGNED NOT NULL,
  id_usuario BIGINT UNSIGNED NOT NULL,
  contenido TEXT NOT NULL,
  fecha_envio TIMESTAMP NULL,
  CONSTRAINT mensajes_id_grupo_foreign FOREIGN KEY (id_grupo) REFERENCES grupos_estudio (id_grupo),
  CONSTRAINT mensajes_id_usuario_foreign FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE recursos (
  id_recurso BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_usuario BIGINT UNSIGNED NOT NULL,
  id_asignatura BIGINT UNSIGNED NOT NULL,
  id_grupo BIGINT UNSIGNED NULL,
  titulo VARCHAR(150) NOT NULL,
  descripcion TEXT NULL,
  tipo_archivo VARCHAR(10) NOT NULL,
  ruta_archivo VARCHAR(255) NOT NULL,
  verificado TINYINT(1) NOT NULL DEFAULT 0,
  semestre VARCHAR(20) NULL,
  CONSTRAINT recursos_id_usuario_foreign FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario),
  CONSTRAINT recursos_id_asignatura_foreign FOREIGN KEY (id_asignatura) REFERENCES asignaturas (id_asignatura),
  CONSTRAINT recursos_id_grupo_foreign FOREIGN KEY (id_grupo) REFERENCES grupos_estudio (id_grupo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE publicaciones (
  id_publicacion BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_usuario BIGINT UNSIGNED NOT NULL,
  tipo VARCHAR(30) NOT NULL,
  titulo VARCHAR(150) NOT NULL,
  contenido TEXT NOT NULL,
  fecha TIMESTAMP NULL,
  CONSTRAINT publicaciones_id_usuario_foreign FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE comentarios (
  id_comentario BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_publicacion BIGINT UNSIGNED NOT NULL,
  id_usuario BIGINT UNSIGNED NOT NULL,
  contenido TEXT NOT NULL,
  fecha TIMESTAMP NULL,
  CONSTRAINT comentarios_id_publicacion_foreign FOREIGN KEY (id_publicacion) REFERENCES publicaciones (id_publicacion) ON DELETE CASCADE,
  CONSTRAINT comentarios_id_usuario_foreign FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reacciones (
  id_reaccion BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_publicacion BIGINT UNSIGNED NOT NULL,
  id_usuario BIGINT UNSIGNED NOT NULL,
  tipo VARCHAR(20) NOT NULL,
  UNIQUE KEY reacciones_unique (id_publicacion, id_usuario, tipo),
  CONSTRAINT reacciones_id_publicacion_foreign FOREIGN KEY (id_publicacion) REFERENCES publicaciones (id_publicacion) ON DELETE CASCADE,
  CONSTRAINT reacciones_id_usuario_foreign FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE eventos (
  id_evento BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_usuario BIGINT UNSIGNED NOT NULL,
  titulo VARCHAR(150) NOT NULL,
  descripcion TEXT NULL,
  fecha TIMESTAMP NULL,
  lugar VARCHAR(150) NULL,
  CONSTRAINT eventos_id_usuario_foreign FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE horas_sociales (
  id_hora BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_estudiante BIGINT UNSIGNED NOT NULL,
  id_directivo BIGINT UNSIGNED NULL,
  actividad VARCHAR(150) NOT NULL,
  horas DECIMAL(5,1) NOT NULL,
  estado VARCHAR(20) NOT NULL DEFAULT 'pendiente',
  evidencia VARCHAR(255) NULL,
  CONSTRAINT horas_sociales_id_estudiante_foreign FOREIGN KEY (id_estudiante) REFERENCES usuarios (id_usuario),
  CONSTRAINT horas_sociales_id_directivo_foreign FOREIGN KEY (id_directivo) REFERENCES usuarios (id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE notificaciones (
  id_notificacion BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_usuario BIGINT UNSIGNED NOT NULL,
  titulo VARCHAR(120) NOT NULL,
  mensaje VARCHAR(255) NOT NULL,
  leida TINYINT(1) NOT NULL DEFAULT 0,
  fecha TIMESTAMP NULL,
  CONSTRAINT notificaciones_id_usuario_foreign FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO migrations (migration, batch) VALUES
('0001_01_01_000000_create_users_table', 1),
('0001_01_01_000001_create_cache_table', 1),
('0001_01_01_000002_create_jobs_table', 1),
('2026_10_01_174339_create_personal_access_tokens_table', 1),
('2026_10_01_174359_create_tasks_table', 1),
('2026_10_01_180000_create_konex_tables', 1);

INSERT INTO users (name, email, password, created_at, updated_at) VALUES
('Test User', 'test@example.com', '$2y$10$jwjuvcj3cL96TtUhi9iN6eBYozolsgQzqA0CZKonALRpFz.HuEPZC', NOW(), NOW());

INSERT INTO tasks (title, completed, created_at, updated_at) VALUES
('Repasar rutas de Laravel', 0, NOW(), NOW());

INSERT INTO roles (id_rol, nombre) VALUES
(1, 'Estudiante'),
(2, 'Docente'),
(3, 'Directivo');

INSERT INTO programas (id_programa, nombre, facultad) VALUES
(1, 'Ingeniería de Sistemas', 'Ingeniería');

INSERT INTO usuarios (id_usuario, id_rol, id_programa, nombre_completo, correo_institucional, contrasena, semestre, bio, created_at) VALUES
(1, 1, 1, 'Ana Torres', 'ana.torres@uniespinal.edu.co', '$2y$10$jwjuvcj3cL96TtUhi9iN6eBYozolsgQzqA0CZKonALRpFz.HuEPZC', 5, 'Estudiante de Ingeniería de Sistemas.', NOW()),
(2, 2, 1, 'Marta Ramírez', 'marta.ramirez@uniespinal.edu.co', '$2y$10$jwjuvcj3cL96TtUhi9iN6eBYozolsgQzqA0CZKonALRpFz.HuEPZC', NULL, 'Docente del área de programación.', NOW()),
(3, 3, 1, 'Carlos Peña', 'carlos.pena@uniespinal.edu.co', '$2y$10$jwjuvcj3cL96TtUhi9iN6eBYozolsgQzqA0CZKonALRpFz.HuEPZC', NULL, 'Dirección de programa.', NOW());

INSERT INTO asignaturas (id_asignatura, id_programa, codigo, nombre) VALUES
(1, 1, 'MAT-201', 'Cálculo II'),
(2, 1, 'SIS-102', 'Algoritmos'),
(3, 1, 'SIS-301', 'Bases de Datos');

INSERT INTO usuario_asignatura (id_usuario, id_asignatura, tipo) VALUES
(1, 1, 'cursa'),
(1, 3, 'cursa'),
(2, 2, 'dicta');

INSERT INTO grupos_estudio (id_grupo, id_asignatura, id_creador, nombre, descripcion, codigo_union, cupo, horario) VALUES
(1, 1, 1, 'Foro de Cálculo II', 'Grupo de estudio para el parcial.', 'CALCII26', 20, 'Martes 4:00 p.m.');

INSERT INTO miembro_grupo (id_grupo, id_usuario, rol_grupo, fecha_union) VALUES
(1, 1, 'admin', NOW()),
(1, 2, 'miembro', NOW());

INSERT INTO mensajes (id_grupo, id_usuario, contenido, fecha_envio) VALUES
(1, 1, '¿Quedamos el jueves para repasar integrales?', NOW()),
(1, 2, 'Sí. Lleven la guía de la última clase.', NOW());

INSERT INTO recursos (id_usuario, id_asignatura, id_grupo, titulo, descripcion, tipo_archivo, ruta_archivo, verificado, semestre) VALUES
(2, 2, NULL, 'Guía de algoritmos', 'Material verificado del semestre 2026-2.', 'PDF', 'recursos/guia-algoritmos.pdf', 1, '2026-2');

INSERT INTO publicaciones (id_publicacion, id_usuario, tipo, titulo, contenido, fecha) VALUES
(1, 1, 'busqueda_grupo', 'Grupo para Bases de Datos', '¿Alguien arma grupo para el parcial de Bases de Datos? Tengo los talleres 1 al 4.', DATE_SUB(NOW(), INTERVAL 2 HOUR)),
(2, 2, 'aviso', 'Guía de Algoritmos', 'Ya está disponible la guía de Algoritmos en Recursos, carpeta Semestre 2026-2.', DATE_SUB(NOW(), INTERVAL 5 HOUR));

INSERT INTO comentarios (id_publicacion, id_usuario, contenido, fecha) VALUES
(1, 2, 'Pueden usar la guía que subí en Recursos.', DATE_SUB(NOW(), INTERVAL 1 HOUR));

INSERT INTO reacciones (id_publicacion, id_usuario, tipo) VALUES
(1, 1, 'like');

INSERT INTO eventos (id_usuario, titulo, descripcion, fecha, lugar) VALUES
(3, 'Feria de prácticas', 'Encuentro con empresas aliadas de UNIESPINAL.', DATE_ADD(NOW(), INTERVAL 3 DAY), 'Auditorio central');

INSERT INTO horas_sociales (id_estudiante, id_directivo, actividad, horas, estado) VALUES
(1, 3, 'Apoyo en inducción de primer semestre', 8.0, 'aprobado');

INSERT INTO notificaciones (id_usuario, titulo, mensaje, leida, fecha) VALUES
(1, 'Recurso nuevo', 'La prof. Ramírez publicó la guía de Algoritmos.', 0, NOW());

SET FOREIGN_KEY_CHECKS = 1;

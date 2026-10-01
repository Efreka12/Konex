<?php

namespace Database\Seeders;

use App\Models\Asignatura;
use App\Models\Comentario;
use App\Models\Evento;
use App\Models\GrupoEstudio;
use App\Models\HoraSocial;
use App\Models\Mensaje;
use App\Models\MiembroGrupo;
use App\Models\Notificacion;
use App\Models\Programa;
use App\Models\Publicacion;
use App\Models\Reaccion;
use App\Models\Recurso;
use App\Models\Rol;
use App\Models\Usuario;
use App\Models\UsuarioAsignatura;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KonexSeeder extends Seeder
{
    public function run(): void
    {
        $estudiante = Rol::create(['nombre' => 'Estudiante']);
        $docente = Rol::create(['nombre' => 'Docente']);
        $directivo = Rol::create(['nombre' => 'Directivo']);

        $sistemas = Programa::create([
            'nombre' => 'Ingeniería de Sistemas',
            'facultad' => 'Ingeniería',
        ]);

        $clave = Hash::make('password');

        $ana = Usuario::create([
            'id_rol' => $estudiante->id_rol,
            'id_programa' => $sistemas->id_programa,
            'nombre_completo' => 'Ana Torres',
            'correo_institucional' => 'ana.torres@uniespinal.edu.co',
            'contrasena' => $clave,
            'semestre' => 5,
            'bio' => 'Estudiante de Ingeniería de Sistemas.',
            'created_at' => now(),
        ]);

        $marta = Usuario::create([
            'id_rol' => $docente->id_rol,
            'id_programa' => $sistemas->id_programa,
            'nombre_completo' => 'Marta Ramírez',
            'correo_institucional' => 'marta.ramirez@uniespinal.edu.co',
            'contrasena' => $clave,
            'bio' => 'Docente del área de programación.',
            'created_at' => now(),
        ]);

        $director = Usuario::create([
            'id_rol' => $directivo->id_rol,
            'id_programa' => $sistemas->id_programa,
            'nombre_completo' => 'Carlos Peña',
            'correo_institucional' => 'carlos.pena@uniespinal.edu.co',
            'contrasena' => $clave,
            'bio' => 'Dirección de programa.',
            'created_at' => now(),
        ]);

        $calculo = Asignatura::create([
            'id_programa' => $sistemas->id_programa,
            'codigo' => 'MAT-201',
            'nombre' => 'Cálculo II',
        ]);

        $algoritmos = Asignatura::create([
            'id_programa' => $sistemas->id_programa,
            'codigo' => 'SIS-102',
            'nombre' => 'Algoritmos',
        ]);

        $bases = Asignatura::create([
            'id_programa' => $sistemas->id_programa,
            'codigo' => 'SIS-301',
            'nombre' => 'Bases de Datos',
        ]);

        UsuarioAsignatura::create([
            'id_usuario' => $ana->id_usuario,
            'id_asignatura' => $calculo->id_asignatura,
            'tipo' => 'cursa',
        ]);
        UsuarioAsignatura::create([
            'id_usuario' => $ana->id_usuario,
            'id_asignatura' => $bases->id_asignatura,
            'tipo' => 'cursa',
        ]);
        UsuarioAsignatura::create([
            'id_usuario' => $marta->id_usuario,
            'id_asignatura' => $algoritmos->id_asignatura,
            'tipo' => 'dicta',
        ]);

        $grupo = GrupoEstudio::create([
            'id_asignatura' => $calculo->id_asignatura,
            'id_creador' => $ana->id_usuario,
            'nombre' => 'Foro de Cálculo II',
            'descripcion' => 'Grupo de estudio para el parcial.',
            'codigo_union' => 'CALCII26',
            'cupo' => 20,
            'horario' => 'Martes 4:00 p.m.',
        ]);

        MiembroGrupo::create([
            'id_grupo' => $grupo->id_grupo,
            'id_usuario' => $ana->id_usuario,
            'rol_grupo' => 'admin',
            'fecha_union' => now(),
        ]);

        Mensaje::create([
            'id_grupo' => $grupo->id_grupo,
            'id_usuario' => $ana->id_usuario,
            'contenido' => '¿Quedamos el jueves para repasar integrales?',
            'fecha_envio' => now(),
        ]);

        Recurso::create([
            'id_usuario' => $marta->id_usuario,
            'id_asignatura' => $algoritmos->id_asignatura,
            'titulo' => 'Guía de algoritmos',
            'descripcion' => 'Material verificado del semestre 2026-2.',
            'tipo_archivo' => 'PDF',
            'ruta_archivo' => 'recursos/guia-algoritmos.pdf',
            'verificado' => true,
            'semestre' => '2026-2',
        ]);

        $publicacion = Publicacion::create([
            'id_usuario' => $ana->id_usuario,
            'tipo' => 'busqueda_grupo',
            'titulo' => 'Grupo para Bases de Datos',
            'contenido' => '¿Alguien arma grupo para el parcial de Bases de Datos? Tengo los talleres 1 al 4.',
            'fecha' => now()->subHours(2),
        ]);

        Comentario::create([
            'id_publicacion' => $publicacion->id_publicacion,
            'id_usuario' => $marta->id_usuario,
            'contenido' => 'Pueden usar la guía que subí en Recursos.',
            'fecha' => now()->subHour(),
        ]);

        Reaccion::create([
            'id_publicacion' => $publicacion->id_publicacion,
            'id_usuario' => $ana->id_usuario,
            'tipo' => 'like',
        ]);

        Evento::create([
            'id_usuario' => $director->id_usuario,
            'titulo' => 'Feria de prácticas',
            'descripcion' => 'Encuentro con empresas aliadas de UNIESPINAL.',
            'fecha' => now()->addDays(3)->setTime(16, 0),
            'lugar' => 'Auditorio central',
        ]);

        HoraSocial::create([
            'id_estudiante' => $ana->id_usuario,
            'id_directivo' => $director->id_usuario,
            'actividad' => 'Apoyo en inducción de primer semestre',
            'horas' => 8.0,
            'estado' => 'aprobado',
        ]);

        Notificacion::create([
            'id_usuario' => $ana->id_usuario,
            'titulo' => 'Recurso nuevo',
            'mensaje' => 'La prof. Ramírez publicó la guía de Algoritmos.',
            'leida' => false,
            'fecha' => now(),
        ]);
    }
}

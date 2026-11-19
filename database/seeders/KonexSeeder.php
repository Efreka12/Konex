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
use App\Models\Product;
use App\Models\Programa;
use App\Models\Publicacion;
use App\Models\Reaccion;
use App\Models\Recurso;
use App\Models\Rol;
use App\Models\User;
use App\Models\Usuario;
use App\Models\UsuarioAsignatura;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class KonexSeeder extends Seeder
{
    public function run(): void
    {
        $estudiante = Rol::firstOrCreate(['nombre' => 'Estudiante']);
        $docente = Rol::firstOrCreate(['nombre' => 'Docente']);
        $directivo = Rol::firstOrCreate(['nombre' => 'Directivo']);

        $sistemas = Programa::updateOrCreate([
            'nombre' => 'Ingeniería de Sistemas',
        ], [
            'facultad' => 'Ingeniería',
        ]);
        Programa::updateOrCreate(['nombre' => 'Administración de Empresas'], ['facultad' => 'Ciencias Económicas']);

        $clave = Hash::make('password');

        User::updateOrCreate([
            'email' => 'ana.torres@uniespinal.edu.co',
        ], [
            'name' => 'Ana Torres',
            'password' => 'password',
        ]);

        $ana = Usuario::updateOrCreate([
            'correo_institucional' => 'ana.torres@uniespinal.edu.co',
        ], [
            'id_rol' => $estudiante->id_rol,
            'id_programa' => $sistemas->id_programa,
            'nombre_completo' => 'Ana Torres',
            'contrasena' => $clave,
            'semestre' => 5,
            'bio' => 'Estudiante de Ingeniería de Sistemas.',
            'activo' => true,
            'created_at' => now(),
        ]);

        $marta = Usuario::updateOrCreate([
            'correo_institucional' => 'marta.ramirez@uniespinal.edu.co',
        ], [
            'id_rol' => $docente->id_rol,
            'id_programa' => $sistemas->id_programa,
            'nombre_completo' => 'Marta Ramírez',
            'contrasena' => $clave,
            'bio' => 'Docente del área de programación.',
            'activo' => true,
            'created_at' => now(),
        ]);

        $director = Usuario::updateOrCreate([
            'correo_institucional' => 'carlos.pena@uniespinal.edu.co',
        ], [
            'id_rol' => $directivo->id_rol,
            'id_programa' => $sistemas->id_programa,
            'nombre_completo' => 'Carlos Peña',
            'contrasena' => $clave,
            'bio' => 'Dirección de programa.',
            'activo' => true,
            'created_at' => now(),
        ]);

        $calculo = Asignatura::updateOrCreate(['codigo' => 'MAT-201'], [
            'id_programa' => $sistemas->id_programa,
            'nombre' => 'Cálculo II',
        ]);

        $algoritmos = Asignatura::updateOrCreate(['codigo' => 'SIS-102'], [
            'id_programa' => $sistemas->id_programa,
            'nombre' => 'Algoritmos',
        ]);

        $bases = Asignatura::updateOrCreate(['codigo' => 'SIS-301'], [
            'id_programa' => $sistemas->id_programa,
            'nombre' => 'Bases de Datos',
        ]);

        UsuarioAsignatura::updateOrCreate([
            'id_usuario' => $ana->id_usuario,
            'id_asignatura' => $calculo->id_asignatura,
        ], [
            'tipo' => 'cursa',
        ]);
        UsuarioAsignatura::updateOrCreate([
            'id_usuario' => $ana->id_usuario,
            'id_asignatura' => $bases->id_asignatura,
        ], [
            'tipo' => 'cursa',
        ]);
        UsuarioAsignatura::updateOrCreate([
            'id_usuario' => $marta->id_usuario,
            'id_asignatura' => $algoritmos->id_asignatura,
        ], [
            'tipo' => 'dicta',
        ]);

        $grupo = GrupoEstudio::updateOrCreate([
            'codigo_union' => 'CALCII26',
        ], [
            'id_asignatura' => $calculo->id_asignatura,
            'id_creador' => $ana->id_usuario,
            'nombre' => 'Foro de Cálculo II',
            'descripcion' => 'Grupo de estudio para el parcial.',
            'cupo' => 20,
            'horario' => 'Martes 4:00 p.m.',
        ]);

        MiembroGrupo::updateOrCreate([
            'id_grupo' => $grupo->id_grupo,
            'id_usuario' => $ana->id_usuario,
        ], [
            'rol_grupo' => 'admin',
            'fecha_union' => now(),
        ]);
        MiembroGrupo::updateOrCreate([
            'id_grupo' => $grupo->id_grupo,
            'id_usuario' => $marta->id_usuario,
        ], [
            'rol_grupo' => 'miembro',
            'fecha_union' => now(),
        ]);

        Mensaje::firstOrCreate([
            'id_grupo' => $grupo->id_grupo,
            'id_usuario' => $ana->id_usuario,
            'contenido' => '¿Quedamos el jueves para repasar integrales?',
        ], [
            'fecha_envio' => now(),
        ]);
        Mensaje::firstOrCreate([
            'id_grupo' => $grupo->id_grupo,
            'id_usuario' => $marta->id_usuario,
            'contenido' => 'Sí. Lleven la guía de la última clase.',
        ], [
            'fecha_envio' => now(),
        ]);

        Storage::disk('local')->put(
            'recursos/guia-algoritmos.pdf',
            "%PDF-1.4\n% Recurso de demostración Konex\nGuía de algoritmos UNIESPINAL\n%%EOF"
        );

        Recurso::updateOrCreate([
            'titulo' => 'Guía de algoritmos',
            'id_asignatura' => $algoritmos->id_asignatura,
        ], [
            'id_usuario' => $marta->id_usuario,
            'id_asignatura' => $algoritmos->id_asignatura,
            'descripcion' => 'Material verificado del semestre 2026-2.',
            'tipo_archivo' => 'PDF',
            'ruta_archivo' => 'recursos/guia-algoritmos.pdf',
            'verificado' => true,
            'semestre' => '2026-2',
        ]);

        $publicacion = Publicacion::updateOrCreate([
            'id_usuario' => $ana->id_usuario,
            'titulo' => 'Grupo para Bases de Datos',
        ], [
            'tipo' => 'busqueda_grupo',
            'contenido' => '¿Alguien arma grupo para el parcial de Bases de Datos? Tengo los talleres 1 al 4.',
            'fecha' => now()->subHours(2),
        ]);

        Publicacion::updateOrCreate([
            'id_usuario' => $director->id_usuario,
            'titulo' => 'Inscripciones para semilleros 2026-2',
        ], [
            'tipo' => 'aviso',
            'contenido' => 'La Dirección de Programa abrió la convocatoria para participar en los semilleros institucionales.',
            'fecha' => now()->subHours(5),
        ]);

        Comentario::firstOrCreate([
            'id_publicacion' => $publicacion->id_publicacion,
            'id_usuario' => $marta->id_usuario,
            'contenido' => 'Pueden usar la guía que subí en Recursos.',
        ], [
            'fecha' => now()->subHour(),
        ]);

        Reaccion::firstOrCreate([
            'id_publicacion' => $publicacion->id_publicacion,
            'id_usuario' => $ana->id_usuario,
            'tipo' => 'like',
        ]);

        Evento::updateOrCreate([
            'titulo' => 'Feria de prácticas',
        ], [
            'id_usuario' => $director->id_usuario,
            'descripcion' => 'Encuentro con empresas aliadas de UNIESPINAL.',
            'fecha' => now()->addDays(3)->setTime(16, 0),
            'lugar' => 'Auditorio central',
        ]);

        HoraSocial::updateOrCreate([
            'id_estudiante' => $ana->id_usuario,
            'actividad' => 'Apoyo en inducción de primer semestre',
        ], [
            'id_directivo' => $director->id_usuario,
            'horas' => 8.0,
            'estado' => 'aprobado',
        ]);

        HoraSocial::updateOrCreate([
            'id_estudiante' => $ana->id_usuario,
            'actividad' => 'Logística de la feria académica',
        ], [
            'id_directivo' => null,
            'horas' => 6.0,
            'estado' => 'pendiente',
        ]);

        Notificacion::updateOrCreate([
            'id_usuario' => $ana->id_usuario,
            'titulo' => 'Recurso nuevo',
        ], [
            'mensaje' => 'La docente Marta Ramírez publicó la guía de Algoritmos.',
            'leida' => false,
            'fecha' => now(),
        ]);

        Product::updateOrCreate(['name' => 'Guía de algoritmos'], [
            'description' => 'Material verificado del semestre 2026-2 de UNIESPINAL.',
            'price' => 0,
            'stock' => 40,
        ]);
        Product::updateOrCreate(['name' => 'Kit de prácticas'], [
            'description' => 'Insumos para la feria de prácticas institucionales.',
            'price' => 25000,
            'stock' => 12,
        ]);
    }
}

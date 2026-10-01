<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id('id_rol');
            $table->string('nombre', 30)->unique();
        });

        Schema::create('programas', function (Blueprint $table) {
            $table->id('id_programa');
            $table->string('nombre', 120);
            $table->string('facultad', 120)->nullable();
        });

        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->foreignId('id_rol')->constrained('roles', 'id_rol');
            $table->foreignId('id_programa')->nullable()->constrained('programas', 'id_programa');
            $table->string('nombre_completo', 150);
            $table->string('correo_institucional', 150)->unique();
            $table->string('contrasena', 255);
            $table->string('foto_perfil', 255)->nullable();
            $table->unsignedTinyInteger('semestre')->nullable();
            $table->text('bio')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('asignaturas', function (Blueprint $table) {
            $table->id('id_asignatura');
            $table->foreignId('id_programa')->constrained('programas', 'id_programa');
            $table->string('codigo', 20)->unique();
            $table->string('nombre', 120);
        });

        Schema::create('usuario_asignatura', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario');
            $table->foreignId('id_asignatura')->constrained('asignaturas', 'id_asignatura');
            $table->string('tipo', 20);
            $table->unique(['id_usuario', 'id_asignatura']);
        });

        Schema::create('grupos_estudio', function (Blueprint $table) {
            $table->id('id_grupo');
            $table->foreignId('id_asignatura')->constrained('asignaturas', 'id_asignatura');
            $table->foreignId('id_creador')->constrained('usuarios', 'id_usuario');
            $table->string('nombre', 120);
            $table->text('descripcion')->nullable();
            $table->string('codigo_union', 30)->unique();
            $table->unsignedInteger('cupo')->default(10);
            $table->string('horario', 80)->nullable();
        });

        Schema::create('miembro_grupo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_grupo')->constrained('grupos_estudio', 'id_grupo');
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario');
            $table->string('rol_grupo', 20)->default('miembro');
            $table->timestamp('fecha_union')->nullable();
            $table->unique(['id_grupo', 'id_usuario']);
        });

        Schema::create('mensajes', function (Blueprint $table) {
            $table->id('id_mensaje');
            $table->foreignId('id_grupo')->constrained('grupos_estudio', 'id_grupo');
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario');
            $table->text('contenido');
            $table->timestamp('fecha_envio')->nullable();
        });

        Schema::create('recursos', function (Blueprint $table) {
            $table->id('id_recurso');
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario');
            $table->foreignId('id_asignatura')->constrained('asignaturas', 'id_asignatura');
            $table->foreignId('id_grupo')->nullable()->constrained('grupos_estudio', 'id_grupo');
            $table->string('titulo', 150);
            $table->text('descripcion')->nullable();
            $table->string('tipo_archivo', 10);
            $table->string('ruta_archivo', 255);
            $table->boolean('verificado')->default(false);
            $table->string('semestre', 20)->nullable();
        });

        Schema::create('publicaciones', function (Blueprint $table) {
            $table->id('id_publicacion');
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario');
            $table->string('tipo', 30);
            $table->string('titulo', 150);
            $table->text('contenido');
            $table->timestamp('fecha')->nullable();
        });

        Schema::create('comentarios', function (Blueprint $table) {
            $table->id('id_comentario');
            $table->foreignId('id_publicacion')->constrained('publicaciones', 'id_publicacion')->cascadeOnDelete();
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario');
            $table->text('contenido');
            $table->timestamp('fecha')->nullable();
        });

        Schema::create('reacciones', function (Blueprint $table) {
            $table->id('id_reaccion');
            $table->foreignId('id_publicacion')->constrained('publicaciones', 'id_publicacion')->cascadeOnDelete();
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario');
            $table->string('tipo', 20);
            $table->unique(['id_publicacion', 'id_usuario', 'tipo']);
        });

        Schema::create('eventos', function (Blueprint $table) {
            $table->id('id_evento');
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario');
            $table->string('titulo', 150);
            $table->text('descripcion')->nullable();
            $table->timestamp('fecha')->nullable();
            $table->string('lugar', 150)->nullable();
        });

        Schema::create('horas_sociales', function (Blueprint $table) {
            $table->id('id_hora');
            $table->foreignId('id_estudiante')->constrained('usuarios', 'id_usuario');
            $table->foreignId('id_directivo')->nullable()->constrained('usuarios', 'id_usuario');
            $table->string('actividad', 150);
            $table->decimal('horas', 5, 1);
            $table->string('estado', 20)->default('pendiente');
            $table->string('evidencia', 255)->nullable();
        });

        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id('id_notificacion');
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario');
            $table->string('titulo', 120);
            $table->string('mensaje', 255);
            $table->boolean('leida')->default(false);
            $table->timestamp('fecha')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
        Schema::dropIfExists('horas_sociales');
        Schema::dropIfExists('eventos');
        Schema::dropIfExists('reacciones');
        Schema::dropIfExists('comentarios');
        Schema::dropIfExists('publicaciones');
        Schema::dropIfExists('recursos');
        Schema::dropIfExists('mensajes');
        Schema::dropIfExists('miembro_grupo');
        Schema::dropIfExists('grupos_estudio');
        Schema::dropIfExists('usuario_asignatura');
        Schema::dropIfExists('asignaturas');
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('programas');
        Schema::dropIfExists('roles');
    }
};

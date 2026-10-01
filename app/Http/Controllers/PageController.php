<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\Evento;
use App\Models\GrupoEstudio;
use App\Models\Mensaje;
use App\Models\MiembroGrupo;
use App\Models\Publicacion;
use App\Models\Recurso;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function landing(): View
    {
        return view('landing');
    }

    public function recuperar(): View
    {
        return view('auth.recuperar');
    }

    public function restaurar(): View
    {
        return view('auth.restaurar');
    }

    public function inicio(): View
    {
        return view('inicio', [
            'grupo' => GrupoEstudio::query()->with('asignatura')->withCount('miembros')->latest('id_grupo')->first(),
            'evento' => Evento::query()->orderBy('fecha')->first(),
            'recurso' => Recurso::query()->with('usuario')->latest('id_recurso')->first(),
            'publicaciones' => Publicacion::query()
                ->with(['usuario.rol', 'usuario.programa'])
                ->withCount(['comentarios', 'reacciones'])
                ->orderByDesc('fecha')
                ->get(),
        ]);
    }

    public function grupos(): View
    {
        return view('grupos.index', [
            'grupos' => GrupoEstudio::query()
                ->with('asignatura')
                ->withCount('miembros')
                ->orderBy('nombre')
                ->get(),
        ]);
    }

    public function unirseGrupo(): View
    {
        return view('grupos.unirse', [
            'asignaturas' => Asignatura::query()->orderBy('nombre')->get(),
        ]);
    }

    public function guardarUnirseGrupo(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'codigo_union' => 'required|string|max:30',
        ]);

        $grupo = GrupoEstudio::query()
            ->where('codigo_union', $validated['codigo_union'])
            ->first();

        if (! $grupo) {
            return back()->withErrors(['codigo_union' => 'No existe un grupo con ese código.']);
        }

        $yaEsMiembro = MiembroGrupo::query()
            ->where('id_grupo', $grupo->id_grupo)
            ->where('id_usuario', $request->user()->id_usuario)
            ->exists();

        if (! $yaEsMiembro) {
            if ($grupo->miembros()->count() >= $grupo->cupo) {
                return back()->withErrors(['codigo_union' => 'El grupo ya no tiene cupo.']);
            }

            MiembroGrupo::create([
                'id_grupo' => $grupo->id_grupo,
                'id_usuario' => $request->user()->id_usuario,
                'rol_grupo' => 'miembro',
                'fecha_union' => now(),
            ]);
        }

        return redirect()->route('grupos')->with('ok', 'Ya formas parte del grupo '.$grupo->nombre.'.');
    }

    public function recursos(): View
    {
        return view('recursos.index', [
            'recursos' => Recurso::query()
                ->with(['usuario', 'asignatura'])
                ->latest('id_recurso')
                ->get(),
        ]);
    }

    public function crearRecurso(): View
    {
        return view('recursos.crear', [
            'asignaturas' => Asignatura::query()->orderBy('nombre')->get(),
        ]);
    }

    public function guardarRecurso(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'titulo' => 'required|string|min:3|max:150',
            'id_asignatura' => 'required|exists:asignaturas,id_asignatura',
            'descripcion' => 'nullable|string|max:1000',
            'tipo_archivo' => 'required|in:PDF,DOC,PPT,IMG',
        ]);

        Recurso::create([
            ...$validated,
            'id_usuario' => $request->user()->id_usuario,
            'ruta_archivo' => 'recursos/'.str()->slug($validated['titulo']).'.'.strtolower($validated['tipo_archivo']),
            'verificado' => $request->user()->rol?->nombre === 'Docente',
            'semestre' => '2026-2',
        ]);

        return redirect()->route('recursos')->with('ok', 'Recurso publicado.');
    }

    public function crearPublicacion(): View
    {
        return view('publicaciones.crear');
    }

    public function guardarPublicacion(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tipo' => 'required|in:aviso,evento,busqueda_grupo',
            'titulo' => 'required|string|min:3|max:150',
            'contenido' => 'required|string|min:1|max:3000',
        ]);

        Publicacion::create([
            ...$validated,
            'id_usuario' => $request->user()->id_usuario,
            'fecha' => now(),
        ]);

        return redirect()->route('inicio')->with('ok', 'Publicación creada.');
    }

    public function chat(Request $request): View
    {
        $usuario = $request->user()->load('grupos.asignatura');
        $grupos = $usuario->grupos;
        $grupo = $grupos->firstWhere('id_grupo', $request->integer('grupo')) ?? $grupos->first();

        $mensajes = $grupo
            ? Mensaje::query()->with('usuario')->where('id_grupo', $grupo->id_grupo)->orderBy('fecha_envio')->get()
            : collect();

        return view('chat.index', compact('grupos', 'grupo', 'mensajes'));
    }

    public function guardarMensaje(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_grupo' => 'required|exists:grupos_estudio,id_grupo',
            'contenido' => 'required|string|min:1|max:2000',
        ]);

        $pertenece = MiembroGrupo::query()
            ->where('id_grupo', $validated['id_grupo'])
            ->where('id_usuario', $request->user()->id_usuario)
            ->exists();

        if (! $pertenece) {
            return back()->withErrors(['contenido' => 'Debes unirte al grupo para escribir.']);
        }

        Mensaje::create([
            'id_grupo' => $validated['id_grupo'],
            'id_usuario' => $request->user()->id_usuario,
            'contenido' => $validated['contenido'],
            'fecha_envio' => now(),
        ]);

        return redirect()->route('chat', ['grupo' => $validated['id_grupo']]);
    }

    public function perfil(Request $request): View
    {
        $usuario = $request->user()->load(['rol', 'programa', 'asignaturas', 'grupos', 'recursos']);

        return view('perfil.index', compact('usuario'));
    }

    public function editarPerfil(Request $request): View
    {
        return view('perfil.editar', [
            'usuario' => $request->user()->load('programa'),
        ]);
    }

    public function guardarPerfil(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre_completo' => 'required|string|min:3|max:150',
            'bio' => 'nullable|string|max:1000',
        ]);

        $request->user()->update($validated);

        return redirect()->route('perfil')->with('ok', 'Perfil actualizado.');
    }

    public function configuracion(): View
    {
        return view('configuracion');
    }
}

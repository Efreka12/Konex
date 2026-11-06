<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\Comentario;
use App\Models\Evento;
use App\Models\GrupoEstudio;
use App\Models\Mensaje;
use App\Models\MiembroGrupo;
use App\Models\Publicacion;
use App\Models\Reaccion;
use App\Models\Recurso;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PageController extends Controller
{
    public function landing(): View
    {
        return view('landing');
    }

    public function inicio(): View
    {
        return view('inicio', [
            'grupo' => GrupoEstudio::query()
                ->with('asignatura')
                ->withCount('miembros')
                ->whereHas('miembros', fn ($q) => $q->where('usuarios.id_usuario', auth()->id()))
                ->latest('id_grupo')
                ->first(),
            'evento' => Evento::query()
                ->whereHas('usuario.rol', fn ($q) => $q->where('nombre', 'Directivo'))
                ->where('fecha', '>=', now())
                ->orderBy('fecha')
                ->first(),
            'recurso' => Recurso::query()->with('usuario')->where('verificado', true)->latest('id_recurso')->first(),
            'publicaciones' => Publicacion::query()
                ->with(['usuario.rol', 'usuario.programa', 'comentarios.usuario'])
                ->withCount(['comentarios', 'reacciones'])
                ->orderByDesc('fecha')
                ->get(),
        ]);
    }

    public function grupos(): View
    {
        $usuario = request()->user();

        return view('grupos.index', [
            'grupos' => GrupoEstudio::query()
                ->with('asignatura')
                ->withCount('miembros')
                ->orderBy('nombre')
                ->get(),
            'gruposPropios' => $usuario->grupos()->pluck('grupos_estudio.id_grupo'),
        ]);
    }

    public function crearGrupo(): View
    {
        return view('grupos.crear', [
            'asignaturas' => Asignatura::query()->orderBy('nombre')->get(),
        ]);
    }

    public function guardarGrupo(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_asignatura' => 'required|exists:asignaturas,id_asignatura',
            'nombre' => 'required|string|min:3|max:120',
            'descripcion' => 'nullable|string|max:1000',
            'cupo' => 'required|integer|min:2|max:100',
            'horario' => 'nullable|string|max:80',
        ]);

        $grupo = GrupoEstudio::create([
            ...$validated,
            'id_creador' => $request->user()->id_usuario,
            'codigo_union' => Str::upper(Str::random(8)),
        ]);

        MiembroGrupo::create([
            'id_grupo' => $grupo->id_grupo,
            'id_usuario' => $request->user()->id_usuario,
            'rol_grupo' => 'admin',
            'fecha_union' => now(),
        ]);

        return redirect()->route('grupos')->with('ok', 'Grupo creado. Comparte el código '.$grupo->codigo_union.'.');
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
        $consulta = Recurso::query()->with(['usuario', 'asignatura'])->latest('id_recurso');
        if ($asignatura = request()->integer('asignatura')) {
            $consulta->where('id_asignatura', $asignatura);
        }
        if ($tipo = request()->string('tipo')->toString()) {
            $consulta->where('tipo_archivo', $tipo);
        }

        return view('recursos.index', [
            'recursos' => $consulta->get(),
            'asignaturas' => Asignatura::query()->orderBy('nombre')->get(),
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
            'archivo' => 'required|file|max:10240|mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png',
        ]);

        $ruta = $request->file('archivo')->store('recursos', 'local');

        Recurso::create([
            'titulo' => $validated['titulo'],
            'id_asignatura' => $validated['id_asignatura'],
            'descripcion' => $validated['descripcion'] ?? null,
            'tipo_archivo' => $validated['tipo_archivo'],
            'id_usuario' => $request->user()->id_usuario,
            'ruta_archivo' => $ruta,
            'verificado' => $request->user()->rol?->nombre === 'Docente',
            'semestre' => '2026-2',
        ]);

        return redirect()->route('recursos')->with('ok', 'Recurso publicado.');
    }

    public function descargarRecurso(Request $request, Recurso $recurso): StreamedResponse
    {
        abort_unless(Storage::disk('local')->exists($recurso->ruta_archivo), 404, 'El archivo no está disponible.');

        return Storage::disk('local')->download(
            $recurso->ruta_archivo,
            basename($recurso->ruta_archivo)
        );
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

    public function reaccionar(Request $request, Publicacion $publicacion): RedirectResponse
    {
        $reaccion = Reaccion::query()
            ->where('id_publicacion', $publicacion->id_publicacion)
            ->where('id_usuario', $request->user()->id_usuario)
            ->where('tipo', 'like')
            ->first();

        $reaccion
            ? $reaccion->delete()
            : Reaccion::create([
                'id_publicacion' => $publicacion->id_publicacion,
                'id_usuario' => $request->user()->id_usuario,
                'tipo' => 'like',
            ]);

        return back()->with('ok', $reaccion ? 'Reacción retirada.' : 'Reacción registrada.');
    }

    public function comentar(Request $request, Publicacion $publicacion): RedirectResponse
    {
        $validated = $request->validate([
            'contenido' => 'required|string|min:2|max:500',
        ]);

        Comentario::create([
            'id_publicacion' => $publicacion->id_publicacion,
            'id_usuario' => $request->user()->id_usuario,
            'contenido' => $validated['contenido'],
            'fecha' => now(),
        ]);

        return back()->with('ok', 'Comentario publicado.');
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

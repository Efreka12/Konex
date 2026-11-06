<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\HoraSocial;
use App\Models\Publicacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InstitucionalController extends Controller
{
    public function index(): View
    {
        return view('institucional.index', [
            'eventos' => Evento::query()
                ->with('usuario')
                ->whereHas('usuario.rol', fn ($q) => $q->where('nombre', 'Directivo'))
                ->orderBy('fecha')
                ->get(),
            'noticias' => Publicacion::query()
                ->with('usuario')
                ->where('tipo', 'aviso')
                ->whereHas('usuario.rol', fn ($q) => $q->where('nombre', 'Directivo'))
                ->latest('fecha')
                ->get(),
        ]);
    }

    public function guardarEvento(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'titulo' => 'required|string|min:3|max:150',
            'descripcion' => 'nullable|string|max:2000',
            'fecha' => 'required|date',
            'lugar' => 'required|string|max:150',
        ]);

        Evento::create([
            ...$validated,
            'id_usuario' => $request->user()->id_usuario,
        ]);

        return back()->with('ok', 'Evento institucional publicado.');
    }

    public function guardarNoticia(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'titulo' => 'required|string|min:3|max:150',
            'contenido' => 'required|string|min:3|max:3000',
        ]);

        Publicacion::create([
            ...$validated,
            'tipo' => 'aviso',
            'id_usuario' => $request->user()->id_usuario,
            'fecha' => now(),
        ]);

        return back()->with('ok', 'Noticia institucional publicada.');
    }

    public function horas(Request $request): View
    {
        $usuario = $request->user();
        $consulta = HoraSocial::query()->with(['estudiante', 'directivo'])->latest('id_hora');

        if ($usuario->tieneRol('Estudiante')) {
            $consulta->where('id_estudiante', $usuario->id_usuario);
        }

        return view('horas-sociales.index', [
            'solicitudes' => $consulta->get(),
        ]);
    }

    public function guardarHoras(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'actividad' => 'required|string|min:3|max:150',
            'horas' => 'required|numeric|min:0.5|max:999.9',
            'evidencia' => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png',
        ]);

        HoraSocial::create([
            'id_estudiante' => $request->user()->id_usuario,
            'actividad' => $validated['actividad'],
            'horas' => $validated['horas'],
            'estado' => 'pendiente',
            'evidencia' => $request->file('evidencia')?->store('evidencias', 'local'),
        ]);

        return back()->with('ok', 'Solicitud enviada para revisión.');
    }

    public function actualizarHoras(Request $request, HoraSocial $horaSocial): RedirectResponse
    {
        $validated = $request->validate([
            'estado' => 'required|in:aprobado,rechazado',
        ]);

        $horaSocial->update([
            'estado' => $validated['estado'],
            'id_directivo' => $request->user()->id_usuario,
        ]);

        return back()->with('ok', 'Solicitud actualizada.');
    }

    public function descargarEvidencia(Request $request, HoraSocial $horaSocial): StreamedResponse
    {
        abort_unless(
            $request->user()->tieneRol('Directivo')
            || $horaSocial->id_estudiante === $request->user()->id_usuario,
            403,
            'No tienes permiso para consultar esta evidencia.'
        );
        abort_unless(
            $horaSocial->evidencia && Storage::disk('local')->exists($horaSocial->evidencia),
            404,
            'La evidencia no está disponible.'
        );

        return Storage::disk('local')->download($horaSocial->evidencia);
    }
}

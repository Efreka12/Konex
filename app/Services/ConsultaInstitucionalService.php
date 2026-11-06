<?php

namespace App\Services;

use App\Models\Evento;
use App\Models\HoraSocial;
use App\Models\Publicacion;
use App\Models\Recurso;
use App\Models\Usuario;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ConsultaInstitucionalService
{
    public function responder(Usuario $usuario, string $pregunta): array
    {
        $consulta = Str::of($pregunta)->lower()->ascii()->toString();

        if ($this->incluye($consulta, ['evento', 'feria', 'calendario', 'actividad institucional'])) {
            return $this->eventos();
        }

        if ($this->incluye($consulta, ['noticia', 'aviso', 'informacion institucional', 'novedad'])) {
            return $this->noticias();
        }

        if ($this->incluye($consulta, ['hora social', 'horas sociales', 'solicitud', 'tramite'])) {
            return $this->horas($usuario);
        }

        if ($this->incluye($consulta, ['recurso', 'guia', 'taller', 'apunte', 'material'])) {
            return $this->recursos();
        }

        return $this->sinDato(
            'No tengo ese dato en Konex. Puedo consultar eventos, noticias, recursos verificados y el estado de horas sociales.'
        );
    }

    private function eventos(): array
    {
        $eventos = Evento::query()
            ->whereHas('usuario.rol', fn ($q) => $q->where('nombre', 'Directivo'))
            ->orderBy('fecha')
            ->limit(5)
            ->get();
        if ($eventos->isEmpty()) {
            return $this->sinDato('No hay eventos institucionales cargados en Konex.');
        }

        $texto = $eventos->map(fn (Evento $evento) => sprintf(
            '%s: %s en %s.',
            $evento->titulo,
            $evento->fecha?->format('d/m/Y H:i') ?? 'fecha por confirmar',
            $evento->lugar ?: 'lugar por confirmar'
        ))->implode(' ');

        return $this->respuesta('Estos son los eventos registrados: '.$texto, $eventos, 'evento', 'id_evento');
    }

    private function noticias(): array
    {
        $noticias = Publicacion::query()
            ->where('tipo', 'aviso')
            ->whereHas('usuario.rol', fn ($q) => $q->where('nombre', 'Directivo'))
            ->latest('fecha')
            ->limit(5)
            ->get();
        if ($noticias->isEmpty()) {
            return $this->sinDato('No hay noticias o avisos institucionales cargados en Konex.');
        }

        $texto = $noticias->map(
            fn (Publicacion $noticia) => $noticia->titulo.': '.$noticia->contenido
        )->implode(' ');

        return $this->respuesta('Noticias registradas en Konex: '.$texto, $noticias, 'noticia', 'id_publicacion');
    }

    private function recursos(): array
    {
        $recursos = Recurso::query()->with('asignatura')->where('verificado', true)->latest('id_recurso')->limit(5)->get();
        if ($recursos->isEmpty()) {
            return $this->sinDato('No hay recursos académicos verificados cargados en Konex.');
        }

        $texto = $recursos->map(fn (Recurso $recurso) => sprintf(
            '%s (%s, %s).',
            $recurso->titulo,
            $recurso->asignatura?->nombre ?? 'sin asignatura',
            $recurso->tipo_archivo
        ))->implode(' ');

        return $this->respuesta('Recursos verificados disponibles: '.$texto, $recursos, 'recurso', 'id_recurso');
    }

    private function horas(Usuario $usuario): array
    {
        $consulta = HoraSocial::query()->with('estudiante')->latest('id_hora');
        if ($usuario->tieneRol('Estudiante')) {
            $consulta->where('id_estudiante', $usuario->id_usuario);
        } elseif (! $usuario->tieneRol('Directivo')) {
            return $this->sinDato('El seguimiento de horas sociales está disponible para estudiantes y directivos.');
        }

        $solicitudes = $consulta->limit(10)->get();
        if ($solicitudes->isEmpty()) {
            return $this->sinDato('No hay solicitudes de horas sociales registradas para esta consulta.');
        }

        $texto = $solicitudes->map(fn (HoraSocial $hora) => sprintf(
            '%s: %s horas, estado %s%s.',
            $hora->actividad,
            $hora->horas,
            $hora->estado,
            $usuario->tieneRol('Directivo') ? ', estudiante '.$hora->estudiante->nombre_completo : ''
        ))->implode(' ');

        return $this->respuesta('Información de horas sociales: '.$texto, $solicitudes, 'hora_social', 'id_hora');
    }

    private function respuesta(string $texto, Collection $filas, string $tipo, string $clave): array
    {
        return [
            'respuesta' => $texto,
            'fuentes' => $filas->map(fn ($fila) => [
                'tipo' => $tipo,
                'id' => $fila->{$clave},
                'titulo' => $fila->titulo ?? $fila->actividad,
            ])->values()->all(),
        ];
    }

    private function sinDato(string $texto): array
    {
        return ['respuesta' => $texto, 'fuentes' => []];
    }

    private function incluye(string $consulta, array $terminos): bool
    {
        return collect($terminos)->contains(fn (string $termino) => str_contains($consulta, $termino));
    }
}

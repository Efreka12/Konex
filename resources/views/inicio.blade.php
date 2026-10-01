@extends('layouts.panel')

@section('title', 'Inicio')

@section('content')
<div class="kx-top">
    <div>
        <p class="text-secondary mb-1">UNIESPINAL</p>
        <h1 class="h3 fw-bold mb-0">Inicio · Konex</h1>
    </div>
    <a href="{{ route('publicaciones.crear') }}" class="kx-btn">Nueva publicación</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="kx-card kx-tile">
            <span class="kx-chip">Hoy</span>
            <h2 class="h5 mt-3">{{ $grupo->nombre ?? 'Aún no hay grupos' }}</h2>
            <p class="text-secondary mb-0">
                @if ($grupo)
                    {{ $grupo->miembros_count }} compañeros en {{ $grupo->asignatura->nombre ?? 'el grupo de estudio' }}.
                @else
                    Únete a un grupo para ver actividad.
                @endif
            </p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="kx-card kx-tile">
            <span class="kx-chip">Evento</span>
            <h2 class="h5 mt-3">{{ $evento->titulo ?? 'Sin eventos' }}</h2>
            <p class="text-secondary mb-0">
                @if ($evento)
                    {{ $evento->fecha?->translatedFormat('l g:i a') }} · {{ $evento->lugar }}
                @else
                    Cuando un directivo publique un evento, aparecerá aquí.
                @endif
            </p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="kx-card kx-tile">
            <span class="kx-chip">Recurso</span>
            <h2 class="h5 mt-3">{{ $recurso->titulo ?? 'Sin recursos' }}</h2>
            <p class="text-secondary mb-0">
                @if ($recurso)
                    Subido por {{ $recurso->usuario->nombre_completo }} · {{ $recurso->tipo_archivo }}{{ $recurso->verificado ? ' verificado' : '' }}.
                @else
                    Comparte el primer apunte en Recursos.
                @endif
            </p>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h5 mb-0">Publicaciones recientes</h2>
    <a href="{{ route('recursos') }}">Ver apuntes</a>
</div>

@forelse ($publicaciones as $publicacion)
<article class="kx-card kx-post mb-3">
    <div class="d-flex gap-3 mb-3">
        <div class="kx-avatar d-grid place-items-center text-center pt-2">{{ $publicacion->usuario->iniciales() }}</div>
        <div>
            <strong>{{ $publicacion->usuario->nombre_completo }}</strong>
            <div class="text-secondary small">
                {{ $publicacion->usuario->rol->nombre ?? 'Usuario' }}
                @if ($publicacion->usuario->programa)
                    · {{ $publicacion->usuario->programa->nombre }}
                @endif
                · {{ $publicacion->fecha?->diffForHumans() }}
            </div>
        </div>
    </div>
    <p>{{ $publicacion->contenido }}</p>
    <div class="d-flex gap-3 text-secondary small">
        <span><i class="bi bi-heart"></i> {{ $publicacion->reacciones_count }}</span>
        <span><i class="bi bi-chat"></i> {{ $publicacion->comentarios_count }}</span>
    </div>
</article>
@empty
    <div class="kx-card p-4 text-secondary">Todavía no hay publicaciones. Crea la primera.</div>
@endforelse
@endsection

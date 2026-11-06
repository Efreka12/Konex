@extends('layouts.panel')

@section('title', 'Información institucional')

@section('content')
<div class="kx-top">
    <div>
        <h1 class="h3 fw-bold mb-0">Información institucional</h1>
        <p class="text-secondary mb-0">Eventos y noticias publicados en Konex.</p>
    </div>
</div>

@if (auth()->user()->tieneRol('Directivo'))
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="kx-card p-4 h-100">
            <h2 class="h5">Publicar evento</h2>
            <form method="POST" action="{{ route('institucional.eventos.store') }}">
                @csrf
                <input class="kx-input mb-2" name="titulo" value="{{ old('titulo') }}" placeholder="Título del evento" required>
                <textarea class="kx-textarea mb-2" name="descripcion" rows="2" placeholder="Descripción">{{ old('descripcion') }}</textarea>
                <input class="kx-input mb-2" type="datetime-local" name="fecha" value="{{ old('fecha') }}" required>
                <input class="kx-input mb-3" name="lugar" value="{{ old('lugar') }}" placeholder="Lugar" required>
                <button class="kx-btn" type="submit">Publicar evento</button>
            </form>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="kx-card p-4 h-100">
            <h2 class="h5">Publicar noticia</h2>
            <form method="POST" action="{{ route('institucional.noticias.store') }}">
                @csrf
                <input class="kx-input mb-2" name="titulo" value="{{ old('titulo') }}" placeholder="Título de la noticia" required>
                <textarea class="kx-textarea mb-3" name="contenido" rows="5" placeholder="Información institucional" required>{{ old('contenido') }}</textarea>
                <button class="kx-btn" type="submit">Publicar noticia</button>
            </form>
        </div>
    </div>
</div>
@endif

<div class="row g-3">
    <div class="col-lg-6">
        <h2 class="h5 mb-3">Próximos eventos</h2>
        @forelse ($eventos as $evento)
            <article class="kx-card p-3 mb-3">
                <span class="kx-chip">Evento</span>
                <h3 class="h5 mt-2">{{ $evento->titulo }}</h3>
                <p>{{ $evento->descripcion }}</p>
                <p class="text-secondary small mb-0"><i class="bi bi-calendar-event"></i> {{ $evento->fecha?->format('d/m/Y H:i') }} · {{ $evento->lugar }}</p>
            </article>
        @empty
            <div class="kx-card p-4 text-secondary">No hay eventos institucionales registrados.</div>
        @endforelse
    </div>
    <div class="col-lg-6">
        <h2 class="h5 mb-3">Noticias y avisos</h2>
        @forelse ($noticias as $noticia)
            <article class="kx-card p-3 mb-3">
                <span class="kx-chip">Noticia</span>
                <h3 class="h5 mt-2">{{ $noticia->titulo }}</h3>
                <p>{{ $noticia->contenido }}</p>
                <p class="text-secondary small mb-0">{{ $noticia->usuario->nombre_completo }} · {{ $noticia->fecha?->format('d/m/Y') }}</p>
            </article>
        @empty
            <div class="kx-card p-4 text-secondary">No hay noticias institucionales registradas.</div>
        @endforelse
    </div>
</div>
@endsection

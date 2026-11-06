@extends('layouts.panel')

@section('title', 'Recursos')

@section('content')
<div class="kx-top">
    <div>
        <h1 class="h3 fw-bold mb-0">Recursos académicos</h1>
        <p class="text-secondary mb-0">Apuntes, guías y talleres clasificados por asignatura.</p>
    </div>
    <a href="{{ route('recursos.crear') }}" class="kx-btn">Añadir recurso</a>
</div>

<form method="GET" class="kx-card p-3 mb-3 row g-2 align-items-end">
    <div class="col-md-6">
        <label class="kx-label">Asignatura</label>
        <select class="kx-select" name="asignatura">
            <option value="">Todas</option>
            @foreach ($asignaturas as $asignatura)
                <option value="{{ $asignatura->id_asignatura }}" @selected(request('asignatura') == $asignatura->id_asignatura)>{{ $asignatura->nombre }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="kx-label">Tipo</label>
        <select class="kx-select" name="tipo">
            <option value="">Todos</option>
            @foreach (['PDF', 'DOC', 'PPT', 'IMG'] as $tipo)
                <option value="{{ $tipo }}" @selected(request('tipo') === $tipo)>{{ $tipo }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3"><button class="kx-btn w-100" type="submit">Filtrar</button></div>
</form>

<div class="row g-3">
    @forelse ($recursos as $recurso)
    <div class="col-md-6">
        <div class="kx-card kx-tile">
            <span class="kx-chip">{{ $recurso->tipo_archivo }}</span>
            <h2 class="h5 mt-3">{{ $recurso->titulo }}</h2>
            <p class="text-secondary mb-2">
                {{ $recurso->usuario->nombre_completo }} · {{ $recurso->asignatura->nombre }}
                @if ($recurso->verificado) · verificado @endif
            </p>
            <div class="d-flex gap-2 align-items-center flex-wrap">
                <span class="kx-chip">{{ $recurso->semestre }}</span>
                <a class="kx-btn kx-btn-ghost" href="{{ route('recursos.descargar', $recurso) }}"><i class="bi bi-download"></i> Descargar</a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="kx-card p-4 text-secondary">No hay recursos. Publica el primero.</div>
    </div>
    @endforelse
</div>
@endsection

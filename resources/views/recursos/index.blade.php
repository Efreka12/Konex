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
            <span class="kx-btn kx-btn-ghost">{{ $recurso->semestre }}</span>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="kx-card p-4 text-secondary">No hay recursos. Publica el primero.</div>
    </div>
    @endforelse
</div>
@endsection

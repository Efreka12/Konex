@extends('layouts.panel')

@section('title', 'Grupos')

@section('content')
<div class="kx-top">
    <div>
        <h1 class="h3 fw-bold mb-0">Grupos de estudio</h1>
        <p class="text-secondary mb-0">Encuentra compañeros de tu asignatura.</p>
    </div>
    <a href="{{ route('grupos.unirse') }}" class="kx-btn">Unirse a un grupo</a>
</div>

<div class="row g-3">
    @forelse ($grupos as $grupo)
    <div class="col-md-6">
        <div class="kx-card kx-tile h-100">
            <span class="kx-chip">{{ $grupo->asignatura->nombre }}</span>
            <h2 class="h5 mt-3">{{ $grupo->nombre }}</h2>
            <p class="text-secondary">{{ $grupo->miembros_count }} integrantes · código {{ $grupo->codigo_union }}</p>
            <a href="{{ route('chat', ['grupo' => $grupo->id_grupo]) }}" class="kx-btn kx-btn-ghost">Abrir chat</a>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="kx-card p-4 text-secondary">No hay grupos todavía. Únete con un código.</div>
    </div>
    @endforelse
</div>
@endsection

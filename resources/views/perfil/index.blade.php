@extends('layouts.panel')

@section('title', 'Perfil')

@section('content')
<div class="kx-card p-4 mb-4 text-center">
    <div class="kx-avatar mx-auto mb-3" style="width: 92px; height: 92px; font-size: 1.6rem; display: grid; place-items: center;">{{ $usuario->iniciales() }}</div>
    <h1 class="h3 fw-bold mb-1">{{ $usuario->nombre_completo }}</h1>
    <p class="text-secondary mb-2">{{ $usuario->rol->nombre ?? 'Usuario' }}@if($usuario->programa) · {{ $usuario->programa->nombre }}@endif</p>
    <span class="kx-chip">UNIESPINAL</span>
    @if ($usuario->bio)
        <p class="text-secondary mt-3 mb-0">{{ $usuario->bio }}</p>
    @endif
    <div class="mt-4 d-flex justify-content-center gap-2">
        <a href="{{ route('perfil.editar') }}" class="kx-btn">Editar perfil</a>
        <a href="{{ route('configuracion') }}" class="kx-btn kx-btn-ghost">Configuración</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="kx-card kx-tile">
            <h2 class="h6 text-secondary">Asignaturas</h2>
            <p class="mb-0">
                {{ $usuario->asignaturas->pluck('nombre')->filter()->join(' · ') ?: 'Aún no tienes asignaturas' }}
            </p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="kx-card kx-tile">
            <h2 class="h6 text-secondary">Actividad</h2>
            <p class="mb-0">{{ $usuario->grupos->count() }} grupos · {{ $usuario->recursos->count() }} recursos compartidos</p>
        </div>
    </div>
</div>
@endsection

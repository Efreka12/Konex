@extends('layouts.panel')

@section('title', 'Perfil')

@section('content')
<div class="kx-card p-4 mb-4 text-center">
    <div class="kx-avatar mx-auto mb-3" style="width: 92px; height: 92px; font-size: 1.6rem; display: grid; place-items: center;">AT</div>
    <h1 class="h3 fw-bold mb-1">Ana Torres</h1>
    <p class="text-secondary mb-2">Estudiante · Ingeniería de Sistemas</p>
    <span class="kx-chip">UNIESPINAL</span>
    <div class="mt-4 d-flex justify-content-center gap-2">
        <a href="{{ route('perfil.editar') }}" class="kx-btn">Editar perfil</a>
        <a href="{{ route('configuracion') }}" class="kx-btn kx-btn-ghost">Configuración</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="kx-card kx-tile">
            <h2 class="h6 text-secondary">Asignaturas</h2>
            <p class="mb-0">Bases de Datos · Cálculo II · Algoritmos</p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="kx-card kx-tile">
            <h2 class="h6 text-secondary">Actividad</h2>
            <p class="mb-0">3 grupos · 5 recursos compartidos</p>
        </div>
    </div>
</div>
@endsection

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
    <div class="col-md-6">
        <div class="kx-card kx-tile h-100">
            <span class="kx-chip">Bases de Datos</span>
            <h2 class="h5 mt-3">Parcial 2 · Squad 4</h2>
            <p class="text-secondary">8 integrantes · se reúnen los martes.</p>
            <a href="{{ route('chat') }}" class="kx-btn kx-btn-ghost">Abrir chat</a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="kx-card kx-tile h-100">
            <span class="kx-chip">Cálculo II</span>
            <h2 class="h5 mt-3">Repaso de integrales</h2>
            <p class="text-secondary">12 integrantes · material del semestre anterior.</p>
            <a href="{{ route('chat') }}" class="kx-btn kx-btn-ghost">Abrir chat</a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="kx-card kx-tile h-100">
            <span class="kx-chip">Algoritmos</span>
            <h2 class="h5 mt-3">Taller semanal</h2>
            <p class="text-secondary">5 integrantes · cupos disponibles.</p>
            <a href="{{ route('grupos.unirse') }}" class="kx-btn">Unirme</a>
        </div>
    </div>
</div>
@endsection

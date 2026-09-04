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

<div class="kx-card p-3 mb-3">
    <input class="kx-input" type="search" placeholder="Buscar por asignatura, taller o docente">
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="kx-card kx-tile">
            <span class="kx-chip">PDF</span>
            <h2 class="h5 mt-3">Guía de algoritmos</h2>
            <p class="text-secondary mb-2">Prof. Ramírez · Semestre 2026-2</p>
            <button class="kx-btn kx-btn-ghost" type="button">Descargar</button>
        </div>
    </div>
    <div class="col-md-6">
        <div class="kx-card kx-tile">
            <span class="kx-chip">DOC</span>
            <h2 class="h5 mt-3">Talleres 1-4 Bases de Datos</h2>
            <p class="text-secondary mb-2">Ana Torres · verificado</p>
            <button class="kx-btn kx-btn-ghost" type="button">Descargar</button>
        </div>
    </div>
    <div class="col-md-6">
        <div class="kx-card kx-tile">
            <span class="kx-chip">PDF</span>
            <h2 class="h5 mt-3">Apuntes de Cálculo II</h2>
            <p class="text-secondary mb-2">Grupo Repaso · semestre anterior</p>
            <button class="kx-btn kx-btn-ghost" type="button">Descargar</button>
        </div>
    </div>
</div>
@endsection

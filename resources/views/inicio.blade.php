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
            <h2 class="h5 mt-3">Foro de Cálculo II</h2>
            <p class="text-secondary mb-0">12 compañeros activos en el grupo de estudio.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="kx-card kx-tile">
            <span class="kx-chip">Evento</span>
            <h2 class="h5 mt-3">Feria de prácticas</h2>
            <p class="text-secondary mb-0">Jueves 4:00 p.m. · Auditorio central.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="kx-card kx-tile">
            <span class="kx-chip">Recurso</span>
            <h2 class="h5 mt-3">Guía de algoritmos</h2>
            <p class="text-secondary mb-0">Subida por la prof. Ramírez · PDF verificado.</p>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h5 mb-0">Publicaciones recientes</h2>
    <a href="{{ route('recursos') }}">Ver apuntes</a>
</div>

<article class="kx-card kx-post mb-3">
    <div class="d-flex gap-3 mb-3">
        <div class="kx-avatar d-grid place-items-center text-center pt-2">AT</div>
        <div>
            <strong>Ana Torres</strong>
            <div class="text-secondary small">Ingeniería de Sistemas · hace 2 h</div>
        </div>
    </div>
    <p>¿Alguien arma grupo para el parcial de Bases de Datos? Tengo los talleres 1 al 4.</p>
    <div class="d-flex gap-3 text-secondary small">
        <span><i class="bi bi-heart"></i> 18</span>
        <span><i class="bi bi-chat"></i> 6</span>
        <span><i class="bi bi-bookmark"></i> Guardar</span>
    </div>
</article>

<article class="kx-card kx-post">
    <div class="d-flex gap-3 mb-3">
        <div class="kx-avatar d-grid text-center pt-2">MR</div>
        <div>
            <strong>Prof. Marta Ramírez</strong>
            <div class="text-secondary small">Docente · hace 5 h</div>
        </div>
    </div>
    <p>Ya está disponible la guía de Algoritmos en Recursos, carpeta Semestre 2026-2.</p>
    <a href="{{ route('recursos') }}" class="kx-chip">Ver archivo</a>
</article>
@endsection

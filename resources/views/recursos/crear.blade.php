@extends('layouts.panel')

@section('title', 'Añadir recurso')

@section('content')
<div class="kx-top">
    <div>
        <h1 class="h3 fw-bold mb-0">Añadir recurso</h1>
        <p class="text-secondary mb-0">Comparte un apunte, guía o taller por asignatura.</p>
    </div>
</div>

<div class="kx-card p-4" style="max-width: 640px;">
    <form data-kx-submit data-kx-next="{{ route('recursos') }}">
        <div class="mb-3">
            <label class="kx-label">Título</label>
            <input class="kx-input" type="text" placeholder="Guía de algoritmos" required>
        </div>
        <div class="mb-3">
            <label class="kx-label">Asignatura</label>
            <select class="kx-select" required>
                <option>Algoritmos</option>
                <option>Bases de Datos</option>
                <option>Cálculo II</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="kx-label">Descripción</label>
            <textarea class="kx-textarea" rows="4" placeholder="Qué contiene el archivo"></textarea>
        </div>
        <div class="mb-3">
            <label class="kx-label">Archivo</label>
            <input class="kx-input" type="file">
        </div>
        <button class="kx-btn" type="submit">Publicar recurso</button>
        <a href="{{ route('recursos') }}" class="ms-3">Cancelar</a>
    </form>
</div>
@endsection

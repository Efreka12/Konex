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
    <form method="POST" action="{{ route('recursos.store') }}">
        @csrf
        <div class="mb-3">
            <label class="kx-label">Título</label>
            <input class="kx-input" name="titulo" type="text" value="{{ old('titulo') }}" placeholder="Guía de algoritmos" required>
        </div>
        <div class="mb-3">
            <label class="kx-label">Asignatura</label>
            <select class="kx-select" name="id_asignatura" required>
                @foreach ($asignaturas as $asignatura)
                    <option value="{{ $asignatura->id_asignatura }}" @selected(old('id_asignatura') == $asignatura->id_asignatura)>{{ $asignatura->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="kx-label">Tipo</label>
            <select class="kx-select" name="tipo_archivo" required>
                <option value="PDF">PDF</option>
                <option value="DOC">DOC</option>
                <option value="PPT">PPT</option>
                <option value="IMG">IMG</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="kx-label">Descripción</label>
            <textarea class="kx-textarea" name="descripcion" rows="4" placeholder="Qué contiene el archivo">{{ old('descripcion') }}</textarea>
        </div>
        <button class="kx-btn" type="submit">Publicar recurso</button>
        <a href="{{ route('recursos') }}" class="ms-3">Cancelar</a>
    </form>
</div>
@endsection

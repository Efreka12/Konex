@extends('layouts.panel')

@section('title', 'Nueva publicación')

@section('content')
<div class="kx-top">
    <div>
        <h1 class="h3 fw-bold mb-0">Nueva publicación</h1>
        <p class="text-secondary mb-0">Comparte un aviso, duda o convocatoria con tu red.</p>
    </div>
</div>

<div class="kx-card p-4" style="max-width: 640px;">
    <form method="POST" action="{{ route('publicaciones.store') }}">
        @csrf
        <div class="mb-3">
            <label class="kx-label">Tipo</label>
            <select class="kx-select" name="tipo">
                <option value="aviso">Aviso académico</option>
                <option value="evento">Evento</option>
                <option value="busqueda_grupo">Búsqueda de grupo</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="kx-label">Título</label>
            <input class="kx-input" name="titulo" type="text" value="{{ old('titulo') }}" required>
        </div>
        <div class="mb-3">
            <label class="kx-label">Contenido</label>
            <textarea class="kx-textarea" name="contenido" rows="5" required>{{ old('contenido') }}</textarea>
        </div>
        <button class="kx-btn" type="submit">Publicar</button>
        <a href="{{ route('inicio') }}" class="ms-3">Cancelar</a>
    </form>
</div>
@endsection

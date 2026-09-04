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
    <form data-kx-submit data-kx-next="{{ route('inicio') }}">
        <div class="mb-3">
            <label class="kx-label">Tipo</label>
            <select class="kx-select">
                <option>Aviso académico</option>
                <option>Evento</option>
                <option>Búsqueda de grupo</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="kx-label">Título</label>
            <input class="kx-input" type="text" required>
        </div>
        <div class="mb-3">
            <label class="kx-label">Contenido</label>
            <textarea class="kx-textarea" rows="5" required></textarea>
        </div>
        <button class="kx-btn" type="submit">Publicar</button>
        <a href="{{ route('inicio') }}" class="ms-3">Cancelar</a>
    </form>
</div>
@endsection

@extends('layouts.panel')

@section('title', 'Unirse a un grupo')

@section('content')
<div class="kx-top">
    <div>
        <h1 class="h3 fw-bold mb-0">Unirse a un grupo</h1>
        <p class="text-secondary mb-0">Usa el código que te compartió un compañero o un docente.</p>
    </div>
</div>

<div class="kx-card p-4" style="max-width: 520px;">
    <form data-kx-submit data-kx-next="{{ route('grupos') }}">
        <div class="mb-3">
            <label class="kx-label">Código del grupo</label>
            <input class="kx-input" type="text" placeholder="KX-BD-204" required>
        </div>
        <div class="mb-3">
            <label class="kx-label">Asignatura</label>
            <select class="kx-select">
                <option>Bases de Datos</option>
                <option>Cálculo II</option>
                <option>Algoritmos</option>
            </select>
        </div>
        <button class="kx-btn" type="submit">Unirme ahora</button>
        <a href="{{ route('grupos') }}" class="ms-3">Cancelar</a>
    </form>
</div>
@endsection

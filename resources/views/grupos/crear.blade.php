@extends('layouts.panel')

@section('title', 'Crear grupo')

@section('content')
<div class="kx-top">
    <div>
        <h1 class="h3 fw-bold mb-0">Crear grupo de estudio</h1>
        <p class="text-secondary mb-0">Organiza el acompañamiento de una asignatura.</p>
    </div>
</div>

<div class="kx-card p-4" style="max-width: 680px;">
    <form method="POST" action="{{ route('grupos.store') }}">
        @csrf
        <div class="mb-3">
            <label class="kx-label">Nombre</label>
            <input class="kx-input" name="nombre" value="{{ old('nombre') }}" required maxlength="120">
        </div>
        <div class="mb-3">
            <label class="kx-label">Asignatura</label>
            <select class="kx-select" name="id_asignatura" required>
                @foreach ($asignaturas as $asignatura)
                    <option value="{{ $asignatura->id_asignatura }}" @selected(old('id_asignatura') == $asignatura->id_asignatura)>
                        {{ $asignatura->codigo }} · {{ $asignatura->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="kx-label">Descripción</label>
            <textarea class="kx-textarea" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="kx-label">Cupo</label>
                <input class="kx-input" type="number" name="cupo" min="2" max="100" value="{{ old('cupo', 20) }}" required>
            </div>
            <div class="col-md-8">
                <label class="kx-label">Horario</label>
                <input class="kx-input" name="horario" value="{{ old('horario') }}" placeholder="Martes 3:00 p. m.">
            </div>
        </div>
        <button class="kx-btn" type="submit">Crear grupo</button>
        <a href="{{ route('grupos') }}" class="ms-3">Cancelar</a>
    </form>
</div>
@endsection

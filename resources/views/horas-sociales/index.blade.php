@extends('layouts.panel')

@section('title', 'Horas sociales')

@section('content')
<div class="kx-top">
    <div>
        <h1 class="h3 fw-bold mb-0">Horas sociales</h1>
        <p class="text-secondary mb-0">{{ auth()->user()->tieneRol('Directivo') ? 'Revisa las solicitudes de estudiantes.' : 'Registra y consulta el estado de tus actividades.' }}</p>
    </div>
</div>

@if (auth()->user()->tieneRol('Estudiante'))
<div class="kx-card p-4 mb-4" style="max-width: 720px;">
    <h2 class="h5">Nueva solicitud</h2>
    <form method="POST" action="{{ route('horas.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-md-7">
                <label class="kx-label">Actividad</label>
                <input class="kx-input" name="actividad" value="{{ old('actividad') }}" required>
            </div>
            <div class="col-md-5">
                <label class="kx-label">Número de horas</label>
                <input class="kx-input" type="number" name="horas" min="0.5" max="999.9" step="0.5" value="{{ old('horas') }}" required>
            </div>
            <div class="col-12">
                <label class="kx-label">Evidencia (opcional)</label>
                <input class="kx-input" type="file" name="evidencia" accept=".pdf,.jpg,.jpeg,.png">
            </div>
        </div>
        <button class="kx-btn mt-3" type="submit">Enviar solicitud</button>
    </form>
</div>
@endif

<div class="row g-3">
@forelse ($solicitudes as $solicitud)
    <div class="col-lg-6">
        <article class="kx-card p-4 h-100">
            <div class="d-flex justify-content-between gap-2">
                <h2 class="h5">{{ $solicitud->actividad }}</h2>
                <span class="kx-chip">{{ ucfirst($solicitud->estado) }}</span>
            </div>
            <p class="text-secondary">{{ $solicitud->horas }} horas · {{ $solicitud->estudiante->nombre_completo }}</p>
            @if ($solicitud->evidencia)
                <a href="{{ route('horas.evidencia', $solicitud) }}"><i class="bi bi-paperclip"></i> Ver evidencia</a>
            @endif
            @if ($solicitud->directivo)
                <p class="small text-secondary mt-2 mb-0">Revisó: {{ $solicitud->directivo->nombre_completo }}</p>
            @endif
            @if (auth()->user()->tieneRol('Directivo') && $solicitud->estado === 'pendiente')
                <div class="d-flex gap-2 mt-3">
                    @foreach (['aprobado' => 'Aprobar', 'rechazado' => 'Rechazar'] as $estado => $texto)
                        <form method="POST" action="{{ route('horas.update', $solicitud) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="estado" value="{{ $estado }}">
                            <button class="kx-btn {{ $estado === 'rechazado' ? 'kx-btn-ghost' : '' }}" type="submit">{{ $texto }}</button>
                        </form>
                    @endforeach
                </div>
            @endif
        </article>
    </div>
@empty
    <div class="col-12"><div class="kx-card p-4 text-secondary">No hay solicitudes de horas sociales.</div></div>
@endforelse
</div>
@endsection

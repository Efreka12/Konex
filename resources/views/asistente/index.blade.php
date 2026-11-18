@extends('layouts.panel')

@section('title', 'Asistente institucional')

@section('content')
<div class="kx-top">
    <div>
        <h1 class="h3 fw-bold mb-0">Asistente institucional</h1>
        <p class="text-secondary mb-0">Consulta la información oficial de UNIESPINAL y lo que está registrado en Konex.</p>
    </div>
</div>

<div class="kx-card p-4" style="max-width: 850px;">
    <div class="alert alert-light border">
        <i class="bi bi-shield-check"></i>
        Responde con la base oficial de UNIESPINAL (misión, programas, sedes, contacto, matrícula, calendario y bienestar) y con eventos, noticias, recursos verificados y horas sociales cargados en Konex. Si el dato no está, lo dice.
    </div>
    <div id="kx-assistant-log" class="kx-assistant-log" aria-live="polite">
        <div class="kx-assistant-message">
            Puedes preguntar: “¿Cuál es la misión de UNIESPINAL?”, “¿Qué programas hay?”, “¿Dónde queda la sede?”, “¿Cuánto cuesta la matrícula?”, “¿Qué eventos hay?” o “¿Cuál es el estado de mis horas sociales?”.
        </div>
    </div>
    <form id="kx-assistant-form" action="{{ route('asistente.consultar') }}" class="d-flex gap-2 mt-3">
        <input class="kx-input" name="pregunta" maxlength="500" placeholder="Escribe tu consulta institucional" required>
        <button class="kx-btn" type="submit">Consultar</button>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/konex-asistente.js') }}"></script>
@endpush

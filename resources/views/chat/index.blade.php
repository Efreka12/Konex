@extends('layouts.panel')

@section('title', 'Chat')

@section('content')
<div class="kx-top">
    <div>
        <h1 class="h3 fw-bold mb-0">Chat de grupo</h1>
        <p class="text-secondary mb-0">Parcial 2 · Squad 4</p>
    </div>
</div>

<div class="kx-card p-3" style="min-height: 420px; display: flex; flex-direction: column;">
    <div class="flex-grow-1 mb-3">
        <div class="mb-3">
            <strong>Luis Peña</strong>
            <p class="kx-card p-3 mt-1 mb-0">¿Quedamos mañana a las 6 para el taller?</p>
        </div>
        <div class="mb-3 text-end">
            <strong>Tú</strong>
            <p class="kx-card p-3 mt-1 mb-0 d-inline-block" style="background: rgba(255,59,107,.18);">Sí. Yo llevo los apuntes del semestre pasado.</p>
        </div>
        <div>
            <strong>Camila Ríos</strong>
            <p class="kx-card p-3 mt-1 mb-0">Perfecto, los subo también a Recursos.</p>
        </div>
    </div>
    <form class="d-flex gap-2" data-kx-submit data-kx-next="{{ route('chat') }}">
        <input class="kx-input" type="text" placeholder="Escribe un mensaje" required>
        <button class="kx-btn" type="submit">Enviar</button>
    </form>
</div>
@endsection

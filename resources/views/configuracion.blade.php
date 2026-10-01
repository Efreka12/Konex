@extends('layouts.panel')

@section('title', 'Configuración')

@section('content')
<div class="kx-top">
    <div>
        <h1 class="h3 fw-bold mb-0">Configuración</h1>
        <p class="text-secondary mb-0">Preferencias de tu cuenta Konex.</p>
    </div>
</div>

<div class="kx-card p-0 overflow-hidden" style="max-width: 640px;">
    <div class="d-flex justify-content-between align-items-center p-3 border-bottom border-secondary-subtle">
        Notificaciones <i class="bi bi-chevron-right"></i>
    </div>
    <div class="d-flex justify-content-between align-items-center p-3 border-bottom border-secondary-subtle">
        Privacidad <i class="bi bi-chevron-right"></i>
    </div>
    <div class="d-flex justify-content-between align-items-center p-3 border-bottom border-secondary-subtle">
        Idioma <i class="bi bi-chevron-right"></i>
    </div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="d-flex justify-content-between align-items-center p-3 w-100 border-0 bg-transparent" type="submit">
            Cerrar sesión <i class="bi bi-box-arrow-right"></i>
        </button>
    </form>
</div>
@endsection

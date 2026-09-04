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
    <a href="#" class="d-flex justify-content-between align-items-center p-3 border-bottom border-secondary-subtle text-white">
        Notificaciones <i class="bi bi-chevron-right"></i>
    </a>
    <a href="#" class="d-flex justify-content-between align-items-center p-3 border-bottom border-secondary-subtle text-white">
        Privacidad <i class="bi bi-chevron-right"></i>
    </a>
    <a href="#" class="d-flex justify-content-between align-items-center p-3 border-bottom border-secondary-subtle text-white">
        Idioma <i class="bi bi-chevron-right"></i>
    </a>
    <a href="{{ route('landing') }}" class="d-flex justify-content-between align-items-center p-3 text-white">
        Cerrar sesión <i class="bi bi-box-arrow-right"></i>
    </a>
</div>
@endsection

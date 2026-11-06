@extends('layouts.guest')

@section('title', 'Recuperar acceso')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center p-4">
    <div class="kx-card p-4 p-md-5 w-100" style="max-width: 440px;">
        <a class="kx-brand mb-4" href="{{ route('landing') }}">
            <img src="{{ asset('img/konex-mark.png') }}" alt="" class="kx-mark">
            <span class="kx-wordmark">Konex</span>
        </a>
        <h1 class="h3 fw-bold mb-2">Recuperar acceso</h1>
        <p class="text-secondary mb-4">Elige cómo comprobar que la cuenta es tuya. No enviamos la contraseña por correo.</p>

        <div class="d-flex flex-column gap-3 mb-4">
            <a href="{{ route('recuperar.correo') }}" class="kx-recover-option">
                <i class="bi bi-envelope"></i>
                <span>
                    <strong>Código al correo</strong>
                    <small>Te enviamos 8 dígitos. Vence en 10 minutos.</small>
                </span>
            </a>
            <a href="{{ route('recuperar.archivo') }}" class="kx-recover-option">
                <i class="bi bi-file-earmark-lock"></i>
                <span>
                    <strong>Archivo de recuperación</strong>
                    <small>El archivo binario de esta cuenta. No se gasta.</small>
                </span>
            </a>
        </div>

        <a class="small" href="{{ route('login') }}">Volver al inicio de sesión</a>
    </div>
</div>
@endsection

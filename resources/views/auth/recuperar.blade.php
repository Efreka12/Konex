@extends('layouts.guest')

@section('title', 'Recuperar contraseña')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center p-4">
    <div class="kx-card p-4 p-md-5 w-100" style="max-width: 420px;">
        <a class="kx-brand mb-4" href="{{ route('landing') }}">
            <img src="{{ asset('img/konex-mark.png') }}" alt="" class="kx-mark">
            <span class="kx-wordmark">Konex</span>
        </a>
        <h1 class="h3 fw-bold">Recuperar acceso</h1>
        <p class="text-secondary">Te enviaremos un enlace a tu correo institucional.</p>

        <form data-kx-submit data-kx-next="{{ route('restaurar') }}">
            <div class="mb-3">
                <label class="kx-label">Correo</label>
                <input class="kx-input" type="email" required>
            </div>
            <button class="kx-btn w-100 mb-3" type="submit">Enviar enlace</button>
        </form>

        <a class="small" href="{{ route('login') }}">Volver al inicio de sesión</a>
    </div>
</div>
@endsection

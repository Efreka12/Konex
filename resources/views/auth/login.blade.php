@extends('layouts.guest')

@section('title', 'Iniciar sesión')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center p-4">
    <div class="kx-card p-4 p-md-5 w-100" style="max-width: 420px;">
        <a class="kx-brand mb-4" href="{{ route('landing') }}">
            <img src="{{ asset('img/konex-mark.png') }}" alt="" class="kx-mark">
            <span class="kx-wordmark">Konex</span>
        </a>
        <h1 class="h3 fw-bold">Entrar a Konex</h1>
        <p class="text-secondary">Usa tu correo institucional de UNIESPINAL.</p>

        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <div class="mb-3">
                <label class="kx-label">Correo</label>
                <input class="kx-input" name="correo_institucional" type="email" value="{{ old('correo_institucional') }}" placeholder="ana.torres@uniespinal.edu.co" required>
            </div>
            <div class="mb-3">
                <label class="kx-label">Contraseña</label>
                <input class="kx-input" name="contrasena" type="password" placeholder="••••••••" required>
            </div>
            <button class="kx-btn w-100 mb-3" type="submit">Iniciar sesión</button>
        </form>

        <div class="d-flex justify-content-between small">
            <a href="{{ route('registro') }}">Registrarse</a>
            <a href="{{ route('recuperar') }}">¿Olvidaste tu clave?</a>
        </div>
    </div>
</div>
@endsection

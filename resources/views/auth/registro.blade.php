@extends('layouts.guest')

@section('title', 'Registro')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center p-4">
    <div class="kx-card p-4 p-md-5 w-100" style="max-width: 480px;">
        <a class="kx-brand mb-4" href="{{ route('landing') }}">
            <img src="{{ asset('img/konex-mark.png') }}" alt="" class="kx-mark">
            <span class="kx-wordmark">Konex</span>
        </a>
        <h1 class="h3 fw-bold">Crear cuenta</h1>
        <p class="text-secondary">Únete a la red académica de UNIESPINAL.</p>

        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('registro.store') }}">
            @csrf
            <div class="mb-3">
                <label class="kx-label">Nombre completo</label>
                <input class="kx-input" name="nombre_completo" type="text" value="{{ old('nombre_completo') }}" placeholder="Ana Torres" required>
            </div>
            <div class="mb-3">
                <label class="kx-label">Correo institucional</label>
                <input class="kx-input" name="correo_institucional" type="email" value="{{ old('correo_institucional') }}" placeholder="david.c@example.com" required>
            </div>
            <div class="mb-3">
                <label class="kx-label">Rol</label>
                <select class="kx-select" name="id_rol" required>
                    <option value="">Selecciona tu rol</option>
                    @foreach ($roles as $rol)
                        <option value="{{ $rol->id_rol }}" @selected(old('id_rol') == $rol->id_rol)>{{ $rol->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="kx-label">Contraseña</label>
                <input class="kx-input" name="contrasena" type="password" required>
            </div>
            <div class="mb-3">
                <label class="kx-label">Confirmar contraseña</label>
                <input class="kx-input" name="contrasena_confirmation" type="password" required>
            </div>
            <button class="kx-btn w-100 mb-3" type="submit">Registrarme</button>
        </form>

        <p class="small mb-0">¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
    </div>
</div>
@endsection

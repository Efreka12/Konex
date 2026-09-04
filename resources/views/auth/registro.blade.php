@extends('layouts.guest')

@section('title', 'Registro')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center p-4">
    <div class="kx-card p-4 p-md-5 w-100" style="max-width: 480px;">
        <a class="kx-brand mb-4" href="{{ route('landing') }}">
            <span class="kx-mark">K</span> Konex
        </a>
        <h1 class="h3 fw-bold">Crear cuenta</h1>
        <p class="text-secondary">Únete a la red académica de UNIESPINAL.</p>

        <form data-kx-submit data-kx-next="{{ route('inicio') }}">
            <div class="mb-3">
                <label class="kx-label">Nombre completo</label>
                <input class="kx-input" type="text" placeholder="Ana Torres" required>
            </div>
            <div class="mb-3">
                <label class="kx-label">Correo institucional</label>
                <input class="kx-input" type="email" placeholder="david.c@example.com" required>
            </div>
            <div class="mb-3">
                <label class="kx-label">Rol</label>
                <select class="kx-select" required>
                    <option value="">Selecciona tu rol</option>
                    <option>Estudiante</option>
                    <option>Profesor</option>
                    <option>Directivo</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="kx-label">Contraseña</label>
                <input class="kx-input" type="password" required>
            </div>
            <div class="mb-3">
                <label class="kx-label">Confirmar contraseña</label>
                <input class="kx-input" type="password" required>
            </div>
            <button class="kx-btn w-100 mb-3" type="submit">Registrarme</button>
        </form>

        <p class="small mb-0">¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
    </div>
</div>
@endsection

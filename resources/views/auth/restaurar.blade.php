@extends('layouts.guest')

@section('title', 'Restablecer contraseña')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center p-4">
    <div class="kx-card p-4 p-md-5 w-100" style="max-width: 420px;">
        <a class="kx-brand mb-4" href="{{ route('landing') }}">
            <span class="kx-mark">K</span> Konex
        </a>
        <h1 class="h3 fw-bold">Nueva contraseña</h1>
        <p class="text-secondary">Elige una clave nueva para tu cuenta Konex.</p>

        <form data-kx-submit data-kx-next="{{ route('login') }}">
            <div class="mb-3">
                <label class="kx-label">Nueva contraseña</label>
                <input class="kx-input" type="password" required>
            </div>
            <div class="mb-3">
                <label class="kx-label">Confirmar</label>
                <input class="kx-input" type="password" required>
            </div>
            <button class="kx-btn w-100" type="submit">Guardar contraseña</button>
        </form>
    </div>
</div>
@endsection

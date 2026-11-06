@extends('layouts.guest')

@section('title', 'Nueva contraseña')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center p-4">
    <div class="kx-card p-4 p-md-5 w-100" style="max-width: 420px;">
        <a class="kx-brand mb-4" href="{{ route('landing') }}">
            <img src="{{ asset('img/konex-mark.png') }}" alt="" class="kx-mark">
            <span class="kx-wordmark">Konex</span>
        </a>
        <h1 class="h3 fw-bold">Nueva contraseña</h1>
        <p class="text-secondary">Ya comprobamos que la cuenta es tuya. Elige una clave nueva. Se guarda con bcrypt.</p>

        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('recuperar.clave.store') }}">
            @csrf
            <div class="mb-3">
                <label class="kx-label">Nueva contraseña</label>
                <input class="kx-input" name="contrasena" type="password" minlength="8" required>
            </div>
            <div class="mb-3">
                <label class="kx-label">Confirmar</label>
                <input class="kx-input" name="contrasena_confirmation" type="password" minlength="8" required>
            </div>
            <button class="kx-btn w-100" type="submit">Guardar contraseña</button>
        </form>
    </div>
</div>
@endsection

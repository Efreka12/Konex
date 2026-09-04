@extends('layouts.guest')

@section('title', 'Konex')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center p-4">
    <div class="text-center" style="max-width: 420px;">
        <div class="kx-mark mx-auto mb-4" style="width: 72px; height: 72px; border-radius: 22px; font-size: 1.8rem;">K</div>
        <h1 class="fw-bold mb-2">KONEX</h1>
        <p class="text-secondary mb-4">Plataforma colaborativa de UNIESPINAL para estudiantes, docentes y directivos.</p>
        <a href="{{ route('login') }}" class="kx-btn d-block mb-3">Iniciar sesión</a>
        <a href="{{ route('registro') }}" class="kx-btn kx-btn-ghost d-block">Crear cuenta</a>
    </div>
</div>
@endsection

@extends('layouts.guest')

@section('title', 'Konex')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center p-4">
    <div class="text-center" style="max-width: 420px;">
        <img src="{{ asset('img/konex-mark.png') }}" alt="KONEX" class="kx-logo">
        <h1 class="kx-wordmark">KONEX</h1>
        <p class="text-secondary mb-4">Plataforma colaborativa de UNIESPINAL para estudiantes, docentes y directivos.</p>
        <a href="{{ route('login') }}" class="kx-btn d-block mb-3">Iniciar sesión</a>
        <a href="{{ route('registro') }}" class="kx-btn kx-btn-ghost d-block">Crear cuenta</a>
    </div>
</div>
@endsection

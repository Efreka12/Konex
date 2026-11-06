@extends('layouts.guest')

@section('title', 'Confirmar código')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center p-4">
    <div class="kx-card p-4 p-md-5 w-100" style="max-width: 420px;">
        <a class="kx-brand mb-4" href="{{ route('landing') }}">
            <img src="{{ asset('img/konex-mark.png') }}" alt="" class="kx-mark">
            <span class="kx-wordmark">Konex</span>
        </a>
        <h1 class="h3 fw-bold">Confirmar código</h1>
        <p class="text-secondary">Si enviamos un código, escríbelo aquí. Tiene 8 dígitos y vence en 10 minutos.</p>

        @if (session('ok'))
            <div class="alert alert-success">{{ session('ok') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('recuperar.otp.store') }}">
            @csrf
            <div class="mb-3">
                <label class="kx-label">Código</label>
                <input class="kx-input" name="codigo" type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="20" required>
            </div>
            <button class="kx-btn w-100 mb-3" type="submit">Confirmar código</button>
        </form>

        <a href="{{ route('buzon') }}" class="kx-btn kx-btn-ghost w-100 mb-3" target="_blank" rel="noopener">Abrir buzón</a>
        <a class="small" href="{{ route('recuperar.correo') }}">Pedir otro código</a>
    </div>
</div>
@endsection

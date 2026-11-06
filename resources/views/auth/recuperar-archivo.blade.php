@extends('layouts.guest')

@section('title', 'Archivo de recuperación')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center p-4">
    <div class="kx-card p-4 p-md-5 w-100" style="max-width: 420px;">
        <a class="kx-brand mb-4" href="{{ route('landing') }}">
            <img src="{{ asset('img/konex-mark.png') }}" alt="" class="kx-mark">
            <span class="kx-wordmark">Konex</span>
        </a>
        <h1 class="h3 fw-bold">Confirmar archivo</h1>
        <p class="text-secondary">Sube el archivo binario de esta cuenta. Confirmarlo no lo gasta.</p>

        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('recuperar.archivo.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="kx-label">Correo</label>
                <input class="kx-input" name="correo_institucional" type="email" value="{{ old('correo_institucional') }}" required>
            </div>
            <div class="mb-3">
                <label class="kx-label">Archivo de recuperación</label>
                <input class="kx-input" name="archivo" type="file" required>
            </div>
            <button class="kx-btn w-100 mb-3" type="submit">Confirmar archivo</button>
        </form>

        <a class="small" href="{{ route('recuperar') }}">Elegir otro método</a>
    </div>
</div>
@endsection

@extends('layouts.panel')

@section('title', 'Editar perfil')

@section('content')
<div class="kx-top">
    <div>
        <h1 class="h3 fw-bold mb-0">Editar perfil</h1>
        <p class="text-secondary mb-0">Actualiza cómo te ven tus compañeros y docentes.</p>
    </div>
</div>

<div class="kx-card p-4" style="max-width: 640px;">
    <form method="POST" action="{{ route('perfil.store') }}">
        @csrf
        <div class="mb-3">
            <label class="kx-label">Nombre</label>
            <input class="kx-input" name="nombre_completo" type="text" value="{{ old('nombre_completo', $usuario->nombre_completo) }}">
        </div>
        <div class="mb-3">
            <label class="kx-label">Programa</label>
            <input class="kx-input" type="text" value="{{ $usuario->programa->nombre ?? 'Sin programa' }}" disabled>
        </div>
        <div class="mb-3">
            <label class="kx-label">Bio</label>
            <textarea class="kx-textarea" name="bio" rows="4">{{ old('bio', $usuario->bio) }}</textarea>
        </div>
        <button class="kx-btn" type="submit">Guardar cambios</button>
        <a href="{{ route('perfil') }}" class="ms-3">Cancelar</a>
    </form>
</div>
@endsection

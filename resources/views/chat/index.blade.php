@extends('layouts.panel')

@section('title', 'Chat')

@section('content')
<div class="kx-top">
    <div>
        <h1 class="h3 fw-bold mb-0">Chat de grupo</h1>
        <p class="text-secondary mb-0">{{ $grupo->nombre ?? 'Únete a un grupo para chatear' }}</p>
    </div>
</div>

@if ($grupos->isNotEmpty())
    <div class="d-flex flex-wrap gap-2 mb-3">
        @foreach ($grupos as $item)
            <a href="{{ route('chat', ['grupo' => $item->id_grupo]) }}" class="kx-chip {{ $grupo && $grupo->id_grupo === $item->id_grupo ? '' : 'text-decoration-none' }}">
                {{ $item->nombre }}
            </a>
        @endforeach
    </div>
@endif

<div class="kx-card p-3" style="min-height: 420px; display: flex; flex-direction: column;">
    <div class="flex-grow-1 mb-3">
        @forelse ($mensajes as $mensaje)
            <div class="mb-3 {{ $mensaje->id_usuario === auth()->id() ? 'text-end' : '' }}">
                <strong>{{ $mensaje->id_usuario === auth()->id() ? 'Tú' : $mensaje->usuario->nombre_completo }}</strong>
                <p class="kx-card p-3 mt-1 mb-0 {{ $mensaje->id_usuario === auth()->id() ? 'd-inline-block' : '' }}" @if ($mensaje->id_usuario === auth()->id()) style="background: #f8e9ed;" @endif>
                    {{ $mensaje->contenido }}
                </p>
            </div>
        @empty
            <p class="text-secondary mb-0">No hay mensajes todavía.</p>
        @endforelse
    </div>
    @if ($grupo)
        <form class="d-flex gap-2" method="POST" action="{{ route('chat.store') }}">
            @csrf
            <input type="hidden" name="id_grupo" value="{{ $grupo->id_grupo }}">
            <input class="kx-input" name="contenido" type="text" placeholder="Escribe un mensaje" required>
            <button class="kx-btn" type="submit">Enviar</button>
        </form>
    @else
        <a href="{{ route('grupos.unirse') }}" class="kx-btn">Unirse a un grupo</a>
    @endif
</div>
@endsection

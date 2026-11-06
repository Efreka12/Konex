<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Buzón Konex · UNIESPINAL</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/konex.css') }}" rel="stylesheet">
</head>
<body class="konex kx-mail-body">
    <div class="kx-mail">
        <header class="kx-mail-top">
            <a class="kx-brand" href="{{ route('buzon') }}">
                <img src="{{ asset('img/konex-mark.png') }}" alt="" class="kx-mark" style="width: 34px;">
                <span class="kx-wordmark" style="font-size: 0.95rem;">Buzón</span>
            </a>
            <form class="kx-mail-search" method="GET" action="{{ route('buzon') }}">
                <i class="bi bi-search"></i>
                <input type="search" name="q" value="{{ $busqueda }}" placeholder="Buscar en el buzón">
            </form>
            <div class="kx-mail-count">{{ $sinLeer }} / {{ $total }}</div>
        </header>

        <aside class="kx-mail-side">
            <a class="is-active" href="{{ route('buzon') }}"><i class="bi bi-inbox"></i> Bandeja</a>
            <form method="POST" action="{{ route('buzon.leidos') }}">
                @csrf
                <button type="submit"><i class="bi bi-eye"></i> Marcar todo leído</button>
            </form>
            <form method="POST" action="{{ route('buzon.vaciar') }}" onsubmit="return confirm('¿Borrar todos los mensajes?');">
                @csrf
                <button type="submit"><i class="bi bi-trash"></i> Borrar todo</button>
            </form>
        </aside>

        <main class="kx-mail-main {{ $abierto ? 'has-open' : '' }}">
            @if ($mensajes->isEmpty())
                <div class="kx-mail-empty">
                    <i class="bi bi-envelope"></i>
                    <p>No hay mensajes en tu buzón</p>
                </div>
            @else
                <div class="kx-mail-list">
                    @foreach ($mensajes as $mensaje)
                        <a class="kx-mail-row {{ $mensaje->leido ? '' : 'is-unread' }} {{ $abierto?->id === $mensaje->id ? 'is-open' : '' }}" href="{{ route('buzon', ['mensaje' => $mensaje->id, 'q' => $busqueda ?: null]) }}">
                            <span class="kx-mail-from">{{ $mensaje->correo }}</span>
                            <span class="kx-mail-subject">{{ $mensaje->asunto }}</span>
                            <span class="kx-mail-time">{{ $mensaje->created_at?->format('H:i') }}</span>
                        </a>
                    @endforeach
                </div>
                @if ($abierto)
                    <article class="kx-mail-read">
                        <h1>{{ $abierto->asunto }}</h1>
                        <p class="kx-mail-meta">De {{ $abierto->remitente }} · Para {{ $abierto->correo }} · {{ $abierto->created_at?->format('d/m/Y H:i') }}</p>
                        <p class="kx-mail-text">{{ $abierto->cuerpo }}</p>
                        <div class="kx-mail-code">{{ $abierto->codigoFormateado() }}</div>
                        <a class="kx-btn" href="{{ route('recuperar.otp') }}">Usar este código</a>
                    </article>
                @endif
            @endif
        </main>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Inicio') · Konex</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/konex.css') }}" rel="stylesheet">
</head>
<body class="konex">
    <div class="kx-shell">
        <aside class="kx-side">
            <a class="kx-brand mb-4" href="{{ route('inicio') }}">
                <img src="{{ asset('img/konex-mark.png') }}" alt="" class="kx-mark">
                <span class="kx-wordmark">Konex</span>
            </a>
            <nav class="kx-nav">
                <a href="{{ route('inicio') }}" class="{{ request()->routeIs('inicio') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i> Inicio
                </a>
                <a href="{{ route('grupos') }}" class="{{ request()->routeIs('grupos*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Grupos
                </a>
                <a href="{{ route('recursos') }}" class="{{ request()->routeIs('recursos*') ? 'active' : '' }}">
                    <i class="bi bi-folder2-open"></i> Recursos
                </a>
                <a href="{{ route('chat') }}" class="{{ request()->routeIs('chat') ? 'active' : '' }}">
                    <i class="bi bi-chat-dots"></i> Chat
                </a>
                <a href="{{ route('perfil') }}" class="{{ request()->routeIs('perfil*') ? 'active' : '' }}">
                    <i class="bi bi-person"></i> Perfil
                </a>
                <a href="{{ route('configuracion') }}" class="{{ request()->routeIs('configuracion') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i> Configuración
                </a>
            </nav>
        </aside>

        <main class="kx-main">
            @yield('content')
        </main>
    </div>

    <nav class="kx-bottom">
        <a href="{{ route('inicio') }}" class="{{ request()->routeIs('inicio') ? 'active' : '' }}">
            <i class="bi bi-house-door"></i>Inicio
        </a>
        <a href="{{ route('grupos') }}" class="{{ request()->routeIs('grupos*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>Grupos
        </a>
        <a href="{{ route('recursos') }}" class="{{ request()->routeIs('recursos*') ? 'active' : '' }}">
            <i class="bi bi-folder2-open"></i>Apuntes
        </a>
        <a href="{{ route('chat') }}" class="{{ request()->routeIs('chat') ? 'active' : '' }}">
            <i class="bi bi-chat-dots"></i>Chat
        </a>
        <a href="{{ route('perfil') }}" class="{{ request()->routeIs('perfil*') ? 'active' : '' }}">
            <i class="bi bi-person"></i>Perfil
        </a>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/konex.js') }}"></script>
</body>
</html>

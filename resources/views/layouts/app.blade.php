<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Konex') · UNIESPINAL</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/konex.css') }}" rel="stylesheet">
</head>
<body>
    <div class="kx-shell">
        @include('partials.sidebar')
        <div class="kx-main">
            <div class="kx-topbar">
                <div>
                    <div class="kx-label mb-1">Konex · UNIESPINAL</div>
                    <h1 class="h4 kx-display mb-0">@yield('heading')</h1>
                </div>
                <form class="kx-search d-none d-md-block" action="{{ route('inicio') }}" method="get">
                    <input class="kx-input" type="search" name="q" placeholder="Buscar apuntes, grupos o eventos">
                </form>
                <a href="{{ route('perfil') }}" class="d-flex align-items-center gap-2">
                    <span class="kx-avatar">CR</span>
                </a>
            </div>
            @yield('content')
        </div>
    </div>

    <nav class="kx-bottom-nav">
        <a href="{{ route('inicio') }}" class="{{ request()->routeIs('inicio') ? 'active' : '' }}">
            <div><i class="bi bi-house-door"></i></div>Inicio
        </a>
        <a href="{{ route('grupos') }}" class="{{ request()->routeIs('grupos*') ? 'active' : '' }}">
            <div><i class="bi bi-people"></i></div>Grupos
        </a>
        <a href="{{ route('recursos') }}" class="{{ request()->routeIs('recursos*') ? 'active' : '' }}">
            <div><i class="bi bi-journal-richtext"></i></div>Recursos
        </a>
        <a href="{{ route('chat') }}" class="{{ request()->routeIs('chat') ? 'active' : '' }}">
            <div><i class="bi bi-chat-dots"></i></div>Chat
        </a>
        <a href="{{ route('perfil') }}" class="{{ request()->routeIs('perfil*') ? 'active' : '' }}">
            <div><i class="bi bi-person"></i></div>Perfil
        </a>
    </nav>
</body>
</html>

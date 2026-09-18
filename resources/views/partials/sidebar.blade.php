<aside class="kx-sidebar">
    <div class="d-flex align-items-center gap-2 mb-4 px-2">
        <img src="{{ asset('img/konex-mark.png') }}" alt="" class="kx-mark mb-0">
        <div>
            <div class="kx-display fw-bold">Konex</div>
            <small class="text-secondary">UNIESPINAL</small>
        </div>
    </div>

    <a class="kx-nav-link {{ request()->routeIs('inicio') ? 'active' : '' }}" href="{{ route('inicio') }}">
        <i class="bi bi-house-door"></i> Inicio
    </a>
    <a class="kx-nav-link {{ request()->routeIs('grupos*') ? 'active' : '' }}" href="{{ route('grupos') }}">
        <i class="bi bi-people"></i> Grupos
    </a>
    <a class="kx-nav-link {{ request()->routeIs('recursos*') ? 'active' : '' }}" href="{{ route('recursos') }}">
        <i class="bi bi-journal-richtext"></i> Recursos
    </a>
    <a class="kx-nav-link {{ request()->routeIs('chat') ? 'active' : '' }}" href="{{ route('chat') }}">
        <i class="bi bi-chat-dots"></i> Chat
    </a>
    <a class="kx-nav-link {{ request()->routeIs('perfil*') ? 'active' : '' }}" href="{{ route('perfil') }}">
        <i class="bi bi-person"></i> Perfil
    </a>
    <a class="kx-nav-link {{ request()->routeIs('configuracion') ? 'active' : '' }}" href="{{ route('configuracion') }}">
        <i class="bi bi-gear"></i> Configuración
    </a>

    <div class="mt-auto px-2 pt-4">
        <a href="{{ route('login') }}" class="kx-btn kx-btn-ghost kx-btn-full">
            <i class="bi bi-box-arrow-left"></i> Salir
        </a>
    </div>
</aside>

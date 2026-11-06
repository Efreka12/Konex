@extends('layouts.panel')

@section('title', 'Configuración')

@section('content')
<div class="kx-top">
    <div>
        <h1 class="h3 fw-bold mb-0">Configuración</h1>
        <p class="text-secondary mb-0">Preferencias y recuperación de tu cuenta Konex.</p>
    </div>
</div>

<div class="kx-card p-4 mb-4" style="max-width: 640px;">
    <h2 class="h5">Recuperación</h2>
    <p class="text-secondary">Descargar un archivo nuevo reemplaza al anterior.</p>
    <a href="{{ route('configuracion.archivo') }}" class="kx-btn">Descargar archivo de recuperación</a>
</div>

<div class="kx-card p-4 mb-4" style="max-width: 640px;">
    <h2 class="h5">Llave de acceso (FIDO2)</h2>
    <p class="text-secondary mb-2">
        El estándar es <strong>WebAuthn / FIDO2</strong>: una llave USB certificada crea la clave privada adentro y esa clave nunca sale.
        El servidor solo guardaría la clave pública. El navegador usaría <code>navigator.credentials.create()</code> para registrarla
        y <code>navigator.credentials.get()</code> para entrar, con transporte <code>usb</code>.
    </p>
    <p class="text-secondary mb-0">
        Una memoria USB normal no sirve, aunque tenga 32 GB. Tampoco sirve un archivo descargado. En este equipo no hay una llave FIDO2 física,
        así que no se simula el botón ni se instala la librería. En <code>127.0.0.1</code> el navegador sí permitiría probarla si hubiera dispositivo.
    </p>
</div>

<div class="kx-card p-0 overflow-hidden" style="max-width: 640px;">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="d-flex justify-content-between align-items-center p-3 w-100 border-0 bg-transparent" type="submit">
            Cerrar sesión <i class="bi bi-box-arrow-right"></i>
        </button>
    </form>
</div>
@endsection

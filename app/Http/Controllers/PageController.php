<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    public function landing(): View
    {
        return view('landing');
    }

    public function login(): View
    {
        return view('auth.login');
    }

    public function registro(): View
    {
        return view('auth.registro');
    }

    public function recuperar(): View
    {
        return view('auth.recuperar');
    }

    public function restaurar(): View
    {
        return view('auth.restaurar');
    }

    public function inicio(): View
    {
        return view('inicio');
    }

    public function grupos(): View
    {
        return view('grupos.index');
    }

    public function unirseGrupo(): View
    {
        return view('grupos.unirse');
    }

    public function recursos(): View
    {
        return view('recursos.index');
    }

    public function crearRecurso(): View
    {
        return view('recursos.crear');
    }

    public function crearPublicacion(): View
    {
        return view('publicaciones.crear');
    }

    public function chat(): View
    {
        return view('chat.index');
    }

    public function perfil(): View
    {
        return view('perfil.index');
    }

    public function editarPerfil(): View
    {
        return view('perfil.editar');
    }

    public function configuracion(): View
    {
        return view('configuracion');
    }
}

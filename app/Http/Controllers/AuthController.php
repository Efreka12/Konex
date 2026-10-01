<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function loginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'correo_institucional' => 'required|email',
            'contrasena' => 'required|string',
        ]);

        $usuario = Usuario::query()
            ->where('correo_institucional', $validated['correo_institucional'])
            ->first();

        if (! $usuario || ! Hash::check($validated['contrasena'], $usuario->contrasena)) {
            return back()
                ->withInput($request->only('correo_institucional'))
                ->withErrors(['correo_institucional' => 'Correo o contraseña incorrectos.']);
        }

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route('inicio');
    }

    public function registroForm(): View
    {
        return view('auth.registro', [
            'roles' => Rol::query()->orderBy('id_rol')->get(),
        ]);
    }

    public function registro(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre_completo' => 'required|string|min:3|max:150',
            'correo_institucional' => 'required|email|max:150|unique:usuarios,correo_institucional',
            'id_rol' => 'required|exists:roles,id_rol',
            'contrasena' => 'required|string|min:8|confirmed',
        ]);

        $usuario = Usuario::create([
            'id_rol' => $validated['id_rol'],
            'id_programa' => \App\Models\Programa::query()->value('id_programa'),
            'nombre_completo' => $validated['nombre_completo'],
            'correo_institucional' => $validated['correo_institucional'],
            'contrasena' => Hash::make($validated['contrasena']),
            'created_at' => now(),
        ]);

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route('inicio');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }
}

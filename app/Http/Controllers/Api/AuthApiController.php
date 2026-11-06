<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Programa;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthApiController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|min:3|max:150',
            'email' => 'required|email|max:150|unique:usuarios,correo_institucional',
            'password' => 'required|string|min:8',
        ]);

        $estudiante = Rol::query()->where('nombre', 'Estudiante')->firstOrFail();

        $usuario = Usuario::create([
            'id_rol' => $estudiante->id_rol,
            'id_programa' => Programa::query()->value('id_programa'),
            'nombre_completo' => $data['name'],
            'correo_institucional' => $data['email'],
            'contrasena' => Hash::make($data['password']),
            'activo' => true,
            'created_at' => now(),
        ]);

        return response()->json([
            'message' => 'Usuario registrado',
            'userId' => $usuario->id_usuario,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $usuario = Usuario::query()
            ->where('correo_institucional', $data['email'])
            ->where('activo', true)
            ->first();

        if (! $usuario || ! Hash::check($data['password'], $usuario->contrasena)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        return response()->json([
            'token' => $usuario->createToken('react')->plainTextToken,
        ]);
    }

    public function profile(Request $request): JsonResponse
    {
        return response()->json($request->user()->toAuthArray());
    }

    public function update(Request $request): JsonResponse
    {
        $usuario = $request->user();

        $data = $request->validate([
            'name' => 'required|string|min:3|max:150',
            'email' => 'required|email|max:150|unique:usuarios,correo_institucional,'.$usuario->id_usuario.',id_usuario',
        ]);

        $usuario->update([
            'nombre_completo' => $data['name'],
            'correo_institucional' => $data['email'],
        ]);

        return response()->json($usuario->fresh()->toAuthArray());
    }
}

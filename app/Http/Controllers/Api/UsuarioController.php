<?php

namespace App\Http\Controllers\Api;

use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends ResourceController
{
    protected function modelClass(): string
    {
        return Usuario::class;
    }

    protected function storeRules(): array
    {
        return [
            'id_rol' => 'required|exists:roles,id_rol',
            'id_programa' => 'nullable|exists:programas,id_programa',
            'nombre_completo' => 'required|string|min:3|max:150',
            'correo_institucional' => 'required|email|max:150|unique:usuarios,correo_institucional',
            'contrasena' => 'required|string|min:8|max:255',
            'foto_perfil' => 'nullable|string|max:255',
            'semestre' => 'nullable|integer|min:1|max:12',
            'bio' => 'nullable|string|max:1000',
        ];
    }

    protected function updateRules(): array
    {
        return [
            'id_rol' => 'sometimes|exists:roles,id_rol',
            'id_programa' => 'nullable|exists:programas,id_programa',
            'nombre_completo' => 'sometimes|string|min:3|max:150',
            'correo_institucional' => 'sometimes|email|max:150',
            'contrasena' => 'sometimes|string|min:8|max:255',
            'foto_perfil' => 'nullable|string|max:255',
            'semestre' => 'nullable|integer|min:1|max:12',
            'bio' => 'nullable|string|max:1000',
        ];
    }

    protected function prepare(array $data): array
    {
        if (isset($data['contrasena'])) {
            $data['contrasena'] = Hash::make($data['contrasena']);
        }

        $data['created_at'] ??= now();

        return $data;
    }
}

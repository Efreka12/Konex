<?php

namespace App\Http\Controllers\Api;

use App\Models\UsuarioAsignatura;

class UsuarioAsignaturaController extends ResourceController
{
    protected function modelClass(): string
    {
        return UsuarioAsignatura::class;
    }

    protected function storeRules(): array
    {
        return [
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'id_asignatura' => 'required|exists:asignaturas,id_asignatura',
            'tipo' => 'required|in:cursa,dicta',
        ];
    }

    protected function updateRules(): array
    {
        return [
            'id_usuario' => 'sometimes|exists:usuarios,id_usuario',
            'id_asignatura' => 'sometimes|exists:asignaturas,id_asignatura',
            'tipo' => 'sometimes|in:cursa,dicta',
        ];
    }
}

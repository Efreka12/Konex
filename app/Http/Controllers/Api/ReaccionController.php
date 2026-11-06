<?php

namespace App\Http\Controllers\Api;

use App\Models\Reaccion;

class ReaccionController extends ResourceController
{
    protected function modelClass(): string
    {
        return Reaccion::class;
    }

    protected function storeRules(): array
    {
        return [
            'id_publicacion' => 'required|exists:publicaciones,id_publicacion',
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'tipo' => 'required|in:like,guardar',
        ];
    }

    protected function updateRules(): array
    {
        return [
            'id_publicacion' => 'sometimes|exists:publicaciones,id_publicacion',
            'id_usuario' => 'sometimes|exists:usuarios,id_usuario',
            'tipo' => 'sometimes|in:like,guardar',
        ];
    }
}

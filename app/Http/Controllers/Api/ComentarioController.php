<?php

namespace App\Http\Controllers\Api;

use App\Models\Comentario;

class ComentarioController extends ResourceController
{
    protected function modelClass(): string
    {
        return Comentario::class;
    }

    protected function storeRules(): array
    {
        return [
            'id_publicacion' => 'required|exists:publicaciones,id_publicacion',
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'contenido' => 'required|string|min:1|max:1000',
            'fecha' => 'nullable|date',
        ];
    }

    protected function updateRules(): array
    {
        return [
            'id_publicacion' => 'sometimes|exists:publicaciones,id_publicacion',
            'id_usuario' => 'sometimes|exists:usuarios,id_usuario',
            'contenido' => 'sometimes|string|min:1|max:1000',
            'fecha' => 'nullable|date',
        ];
    }
}

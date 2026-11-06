<?php

namespace App\Http\Controllers\Api;

use App\Models\Publicacion;

class PublicacionController extends ResourceController
{
    protected function modelClass(): string
    {
        return Publicacion::class;
    }

    protected function storeRules(): array
    {
        return [
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'tipo' => 'required|in:aviso,evento,busqueda_grupo',
            'titulo' => 'required|string|min:3|max:150',
            'contenido' => 'required|string|min:1|max:3000',
            'fecha' => 'nullable|date',
        ];
    }

    protected function updateRules(): array
    {
        return [
            'id_usuario' => 'sometimes|exists:usuarios,id_usuario',
            'tipo' => 'sometimes|in:aviso,evento,busqueda_grupo',
            'titulo' => 'sometimes|string|min:3|max:150',
            'contenido' => 'sometimes|string|min:1|max:3000',
            'fecha' => 'nullable|date',
        ];
    }
}

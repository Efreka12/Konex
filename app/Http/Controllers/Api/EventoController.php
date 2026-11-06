<?php

namespace App\Http\Controllers\Api;

use App\Models\Evento;

class EventoController extends ResourceController
{
    protected function modelClass(): string
    {
        return Evento::class;
    }

    protected function storeRules(): array
    {
        return [
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'titulo' => 'required|string|min:3|max:150',
            'descripcion' => 'nullable|string|max:2000',
            'fecha' => 'required|date',
            'lugar' => 'nullable|string|max:150',
        ];
    }

    protected function updateRules(): array
    {
        return [
            'id_usuario' => 'sometimes|exists:usuarios,id_usuario',
            'titulo' => 'sometimes|string|min:3|max:150',
            'descripcion' => 'nullable|string|max:2000',
            'fecha' => 'sometimes|date',
            'lugar' => 'nullable|string|max:150',
        ];
    }
}

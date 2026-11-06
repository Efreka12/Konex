<?php

namespace App\Http\Controllers\Api;

use App\Models\Notificacion;

class NotificacionController extends ResourceController
{
    protected function modelClass(): string
    {
        return Notificacion::class;
    }

    protected function storeRules(): array
    {
        return [
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'titulo' => 'required|string|min:3|max:120',
            'mensaje' => 'required|string|min:3|max:255',
            'leida' => 'sometimes|boolean',
            'fecha' => 'nullable|date',
        ];
    }

    protected function updateRules(): array
    {
        return [
            'id_usuario' => 'sometimes|exists:usuarios,id_usuario',
            'titulo' => 'sometimes|string|min:3|max:120',
            'mensaje' => 'sometimes|string|min:3|max:255',
            'leida' => 'sometimes|boolean',
            'fecha' => 'nullable|date',
        ];
    }
}

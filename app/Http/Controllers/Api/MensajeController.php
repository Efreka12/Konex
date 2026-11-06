<?php

namespace App\Http\Controllers\Api;

use App\Models\Mensaje;

class MensajeController extends ResourceController
{
    protected function modelClass(): string
    {
        return Mensaje::class;
    }

    protected function storeRules(): array
    {
        return [
            'id_grupo' => 'required|exists:grupos_estudio,id_grupo',
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'contenido' => 'required|string|min:1|max:2000',
            'fecha_envio' => 'nullable|date',
        ];
    }

    protected function updateRules(): array
    {
        return [
            'id_grupo' => 'sometimes|exists:grupos_estudio,id_grupo',
            'id_usuario' => 'sometimes|exists:usuarios,id_usuario',
            'contenido' => 'sometimes|string|min:1|max:2000',
            'fecha_envio' => 'nullable|date',
        ];
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\MiembroGrupo;

class MiembroGrupoController extends ResourceController
{
    protected function modelClass(): string
    {
        return MiembroGrupo::class;
    }

    protected function storeRules(): array
    {
        return [
            'id_grupo' => 'required|exists:grupos_estudio,id_grupo',
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'rol_grupo' => 'required|in:admin,miembro',
            'fecha_union' => 'nullable|date',
        ];
    }

    protected function updateRules(): array
    {
        return [
            'id_grupo' => 'sometimes|exists:grupos_estudio,id_grupo',
            'id_usuario' => 'sometimes|exists:usuarios,id_usuario',
            'rol_grupo' => 'sometimes|in:admin,miembro',
            'fecha_union' => 'nullable|date',
        ];
    }
}

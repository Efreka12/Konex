<?php

namespace App\Http\Controllers\Api;

use App\Models\GrupoEstudio;

class GrupoEstudioController extends ResourceController
{
    protected function modelClass(): string
    {
        return GrupoEstudio::class;
    }

    protected function storeRules(): array
    {
        return [
            'id_asignatura' => 'required|exists:asignaturas,id_asignatura',
            'id_creador' => 'required|exists:usuarios,id_usuario',
            'nombre' => 'required|string|min:3|max:120',
            'descripcion' => 'nullable|string|max:1000',
            'codigo_union' => 'required|string|min:4|max:30',
            'cupo' => 'required|integer|min:2|max:50',
            'horario' => 'nullable|string|max:80',
        ];
    }

    protected function updateRules(): array
    {
        return [
            'id_asignatura' => 'sometimes|exists:asignaturas,id_asignatura',
            'id_creador' => 'sometimes|exists:usuarios,id_usuario',
            'nombre' => 'sometimes|string|min:3|max:120',
            'descripcion' => 'nullable|string|max:1000',
            'codigo_union' => 'sometimes|string|min:4|max:30',
            'cupo' => 'sometimes|integer|min:2|max:50',
            'horario' => 'nullable|string|max:80',
        ];
    }
}

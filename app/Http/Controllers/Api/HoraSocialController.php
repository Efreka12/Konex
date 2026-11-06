<?php

namespace App\Http\Controllers\Api;

use App\Models\HoraSocial;

class HoraSocialController extends ResourceController
{
    protected function modelClass(): string
    {
        return HoraSocial::class;
    }

    protected function storeRules(): array
    {
        return [
            'id_estudiante' => 'required|exists:usuarios,id_usuario',
            'id_directivo' => 'nullable|exists:usuarios,id_usuario',
            'actividad' => 'required|string|min:3|max:150',
            'horas' => 'required|numeric|min:0.5|max:999.9',
            'estado' => 'required|in:pendiente,aprobado,rechazado',
            'evidencia' => 'nullable|string|max:255',
        ];
    }

    protected function updateRules(): array
    {
        return [
            'id_estudiante' => 'sometimes|exists:usuarios,id_usuario',
            'id_directivo' => 'nullable|exists:usuarios,id_usuario',
            'actividad' => 'sometimes|string|min:3|max:150',
            'horas' => 'sometimes|numeric|min:0.5|max:999.9',
            'estado' => 'sometimes|in:pendiente,aprobado,rechazado',
            'evidencia' => 'nullable|string|max:255',
        ];
    }
}

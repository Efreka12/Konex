<?php

namespace App\Http\Controllers\Api;

use App\Models\Asignatura;

class AsignaturaController extends ResourceController
{
    protected function modelClass(): string
    {
        return Asignatura::class;
    }

    protected function storeRules(): array
    {
        return [
            'id_programa' => 'required|exists:programas,id_programa',
            'codigo' => 'required|string|min:3|max:20',
            'nombre' => 'required|string|min:3|max:120',
        ];
    }

    protected function updateRules(): array
    {
        return [
            'id_programa' => 'sometimes|exists:programas,id_programa',
            'codigo' => 'sometimes|string|min:3|max:20',
            'nombre' => 'sometimes|string|min:3|max:120',
        ];
    }
}

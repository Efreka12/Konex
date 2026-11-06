<?php

namespace App\Http\Controllers\Api;

use App\Models\Programa;

class ProgramaController extends ResourceController
{
    protected function modelClass(): string
    {
        return Programa::class;
    }

    protected function storeRules(): array
    {
        return [
            'nombre' => 'required|string|max:120',
            'facultad' => 'nullable|string|max:120',
        ];
    }

    protected function updateRules(): array
    {
        return [
            'nombre' => 'sometimes|string|max:120',
            'facultad' => 'nullable|string|max:120',
        ];
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Rol;

class RolController extends ResourceController
{
    protected function modelClass(): string
    {
        return Rol::class;
    }

    protected function storeRules(): array
    {
        return [
            'nombre' => 'required|string|max:30',
        ];
    }

    protected function updateRules(): array
    {
        return [
            'nombre' => 'sometimes|string|max:30',
        ];
    }
}

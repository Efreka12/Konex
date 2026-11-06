<?php

namespace App\Http\Controllers\Api;

use App\Models\Recurso;

class RecursoController extends ResourceController
{
    protected function modelClass(): string
    {
        return Recurso::class;
    }

    protected function storeRules(): array
    {
        return [
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'id_asignatura' => 'required|exists:asignaturas,id_asignatura',
            'id_grupo' => 'nullable|exists:grupos_estudio,id_grupo',
            'titulo' => 'required|string|min:3|max:150',
            'descripcion' => 'nullable|string|max:1000',
            'tipo_archivo' => 'required|in:PDF,DOC,PPT,IMG',
            'ruta_archivo' => 'required|string|max:255',
            'verificado' => 'sometimes|boolean',
            'semestre' => 'nullable|string|max:20',
        ];
    }

    protected function updateRules(): array
    {
        return [
            'id_usuario' => 'sometimes|exists:usuarios,id_usuario',
            'id_asignatura' => 'sometimes|exists:asignaturas,id_asignatura',
            'id_grupo' => 'nullable|exists:grupos_estudio,id_grupo',
            'titulo' => 'sometimes|string|min:3|max:150',
            'descripcion' => 'nullable|string|max:1000',
            'tipo_archivo' => 'sometimes|in:PDF,DOC,PPT,IMG',
            'ruta_archivo' => 'sometimes|string|max:255',
            'verificado' => 'sometimes|boolean',
            'semestre' => 'nullable|string|max:20',
        ];
    }
}

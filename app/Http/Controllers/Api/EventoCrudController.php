<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use Illuminate\Http\Request;

class EventoCrudController extends Controller
{
    public function index()
    {
        return response()->json(Evento::query()->latest('id_evento')->get(), 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'titulo' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'lugar' => 'nullable|string|max:150',
        ]);

        $evento = Evento::create($data);

        return response()->json($evento, 201);
    }

    public function show(Evento $evento)
    {
        return response()->json($evento, 200);
    }

    public function update(Request $request, Evento $evento)
    {
        $data = $request->validate([
            'id_usuario' => 'sometimes|required|exists:usuarios,id_usuario',
            'titulo' => 'sometimes|required|string|max:150',
            'descripcion' => 'nullable|string',
            'fecha' => 'sometimes|required|date',
            'lugar' => 'nullable|string|max:150',
        ]);

        $evento->update($data);

        return response()->json($evento, 200);
    }

    public function destroy(Evento $evento)
    {
        $evento->delete();

        return response()->json(['message' => 'Evento eliminado'], 200);
    }
}

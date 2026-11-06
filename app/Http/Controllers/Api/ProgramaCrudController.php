<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Programa;
use Illuminate\Http\Request;

class ProgramaCrudController extends Controller
{
    public function index()
    {
        return response()->json(Programa::query()->latest('id_programa')->get(), 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:120',
            'facultad' => 'nullable|string|max:120',
        ]);

        $programa = Programa::create($data);

        return response()->json($programa, 201);
    }

    public function show(Programa $programa)
    {
        return response()->json($programa, 200);
    }

    public function update(Request $request, Programa $programa)
    {
        $data = $request->validate([
            'nombre' => 'sometimes|required|string|max:120',
            'facultad' => 'nullable|string|max:120',
        ]);

        $programa->update($data);

        return response()->json($programa, 200);
    }

    public function destroy(Programa $programa)
    {
        $programa->delete();

        return response()->json(['message' => 'Programa eliminado'], 200);
    }
}

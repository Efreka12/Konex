<?php

namespace App\Http\Controllers;

use App\Services\ConsultaInstitucionalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AsistenteController extends Controller
{
    public function __construct(private ConsultaInstitucionalService $consulta) {}

    public function index(): View
    {
        return view('asistente.index');
    }

    public function consultar(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pregunta' => 'required|string|min:3|max:500',
        ]);

        return response()->json(
            $this->consulta->responder($request->user(), $validated['pregunta'])
        );
    }
}

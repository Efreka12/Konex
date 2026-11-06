<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

abstract class ResourceController extends Controller
{
    abstract protected function modelClass(): string;

    abstract protected function storeRules(): array;

    protected function updateRules(): array
    {
        return $this->storeRules();
    }

    protected function prepare(array $data): array
    {
        return $data;
    }

    public function index()
    {
        $model = $this->modelClass();

        return response()->json(['data' => $model::query()->get()]);
    }

    public function store(Request $request)
    {
        $validated = $this->prepare($request->validate($this->storeRules()));
        $row = $this->modelClass()::query()->create($validated);

        return response()->json(['data' => $row], 201);
    }

    public function show(string $id)
    {
        return response()->json(['data' => $this->modelClass()::query()->findOrFail($id)]);
    }

    public function update(Request $request, string $id)
    {
        $row = $this->modelClass()::query()->findOrFail($id);
        $row->update($this->prepare($request->validate($this->updateRules())));

        return response()->json(['data' => $row->fresh()]);
    }

    public function destroy(string $id)
    {
        $row = $this->modelClass()::query()->findOrFail($id);

        try {
            $row->delete();
        } catch (QueryException) {
            return response()->json([
                'message' => 'No se puede eliminar porque tiene registros relacionados.',
            ], 409);
        }

        return response()->json(null, 204);
    }
}

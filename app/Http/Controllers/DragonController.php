<?php
namespace App\Http\Controllers;

use App\Models\Dragon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DragonController extends Controller
{
    // GET /api/dragons
    public function index(): JsonResponse
    {
        return response()->json(Dragon::with('domador')->get(), 200);
    }

    // POST /api/dragons
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre'     => 'required|string|max:255',
            'tipo'       => 'required|string|max:100',
            'poder'      => 'required|integer|min:1',
            'domador_id' => 'required|exists:domadores,id',
        ]);

        $dragon = Dragon::create($validated);
        $dragon->load('domador');
        return response()->json($dragon, 201);
    }

    // GET /api/dragons/{id}
    public function show(int $id): JsonResponse
    {
        return response()->json(Dragon::with('domador')->findOrFail($id), 200);
    }

    // PUT /api/dragons/{id}
    public function update(Request $request, int $id): JsonResponse
    {
        $dragon = Dragon::findOrFail($id);

        $validated = $request->validate([
            'nombre'     => 'sometimes|string|max:255',
            'tipo'       => 'sometimes|string|max:100',
            'poder'      => 'sometimes|integer|min:1',
            'domador_id' => 'sometimes|exists:domadores,id',
        ]);

        $dragon->update($validated);
        $dragon->load('domador');
        return response()->json($dragon, 200);
    }

    // DELETE /api/dragons/{id}
    public function destroy(int $id): JsonResponse
    {
        Dragon::findOrFail($id)->delete();
        return response()->json(['message' => 'Dragón eliminado correctamente.'], 200);
    }
}
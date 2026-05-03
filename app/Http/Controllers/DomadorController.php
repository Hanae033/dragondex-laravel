<?php
namespace App\Http\Controllers;

use App\Models\Domador;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DomadorController extends Controller
{
    // GET /api/domadores
    public function index(): JsonResponse
    {
        return response()->json(Domador::with('dragons')->get(), 200);
    }

    // POST /api/domadores
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:255',
            'ciudad'      => 'required|string|max:255',
            'experiencia' => 'required|integer|min:0',
        ]);

        return response()->json(Domador::create($validated), 201);
    }

    // GET /api/domadores/{id}
    public function show(int $id): JsonResponse
    {
        return response()->json(Domador::with('dragons')->findOrFail($id), 200);
    }

    // PUT /api/domadores/{id}
    public function update(Request $request, int $id): JsonResponse
    {
        $domador = Domador::findOrFail($id);

        $validated = $request->validate([
            'nombre'      => 'sometimes|string|max:255',
            'ciudad'      => 'sometimes|string|max:255',
            'experiencia' => 'sometimes|integer|min:0',
        ]);

        $domador->update($validated);
        return response()->json($domador, 200);
    }

    // DELETE /api/domadores/{id}
    public function destroy(int $id): JsonResponse
    {
        Domador::findOrFail($id)->delete();
        return response()->json(['message' => 'Domador eliminado correctamente.'], 200);
    }
}
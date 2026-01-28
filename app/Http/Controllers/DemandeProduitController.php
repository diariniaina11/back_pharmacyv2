<?php

namespace App\Http\Controllers;

use App\Models\DemandeProduit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DemandeProduitController extends Controller
{
    public function index()
    {
        return response()->json(DemandeProduit::with(['produit', 'utilisateur'])->get(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produit_id' => 'nullable|exists:produits,id',
            'utilisateur_id' => 'required|exists:utilisateurs,id',
            'quantite_demandee' => 'required|integer|min:1',
            'commentaire' => 'nullable|string',
            'status' => ['nullable', Rule::in(['EN_ATTENTE', 'VALIDE', 'REFUSE'])],
            'date_creation' => 'nullable|date',
        ]);

        $demandeProduit = DemandeProduit::create($validated);

        return response()->json($demandeProduit, 201);
    }

    public function show($id)
    {
        $demandeProduit = DemandeProduit::with(['produit', 'utilisateur'])->find($id);

        if (!$demandeProduit) {
            return response()->json(['message' => 'DemandeProduit not found'], 404);
        }

        return response()->json($demandeProduit, 200);
    }

    public function update(Request $request, $id)
    {
        $demandeProduit = DemandeProduit::find($id);

        if (!$demandeProduit) {
            return response()->json(['message' => 'DemandeProduit not found'], 404);
        }

        $validated = $request->validate([
            'produit_id' => 'nullable|exists:produits,id',
            'utilisateur_id' => 'required|exists:utilisateurs,id',
            'quantite_demandee' => 'required|integer|min:1',
            'commentaire' => 'nullable|string',
            'status' => ['nullable', Rule::in(['EN_ATTENTE', 'VALIDE', 'REFUSE'])],
            'date_creation' => 'nullable|date',
        ]);

        $demandeProduit->update($validated);

        return response()->json($demandeProduit, 200);
    }

    public function destroy($id)
    {
        $demandeProduit = DemandeProduit::find($id);

        if (!$demandeProduit) {
            return response()->json(['message' => 'DemandeProduit not found'], 404);
        }

        $demandeProduit->delete();

        return response()->json(['message' => 'DemandeProduit deleted successfully'], 200);
    }
}

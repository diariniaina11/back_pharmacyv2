<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use Illuminate\Http\Request;

class VenteController extends Controller
{
    public function index()
    {
        return response()->json(Vente::with(['produit', 'utilisateur'])->get(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'utilisateur_id' => 'required|exists:utilisateurs,id',
            'quantite_vendue' => 'required|integer|min:1',
            'date_vente' => 'nullable|date',
        ]);

        $vente = Vente::create($validated);

        return response()->json($vente, 201);
    }

    public function show($id)
    {
        $vente = Vente::with(['produit', 'utilisateur'])->find($id);

        if (!$vente) {
            return response()->json(['message' => 'Vente not found'], 404);
        }

        return response()->json($vente, 200);
    }

    public function update(Request $request, $id)
    {
        $vente = Vente::find($id);

        if (!$vente) {
            return response()->json(['message' => 'Vente not found'], 404);
        }

        $validated = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'utilisateur_id' => 'required|exists:utilisateurs,id',
            'quantite_vendue' => 'required|integer|min:1',
            'date_vente' => 'nullable|date',
        ]);

        $vente->update($validated);

        return response()->json($vente, 200);
    }

    public function destroy($id)
    {
        $vente = Vente::find($id);

        if (!$vente) {
            return response()->json(['message' => 'Vente not found'], 404);
        }

        $vente->delete();

        return response()->json(['message' => 'Vente deleted successfully'], 200);
    }
}

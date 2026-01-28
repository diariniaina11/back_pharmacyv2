<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function index()
    {
        // Eager load category and fournisseur for better performance and response data
        return response()->json(Produit::with(['category', 'fournisseur'])->get(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:150',
            'categorie_id' => 'required|exists:categories,id',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'numero_lot' => 'required|string|max:100',
            'date_peremption' => 'required|date',
            'quantite_boites' => 'integer|min:0',
            'quantite_unites' => 'integer|min:0',
            'prix' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $produit = Produit::create($validated);

        return response()->json($produit, 201);
    }

    public function show($id)
    {
        $produit = Produit::with(['category', 'fournisseur'])->find($id);

        if (!$produit) {
            return response()->json(['message' => 'Produit not found'], 404);
        }

        return response()->json($produit, 200);
    }

    public function update(Request $request, $id)
    {
        $produit = Produit::find($id);

        if (!$produit) {
            return response()->json(['message' => 'Produit not found'], 404);
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:150',
            'categorie_id' => 'required|exists:categories,id',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'numero_lot' => 'required|string|max:100',
            'date_peremption' => 'required|date',
            'quantite_boites' => 'integer|min:0',
            'quantite_unites' => 'integer|min:0',
            'prix' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $produit->update($validated);

        return response()->json($produit, 200);
    }

    public function destroy($id)
    {
        $produit = Produit::find($id);

        if (!$produit) {
            return response()->json(['message' => 'Produit not found'], 404);
        }

        $produit->delete();

        return response()->json(['message' => 'Produit deleted successfully'], 200);
    }
}

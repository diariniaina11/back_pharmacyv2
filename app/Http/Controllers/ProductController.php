<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::with(['category', 'fournisseur'])->get(), 200);
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

        $product = Product::create($validated);

        return response()->json($product->load(['category', 'fournisseur']), 201);
    }

    public function show($id)
    {
        $product = Product::with(['category', 'fournisseur'])->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product, 200);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validated = $request->validate([
            'nom' => 'sometimes|string|max:150',
            'categorie_id' => 'sometimes|exists:categories,id',
            'fournisseur_id' => 'sometimes|exists:fournisseurs,id',
            'numero_lot' => 'sometimes|string|max:100',
            'date_peremption' => 'sometimes|date',
            'quantite_boites' => 'sometimes|integer|min:0',
            'quantite_unites' => 'sometimes|integer|min:0',
            'prix' => 'sometimes|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $product->update($validated);

        return response()->json($product->load(['category', 'fournisseur']), 200);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}

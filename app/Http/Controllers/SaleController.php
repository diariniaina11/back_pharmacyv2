<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        return response()->json(Sale::with(['product', 'user'])->get(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'quantite_vendue' => 'required|integer|min:1',
            'date_vente' => 'required|date',
        ]);

        $sale = Sale::create($validated);

        return response()->json($sale->load(['product', 'user']), 201);
    }

    public function show($id)
    {
        $sale = Sale::with(['product', 'user'])->find($id);

        if (!$sale) {
            return response()->json(['message' => 'Sale not found'], 404);
        }

        return response()->json($sale, 200);
    }

    public function update(Request $request, $id)
    {
        $sale = Sale::find($id);

        if (!$sale) {
            return response()->json(['message' => 'Sale not found'], 404);
        }

        $validated = $request->validate([
            'product_id' => 'sometimes|exists:products,id',
            'user_id' => 'sometimes|exists:users,id',
            'quantite_vendue' => 'sometimes|integer|min:1',
            'date_vente' => 'sometimes|date',
        ]);

        $sale->update($validated);

        return response()->json($sale->load(['product', 'user']), 200);
    }

    public function destroy($id)
    {
        $sale = Sale::find($id);

        if (!$sale) {
            return response()->json(['message' => 'Sale not found'], 404);
        }

        $sale->delete();

        return response()->json(['message' => 'Sale deleted successfully'], 200);
    }
}

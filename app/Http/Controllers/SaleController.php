<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        return DB::transaction(function () use ($validated) {
            $product = Product::lockForUpdate()->find($validated['product_id']);
            
            if ($product->quantite_boites < $validated['quantite_vendue']) {
                return response()->json(['message' => 'Stock insuffisant'], 400);
            }

            $product->decrement('quantite_boites', $validated['quantite_vendue']);
            $sale = Sale::create($validated);

            return response()->json($sale->load(['product', 'user']), 201);
        });
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

        return DB::transaction(function () use ($sale, $validated) {
            if (isset($validated['quantite_vendue'])) {
                $product = Product::find($sale->product_id);
                // Revert old quantity, apply new one
                $product->increment('quantite_boites', $sale->quantite_vendue);
                $product->decrement('quantite_boites', $validated['quantite_vendue']);
            }
            
            $sale->update($validated);
            return response()->json($sale->load(['product', 'user']), 200);
        });
    }

    public function destroy($id)
    {
        $sale = Sale::find($id);

        if (!$sale) {
            return response()->json(['message' => 'Sale not found'], 404);
        }

        return DB::transaction(function () use ($sale) {
            $product = Product::find($sale->product_id);
            $product->increment('quantite_boites', $sale->quantite_vendue);
            $sale->delete();
            return response()->json(['message' => 'Sale deleted successfully'], 200);
        });
    }
}

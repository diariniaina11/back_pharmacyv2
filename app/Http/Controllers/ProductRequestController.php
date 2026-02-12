<?php

namespace App\Http\Controllers;

use App\Models\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ProductRequestController extends Controller
{
    public function index()
    {
        return response()->json(ProductRequest::with(['product', 'user'])->get(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'quantite_demandee' => 'required|integer|min:1',
            'commentaire' => 'nullable|string',
            'status' => ['sometimes', Rule::in(['EN_ATTENTE', 'VALIDE', 'REFUSE'])],
            'date_creation' => 'sometimes|date',
        ]);

        if (!isset($validated['date_creation'])) {
            $validated['date_creation'] = now()->toDateString();
        }

        $productRequest = ProductRequest::create($validated);

        return response()->json($productRequest->load(['product', 'user']), 201);
    }

    public function show($id)
    {
        $productRequest = ProductRequest::with(['product', 'user'])->find($id);

        if (!$productRequest) {
            return response()->json(['message' => 'Product request not found'], 404);
        }

        return response()->json($productRequest, 200);
    }

    public function update(Request $request, $id)
    {
        $productRequest = ProductRequest::find($id);

        if (!$productRequest) {
            return response()->json(['message' => 'Product request not found'], 404);
        }

        $validated = $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'user_id' => 'sometimes|exists:users,id',
            'quantite_demandee' => 'sometimes|integer|min:1',
            'commentaire' => 'nullable|string',
            'status' => ['sometimes', Rule::in(['EN_ATTENTE', 'VALIDE', 'REFUSE'])],
            'date_creation' => 'sometimes|date',
        ]);

        return DB::transaction(function () use ($productRequest, $validated) {
            $oldStatus = $productRequest->status;
            $productRequest->update($validated);
            
            // If status changed to VALIDE, increment stock
            if ($oldStatus !== 'VALIDE' && $productRequest->status === 'VALIDE' && $productRequest->product_id) {
                $product = Product::find($productRequest->product_id);
                if ($product) {
                    $product->increment('quantite_boites', $productRequest->quantite_demandee);
                }
            }
            
            // If it was VALIDE and changed to something else, we might need to decrement
            if ($oldStatus === 'VALIDE' && $productRequest->status !== 'VALIDE' && $productRequest->product_id) {
                 $product = Product::find($productRequest->product_id);
                 if ($product) {
                    $product->decrement('quantite_boites', $productRequest->quantite_demandee);
                 }
            }

            return response()->json($productRequest->load(['product', 'user']), 200);
        });
    }

    public function destroy($id)
    {
        $productRequest = ProductRequest::find($id);

        if (!$productRequest) {
            return response()->json(['message' => 'Product request not found'], 404);
        }

        return DB::transaction(function () use ($productRequest) {
            if ($productRequest->status === 'VALIDE' && $productRequest->product_id) {
                $product = Product::find($productRequest->product_id);
                if ($product) {
                    $product->decrement('quantite_boites', $productRequest->quantite_demandee);
                }
            }
            $productRequest->delete();
            return response()->json(['message' => 'Product request deleted successfully'], 200);
        });
    }
}

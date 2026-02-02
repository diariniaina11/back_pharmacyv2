<?php

namespace App\Http\Controllers;

use App\Models\ProductRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            'status' => ['nullable', Rule::in(['EN_ATTENTE', 'VALIDE', 'REFUSE'])],
            'date_creation' => 'required|date',
        ]);

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

        $productRequest->update($validated);

        return response()->json($productRequest->load(['product', 'user']), 200);
    }

    public function destroy($id)
    {
        $productRequest = ProductRequest::find($id);

        if (!$productRequest) {
            return response()->json(['message' => 'Product request not found'], 404);
        }

        $productRequest->delete();

        return response()->json(['message' => 'Product request deleted successfully'], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function index()
    {
        return response()->json(Fournisseur::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|unique:fournisseurs,nom|max:120',
        ]);

        $fournisseur = Fournisseur::create($validated);

        return response()->json($fournisseur, 201);
    }

    public function show($id)
    {
        $fournisseur = Fournisseur::find($id);

        if (!$fournisseur) {
            return response()->json(['message' => 'Fournisseur not found'], 404);
        }

        return response()->json($fournisseur, 200);
    }

    public function update(Request $request, $id)
    {
        $fournisseur = Fournisseur::find($id);

        if (!$fournisseur) {
            return response()->json(['message' => 'Fournisseur not found'], 404);
        }

        $validated = $request->validate([
            'nom' => 'required|string|unique:fournisseurs,nom,' . $id . '|max:120',
        ]);

        $fournisseur->update($validated);

        return response()->json($fournisseur, 200);
    }

    public function destroy($id)
    {
        $fournisseur = Fournisseur::find($id);

        if (!$fournisseur) {
            return response()->json(['message' => 'Fournisseur not found'], 404);
        }

        $fournisseur->delete();

        return response()->json(['message' => 'Fournisseur deleted successfully'], 200);
    }
}

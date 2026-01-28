<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;

class UtilisateurController extends Controller
{
    public function index()
    {
        return response()->json(Utilisateur::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:120',
            'email' => 'nullable|email|unique:utilisateurs,email|max:120',
        ]);

        $utilisateur = Utilisateur::create($validated);

        return response()->json($utilisateur, 201);
    }

    public function show($id)
    {
        $utilisateur = Utilisateur::find($id);

        if (!$utilisateur) {
            return response()->json(['message' => 'Utilisateur not found'], 404);
        }

        return response()->json($utilisateur, 200);
    }

    public function update(Request $request, $id)
    {
        $utilisateur = Utilisateur::find($id);

        if (!$utilisateur) {
            return response()->json(['message' => 'Utilisateur not found'], 404);
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:120',
            'email' => 'nullable|email|unique:utilisateurs,email,' . $id . '|max:120',
        ]);

        $utilisateur->update($validated);

        return response()->json($utilisateur, 200);
    }

    public function destroy($id)
    {
        $utilisateur = Utilisateur::find($id);

        if (!$utilisateur) {
            return response()->json(['message' => 'Utilisateur not found'], 404);
        }

        $utilisateur->delete();

        return response()->json(['message' => 'Utilisateur deleted successfully'], 200);
    }
}

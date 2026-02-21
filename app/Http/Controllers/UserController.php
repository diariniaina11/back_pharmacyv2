<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email|max:150',
            'password' => 'required|string|min:6',
            'role' => 'required|in:ADMIN,VENDEUR',
            'badge_id' => 'required|string|unique:users,badge_id|max:50',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json($user, 201);
    }

    public function show($identifier)
    {
        // Check if the identifier contains @ (potential email)
        if (str_contains($identifier, '@')) {
            // Search by email without strict validation
            $user = User::where('email', $identifier)->first();
        } else {
            // Search by ID
            $user = User::find($identifier);
        }

        if (!$user) {
            return response()->json([
                'message' => 'Aucun utilisateur correspondant'
            ], 404);
        }

        // Make the password visible in the response
        $user->makeVisible('password');

        return response()->json($user, 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validated = $request->validate([
            'nom' => 'sometimes|string|max:100',
            'prenom' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|unique:users,email,' . $id . '|max:150',
            'password' => 'sometimes|string|min:6',
            'role' => 'sometimes|in:ADMIN,VENDEUR',
            'badge_id' => 'sometimes|string|unique:users,badge_id,' . $id . '|max:50',
            'updated_at' => 'sometimes|date',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json($user, 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}

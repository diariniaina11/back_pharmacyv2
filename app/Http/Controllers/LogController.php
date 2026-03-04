<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Log::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'nullable|date',
            'action' => 'required|string|max:100',
            'info' => 'required|string|max:100',
            'user' => 'required|string|max:50',
        ]);

        $log = Log::create($validatedData);

        return response()->json($log, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $log = Log::find($id);

        if (!$log) {
            return response()->json(['message' => 'Log not found'], 404);
        }

        return response()->json($log, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $log = Log::find($id);

        if (!$log) {
            return response()->json(['message' => 'Log not found'], 404);
        }

        $validatedData = $request->validate([
            'date' => 'nullable|date',
            'action' => 'sometimes|required|string|max:100',
            'info' => 'sometimes|required|string|max:100',
            'user' => 'sometimes|required|string|max:50',
        ]);

        $log->update($validatedData);

        return response()->json($log, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $log = Log::find($id);

        if (!$log) {
            return response()->json(['message' => 'Log not found'], 404);
        }

        $log->delete();

        return response()->json(['message' => 'Log deleted successfully'], 200);
    }
}

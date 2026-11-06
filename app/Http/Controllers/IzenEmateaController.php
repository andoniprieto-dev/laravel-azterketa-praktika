<?php

namespace App\Http\Controllers;

use App\Models\Jarduera;
use App\Models\User;
use Illuminate\Http\Request;

class IzenEmateaController extends Controller
{
    // POST /api/jarduerak/{id}/izen-emateak
    public function store(Request $request, $id)
    {
        $jarduera = Jarduera::findOrFail($id);

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Estra: lehendik izena emanda?
        if ($jarduera->bazkideak()->where('users.id', $data['user_id'])->exists()) {
            return response()->json(['mezua' => 'Erabiltzaileak izena emanda du jada.'], 422);
        }

        // Estra: beteta?
        if ($jarduera->bazkideak()->count() >= $jarduera->edukiera) {
            return response()->json(['mezua' => 'Jarduera beteta dago.'], 422);
        }

        $jarduera->bazkideak()->attach($data['user_id'], ['ordainduta' => false]);

        return response()->json($jarduera->load('bazkideak'), 201);
    }

    // DELETE /api/jarduerak/{id}/izen-emateak/{userId}
    public function destroy($id, $userId)
    {
        $jarduera = Jarduera::findOrFail($id);
        User::findOrFail($userId);

        $jarduera->bazkideak()->detach($userId);

        return response()->noContent();
    }
}

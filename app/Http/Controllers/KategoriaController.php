<?php

namespace App\Http\Controllers;

use App\Models\Kategoria;
use Illuminate\Http\Request;

class KategoriaController extends Controller
{
    public function index()
    {
        return Kategoria::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'izena' => 'required|string|max:255|unique:kategoriak,izena',
            'deskribapena' => 'nullable|string',
        ]);

        return response()->json(Kategoria::create($data), 201);
    }

    public function show($id)
    {
        return Kategoria::with('jarduerak')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $kategoria = Kategoria::findOrFail($id);

        $data = $request->validate([
            'izena' => 'required|string|max:255|unique:kategoriak,izena,' . $kategoria->id,
            'deskribapena' => 'nullable|string',
        ]);

        $kategoria->update($data);

        return $kategoria;
    }

    public function destroy($id)
    {
        $kategoria = Kategoria::findOrFail($id);

        if ($kategoria->jarduerak()->exists()) {
            return response()->json([
                'mezua' => 'Ezin da kategoria ezabatu: jarduerak ditu.',
            ], 409);
        }

        $kategoria->delete();

        return response()->noContent();
    }
}

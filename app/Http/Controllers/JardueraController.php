<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Jarduera;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JardueraController extends Controller
{
    private function arauak(): array
    {
        return [
            'izenburua' => 'required|string|max:255',
            'deskribapena' => 'nullable|string',
            'data' => 'required|date',
            'edukiera' => 'required|integer|min:1',
            'prezioa' => 'required|numeric|min:0',
            'kategoria_id' => 'required|exists:kategoriak,id',
            // Estra: begiraleak 'begiralea' rola izan behar du
            'begiralea_id' => [
                'nullable',
                Rule::exists('users', 'id')->where('role', Role::Begiralea->value),
            ],
        ];
    }

    public function index()
    {
        // Eager loading: 3 kontsulta, jarduera kopurua edozein dela
        return Jarduera::with(['kategoria', 'begiralea'])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->arauak());

        return response()->json(Jarduera::create($data), 201);
    }

    public function show($id)
    {
        return Jarduera::with(['kategoria', 'begiralea', 'bazkideak'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $jarduera = Jarduera::findOrFail($id);

        $data = $request->validate($this->arauak());

        $jarduera->update($data);

        return $jarduera;
    }

    public function destroy($id)
    {
        Jarduera::findOrFail($id)->delete();   // izen-emateak cascade bidez ezabatzen dira

        return response()->noContent();
    }
}

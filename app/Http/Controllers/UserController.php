<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        return $query->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => ['nullable', Rule::enum(Role::class)],
            'telefonoa' => 'nullable|string|max:20',
        ]);

        $user = User::create($data);   // password cast 'hashed' -> automatikoki hash-eatzen da

        return response()->json($user, 201);
    }

    public function show($id)
    {
        return User::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'role' => ['nullable', Rule::enum(Role::class)],
            'telefonoa' => 'nullable|string|max:20',
        ]);

        if (empty($data['password'])) {
            unset($data['password']);  // ez bada bidali, zaharra mantendu
        }

        $user->update($data);

        return $user;
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return response()->noContent();   // 204
    }

    // GET /api/users/{id}/jarduerak
    public function jarduerak($id)
    {
        return User::findOrFail($id)->jarduerak;   // pivot->ordainduta barne
    }
}

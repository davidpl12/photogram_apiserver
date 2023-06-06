<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $rol = Role::create($request->all());
        return response()->json($rol, 201);
    }

    public function show($id)
    {
        $rol = Role::findOrFail($id);
        return response()->json($rol);
    }

    public function update(Request $request, $id)
    {
        $rol = Role::findOrFail($id);
        $rol->update($request->all());
        return response()->json($rol);
    }

    public function destroy($id)
    {
        $rol = Role::findOrFail($id);
        $rol->delete();
        return response()->json(null, 204);
    }

    public function assignRole(Request $request, $userId)
{
    $user = User::findOrFail($userId);
    $user->rol_id = $request->input('rol_id');
    $user->save();

    return response()->json(['message' => 'Rol asignado correctamente']);
}

}

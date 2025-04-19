<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all());
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['error' => 'Usuario no encontrado'], 404);

        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['error' => 'Usuario no encontrado'], 404);

        $validator = Validator::make($request->all(), [
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->fill($request->only(['name', 'email']));
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['error' => 'Usuario no encontrado'], 404);

        $user->delete();

        return response()->json(['message' => 'Usuario eliminado con éxito']);
    }
}
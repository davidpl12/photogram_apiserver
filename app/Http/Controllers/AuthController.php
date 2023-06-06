<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Registro de un nuevo usuario.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'apellidos' => 'required',
            'sexo' => 'required',
            'email' => 'required|email|unique:usuarios',
            'user' => 'required',
            'password' => 'required|min:6',
            'fecha_nac' => 'required',
        ]);

        $validatedData['password'] = bcrypt($request->password);


        $usuario = new User;

        $usuario->nombre = $request->input('nombre');
        $usuario->apellidos = $request->input('apellidos');
        $usuario->sexo = $request->input('sexo');
        $usuario->email = $request->input('email');
        $usuario->user = $request->input('user');
        $usuario->password = bcrypt($request->input('password'));
        $usuario->fecha_nac = $request->input('fecha_nac');

         // Procesar la imagen de perfil si se proporciona
         if ($request->hasFile('foto_perfil')) {
            $fotoPerfil = $request->file('foto_perfil');
            $nombreUsuario = $request->user;
            $nombreimagen = Str::slug($nombreUsuario) . "." . $fotoPerfil->guessExtension();

            $rutaCarpeta = public_path("img/perfil/" . $nombreUsuario);
            if (!is_dir($rutaCarpeta)) {
                mkdir($rutaCarpeta, 0755, true);
            }

            $rutaImagen = $rutaCarpeta . '/' . $nombreimagen;
            $fotoPerfil->move($rutaCarpeta, $nombreimagen);
            $usuario->foto_perfil = $nombreUsuario . '/' . $nombreimagen;
        }
        $usuario->fecha_registro = now();
        $usuario->rol_id = 2;

        $usuario->save();


        return response()->json(['message' => 'Registro exitoso'], 201);
    }

    /**
     * Inicio de sesión de un usuario.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        $user = $request->user();
        $token = $user->createToken('token-name')->plainTextToken;
        $id=$user->id;
        $id_rol=$user->rol_id;

        return response()->json(['access_token' => $token, 'userRole' => $id_rol, 'id' => $id], 200);
    }

    /**
     * Cierre de sesión de un usuario.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Cierre de sesión exitoso']);
    }

    public function checkEmailAvailability(Request $request)
    {
        $email = $request->input('email');

        $user = User::where('email', $email)->first();

        return response()->json([
            'available' => !$user
        ]);
    }

    public function checkUserAvailability(Request $request)
    {
        $user = $request->input('user');

        $user = User::where('user', $user)->first();

        return response()->json([
            'available' => !$user
        ]);
    }
}

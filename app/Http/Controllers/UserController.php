<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::all();
        return response()->json($usuarios);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $usuario = new User;
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
        $usuario->nombre = $request->input('nombre');
        $usuario->apellidos = $request->input('apellidos');
        $usuario->sexo = $request->input('sexo');
        $usuario->email = $request->input('email');
        $usuario->user = $request->input('user');
        $usuario->password = bcrypt($request->input('password'));
        $usuario->fecha_nac = $request->input('fecha_nac');


        $usuario->save();

        return response()->json($usuario, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuario = User::find($id);
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        return response()->json($usuario);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
{
    $usuario = User::findOrFail($id);

    // Validar si se ha proporcionado una foto de perfil
    if ($request->hasFile('foto_perfil')) {
        $fotoPerfil = $request->file('foto_perfil');

        // Obtener el nombre original del archivo
        $nombreOriginal = $fotoPerfil->getClientOriginalName();

        // Generar un nombre único para el archivo
        $nombreArchivo = time() . '_' . $nombreOriginal;

        // Almacenar la imagen en el disco 'public'
        $rutaImagen = $fotoPerfil->storeAs('perfil', $nombreArchivo, 'public');

        // Actualizar la ruta de la foto de perfil en el usuario
        $usuario->foto_perfil = $rutaImagen;
    }

    // Actualizar los demás campos del usuario
    $usuario->nombre = $request->input('nombre');
    $usuario->apellidos = $request->input('apellidos');
    $usuario->sexo = $request->input('sexo');
    $usuario->email = $request->input('email');
    $usuario->user = $request->input('user');
    $usuario->password = bcrypt($request->input('password'));
    $usuario->fecha_nac = $request->input('fecha_nac');
    $usuario->rol_id = $request->input('rol_id');

    $usuario->save();

    return response()->json($usuario);
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $usuario = User::find($id);
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        $fotoPerfil = $usuario->foto_perfil; // Obtener la ruta de la imagen de perfil

        $usuario->delete();
        if ($fotoPerfil && file_exists(public_path("img/perfil/" . $fotoPerfil))) {
            unlink(public_path("img/perfil/" . $fotoPerfil));
            $carpetaUsuario = dirname(public_path("img/perfil/" . $fotoPerfil));
            rmdir($carpetaUsuario);
        }

        $carpetaPubli = public_path("img/publicaciones/" . $id);

    if (File::exists($carpetaPubli)) {
        File::deleteDirectory($carpetaPubli);
    }


        return response()->json(['message' => 'Usuario eliminado']);
    }

    public function getUserData()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'No se encontró el usuario'], 401);
        }

        $userData = [
            'id' => $user->id,
            'nombre' => $user->nombre,
            'apellidos' => $user->apellidos,
            'sexo' => $user->sexo,
            'email' => $user->email,
            'user' => $user->user,
            'password' => $user->password,
            'fecha_nac' => $user->fecha_nac,
            'foto_perfil' => $user->foto_perfil,
            'fecha_registro' => $user->fecha_registro,
            'rol_id' => $user->rol_id,
        ];

        return response()->json($userData);
    }



    public function updateRole(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $role = Role::findOrFail($request->input('rol_id'));

        $user->role()->associate($role);
        $user->save();

        return redirect()->back()->with('success', 'Rol asignado correctamente.');
    }


    public function searchUsers(Request $request)
    {
        $keyword = $request->input('search');

        $users = User::where('nombre', 'like', '%' . $keyword . '%')
                     ->orWhere('user', 'like', '%' . $keyword . '%')
                     ->get();

        return response()->json($users);
    }
}

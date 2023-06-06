<?php

namespace App\Http\Controllers;

use App\Models\Reaccion;
use Illuminate\Http\Request;

class ReaccionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reacciones = Reaccion::all();
        return response()->json($reacciones);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reaccion = new Reaccion;
        $reaccion->user = $request->input('user');
        $reaccion->publicacion = $request->input('publicacion');
        $reaccion->fecha_reaccion = $request->input('fecha_reaccion');
        $reaccion->save();

        return response()->json($reaccion, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reaccion = Reaccion::find($id);
        if (!$reaccion) {
            return response()->json(['message' => 'Reaccion no encontrada'], 404);
        }
        return response()->json($reaccion);
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
        $reaccion = Reaccion::find($id);
        if (!$reaccion) {
            return response()->json(['message' => 'Reaccion no encontrada'], 404);
        }
        $reaccion->user = $request->input('user');
        $reaccion->publicación = $request->input('publicación');
        $reaccion->fecha_reaccion = $request->input('fecha_reaccion');
        $reaccion->save();

        return response()->json($reaccion);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reaccion = Reaccion::find($id);
        if (!$reaccion) {
            return response()->json(['message' => 'Reaccion no encontrada'], 404);
        }
        $reaccion->delete();

        return response()->json(['message' => 'Reaccion eliminada']);
    }


    public function getReacciones($publicacion)
    {
        $reacciones = Reaccion::where('publicacion', $publicacion)->get();

        return response()->json($reacciones);
    }


    public function getNumReacciones($idpublicacion)
    {
        $numeroReacciones = Reaccion::where('publicacion', $idpublicacion)->count();

        return response()->json(['num_reaccion' => $numeroReacciones]);
    }




    public function darMeGusta(Request $request)
    {
        // Obtener el ID del usuario que realiza la reacción desde el token de autenticación u otra fuente
        $usuarioId = $request->input('usuarioId');

        // Obtener el ID de la publicación a la que se da "me gusta" desde la solicitud
        $publicacionId = $request->input('publicacionId');

        // Comprobar si ya existe una reacción para el usuario y la publicación especificada
        $reaccion = Reaccion::where('user', $usuarioId)
            ->where('publicacion', $publicacionId)
            ->first();

        if ($reaccion) {
            // La reacción ya existe, no se hace nada
            return response()->json(['success' => false, 'message' => 'Ya has dado "me gusta" a esta publicación']);
        }

        // Crear una nueva reacción
        $reaccion = new Reaccion;
        $reaccion->user = $usuarioId;
        $reaccion->publicacion = $publicacionId;
        $reaccion->save();

        return response()->json(['success' => true, 'message' => 'Has dado "me gusta" a esta publicación']);
    }

    public function quitarMeGusta(Request $request)
    {
        // Obtener el ID del usuario que realiza la acción desde el token de autenticación u otra fuente
        $usuarioId =$request->input('usuarioId');

        // Obtener el ID de la publicación de la cual se quita el "me gusta" desde la solicitud
        $publicacionId = $request->input('publicacionId');

        // Buscar y eliminar la reacción del usuario a la publicación
        $reaccion = Reaccion::where('user', $usuarioId)
            ->where('publicacion', $publicacionId)
            ->first();

        if ($reaccion) {
            $reaccion->delete();
            return response()->json(['success' => true, 'message' => 'Has quitado "me gusta" a esta publicación']);
        }

        return response()->json(['success' => false, 'message' => 'No has dado "me gusta" a esta publicación']);
    }


    public function verificarMeGusta($usuarioId, $publicacionId)
    {

        $meGusta = Reaccion::where('user', $usuarioId)
            ->where('publicacion', $publicacionId)
            ->exists();

        return response()->json($meGusta);
    }


}

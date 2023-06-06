<?php

namespace App\Http\Controllers;

use App\Models\Camara;
use Illuminate\Http\Request;

class CamarasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $camaras = Camara::all();
        return response()->json($camaras);    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $camara = new Camara;
        $camara->marca = $request->input('marca');
        $camara->modelo = $request->input('modelo');
        $camara->descripcion = $request->input('descripcion');
        $camara->valoracion = $request->input('valoracion');
        $camara->save();

        return response()->json($camara, 201);    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $camara = Camara::find($id);

        if (!$camara) {
            return response()->json(['message' => 'Camara not found'], 404);
        }

        return response()->json($camara);    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $camara = Camara::find($id);

        if (!$camara) {
            return response()->json(['message' => 'Camara not found'], 404);
        }

        $camara->marca = $request->input('marca');
        $camara->modelo = $request->input('modelo');
        $camara->descripcion = $request->input('descripcion');
        $camara->valoracion = $request->input('valoracion');
        $camara->save();

        return response()->json($camara);    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $camara = Camara::find($id);

        if (!$camara) {
            return response()->json(['message' => 'Camara not found'], 404);
        }

        $camara->delete();

        return response()->json(['message' => 'Camara deleted']);    }

}

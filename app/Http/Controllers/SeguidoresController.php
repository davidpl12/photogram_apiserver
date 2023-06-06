<?php

namespace App\Http\Controllers;

use App\Models\Seguidor;
use Illuminate\Http\Request;

class SeguidoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seguidores = Seguidor::all();
        return response()->json($seguidores);    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $seguidor = Seguidor::create($request->all());
        return response()->json($seguidor, 201);    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $seguidor = Seguidor::find($id);
        if (!$seguidor) {
            return response()->json(['message' => 'Seguidor no encontrado'], 404);
        }
        return response()->json($seguidor);    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $seguidor = Seguidor::find($id);
        if (!$seguidor) {
            return response()->json(['message' => 'Seguidor no encontrado'], 404);
        }
        $seguidor->update($request->all());
        return response()->json($seguidor);    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $seguidor = Seguidor::find($id);
        if (!$seguidor) {
            return response()->json(['message' => 'Seguidor no encontrado'], 404);
        }
        $seguidor->delete();
        return response()->json(['message' => 'Seguidor eliminado']);    }


        public function getSeguidores($idRecibe)
        {
            $seguidores = Seguidor::where('usuario_recibe', $idRecibe)->get();

            return response()->json($seguidores);
        }


        public function getNumSeguidores($idRecibe)
        {
            $numeroSeguidores = Seguidor::where('usuario_recibe', $idRecibe)->count();

            return response()->json(['num_seguidores' => $numeroSeguidores]);
        }


        public function getSeguidos($idEnvia)
        {
            $seguidos = Seguidor::where('usuario_envia', $idEnvia)->get();

            return response()->json($seguidos);
        }


        public function getNumSeguidos($idEnvia)
        {
            $numeroSeguidos = Seguidor::where('usuario_envia', $idEnvia)->count();

            return response()->json(['num_seguidos' => $numeroSeguidos]);
        }

        public function verificarSeguidor($usuarioRecibeId, $usuarioEnviaId)
{
    $seguidor = Seguidor::where('usuario_recibe', $usuarioRecibeId)
                        ->where('usuario_envia', $usuarioEnviaId)
                        ->first();

    return response()->json([
        'siguiendo' => $seguidor ? true : false
    ]);
}
public function seguirUsuario(Request $request)
    {
        // Obtener el ID del usuario que envía la solicitud desde el token de autenticación o de otra manera
        $usuarioEnviaId = $request->input('usuarioEnviaId');

        // Obtener el ID del usuario a seguir desde la solicitud
        $usuarioRecibeId = $request->input('usuarioRecibeId');

        // Comprobar si la relación de seguimiento ya existe en la base de datos
        $seguidor = Seguidor::where('usuario_envia', $usuarioEnviaId)
            ->where('usuario_recibe', $usuarioRecibeId)
            ->first();

        if ($seguidor) {
            // La relación de seguimiento ya existe, no se hace nada
            return response()->json(['success' => false, 'message' => 'Ya sigues a este usuario']);
        }

        // Crear una nueva relación de seguimiento
        $seguidor = new Seguidor;
        $seguidor->usuario_envia = $usuarioEnviaId;
        $seguidor->usuario_recibe = $usuarioRecibeId;
        $seguidor->fecha_amistad = now()->format('Y-m-d H:i:s'); // Ajustar el formato de fecha
        $seguidor->save();

        return response()->json(['success' => true, 'message' => 'Has seguido a este usuario']);
    }

    public function dejarSeguirUsuario(Request $request)
    {
        // Obtener el ID del usuario que envía la solicitud desde el token de autenticación o de otra manera
        $usuarioEnviaId = $request->input('usuarioEnviaId');

        // Obtener el ID del usuario a dejar de seguir desde la solicitud
        $usuarioRecibeId = $request->input('usuarioRecibeId');

        // Buscar la relación de seguimiento en la base de datos
        $seguidor = Seguidor::where('usuario_envia', $usuarioEnviaId)
            ->where('usuario_recibe', $usuarioRecibeId)
            ->first();

        if (!$seguidor) {
            // La relación de seguimiento no existe, no se hace nada
            return response()->json(['success' => false, 'message' => 'No sigues a este usuario']);
        }

        // Eliminar la relación de seguimiento
        $seguidor->delete();

        return response()->json(['success' => true, 'message' => 'Has dejado de seguir a este usuario']);
    }

}

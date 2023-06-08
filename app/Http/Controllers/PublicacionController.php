<?php

namespace App\Http\Controllers;

use App\Models\Seguidor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use App\Models\Publicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $publicaciones = Publicacion::all();

        return response()->json($publicaciones);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $publicacion = new Publicacion;
        // Asignar los valores del request a las propiedades del modelo Publicacion
        if (Auth::check()) {
            $autor = Auth::user()->id;
            // Resto del código aquí
        } else {
        }
        $publicacion->autor = $request->input('autor');
        $publicacion->descripcion = $request->input('descripcion');
        $publicacion->lugar_realizacion = $request->input('lugar_realizacion');
        $publicacion->licencia = $request->input('licencia');
        $publicacion->camara = $request->input('camara');
        $fechaPublic = date('Y-m-d H:i:s', strtotime($request->input('fecha_public')));

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreUsuario = $request->autor;
            $nombreImagen = Str::slug($nombreUsuario) . "-" . pathinfo($imagen->getClientOriginalName(), PATHINFO_FILENAME) . "." . $imagen->guessExtension();

            $carpetaUsuario = public_path("img/publicaciones/" . $nombreUsuario);

            if (!file_exists($carpetaUsuario)) {
                // Crear la carpeta del usuario si no existe
                mkdir($carpetaUsuario, 0755, true);
            }

            $rutaImagen = $carpetaUsuario . "/" . $nombreImagen;

            $imagen->move($carpetaUsuario, $nombreImagen);

            $publicacion->imagen = $nombreUsuario . '/' . $nombreImagen;
        }
        $publicacion->num_reacciones = $request->input('num_reacciones');
        $publicacion->album = $request->input('album');
        $publicacion->fecha_public = $fechaPublic;
        // Asignar el resto de los campos necesarios

        $publicacion->save();

        return response()->json($publicacion, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $publicacion = Publicacion::findOrFail($id);



        return response()->json($publicacion);
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
        $publicacion = Publicacion::findOrFail($id);
        $publicacion->autor = $request->input('autor');
        $publicacion->descripcion = $request->input('descripcion');
        $publicacion->lugar_realizacion = $request->input('lugar_realizacion');
        $publicacion->licencia = $request->input('licencia');
        $publicacion->camara = $request->input('camara');
        $fechaPublic = date('Y-m-d H:i:s', strtotime($request->input('fecha_public')));

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreUsuario = $request->autor;
            $nombreImagen = Str::slug($nombreUsuario) . "-" . pathinfo($imagen->getClientOriginalName(), PATHINFO_FILENAME) . "." . $imagen->guessExtension();

            $carpetaUsuario = public_path("img/publicaciones/" . $nombreUsuario);

            if (!file_exists($carpetaUsuario)) {
                // Crear la carpeta del usuario si no existe
                mkdir($carpetaUsuario, 0755, true);
            }

            $rutaImagen = $carpetaUsuario . "/" . $nombreImagen;

            $imagen->move($carpetaUsuario, $nombreImagen);

            $publicacion->imagen = $nombreUsuario . '/' . $nombreImagen;;
        }
        $publicacion->num_reacciones = $request->input('num_reacciones');
        $publicacion->album = $request->input('album');
        $publicacion->fecha_public = $fechaPublic;
        $publicacion->save();

        return response()->json($publicacion);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $publicacion = Publicacion::findOrFail($id);
        $imagen = $publicacion->imagen; // Obtener la ruta de la imagen

        $publicacion->delete();

          // Eliminar la imagen si existe
    if ($imagen && file_exists(public_path("img/publicaciones/" . $imagen))) {
        $rutaImagen = public_path("img/publicaciones/" . $imagen);

        // Eliminar la imagen
        unlink($rutaImagen);

        // Obtener la ruta de la carpeta de publicación
        $carpetaPublicacion = dirname($rutaImagen);

        // Verificar si la carpeta está vacía
        if (count(scandir($carpetaPublicacion)) == 2) { // La función scandir() devuelve "." y ".." siempre
            // La carpeta está vacía, se puede eliminar
            rmdir($carpetaPublicacion);
        }
    }

        return response()->json(null, 204);
    }

    public function getPublicacionesPorAutor($idAutor)
    {
        $publicaciones = Publicacion::where('autor', $idAutor)->get();

        return response()->json($publicaciones);
    }
    /*
    public function getPublicacionesPorAutoresSeguidos($autoresSeguidos)
    {
        $publicaciones = Publicacion::whereIn('autor', $autoresSeguidos)->get();

        return response()->json($publicaciones);
    }
    */


    public function getPublicacionByCamara($idCamara)
    {
        $publicaciones = Publicacion::where('camara', $idCamara)->get();

        return response()->json($publicaciones);
    }

    public function getPublicacionByAlbum($idAlbum)
    {
        $publicaciones = Publicacion::where('album', $idAlbum)->get();

        return response()->json($publicaciones);
    }

    public function obtenerPublicacionesPorUsuarioCamara($idUsuario)
    {
        $publicaciones = Publicacion::where('autor', $idUsuario)
            ->with('camara') // Asegúrate de tener las relaciones definidas en el modelo
            ->get();

        return response()->json($publicaciones);
    }


    public function getPublicacionesSeguidos($idUsuario)
    {
            //$user = User::find($idUsuario); // Obtener el usuario actualmente autenticado o el usuario que envía la solicitud

        $followedUsers = Seguidor::where('usuario_envia', $idUsuario)->pluck('usuario_recibe'); // Obtener los IDs de los usuarios seguidos

        $posts = Publicacion::whereIn('autor', $followedUsers)->orderBy('created_at', 'desc')->get(); // Obtener las publicaciones de los usuarios seguidos

        return response()->json($posts);
    }




}

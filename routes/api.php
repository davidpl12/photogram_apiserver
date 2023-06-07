<?php

use App\Http\Controllers\RolController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\PublicacionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SeguidoresController;
use App\Http\Controllers\ReaccionController;
use App\Http\Controllers\CamarasController;
use Illuminate\Support\Facades\Storage;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/check-email', [AuthController::class, 'checkEmailAvailability']);
Route::post('/check-user', [AuthController::class, 'checkUserAvailability']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::get('/albumes', [AlbumController::class, 'index'])->middleware('auth:sanctum');
Route::post('/albumes', [AlbumController::class, 'store'])->middleware('auth:sanctum');
Route::get('/albumes/{id}', [AlbumController::class, 'show'])->middleware('auth:sanctum');
Route::put('/albumes/{id}', [AlbumController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/albumes/{id}', [AlbumController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('/publicaciones', [PublicacionController::class, 'index'])->middleware('auth:sanctum');
Route::post('/publicaciones', [PublicacionController::class, 'store'])->middleware('auth:sanctum');
Route::get('/publicaciones/{id}', [PublicacionController::class, 'show'])->middleware('auth:sanctum');
Route::put('/publicaciones/{id}', [PublicacionController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/publicaciones/{id}', [PublicacionController::class, 'destroy'])->middleware('auth:sanctum');
Route::get('publicaciones/autor/{idAutor}', [PublicacionController::class, 'getPublicacionesPorAutor'])->middleware('auth:sanctum');
Route::get('publicaciones/camara/{idCamara}', [PublicacionController::class, 'getPublicacionByCamara'])->middleware('auth:sanctum');
Route::get('publicaciones/album/{idAlbum}', [PublicacionController::class, 'getPublicacionByAlbum'])->middleware('auth:sanctum');
//Route::get('publicaciones/autorSeguido/{autoresSeguidos}', [PublicacionController::class, 'getPublicacionesPorAutoresSeguidos']);


Route::get('/usuarios', [UserController::class, 'index'])->middleware('auth:sanctum');
Route::get('/usuarios/{id}', [UserController::class, 'show'])->middleware('auth:sanctum');
Route::post('/usuarios', [UserController::class, 'store'])->middleware('auth:sanctum');
Route::put('/usuarios/{id}', [UserController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/usuarios/{id}', [UserController::class, 'destroy'])->middleware('auth:sanctum');
Route::get('/user-data', [UserController::class, 'getUserData'])->middleware('auth:sanctum');
Route::post('usuarios/{userId}/assign-role', [RolController::class, 'assignRole']);
Route::get('/busquedad', [UserController::class, 'searchUsers']);



Route::get('/seguidores', [SeguidoresController::class, 'index'])->middleware('auth:sanctum');
Route::post('/seguidores', [SeguidoresController::class, 'store'])->middleware('auth:sanctum');
Route::get('/seguidores/{id}', [SeguidoresController::class, 'show'])->middleware('auth:sanctum');
Route::put('/seguidores/{id}', [SeguidoresController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/seguidores/{id}', [SeguidoresController::class, 'destroy'])->middleware('auth:sanctum');
Route::get('/todosseguidores/{idRecibe}', [SeguidoresController::class, 'getSeguidores'])->middleware('auth:sanctum');
Route::get('/num-seguidores/{idRecibe}', [SeguidoresController::class, 'getNumSeguidores'])->middleware('auth:sanctum');
Route::get('/todosseguidos/{idEnvia}', [SeguidoresController::class, 'getSeguidos'])->middleware('auth:sanctum');
Route::get('/num-seguidos/{idEnvia}', [SeguidoresController::class, 'getNumSeguidos'])->middleware('auth:sanctum');
Route::get('/usuarios/{usuarioRecibeId}/verificar-seguidor/{usuarioEnviaId}', [SeguidoresController::class, 'verificarSeguidor'])->middleware('auth:sanctum');
Route::post('/usuarios/seguir', [SeguidoresController::class, 'seguirUsuario']);
Route::post('/usuarios/dejar-seguir', [SeguidoresController::class, 'dejarSeguirUsuario']);



Route::get('/reacciones', [ReaccionController::class, 'index']);
Route::post('/reacciones', [ReaccionController::class, 'store']);
Route::get('/reacciones/{id}', [ReaccionController::class, 'show']);
Route::put('/reacciones/{id}', [ReaccionController::class, 'update']);
Route::delete('/reacciones/{id}', [ReaccionController::class, 'destroy']);
Route::delete('/reacciones/{userId}/{publicacionId}', [ReaccionController::class, 'eliminarReaccion'])->middleware('auth:sanctum');
//Route::get('/reacciones/verificar/{userId}/{publicacionId}', 'ReaccionController@verificarReaccion');

Route::post('/me-gusta', [ReaccionController::class, 'darMeGusta']);
Route::delete('/no-me-gusta', [ReaccionController::class, 'quitarMeGusta']);
Route::get('/verificar-megusta/{usuarioId}/{publicacionId}', [ReaccionController::class, 'verificarMeGusta']);
Route::get('/reacciones/numero/{publicacionId}', [ReaccionController::class, 'getNumReacciones']);


Route::get('/camaras', [CamarasController::class, 'index'])->middleware('auth:sanctum');
Route::get('/camaras/{id}', [CamarasController::class, 'show'])->middleware('auth:sanctum');
Route::post('/camaras', [CamarasController::class, 'store'])->middleware('auth:sanctum')->middleware('admin');
Route::put('/camaras/{id}', [CamarasController::class, 'update'])->middleware('auth:sanctum')->middleware('admin');
Route::delete('/camaras/{id}', [CamarasController::class, 'destroy'])->middleware('auth:sanctum')->middleware('admin');


Route::prefix('roles')->group(function () {
    Route::get('/', [RolController::class, 'index']);
    Route::post('/', [RolController::class, 'store'])->middleware('auth:sanctum')->middleware('admin');
    Route::get('/{id}', [RolController::class, 'show']);
    Route::put('/{id}', [RolController::class, 'update'])->middleware('auth:sanctum')->middleware('admin');
    Route::delete('/{id}', [RolController::class, 'destroy'])->middleware('auth:sanctum')->middleware('admin');
});



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

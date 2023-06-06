<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaccion extends Model
{
    use HasFactory;


    protected $table = 'reacciones';
    protected $fillable = [
        'id',
        'user',
        'publicacion',
        'fecha_reaccion',
    ];

    // Relación con la tabla de publicaciones
    public function publicacion()
    {
        return $this->belongsTo(Publicacion::class);
    }

    // Relación con la tabla de usuarios
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}

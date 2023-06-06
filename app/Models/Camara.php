<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Camara extends Model
{
    use HasFactory;

    protected $table = 'camaras';

    protected $fillable = [
        'id',
        'marca',
        'modelo',
        'descripcion',
        'valoracion'
    ];

    // Relación con la tabla de publicaciones
    public function publicaciones()
    {
        return $this->hasMany(Publicacion::class, 'camara');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $table = 'albumes';
    protected $fillable = ['nombre_album', 'descripcion'];

    public function publicaciones()
    {
        return $this->hasMany(Publicacion::class, 'album');
    }
}

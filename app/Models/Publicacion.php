<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publicacion extends Model
{

    use HasFactory;

    protected $table = 'publicaciones';

    protected $fillable = [
        'autor',
        'descripcion',
        'lugar_realizacion',
        'licencia',
        'camara',
        'imagen',
        'num_reacciones',
        'album',
        'fecha_public'
    ];

    public function autor()
    {
        return $this->belongsTo(Usuario::class, 'autor');
    }

    public function camara()
    {
        return $this->belongsTo(Camara::class, 'camara');
    }

    public function album()
    {
        return $this->belongsTo(Album::class, 'album');
    }

    public function reacciones()
    {
        return $this->hasMany(Reaccion::class, 'publicacion');
    }
}

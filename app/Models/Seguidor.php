<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seguidor extends Model
{

    use HasFactory;

    protected $table = 'seguidores';

    protected $fillable = [
        'usuario_envia',
        'usuario_recibe',
        'fecha_amistad'
    ];

    // Relación con la tabla de usuarios (usuario_envia)
    public function usuarioEnvia()
    {
        return $this->belongsTo(Usuario::class, 'usuario_envia');
    }

    // Relación con la tabla de usuarios (usuario_recibe)
    public function usuarioRecibe()
    {
        return $this->belongsTo(Usuario::class, 'usuario_recibe');
    }
}

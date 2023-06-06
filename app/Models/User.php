<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{


    use HasApiTokens, HasFactory, Notifiable;


    protected $table = 'usuarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nombre', 'apellidos', 'sexo', 'email', 'user', 'password', 'fecha_nac', 'foto_perfil', 'fecha_registro', "rol_id"];

    public function publicaciones()
    {
        return $this->hasMany(Publicacion::class, 'autor');
    }

    public function seguidoresEnviados()
    {
        return $this->hasMany(Seguidor::class, 'usuario_envia');
    }

    public function seguidoresRecibidos()
    {
        return $this->hasMany(Seguidor::class, 'usuario_recibe');
    }

    public function reacciones()
    {
        return $this->hasMany(Reaccion::class, 'user');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role()
{
    return $this->belongsTo(Role::class);
}

}

<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class Usuario extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use Notifiable;

    public $timestamps = false;
    protected $table = "usuarios";
    protected $primaryKey = 'usuario_id';

    protected $fillable = [
        'usuario',
        'clave',
        'apellidos',
        'nombres',
        'numdoc',
        'email'
    ];

    protected $hidden = [
        'clave',
        'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getAuthPassword()
    {
        return $this->clave;
    }

   
}

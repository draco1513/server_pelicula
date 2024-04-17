<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicios extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "Servicios";
    protected $primaryKey = 'servicio_id';

    protected $fillable = [
        'imagen_url',
        'descripcion',
        'producto_link',
        'numero_perfiles'
    ];

    public function cuentas(): HasMany
    {
        return $this->hasMany(Cuentas::class, 'servicio_id')->with('perfil');
    }
}

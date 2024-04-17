<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cuentas extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "Cuentas";
    protected $primaryKey = 'cuenta_id';

    protected $fillable = [
        'correo',
        'password_correo',
        'password_cuenta',
        'fecha_facturacion',
        'servicio_id',
        'perfiles_seleccionados'
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicios::class, 'servicio_id', 'servicio_id');
    }

    public function perfil(): HasOne
    {
        return $this->hasOne(Perfiles::class, 'cuenta_id');
    }
}

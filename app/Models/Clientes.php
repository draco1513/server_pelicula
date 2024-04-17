<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "Clientes";
    protected $primaryKey = 'cliente_id';

    protected $fillable = [
        'nombre',
        'celular',
        'correo',
        
    ];
    
    public function perfiles()
    {
        return $this->hasMany(Perfiles::class, 'cliente_id');
    }

    public function cuentas()
    {
        return $this->hasManyThrough(Cuentas::class, Perfiles::class, 'cliente_id', 'cuenta_id')->with('servicio', 'perfiles');
    }
}

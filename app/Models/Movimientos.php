<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimientos extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "Movimientos";
    protected $primaryKey = 'movimiento_id';

    protected $fillable = [
        'cliente_id',
        'cuenta_id',
        'descripcion',
        'monto',
        'fecha_movimiento',
        'tipo',
        'perfil_id'
        
    ];

     // Relación con el modelo Clientes
     public function cliente()
     {
         return $this->belongsTo(Clientes::class, 'cliente_id', 'cliente_id');
     }
 
     // Relación con el modelo Cuentas
     public function cuenta()
     {
         return $this->belongsTo(Cuentas::class, 'cuenta_id', 'cuenta_id');
     }

     public function perfil()
     {
         return $this->belongsTo(Perfiles::class, 'perfil_id', 'perfil_id');
     }
     
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfiles extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "Perfiles";
    protected $primaryKey = 'perfil_id';

    protected $fillable = [
        'cuenta_id',
        'cliente_id',
        'PIN',
        'fecha_fin',
        'dias'
        
    ];

    protected $casts = [
        'fecha_fin' => 'date',
    ]; 
    
    // Relación con el modelo Cuenta
    public function cuenta()
    {
        return $this->belongsTo(Cuentas::class, 'cuenta_id', 'cuenta_id');
    }

    // Relación con el modelo Cliente
    public function cliente()
    {
        return $this->belongsTo(Clientes::class, 'cliente_id', 'cliente_id');
    }
}

<?php

namespace App\Http\Controllers;

date_default_timezone_set('America/Bogota');

use Illuminate\Http\Request;
use App\Models\Movimientos;
use App\Models\Perfiles;

class MovimientoController extends Controller
{   
    //private $messages;

    public function __construct()
    {
     
    }

    public function getMovimientos(Request $request)
    {
        $search = $request->search ? $request->search : '%';
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;
        $page = $request->page ? $request->page : 1;
        $pageSize = $request->pageSize ? $request->pageSize : 10;
        $paginate = $request->pagination ? $request->pagination : false;
    
        $query = Movimientos::query();
    
        // Incluir la relación con el cliente
        $query->with('cliente');
    
        // Filtrar por el campo 'descripcion' o el nombre del cliente
        $query->where(function ($q) use ($search) {
            $q->where('descripcion', 'like', '%' . $search . '%')
                ->orWhereHas('cliente', function ($q) use ($search) {
                    $q->where('nombre', 'like', '%' . $search . '%');
                });
        });
    
        // Filtrar por fechas si se proporcionan ambas fechas
        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('fecha_movimiento', [$fechaInicio, $fechaFin]);
        }
    
        // Si no existe la relación 'cliente', también incluir los movimientos sin cliente
        $query->orWhereDoesntHave('cliente');
    
        if ($paginate) {
            $movimientos = $query->paginate($pageSize);
        } else {
            $movimientos = $query->get();
        }
    
        return response()->json($movimientos);
    }

    public function getMovimientosPagados(Request $request)
    {
        try {
            // Crear una instancia de la consulta de Movimientos
            $query = Movimientos::query();
        
            // Incluir la relación con el cliente
            $query->with('cliente');
        
            // Obtener todos los movimientos sin aplicar filtros
            $movimientos = $query->get();
    
            // Filtrar los movimientos con tipo 1
            $movimientosVentas = $movimientos->where('tipo', 1);
    
            // Filtrar los movimientos con tipo 2
            $movimientosCompras = $movimientos->where('tipo', 2);
    
            // Calcular la suma de los montos de ventas
            $sumaVentas = $movimientosVentas->sum('monto');
    
            // Calcular la suma de los montos de compras
            $sumaCompras = $movimientosCompras->sum('monto');
    
            // Añadir la suma de ventas y compras al resultado de la consulta
            $result = [
                'movimientos' => $movimientos,
                'ventas' => $sumaVentas,
                'compras' => $sumaCompras,
            ];
        
            // Retornar la respuesta JSON con todos los movimientos, la suma de ventas y la suma de compras
            return response()->json($result);
        } catch (\Exception $e) {
            // Manejar cualquier excepción que pueda ocurrir
            return response()->json(['error' => 'Hubo un error al obtener los movimientos pagados.'], 500);
        }
    }
    


    public function saveMovimiento( Request $request)
    {
        

   
        $movimiento = Movimientos::create([
            'cliente_id' => $request->cliente_id,
            'cuenta_id' => $request->cuenta_id,
            'descripcion' => $request->descripcion,
            'monto' => $request->monto,
            'fecha_movimiento' => $request->fecha_movimiento,
            'tipo' => $request->tipo,
            'perfil_id' => $request->perfil_id,
        ]);
    
        // Verificar el tipo antes de actualizar el perfil
        if ($request->tipo === 1) {
            $perfil = Perfiles::find($request->perfil_id);
            $perfil->fecha_fin = $request->fecha_nueva;
            $perfil->save();
        }
    
        $movimiento['message'] = 'Movimiento guardado';
        return response()->json($movimiento, 201);
    }   
    
    
   public function deleteMovimiento(Request $request)
   {
    
    $numRows = Movimientos::where('movimiento_id', $request->movimiento_id)->delete();
    if ($numRows > 0) {
        $currentPage = $request->page ?? 1; // Obtener la página actual del request
        return response()->json(['message' => 'Movimiento eliminado correctamente', 'currentPage' => $currentPage], 204);
    }

   }


   
   

}

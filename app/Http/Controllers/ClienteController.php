<?php

namespace App\Http\Controllers;

date_default_timezone_set('America/Bogota');

use Illuminate\Http\Request;
use App\Models\Clientes;
use App\Models\Cuentas;
use App\Models\Perfiles;
use Carbon\Carbon;

class ClienteController extends Controller
{   
    //private $messages;

    public function __construct()
    {
     
    }

    public function getClientes(Request $request)
    {
        $search = $request->search ? $request->search : '%';
        $page = $request->page ? $request->page : 1;
        $pageSize = $request->pageSize ? $request->pageSize : 10;
        $paginate = $request->pagination ? $request->pagination : false;
    
        $query = Clientes::query();
    
        // Filtrar por el campo 'descripcion' si hay un valor de búsqueda
        if ($search !== '%') {
            $query->where('nombre', 'like', '%' . $search . '%');
        }
    
        if ($paginate) {
            $clientes = $query->paginate($pageSize);
        } else {
            $clientes = $query->get();
        }
    
        return response()->json($clientes);
    }


    public function saveCliente( Request $request)
    {
        

        if ($request->cliente_id) {
            $client = Clientes::find($request->cliente_id);
            $client->nombre = $request->nombre;
            $client->celular = $request->celular;
            $client->correo = $request->correo;
            $client->save();
        } else {
            $client = Clientes::create([
                'nombre' => $request->nombre,
                'celular' => $request->celular,
                'correo' => $request->correo,
            ]);
        }
        $client['message'] = 'Cliente guardado';
        return response()->json($client, 201);
    }   
    
    
   public function deleteCliente(Request $request)
   {
   

    $numRows = Clientes::where('cliente_id', $request->cliente_id)->delete();
    if ($numRows > 0) {
        $currentPage = $request->page ?? 1; // Obtener la página actual del request
        return response()->json(['message' => 'Cliente eliminado correctamente', 'currentPage' => $currentPage], 204);
    }

   }



    public function obtenerClientesConCuentas()
    {

        $clientasCuentas = Clientes::join('perfiles', 'clientes.cliente_id', '=', 'perfiles.cliente_id')
            ->select('clientes.*')
            ->distinct()
            ->get();

        return response()->json(['clientasCuentas' => $clientasCuentas]);
    }

    public function obtenerInformacionCuentasCliente(Request $request)
    {
          // Obtén el ID del cliente desde la solicitud
        $clienteId = $request->input('cliente_id');
        
        // Busca el cliente por ID con las relaciones cargadas
        $cliente = Clientes::with('perfiles.cuenta.servicio')->find($clienteId);
        
        // Verifica si se encontró el cliente
        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }
    
       
        // Obtén los perfiles del cliente con la información expandida
        $perfiles = $cliente->perfiles->map(function ($perfil) {
            $fechaNueva=new Carbon($perfil->fecha_fin);
            return [
                'perfil_id' => $perfil->perfil_id,
                'cuenta_id' => $perfil->cuenta_id,
                'cliente_id' => $perfil->cliente_id,
                'PIN' => $perfil->PIN,
                'fecha_fin' => $perfil->fecha_fin->format('d/m/Y'),
                'fecha_nueva'=>$fechaNueva->addMonths(1)->format('Y-m-d'),
                'cuenta_correo' => $perfil->cuenta->correo,
                'cuenta_password_correo' => $perfil->cuenta->password_correo,
                'cuenta_password_cuenta' => $perfil->cuenta->password_cuenta,
                'cuenta_fecha_facturacion' => $perfil->cuenta->fecha_facturacion,
                'cuenta_perfiles_seleccionados' => $perfil->cuenta->perfiles_seleccionados,
                'servicio_id' => $perfil->cuenta->servicio->servicio_id,
                'servicio_descripcion' => $perfil->cuenta->servicio->descripcion,
                'servicio_producto_link' => $perfil->cuenta->servicio->producto_link,
                
            ];
        });

    // Retorna el resultado como JSON
    return response()->json(['perfiles' => $perfiles]);

    }


}

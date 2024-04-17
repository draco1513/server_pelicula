<?php

namespace App\Http\Controllers;

date_default_timezone_set('America/Bogota');

use Illuminate\Http\Request;
use App\Models\Clientes;
use App\Models\Cuentas;

class CuentaController extends Controller
{   
    //private $messages;

    public function __construct()
    {
       // $this->middleware('auth:api', ['except' => ['getServicios']]);
        //$this->messages = app('messages');
    }

        public function getCuentas(Request $request)
    {
        $search = $request->search ? $request->search : '%';
        $page = $request->page ? $request->page : 1;
        $pageSize = $request->pageSize ? $request->pageSize : 10;
        $paginate = $request->pagination ? $request->pagination : false;

        $query = Cuentas::query();

        // Incluir la relación con la tabla Servicios
        $query->with('servicio');

        // Filtrar por el campo 'correo' o 'descripcion' si hay un valor de búsqueda
        if ($search !== '%') {
            $query->where('correo', 'like', '%' . $search . '%')
                ->orWhereHas('servicio', function ($subquery) use ($search) {
                    $subquery->where('descripcion', 'LIKE', '%' . $search . '%');
                });
        }

        if ($paginate) {
            $cuentas = $query->paginate($pageSize);
        } else {
            $cuentas = $query->get();
        }

        return response()->json($cuentas);
    }

    public function getCuentasAll(Request $request)
    {
       // Crear una instancia de la consulta de Movimientos
       $query = Cuentas::query();
    
       // Incluir la relación con el cliente
       $query->with('servicio');
   
       // Obtener todos los movimientos sin aplicar filtros
       $cuentas = $query->get();
   
       // Retornar la respuesta JSON con todos los movimientos
       return response()->json($cuentas);
    }

    public function saveCuenta( Request $request)
    {
        /*$validation = AuthController::validationUser($request->sys, $request->url);
        if ($validation['validation'] == false) {
            return response()->json($validation, 400);
        }*

        $request->validate([
            'desPerfil' => 'required|string|max:20',
            'codPerfil' => 'required|string|max:10',
            'usuario_id' => 'required|integer',
        ], $this->messages);*/

        if ($request->cuenta_id) {
            $cuenta = Cuentas::find($request->cuenta_id);
            $cuenta->correo = $request->correo;
            $cuenta->password_correo = $request->password_correo;
            $cuenta->password_cuenta = $request->password_cuenta;
            $cuenta->fecha_facturacion = $request->fecha_facturacion;
            $cuenta->servicio_id = $request->servicio_id;
            $cuenta->perfiles_seleccionados = 0 ;
            //$profile->estado = $request->estado;
            $cuenta->save();
        } else {
            $cuenta = Cuentas::create([
                'correo' => $request->correo,
                'password_correo' => $request->password_correo,
                'password_cuenta' => $request->password_cuenta,
                'fecha_facturacion' => $request->fecha_facturacion,
                'servicio_id' => $request->servicio_id,
                'perfiles_seleccionados' => 0,
            ]);
        }
        $cuenta['message'] = 'Cuenta guardado';
        return response()->json($cuenta, 201);
    }   
    
    
    public function deleteCuenta(Request $request)
    {
        try {
            $cuenta = Cuentas::findOrFail($request->cuenta_id);
    
            // Eliminar el perfil relacionado (si existe)
            if ($cuenta->perfil) {
                $cuenta->perfil->delete();
            }
    
            // Eliminar la cuenta
            $cuenta->delete();
    
            $currentPage = $request->page ?? 1; // Obtener la página actual del request
            return response()->json(['message' => 'Cuenta eliminada correctamente', 'currentPage' => $currentPage], 204);
        } catch (\Exception $e) {
            // Manejar excepciones según tus necesidades
            return response()->json(['error' => 'Error al eliminar la cuenta', 'message' => $e->getMessage()], 500);
        }
    }
}

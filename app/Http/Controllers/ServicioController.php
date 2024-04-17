<?php

namespace App\Http\Controllers;

date_default_timezone_set('America/Bogota');

use Illuminate\Http\Request;
use App\Models\Servicios;


class ServicioController extends Controller
{   
    //private $messages;

    public function __construct()
    {
       // $this->middleware('auth:api', ['except' => ['getServicios']]);
        //$this->messages = app('messages');
    }

    public function getServicios(Request $request)
    {
        $search = $request->search ? $request->search : '%';
        $page = $request->page ? $request->page : 1;
        $pageSize = $request->pageSize ? $request->pageSize : 10;
        $paginate = $request->pagination ? $request->pagination : false;
    
        $query = Servicios::query();
    
        // Filtrar por el campo 'descripcion' si hay un valor de búsqueda
        if ($search !== '%') {
            $query->where('descripcion', 'like', '%' . $search . '%');
        }
    
        if ($paginate) {
            $servicios = $query->paginate($pageSize);
        } else {
            $servicios = $query->get();
        }
    
        return response()->json($servicios);
    }


    public function saveServicio( Request $request)
    {
        if ($request->servicio_id) {
            $service = Servicios::find($request->servicio_id);
            $service->descripcion = $request->descripcion;
            $service->producto_link = $request->producto_link;
            $service->numero_perfiles = $request->numero_perfiles;
            $service->save();
        } else {
            $service = Servicios::create([
                'descripcion' => $request->descripcion,
                'producto_link' => $request->producto_link,
                'numero_perfiles' => $request->numero_perfiles,
            ]);
        }
        $service['message'] = 'Servicio guardado';
        return response()->json($service, 201);
    }   
    
    
    public function deleteServicio(Request $request)
    {
        try {

    
            $numRows = Servicios::where('servicio_id', $request->servicio_id)->delete();
    
            if ($numRows > 0) {
                $currentPage = $request->page ?? 1;
                return response()->json(['message' => 'Servicio eliminado correctamente', 'currentPage' => $currentPage], 204);
            } else {
                return response()->json(['error' => 'No se encontró el servicio'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el servicio', 'message' => $e->getMessage()], 500);
        }
    }
    


}

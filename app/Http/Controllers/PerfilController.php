<?php

namespace App\Http\Controllers;

date_default_timezone_set('America/Bogota');

use Illuminate\Http\Request;
use App\Models\Perfiles;
use App\Models\Clientes;

class PerfilController extends Controller
{   
    //private $messages;

    public function __construct()
    {
       
    }

    public function getPerfiles()
    {
        $perfiles = Perfiles::with('cliente')->get();
        return response()->json($perfiles);
    }

    public function getClientesAll()
    {
        $clientes = Clientes::all();
        return response()->json($clientes);
    }


   public function savePerfilesCuenta( Request $request)
    {
       

       
       
            $perfiles = Perfiles::create([
                'cuenta_id' => $request->cuenta_id,
                'cliente_id' => $request->cliente_id,
                'PIN' => $request->pin,
                'fecha_fin'=> $request->fecha_fin,
                'dias'=>$request->dias,
            ]);
        

        $perfiles['message'] = 'Perfil guardado';
        return response()->json($perfiles, 201);
    }   
    
    
   public function deleteServicio(Request $request)
   {
    
    $numRows = Servicios::where('servicio_id', $request->servicio_id)->delete();
    if ($numRows > 0) {
        $currentPage = $request->page ?? 1; // Obtener la página actual del request
        return response()->json(['message' => 'Servicio eliminado correctamente', 'currentPage' => $currentPage], 204);
    }

   }
    


}

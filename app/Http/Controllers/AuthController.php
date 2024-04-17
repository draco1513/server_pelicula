<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','logout']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'clave' => 'required|string',
            //'sistema'=>'required|string|max:10'
        ]);

        $credentials = [
            "usuario" => $request->usuario,
            "password" => $request->clave
        ];

        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([ 'status' => 'error', 'message' => 'Error de credenciales',], 401);
        }

        $user = Auth::user();
        $data = [
            "token" => $token,
            'user' => $user
        ];
        return response()->json($data);
    }

    public function register(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string|max:255',
            'clave' => 'required|string|min:6',
        ]);

        $user = Usuario::create([
            'usuario' => $request->usuario,
            'clave' => Hash::make($request->clave),
        ]);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function me()
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'user' => auth()->user()
        ]);
    }

}

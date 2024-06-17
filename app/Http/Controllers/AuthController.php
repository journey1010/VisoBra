<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\Auth\Horizon;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        if(Auth::check()){
            return redirect('panel/horizon');
        }
        return view('login');
    }

    public function login(Horizon $request): JsonResponse
    {
        $credentials = $request->only('email','password');
        $credentials['status'] = true;
        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            return response()->json([
                'status' => 'success',
                'message' => 'Credenciales correctas'
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'El usuario no esta autorizado'
        ], 400);
    }
}

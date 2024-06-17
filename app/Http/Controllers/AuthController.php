<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\Auth\Horizon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        if(Auth::check()){
            return redirect('/horizon');
        }
        return view('login');
    }

    public function login(Horizon $request): JsonResponse
    {

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            DB::table('user_online')->insert([
                'users_id' => $user->id,
                'created_at' => date('Y-m-d H:i:s')
            ]);
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

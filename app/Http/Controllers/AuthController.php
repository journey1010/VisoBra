<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\Horizon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Horizon $request)
    {
        $credentials = $request->only('email','password');
    }
}

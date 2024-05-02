<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class Prueba extends Controller
{
    public function test()
    {
        User::create([
            'name' => 'hola',
            'email' => 'hola@gmail',
            'password' => 'hola'
        ]);

    }
}
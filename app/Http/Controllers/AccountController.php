<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function balance()
    {        
        return response()->json([
            'test' => 'test'
        ]);
    }

    public function event()
    {        
        return response()->json([
            'test1' => 'test1'
        ]);
    } 
}

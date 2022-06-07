<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    public function notAuthenticated(){
        return response()->json([
            "message" => "User not authenticated"
        ], 401);
    }
}

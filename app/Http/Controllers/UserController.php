<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request){
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password),
        ]);
        if($user){
            return response()->json([
                "message" => "User created successfully",
                "user" => $user
            ], 201);
        }else{
            return response()->json([
                "message" => "User not created"
            ], 500);
        }
    }

    public function login (Request $request){
        if(Auth::attempt(['email'=> $request->email, 'password'=>$request->password])){
            $user = Auth::user();
            $token = $user->createToken('MyApp')->plainTextToken;
            return response()->json([
                "message" => "User logged in successfully",
                "token" => $token,
                "user" => $user
            ], 200);
        }else{
            return response()->json([
                "message" => "User not found"
            ], 401);
        }
    }
}

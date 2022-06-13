<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{   
    private function registerValidator(Request $request){
        return Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email|email',
            'username' => 'required|unique:users',
            'address' => 'required',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ]);
    }

    private function loginValidator(Request $request){
        return Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|min:8',
        ]);
    }

    public function register(Request $request){
        $validate = $this->registerValidator($request);
        if(!$validate->fails()){
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "username" => $request->username,
                "address" => $request->address,
                "password" => bcrypt($request->password),
            ]);
            if($user){
                return ResponseFormatter::success($user, "user has been created", 201, 'success');
            }else{
                return ResponseFormater::error(null, "User not created", 400, "internal error");
            }
        }else{
            return ResponseFormatter::error(null, "Unprocessable Entity", 422, $validate->errors());
        }
    }

    public function login (Request $request){
        $validate = $this->loginValidator($request);
        if(!$validate->fails()){
            if(Auth::attempt(['email'=> $request->username, 'password'=>$request->password]) || Auth::attempt(['username'=> $request->username, 'password'=>$request->password])){
                $user = Auth::user();
                $token = $user->createToken('CPToken')->plainTextToken;
                return response()->json([
                    "message" => "User logged in successfully",
                    "token" => $token,
                    "user" => $user
                ], 200);
            }else{
                return ResponseFormatter::error(null, "User not authenticated", 401, "user/password not match");
            }
        }else{
            return ResponseFormatter::error(null, "Unprocessable Entity", 422, $validate->errors());
        }
        
    }
}

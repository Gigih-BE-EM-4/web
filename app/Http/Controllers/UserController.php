<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
  private function registerValidator(Request $request)
  {
    return Validator::make($request->all(), [
      'name' => 'required',
      'email' => 'required|unique:users,email|email',
      'username' => 'required|unique:users',
      'address' => 'required',
      'password' => 'required|min:8',
      'confirm_password' => 'required|same:password',
    ]);
  }

  private function loginValidator(Request $request)
  {
    return Validator::make($request->all(), [
      'username' => 'required',
      'password' => 'required|min:8',
    ]);
  }

  public function register(Request $request)
  {
    $validate = $this->registerValidator($request);
    if (!$validate->fails()) {
      $user = User::create([
        "name" => $request->name,
        "email" => $request->email,
        "username" => $request->username,
        "address" => $request->address,
        "password" => bcrypt($request->password),
        "verify" => Str::random(40),
      ]);
      if ($user) {
        return ResponseFormatter::success($user, "user has been created", 201, 'success');
      } else {
        return ResponseFormater::error(null, "User not created", 400, "internal error");
      }
    } else {
      return ResponseFormatter::error(null, "Unprocessable Entity", 422, $validate->errors());
    }
  }

  private function updateValidator(Request $request)
  {
    return Validator::make($request->all(), [
      'name' => 'required',
      "address" => 'min:1',
    ]);
  }
  private function confirmPasswordValidator(Request $request)
  {
    return Validator::make($request->all(), [
      'password' => 'required|min:8',
      'confirm_password' => 'required|same:password',
    ]);
  }


  private function uploadImage($request)
  {
    if ($file = $request->hasFile('profile')) {
      $file = $request->file('profile');
      $fileName = $file->getClientOriginalName();
      $destinationPath = public_path() . '/User/Profile';
      $now = Carbon::now();
      $file->move($destinationPath, $now . $fileName);
      return "/User/Profile/" . $now . $fileName;
    } else {
      return "";
    }
  }

  public function login(Request $request)
  {
    $validate = $this->loginValidator($request);
    if (!$validate->fails()) {
      if (Auth::attempt(['email' => $request->username, 'password' => $request->password]) || Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
        $user = Auth::user();
        $token = $user->createToken('CPToken')->plainTextToken;
        return ResponseFormatter::success(["token" => $token], "user has been logged in", 201, 'success');
      } else {
        return ResponseFormatter::error(null, "User not authenticated", 401, "user/password not match");
      }
    } else {
      return ResponseFormatter::error(null, "Unprocessable Entity", 422, $validate->errors());
    }
  }

  public function update(Request $request)
  {
    $validate = $this->updateValidator($request);
    if (!$validate->fails()) {
      $request->merge(['profile' => $this->uploadImage($request)]);
      if (isset($request->password)) {
        $pwValidate = $this->confirmPasswordValidator($request);
        if (!$pwValidate->fails()) {
          $request->merge(['password' => bcrypt($request->password)]);
        } else {
          return ResponseFormatter::error(null, "Unprocessable Entity", 422, $pwValidate->errors());
        }
      }
      $user = Auth::User()->update($request->all());
      if ($user) {
        return ResponseFormatter::success($user, "user has been updated", 201, 'success');
      } else {
        return ResponseFormatter::error(null, "User not updated", 400, "internal error");
      }
    } else {
      return ResponseFormatter::error(null, "Unprocessable Entity", 422, $validate->errors());
    }
  }

  public function verify($verify)
  {
    $user = User::where('verify', $verify)->first();
    if ($user) {
      $user->verify = null;
      $user->save();
      return ResponseFormatter::success($user, "user has been verified", 201, 'success');
    } else {
      return ResponseFormatter::error(null, "User not found", 404, "user not found");
    }
  }
  public function isVerify()
  {
    $user = Auth::User();
    if ($user->verify != null) {
      return ResponseFormatter::error(null, "User not verified", 401, "user not verified");
    } else {
      return ResponseFormatter::success($user, "user is verified", 200, 'success');
    }
  }

  public function detail($id)
  {
    $user = User::findOrFail($id);
    if ($user) {
      return ResponseFormatter::success($user, "user has been found", 200, 'success');
    } else {
      return ResponseFormatter::error(null, "User not found", 404, "user not found");
    }
  }

  public function logOut()
  {
    if (Auth::User()->currentAccessToken()->delete()) {
      return ResponseFormatter::success(null, "user has been logged out", 200, 'success');
    } else {
      return ResponseFormatter::error(null, "User not logged out", 400, "internal error");
    }
  }
}

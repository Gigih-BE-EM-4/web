<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
  private function registerValidator(Request $request)
  {
    return Validator::make($request->all(), [
      'name' => 'required',
      'email' => 'required|unique:users,email|email',
      'username' => 'required|unique:users|min:5',
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
        if(env("SEND_EMAIL")){
          \Mail::to($request->email)->send(new \App\Mail\RegisterMail($user->name,$user->verify));
          if (\Mail::failures()) {
            return ResponseFormater::error(null, "Email Not Sended", 400, Mail::failures());
          }else{
            return ResponseFormatter::success($user, "user has been created", 201, 'success');
          }
        }else{
          return ResponseFormatter::success($user, "user has been created", 201, 'success');
        }
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
      'name' => 'min:5',
      'bio' => 'min:1',
      'address' => 'min:1',
      "last_education" => 'min:1',
    ]);
  }
  private function confirmPasswordValidator(Request $request)
  {
    return Validator::make($request->all(), [
      'password' => 'required|min:8',
      'confirm_password' => 'required|same:password|min:8',
    ]);
  }
  private function changeProfileValidator(Request $request)
  {
    return Validator::make($request->all(), [
      'profile' => 'image|mimes:png,jpg,gif|max:2048',
    ]);
  }


  private function uploadImage($request)
  {
    if ($file = $request->hasFile('profile')) {
      $file = $request->file('profile');
      $fileName = time().$file->getClientOriginalName();
      $destinationPath = public_path().'/user/Profile';
      $file->move($destinationPath,$fileName);
      return "/User/Profile/" .$fileName;
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
      $data = $validate->validated();
      $user = Auth::User()->update($data);
      if ($user) {
        return ResponseFormatter::success($user, "user has been updated", 201, 'success');
      } else {
        return ResponseFormatter::error(null, "User not updated", 400, "internal error");
      }
    } else {
      return ResponseFormatter::error(null, "Unprocessable Entity", 422, $validate->errors());
    }
  }
  public function changeProfile(Request $request){
    $validate = $this->changeProfileValidator($request);
    if(!$validate->fails()){
      $x = $this->uploadImage($request);
      if($x != ""){
        $user = Auth::User()->update(['profile' => $x]);
        if($user){
          return ResponseFormatter::success($user, "user has been updated", 201, 'success');
        }else{
          return ResponseFormatter::error(null, "User not updated", 400, "internal error");
        }
      }else{
        return ResponseFormatter::error(null, "Unprocessable Entity", 422, "image not uploaded");
      }
    }else{
      return ResponseFormatter::error(null, "Unprocessable Entity", 422, $validate->errors());
    }
   
    
  }
  public function changePassword(Request $request)
  {
    $validate = $this->confirmPasswordValidator($request);
    if (!$validate->fails()) {
      $user = Auth::User()->update([
        "password" => bcrypt($request->password),
      ]);
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

  public function forgot($email)
  {
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
      $user = User::where('email', $email)->first();
      if ($user) {
        // $password = Str::random(10);
        $password = "12345678";
        $hash = bcrypt($password);
        //ceritanya ngirim email
        $sendEmail = true;
        if($sendEmail){
          $user->password = $hash;
          $user->save();
          return ResponseFormatter::success($user, "user has been reset", 200, 'success');
        }else{
          return ResponseFormatter::error(null, "Gagal mengirim email, silahkan coba beberapa saat lagi", 404, "fail to send email");
        }
      } else {
        return ResponseFormatter::error(null, "User Not Found", 404, "user not found");
      }
    }else{
      return ResponseFormatter::error(null, "Unprocessable Entity", 422, ["email" => ["Email not valid"]]);
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

  public function ping(){
      return ResponseFormatter::success(null, "user is logged in", 200, 'success');
  }
}

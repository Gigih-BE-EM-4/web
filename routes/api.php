<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('user/register', [UserController::class, 'register']);
Route::post('user/login', [UserController::class, 'login'])->name("user");
Route::get('notAuthenticated', [App\Http\Controllers\ErrorController::class, 'notAuthenticated'])->name("notAuthenticated");

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/company', [CompanyController::class, 'store']);
});



Route::middleware(['auth:sanctum','isVerify'])->group(function () {
    Route::post('user', [UserController::class, 'update'])->name("user.update");
});













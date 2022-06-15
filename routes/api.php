<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyProjectController;
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
Route::get('user/verify/{verify}', [UserController::class, 'verify'])->name("user.verify");
Route::get('user/{id}', [UserController::class, 'detail'])->name("user.detail");

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/company/join', [CompanyController::class, 'joinCompany']);
    Route::post('/company/leave', [CompanyController::class, 'leaveCompany']);
    Route::get('/company/{company_id}/members', [CompanyController::class, 'companyMembers']);
    Route::get('/company/{company_id}', [CompanyController::class, 'companyDetail']);
    Route::post('/company', [CompanyController::class, 'store']);
});



Route::middleware(['auth:sanctum'])->group(function () {
  Route::post('user', [UserController::class, 'update'])->name("user.update");
  Route::get('user/verify', [UserController::class, 'isVerify'])->name("user.isVerify");
  Route::post('user/logout', [UserController::class, 'logout'])->name("user.logout");
});

Route::middleware('auth:sanctum')->group(function () {
  Route::get('user/company/projects', [CompanyProjectController::class, 'getAllProjects'])->name("user.company.projects");
  Route::post('company/project', [CompanyProjectController::class, 'createProject']);
  Route::post('company/project/{id}', [CompanyProjectController::class, 'updateProject']);
  Route::get('company/project/{project_id}/role/{role_id}/applicants', [CompanyProjectController::class, 'getAllApplicants'])->name("user.company.applicants");
});

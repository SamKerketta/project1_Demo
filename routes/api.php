<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ResetPasswordController;
use PhpParser\Node\Scalar\MagicConst\Function_;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//public access
Route::post("register",[UserController::class,"register"]);
Route::post("login",[UserController::class,"login"]);

//Protected
Route::group(['middleware'=>'auth:sanctum'],function()
{
    Route::post("logout",[UserController::class,"logout"]);   
    Route::post("logged",[UserController::class,"logged_user"]);
    Route::post("changepassword",[UserController::class,"changePassword"]);   
});

//student routes
Route::put("updatestudent/{id}",[StudentController::class,"studentup"]);

//password reset
Route::controller(ResetPasswordController::class)->group(function ()
{
    Route::post("send","send");
});


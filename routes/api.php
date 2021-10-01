<?php

use App\Http\Controllers\ForgotPasswordApiController;
use App\Http\Controllers\ResetPasswordAPIController;
use App\Http\Controllers\bill_controller;
use App\Http\Controllers\RegisterClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterCompany;
use App\Http\Controllers\RegisterEmployee;
use App\Http\Controllers\order;
use App\Http\Controllers\login;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
//login
Route::POST('login',[login::class,'userlogin']);
//forgotpass
Route::POST('forgot-password',[ForgotPasswordApiController::class,'sendResetLinkEmail']);

//restPass
Route::post('reset',[ResetPasswordAPIController::class,'reset']);

//order routes
// Route::group(['middleware' => ['sessions']], function () {
//     Route::POST('add/order',[order::class,'store']);
// });
//
Route::GET('search/bill/{id}',[bill_controller::class,'index']);

Route::GET('bill',[bill_controller::class,'show']);
Route::DELETE('delete/bill/{id}',[bill_controller::class,'destroy']);



// Route::post('message',[order::class,'message']);
Route::Post('add/order',[order::class,'store']);
Route::GET('display/order',[order::class,'index']);

// Company Api routes
Route::POST('register/company',[RegisterCompany::class,'store']);
Route::GET('search/company/{name}',[RegisterCompany::class,'show']);
Route::DELETE('delete/company/{id}',[RegisterCompany::class,'destroy']);
Route::POST('update/company/{id}',[RegisterCompany::class,'update']);
Route::GET('company',[RegisterCompany::class,'index']);

//CLient Api routes
Route::POST('register/client',[RegisterClient::class,'upload']);
Route::GET('search/client/{name}',[RegisterClient::class,'show']);
Route::DELETE('delete/client/{id}',[RegisterClient::class,'destroy']);
Route::POST('update/client/{id}',[RegisterClient::class,'update']);
Route::GET('client',[RegisterClient::class,'index']);
Route::GET('client/count',[RegisterClient::class,'dashboard_data']);

//Employee Api routes
Route::POST('register/employee',[RegisterEmployee::class,'store']);
Route::GET('search/employee/{name}',[RegisterEmployee::class,'show']);
Route::DELETE('delete/employee/{id}',[RegisterEmployee::class,'destroy']);
// Route::PUT('update/employee/{id}',[RegisterEmployee::class,'update']);
Route::POST('update/employee/{id}',[RegisterEmployee::class,'update']);
Route::GET('employee',[RegisterEmployee::class,'index']);


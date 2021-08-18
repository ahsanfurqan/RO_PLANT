<?php

use App\Http\Controllers\RegisterClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterCompany;
use App\Http\Controllers\RegisterEmployee;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::POST('register/company',[RegisterCompany::class,'store']);
Route::POST('register/client',[RegisterClient::class,'upload']);


//Employee Api routes
Route::POST('register/employee',[RegisterEmployee::class,'store']);
Route::GET('search/employee/{name}',[RegisterEmployee::class,'show']);
Route::DELETE('delete/employee/{id}',[RegisterEmployee::class,'destroy']);
// Route::PUT('update/employee/{id}',[RegisterEmployee::class,'update']);
Route::POST('update/employee/{id}',[RegisterEmployee::class,'update']);
Route::GET('employee',[RegisterEmployee::class,'index']);


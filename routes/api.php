<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\V1\AuthController;
use App\Http\Controllers\api\V1\BlogController;
use App\Http\Controllers\api\V1\LostController;
use App\Http\Controllers\api\V1\UserController;
use App\Http\Controllers\SanctumTestController;
use App\Http\Controllers\api\V1\FoundController;
use App\Http\Controllers\api\V1\AgendaController;
use App\Http\Controllers\api\V1\BalanceController;

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

Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);

Route::group(['middleware'=>['auth:sanctum']], function () {
    Route::post('logout',[AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/sanctumtest',[SanctumTestController::class,'index'])->middleware('auth:sanctum');

//
// API/V1
//

// bikin 2 namespace untuk public dan dashboard

Route::group(['prefix'=>'v1/public','namespace'=>'App\Http\Controllers\api\V1'],function(){

    Route::get('/agenda',[AgendaController::class,'index']);
    Route::get('/agenda/{agenda}',[AgendaController::class,'show']);
    Route::get('/blog',[BlogController::class,'index']);
    Route::get('/blog/{blog}',[BlogController::class,'show']); // with slug
    Route::get('/found',[FoundController::class,'index']);
    Route::get('/found/{found}',[FoundController::class,'show']);
    Route::get('/lost',[LostController::class,'index']);
    Route::get('/lost/{lost}',[LostController::class,'show']);
    Route::get('/balance',[BalanceController::class,'publicIndex']);

});

Route::group(['prefix'=>'v1/dashboard','namespace'=>'App\Http\Controllers\api\V1','middleware'=>'auth:sanctum'],function(){

    Route::get('/agenda',[AgendaController::class,'dashboardIndex']);
    Route::get('/blog',[BlogController::class,'dashboardIndex']);
    Route::get('/found',[FoundController::class,'dashboardIndex']);
    Route::get('/lost',[LostController::class,'dashboardIndex']);

});

Route::group(['prefix'=>'v1','namespace'=>'App\Http\Controllers\api\V1','middleware'=>'auth:sanctum'], function(){

    Route::apiResource('blog',BlogController::class);
    Route::apiResource('user',UserController::class);
    Route::apiResource('agenda',AgendaController::class);
    Route::apiResource('balance',BalanceController::class);
    Route::apiResource('found',FoundController::class);
    Route::apiResource('lost',LostController::class);

});
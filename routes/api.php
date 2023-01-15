<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\V1\AuthController;
use App\Http\Controllers\api\V1\AgendaController;
use App\Http\Controllers\api\V1\BalanceController;
use App\Http\Controllers\api\V1\BlogController;
use App\Http\Controllers\api\V1\FoundController;
use App\Http\Controllers\api\V1\UserController;
use App\Http\Controllers\SanctumTestController;

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

Route::get('/v1/blog/slug/{slug}',[BlogController::class,'slug']); // use this for slug only

Route::group(['prefix'=>'v1','namespace'=>'App\Http\Controllers\api\V1'], function(){
    Route::post('found/bulk',[FoundController::class,'bulkStore']);

    Route::apiResource('blog',BlogController::class);
    Route::apiResource('user',UserController::class);
    Route::apiResource('agenda',AgendaController::class);
    Route::apiResource('balance',BalanceController::class);
    Route::apiResource('found',FoundController::class);
    Route::apiResource('lost',LostController::class);
});
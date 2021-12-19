<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\MembersController;
use App\Http\Controllers\Api\v1\AuthController;

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

Route::group(['prefix' => 'v1'],function(){

    Route::group(['middleware' => 'jwt.verify'],function(){

        Route::get('profile', [AuthController::class, 'getUser']);

        Route::group(['prefix' => 'members'],function(){
            Route::post('/create',[MembersController::class,'create']);
            Route::get('/{uuid}',[MembersController::class,'show']);
            Route::put('update/{uuid}',[MembersController::class,'update']);
            Route::delete('delete/{uuid}',[MembersController::class,'delete']);
        });
    });

    Route::get('members/',[MembersController::class,'list']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});



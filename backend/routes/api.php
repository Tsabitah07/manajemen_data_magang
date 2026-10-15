<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/user', [AuthController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::group(['middleware' => 'auth:sanctum'], function (){
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/update', [AuthController::class, 'update']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

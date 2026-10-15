<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InternshipController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');

    Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum', 'admin']], function () {
        Route::get('/users', [AuthController::class, 'getAll']);
        Route::get('/users/{id}', [AuthController::class, 'get']);
    });

    Route::group(['prefix' => 'campus-unit', 'middleware' => ['auth:sanctum', 'campus-unit']], function () {
        Route::get('/users', [AuthController::class, 'getAll']);
        Route::get('/users/{id}', [AuthController::class, 'get']);
    });
});

Route::group(['prefix' => 'internships'], function () {
    Route::get('/', [InternshipController::class, 'index']);

    Route::group(['middleware' => ['auth:sanctum', 'campus-unit']], function () {
        Route::post('/', [InternshipController::class, 'store']);
        Route::put('/', [InternshipController::class, 'update']);
        Route::delete('/', [InternshipController::class, 'destroy']);
    });
});

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InternshipApplicationController;
use App\Http\Controllers\InternshipController;
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

Route::group(['prefix' => 'internships'], function () {
    Route::get('/', [InternshipController::class, 'index']);

    Route::group(['middleware' => ['auth:sanctum', 'campus-unit']], function () {
        Route::post('/', [InternshipController::class, 'store']);
        Route::put('/', [InternshipController::class, 'update']);
        Route::delete('/', [InternshipController::class, 'destroy']);
    });
});

Route::group(['prefix' => 'intern-application', 'middleware' => 'auth:sanctum'], function () {
    Route::group(['prefix' => '/student', 'middleware' => 'student'], function () {
        Route::post('/', [InternshipApplicationController::class, 'store']);
        Route::get('/', [InternshipApplicationController::class, 'index']);
        Route::put('/', [InternshipApplicationController::class, 'update']);
        Route::delete('/', [InternshipApplicationController::class, 'destroy']);
    });

    Route::group(['prefix' => '/campus-unit', 'middleware' => 'campus-unit'], function () {
        Route::get('/', [InternshipApplicationController::class, 'index']);
        Route::put('/', [InternshipApplicationController::class, 'update']);
    });
});

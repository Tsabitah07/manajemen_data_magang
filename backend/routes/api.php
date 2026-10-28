<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InternshipApplicationController;
use App\Http\Controllers\InternshipController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/user', [AuthController::class, 'profile']);
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

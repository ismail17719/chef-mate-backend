<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// User Routes
Route::controller(UserController::class)->group(function(){
    Route::prefix('/user')->group(function(){
        // Public Routes
        Route::post('/registration','register')->name('user.register');
        Route::post('/login','login')->name('user.login');

        // Protected Routes
        Route::middleware('auth:sanctum')->group(function(){
            Route::post('/logout','logout')->name('user.logout');
        });
    });
    
});

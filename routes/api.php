<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordResetController;

// User Routes
Route::controller(UserController::class)->group(function(){
    Route::prefix('/user')->group(function(){
        // Public Routes
        Route::post('/registration','register');
        Route::post('/login','login');
        

        // Protected Routes
        Route::middleware('auth:sanctum')->group(function(){
            Route::post('/logout','logout');
            Route::get('/loggedin/data','loggedInUser');
        });
    });
    
});
//// Rest Password Routes
Route::controller(PasswordResetController::class)->group(function(){
    Route::prefix('/user')->group(function(){
        Route::post('/reset/password', 'resetPassword');
        Route::post('/reset/password/{token}', 'reset');
    });
    
});
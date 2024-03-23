<?php

use App\Http\Controllers\MenuItemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChefController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\VerifyIdController;

// Chef Routes
Route::prefix('/chef')->group(function(){
    Route::controller(ChefController::class)->group(function(){
        // Public Routes
        Route::post('/registration','register');
        Route::post('/login','login');
        
    });
    
    //// Rest Password Routes

    Route::controller(PasswordResetController::class)->group(function(){
        Route::post('/reset/password', 'resetPassword');
        Route::post('/reset/password/{token}', 'reset');
    });

   


    // Protected Routes
    Route::middleware('auth:sanctum')->group(function(){
        Route::controller(ChefController::class)->group(function(){
            Route::post('/logout','logout');
            Route::get('/loggedin/data','loggedInUser');
            /// Edit Profile
            Route::post('/edit/profile','editProfile');

        });
        // Add Food Items Route
        Route::controller(MenuItemController::class)->group(function(){
            Route::post('/add/food', 'addFood');
            
         });

        /// Verify image route
        Route::post('/upload/verify-id/images',[VerifyIdController::class,'verify']);


    });// end auth middleware


    
});
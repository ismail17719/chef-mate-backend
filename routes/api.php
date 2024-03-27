<?php

use App\Http\Controllers\MenuItemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChefController;
use App\Http\Controllers\DaysOfweekController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\VerifyIdController;
use App\Http\Controllers\WeeklyMenuController;
use App\Http\Controllers\UserController;

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
            Route::post('/update/profile','updateProfile');

        });
        // Add Food Items Route
        Route::controller(MenuItemController::class)->group(function(){
            Route::post('/add/food', 'addFood');
            Route::get('/all/food', 'allFood');
            Route::get('/all/active/food', 'allActiveFood');
            Route::get('/all/inactive/food', 'allInactiveFood');
            Route::post('/update/food/{id}','updateFood');
            Route::post('/food/inactive/{id}','foodInactive');
            Route::post('/food/active/{id}','foodActive');
            
         });

        /// Verify image route
        Route::post('/upload/verify-id/images',[VerifyIdController::class,'verify']);

        // Days of week route
        Route::get('/days/of/week', [DaysOfweekController::class, 'days']);

        // Weekly Menu
        Route::controller(WeeklyMenuController::class)->group(function(){
            Route::post('/weekly/menu/food/{food_id}/{day_id}', 'addWeeklyFood');
            Route::get('/today/menu', 'todayFood');
        });
       


    });// end auth middleware

    
});// end chef prefix

// User Routes
Route::prefix('/user')->group(function(){
    Route::controller(UserController::class)->group(function(){
        // Public Routes
        Route::post('/registration','register');
        Route::post('/login','login');

        // Protected Routes
        Route::middleware('auth:sanctum')->group(function(){
            Route::post('/logout','logout');
            /// Edit Profile
            Route::post('/edit/profile','editProfile');
            Route::post('/update/profile','updateProfile');
        });
    });
});
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    CartController,
    ConversationController,
    MenuItemController,
    ChefController,
    DaysOfweekController,
    MessageController,
    OrderController,
    PasswordResetController,
    VerifyIdController,
    WeeklyMenuController,
    UserController,
};


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
        });// end middleware
    });// end controller

            /// add to cart routes
        Route::middleware('auth:sanctum')->group(function(){
            Route::controller(CartController::class)->group(function(){
                Route::post('/add-to-cart','addToCart');
                Route::put('/cart/increment/{id}', 'incrementCartItem');
                Route::put('/cart/decrement/{id}', 'decrementCartItem');
                Route::get('/view/cart', 'viewCart');
               
            });// end controller
       
            // order routes
            Route::controller(OrderController::class)->group(function(){
                Route::get('/new/order','newOrder');
                Route::get('/complete/order','completeOrder');
               
               
            });// end controller
        });// end middleware
});// end prefix

 // Chat Routes
 Route::middleware('auth:sanctum')->group(function(){
    Route::controller(ConversationController::class)->group(function(){
        Route::post('/create/coversation/{id}','createCoversation');
    });
    Route::controller(MessageController::class)->group(function(){
        Route::post('/send/message/{id}','sendMessage');
        Route::get('/coversation/messages/{sender}/{receiver}', 'getConversationMessages');
        Route::get('/users/with/conversation/{id}','getUsersWithConversations');
    });
});
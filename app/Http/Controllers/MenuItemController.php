<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuItemRequest;
use App\Models\MenuItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class MenuItemController extends Controller
{
    public function addFood(MenuItemRequest $request){

        $user_id = Auth::id();
        

        $takenImage = $request->file('food_image');
        $manager = new ImageManager(new Driver());
        $image_name = hexdec(uniqid()).".".$takenImage->getClientOriginalExtension();
        $image = $manager->read($takenImage);
        $image = $image->resize(120,120);
        $image->save(base_path('public/upload/food_images/'.$image_name));
        $url = 'upload/food_images/'.$image_name;

        MenuItem::insert([
            'food_name' => $request->food_name,
            'price' => $request->price,
            'size' => $request->size,
            'description' => $request->description,
            'cooking_time' => $request->cooking_time,
            'food_image' => $url,
            'user_id' => $user_id,
            'created_at' => Carbon::now(),
        ]);
        
        return response()->json([
            'message' => 'Your food item added successfully'
        ]);
    }
    //end method

    public function editFood(MenuItemRequest $request){

       
    }// end method
}

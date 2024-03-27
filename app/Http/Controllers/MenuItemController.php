<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuItemRequest;
use App\Models\MenuItem;
use App\Services\FileUpload;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;


class MenuItemController extends Controller
{
    public function __construct(private FileUpload $fileupload)
    {
        
    }
    public function addFood(MenuItemRequest $request){

        $user_id = Auth::id();

        $food_image = $request->file('food_image');
        
        $url = $this->fileupload->uploadFoodImage($food_image);

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

    public function allFood(){
        $id = Auth::id();
        $food = MenuItem::where('user_id',$id)->get();

        return response()->json([
            'food' => $food,
            'message' => 'Get all food item'
        ]);
       
    }// end method

    public function allActiveFood(){
        $id = Auth::id();
        $active = MenuItem::where('user_id',$id)->where('status','active')->get();

        return response()->json([
            'allActiveFood' => $active,
            'message' => 'All Active Foods'
        ]);
    }// end method

    public function allInactiveFood(){
        $id = Auth::id();
        $inactive = MenuItem::where('user_id',$id)->where('status','inactive')->get();

        return response()->json([
            'allInactiveFood' => $inactive,
            'message' => 'All Inactive Foods'
        ]);
    }// end method


    public function updateFood(MenuItemRequest $request,$id){

        /// Update Food item with image
        if ($request->file('food_image')) {

            $food_image = $request->file('food_image');
        
            $url = $this->fileupload->uploadFoodImage($food_image);

            MenuItem::find($id)->update([
                'food_name' => $request->food_name,
                'price' => $request->price,
                'size' => $request->size,
                'description' => $request->description,
                'cooking_time' => $request->cooking_time,
                'food_image' => $url,
                'updated_at' => Carbon::now(),
            ]);

            return response()->json([
                'message' => 'Update food with image successfully'
            ]);

            
        }

        /// Update food item without image
        MenuItem::find($id)->update([
            'food_name' => $request->food_name,
            'price' => $request->price,
            'size' => $request->size,
            'description' => $request->description,
            'cooking_time' => $request->cooking_time,
            'updated_at' => Carbon::now(),
        ]);

        return response()->json([
            'message' => 'Food item update successfully'
        ]);
    }/// end method

    /// Food item Hide
    public function foodInactive($id){
        MenuItem::find($id)->update([
            'status' => 'inactive',
            'updated_at' => Carbon::now()
        ]);

        return response()->json([
            'message' => 'Food item hide successfully'
        ]);
    }// end method

     /// Food item Show
     public function foodActive($id){
        MenuItem::find($id)->update([
            'status' => 'active',
            'updated_at' => Carbon::now()
        ]);

        return response()->json([
            'message' => 'Food item show successfully'
        ]);
    }/// end method
}

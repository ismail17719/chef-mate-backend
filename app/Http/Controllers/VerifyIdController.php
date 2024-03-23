<?php

namespace App\Http\Controllers;

use App\Http\Requests\VerifyIdRequest;
use App\Models\VerifyId;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class VerifyIdController extends Controller
{
    public function verify(VerifyIdRequest $request){

        $id = Auth::id();

       $front_image = $request->file('front_image');
       $back_image = $request->file('back_image');

       if($front_image && $back_image){
        /// front side image upload
        $image_front = $request->file('front_image');
        $manger = new ImageManager(new Driver());
        $front_name = hexdec(uniqid()).".".$image_front->getClientOriginalExtension();
        $f_image = $manger->read($image_front);
        $f_image = $f_image->resize(120,120);
        $f_image->save(base_path('public/upload/verifyId_images/'.$front_name));
        $front_url = 'upload/verifyId_images/'.$front_name;

         /// back side image upload
         $image_back = $request->file('back_image');
         $manger = new ImageManager(new Driver());
         $back_name = hexdec(uniqid()).".".$image_back->getClientOriginalExtension();
         $b_image = $manger->read($image_back);
         $b_image = $b_image->resize(120,120);
         $b_image->save(base_path('public/upload/verifyId_images/'.$back_name));
         $back_url = 'upload/verifyId_images/'.$back_name;

         VerifyId::insert([
            'front_image' => $front_url,
            'back_image' => $back_url,
            'user_id' => $id
         ]);

         return response()->json([
            'message' => 'Images uploaded successfully'
         ]);
       }
        

       
    }
}

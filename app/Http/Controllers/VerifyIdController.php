<?php

namespace App\Http\Controllers;

use App\Http\Requests\VerifyIdRequest;
use App\Models\VerifyId;
use App\Services\FileUpload;
use Illuminate\Support\Facades\Auth;


class VerifyIdController extends Controller
{  
   public function __construct(private FileUpload $fileupload)
   {
   
   }

    public function verify(VerifyIdRequest $request){
   
        $id = Auth::id();

      if($request->file('front_image') && $request->file('back_image')){
        /// front side image upload
         $front_image = $request->file('front_image');
         $front_url = $this->fileupload->verifyCnicFile($front_image);
         
          // /// back side image upload
          $back_image = $request->file('back_image');
          $back_url = $this->fileupload->verifyCnicFile($back_image);

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

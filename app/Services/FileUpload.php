<?php

namespace App\Services;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class FileUpload{
    public function verifyCnicFile($file){
        
        $manger = new ImageManager(new Driver());
        $name = hexdec(uniqid()).".".$file->getClientOriginalExtension();
        $image = $manger->read($file);
        $image = $image->resize(120,120);
        $image->save(base_path('public/upload/verifyId_images/'.$name));
        $path = 'upload/verifyId_images/'.$name;

    return $path;
    }//end method

    public function uploadFoodImage($file){
        $manger = new ImageManager(new Driver());
        $name = hexdec(uniqid()).".".$file->getClientOriginalExtension();
        $image = $manger->read($file);
        $image = $image->resize(120,120);
        $image->save(base_path('public/upload/food_images/'.$name));
        $path = 'upload/food_images/'.$name;
        return $path;
    }// end method

    public function uploadProfileImage($file){
        $manger = new ImageManager(new Driver());
        $name = hexdec(uniqid()).".".$file->getClientOriginalExtension();
        $image = $manger->read($file);
        $image = $image->resize(120,120);
        $image->save(base_path('public/upload/user_images/'.$name));
        $path = 'upload/user_images/'.$name;
        return $path;
    }
}

?>
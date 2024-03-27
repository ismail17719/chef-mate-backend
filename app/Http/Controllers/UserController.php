<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;


class UserController extends Controller
{

    /// User Register
    public function register(UserRequest $request){

        /// Create User
        User::create([  
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'role' => 'user',
            'status' => 'inactive'
        ]);
        
                  
        
        /// Generate Token
        $user = User::where('email',$request->email)->first();
        $token = $user->createToken($request->email)->plainTextToken;

        return response()->json([
            "token" => $token,
            "message" => "Registration successfully",
        ]);

    }
    /// End Method
        
    /// User Login

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $email = $request->email;
        $user = User::where('email',$email)->first();
        /// Check Email and Password
        if (!$user) {
            return response()->json([
                'message' => 'Email dose not match'
            ]);
        }elseif (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Password dose not match'
            ]);
        }else{
            $token = $user->createToken($email)->plainTextToken;

            return response()->json([
                'message' => 'Login Successfully',
                'token' => $token
            ]);
        }
    }
    /// End Method
        
        
    //// User Logout

    public function logout(){
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'Logout Successfully'
        ]);
    }
        
        
    /// Edit profile

    public function editProfile(){
        $userData = Auth::user();

        return response()->json([
            'user' => $userData,
            'message' => 'Edit profile',
        ]);
    }// end method

    // Update profile
    public function updateProfile(Request $request){
        $id = Auth::id();
        if ($request->file('profile_image')) {
            $file = $request->file('profile_image');
            $manger = new ImageManager(new Driver());
            $name = hexdec(uniqid()).".".$file->getClientOriginalExtension();
            $image = $manger->read($file);
            $image = $image->resize(120,120);
            $image->save(base_path('public/upload/user_images/'.$name));
            $path = 'upload/user_images/'.$name;

            User::find($id)->update([
                'full_name' => $request->full_name,
                'profile_image' => $path,
                'updated_at' => Carbon::now()
            ]);

            return response()->json([
                'message' => 'Your profile update successfully'
            ]);
        }

        // without image
        User::find($id)->update([
            'full_name' => $request->full_name,
            'updated_at' => Carbon::now()

        ]);
        return response()->json([
            'message' => 'Your profile update successfully'
        ]);

    }
}

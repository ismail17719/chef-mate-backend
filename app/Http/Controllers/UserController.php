<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kitchen;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class UserController extends Controller
{
    /// User Register
    public function register(Request $request){
        $request->validate([
            'email' => 'required|string|email|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required|confirmed',
            'address' => 'required',
            'profile_image' => 'extensions:png,jpeg'
        ]);
//// Create user with image
        if ($request->file('profile_image')) {
            $image_taken = $request->file('profile_image');
            $manager = new ImageManager(new Driver());
            $image_name = hexdec(uniqid()).'.'.$image_taken->getClientOriginalExtension();
            $image = $manager->read($image_taken);
            $image = $image->resize(120,120);
            $image->save(base_path('public/upload/user_images/'.$image_name));
            $save_url = 'upload/user_images/'.$image_name;

          
            /// Create User
            $user_id =  User::insertGetId([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'address' => $request->address,
                'profile_image' => $save_url,
                'created_at' => Carbon::now()
            ]);

            //// Insert Kitchen Title and get this id      
            Kitchen::insert([
                'user_id' => $user_id,
                'kitchen_title' => $request->kitchen_title,
                'created_at' => Carbon::now()
            ]);

            /// Generate Token
            $user = User::where('email',$request->email)->first();
            $token = $user->createToken($request->email)->plainTextToken;

            return response()->json([
                "token" => $token,
                "message" => "Registration successfully",
            ]);
        }
        
       
        



/// Create User
        $user_id =  User::insertGetId([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'address' => $request->address,
                'created_at' => Carbon::now()
            ]);

///// Insert Kitchen Title and get this id      
        Kitchen::insert([
            'user_id' => $user_id,
            'kitchen_title' => $request->kitchen_title,
            'created_at' => Carbon::now()
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


    /// Logged in user data

    public function loggedInUser(){
        $userData = Auth::user();

        return response()->json([
            'user' => $userData,
            'message' => 'Logged in user data',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
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
    public function register(UserRequest $request){

        /// Create User
        User::insert([
            
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
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

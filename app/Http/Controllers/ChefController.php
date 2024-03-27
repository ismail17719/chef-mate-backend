<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ChefRequest;
use App\Models\User;
use App\Models\Kitchen;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Services\FileUpload;


class ChefController extends Controller
{
    public function __construct(private FileUpload $fileupload)
    {
        
    }
     /// User Register
    public function register(ChefRequest $request){
        
        //// Create user with image
        if ($request->file('profile_image')) {
            $profile_image = $request->file('profile_image');
           
            $save_url = $this->fileupload->uploadProfileImage($profile_image);

            
            /// Create User
            $user_id =  User::insertGetId([
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
                
        
        // Create User
        $user_id =  User::insertGetId([
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
    /// end method


    /// Edit Profile

    public function updateProfile(Request $request){
        $request->validate([
            'email' => 'required',
            'phone' => 'required',
            'profile_image' => 'extensions:png,jpeg,jpg'
        ]);

        $id = Auth::id();

        if ($request->file('profile_image')) {

            $profile_image = $request->file('profile_image');
            $save_url = $this->fileupload->uploadProfileImage($profile_image);

            User::find($id)->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'profile_image' => $save_url,
                'updated_at' => Carbon::now()
            ]);
            
            Kitchen::where('user_id',$id)->update([
                'description' => $request->description,
                'updated_at' => Carbon::now()
            ]);
    
            return response()->json([
                'message' => 'Update profile successfully'
            ]);
        }

        /// Update without profile image
        User::find($id)->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'updated_at' => Carbon::now()
        ]);

        Kitchen::where('user_id',$id)->update([
            'description' => $request->description,
            'updated_at' => Carbon::now()
        ]);
        return response()->json([
            'message' => 'Update profile successfully'
        ]);
    }
    /// end method



}

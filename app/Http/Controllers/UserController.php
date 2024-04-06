<?php

namespace App\Http\Controllers;

use App\Events\UserOnlineEvent;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Services\FileUpload;

class UserController extends Controller
{
    public function __construct(private FileUpload $fileupload)
    {
        
    }

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
            event(new UserOnlineEvent($user,true));
            return response()->json([
                'message' => 'Login Successfully',
                'token' => $token
            ]);
        }
    }
    /// End Method
        
        
    //// User Logout

    public function logout(){
        $user = Auth::user();
        $user->last_seen_at = Carbon::now();
        $user->save();
        Auth::user()->tokens()->delete();
        event(new UserOnlineEvent($user,false));
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
           
            $path = $this->fileupload->uploadFoodImage($file);

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

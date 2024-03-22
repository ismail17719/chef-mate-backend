<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Mail\Message;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    public function resetPassword(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;

        /// Check email exist or not
        $user = User::where('email',$email)->first();

        if(!$user){
            return response()->json([
                "message" => "Email doesn't exist",
            ]);
        }

        /// Generate Token

        $token = Str::random(60);

        PasswordResetToken::insert([
            'email' => $email,
            'token' => $token
        ]);

        ///  send email

        Mail::send('reset_password.reset_password',['token' => $token], 
        function(Message $message)use($email){
            $message->subject("Reset your password");
            $message->to($email);
        });
        return response()->json([
            'message' => 'Password reset email sent...'
        ]);
    }
    /// end method


    public function reset(Request $request,$token){

        /// Delete Token
        $expire = Carbon::now()->subMinutes(2)->toDateTimeString();
        PasswordResetToken::where('created_at', '<=', $expire)->delete();

        $request->validate([
            'password' => 'required|confirmed',
        ]);

        $passwordreset = PasswordResetToken::where('token', $token)->first();

        if (!$passwordreset) {
            
            return response()->json([
                'message' => 'Token is invalid or expired'
            ]);
        }

        $user = User::where('email',$passwordreset->email)->first();

        $user->password = Hash::make($request->password);
        $user->save();

        PasswordResetToken::where('email',$user->email)->delete();

        return response()->json([
            'message' => 'Password Reset Successfully'
        ]);
    } 
}

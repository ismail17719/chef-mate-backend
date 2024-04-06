<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

class MessageController extends Controller
{
    public function sendMessage(Request $request,$conversation_id)
   {    
        $conv = Conversation::find($conversation_id);
        $sender_id = Auth::id();
        $message = Message::create([
            'conversation_id' => $conversation_id,
            'sender_id' => $sender_id,
            'receiver_id' => $conv->getReceiver()->id,
            'message' => $request->message
        ]);

        event(new MessageSent($message));
        return response()->json([
            'message' => 'Message send successfully'
        ]);
   }

   // Get Conversation Messages

   public function getConversationMessages($sender_id,$receiver_id)
   {
    $messages = Message::where(function ($qry) use ($sender_id,$receiver_id){
        $qry->where('sender_id',$sender_id)->where('receiver_id',$receiver_id);
    })->orWhere(function ($qry) use ($sender_id,$receiver_id){
        $qry->where('sender_id',$receiver_id)->where('receiver_id',$sender_id);
    })->orderBy('created_at','asc')->get();

    return response()->json([
        'conversation' => $messages
    ]);
   }

   // Get Users With Conversation

   public function getUsersWithConversations($id)
   {
    $senders = Message::where('receiver_id',$id)->pluck('sender_id')->unique();
    $receivers = Message::where('sender_id',$id)->pluck('receiver_id')->unique();

    $user_id = $senders->merge($receivers)->unique();

    $users = User::where('id',$user_id)->with(['messages' => function ($qry) use ($id){
        $qry->where('sender_id',$id)->orWhere('receiver_id')->latest()->first();
    }])->get();

    return response()->json($users);
   }
}

<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function createCoversation($receiver_id)
    {
        $sender_id = Auth::id();

        $existingConversation = Conversation::where(function ($qry) use ($sender_id,$receiver_id){
            $qry->where('sender_id',$sender_id)->where('receiver_id',$receiver_id);
        })->orWhere(function($qry) use ($sender_id,$receiver_id){
            $qry->where('sender_id',$receiver_id)->where('receiver_id',$sender_id);
        })->first();

        if ($existingConversation) {
            # code...
        }else {
            /// create converstion
        $createConversation = Conversation::create([
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id
        ]);

        return response()->json([
            'conversationId' => $createConversation->id,
            'message' => 'Conversation create successfully'
        ]);
        }
        
    }

        
}

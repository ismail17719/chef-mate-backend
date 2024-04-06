<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function newOrder(){
        $id = Auth::id();
        $new_order = Order::where('status','In Process')->where('chef_id',$id)->count();
       
        return response()->json([
            'new_Order' => $new_order,
        ]);
    }//end method

    public function completeOrder(){
        $id = Auth::id();
        
        $comp_order = Order::where('status','Complete')->where('chef_id',$id)->count();
      
       
        return response()->json([
            'complete_order' => $comp_order
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Exists;

class CartController extends Controller
{
    public function addToCart(Request $request){
      $request->validate([
        'menu_item_id' => 'required|exists:menu_items,id',
        'quantity' => 'required|integer|min:1'
      ]);

      $id = Auth::id();
      CartItem::create([
        'menu_item_id' => $request->menu_item_id,
        'user_id' => $id,
        'quantity' => $request->quantity,
        'size' => $request->size,
        'price' => $request->price,
      ]);

      return response()->json([
        'message' => 'Add to cart successfully'
      ]);
       
    }// end method

    public function viewCart(){
      $id = Auth::id();
      
        $cartItems = CartItem::where('user_id',$id)->get();

        return response()->json([
          'message' => 'View Cart',
          'cart_items' => $cartItems
        ]);
        
      
    
     
    }// end method

    public function incrementCartItem($id){
      $cartItem = CartItem::find($id);
      $cartItem->quantity += 1;
      $cartItem->save();

      return response()->json([
        'message' => 'Cart item incremented',
         'cart_item' => $cartItem
      ]);
    }// end method

    public function decrementCartItem($id){
      $cartItem = CartItem::find($id);
      if($cartItem->quantity > 1){
          $cartItem->quantity -= 1;
          $cartItem->save();

          return response()->json([
            'message' => 'Cart item decremented',
            'cart_item' => $cartItem
          ]);

      }else{  
            return response()->json([
              'message' => 'Cart item quantity reached minimum'
          ]);
      }

     
    }// end method

   
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function menuItems(){
        return $this->belongsTo(MenuItem::class, 'menu_item_id','id');
    }

    public function users(){
        return $this->belongsTo(User::class, 'user_id','id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function weeklyMenus(){
        return $this->hasMany(WeeklyMenu::class);
    }
    public function CartItems(){
        return $this->hasMany(CartItem::class);
    }
   
}

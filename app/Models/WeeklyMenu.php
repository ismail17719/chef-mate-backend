<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyMenu extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function menuItems(){
        return $this->belongsTo(MenuItem::class, 'menu_item_id','id');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\DaysOfWeek;
use App\Models\MenuItem;
use App\Models\WeeklyMenu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WeeklyMenuController extends Controller
{
    public function addWeeklyFood($food_id, $day_id){

        $id = Auth::id();

        WeeklyMenu::create([
            'user_id' => $id,
            'menu_item_id' => $food_id,
            'days_of_week_id' => $day_id
        ]);

        return response()->json([
            'message' => 'Add food in weekly menu'
        ]);
    }/// end method

    public function todayFood(){
        $day = Carbon::now();
        $day = $day->format('l');
        $day_id = DaysOfWeek::where('day',$day)->first();
        $day_id = $day_id->id;

        $menuitems = MenuItem::where('status','active')->whereHas('weeklyMenus',function($query) use ($day_id){
            $query->where('days_of_week_id',$day_id);
        })->get();

        //$menuitem = WeeklyMenu::where('days_of_week_id',$day_id)->withWhereHas('menuitems')->get();

        return response()->json([
            'todayMenu' => $menuitems,
            'message' => 'Get Today Menu Successfully'
        ]);
    }

  
}

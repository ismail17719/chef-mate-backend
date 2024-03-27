<?php

namespace App\Http\Controllers;

use App\Models\DaysOfWeek;
use Illuminate\Http\Request;

class DaysOfweekController extends Controller
{
    public function days(){
        $days = DaysOfWeek::select('id','day')->get();

        return response()->json([
            'days' => $days,
            'message' => 'Get all days of week'
        ]);
    }
}

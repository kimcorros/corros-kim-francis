<?php

namespace App\Http\Controllers;

use App\Models\StoreHour;
use Inertia\Inertia;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $storeHours = StoreHour::first();

        if (!$storeHours) {
            $storeHours = (object)[
                'open_days' => [
                    'monday'    => true,
                    'tuesday'   => false,
                    'wednesday' => true,
                    'thursday'  => false,
                    'friday'    => true,
                    'saturday'  => false,
                    'sunday'    => false,
                ],
                'opening_time'       => '08:00:00',
                'closing_time'       => '16:00:00',
                'lunch_break_start'  => '12:00:00',
                'lunch_break_end'    => '12:45:00',
                'alternate_saturday' => true,
            ];
        }

        return Inertia::render('Home', [
            'storeHours' => $storeHours,
        ]);
    }
}

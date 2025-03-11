<?php

namespace App\Http\Controllers;

use App\Models\StoreHour;
use Illuminate\Http\Request;

class StoreHoursController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'openingTime'       => 'required|date_format:H:i',
            'closingTime'       => 'required|date_format:H:i',
            'lunchBreakStart'   => 'required|date_format:H:i',
            'lunchBreakEnd'     => 'required|date_format:H:i',
            'openDays'          => 'required|array',
            'openDays.monday'    => 'sometimes|boolean',
            'openDays.tuesday'   => 'sometimes|boolean',
            'openDays.wednesday' => 'sometimes|boolean',
            'openDays.thursday'  => 'sometimes|boolean',
            'openDays.friday'    => 'sometimes|boolean',
            'openDays.saturday'  => 'sometimes|boolean',
            'openDays.sunday'    => 'sometimes|boolean',
            'alternateSaturday' => 'required|boolean',
        ]);

        $storeHour = StoreHour::first();
        if (!$storeHour) {
            $storeHour = new StoreHour();
        }

        $storeHour->open_days = $validated['openDays'];
        $storeHour->opening_time      = $validated['openingTime'] . ':00';
        $storeHour->closing_time      = $validated['closingTime'] . ':00';
        $storeHour->lunch_break_start = $validated['lunchBreakStart'] . ':00';
        $storeHour->lunch_break_end   = $validated['lunchBreakEnd'] . ':00';
        $storeHour->alternate_saturday = $validated['alternateSaturday'];
        $storeHour->save();

        return redirect()->back()->with('success', 'Store hours updated successfully.');
    }
}

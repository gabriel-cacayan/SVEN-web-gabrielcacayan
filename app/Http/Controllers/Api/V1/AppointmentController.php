<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'frequency'   => 'required|string|max:255',
            'start_date'  => 'required|date|after_or_equal:today',
            'days'        => 'required|array|min:1',
            'days.*'      => 'string|in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'times'       => 'required|array|min:1',
            'times.*'     => 'string|in:Morning,Afternoon,Evening',
            'notes'       => 'nullable|string|max:255',
        ]);

        $appointment = Appointment::create([
            'frequency'   => $validated['frequency'],
            'start_date'  => $validated['start_date'],
            'days'        => json_encode($validated['days']),
            'times'       => json_encode($validated['times']),
            'notes'       => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'message' => 'Success',
            'data'    => $appointment,
        ], 200);
    }
}

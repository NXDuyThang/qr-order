<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'date' => 'required|date',
            'time' => 'required',
            'guests' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        Booking::create($validated);

        return redirect()->route('booking')->with('success', 'Đặt bàn thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất để xác nhận.');
    }
}

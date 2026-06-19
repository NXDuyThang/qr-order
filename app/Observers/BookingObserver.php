<?php

namespace App\Observers;

use App\Models\Booking;

use App\Models\User;
use App\Mail\AdminBookingNotification;
use App\Mail\CustomerBookingNotification;
use Illuminate\Support\Facades\Mail;

class BookingObserver
{
    /**
     * Handle the Booking "created" event.
     */
    public function created(Booking $booking): void
    {
        // 1. Gửi cho admin
        // Lấy admin user (hoặc fix cứng email admin)
        $adminEmail = '2224801030385@student.tdmu.edu.vn';
        try {
            Mail::to($adminEmail)->send(new AdminBookingNotification($booking));
        } catch (\Exception $e) {
            // Log lỗi nếu cần thiết
            \Log::error('Cannot send admin booking email: ' . $e->getMessage());
        }

        // 2. Gửi cho khách hàng (nếu có email)
        if (!empty($booking->email)) {
            try {
                Mail::to($booking->email)->send(new CustomerBookingNotification($booking));
            } catch (\Exception $e) {
                \Log::error('Cannot send customer booking email: ' . $e->getMessage());
            }
        }
    }

    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "deleted" event.
     */
    public function deleted(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "restored" event.
     */
    public function restored(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "force deleted" event.
     */
    public function forceDeleted(Booking $booking): void
    {
        //
    }
}

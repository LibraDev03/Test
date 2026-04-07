<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function showSearch()
    {
        return view('admin.booking.search');
    }
    
    public function searchResult(Request $request)
    {
        // validate: nếu tất cả đều rỗng → lỗi
        if (
            !$request->customer_name &&
            !$request->customer_contact &&
            !$request->checkin_time &&
            !$request->checkout_time
        ) {
            return redirect()
                ->route('adminBookingSearchPage')
                ->withErrors(['search' => '何も入力されていません']);
        }

        $query = Booking::query();

        if ($request->customer_name) {
            $query->where('customer_name', 'like', '%' . $request->customer_name . '%');
        }

        if ($request->customer_contact) {
            $query->where('customer_contact', 'like', '%' . $request->customer_contact . '%');
        }

        if ($request->checkin_time) {
            $query->where('checkin_time', '>=', $request->checkin_time);
        }

        if ($request->checkout_time) {
            $query->where('checkout_time', '<=', $request->checkout_time);
        }

        $bookings = $query->with('hotel')->get();

        return view('admin.booking.result', compact('bookings'));
    }
}

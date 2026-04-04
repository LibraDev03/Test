<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TopController extends Controller
{
    public function index(): View
    {
        $hotelList = Hotel::with('prefecture')->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.home', compact('hotelList'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Hotel;
use App\Models\Prefecture;
use Symfony\Component\HttpFoundation\RedirectResponse;

class HotelController extends Controller
{
    /** get methods */

    public function showSearch(): View
    {
        return view('admin.hotel.search');
    }

    public function showResult(): View
    {
        return view('admin.hotel.result');
    }

    public function showEdit(): View
    {
        return view('admin.hotel.edit');
    }

    public function showCreate(): View
    {
        $prefectures = Prefecture::all();
        
        return view('admin.hotel.create', compact('prefectures'));
    }

    /** post methods */

    public function searchResult(Request $request): View
    {
        $var = [];

        $hotelNameToSearch = $request->input('hotel_name');
        $hotelList = Hotel::getHotelListByName($hotelNameToSearch);

        $var['hotelList'] = $hotelList;

        return view('admin.hotel.result', $var);
    }

    public function edit(Request $request): void
    {
        //
    }

    public function create(Request $request): RedirectResponse
    {
        $request->validate([
            'hotel_name' => 'required|max:255',
            'prefecture_id' => 'required|exists:prefectures,prefecture_id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $filePath = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/img/hoteltype'), $fileName);

            $filePath = 'hoteltype/' . $fileName;
        }

        Hotel::create([
            'hotel_name' => $request->hotel_name,
            'prefecture_id' => $request->prefecture_id,
            'file_path' => $filePath
        ]);

        return redirect()->route('adminTop')->with('success', '登録成功しました');

    }

    public function delete(Request $request): void
    {
        //
    }
}

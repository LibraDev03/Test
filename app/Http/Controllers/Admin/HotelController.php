<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Hotel;
use App\Models\Prefecture;
use Illuminate\Validation\ValidationException;
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

    public function showEdit(Request $request): View
    {
        $hotel = Hotel::findOrFail($request->hotel_id);
        $prefectures = Prefecture::all();

        return view('admin.hotel.edit', compact('hotel', 'prefectures'));
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

    public function edit(Request $request): RedirectResponse
    {
        $request->validate([
            'hotel_name' => 'required|max:255',
            'prefecture_id' => 'required|exists:prefectures,prefecture_id'
        ]);

        $hotel = Hotel::findOrFail($request->hotel_id);

        if ($request->hasFile('image')) {

            if ($hotel->file_path && file_exists(public_path('assets/img/' . $hotel->file_path))) {
                unlink(public_path('assets/img/' . $hotel->file_path));
            }

            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/img/hotel'), $fileName);

            $hotel->file_path = 'hotel/' . $fileName;
        }

        $hotel->hotel_name = $request->hotel_name;
        $hotel->prefecture_id = $request->prefecture_id;
        $hotel->save();

        return redirect()->route('adminTop')->with('success', '更新しました');
    }

    public function updateAjax(Request $request)
    {
        try {
            $request->validate([
                'hotel_id' => 'required|exists:hotels,hotel_id',
                'hotel_name' => 'required|max:255',
                'prefecture_id' => 'required|exists:prefectures,prefecture_id',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        }

        $hotel = Hotel::findOrFail($request->hotel_id);

        if ($request->hasFile('image')) {
            if ($hotel->file_path && file_exists(public_path('assets/img/' . $hotel->file_path))) {
                unlink(public_path('assets/img/' . $hotel->file_path));
            }
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/img/hotel'), $fileName);
            $hotel->file_path = 'hotel/' . $fileName;
        }

        $hotel->hotel_name = $request->hotel_name;
        $hotel->prefecture_id = $request->prefecture_id;
        $hotel->save();

        return response()->json([
            'success' => true,
            'message' => '更新しました'
        ]);
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
            $file->move(public_path('assets/img/hotel'), $fileName);
            $filePath = 'hotel/' . $fileName;
        }

        Hotel::create([
            'hotel_name' => $request->hotel_name,
            'prefecture_id' => $request->prefecture_id,
            'file_path' => $filePath
        ]);

        return redirect()->route('adminTop')->with('success', '登録成功しました');

    }

    public function delete(Request $request): RedirectResponse
    {
        $request->validate([
            'hotel_id' => 'required|integer|exists:hotels,hotel_id'
        ]);

        $hotel = Hotel::findOrFail($request->hotel_id);

        if ($hotel->file_path && file_exists(public_path('assets/img/' . $hotel->file_path))) {
            unlink(public_path('assets/img/' . $hotel->file_path));
        }

        $tempFile = str_replace('hotel/', 'temp/', $hotel->file_path);
        if ($hotel->file_path && str_starts_with($hotel->file_path, 'hotel/') 
            && file_exists(public_path('assets/img/' . $tempFile))) {
            unlink(public_path('assets/img/' . $tempFile));
        }

        $hotel->delete();
        
       return redirect()->back()->with('success', '削除しました');
    }
}

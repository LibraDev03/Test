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
        $prefectures = Prefecture::all();
        return view('admin.hotel.search', compact('prefectures'));
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

    public function searchResult(Request $request): View|RedirectResponse
    {
        $hotelName = $request->input('hotel_name');
        $prefectureId = $request->input('prefecture_id');

        if (empty($hotelName) && empty($prefectureId)) {
            return back()->withErrors([
                'search' => '何も入力されていません'
            ]);
        }

        $query = Hotel::query();

        if ($hotelName) {
            $query->where('hotel_name', 'like', '%' . $hotelName . '%');
        }

        if ($prefectureId) {
            $query->where('prefecture_id', $prefectureId);
        }

        // paginate 10 kết quả / trang
        $hotelList = $query->paginate(10)->withQueryString(); 

        $prefectures = Prefecture::all();

        return view('admin.hotel.result', compact('hotelList', 'prefectures'));
    }

    public function edit(Request $request): RedirectResponse
    {
        $request->validate([
            'hotel_name' => 'required|max:255',
            'prefecture_id' => 'required|exists:prefectures,prefecture_id'
        ], [
            'hotel_name.required' => 'ホテル名を入力してください',
            'hotel_name.max' => 'ホテル名は255文字以内で入力してください',

            'prefecture_id.required' => '都道府県を選択してください',
            'prefecture_id.exists' => '正しい都道府県を選択してください',
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
            ], [
                'hotel_id.required' => 'ホテルIDが不正です',
                'hotel_id.exists' => 'ホテルが存在しません',

                'hotel_name.required' => 'ホテル名を入力してください',
                'hotel_name.max' => 'ホテル名は255文字以内で入力してください',

                'prefecture_id.required' => '都道府県を選択してください',
                'prefecture_id.exists' => '正しい都道府県を選択してください',

                'image.image' => '画像ファイルを選択してください',
                'image.mimes' => 'jpg, jpeg, png, webp形式のみアップロード可能です',
                'image.max' => '画像サイズは2MB以内にしてください'
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
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ], [
            'hotel_name.required' => 'ホテル名を入力してください',
            'hotel_name.max' => 'ホテル名は255文字以内で入力してください',

            'prefecture_id.required' => '都道府県を選択してください',
            'prefecture_id.exists' => '正しい都道府県を選択してください',

            'image.image' => '画像ファイルを選択してください',
            'image.mimes' => 'jpg, jpeg, png形式のみアップロード可能です',
            'image.max' => '画像サイズは2MB以内にしてください'
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
        ], [
            'hotel_id.required' => 'ホテルIDが必要です',
            'hotel_id.integer' => 'ホテルIDの形式が不正です',
            'hotel_id.exists' => 'ホテルが存在しません'
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

        return redirect()->route('adminTop')->with('success', '削除しました');
    }
}

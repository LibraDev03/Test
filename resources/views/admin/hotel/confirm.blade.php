{{-- <!-- confirm.blade.php -->
@extends('common.admin.base')

@section('custom_css')
    @vite('resources/scss/admin/home.scss')
@endsection

@section('main_contents')
<div class="container">
    <h2 class="title">ホテル編集確認</h2>

    <table class="shopsearchlist_table">
        <tbody>
            <tr>
                <td>ホテル名</td>
                <td>{{ $data['hotel_name'] }}</td>
            </tr>

            <tr>
                <td>都道府県</td>
                <td>{{ $prefecture->prefecture_name }}</td>
            </tr>

            <tr>
                <td>画像</td>
                <td>
                    @if($data['file_path'])
                        <img src="{{ asset('assets/img/' . $data['file_path']) }}" width="120">
                    @else
                        なし
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <form action="{{ route('adminHotelUpdate') }}" method="POST">
        @csrf
        <input type="hidden" name="hotel_id" value="{{ $data['hotel_id'] }}">
        <input type="hidden" name="hotel_name" value="{{ $data['hotel_name'] }}">
        <input type="hidden" name="prefecture_id" value="{{ $data['prefecture_id'] }}">
        <input type="hidden" name="file_path" value="{{ $data['file_path'] }}">

        <div style="margin-top:15px; text-align:center;">
            <button type="submit">更新する</button>
            <a href="{{ route('adminHotelEditPage', ['hotel_id' => $data['hotel_id']]) }}" style="margin-left:10px;">戻る</a>
        </div>
    </form>
</div>
@endsection --}}
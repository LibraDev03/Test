<!-- base view -->
@extends('common.admin.base')

<!-- CSS per page -->
@section('custom_css')
    @vite('resources/scss/admin/home.scss')
@endsection

<!-- main contents -->
@section('main_contents')

<div class="container">
    <h2 class="title">ホテル追加</h2>

    @if ($errors->any())
        <div style="color:red; margin-bottom: 15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('adminHotelCreateProcess') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <table class="shopsearchlist_table">
            <tbody>

                <tr>
                    <td>ホテル名</td>
                    <td>
                        <input type="text" name="hotel_name" value="{{ old('hotel_name') }}">
                    </td>
                </tr>

                <tr>
                    <td>都道府県</td>
                    <td>
                        <select name="prefecture_id">
                            <option value="">選択してください</option>
                            @foreach ($prefectures as $pref)
                                <option value="{{ $pref->prefecture_id }}"
                                    {{ old('prefecture_id') == $pref->prefecture_id ? 'selected' : '' }}>
                                    {{ $pref->prefecture_name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>画像</td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>

                <tr>
                    <td colspan="2" style="text-align:center;">
                        <button type="submit">登録</button>
                    </td>
                </tr>

            </tbody>
        </table>
    </form>
</div>

@endsection
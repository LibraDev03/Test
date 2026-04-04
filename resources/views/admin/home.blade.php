<!-- base view -->
@extends('common.admin.base')

<!-- CSS per page -->
@section('custom_css')
    @vite('resources/scss/admin/home.scss')
@endsection

<!-- main contents -->
@section('main_contents')
    <div class="top">
        <h2 class="title">THK Holdings Vietnam Hanoi's entrance examination</h2>
        <p class="description">
            This is a management screen for THK Holdings Vietnam Hanoi's entry examination.<br>
            Although it will be a management screen, a login feature has not been implemented this time to simplify testing. note that.
        </p>
    </div>

    <div class="container">

        <h3 class="title">ホテル一覧</h3>

        @if(session('success'))
            <p style="color:green;">{{ session('success') }}</p>
        @endif

        <table class="shopsearchlist_table">
            <thead>
                <tr>
                    <th>ホテル名</th>
                    <th>都道府県</th>
                    <th>登録日</th>
                    <th>更新日</th>
                    <th>編集</th>
                    <th>削除</th>
                </tr>
            </thead>

            <tbody>
                @foreach($hotelList as $hotel)
                    <tr>
                        <td>{{ $hotel->hotel_name }}</td>

                        <td>
                            {{ $hotel->prefecture->prefecture_name ?? '' }}
                        </td>

                        <td>{{ $hotel->created_at }}</td>
                        <td>{{ $hotel->updated_at }}</td>

                        {{-- EDIT --}}
                        <td>
                            <form action="{{ route('adminHotelEditPage') }}" method="GET">
                                <input type="hidden" name="hotel_id" value="{{ $hotel->hotel_id }}">
                                <button type="submit">編集</button>
                            </form>
                        </td>

                        {{-- DELETE --}}
                        <td>
                            <form action="{{ route('adminHotelDeleteProcess') }}" method="POST"
                                onsubmit="return confirm('削除してもよろしいですか？')">
                                @csrf
                                <input type="hidden" name="hotel_id" value="{{ $hotel->hotel_id }}">
                                <button type="submit">削除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
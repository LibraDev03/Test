@extends('common.admin.base')

@section('custom_css')
    @vite('resources/scss/admin/search.scss')
    @vite('resources/scss/admin/result.scss')
@endsection

@section('main_contents')
    <div class="page-wrapper search-page-wrapper">
        <h2 class="title">予約情報検索</h2>
        <hr>

        <div class="search-hotel-name">
            <form action="{{ route('adminBookingSearchResult') }}" method="GET">
                @csrf
                <div style="display: flex; gap: 20px">

                    {{-- 顧客名 --}}
                    <div>
                        <input type="text" name="customer_name" placeholder="顧客名">
                    </div>

                    {{-- 連絡先 --}}
                    <div>
                        <input type="text" name="customer_contact" placeholder="連絡先">
                    </div>

                    {{-- チェックイン --}}
                    <div>
                        <input type="date" name="checkin_time">
                    </div>

                    {{-- チェックアウト --}}
                    <div>
                        <input type="date" name="checkout_time">
                    </div>

                    {{-- ボタン --}}
                    <div>
                        <button type="submit">検索</button>
                    </div>

                </div>
            </form>
            @if ($errors->has('search'))
                <p style="color:red">
                    {{ $errors->first('search') }}
                </p>
            @endif
        </div>

        <hr>
    </div>

    @yield('search_results')
@endsection
<!-- base view -->
@extends('common.admin.base')

<!-- CSS per page -->
@section('custom_css')
    @vite('resources/scss/admin/search.scss')
    @vite('resources/scss/admin/result.scss')
@endsection

<!-- main containts -->
@section('main_contents')
    <div class="page-wrapper search-page-wrapper">
        <h2 class="title">検索画面</h2>
        <hr>
        <div class="search-hotel-name">
            <form action="{{ route('adminHotelSearchResult') }}" method="get">
                @csrf
                <div style="display: flex; gap: 20px">

                    {{-- bên trái: search tên --}}
                    <div>
                        <input type="text" name="hotel_name" placeholder="ホテル名">
                    </div>

                    {{-- bên phải: select tỉnh --}}
                    <div>
                        <select name="prefecture_id">
                            <option value="">都道府県を選択</option>
                            @foreach($prefectures as $pref)
                                <option value="{{ $pref->prefecture_id }}">
                                    {{ $pref->prefecture_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- nút search --}}
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
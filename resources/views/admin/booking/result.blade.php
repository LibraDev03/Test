@extends('admin.booking.search')

@section('search_results')
    <div class="page-wrapper search-page-wrapper">
        <div class="search-result">
            <h3 class="search-result-title">検索結果</h3>

            @if (!empty($bookings) && count($bookings) > 0)
                <table class="shopsearchlist_table">
                    <tbody>
                        <tr>
                            <td>顧客名</td>
                            <td>連絡先</td>
                            <td>チェックイン</td>
                            <td>チェックアウト</td>
                            <td>ホテル名</td>
                            {{-- <td>登録日</td> --}}
                            {{-- <td>更新日</td> --}}
                        </tr>

                        @foreach($bookings as $b)
                            <tr style="background-color:#BDF1FF">
                                <td>{{ $b->customer_name }}</td>
                                <td>{{ $b->customer_contact }}</td>
                                <td>{{ $b->checkin_time }}</td>
                                <td>{{ $b->checkout_time }}</td>
                                <td>{{ $b->hotel->hotel_name ?? '未設定' }}</td>
                                {{-- <td>{{ $b->created_at }}</td> --}}
                                {{-- <td>{{ $b->updated_at }}</td> --}}
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            @else
                <p>検索結果がありません</p>
            @endif

        </div>
    </div>
@endsection
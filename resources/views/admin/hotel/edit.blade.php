@extends('common.admin.base')

@section('custom_css')
    @vite('resources/scss/admin/home.scss')
    <style>
        /* Simple modal */
        #confirmModal {
            display: none;
            position: fixed; top: 0; left:0; width:100%; height:100%;
            background: rgba(0,0,0,0.5); z-index: 9999;
        }
        #confirmModal .modal-content {
            background: #fff; margin: 100px auto; padding: 20px; width: 400px; border-radius: 8px;
        }
        #confirmModal table { width: 100%; }
    </style>
@endsection

@section('main_contents')
<div class="container">
    <h2 class="title">ホテル編集</h2>

    <form id="hotelEditForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="hotel_id" value="{{ $hotel->hotel_id }}">

        <table class="shopsearchlist_table">
            <tr>
                <td>ホテル名</td>
                <td><input type="text" name="hotel_name" value="{{ old('hotel_name', $hotel->hotel_name) }}"></td>
            </tr>
            <tr>
                <td>都道府県</td>
                <td>
                    <select name="prefecture_id">
                        <option value="">選択してください</option>
                        @foreach ($prefectures as $pref)
                            <option value="{{ $pref->prefecture_id }}"
                                {{ old('prefecture_id', $hotel->prefecture_id) == $pref->prefecture_id ? 'selected' : '' }}>
                                {{ $pref->prefecture_name }}
                            </option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td>現在の画像</td>
                <td>
                    @if ($hotel->file_path)
                        <img src="{{ asset('assets/img/' . $hotel->file_path) }}" width="120">
                    @else なし @endif
                </td>
            </tr>
            <tr>
                <td>画像変更</td>
                <td><input type="file" name="image" id="imageInput"></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center;">
                    <button type="submit">プレビュー</button>
                </td>
            </tr>
        </table>
    </form>
</div>

<!-- Popup confirm -->
<div id="confirmModal">
    <div class="modal-content">
        <h3>確認</h3>
        <div id="modalErrors" style="color:red; margin-bottom:10px;"></div>
        <table>
            <tr><td>ホテル名</td><td id="cHotelName"></td></tr>
            <tr><td>都道府県</td><td id="cPrefecture"></td></tr>
            <tr><td>画像</td><td><img id="cImage" width="120" /></td></tr>
        </table>
        <div style="margin-top:15px; text-align:center;">
            <button id="confirmUpdateBtn" type="button">更新する</button>
            <button id="cancelBtn" type="button" style="margin-left:10px;">戻る</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('hotelEditForm');
        const modal = document.getElementById('confirmModal');
        const cHotelName = document.getElementById('cHotelName');
        const cPrefecture = document.getElementById('cPrefecture');
        const cImage = document.getElementById('cImage');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            document.getElementById('modalErrors').innerHTML = '';
            cHotelName.innerText = form.hotel_name.value;
            cPrefecture.innerText = form.prefecture_id.options[form.prefecture_id.selectedIndex].text;

            const file = document.getElementById('imageInput').files[0];
            if(file){
                const reader = new FileReader();
                reader.onload = e => { cImage.src = e.target.result; };
                reader.readAsDataURL(file);
            } else {
                cImage.src = "{{ $hotel->file_path ? asset('assets/img/' . $hotel->file_path) : '' }}";
            }

            modal.style.display = 'block';
        });

        document.getElementById('cancelBtn').addEventListener('click', () => {
            modal.style.display = 'none';
            document.getElementById('modalErrors').innerHTML = '';
        });

        document.getElementById('confirmUpdateBtn').addEventListener('click', function(){
            const formData = new FormData(form);

            fetch("{{ route('adminHotelUpdateAjax') }}", {
                method: "POST",
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            })
            .then(async res => {
                const data = await res.json();

                if (!res.ok) {
                    console.log(data); // 👈 debug
                    // alert('Validation lỗi: ' + JSON.stringify(data.errors));
                    const errorBox = document.getElementById('modalErrors');
                    errorBox.innerHTML = ''; // clear lỗi cũ

                    const errors = data.message;

                    for (let field in errors) {
                        errors[field].forEach(msg => {
                            const div = document.createElement('div');
                            div.innerText = msg;
                            errorBox.appendChild(div);
                        });
                    }
                    return;
                }

                alert(data.message);
                window.location.href = "{{ route('adminTop') }}";
            })
            .catch(err => console.error('Lỗi fetch:', err));
        });
    });
</script>
@endsection
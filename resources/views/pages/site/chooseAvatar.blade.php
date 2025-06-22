<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Выбор аватара</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adaptation.css') }}">

    <script src="{{ asset('js/script.js') }}" defer></script>
    <link rel="icon" href="{{ asset('icon-logo.svg') }}" type="image/svg+xml">
</head>

<body>
    @include('layout.header')
    <div class="choose-avatar">
        <div class="wrapper">
            <div class="head-block">
                <h2><span class="attention-title">Выбери</span> свой аватар</h2>
                <p>Можно загрузить свой аватар или выбрать из доступных</p>
            </div>
            <form action="{{ route('choose.avatar.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="choice">
                    <div class="upload-section">
                        <label for="custom_avatar" class="upload-label">
                            <img src="{{ asset('images/Upload file area.svg') }}" alt="Загрузите файл"
                                id="upload-preview">
                        </label>
                        <input type="file" name="avatar" id="custom_avatar" class="hidden-input">
                    </div>

                    <div class="avatar-grid">
                        @foreach (range(1, 16) as $id)
                            <img src="{{ asset('images/avatar' . $id . '.svg') }}" alt="Аватар {{ $id }}"
                                class="avatar-option" data-avatar-id="{{ $id }}">
                        @endforeach
                    </div>
                </div>
                <input type="hidden" name="default_avatar" id="selected-avatar" value="">
                @error('avatar')
                    <span class="error-message">{{ $message }}</span>
                @enderror
                <div class="buttons">
                    <a href="{{ route('login') }}">
                        <div class="button-next">
                            <p>Пропустить</p>
                            <img src="{{ asset('images/arrow-right button.svg') }}" alt="">
                        </div>
                    </a>
                    <button type="submit">Сохранить</button>
                </div>
            </form>


        </div>
    </div>

    @include('layout.footer')
</body>
<script>
    function previewImage(event) {
        const output = document.getElementById('upload-preview');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src);
        };
    }
</script>

</html>

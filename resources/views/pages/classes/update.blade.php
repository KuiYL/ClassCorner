<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование Класса</title>
    <link rel="stylesheet" href="{{ asset('css/style-platform.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/script.js') }}" defer></script>
</head>

<body>
    @include('layout.sidebar', ['activePage' => 'classes'])

    <div class="topbar">
        @include('layout.topbar')
        <main>
            <div class="main-platform">

                <form action="{{ route('classes.update', $class->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="head-block">
                        <h2><span class="attention-title">Изменить</span> данные класса</h1>
                    </div>
                    <div class="form-group">
                        <label for="name">Название<span>*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $class->name) }}">
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Описание (необязательно)</label>
                        <textarea id="description" name="description" rows="4">{{ old('description', $class->description) }}</textarea>
                        @error('description')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="action-button">Сохранить изменения</button>
                </form>
            </div>
        </main>
    </div>
</body>

</html>

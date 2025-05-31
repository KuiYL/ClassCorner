<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создание Класса</title>
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

                <form action="{{ route('classes.store') }}" method="POST" class="mt-6">
                    @csrf
                    <div class="head-block">
                        <h2><span class="attention-title">Добавить</span> новый класс</h1>
                    </div>
                    <div class="form-group">
                        <label for="name">Название класса <span>*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="{{ $errors->has('name') ? 'input-error' : '' }}">
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Описание (необязательно)</label>
                        <textarea name="description" id="description" rows="4"
                            class="{{ $errors->has('description') ? 'input-error' : '' }}">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Преподаватель <span>*</span></label>
                        <p class="teacher-name">{{ auth()->user()->name }} {{ auth()->user()->surname }}</p>
                        <input type="hidden" name="teacher_id" value="{{ auth()->user()->id }}">
                        @error('teacher_id')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <input type="hidden" name="return_url" value="{{ request('return_url', route('user.classes')) }}">

                    <button type="submit" class="action-button">Создать класс</button>
                </form>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

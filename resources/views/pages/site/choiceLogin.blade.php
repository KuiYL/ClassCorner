<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Контакты</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adaptation.css') }}">

    <script src="{{ asset('js/script.js') }}" defer></script>
    <link rel="icon" href="{{ asset('icon-logo.svg') }}" type="image/svg+xml">
</head>

<body>
    @include('layout.header')

    <div class="choice-login hidden">
        <div class="wrapper">
            <div class="choice">
                <a href="{{ route('register.role', ['role' => 'student']) }}">
                    <img src="{{ asset('images/ChoiceStudent.svg') }}" alt="">
                </a>
                <a href="{{ route('register.role', ['role' => 'teacher']) }}">
                    <img src="{{ asset('images/ChoiceTeacher.svg') }}" alt="">
                </a>
            </div>
            <a href="{{ route('login') }}">Уже есть аккаунт? <span class="active">Войти</span></a>
        </div>
    </div>

    @include('layout.footer')
</body>

</html>

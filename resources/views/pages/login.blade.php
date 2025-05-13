<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Авторизация</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adaptation.css') }}">

    <script src="{{ asset('js/script.js') }}" defer></script>
</head>

<body>
    @include('layout.header')

    <div class="auth">
        <div class="wrapper">
            <div class="first">
                <div class="head-block">
                    <h2><span class="attention-title">Войдите</span> в свой аккаунт</h2>
                    <p>Пожалуйста, введите свои учетные данные для продолжения</p>
                </div>

                <form action="{{ route('login') }}" method="POST" class="auth-form">
                    @csrf
                    @if ($errors->has('global'))
                        <div class="error-message global-error">
                            {{ $errors->first('global') }}
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="email">Электронная почта <span>*</span></label>
                        <input type="email" id="email" name="email" placeholder="Введите электронную почту"
                            value="{{ old('email') }}" class="{{ $errors->has('email') ? 'input-error' : '' }}">
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Пароль <span>*</span></label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" placeholder="Введите пароль"
                                class="{{ $errors->has('password') ? 'input-error' : '' }}">
                            <button type="button" class="toggle-password">
                                <img src="{{ asset('images/showPassword.svg') }}" alt="Показать пароль"
                                    class="icon-show">
                                <img src="{{ asset('images/hidePassword.svg') }}" alt="Скрыть пароль"
                                    class="icon-hide hidden">
                            </button>
                        </div>
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="action-button">Войти в аккаунт</button>
                </form>
                <a href="{{ route('choiceLogin') }}">Нет аккаунта? <span class="active">Зарегистрируйтесь</span></a>
            </div>
            <div class="second">
                <img src="{{ asset('images/bannerLogin.svg') }}" alt="">
            </div>
        </div>
    </div>

    @include('layout.footer')
</body>

</html>

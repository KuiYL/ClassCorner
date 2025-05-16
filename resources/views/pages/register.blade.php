<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Регистрация</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adaptation.css') }}">

    <script src="{{ asset('js/script.js') }}" defer></script>
    <link rel="icon" href="{{ asset('icon-logo.svg') }}" type="image/svg+xml">
</head>

<body>
    @include('layout.header')

    <div class="auth hidden">
        <div class="wrapper">
            @if ($role === 'student')
                <div class="second">
                    <img src="{{ asset('images/registerStudent.svg') }}" alt="">
                </div>
                <div class="first">
                    <div class="head-block">
                        <h2><span class="attention-title">Регистрация</span> для студента</h2>
                        <p>Создайте аккаунт для начала обучения и отслеживания своего прогресса</p>
                    </div>

                    <form action="{{ route('register.store', $role) }}" method="POST" class="auth-form">
                        @csrf
                        <input type="hidden" name="role" value="{{ $role }}">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Имя <span>*</span></label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    placeholder="Ваше имя" class="{{ $errors->has('name') ? 'input-error' : '' }}">
                                @error('name')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="surname">Фамилия <span>*</span></label>
                                <input type="text" id="surname" name="surname" value="{{ old('surname') }}"
                                    placeholder="Ваша фамилия"class="{{ $errors->has('surname') ? 'input-error' : '' }}">
                                @error('surname')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Электронная почта <span>*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                placeholder="Введите электронную почту"
                                class="{{ $errors->has('email') ? 'input-error' : '' }}">
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
                        <div class="form-group">
                            <label for="password_confirmation">Подтверждение пароля <span>*</span></label>
                            <div class="password-wrapper">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    placeholder="Подтвердите пароль">
                                <button type="button" class="toggle-password">
                                    <img src="{{ asset('images/showPassword.svg') }}" alt="Показать пароль"
                                        class="icon-show">
                                    <img src="{{ asset('images/hidePassword.svg') }}" alt="Скрыть пароль"
                                        class="icon-hide hidden">
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="action-button">Зарегистрироваться</button>
                    </form>

                    <a href="{{ route('login') }}">Уже есть аккаунт? <span class="active">Войдите</span></a>
                </div>
            @elseif($role === 'teacher')
                <div class="second">
                    <img src="{{ asset('images/registerTeacher.svg') }}" alt="">
                </div>
                <div class="first">
                    <div class="head-block">
                        <h2><span class="attention-title">Регистрация</span> для учителя</h2>
                        <p>Создайте аккаунт для управления уроками и студентами</p>
                    </div>

                    <form action="{{ route('register.store', $role) }}" method="POST" class="auth-form">
                        @csrf
                        <input type="hidden" name="role" value="{{ $role }}">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Имя <span>*</span></label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    placeholder="Ваше имя" class="{{ $errors->has('name') ? 'input-error' : '' }}">
                                @error('name')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="surname">Фамилия <span>*</span></label>
                                <input type="text" id="surname" name="surname" value="{{ old('surname') }}"
                                    placeholder="Ваша фамилия"
                                    class="{{ $errors->has('surname') ? 'input-error' : '' }}">
                                @error('surname')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Электронная почта <span>*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                placeholder="Введите электронную почту"
                                class="{{ $errors->has('email') ? 'input-error' : '' }}">
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
                        <div class="form-group">
                            <label for="password_confirmation">Подтверждение пароля <span>*</span></label>
                            <div class="password-wrapper">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    placeholder="Подтвердите пароль">
                                <button type="button" class="toggle-password">
                                    <img src="{{ asset('images/showPassword.svg') }}" alt="Показать пароль"
                                        class="icon-show">
                                    <img src="{{ asset('images/hidePassword.svg') }}" alt="Скрыть пароль"
                                        class="icon-hide hidden">
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="action-button">Зарегистрироваться</button>
                    </form>

                    <a href="{{ route('login') }}">Уже есть аккаунт? <span class="active">Войдите</span></a>
                </div>
            @else
                <p>Неверная роль или данные не переданы.</p>
            @endif
        </div>
    </div>

    @include('layout.footer')
</body>

</html>

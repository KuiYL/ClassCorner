<header id="top">
    <div class="wrapper">

        <button class="hamburger" aria-label="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <menu class="menu-burger">
            <li><a href="{{ route('home') }}" class="">Главная</a></li>
            <li><a href="{{ route('about') }}" class="">О платформе</a></li>
            <li><a href="{{ route('career') }}" class="">Поддержка</a></li>
            <li><a href="{{ route('contact') }}" class="">Контакты</a></li>
        </menu>

        <a href="{{ route('home') }}">
            <img src="{{ asset('images/logo.svg') }}" alt="">
        </a>

        <menu class="menu">
            <li><a href="{{ route('home') }}" class="link-button">Главная</a></li>
            <li><a href="{{ route('about') }}" class="link-button">О платформе</a></li>
            <li><a href="{{ route('career') }}" class="link-button">Поддержка</a></li>
            <li><a href="{{ route('contact') }}" class="link-button">Контакты</a></li>
            @auth
                @if (auth()->user()->role === 'admin')
                    <li><a href="{{ route('admin.dashboard') }}" class="link-button">Админ-панель</a></li>
                @endif
                @if (auth()->user()->role === 'teacher' || auth()->user()->role === 'student')
                    <li><a href="{{ route('user.dashboard') }}" class="link-button">Личный кабинет</a></li>
                @endif
            @endauth
        </menu>

        @guest
            <a href="{{ route('choiceLogin') }}" class="login-button">Войти в систему</a>
            <a href="{{ route('choiceLogin') }}" class="login-button-phone">Войти</a>
        @else
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

            <a href="#" class="login-button"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Выход
            </a>
            <a href="#" class="login-button-phone"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Выход
            </a>
        @endguest
    </div>
</header>

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

            @auth
                @if (auth()->user()->role === 'admin')
                    <li><a href="{{ route('user.dashboard') }}">Админ-панель</a></li>
                @elseif (auth()->user()->role === 'teacher' || auth()->user()->role === 'student')
                    <li><a href="{{ route('user.dashboard') }}">Личный кабинет</a></li>
                @endif
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Выход
                    </a></li>
            @endauth
        </menu>

        <a href="{{ route('home') }}">
            <img src="{{ asset('images/logo.svg') }}" alt="">
        </a>

        <menu class="menu">
            <li><a href="{{ route('home') }}" class="link-button">Главная</a></li>
            <li><a href="{{ route('about') }}" class="link-button">О платформе</a></li>
            <li><a href="{{ route('career') }}" class="link-button">Поддержка</a></li>
            <li><a href="{{ route('contact') }}" class="link-button">Контакты</a></li>
        </menu>

        @guest
            <a href="{{ route('choiceLogin') }}" class="login-button">Войти в систему</a>
            <a href="{{ route('choiceLogin') }}" class="login-button-phone">Войти</a>
        @else
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

            <div class="auth-button">
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('user.dashboard') }}" class="login-button">Админ-панель</a>
                @elseif (auth()->user()->role === 'teacher' || auth()->user()->role === 'student')
                    <a href="{{ route('user.dashboard') }}" class="login-button">Личный кабинет</a>
                @endif

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form><a class="login-button" href="#"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Выход
                </a>


            </div>

        @endguest
    </div>
</header>

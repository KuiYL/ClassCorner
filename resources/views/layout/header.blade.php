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
            <img src="{{ 'images/logo.svg' }}" alt="">
        </a>

        <menu class="menu">
            <li><a href="{{ route('home') }}" class="link-button">Главная</a></li>
            <li><a href="{{ route('about') }}" class="link-button">О платформе</a></li>
            <li><a href="{{ route('career') }}" class="link-button">Поддержка</a></li>
            <li><a href="{{ route('contact') }}" class="link-button">Контакты</a></li>
        </menu>

        <a href="#" class="login-button">Войти в систему</a>
        <a href="#" class="login-button-phone">Войти</a>
    </div>
</header>

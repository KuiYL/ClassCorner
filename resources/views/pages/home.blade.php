<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Главная</title>
    <link rel="stylesheet" href="{{ 'css/style.css' }}">
    <link rel="stylesheet" href="{{ 'css/layout.css' }}">

    <script src="{{ 'js/script.js' }}" defer></script>
</head>

<body>
    @include('layout.header')

    <div class="banner">
        <div class="wrapper">
            <div class="first">
                <h1 class="head">Ваш путь <span class="banner-active">к знаниям</span> начинается здесь!</h1>
                <p class="info">Создайте уникальные классы для своих учеников уже сегодня с нашей образовательной
                    платформой! Наша
                    платформа помогает педагогам воплощать свои идеи в жизнь каждый день и двигаться за пределы
                    стандартного обучения.
                </p>
                <div class="buttons">
                    <a href="#" class="action-button">Попробовать бесплатно</a>

                    <a href="#" class="extra-button"> <img src="{{ 'images/arrow-right button.svg' }}"
                            alt=""></a>

                    <p>Узнайте больше</p>
                </div>
            </div>
            <div class="second">
                <img src="{{ 'images/home-banner.svg' }}" alt="">
            </div>
        </div>
    </div>

    <div class="about">

    </div>

    <div class="benefits">

    </div>

    <div class="faq">

    </div>

    <div class="subscribe">

    </div>
    @include('layout.footer')
</body>

</html>

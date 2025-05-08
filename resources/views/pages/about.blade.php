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
                <div class="">
                    <h2 class="extra-banner-text">О компании</h2>
                    <h1 class="head"><span class="banner-active">Классный Уголок</span></h1>
                </div>
                <p class="info">Мы создаем условия для легкого взаимодействия между учителями и учениками, помогая
                    преподавателям управлять занятиями, разрабатывать и отслеживать задания, а учащимся — эффективно
                    участвовать в процессе обучения.
                </p>
                <div class="buttons">
                    <a href="#" class="action-button">Карьера с нами</a>

                    <a href="#" class="extra-button"> <img src="{{ 'images/arrow-right button.svg' }}"
                            alt=""></a>

                    <p>Узнайте больше</p>
                </div>
            </div>
            <div class="second">
                <img src="{{ 'images/about-banner.svg' }}" alt="">
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

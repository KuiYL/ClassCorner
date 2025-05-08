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
                    <h2 class="extra-banner-text">Помогите нам поддержать учителей!</h2>
                    <h1 class="head"><span class="banner-active">Карьера с нами</span></h1>
                </div>
                <p class="info">Наши клиенты влияют на то, как мы работаем, и мы гордимся тем, что среди них есть
                    учителя и студенты. Если вы страстно увлечены развитием образования, присоединяйтесь к нам! Мы ценим
                    смелые идеи, ответственность, открытость и честность.
                </p>
                <div class="buttons">
                    <a href="#" class="action-button">Свяжитесь с нами</a>

                    <a href="#" class="extra-button"> <img src="{{ 'images/arrow-right button.svg' }}"
                            alt=""></a>

                    <p>Узнайте больше</p>
                </div>
            </div>
            <div class="second">
                <img src="{{ 'images/career-banner.svg' }}" alt="">
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

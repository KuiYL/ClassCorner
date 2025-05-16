<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Главная</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adaptation.css') }}">

    <script src="{{ asset('js/script.js') }}" defer></script>
    <meta name="close-icon" content="{{ asset('images/faq-close.svg') }}">
    <meta name="open-icon" content="{{ asset('images/faq-open.svg') }}">
    <link rel="icon" href="{{ asset('icon-logo.svg') }}" type="image/svg+xml">
</head>

<body>
    @include('layout.header')

    <div class="banner hidden">
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

    <div class="about hidden">
        <div class="wrapper">
            <div class="first">
                <img src="{{ 'images/image-about-block.svg' }}" alt="">
            </div>
            <div class="second">
                <div class="head-block">
                    <p>Кто мы такие</p>
                    <h2>Почему <span class="attention-title">Классный Уголок?</span></h2>
                </div>
                <div class="list-info">
                    <div class="info">
                        <img src="{{ 'images/medal-star.svg' }}" alt="">
                        <p>Мы предлагаем инновационные решения для онлайн-обучения, которые делают процесс удобным и
                            доступным
                            для всех.</p>
                    </div>
                    <div class="info">
                        <img src="{{ 'images/medal-star.svg' }}" alt="">
                        <p>Наша платформа разработана с учетом потребностей пользователей, что обеспечивает простоту
                            навигации и
                            взаимодействия.</p>
                    </div>
                    <div class="info">
                        <img src="{{ 'images/medal-star.svg' }}" alt="">
                        <p>Учителя могут легко отслеживать успехи учеников и предоставлять конструктивные комментарии.
                        </p>
                    </div>
                    <div class="info">
                        <img src="{{ 'images/medal-star.svg' }}" alt="">
                        <p>Мы придаем большое значение защите данных пользователей и соблюдению всех норм безопасности.
                        </p>
                    </div>
                </div>
                <a href="{{ route('about') }}" class="action-button">Больше о нас</a>
            </div>
        </div>
    </div>

    <div class="benefits hidden">
        <div class="wrapper">
            <div class="icon-boxes">
                <div class="icon-box">
                    <img src="{{ 'images/chart-benefits.svg' }}" alt="">
                    <div class="text">
                        <h3>Мониторинг успеваемости</h3>
                        <p>Учитель видит текущие результаты учеников и может корректировать обучение.</p>
                    </div>
                </div>
                <hr class="solid">
                <div class="icon-box">
                    <img src="{{ 'images/calendar-benefits.svg' }}" alt="">
                    <div class="text">
                        <h3>Автоматическое расписание сдачи работ</h3>
                        <p>Календарь с автоматическими уведомлениями о сроках сдачи работ.</p>
                    </div>
                </div>
                <hr class="solid">
                <div class="icon-box">
                    <img src="{{ 'images/document-code-benefits.svg' }}" alt="">
                    <div class="text">
                        <h3>Искусственный интеллект</h3>
                        <p>ИИ анализирует прогресс учеников и дает рекомендации для улучшения обучения.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="faq hidden">
        <div class="wrapper">
            <div class="head-block">
                <p>Ознакомьтесь с ответами на часто задаваемые учителями вопросы</p>
                <h2>Часто задаваемые <span class="attention-title">вопросы</span></h2>
            </div>

            <div class="faq-items">
                <div class="faq-item">
                    <div class="question">
                        <p>Как преподаватели проверяют задания?</p>
                        <img src="{{ asset('images/faq-close.svg') }}" alt="Закрыто">
                    </div>
                    <div class="answer">
                        Преподаватели просматривают отправленные задания в своей панели управления. После проверки они
                        оставляют оценку и комментарии, которые ученик может увидеть в разделе «История выполненных
                        заданий».
                    </div>
                </div>

                <div class="faq-item">
                    <div class="question">
                        <p>Можно ли просмотреть историю выполненных заданий?</p>
                        <img src="{{ asset('images/faq-close.svg') }}" alt="Закрыто">
                    </div>
                    <div class="answer">
                        Да, ученики могут просматривать историю выполненных заданий в специальном разделе на платформе,
                        где отображаются все отправленные задания с оценками и комментариями преподавателей.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="question">
                        <p>Какие функции доступны ученикам?</p>
                        <img src="{{ asset('images/faq-close.svg') }}" alt="Закрыто">
                    </div>
                    <div class="answer">
                        Ученики могут отправлять задания, просматривать результаты проверок, участвовать в обсуждениях,
                        получать уведомления о сроках сдачи и следить за своим прогрессом.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="question">
                        <p>Как присоединиться к курсу по коду?</p>
                        <img src="{{ asset('images/faq-close.svg') }}" alt="Закрыто">
                    </div>
                    <div class="answer">
                        Чтобы присоединиться к курсу, необходимо ввести предоставленный преподавателем уникальный код
                        курса в специальном разделе на платформе. После этого курс появится в вашем списке.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="subscribe hidden">
        <div class="wrapper">
            <div class="head-block">
                <p>ОСТАВАЙТЕСЬ В КУРСЕ СОБЫТИЙ</p>
                <h2>Получайте актуальные объявления первыми!</h2>
            </div>

            <form action="#" method="post" class="form-subscribe">
                <input class="custom-input" placeholder="Введите адрес вашей рабочей почты" type="text">
                <button type="submit" class="action-button">Подписаться</button>
            </form>
        </div>
    </div>

    @include('layout.footer')
</body>

</html>

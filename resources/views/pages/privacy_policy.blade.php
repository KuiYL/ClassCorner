<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Условия обслуживания</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adaptation.css') }}">

    <script src="{{ asset('js/script.js') }}" defer></script>
    <meta name="close-icon" content="{{ asset('images/faq-close.svg') }}">
    <meta name="open-icon" content="{{ asset('images/faq-open.svg') }}">
</head>

<body>
    @include('layout.header')

    <div class="law">
        <div class="wrapper">
            <div class="law-head hidden">
                <p class="law-title">Условия обслуживания</p>
                <p class="law-data">Последнее обновление: 30 апреля 2025 г.</p>
                <p class="law-description">Используя наш сайт или услуги, вы подтверждаете, что ознакомлены с настоящими
                    Условиями обслуживания и соглашаетесь с ними. Если вы не согласны с этими условиями, вам запрещено
                    использовать наши услуги.</p>
            </div>

            <div class="law-item hidden">
                <p class="law-subtitle">Принятие условий обслуживания</p>
                <ul class="law-list">
                    <li>Учебный Компаньон предоставляет услуги через веб-сайт и связанные технологии.</li>
                    <li>Использование Сервиса регулируется настоящими Условиями обслуживания.</li>
                    <li>Мы оставляем за собой право вносить изменения в Условия обслуживания. Актуальная версия всегда
                        доступна на сайте.</li>
                </ul>
            </div>

            <div class="law-item hidden">
                <p class="law-subtitle">Ваша конфиденциальность</p>
                <ul class="law-list">
                    <li>Мы уважаем конфиденциальность наших пользователей.</li>
                    <li>Политика конфиденциальности доступна по ссылке [ваша Политика конфиденциальности].</li>
                    <li>Используя Сервис, вы соглашаетесь с условиями сбора, использования и хранения данных.</li>
                </ul>
            </div>

            <div class="law-item hidden">
                <p class="law-subtitle">Доступ и использование Сервиса</p>
                <ul class="law-list">
                    <li>Для доступа к определённым функциям может потребоваться регистрация.</li>
                    <li>Вы обязаны предоставить актуальную и достоверную информацию при регистрации.</li>
                    <li>Ответственность за конфиденциальность пароля и учетной записи лежит на пользователе.</li>
                    <li>Мы оставляем за собой право изменять или прекращать предоставление услуг.</li>
                </ul>
            </div>

            <div class="law-item hidden">
                <p class="law-subtitle">Общие правила использования</p>
                <ul class="law-list">
                    <li>Запрещено использовать Сервис для незаконной деятельности или выдачи себя за других лиц.</li>
                    <li>Недопустимо вмешательство в работу Сервиса.</li>
                    <li>Сбор информации о несовершеннолетних запрещён.</li>
                    <li>Недопустимо использование Сервиса конкурентами без разрешения.</li>
                </ul>
            </div>

            <div class="law-item hidden">
                <p class="law-subtitle">Права интеллектуальной собственности</p>
                <ul class="law-list">
                    <li>Контент Сервиса защищён законами об авторских правах и товарных знаках.</li>
                    <li>Использование контента возможно только с разрешения Учебного Компаньона.</li>
                    <li>Товарные знаки компании принадлежат исключительно Учебному Компаньону.</li>
                </ul>
            </div>

            <div class="law-item hidden">
                <p class="law-subtitle">Программное обеспечение</p>
                <ul class="law-list">
                    <li>Все права на программное обеспечение, используемое в Сервисе, принадлежат Учебному Компаньону и
                        его аффилированным лицам.</li>
                    <li>Любое копирование, изменение или обратная разработка программного обеспечения запрещены.</li>
                    <li>Пользователи несут ответственность за соблюдение экспортных законов своей страны.</li>
                </ul>
            </div>

            <div class="law-item hidden">
                <p class="law-subtitle">Обратная связь</p>
                <p class="law-text">Если у вас есть вопросы или замечания, вы можете обратиться в службу поддержки по
                    электронной почте: support@uchkompanon.ru.</p>
            </div>
        </div>
    </div>

    @include('layout.footer')
</body>

</html>

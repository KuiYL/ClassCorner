<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>О безопасности</title>
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

    <div class="law">
        <div class="wrapper">
            <div class="law-head hidden">
                <p class="law-title">О безопасности</p>
                <p class="law-data">Последнее обновление: 29 сентября 2024 г.</p>
                <p class="law-description">В "Классном Уголке" безопасность и конфиденциальность наших пользователей
                    являются нашим приоритетом. Мы принимаем все необходимые меры для защиты вашей информации и
                    обеспечения безопасности использования нашего сервиса.</p>
            </div>

            <div class="law-item hidden">
                <p class="law-subtitle">Безопасность данных</p>
                <p class="law-text">Мы используем современные технологии шифрования для защиты данных, передаваемых
                    между вашим устройством и нашими серверами. Это позволяет предотвратить доступ несанкционированных
                    лиц к вашей личной информации.</p>
            </div>

            <div class="law-item hidden">
                <p class="law-subtitle">Защита информации</p>
                <p class="law-text">Все данные, которые вы предоставляете нам, хранятся на защищенных серверах с
                    ограниченным доступом. Мы регулярно проводим аудит и обновляем наши системы безопасности для
                    обеспечения надежной защиты.</p>
            </div>

            <div class="law-item hidden">
                <p class="law-subtitle">Доступ к данным</p>
                <p class="law-text">Только уполномоченные сотрудники "Классного Уголка" имеют доступ к вашей
                    информации, и мы строго контролируем процесс доступа, чтобы гарантировать, что ваша информация
                    используется только в законных целях и в соответствии с нашей Политикой конфиденциальности.</p>
            </div>

            <div class="law-item hidden">
                <p class="law-subtitle">Обучение сотрудников</p>
                <p class="law-text">Наши сотрудники проходят регулярное обучение по вопросам безопасности данных и
                    конфиденциальности, чтобы обеспечить соблюдение всех необходимых стандартов безопасности.</p>
            </div>

            <div class="law-item hidden">
                <p class="law-subtitle">Обратная связь</p>
                <p class="law-text">Если у вас есть вопросы или опасения по поводу безопасности вашего аккаунта или
                    информации, пожалуйста, свяжитесь с нашей службой поддержки по электронной почте:
                    support@klassugolok.ru.</p>
            </div>

        </div>
    </div>

    @include('layout.footer')
</body>

</html>

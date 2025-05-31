<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Политика конфиденциальности</title>
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
                <p class="law-title">Политика конфиденциальности</p>
                <p class="law-data">Последнее обновление: 29 сентября 2024 г.</p>
                <p class="law-description">В компании "Учебный Уголок" мы обязуемся защищать вашу конфиденциальность
                    и обеспечивать безопасность ваших персональных данных. Данная Политика конфиденциальности объясняет,
                    какие данные мы собираем, как мы их используем и защищаем, а также ваши права в отношении вашей
                    информации.</p>
            </div>

            <div class="law-item hidden">
                <p class="law-subtitle">Сбор данных</p>
                <p class="law-text">Мы собираем информацию, которую вы предоставляете нам, когда используете наши
                    услуги, включая, но не ограничиваясь:</p>
                <ul>
                    <li>- Имя</li>
                    <li>- Адрес электронной почты</li>
                    <li>- Номер телефона</li>
                    <li>- Любую другую информацию, которую вы решите предоставить</li>
                </ul>
            </div>

            <div class="law-item hidden">
                <p class="law-subtitle">Использование данных</p>
                <p class="law-text">Мы можем использовать собранную информацию для следующих целей:</p>
                <ul>
                    <li>- Для предоставления и поддержания наших услуг</li>
                    <li>- Для уведомления вас об изменениях в наших услугах</li>
                    <li>- Для предоставления клиентской поддержки</li>
                    <li>- Для сбора анализа или ценной информации, которая поможет нам улучшить наши услуги</li>
                    <li>- Для отслеживания использования наших услуг</li>
                    <li>- Для обнаружения, предотвращения и устранения технических проблем</li>
                </ul>
            </div>

            <div class="law-item hidden">
                <p class="law-subtitle">Передача данных</p>
                <p class="law-text">Ваша информация, включая персональные данные, может быть передана на компьютеры,
                    расположенные за пределами вашего штата, региона, страны или другой государственной юрисдикции, где
                    законы о защите данных могут отличаться от тех, что действуют в вашей юрисдикции.</p>
            </div>

            <div class="law-item hidden">
                <p class="law-subtitle">Раскрытие данных</p>
                <p class="law-text">Мы можем раскрывать вашу персональную информацию в следующих случаях:</p>
                <ul>
                    <li>- Если это требуется по закону</li>
                    <li>- Для защиты и отстаивания наших прав</li>
                    <li>- Для предотвращения или расследования возможных нарушений</li>
                    <li>- Для защиты личной безопасности пользователей наших услуг</li>
                </ul>
            </div>

            <div class="law-item hidden">
                <p class="law-subtitle">Ваши права</p>
                <p class="law-text">Вы имеете право на доступ к своим персональным данным и на их исправление. Вы также
                    можете потребовать удалить ваши данные в определенных ситуациях. Если вы хотите воспользоваться
                    этими правами, свяжитесь с нами.</p>
            </div>

            <div class="law-item hidden">
                <p class="law-subtitle">Изменения в Политике конфиденциальности</p>
                <p class="law-text">Мы можем периодически обновлять нашу Политику конфиденциальности. Мы уведомим вас о
                    любых изменениях, опубликовав новую Политику конфиденциальности на этой странице. Рекомендуем
                    периодически проверять эту страницу на наличие изменений.</p>
            </div>

            <div class="law-item hidden">
                <p class="law-subtitle">Контактная информация</p>
                <p class="law-text">Если у вас есть какие-либо вопросы по данной Политике конфиденциальности,
                    пожалуйста, свяжитесь с нами:</p>
                <ul>
                    <li>- Электронная почта: support@klassugolok.ru</li>
                </ul>
            </div>
        </div>
    </div>

    @include('layout.footer')
</body>

</html>

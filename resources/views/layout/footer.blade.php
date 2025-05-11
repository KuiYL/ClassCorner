<footer class="hidden">
    <div class="wrapper">
        <div class="column-left">
            <a href="{{ route('home') }}"><img src="{{ asset('images/logo-white ver.svg') }}" alt="Логотип компании"></a>
            <p class="info"> — это онлайн-обучение с инновационными решениями для автоматизации проверки домашних
                заданий. Наша цель — создать удобный инструмент для преподавателей и учеников, улучшающий
                образовательный процесс.</p>
            <div class="social">
                <a href="https://vk.com" aria-label="ВКонтакте">
                    <img src="{{ asset('images/vk-white ver..svg') }}" alt="ВКонтакте">
                </a>
                <a href="https://t.me" aria-label="Telegram">
                    <img src="{{ asset('images/telegram-white ver.svg') }}" alt="Telegram">
                </a>
            </div>
        </div>
        <div class="column-center">
            <nav class="column">
                <p class="title">О КОМПАНИИ</p>
                <div class="column-info">
                    <a href="{{ route('about') }}">О нас</a>
                    <a href="{{ route('career') }}">Карьера в компании</a>
                    <a href="{{ route('contact') }}">Контакты</a>
                </div>
            </nav>

            <nav class="column">
                <p class="title">СВЯЗЬ С НАМИ</p>
                <div class="column-info">
                    <a href="{{ route('service-conditions') }}">Правила использования</a>
                    <a href="{{ route('contact') }}">Служба поддержки</a>
                    <a href="{{ route('contact') }}">Оставить отзыв</a>
                </div>
            </nav>

            <nav class="column">
                <p class="title">ПРАВОВАЯ ИНФОРМАЦИЯ</p>
                <div class="column-info">
                    <a href="{{ route('service-conditions') }}">Пользовательское соглашение</a>
                    <a href="{{ route('privacy-policy') }}">Политика конфиденциальности</a>
                    <a href="{{ route('security') }}">Защита персональных данных</a>
                </div>
            </nav>
        </div>
    </div>
</footer>

<div class="extra--footer">
    <div class="wrapper">
        <div class="dev">
            © Ознобишина Дарья 2025. Все права защищены.
        </div>
        <a href="#top">ПЕРЕЙТИ ВВЕРХ</a>
    </div>
</div>

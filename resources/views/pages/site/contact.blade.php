<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Контакты</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adaptation.css') }}">

    <script src="{{ asset('js/script.js') }}" defer></script>
    <link rel="icon" href="{{ asset('icon-logo.svg') }}" type="image/svg+xml">
</head>

<body>
    @include('layout.header')

    <div class="contacts-form hidden">
        <div class="wrapper">
            <div class="first">
                <div class="head-block">
                    <p>Есть вопросы?</p>
                    <h2><span class="attention-title">Свяжитесь</span> с нами</h2>
                </div>

                <div class="contact-ways">
                    <div class="way">
                        <div class="icon-container">
                            <img src="{{ asset('images/chat.svg') }}" alt="Напишите нам">
                        </div>
                        <div class="text">
                            <p class="name-contact">Напишите нам:</p>
                            <p class="email">
                                <a href="mailto:hello@klassugolok.com">hello@klassugolok.com</a>
                            </p>
                        </div>
                    </div>
                    <div class="way">
                        <div class="icon-container">
                            <img src="{{ asset('images/phone.svg') }}" alt="Позвоните нам">
                        </div>
                        <div class="text">
                            <p class="name-contact">Позвоните нам:</p>
                            <p class="phone">
                                <a href="tel:+79997655544">8 (999) 765-55-44</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="second">
                <form id="contact-form" class="contact-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Имя <span>*</span></label>
                            <input type="text" id="name" placeholder="Ваше имя">
                        </div>
                        <div class="form-group">
                            <label for="surname">Фамилия <span>*</span></label>
                            <input type="text" id="surname" placeholder="Ваша фамилия" >
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Электронная почта <span>*</span></label>
                            <input type="email" id="email" placeholder="Ваш email" >
                        </div>
                        <div class="form-group">
                            <label for="phone">Номер телефона <span>*</span></label>
                            <input type="tel" id="phone" placeholder="Ваш номер телефона" >
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="message">Сообщение <span>*</span></label>
                            <textarea id="message" placeholder="Ваше сообщение"  rows="5"></textarea>
                        </div>
                    </div>
                    <div class="send-contact">
                        <button type="submit" class="action-button">Отправить сообщение</button>
                        <p>Нажимая кнопку, вы разрешаете <a href="{{ route('privacy-policy') }}">обработку
                                персональных данных</a> и
                            соглашаетесь с <a href="{{ route('service-conditions') }}">политикой
                                конфиденциальности</a>
                        </p>
                    </div>
                </form>
                <div id="contact-result" class="hidden text-success"></div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('contact-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const name = document.getElementById('name').value.trim();
            const surname = document.getElementById('surname').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const message = document.getElementById('message').value.trim();

            if (!name || !surname || !email || !phone || !message) {
                alert('Пожалуйста, заполните все обязательные поля.');
                return;
            }

            const resultDiv = document.getElementById('contact-result');
            resultDiv.textContent =
                `Спасибо за ваше сообщение, ${name} ${surname}! Мы свяжемся с вами по электронной почте ${email}.`;
            resultDiv.classList.remove('hidden');
            resultDiv.classList.add('text-success');

            document.getElementById('contact-form').reset();
        });
    </script>


    @include('layout.footer')
</body>

</html>

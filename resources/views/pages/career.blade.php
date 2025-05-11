<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Карьера</title>
    <link rel="stylesheet" href="{{ 'css/style.css' }}">
    <link rel="stylesheet" href="{{ 'css/layout.css' }}">
    <link rel="stylesheet" href="{{ 'css/components.css' }}">
    <link rel="stylesheet" href="{{ 'css/adaptation.css' }}">

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
                    <a href="{{ route('contact') }}" class="action-button">Свяжитесь с нами</a>

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

    <div class="benefits-company hidden">
        <div class="wrapper">
            <div class="head-block">
                <p>Вместе мы можем изменить образование</p>
                <h2>Почему стоит <span class="attention-title">присоединиться</span> к нашей команде?</h2>
            </div>

            <div class="items">
                <div class="item">
                    <div class="icon-container">
                        <img src="{{ asset('images/benefits1.svg') }}" alt="Обучение и развитие" class="icon">
                    </div>
                    <p class="item-name">Обучение и развитие</p>
                    <p class="item-text">Мы предоставляем возможности для обучения и профессионального роста</p>
                </div>
                <div class="item">
                    <div class="icon-container">
                        <img src="{{ asset('images/benefits2.svg') }}" alt="Командная работа" class="icon">
                    </div>
                    <p class="item-name">Командная работа</p>
                    <p class="item-text">У нас ценят сотрудничество и поддерживают ваши идеи и инициативы</p>
                </div>
                <div class="item">
                    <div class="icon-container">
                        <img src="{{ asset('images/benefits3.svg') }}" alt="Гибкость и удобство" class="icon">
                    </div>
                    <p class="item-name">Гибкость и удобство</p>
                    <p class="item-text">Мы предоставляем гибкие условия работы, чтобы совмещать личные дела и карьеру
                    </p>
                </div>
                <div class="item">
                    <div class="icon-container">
                        <img src="{{ asset('images/benefits4.svg') }}" alt="Стабильность и гарантии" class="icon">
                    </div>
                    <p class="item-name">Стабильность и гарантии</p>
                    <p class="item-text">Мы предоставляем стабильность и социальные гарантии</p>
                </div>
            </div>
        </div>
    </div>

    <div class="contacts-form">
        <div class="wrapper">
            <div class="first">
                <div class="head-block">
                    <p>Отправь резюме</p>
                    <h2><span class="attention-title">Станьте частью</span> нашей команды!</h2>
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

                <div class="contact-socials">
                    <p>Подпишись на нас:</p>
                    <div class="socials">
                        <a href="#"><img src="{{ asset('images/vk-contacts.svg') }}" alt="VK"></a>
                        <a href="#"><img src="{{ asset('images/telegram-contacts.svg') }}" alt="Telegram"></a>
                    </div>
                </div>
            </div>
            <div class="second">
                <div class="head-block">
                    <p>Если вы хотите стать частью команды, отправьте ваше резюме через форму ниже.</p>
                </div>

                <form action="" class="contact-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Имя <span>*</span></label>
                            <input type="text" id="name" placeholder="Ваше имя" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Электронная почта <span>*</span></label>
                            <input type="email" id="email" placeholder="Ваш email" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Номер телефона <span>*</span></label>
                            <input type="tel" id="phone" placeholder="Ваш номер телефона" required>
                        </div>
                        <div class="form-group">
                            <label for="resume-link">Ссылка на резюме</label>
                            <input type="url" id="resume-link" placeholder="Ссылка на резюме">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="file-upload">Загрузить резюме <span>*</span></label>
                            <div class="file-upload-wrapper" onclick="triggerFileInput()">
                                <img src="{{ asset('images/upload-icon.svg') }}" alt="Upload">
                                <span>Выберите файл</span>
                                <input type="file" id="file-upload" onchange="updateFileName(this)">
                            </div>
                            <p class="file-upload-text" id="file-upload-text">Файл не выбран</p>
                        </div>
                    </div>
                    <div class="send-contact">
                        <button type="submit" class="action-button">Отправить заявку</button>
                        <p>Нажимая кнопку, вы разрешаете <a href="#">обработку персональных данных</a> и
                            соглашаетесь с <a href="#">политикой конфиденциальности</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('layout.footer')
</body>

</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>О нас</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adaptation.css') }}">

    <script src="{{ asset('js/script.js') }}" defer></script>
    <meta name="indicator-active" content="{{ asset('images/indicator-active.svg') }}">
    <meta name="indicator" content="{{ asset('images/indicator-inactive.svg') }}">
    <link rel="icon" href="{{ asset('icon-logo.svg') }}" type="image/svg+xml">
</head>

<body>
    @include('layout.header')

    <div class="banner hidden">
        <div class="wrapper">
            <div class="first">
                <div class="">
                    <h2 class="extra-banner-text">О компании</h2>
                    <h1 class="head"><span class="banner-active">Учебный Уголок</span></h1>
                </div>
                <p class="info">Мы создаем условия для легкого взаимодействия между учителями и учениками, помогая
                    преподавателям управлять занятиями, разрабатывать и отслеживать задания, а учащимся — эффективно
                    участвовать в процессе обучения.
                </p>
                <div class="buttons">
                    <a href="{{ route('career') }}" class="action-button">Карьера с нами</a>

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

    <div class="history-company hidden">
        <div class="wrapper">
            <div class="head-block">
                <p>Как мы помогаем изменить образование к лучшему?</p>
                <h2><span class="attention-title">Что стоит за созданием</span> нашей платформы?</h2>
            </div>

            <div class="items">
                <div class="item">
                    <img src="{{ asset('images/1Number.svg') }}" alt="" class="number">
                    <p class="item-text">Наша команда вложила все свои знания и опыт в создание этой платформы, чтобы
                        сделать обучение удобным и эффективным.</p>
                </div>
                <div class="item">
                    <img src="{{ asset('images/2Number.svg') }}" alt="" class="number">
                    <p class="item-text">Мы тщательно продумали каждый элемент, чтобы платформа отвечала современным
                        требованиям образования.</p>
                </div>
                <div class="item">
                    <img src="{{ asset('images/3Number.svg') }}" alt="" class="number">
                    <p class="item-text">Платформа создана для того, чтобы соединить технологии и учебный процесс, делая
                        его
                        более доступным и понятным.</p>
                </div>
                <div class="item">
                    <img src="{{ asset('images/4Number.svg') }}" alt="" class="number">
                    <p class="item-text">Это решение открывает новые возможности для всех участников образовательного
                        процесса.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="quote hidden">
        <div class="wrapper">
            <p class="text">Образование меняется, и мы идем в ногу со временем! <span
                    class="attention-title">Присоединяйтесь к будущему знаний с
                    нами.</span></p>
        </div>
    </div>

    <div class="staff hidden">
        <div class="wrapper">
            <div class="head-block">
                <p>Познакомьтесь с нашей командой</p>
                <h2><span class="attention-title">Присоединяйся</span> к нашей команде</h2>
            </div>

            <div class="slider-container">
                <div class="slider">
                    <div class="slide">
                        <div class="image-container">
                            <img src="{{ asset('images/staff1.svg') }}" alt="">
                            <div class="overlay">
                                <p class="name">Анна Иванова</p>
                            </div>
                        </div>
                        <p class="position">Специалист по обучению и развитию</p>
                    </div>
                    <div class="slide">
                        <div class="image-container">
                            <img src="{{ asset('images/staff2.svg') }}" alt="">
                            <div class="overlay">
                                <p class="name">Мария Кузнецова</p>
                            </div>
                        </div>
                        <p class="position">Менеджер по клиентскому обслуживанию</p>
                    </div>
                    <div class="slide">
                        <div class="image-container">
                            <img src="{{ asset('images/staff3.svg') }}" alt="">
                            <div class="overlay">
                                <p class="name">Екатерина Смирнова</p>
                            </div>
                        </div>
                        <p class="position">Директор по развитию</p>
                    </div>
                    <div class="slide">
                        <div class="image-container">
                            <img src="{{ asset('images/staff4.svg') }}" alt="">
                            <div class="overlay">
                                <p class="name">Сергей Павлов</p>
                            </div>
                        </div>
                        <p class="position">Технический директор</p>
                    </div>
                    <div class="slide">
                        <div class="image-container">
                            <img src="{{ asset('images/staff5.svg') }}" alt="">
                            <div class="overlay">
                                <p class="name">Дмитрий Соколов</p>
                            </div>
                        </div>
                        <p class="position">Контент-менеджер</p>
                    </div>
                    <div class="slide">
                        <div class="image-container">
                            <img src="{{ asset('images/staff6.svg') }}" alt="">
                            <div class="overlay">
                                <p class="name">Александра Лебедева</p>
                            </div>
                        </div>
                        <p class="position">Разработчик программного обеспечения</p>
                    </div>
                    <div class="slide">
                        <div class="image-container">
                            <img src="{{ asset('images/staff7.svg') }}" alt="">
                            <div class="overlay">
                                <p class="name">Ольга Воробьёва</p>
                            </div>
                        </div>
                        <p class="position">Специалист по рекламе и PR</p>
                    </div>
                    <div class="slide">
                        <div class="image-container">
                            <img src="{{ asset('images/staff8.svg') }}" alt="">
                            <div class="overlay">
                                <p class="name">Иван Орлов</p>
                            </div>
                        </div>
                        <p class="position">Разработчик WEB</p>
                    </div>
                </div>
            </div>

            <div class="indicators">
                <img src="{{ asset('images/indicator-active.svg') }}" alt="Активный индикатор"
                    class="indicator active" data-slide="0">
                <img src="{{ asset('images/indicator-inactive.svg') }}" alt="Неактивный индикатор" class="indicator"
                    data-slide="1">
            </div>
            <a href="{{ route('career') }}" class="action-button">Присоединиться</a>
        </div>
    </div>

    <div class="subscribe hidden">
        <div class="wrapper">
            <div class="head-block">
                <p>ОСТАВАЙТЕСЬ В КУРСЕ СОБЫТИЙ</p>
                <h2>Получайте актуальные объявления первыми!</h2>
            </div>

            <form id="form-subscribe" class="form-subscribe">
                <input class="custom-input" placeholder="Введите адрес вашей рабочей почты" type="email">
                <button type="submit" class="action-button">Подписаться</button>
            </form>

            <div id="subscribe-result" class="hidden text-success"></div>
        </div>
    </div>

    @include('layout.footer')

    <script>
        document.getElementById('form-subscribe').addEventListener('submit', function(event) {
            event.preventDefault();

            const emailInput = event.target.querySelector('.custom-input').value;

            if (!emailInput || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput)) {
                alert('Пожалуйста, введите корректный адрес электронной почты.');
                return;
            }

            const resultDiv = document.getElementById('subscribe-result');
            resultDiv.textContent = 'Спасибо за подписку! Мы отправим вам новости на ' + emailInput;
            resultDiv.classList.remove('hidden');
            resultDiv.classList.add('text-success');
        });
    </script>
</body>

</html>

// Находим элементы
const hamburger = document.querySelector('.hamburger');
const burgerMenu = document.querySelector('.menu-burger');
const body = document.querySelector('body');

// Открытие/закрытие бургер-меню
hamburger.addEventListener('click', (e) => {
    hamburger.classList.toggle('open');
    burgerMenu.classList.toggle('active');
    body.classList.toggle('no-scroll'); // Блокируем прокрутку страницы
});

// Закрытие меню при клике вне области
document.addEventListener('click', (e) => {
    if (!burgerMenu.contains(e.target) && !hamburger.contains(e.target)) {
        hamburger.classList.remove('open');
        burgerMenu.classList.remove('active');
        body.classList.remove('no-scroll');
    }
});

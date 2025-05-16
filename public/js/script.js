// Бургер-меню
const hamburger = document.querySelector('.hamburger');
const burgerMenu = document.querySelector('.menu-burger');
const body = document.querySelector('body');

if (hamburger && burgerMenu && body) {
    hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('open');
        burgerMenu.classList.toggle('active');
        body.classList.toggle('no-scroll');
    });

    document.addEventListener('click', (e) => {
        if (!burgerMenu.contains(e.target) && !hamburger.contains(e.target)) {
            hamburger.classList.remove('open');
            burgerMenu.classList.remove('active');
            body.classList.remove('no-scroll');
        }
    });
}

// FAQ-элементы
document.addEventListener('DOMContentLoaded', () => {
    const faqItems = document.querySelectorAll('.faq-item');


    faqItems.forEach(item => {
        const question = item.querySelector('.question');
        const answer = item.querySelector('.answer');
        const icon = question?.querySelector('img');
        const closeIconPath = document.querySelector('meta[name="close-icon"]').getAttribute('content');
        const openIconPath = document.querySelector('meta[name="open-icon"]').getAttribute('content');
        if (question && answer && icon) {
            question.addEventListener('click', () => {
                const isOpen = item.classList.contains('faq-item-open');
                faqItems.forEach(i => {
                    i.classList.remove('faq-item-open');
                    const ans = i.querySelector('.answer');
                    const img = i.querySelector('.question img');
                    if (ans) ans.style.display = 'none';
                    if (img) img.src = closeIconPath;
                });

                if (!isOpen) {
                    item.classList.add('faq-item-open');
                    answer.style.display = 'block';
                    icon.src = openIconPath;
                }
            });
        }
    });
});

// Lazy load для элементов
document.addEventListener("DOMContentLoaded", () => {
    const elements = document.querySelectorAll(".hidden");

    if (elements.length > 0) {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("visible");
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.1 }
        );

        elements.forEach((el) => observer.observe(el));
    }
});

//Слайдер
document.addEventListener("DOMContentLoaded", () => {
    const slider = document.querySelector(".slider");
    const indicatorsContainer = document.querySelector(".indicators");
    const slides = document.querySelectorAll(".slide");

    let slidesToShow = 4;
    let currentIndex = 0;
    let autoSlideIndex = 0;
    let autoSlideInterval;

    function calculateSlidesToShow() {
        if (window.innerWidth >= 700) {
            slidesToShow = 4;
        } else {
            slidesToShow = 3;
        }
        updateSlideWidths();
    }

    function updateSlideWidths() {
        const slideWidth = 100 / slidesToShow;
        slides.forEach((slide) => {
            slide.style.flex = `0 0 ${slideWidth}%`;
        });
    }

    function updateIndicators() {
        const totalIndicators = window.innerWidth >= 700 ? 2 : 3;
        const indicatorInactive = document.querySelector('meta[name="indicator-active"]').getAttribute('content');
        const activeIndicator = document.querySelector('meta[name="indicator"]').getAttribute('content');

        indicatorsContainer.innerHTML = "";

        for (let i = 0; i < totalIndicators; i++) {
            const indicator = document.createElement("img");
            indicator.src = i === 0 ? activeIndicator : indicatorInactive;
            indicator.classList.add("indicator");
            if (i === 0) indicator.classList.add("active");
            indicator.dataset.slide = i;
            indicatorsContainer.appendChild(indicator);

            indicator.addEventListener("click", () => {
                currentIndex = i;
                autoSlideIndex = i * slidesToShow;
                updateSlider(currentIndex);
            });
        }
    }

    function updateSlider(index) {
        const slideWidth = slider.clientWidth / slidesToShow;
        slider.style.transform = `translateX(-${index * slideWidth * slidesToShow}px)`;

        const indicators = document.querySelectorAll(".indicator");
        const indicatorInactive = document.querySelector('meta[name="indicator-active"]').getAttribute('content');
        const activeIndicator = document.querySelector('meta[name="indicator"]').getAttribute('content');

        indicators.forEach((indicator, i) => {
            if (i === index) {
                indicator.src = activeIndicator;
                indicator.classList.add("active");
            } else {
                indicator.src = indicatorInactive;
                indicator.classList.remove("active");
            }
        });
    }

    function autoSlide() {
        const totalSlides = slides.length;
        const maxIndex = Math.ceil(totalSlides / slidesToShow) - 1;

        if (autoSlideIndex >= totalSlides - slidesToShow) {
            autoSlideIndex = 0;
            currentIndex = 0;
        } else {
            autoSlideIndex++;
            if (autoSlideIndex % slidesToShow === 0) {
                currentIndex = Math.floor(autoSlideIndex / slidesToShow) % (maxIndex + 1);
            }
        }
        updateSlider(currentIndex);
    }

    function initSlider() {
        calculateSlidesToShow();
        updateIndicators();
        updateSlider(0);
    }

    window.addEventListener("resize", initSlider);

    initSlider();
    autoSlideInterval = setInterval(autoSlide, 2300);

    indicatorsContainer.addEventListener("mouseover", () => clearInterval(autoSlideInterval));
    indicatorsContainer.addEventListener("mouseout", () => (autoSlideInterval = setInterval(autoSlide, 2300)));
});


// Загрузка файла
function triggerFileInput() {
    const fileInput = document.getElementById('file-upload');
    if (fileInput) fileInput.click();
}

function updateFileName(input) {
    const fileName = input?.files.length > 0 ? input.files[0].name : 'Файл не выбран';
    const fileNameElement = document.getElementById('file-upload-text');
    if (fileNameElement) fileNameElement.textContent = fileName;
}

// Переключение видимости пароля
document.addEventListener('DOMContentLoaded', function () {
    const togglePasswordButtons = document.querySelectorAll('.toggle-password');

    togglePasswordButtons.forEach(button => {
        const passwordInput = button.previousElementSibling;

        if (passwordInput) {
            button.addEventListener('click', function () {
                const isPasswordVisible = passwordInput.type === 'text';
                passwordInput.type = isPasswordVisible ? 'password' : 'text';

                button.querySelector('.icon-show')?.classList.toggle('hidden', !isPasswordVisible);
                button.querySelector('.icon-hide')?.classList.toggle('hidden', isPasswordVisible);
            });
        }
    });
});

// Выбор аватара
document.addEventListener("DOMContentLoaded", () => {
    const avatarOptions = document.querySelectorAll(".avatar-option");
    const hiddenInput = document.getElementById("selected-avatar");
    const uploadInput = document.getElementById("custom_avatar");
    const uploadPreview = document.getElementById("upload-preview");

    avatarOptions.forEach(avatar => {
        avatar.addEventListener("click", () => {
            avatarOptions.forEach(a => a.classList.remove("selected"));
            avatar.classList.add("selected");
            if (hiddenInput) hiddenInput.value = avatar.getAttribute("data-avatar-id");

            if (uploadPreview) uploadPreview.classList.remove("selected");
            if (uploadInput) uploadInput.value = "";
        });
    });

    if (uploadInput) {
        uploadInput.addEventListener("change", (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    if (uploadPreview) {
                        uploadPreview.src = e.target.result;
                        uploadPreview.classList.add("selected");
                    }
                    avatarOptions.forEach(a => a.classList.remove("selected"));
                    if (hiddenInput) hiddenInput.value = "";
                };
                reader.readAsDataURL(file);
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    // Анимация карточек классов
    const classCards = document.querySelectorAll('.class-card');
    classCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100 + index * 100);
    });

    // Эффект при нажатии на элементы
    const interactiveItems = document.querySelectorAll('.class-card, [href]');
    interactiveItems.forEach(item => {
        item.style.transition = 'all 0.2s ease';
        item.addEventListener('mousedown', () => {
            item.classList.add('animate-pop');
        });
        item.addEventListener('animationend', () => {
            item.classList.remove('animate-pop');
        });
    });

    // Анимация прогресс-баров
    const progressBars = document.querySelectorAll('.progress-ring__circle');
    progressBars.forEach(bar => {
        const radius = bar.r.baseVal.value;
        const circumference = radius * 2 * Math.PI;
        bar.style.strokeDasharray = `${circumference} ${circumference}`;
        bar.style.strokeDashoffset = circumference;

        const offset = circumference - (70 / 100) * circumference;
        setTimeout(() => {
            bar.style.transition = 'stroke-dashoffset 0.5s ease';
            bar.style.strokeDashoffset = offset;
        }, 300);
    });
});


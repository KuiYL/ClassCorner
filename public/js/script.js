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
    const closeIconPath = "path/to/close-icon.png";
    const openIconPath = "path/to/open-icon.png";

    faqItems.forEach(item => {
        const question = item.querySelector('.question');
        const answer = item.querySelector('.answer');
        const icon = question?.querySelector('img');

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

// Слайдер
document.addEventListener("DOMContentLoaded", () => {
    const slider = document.querySelector(".slider");
    const indicators = document.querySelectorAll(".indicator");
    const slides = document.querySelectorAll(".slide");
    const slidesToShow = 4;

    if (slider && indicators.length > 0 && slides.length > 0) {
        const totalSlides = slides.length;
        const maxIndex = Math.ceil(totalSlides / slidesToShow) - 1;
        let currentIndex = 0;
        let autoSlideIndex = 0;
        let autoSlideInterval;

        const indicatorInactive = "path/to/inactive-icon.png";
        const activeIndicator = "path/to/active-icon.png";

        function updateSlider(index) {
            const slideWidth = slider.clientWidth / slidesToShow;
            slider.style.transform = `translateX(-${index * slideWidth * slidesToShow}px)`;

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
            const slideWidth = slider.clientWidth / slidesToShow;

            if (autoSlideIndex >= totalSlides - slidesToShow) {
                autoSlideIndex = 0;
                currentIndex = 0;
                updateSlider(currentIndex);
            } else {
                autoSlideIndex++;
                if (autoSlideIndex % slidesToShow === 0) {
                    currentIndex = Math.floor(autoSlideIndex / slidesToShow) % indicators.length;
                    updateSlider(currentIndex);
                }
                slider.style.transform = `translateX(-${autoSlideIndex * slideWidth}px)`;
            }
        }

        indicators.forEach((indicator, index) => {
            indicator.addEventListener("click", () => {
                currentIndex = index;
                autoSlideIndex = index * slidesToShow;
                updateSlider(currentIndex);
            });
        });

        autoSlideInterval = setInterval(autoSlide, 2600);

        indicators.forEach((indicator) => {
            indicator.addEventListener("mouseover", () => clearInterval(autoSlideInterval));
            indicator.addEventListener("mouseout", () => (autoSlideInterval = setInterval(autoSlide, 2000)));
        });
    }
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

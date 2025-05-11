const hamburger = document.querySelector('.hamburger');
const burgerMenu = document.querySelector('.menu-burger');
const body = document.querySelector('body');

hamburger.addEventListener('click', (e) => {
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

document.addEventListener('DOMContentLoaded', () => {
    const faqItems = document.querySelectorAll('.faq-item');

    const closeIconPath = document.querySelector('meta[name="close-icon"]').getAttribute('content');
    const openIconPath = document.querySelector('meta[name="open-icon"]').getAttribute('content');

    faqItems.forEach(item => {
        const question = item.querySelector('.question');
        const answer = item.querySelector('.answer');
        const icon = question.querySelector('img');

        question.addEventListener('click', () => {
            const isOpen = item.classList.contains('faq-item-open');
            if (isOpen) {
                item.classList.remove('faq-item-open');
                answer.style.display = 'none';
                icon.src = closeIconPath;
            } else {
                faqItems.forEach(i => {
                    i.classList.remove('faq-item-open');
                    const ans = i.querySelector('.answer');
                    const img = i.querySelector('.question img');
                    ans.style.display = 'none';
                    img.src = closeIconPath;
                });

                item.classList.add('faq-item-open');
                answer.style.display = 'block';
                icon.src = openIconPath;
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const elements = document.querySelectorAll(".hidden");

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
});

document.addEventListener("DOMContentLoaded", () => {
    const slider = document.querySelector(".slider");
    const indicators = document.querySelectorAll(".indicator");
    const slides = document.querySelectorAll(".slide");
    const slidesToShow = 4;
    const totalSlides = slides.length;
    const maxIndex = Math.ceil(totalSlides / slidesToShow) - 1;
    let currentIndex = 0;
    let autoSlideIndex = 0;
    let autoSlideInterval;

    const indicatorInactive = document.querySelector('meta[name="indicator-active"]').getAttribute('content');
    const activeIndicator = document.querySelector('meta[name="indicator"]').getAttribute('content');


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
});

    function triggerFileInput() {
        document.getElementById('file-upload').click();
    }

    function updateFileName(input) {
        const fileName = input.files.length > 0 ? input.files[0].name : 'Файл не выбран';
        document.getElementById('file-upload-text').textContent = fileName;
    }

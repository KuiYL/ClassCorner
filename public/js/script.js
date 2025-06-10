// Бургер-меню
document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.querySelector('.hamburger');
    const burgerMenu = document.querySelector('.menu-burger');
    const body = document.body;

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
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const question = item.querySelector('.question');
        const answer = item.querySelector('.answer');
        const icon = question?.querySelector('img');
        const closeIconPath = document.querySelector('meta[name="close-icon"]')?.content;
        const openIconPath = document.querySelector('meta[name="open-icon"]')?.content;

        if (question && answer && icon && closeIconPath && openIconPath) {
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

    // Lazy load
    const elements = document.querySelectorAll('.hidden');
    if (elements.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        elements.forEach(el => observer.observe(el));
    }

    // Слайдер
    const slider = document.querySelector('.slider');
    const indicatorsContainer = document.querySelector('.indicators');
    const slides = document.querySelectorAll('.slide');

    let slidesToShow = 4;
    let currentIndex = 0;
    let autoSlideIndex = 0;
    let autoSlideInterval;

    const updateSlideWidths = () => {
        const slideWidth = 100 / slidesToShow;
        slides.forEach(slide => slide.style.flex = `0 0 ${slideWidth}%`);
    };

    const updateIndicators = () => {
        const indicatorActive = document.querySelector('meta[name="indicator-active"]')?.content || '';
        const indicatorInactive = document.querySelector('meta[name="indicator"]')?.content || '';
        if (!indicatorActive || !indicatorInactive) return;

        const totalIndicators = Math.ceil(slides.length / slidesToShow);
        indicatorsContainer.innerHTML = '';

        for (let i = 0; i < totalIndicators; i++) {
            const indicator = document.createElement('img');
            indicator.src = i === 0 ? indicatorActive : indicatorInactive;
            indicator.classList.add('indicator');
            if (i === 0) indicator.classList.add('active');
            indicator.dataset.slide = i;
            indicatorsContainer.appendChild(indicator);

            indicator.addEventListener('click', () => {
                currentIndex = i;
                autoSlideIndex = i * slidesToShow;
                updateSlider(currentIndex);
            });
        }
    };

    const updateSlider = (index) => {
        const slideWidth = slider.clientWidth / slidesToShow;
        slider.style.transform = `translateX(-${index * slideWidth}px)`;
        const indicators = indicatorsContainer.querySelectorAll('.indicator');
        const activeIndicator = document.querySelector('meta[name="indicator-active"]')?.content;
        const inactiveIndicator = document.querySelector('meta[name="indicator"]')?.content;

        if (activeIndicator && inactiveIndicator) {
            indicators.forEach((indicator, i) => {
                indicator.src = i === index ? activeIndicator : inactiveIndicator;
                indicator.classList.toggle('active', i === index);
            });
        }
    };

    const autoSlide = () => {
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
    };

    const initSlider = () => {
        slidesToShow = window.innerWidth >= 700 ? 4 : 3;
        updateSlideWidths();
        updateIndicators();
        updateSlider(0);
    };

    if (slider && indicatorsContainer && slides.length > 0) {
        window.addEventListener('resize', initSlider);
        initSlider();
        autoSlideInterval = setInterval(autoSlide, 2300);

        indicatorsContainer.addEventListener('mouseover', () => clearInterval(autoSlideInterval));
        indicatorsContainer.addEventListener('mouseout', () => (autoSlideInterval = setInterval(autoSlide, 2300)));
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

document.addEventListener('DOMContentLoaded', () => {
    // Анимация появления карточек
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

    // Анимация кнопки
    const createBtn = document.querySelector('.floating-btn');
    if (createBtn) {
        createBtn.style.opacity = '0';
        createBtn.style.transform = 'scale(0.5)';
        setTimeout(() => {
            createBtn.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            createBtn.style.opacity = '1';
            createBtn.style.transform = 'scale(1)';
        }, 500);
    }

    // Анимации для интерактивных элементов
    const interactiveItems = document.querySelectorAll('.class-card, .task-completed, .task-urgent, [href]');
    interactiveItems.forEach(item => {
        item.style.transition = 'all 0.2s ease';
        item.addEventListener('mousedown', () => {
            item.classList.add('animate-pop');
        });
        item.addEventListener('animationend', () => {
            item.classList.remove('animate-pop');
        });
    });



    // Логика для модального окна
    const modal = document.getElementById("modal");
    const modalTitle = document.getElementById("modalTitle");
    const modalText = document.getElementById("modalText");
    const deleteForm = document.getElementById("deleteForm");
    const cancelDeleteHeaderBtn = document.getElementById("cancelDeleteHeaderBtn");
    const cancelDeleteFormBtn = document.getElementById("cancelDeleteFormBtn");

    if (!modal || !modalTitle || !modalText || !deleteForm) return;

    function openModal(itemId, itemName, itemType) {
        let entityType = "";
        switch (itemType) {
            case "class":
                entityType = "класс";
                deleteForm.action = `/classes/${itemId}`;
                break;
            case "assignment":
                entityType = "задание";
                deleteForm.action = `/assignments/${itemId}`;
                break;
            case "user":
                entityType = "пользователь";
                deleteForm.action = `/users/${itemId}`;
                break;
            case "assignmentMaterial":
                entityType = "материал";
                deleteForm.action = `/assignment/material/${itemId}`;
                break;
            default:
                console.error("Неизвестный тип:", itemType);
                return;
        }

        modalTitle.textContent = `Удалить ${entityType}?`;
        modalText.textContent = `Вы уверены, что хотите удалить "${itemName}"?`;

        modal.style.display = "flex";
        setTimeout(() => {
            modal.classList.add("show");
        }, 10);
    }

    function closeModal() {
        modal.classList.remove("show");
        setTimeout(() => {
            modal.style.display = "none";
        }, 10);
        deleteForm.action = "";
    }

    document.body.addEventListener("click", function (e) {
        const btn = e.target.closest(".delete-button");
        if (btn) {
            const itemId = btn.dataset.id;
            const itemName = btn.dataset.name;
            const itemType = btn.dataset.type;
            openModal(itemId, itemName, itemType);
        }
    });

    cancelDeleteHeaderBtn.addEventListener("click", closeModal);
    cancelDeleteFormBtn.addEventListener("click", closeModal);

    modal.addEventListener("click", function (e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    const formInputs = document.querySelectorAll("input, textarea, select");

    formInputs.forEach(input => {
        if (input.classList.contains("border-red-500") || input.classList.contains("input-error")) {
            input.addEventListener("input", function () {
                if (this.value.trim() !== "") {
                    this.classList.remove("border-red-500", "input-error");

                    const errorSpan = this.closest('.relative')?.nextElementSibling;
                    if (errorSpan && errorSpan.classList.contains('text-red-500')) {
                        errorSpan.style.display = 'none';
                    }
                }
            });
        }
    });

    const formSelects = document.querySelectorAll("select");
    formSelects.forEach(select => {
        if (select.classList.contains("border-red-500") || select.classList.contains("input-error")) {
            select.addEventListener("change", function () {
                if (this.value) {
                    this.classList.remove("border-red-500", "input-error");

                    const errorSpan = this.closest('.relative')?.nextElementSibling;
                    if (errorSpan && errorSpan.classList.contains('text-red-500')) {
                        errorSpan.style.display = 'none';
                    }
                }
            });
        }
    });

    const toast = document.querySelector(".toast-notification");
    if (!toast) return;

    toast.classList.add("show");

    setTimeout(() => {
        toast.style.opacity = "0";
        toast.style.transform = "translateX(20px)";
        setTimeout(() => {
            toast.remove();
        }, 400);
    }, 3000);
});


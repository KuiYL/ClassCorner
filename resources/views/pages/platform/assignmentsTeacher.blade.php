<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ваши Задания</title>
    <link rel="stylesheet" href="{{ asset('css/style-platform.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/script.js') }}" defer></script>
</head>

<body>
    @include('layout.sidebar', ['activePage' => 'tasks'])

    <div class="topbar">
        @include('layout.topbar')

        <main>
            <div class="main-platform">
                <div class="banner-new-assignment">
                    <div class="wrapper">
                        <div>
                            <h3>Задания на проверку</h3>
                            <p>Проверьте ответы студентов и выставите оценки</p>
                        </div>
                        <a href="{{ route('assignments.to.grade') }}" class="btn primary small">
                            <i class="fas fa-arrow-right"></i>Перейти
                        </a>
                    </div>
                </div>

                <div class="assignments-container">
                    <div class="assignments-header">
                        <h3>Мои задания</h3>
                        <a href="{{ route('assignments.create', ['return_url' => url()->current()]) }}"
                            class="action-button" style="display: flex; gap:8px; background-color: #6e76c1">
                            <i class="fas fa-tasks"></i>
                            Новое задание
                        </a>
                    </div>
                    <div class="assignments-filters"
                        style="margin-bottom: 1rem; display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
                        <label>
                            Класс:
                            <select id="filter-class">
                                <option value="">Все</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->name }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label>
                            Тип вопроса:
                            <select id="filter-type">
                                <option value="">Все</option>
                                <option value="Загрузка файла">Загрузка файла</option>
                                <option value="Множественный выбор">Множественный выбор</option>
                                <option value="Один выбор">Один выбор</option>
                                <option value="Текстовый ответ">Текстовый ответ</option>
                            </select>
                        </label>
                    </div>

                    @if ($assignments->isEmpty())
                        <div class="assignments-empty">
                            У вас пока нет заданий. Нажмите "Новое задание", чтобы создать.
                        </div>
                    @else
                        <div class="assignments-list">
                            @foreach ($assignments as $assignment)
                                <div class="assignment-card">
                                    <div class="card-header">
                                        <h4 class="assignment-title">{{ $assignment->title }}</h4>
                                        <div class="header-controls">
                                            <button class="action-button">Подробнее</button>
                                        </div>
                                    </div>
                                    <div class="card-details hidden">
                                        <p class="assignment-description">{{ $assignment->description }}</p>
                                        <div class="assignment-details">
                                            <span>Класс: {{ $assignment->class->name ?? 'Без класса' }}</span>
                                            <span>Дедлайн: {{ $assignment->due_date }}</span>
                                            @php
                                                $typeTranslations = [
                                                    'file_upload' => 'Загрузка файла',
                                                    'multiple_choice' => 'Множественный выбор',
                                                    'single_choice' => 'Один выбор',
                                                    'text' => 'Текстовый ответ',
                                                ];

                                                $decodedOptions = json_decode($assignment->options, true);
                                                $questionTypes = [];

                                                if (!empty($decodedOptions)) {
                                                    foreach ($decodedOptions as $question) {
                                                        $questionTypes[] = $question['type'];
                                                    }
                                                    $questionTypes = array_unique($questionTypes);
                                                }
                                            @endphp
                                            <span>Типы вопросов:
                                                {{ !empty($questionTypes)
                                                    ? implode(
                                                        ', ',
                                                        array_map(fn($type) => $typeTranslations[$type] ?? ucfirst(str_replace('_', ' ', $type)), $questionTypes),
                                                    )
                                                    : 'Отсутствуют' }}
                                            </span>
                                        </div>
                                        <div class="card-actions">
                                            <a href="{{ route('assignments.edit', ['id' => $assignment->id, 'class_id' => $assignment->class_id, 'return_url' => url()->current()]) }}"
                                                class="btn edit-btn">Изменить</a>
                                            <button class="btn delete-btn delete-button" type="button"
                                                data-id="{{ $assignment->id }}" data-name="{{ $assignment->title }}"
                                                data-type="assignment">
                                                Удалить
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="no-assignments-message hidden">
                            <p>Нет заданий, соответствующих выбранным фильтрам.</p>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <a href="{{ route('assignments.create', ['return_url' => url()->current()]) }}" class="floating-btn">
        <button>
            <i class="fas fa-plus"></i>
        </button>
    </a>
    @include('layout.modal-delete')

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cards = document.querySelectorAll(".assignment-card");

            cards.forEach((card) => {
                const toggleBtn = card.querySelector(".action-button");
                const cardDetails = card.querySelector(".card-details");

                toggleBtn.addEventListener("click", () => {
                    const isHidden = cardDetails.classList.toggle("hidden");
                    toggleBtn.textContent = isHidden ? "Подробнее" : "Скрыть";
                    cardDetails.style.display = isHidden ? "none" : "block";
                });
            });

            const filterClass = document.getElementById("filter-class");
            const filterType = document.getElementById("filter-type");

            function filterAssignments() {
                const classVal = filterClass.value.trim();
                const typeVal = filterType.value.trim();

                let hasVisibleCards = false;

                cards.forEach(card => {
                    const cardClass = card.querySelector(".assignment-details span:first-child")
                        ?.textContent.replace('Класс: ', '').trim() || "";
                    const cardTypesText = card.querySelector(".assignment-details span:nth-child(3)")
                        ?.textContent.replace('Типы вопросов:', '').trim() || "";

                    const classMatch = !classVal || cardClass === classVal;
                    const typeMatch = !typeVal || cardTypesText.includes(typeVal);

                    if (classMatch && typeMatch) {
                        card.style.display = "block";
                        hasVisibleCards = true;
                    } else {
                        card.style.display = "none";
                    }
                });

                const noAssignmentsMessage = document.querySelector(".no-assignments-message");
                if (hasVisibleCards) {
                    noAssignmentsMessage.classList.add("hidden");
                } else {
                    noAssignmentsMessage.classList.remove("hidden");
                }
            }


            filterClass.addEventListener("change", filterAssignments);
            filterType.addEventListener("change", filterAssignments);

            filterAssignments();
        });
    </script>

</body>

</html>

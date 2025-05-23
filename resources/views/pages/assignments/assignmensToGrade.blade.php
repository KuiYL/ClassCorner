<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задания на проверку</title>
    <link rel="stylesheet" href="{{ asset('css/style-platform.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css ">
    <script src="{{ asset('js/script.js') }}" defer></script>
</head>

<body>
    @include('layout.sidebar', ['activePage' => 'assignments'])

    <div class="topbar">
        @include('layout.topbar')

        <main>
            <div class="main-platform">
                <div class="banner-new-assignment">
                    <div class="wrapper">
                        <div>
                            <h3>Задания на проверку</h3>
                            <p>Выберите задание для выставления оценок</p>
                        </div>
                        <a href="{{ route('user.assignments') }}">
                            <i class="fas fa-arrow-left"></i>
                            Вернуться
                        </a>
                    </div>
                </div>

                <div class="assignments-container">
                    <div class="assignments-header">
                        <h3>Список заданий на проверку</h3>
                    </div>

                    <div class="assignments-filters"
                        style="margin-bottom: 1rem; display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
                        <label>
                            Фильтр по статусу:
                            <select id="filter-status">
                                <option value="">Все</option>
                                <option value="Срочно">Срочно</option>
                                <option value="Проверено">Проверено</option>
                            </select>
                        </label>

                        <label>
                            Класс:
                            <select id="filter-class">
                                <option value="">Все</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->name }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>

                    @if ($assignmentsToGrade->isEmpty())
                        <div class="assignments-empty">
                            У вас нет заданий на проверку.
                        </div>
                    @else
                        <div class="assignments-list">
                            @foreach ($assignmentsToGrade as $assignment)
                                <div class="assignment-card status-{{ $assignment->status }}">
                                    <div class="card-header">
                                        <h4 class="assignment-title">{{ $assignment->assignment->title }}</h4>
                                        <div class="header-controls">
                                            <!-- Статус -->
                                            <span class="badge status-badge status-{{ $assignment->status }}">
                                                {{ $assignment->status === 'submitted' ? 'Срочно' : 'Проверено' }}
                                            </span>
                                            <!-- Кнопка "Проверить" -->
                                            <a href="{{ route('assignment.grade.form', $assignment->id) }}"
                                                class="btn check-btn">Проверить</a>
                                        </div>
                                    </div>

                                    <div class="card-details hidden">
                                        <p class="assignment-description">
                                            {{ Str::limit($assignment->assignment->description, 100) }}
                                        </p>
                                        <div class="assignment-details">
                                            <span>Класс:
                                                {{ optional($assignment->assignment->class)->name ?? 'Не указан' }}</span>
                                            <span>Работ: {{ $assignment->students_count }}</span>
                                            @php
                                                $typeTranslations = [
                                                    'file_upload' => 'Загрузка файла',
                                                    'multiple_choice' => 'Множественный выбор',
                                                    'single_choice' => 'Один выбор',
                                                    'text' => 'Текстовый ответ',
                                                ];

                                                $decodedOptions = json_decode($assignment->assignment->options, true);
                                                $questionTypes = [];

                                                if (!empty($decodedOptions)) {
                                                    foreach ($decodedOptions as $question) {
                                                        $questionTypes[] = $question['type'];
                                                    }
                                                    $questionTypes = array_unique($questionTypes);
                                                }
                                            @endphp
                                            <span>
                                                Типы вопросов:
                                                {{ !empty($questionTypes)
                                                    ? implode(
                                                        ', ',
                                                        array_map(fn($type) => $typeTranslations[$type] ?? ucfirst(str_replace('_', ' ', $type)), $questionTypes),
                                                    )
                                                    : 'Отсутствуют' }}
                                            </span>
                                        </div>
                                        <div class="card-actions">
                                            <small>Срок проверки: до
                                                {{ \Carbon\Carbon::parse($assignment->assignment->due_date)->format('d.m.Y') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div id="no-assignments-message" class="no-assignments-message hidden">
                            <p>Нет заданий, соответствующих выбранным фильтрам.</p>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cards = document.querySelectorAll(".assignment-card");
            const filterStatus = document.getElementById("filter-status");
            const filterClass = document.getElementById("filter-class");
            const noAssignmentsMessage = document.querySelector(".no-assignments-message");

            document.querySelectorAll(".action-button").forEach(btn => {
                btn.addEventListener("click", function() {
                    const details = this.closest('.assignment-card').querySelector('.card-details');
                    details.classList.toggle("hidden");
                    this.textContent = details.classList.contains("hidden") ? "Подробнее" :
                        "Скрыть";
                });
            });

            function applyFilters() {
                const selectedStatus = filterStatus.value.trim();
                const selectedClass = filterClass.value.trim();

                let hasVisibleCards = false;

                cards.forEach(card => {
                    const cardStatusText = card.querySelector(".status-badge")?.textContent.trim() || "";
                    const cardClassText = card.querySelector(".assignment-details span:first-child")
                        ?.textContent.replace("Класс: ", "").trim() || "";

                    const matchesStatus = !selectedStatus || cardStatusText === selectedStatus;
                    const matchesClass = !selectedClass || cardClassText === selectedClass;

                    if (matchesStatus && matchesClass) {
                        card.style.display = "flex";
                        hasVisibleCards = true;
                    } else {
                        card.style.display = "none";
                    }
                });

                noAssignmentsMessage.classList.toggle("hidden", hasVisibleCards);
            }

            filterStatus.addEventListener("change", applyFilters);
            filterClass.addEventListener("change", applyFilters);

            applyFilters();
        });
    </script>
</body>

</html>

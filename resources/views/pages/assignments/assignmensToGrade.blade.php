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
                @if (session('success'))
                    <div class="alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert-error">{{ session('error') }}</div>
                @endif
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
                                <option value="На проверке">На проверку</option>
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
                                @php
                                    $status = $assignment['submission']
                                        ? $assignment['submission']->status
                                        : 'not_submitted';
                                    $statusLabels = [
                                        'not_submitted' => 'Не выполнено',
                                        'submitted' => 'На проверке',
                                        'graded' => 'Выполнено',
                                    ];
                                    $badgeColor = '';
                                    switch ($status) {
                                        case 'not_submitted':
                                            $badgeColor = 'red';
                                            break;
                                        case 'submitted':
                                            $badgeColor = 'yellow';
                                            break;
                                        case 'graded':
                                            $badgeColor = 'green';
                                            break;
                                        default:
                                            $badgeColor = 'gray';
                                    }
                                @endphp
                                <div class="assignment-card"
                                    data-class="{{ optional($assignment->assignment->class)->name }}">
                                    <div class="card-header">
                                        <h4 class="assignment-title">{{ $assignment->assignment->title }}</h4>
                                        <div class="header-controls">
                                            <span class="badge status-badge status-{{ $assignment->status }}">
                                                {{ $assignment->status === 'submitted' ? 'На проверке' : 'Проверено' }}
                                            </span>
                                            <button type="button" class="action-button"
                                                style="display: flex; gap:6px;">
                                                <i class="fas fa-eye"></i> Подробнее
                                            </button>
                                            @if ($assignment->status === 'submitted')
                                                <a href="{{ route('assignment.grade.form', $assignment->assignment->id) }}"
                                                    class="action-button">
                                                    Проверить
                                                </a>
                                            @else
                                                <a href="{{ route('assignment.result', ['id' => $assignment->id]) }}"
                                                    class="action-button">
                                                    Посмотреть
                                                </a>
                                            @endif
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
                                                {{ \Carbon\Carbon::parse($assignment->assignment->due_date)->format('d.m.Y') }}</small>
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
    <style>
        :root {
            --status-red: #e74c3c;
            --status-yellow: #eb9800;
            --status-green: #2ecc71;
            --status-gray: #95a5a6;
        }

        .badge.status-badge {
            display: inline-block;
            padding: 0.3rem 0.6rem;
            border-radius: 6px;
            color: white;
            font-weight: bold;
            text-align: center;
            font-size: 0.85rem;
        }

        .status-badge.status-not_submitted {
            background-color: var(--status-red);
        }

        .status-badge.status-submitted {
            background-color: var(--status-yellow);
        }

        .status-badge.status-graded {
            background-color: var(--status-green);
        }

        .hidden {
            display: none;
        }
    </style>

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
            const filterStatus = document.getElementById("filter-status");

            function filterAssignments() {
                const classVal = filterClass.value.trim();
                const statusVal = filterStatus.value.trim();

                let hasVisibleCards = false;

                cards.forEach((card) => {
                    const cardClass = card.getAttribute("data-class") || "";
                    const cardStatus = card.querySelector(".status-badge")?.textContent.trim() || "";

                    const classMatch = !classVal || cardClass === classVal;
                    const statusMatch = !statusVal || cardStatus === statusVal;

                    if (classMatch && statusMatch) {
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
            filterStatus.addEventListener("change", filterAssignments);

            filterAssignments();
        });
    </script>
</body>

</html>

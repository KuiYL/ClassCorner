@extends('pages.platform.layout', ['activePage' => 'classes', 'title' => $class->name, 'quick_action' => 'null'])
@section('content')
    <div class="main-platform">
        <div class="banner">
            <div class="greeting">
                <h2>Добро пожаловать в класс, {{ $class->name }}!</h2>
                <p>{{ $class->description }}</p>
                <p>Преподаватель: {{ $class->teacher->name }} {{ $class->teacher->surname }}</p>
            </div>
            <div class="items">
                <div class="item">
                    <p class="text">Задания</p>
                    <p class="text-count">{{ count($assignments) }}</p>
                </div>
                <div class="item">
                    <p class="text">Выполненные</p>
                    <p class="text-count">{{ $completedAssignments }}</p>
                </div>
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
                <h3>Мои задания</h3>
            </div>

            <div class="assignments-filters"
                style="margin-bottom: 1rem; display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
                <label>
                    Статус:
                    <select id="filter-status">
                        <option value="">Все</option>
                        <option value="not_submitted">Не выполнено</option>
                        <option value="submitted">На проверке</option>
                        <option value="graded">Выполнено</option>
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

            <div class="no-assignments-message hidden">
                <p>Нет заданий, соответствующих выбранным фильтрам.</p>
            </div>

            @if (!empty($assignments))
                <div class="assignments-list" id="assignments-list">
                    @foreach ($assignments as $item)
                        @php
                            $status = $item['submission'] ? $item['submission']->status : 'not_submitted';
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

                        <div class="assignment-card status-{{ $status }}" data-status="{{ $status }}">
                            <div class="card-header">
                                <h4 class="assignment-title">{{ $item['assignment']->title }}</h4>
                                <div class="header-controls">
                                    <span class="badge status-badge status-{{ $status }}"
                                        style="background-color: var(--status-{{ $badgeColor }});">
                                        {{ $statusLabels[$status] ?? 'Неизвестный статус' }}
                                    </span>
                                    <button class="action-button toggle-details-btn">Подробнее</button>
                                </div>
                            </div>
                            <div class="card-details hidden">
                                <p class="assignment-description">
                                    {{ Str::limit($item['assignment']->description, 100) }}
                                </p>
                                <div class="assignment-details">
                                    <span>
                                        <i class="fas fa-clock"></i>
                                        Дедлайн:
                                        {{ \Carbon\Carbon::parse($item['assignment']->due_date)->format('d.m.Y') }}
                                    </span>
                                    @php
                                        $typeTranslations = [
                                            'file_upload' => 'Загрузка файла',
                                            'multiple_choice' => 'Множественный выбор',
                                            'single_choice' => 'Один выбор',
                                            'text' => 'Текстовый ответ',
                                        ];
                                        $decodedOptions = json_decode($item['assignment']->options, true);
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
                                    @if ($status !== 'submitted')
                                        @if ($status === 'graded' && !is_null($item['submission']->grade))
                                            <a href="{{ route('assignment.result', ['id' => $item['submission']->id]) }}"
                                                class="btn view-btn" style="display: flex; gap: 5px;">
                                                <i class="fas fa-eye"></i> Результаты
                                            </a>
                                        @else
                                            <a href="{{ route('assignments.show', $item['assignment']->id) }}"
                                                class="btn view-btn" style="display: flex; gap: 5px;">
                                                <i class="fas fa-folder-open"></i> Перейти
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="assignments-empty">
                    <p>В этом классе пока нет заданий.</p>
                </div>
            @endif
        </div>
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
            const filterStatus = document.getElementById("filter-status");
            const filterType = document.getElementById("filter-type");
            const noAssignmentsMessage = document.querySelector(".no-assignments-message");

            document.querySelectorAll(".toggle-details-btn").forEach(btn => {
                btn.addEventListener("click", function() {
                    const details = this.closest('.assignment-card').querySelector('.card-details');
                    details.classList.toggle("hidden");
                    this.textContent = details.classList.contains("hidden") ? "Подробнее" :
                        "Скрыть";
                });
            });

            function applyFilters() {
                const selectedStatus = filterStatus.value.trim();
                const selectedType = filterType.value.trim();

                let hasVisibleCards = false;

                cards.forEach(card => {
                    const cardStatus = card.dataset.status || "";
                    const cardTypeText = card.querySelector(".assignment-details span:nth-child(2)")
                        ?.textContent.replace("Типы вопросов:", "").trim() || "";

                    const typesArray = cardTypeText.split(',').map(t => t.trim());

                    const matchesStatus = !selectedStatus || cardStatus === selectedStatus;
                    const matchesType = !selectedType || typesArray.includes(selectedType);

                    if (matchesStatus && matchesType) {
                        card.style.display = "block";
                        hasVisibleCards = true;
                    } else {
                        card.style.display = "none";
                    }
                });

                noAssignmentsMessage.classList.toggle("hidden", hasVisibleCards);
            }

            filterStatus.addEventListener("change", applyFilters);
            filterType.addEventListener("change", applyFilters);

            applyFilters();
        });
    </script>
@endsection

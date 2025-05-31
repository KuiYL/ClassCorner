@extends('pages.platform.layout', ['activePage' => 'tasks', 'title' => 'Список заданий', 'quick_action' => 'null'])
@section('content')
    <div class="main-platform">

        <div class="banner-new-assignment">
            <div class="wrapper">
                <div>
                    <h3>Ваши задания</h3>
                    <p>Выполняйте задания по вашим классам</p>
                </div>
                <a href="{{ route('user.classes') }}">
                    <i class="fas fa-arrow-right"></i>
                    Перейти к классам
                </a>
            </div>
        </div>

        <div class="assignments-container">
            <div class="assignments-header">
                <h3>Задания на выполнение</h3>
            </div>

            <div class="assignments-filters"
                style="margin-bottom: 1rem; display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
                <label>
                    Статус:
                    <select id="filter-status">
                        <option value="">Все</option>
                        <option value="Не выполнено">Не выполнено</option>
                        <option value="На проверке">На проверке</option>
                        <option value="Выполнено">Выполнено</option>
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
                    Нет доступных заданий.
                </div>
            @else
                <div class="assignments-list">
                    @foreach ($assignments as $item)
                        <div class="assignment-card">
                            @php
                                $status = 'not_submitted'; // Статус по умолчанию
                                if (!empty($item['submission'])) {
                                    $status = $item['submission']->status;
                                }

                                $statusLabels = [
                                    'not_submitted' => 'Не выполнено',
                                    'submitted' => 'На проверке',
                                    'graded' => 'Выполнено',
                                ];
                            @endphp

                            <div class="card-header">
                                <h4 class="assignment-title">{{ $item['assignment']->title }}</h4>
                                <div class="header-controls">
                                    <span
                                        class="assignment-status {{ $status === 'not_submitted' ? 'not-submitted' : ($status === 'submitted' ? 'submitted' : 'completed') }}">
                                        {{ $statusLabels[$status] ?? 'Неизвестный статус' }}
                                    </span>
                                    <button class="action-button">Подробнее</button>
                                </div>
                            </div>
                            <div class="card-details hidden">
                                <p class="assignment-description">{{ $item['assignment']->description }}</p>
                                <div class="assignment-details">
                                    <span>Класс: {{ $item['class']->name }}</span>
                                    <span>Дедлайн: {{ $item['assignment']->due_date }}</span>
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

                <div class="no-assignments-message hidden">
                    <p>Нет заданий, соответствующих выбранным фильтрам.</p>
                </div>
            @endif
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cards = document.querySelectorAll(".assignment-card");

            // Логика разворачивания деталей
            cards.forEach((card) => {
                const toggleBtn = card.querySelector(".action-button");
                const cardDetails = card.querySelector(".card-details");

                toggleBtn.addEventListener("click", () => {
                    const isHidden = cardDetails.classList.toggle("hidden");
                    toggleBtn.textContent = isHidden ? "Подробнее" : "Скрыть";
                    cardDetails.style.display = isHidden ? "none" : "block";
                });
            });

            // Логика фильтрации
            const filterStatus = document.getElementById("filter-status");
            const filterClass = document.getElementById("filter-class");
            const filterType = document.getElementById("filter-type");
            const noAssignmentsMessage = document.querySelector(".no-assignments-message");

            function filterAssignments() {
                const statusVal = filterStatus.value.trim();
                const classVal = filterClass.value.trim();
                const typeVal = filterType.value.trim();
                let hasVisibleCards = false;

                cards.forEach(card => {
                    const cardStatus = card.querySelector(".assignment-status")?.textContent.trim() || "";
                    const cardClass = card.querySelector(".assignment-details span:first-child")
                        ?.textContent.replace('Класс: ', '').trim() || "";
                    const cardTypesText = card.querySelector(".assignment-details span:nth-child(3)")
                        ?.textContent.replace('Типы вопросов:', '').trim() || "";

                    const statusMatch = !statusVal || cardStatus === statusVal;
                    const classMatch = !classVal || cardClass === classVal;
                    const typeMatch = !typeVal || cardTypesText.includes(typeVal);

                    if (statusMatch && classMatch && typeMatch) {
                        card.style.display = "block";
                        hasVisibleCards = true;
                    } else {
                        card.style.display = "none";
                    }
                });

                noAssignmentsMessage.classList.toggle("hidden", hasVisibleCards);
            }

            filterStatus.addEventListener("change", filterAssignments);
            filterClass.addEventListener("change", filterAssignments);
            filterType.addEventListener("change", filterAssignments);

            filterAssignments();
        });
    </script>
@endsection

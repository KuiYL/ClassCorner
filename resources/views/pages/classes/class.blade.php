<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $class->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/style-platform.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/script.js') }}" defer></script>
</head>

<body>
    @include('layout.sidebar', ['activePage' => 'classes'])
    <div class="topbar">
        @include('layout.topbar')
        <main>
            <div class="main-platform">
                <div class="banner">
                    <div class="greeting">
                        <h2>Добро пожаловать в класс, {{ $class->name }}!</h2>
                        <p>{{ $class->description }}</p>
                        <p>Преподаватель: {{ $class->teacher->name }} {{ $class->teacher->surname }}</p>
                    </div>
                    <div class="items">
                        <div class="item">
                            <p class="text">Обучающихся</p>
                            <p class="text-count">{{ $students->count() - 1 }}</p>
                        </div>
                        <div class="item">
                            <p class="text">Задания</p>
                            <p class="text-count">{{ $assignments->count() }}</p>
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
                        <a class="new-assignment-btn"
                            href="{{ route('assignments.create', ['classId' => $class->id, 'return_url' => url()->current()]) }}">Добавить
                            задание</a>
                    </div>
                    <div class="assignments-filters"
                        style="margin-bottom: 1rem; display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">

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
                                        <p class="assignment-description">
                                            {{ Str::limit($assignment->description, 100) }}</p>
                                        <div class="assignment-details">
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
                                            <div class="card-actions">
                                                <a href="{{ route('assignments.show', $assignment->id) }}"
                                                    class="btn view-btn">Перейти</a>
                                                <a href="{{ route('assignments.edit', ['id' => $assignment->id, 'class_id' => $assignment->class_id, 'return_url' => url()->current()]) }}"
                                                    class="btn edit-btn">Изменить</a>
                                                <button class="btn delete-btn delete-button" type="button"
                                                    data-id="{{ $assignment->id }}"
                                                    data-name="{{ $assignment->title }}" data-type="assignment">
                                                    Удалить
                                                </button>
                                            </div>
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

                <div class="table">
                    <div class="head">
                        <h3>Студенты</h3>
                        <button class="btn invite-student-btn">Пригласить ученика</button>
                    </div>

                    @if ($studentProgress->isNotEmpty())
                        <table class="progress-table">
                            <thead>
                                <tr>
                                    <th>Имя</th>
                                    <th>Выполнено</th>
                                    <th>Средний балл</th>
                                    <th>Прогресс</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($studentProgress as $sp)
                                    @if ($sp['student']->role == 'student')
                                        <tr>
                                            <td>{{ $sp['student']->name }} {{ $sp['student']->surname }}</td>
                                            <td>{{ $sp['completed'] }} из {{ $sp['total'] }}</td>
                                            <td>{{ $sp['average_grade'] ?? '-' }}</td>
                                            <td>
                                                <div class="progress-bar-container">
                                                    <div class="progress-bar">
                                                        {{ $sp['percent'] }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Нет студентов в этом классе.</p>
                    @endif
                </div>

                <div id="invite-student-modal" class="modal-invite hidden">
                    <div class="modal-invite-content">
                        <span class="close-btn" id="close-modal">&times;</span>
                        <h3>Пригласить ученика</h3>
                        <input type="text" id="search-student" placeholder="Введите имя или email">
                        <ul id="search-results" class="search-results"></ul>
                        <form id="invite-student-form" action="{{ route('classes.invite', $class->id) }}"
                            method="POST">
                            @csrf
                            <input type="hidden" id="invite-student-email" name="email">
                            <button type="submit" class="btn primary">Добавить выбранного</button>
                        </form>
                        <button id="close-modal-bottom" class="btn secondary">Отмена</button>
                    </div>
                </div>
        </main>
    </div>
    <a href="{{ route('assignments.create', ['classId' => $class->id, 'return_url' => url()->current()]) }}"
        class="floating-btn">
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


            const filterType = document.getElementById("filter-type");

            function filterAssignments() {
                const typeVal = filterType.value.trim();

                let hasVisibleCards = false;

                cards.forEach(card => {
                    const cardTypesText = card.querySelector(".assignment-details span:nth-child(2)")
                        ?.textContent.replace('Типы вопросов:', '').trim() || "";

                    const typeMatch = !typeVal || cardTypesText.includes(typeVal);

                    if (typeMatch) {
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


            filterType.addEventListener("change", filterAssignments);

            filterAssignments();
        });
        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById('invite-student-modal');
            const openBtn = document.querySelector('.invite-student-btn');
            const closeBtns = document.querySelectorAll('#close-modal, #close-modal-bottom');
            const searchInput = document.getElementById('search-student');
            const resultsList = document.getElementById('search-results');
            const emailInput = document.getElementById('invite-student-email');

            openBtn.addEventListener('click', () => {
                modal.classList.remove('hidden');
            });

            closeBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    modal.classList.add('hidden');
                    searchInput.value = '';
                    resultsList.innerHTML = '';
                    emailInput.value = '';
                });
            });

            const availableStudents = @json($availableStudents);

            searchInput.addEventListener('input', () => {
                const query = searchInput.value.trim().toLowerCase();
                resultsList.innerHTML = '';

                if (!query) return;

                const filtered = availableStudents.filter(student =>
                    student.name.toLowerCase().includes(query) ||
                    student.email.toLowerCase().includes(query)
                );

                if (filtered.length === 0) {
                    const li = document.createElement('li');
                    li.textContent = 'Студенты не найдены';
                    li.style.color = '#888';
                    li.style.textAlign = 'center';
                    resultsList.appendChild(li);
                    return;
                }

                filtered.forEach(student => {
                    const li = document.createElement('li');
                    li.textContent = `${student.name} (${student.email})`;
                    li.setAttribute('data-email', student.email);
                    li.addEventListener('click', () => {
                        searchInput.value = `${student.name} (${student.email})`;
                        emailInput.value = student.email;
                        resultsList.innerHTML = '';
                    });
                    resultsList.appendChild(li);
                });
            });
        });
    </script>

</body>

</html>

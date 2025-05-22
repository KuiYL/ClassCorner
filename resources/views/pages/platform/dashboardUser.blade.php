<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная — Студент</title>
    <link rel="stylesheet" href="{{ asset('css/style-platform.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css ">
    <script src="{{ asset('js/script.js') }}" defer></script>
</head>

<body>
    @include('layout.sidebar', ['activePage' => 'dashboard'])

    <div class="topbar">
        @include('layout.topbar')

        <main>
            <div class="main-platform">

                <div class="banner">
                    <div class="greeting">
                        <h2>Добро пожаловать, {{ $user->name }}!</h2>
                        <p>У вас {{ $totalAssignments - $completedAssignments }} невыполненных заданий</p>
                    </div>
                    <div class="items">
                        <div class="item">
                            <p class="text">Активные классы</p>
                            <p class="text-count">{{ $totalClasses }}</p>
                        </div>
                        <div class="item">
                            <p class="text">Выполненные</p>
                            <p class="text-count">{{ $completedAssignments }}</p>
                        </div>
                        <div class="item">
                            <p class="text">Всего заданий</p>
                            <p class="text-count">{{ $totalAssignments }}</p>
                        </div>
                        <div class="item">
                            <p class="text">Осталось</p>
                            <p class="text-count">{{ $totalAssignments - $completedAssignments }}</p>
                        </div>
                    </div>
                </div>

                <div class="wrapper">
                    <div>
                        <h3>Мои классы</h3>
                        <p>Смотрите задания и информацию по вашим классам</p>
                    </div>
                    <a href="{{ route('user.classes') }}">
                        <i class="fas fa-arrow-right"></i>
                        Перейти к списку
                    </a>
                </div>
            </div>

            <div class="classes">
                <div class="head">
                    <h3>Мои классы</h3>
                    <a href="{{ route('user.classes') }}">Смотреть все</a>
                </div>
                @if ($classes->isEmpty())
                    <div class="warning-message">
                        Вы пока не состоите ни в одном классе.
                    </div>
                @else
                    <div class="items">
                        @foreach ($classes as $class)
                            <div class="class-card">
                                <div class="class-card-info">
                                    <div class="card"></div>
                                </div>
                                <div class="info">
                                    <div class="info-student">
                                        <h4>{{ $class->name }}</h4>
                                        <span>{{ $class->students()->count() - 1 }} других студентов</span>
                                    </div>
                                    <div class="info-teacher">
                                        <i class="fas fa-user-tie"></i>
                                        <span>{{ $class->teacher->name }}</span>
                                    </div>
                                    <div class="info-assigments">
                                        <div class="assigments-text">
                                            <i class="fas fa-tasks"></i>
                                            <span>{{ $class->assignments->count() }} заданий</span>
                                        </div>
                                        <a href="{{ route('class.show', $class->id) }}"
                                            class="text-sm text-[#6E76C1] font-medium">Открыть</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="assignments-filters">
                <div class="filters-container">
                    <label>Фильтр по статусу:
                        <select id="filter-status">
                            <option value="">Все</option>
                            <option value="not_submitted">Не выполнено</option>
                            <option value="submitted">На проверке</option>
                            <option value="graded">Выполнено</option>
                        </select>
                    </label>

                    <label>Фильтр по классу:
                        <select id="filter-class">
                            <option value="">Все</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->name }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
            </div>

            <div class="assignments">
                <div class="head">
                    <h3>Задания на выполнение</h3>
                    <a href="#">Смотреть все</a>
                </div>

                <div class="assignments-scrollable">
                    @forelse ($assignments as $item)
                        @php
                            $status = $item['submission']?->status ?? 'not_submitted';
                            $statusLabels = [
                                'not_submitted' => 'Не выполнено',
                                'submitted' => 'На проверке',
                                'graded' => 'Выполнено',
                            ];
                        @endphp

                        <div class="assignment-card status-{{ $status }}">
                            <div class="assignment-header">
                                <h4>{{ $item['assignment']->title }}</h4>
                                <div class="assignment-meta">
                                    <p><strong>Класс:</strong> {{ $item['class']->name }}</p>
                                    <p><strong>Дедлайн:</strong>
                                        @php
                                            $deadline = \Carbon\Carbon::parse($item['assignment']->due_date);
                                        @endphp
                                        {{ $deadline->format('d.m.Y') }}
                                    </p>
                                </div>
                                <div class="assignment-status">
                                    {{ $statusLabels[$status] }}
                                </div>
                            </div>
                            <div class="assignment-actions">
                                <a href="{{ route('assignments.show', $item['assignment']->id) }}"
                                    class="btn view-btn">
                                    Посмотреть
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="empty-message">Нет заданий.</p>
                    @endforelse
                </div>
            </div>

    </div>
    </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cards = document.querySelectorAll(".assignment-card");
            const filterStatus = document.getElementById("filter-status");
            const filterClass = document.getElementById("filter-class");

            function filterAssignments() {
                const selectedStatus = filterStatus.value.trim();
                const selectedClass = filterClass.value.trim();

                let hasVisibleCards = false;

                cards.forEach(card => {
                    const cardStatus = card.classList[1]?.split('-')[
                        1]; // status из class="assignment-card status-..."
                    const cardClass = card.querySelector('.assignment-meta strong + span')?.textContent
                        .trim() || '';

                    const statusMatch = !selectedStatus || cardStatus === selectedStatus;
                    const classMatch = !selectedClass || cardClass.includes(selectedClass);

                    if (statusMatch && classMatch) {
                        card.style.display = "flex";
                        hasVisibleCards = true;
                    } else {
                        card.style.display = "none";
                    }
                });

                const emptyMessage = document.querySelector(".empty-message");
                if (emptyMessage) {
                    emptyMessage.style.display = hasVisibleCards ? "none" : "block";
                }
            }

            filterStatus.addEventListener("change", filterAssignments);
            filterClass.addEventListener("change", filterAssignments);

            filterAssignments();
        });
    </script>
</body>

</html>

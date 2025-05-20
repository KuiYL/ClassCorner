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
    @include('layout.sidebar', ['activePage' => 'dashboard'])
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

                <div class="assignments">
                    <div class="head">
                        <h3>Задания</h3>
                    </div>
                    <div class="items">
                        @forelse ($assignments as $assignment)
                            <div
                                class="item @if ($assignment->status == 'submitted') task-urgent @else task-completed @endif animate-pop">
                                <div class="wrapper">
                                    <div>
                                        <div class="text-title">
                                            <span class="title">{{ $assignment->title }}</span>
                                            @if ($assignment->status == 'submitted')
                                                <span class="title-emergency">Срочно</span>
                                            @endif
                                        </div>
                                        <p class="count">
                                            {{ $assignment->class->name ?? 'Класс не указан' }} ·
                                            {{ $assignment->students_count ?? 0 }} работ
                                        </p>
                                        <div class="data-info">
                                            <i class="fas fa-clock"></i>
                                            <span>Срок проверки: до
                                                {{ \Carbon\Carbon::parse($assignment->due_date)->format('d.m.Y H:i') }}</span>
                                        </div>
                                    </div>
                                    <button class="check" title="Проверить">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="empty-message">Нет заданий.</p>
                        @endforelse
                    </div>
                </div>

                <div class="table">
                    <div class="head">
                        <h3>Студенты класса</h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Имя</th>
                                <th>Количество-баллов</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students->skip(1) as $student)
                                <tr>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->progress }}%</td>
                                    <td>
                                        <a href="#">Подробнее</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="empty-message">Нет студентов.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

        </main>
    </div>

    <a href="{{ route('assignments.create', $class->id) }}" class="floating-btn">
        <button>
            <i class="fas fa-plus"></i>
        </button>
    </a>
</body>

</html>

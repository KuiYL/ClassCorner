<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Платформа для преподавателей</title>
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
                        <h2>Добро пожаловать, {{ $user->name }}!</h2>
                        <p>У вас {{ $newAssignmentsCount }} новых задания на проверку</p>
                    </div>
                    <div class="items">
                        <div class="item">
                            <p class="text">Активные классы</p>
                            <p class="text-count">{{ $activeClassesCount }}</p>
                        </div>
                        <div class="item">
                            <p class="text">Обучающихся</p>
                            <p class="text-count">{{ $studentsCount }}</p>
                        </div>
                        <div class="item">
                            <p class="text">Задания</p>
                            <p class="text-count">{{ $assignmentsCount }}</p>
                        </div>
                        <div class="item">
                            <p class="text">Новые</p>
                            <p class="text-count">{{ $newAssignmentsCount }}</p>
                        </div>
                    </div>
                </div>

                <div class="banner-new-class">
                    <div class="wrapper">
                        <div>
                            <h3>Создать новый класс</h3>
                            <p>Добавьте студентов, материалы и задания</p>
                        </div>
                        <a href="{{ route('classes.create') }}">
                            <i class="fas fa-plus"></i>
                            Новый класс
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
                            У вас пока нет классов. Нажмите "Новый класс", чтобы создать новый.
                        </div>
                    @else
                        <div class="items">
                            @foreach ($classes as $class)
                                <div class="class-card">
                                    <div class="class-card-info">
                                        <div class="card">
                                            <div class="class-settings">
                                                <i class="fas fa-cog"></i>
                                                <div class="settings-menu hidden">
                                                    <a class="setting-menu-action"
                                                        href="{{ route('classes.edit', $class->id) }}">
                                                        <i class="fas fa-edit"></i> Изменить
                                                    </a>
                                                    <button class="setting-menu-action delete-button" type="button"
                                                        data-id="{{ $class->id }}" data-name="{{ $class->name }}">
                                                        <i class="fas fa-trash"></i> Удалить
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="info">
                                        <div class="info-student">
                                            <h4>{{ $class->name }}</h4>
                                            <span>{{ $class->students()->count() - 1 }} обучающихся</span>
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

                <div class="assignments">
                    <div class="head">
                        <h3>Задания на проверку</h3>
                        <a href="#">Смотреть все</a>
                    </div>

                    <div class="items">
                        @forelse ($assignmentsToGrade as $assignment)
                            <div
                                class="item @if ($assignment->status == 'submitted') task-urgent @else task-completed @endif animate-pop">
                                <div class="wrapper">
                                    <div>
                                        <div class="text-title">
                                            <span class="title">{{ $assignment->assignment->title }}</span>
                                            @if ($assignment->status == 'submitted')
                                                <span class="title-emergency">Срочно</span>
                                            @endif
                                        </div>
                                        <p class="count">
                                            {{ $assignment->assignment->class->name }} ·
                                            {{ $assignment->students_count }} работ
                                        </p>
                                        <div class="data-info">
                                            <i class="fas fa-clock"></i>
                                            <span>Срок проверки: до
                                                {{ $assignment->deadline->format('d.m.Y H:i') }}</span>
                                        </div>
                                    </div>
                                    <button class="check" title="Проверить">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="empty-message">У вас нет заданий на проверку.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div class="floating-btn">
        <button>
            <i class="fas fa-plus"></i>
        </button>
    </div>
    @include('layout.modal-delete')
</body>

</html>

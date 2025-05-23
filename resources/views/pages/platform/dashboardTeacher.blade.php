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
                        <a href="{{ route('classes.create', ['return_url' => url()->current()]) }}">
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
                                                        href="{{ route('classes.edit', ['id' => $class->id, 'return_url' => url()->current()]) }}">
                                                        <i class="fas fa-edit"></i> Изменить
                                                    </a>
                                                    <button class="setting-menu-action delete-button" type="button"
                                                        data-id="{{ $class->id }}" data-name="{{ $class->name }}"
                                                        data-type="class">
                                                        <i class="fas fa-trash"></i>Удалить
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
                                            <span>{{ $class->teacher->name }} {{ $class->teacher->surname }}</span>
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
                    <div class="assignments-filters">
                        <div class="filters-container">
                            <label>Фильтр по статусу:
                                <select id="filter-status">
                                    <option value="">Все</option>
                                    <option value="Срочно">Срочно</option>
                                    <option value="Проверено">Проверено</option>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="assignments-scrollable">
                        @forelse ($assignmentsToGrade as $assignment)
                            @php
                                $status = $assignment->status ?? 'graded';
                                $statusLabels = [
                                    'submitted' => 'Срочно',
                                    'graded' => 'Проверено',
                                ];
                            @endphp

                            <div class="assignment-card status-{{ $status }}">
                                <div class="assignment-header">
                                    <h4>{{ $assignment->assignment->title }}</h4>
                                    <div class="assignment-meta">
                                        <p><strong>Класс:</strong> <span class="assignment-class">
                                                {{ $assignment->assignment->class->name }}
                                            </span></p>
                                        <p><strong>Работ:</strong> {{ $assignment->students_count }}</p>
                                    </div>
                                    <div class="assignment-status">
                                        {{ $statusLabels[$status] }}
                                    </div>
                                </div>
                                <button class="action-button check" title="Проверить"> Проверить
                                </button>
                            </div>
                        @empty
                            <p class="empty-message">У вас нет заданий на проверку.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </main>
    </div>

    <a href="{{ route('classes.create', ['return_url' => url()->current()]) }}" class="floating-btn">
        <button>
            <i class="fas fa-plus"></i>
        </button>
    </a>
    @include('layout.modal-delete')

</body>

</html>

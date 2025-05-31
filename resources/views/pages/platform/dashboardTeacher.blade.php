@extends('pages.platform.layout', ['activePage' => 'dashboard', 'title' => 'Главная', 'quick_action' => 'classes.create'])
@section('content')
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
                <a href="{{ route('assignments.to.grade') }}">Смотреть все</a>
            </div>

            <div class="assignments-filters">
                <div class="filters-container">
                    <label>Фильтр по статусу:
                        <select id="filter-status">
                            <option value="">Все</option>
                            <option value="submitted">На проверку</option>
                            <option value="graded">Проверено</option>
                        </select>
                    </label>
                </div>
            </div>

            <div class="assignments-scrollable" id="assignments-list">
                @forelse ($assignmentsToGrade as $assignment)
                    @php
                        $status = $assignment->status ?? 'graded';
                        $statusLabels = [
                            'submitted' => 'На проверку',
                            'graded' => 'Проверено',
                        ];
                    @endphp

                    <div class="assignment-card status-{{ $status }}" data-status="{{ $status }}">
                        <div class="assignment-header">
                            <h4>{{ Str::limit($assignment->assignment->title, 40) }}</h4>
                            <div class="assignment-meta">
                                <p><strong>Класс:</strong>
                                    {{ Str::limit(optional($assignment->assignment->class)->name, 40) ?? 'Не указан' }}
                                </p>
                                <p><strong>Обучающийся:</strong>
                                    @if ($assignment->user)
                                        {{ $assignment->user->name }} {{ $assignment->user->surname }}
                                    @else
                                        Имя не найдено
                                    @endif
                                </p>
                            </div>
                            <div class="assignment-status">{{ $statusLabels[$status] }}</div>
                        </div>

                        @if ($status === 'submitted')
                            <a href="{{ route('assignment.grade.form', $assignment->assignment->id) }}"
                                class="action-button">
                                Проверить
                            </a>
                        @else
                            @if ($status === 'graded')
                                <a href="{{ route('assignment.result', ['id' => $assignment->id]) }}"
                                    class="action-button">
                                    Посмотреть
                                </a>
                            @else
                                <a href="{{ route('assignment.grade.form', $assignment->assignment->id) }}"
                                    class="action-button">
                                    Посмотреть
                                </a>
                            @endif
                        @endif
                    </div>
                @empty
                    <p class="empty-message">Нет заданий на проверку.</p>
                @endforelse
            </div>
        </div>
    </div>
    <script>
        document.getElementById('filter-status').addEventListener('change', function() {
            const selectedStatus = this.value;
            const assignments = document.querySelectorAll('#assignments-list .assignment-card');

            assignments.forEach(card => {
                const cardStatus = card.getAttribute('data-status');
                if (!selectedStatus || cardStatus === selectedStatus) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
@endsection

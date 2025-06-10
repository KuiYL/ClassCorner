@extends('pages.platform.layout', ['activePage' => 'dashboard', 'title' => 'Главная', 'quick_action' => 'null'])
@section('content')
    <div class="container-fluid py-6 px-md-4">

        <!-- Баннер приветствия -->
        <div class="bg-[#6E76C1] text-white rounded-lg shadow-lg mb-8 overflow-hidden">
            <div class="p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <h2 class="text-3xl font-bold">Добро пожаловать, {{ $user->name }}!</h2>
                    <p class="mt-2 text-lg opacity-90">У вас {{ $totalAssignments - $completedAssignments }} невыполненных
                        заданий</p>
                </div>

                <a href="{{ route('user.assignments') }}"
                    class="inline-flex items-center px-5 py-2 bg-white/10 hover:bg-white/20 text-white font-medium rounded-md transition duration-200">
                    <i class="fas fa-book-open mr-2"></i> Мои задания
                </a>
            </div>
        </div>

        <!-- Статистика сверху -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Классы -->
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-[#6E76C1]">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-[#6E76C1]/10 text-[#6E76C1]">
                        <i class="fas fa-school text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Классов</p>
                        <h3 class="text-xl font-bold text-gray-800">{{ $totalClasses }}</h3>
                    </div>
                </div>
            </div>

            <!-- Выполненные задания -->
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Выполнено</p>
                        <h3 class="text-xl font-bold text-gray-800">{{ $completedAssignments }}</h3>
                    </div>
                </div>
            </div>

            <!-- Всего заданий -->
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-tasks text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Всего заданий</p>
                        <h3 class="text-xl font-bold text-gray-800">{{ $totalAssignments }}</h3>
                    </div>
                </div>
            </div>

            <!-- Осталось -->
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-red-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-hourglass-half text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Осталось</p>
                        <h3 class="text-xl font-bold text-gray-800">{{ $totalAssignments - $completedAssignments }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Мои классы -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900 pl-4 relative">
                    <span class="absolute left-0 top-0 h-full w-1 bg-[#6E76C1]"></span>
                    Мои классы
                </h3>
                <a href="{{ route('user.classes') }}" class="text-sm text-[#6E76C1] hover:text-[#616EBD] font-medium">
                    Смотреть все <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            @if ($classes->isEmpty())
                <div class="text-center py-10 bg-white rounded-lg shadow-sm border border-dashed border-gray-300">
                    <i class="fas fa-school text-gray-300 text-4xl mb-4"></i>
                    <h4 class="text-lg font-semibold text-gray-600">Вы не состоите ни в одном классе</h4>
                    <p class="text-gray-500 mt-1">Подключитесь к классу, чтобы начать выполнять задания</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($classes as $class)
                        <div
                            class="bg-white rounded-lg shadow-sm overflow-hidden border-l-4 border-[#6E76C1] transition-all duration-300 hover:shadow-md">
                            <!-- Заголовок класса -->
                            <div class="p-4 bg-gradient-to-r from-[#6E76C1] to-[#9CA4F2] flex justify-between items-start">
                                <h4 class="font-bold text-lg text-white truncate">{{ $class->name }}</h4>
                            </div>

                            <!-- Информация о классе -->
                            <div class="p-4 pt-3 pb-3 space-y-2">
                                <div class="flex items-center justify-between text-sm text-gray-600">
                                    <div class="flex items-center gap-1">
                                        <i class="fas fa-user-tie mr-2 text-[#6E76C1]"></i>{{ $class->teacher->name }}
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <i
                                            class="fas fa-users mr-2 text-[#6E76C1]"></i>{{ $class->students()->count() - 1 }}
                                        Учеников
                                    </div>
                                </div>

                                <div class="flex items-center justify-between text-sm text-gray-600">
                                    <div class="flex items-center gap-1">
                                        <i class="fas fa-tasks mr-2 text-[#6E76C1]"></i>{{ $class->assignments->count() }}
                                        заданий
                                    </div>
                                    <a href="{{ route('class.show', $class->id) }}"
                                        class="text-[#6E76C1] hover:text-[#616EBD] font-medium">
                                        Открыть <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Мои задания -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900 pl-4 relative">
                    <span class="absolute left-0 top-0 h-full w-1 bg-[#6E76C1]"></span>
                    Задания на выполнение
                </h3>
                <a href="{{ route('user.assignments') }}" class="text-sm text-[#6E76C1] hover:text-[#616EBD] font-medium">
                    Смотреть все <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <!-- Фильтры -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6 border border-gray-200">
                <div class="filters-container flex flex-wrap gap-4">
                    <label class="block">
                        <span class="text-sm font-medium text-gray-700">Фильтр по статусу:</span>
                        <select id="filter-status"
                            class="form-select w-auto px-3 py-2 mt-1 border border-gray-300 rounded-md focus:ring-[#6E76C1] focus:border-transparent">
                            <option value="">Все</option>
                            <option value="not_submitted">Не выполнено</option>
                            <option value="submitted">На проверке</option>
                            <option value="graded">Выполнено</option>
                        </select>
                    </label>

                    <label class="block">
                        <span class="text-sm font-medium text-gray-700">Фильтр по классу:</span>
                        <select id="filter-class"
                            class="form-select w-auto px-3 py-2 mt-1 border border-gray-300 rounded-md focus:ring-[#6E76C1] focus:border-transparent">
                            <option value="">Все</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->name }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
            </div>

            <!-- Список заданий -->
            <div id="assignments-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($assignments as $item)
                    @php
                        $assignment = $item['assignment'];
                        $status = $item['submission']?->status ?? 'not_submitted';
                        $badgeClass = match ($status) {
                            'not_submitted' => 'bg-red-50 text-red-600',
                            'submitted' => 'bg-yellow-50 text-yellow-600',
                            'graded' => 'bg-green-50 text-green-600',
                            default => 'bg-gray-100 text-gray-700',
                        };
                        $deadline = \Carbon\Carbon::parse($assignment->due_date);
                        $description = $assignment->description ?: 'Нет описания';

                        // Пример: прогресс и количество (если есть)
                        $completed = $item['completed'] ?? 0;
                        $totalStudents = $item['totalStudents'] ?? 0;
                        $progress = $totalStudents ? round(($completed / $totalStudents) * 100) : 0;
                    @endphp

                    <div class="card h-full shadow-sm hover:shadow-md transition-shadow duration-200 border border-gray-200 rounded-lg overflow-hidden bg-white"
                        data-id="{{ $assignment->id }}">

                        <div class="p-4">
                            <div class="flex justify-between items-start">
                                <h4 class="font-bold text-gray-900 flex items-center group truncate">
                                    <i
                                        class="fas fa-book-open mr-2 text-[#6E76C1] group-hover:scale-110 transition-transform duration-200"></i>
                                    {{ $assignment->title }}
                                </h4>
                                <span class="inline-block px-2 py-1 text-xs font-medium rounded-full {{ $badgeClass }}">
                                    @if ($status === 'not_submitted')
                                        Не выполнено
                                    @elseif ($status === 'submitted')
                                        На проверке
                                    @else
                                        Выполнено
                                    @endif
                                </span>
                            </div>

                            <p class="text-sm text-gray-600 mt-2 truncate">{{ $description }}</p>

                            <div class="mt-4 text-sm text-gray-500 space-y-2">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-calendar-check text-[#6E76C1]"></i>
                                    <strong class="text-gray-700">Дедлайн:</strong>
                                    <span>{{ $deadline->format('d.m.Y') }} в {{ $deadline->format('H:i') }}</span>
                                </div>

                                @if ($totalStudents > 0)
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-tasks text-[#6E76C1]"></i>
                                        <strong class="text-gray-700">Выполнили:</strong>
                                        <div class="w-full ml-2">
                                            <div class="progress h-2 bg-gray-200 rounded overflow-hidden">
                                                <div class="progress-bar bg-[#6E76C1]" role="progressbar"
                                                    style="width: {{ $progress }}%"
                                                    aria-valuenow="{{ $progress }}" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                            <small class="text-gray-500">{{ $completed }} из {{ $totalStudents }}
                                                выполнили</small>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-200 flex justify-end gap-2">
                                @if ($status === 'not_submitted')
                                    <a href="{{ route('assignments.show', $assignment->id) }}"
                                        class="btn outline-none text-red-600 border-red-600 hover:bg-red-600 hover:text-gray-100 rounded-md px-3 py-1 inline-flex items-center">
                                        <i class="fas fa-arrow-right mr-1"></i> Перейти
                                    </a>
                                @elseif ($status === 'submitted')
                                    <span
                                        class="inline-flex items-center text-sm text-yellow-600 cursor-default px-3 py-1 rounded-md border border-yellow-600 bg-yellow-50">
                                        На проверке <i class="fas fa-clock ml-1"></i>
                                    </span>
                                @else
                                    <a href="{{ route('assignment.result', ['id' => $item['submission']->id]) }}"
                                        class="btn outline-none text-green-600 border-green-600 hover:bg-green-600 hover:text-gray-100 rounded-md px-3 py-1 inline-flex items-center">
                                        <i class="fas fa-eye mr-1"></i> Результаты
                                    </a>
                                @endif

                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-full text-center py-10 bg-white rounded-lg shadow-sm border border-dashed border-gray-300">
                        <i class="fas fa-folder-open text-gray-300 text-4xl mb-2"></i>
                        <p class="text-gray-500">Нет заданий на выполнение.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cards = document.querySelectorAll(".assignment-card");
            const filterStatus = document.getElementById("filter-status");
            const filterClass = document.getElementById("filter-class");
            const noAssignmentsMessage = document.getElementById("no-assignments-message");

            function applyFilters() {
                const selectedStatus = filterStatus.value.trim();
                const selectedClass = filterClass.value.trim();

                let visibleCount = 0;

                cards.forEach(card => {
                    const cardStatus = card.classList[0]?.split('-')[1] || '';
                    const cardClassName = card.querySelector('.assignment-class')?.textContent.trim() || '';

                    const matchesStatus = !selectedStatus || cardStatus === selectedStatus;
                    const matchesClass = !selectedClass || cardClassName === selectedClass;

                    if (matchesStatus && matchesClass) {
                        card.style.display = "block";
                        visibleCount++;
                    } else {
                        card.style.display = "none";
                    }
                });

                // Показываем/скрываем сообщение
                if (visibleCount === 0) {
                    noAssignmentsMessage.style.display = "block";
                } else {
                    noAssignmentsMessage.style.display = "none";
                }
            }

            filterStatus.addEventListener("change", applyFilters);
            filterClass.addEventListener("change", applyFilters);

            // Инициализация при загрузке
            window.addEventListener("load", applyFilters);
        });
    </script>
@endsection

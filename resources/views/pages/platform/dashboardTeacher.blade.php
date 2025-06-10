@extends('pages.platform.layout', ['activePage' => 'dashboard', 'title' => 'Главная', 'quick_action' => 'classes.create'])
@section('content')
    <div class="container-fluid py-6 px-md-4">

        <div class="bg-[#6E76C1] text-white rounded-lg shadow-lg mb-8 overflow-hidden">
            <div class="p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <h2 class="text-3xl font-bold">Добро пожаловать, {{ $user->name }}!</h2>
                    <p class="mt-2 text-lg opacity-90">У вас {{ $newAssignmentsCount }} новых заданий на проверку</p>
                </div>

                <a href="{{ route('classes.create', ['return_url' => url()->current()]) }}"
                    class="inline-flex items-center px-5 py-2 bg-white/10 hover:bg-white/20 text-white font-medium rounded-md transition duration-200">
                    <i class="fas fa-plus mr-2"></i> Новый класс
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-[#6E76C1]">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-[#6E76C1]/10 text-[#6E76C1]">
                        <i class="fas fa-school text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Активные классы</p>
                        <h3 class="text-2xl font-semibold text-gray-800">{{ $activeClassesCount }}</h3>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-[#6E76C1]">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-[#6E76C1]/10 text-[#6E76C1]">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Обучающихся</p>
                        <h3 class="text-2xl font-semibold text-gray-800">{{ $studentsCount }}</h3>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-[#6E76C1]">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-[#6E76C1]/10 text-[#6E76C1]">
                        <i class="fas fa-tasks text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Всего заданий</p>
                        <h3 class="text-2xl font-semibold text-gray-800">{{ $assignmentsCount }}</h3>
                    </div>
                </div>
            </div>

            <!-- Новые задания -->
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-[#6E76C1]">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-inbox text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Заданий на проверку</p>
                        <h3 class="text-2xl font-semibold text-gray-800">{{ $newAssignmentsCount }}</h3>
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
                <a href="{{ route('user.classes') }}" class="text-[#6E76C1] hover:text-[#616EBD] text-sm font-medium">
                    Смотреть все <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            @if ($classes->isEmpty())
                <div class="text-center py-10 bg-white rounded-lg shadow-sm border border-dashed border-gray-300">
                    <i class="fas fa-school text-gray-300 text-4xl mb-4"></i>
                    <h4 class="text-lg font-semibold text-gray-600">У вас пока нет классов</h4>
                    <p class="text-gray-500 mt-1">Нажмите "Новый класс", чтобы начать</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($classes as $class)
                        <div
                            class="bg-white rounded-lg shadow-sm overflow-hidden border-l-4 border-[#6E76C1] transition-all duration-300 hover:shadow-md">
                            <!-- Заголовок -->
                            <div class="p-4 bg-gradient-to-r from-[#6E76C1] to-[#9CA4F2] flex justify-between items-start">
                                <h4 class="font-bold text-lg text-white truncate group-hover:text-white">
                                    {{ $class->name }}
                                </h4>

                                <!-- Меню действий -->
                                <div class="relative group">
                                    <i
                                        class="fas fa-cog text-white opacity-80 hover:opacity-100 cursor-pointer transition-opacity"></i>
                                    <div
                                        class="absolute right-0 mt-2 w-36 bg-white shadow-md rounded-md z-10 hidden group-hover:block border border-gray-200">
                                        <a href="{{ route('classes.edit', ['id' => $class->id, 'return_url' => url()->current()]) }}"
                                            class="block px-4 py-2 hover:bg-gray-100 text-sm flex items-center">
                                            <i class="fas fa-edit mr-2 text-[#6E76C1]"></i> Изменить
                                        </a>
                                        <button type="button"
                                            class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100 text-sm flex items-center delete-button"
                                            data-id="{{ $class->id }}" data-name="{{ $class->name }}"
                                            data-type="class">
                                            <i class="fas fa-trash mr-2 text-red-600"></i> Удалить
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Информация -->
                            <div class="p-4 pt-3 pb-3 space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-600">
                                        <i class="fas fa-user-tie mr-2 text-[#6E76C1]"></i>{{ $class->teacher->name }}
                                        {{ $class->teacher->surname }}
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <i
                                            class="fas fa-users mr-2 text-[#6E76C1]"></i>{{ $class->students()->count() - 1 }}
                                        учеников
                                    </div>
                                </div>

                                <div class="flex items-center justify-between text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <i class="fas fa-tasks mr-2 text-[#6E76C1]"></i>{{ $class->assignments->count() }}
                                        заданий
                                    </div>
                                    <a href="{{ route('class.show', $class->id) }}"
                                        class="inline-flex items-center text-sm text-[#6E76C1] hover:text-[#616EBD] font-medium">
                                        Открыть <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Задания на проверку -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Задания на проверку</h3>
                <a href="{{ route('assignments.to.grade') }}"
                    class="text-sm text-[#6E76C1] hover:text-[#616EBD] font-medium">
                    Смотреть все <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <!-- Фильтры -->
            <div class="mb-4 flex items-center gap-4">
                <label for="filter-status" class="text-sm font-medium text-gray-700">Фильтр по статусу:</label>
                <select id="filter-status"
                    class="form-select w-auto px-4 py-2 border border-gray-300 rounded-md focus:ring-[#6E76C1] focus:border-transparent transition duration-200">
                    <option value="">Все</option>
                    <option value="submitted">На проверке</option>
                    <option value="graded">Проверено</option>
                </select>
            </div>

            <!-- Список заданий -->
            <div id="assignments-list" class="space-y-4 max-h-96 overflow-y-auto pr-2">
                @forelse ($assignmentsToGrade as $assignment)
                    @php
                        $status = $assignment->status ?? 'graded';
                        $statusLabels = [
                            'submitted' => 'На проверке',
                            'graded' => 'Проверено',
                        ];
                        $badgeColor =
                            $status === 'submitted' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800';
                        $badgeText = $statusLabels[$status];
                    @endphp

                    <div
                        class="assignment-card bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">
                                    {{ Str::limit($assignment->assignment->title, 40) }}</h4>
                                <div class="mt-2 space-y-1">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-chalkboard-teacher text-sm text-[#6E76C1] mr-2"></i>
                                        <strong>Класс:</strong>
                                        <span
                                            class="ml-1">{{ optional($assignment->assignment->class)->name ?? 'Не указан' }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-user-graduate text-sm text-[#6E76C1] mr-2"></i>
                                        <strong>Ученик:</strong>
                                        <span class="ml-1">
                                            {{ $assignment->user?->name . ' ' . $assignment->user?->surname ?: 'Имя не найдено' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col items-end">
                                <span
                                    class="px-3 py-1 text-xs rounded-full font-medium {{ $status === 'submitted' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $statusLabels[$status] }}
                                </span>

                                @if ($status === 'submitted')
                                    <a href="{{ route('assignment.grade.form', $assignment->assignment->id) }}"
                                        class="mt-3 inline-flex items-center text-sm text-[#6E76C1] hover:text-[#616EBD]">
                                        Проверить <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                @else
                                    <a href="{{ route('assignment.result', ['id' => $assignment->id]) }}"
                                        class="mt-3 inline-flex items-center text-sm text-[#6E76C1] hover:text-[#616EBD]">
                                        Посмотреть <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 bg-white rounded-lg shadow-sm border border-dashed border-gray-300">
                        <i class="fas fa-folder-open text-gray-300 text-4xl mb-4"></i>
                        <p class="text-gray-500">Нет заданий на проверку</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        document.getElementById("filter-status").addEventListener("change", function() {
            const selectedStatus = this.value;
            const cards = document.querySelectorAll(".assignment-card");

            cards.forEach(card => {
                const cardStatus = card.getAttribute("data-status");
                if (!selectedStatus || cardStatus === selectedStatus) {
                    card.style.display = "flex";
                } else {
                    card.style.display = "none";
                }
            });
        });
    </script>
@endsection

@extends('pages.platform.layout', ['activePage' => 'dashboard', 'title' => 'Главная', 'quick_action' => 'classes.create'])
@section('content')
    <div class="container-fluid py-6 px-md-4">
        <div class="bg-[#6E76C1] text-white rounded-lg shadow-lg mb-8 overflow-hidden">
            <div class="p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <h2 class="text-3xl font-bold">Добро пожаловать, {{ $user->name }} {{ $user->surname }}!</h2>
                    @if ($user->role === 'teacher')
                        <p class="mt-2 text-lg opacity-90">У вас {{ $newAssignmentsCount }} новых заданий на проверку</p>
                    @endif
                </div>

                <a href="{{ route('classes.create', ['return_url' => url()->current()]) }}"
                    class="inline-flex items-center px-5 py-2 bg-white/10 hover:bg-white/20 text-white font-medium rounded-md transition duration-200">
                    <i class="fas fa-plus mr-2"></i> Новый класс
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-12">
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

            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-red-400">
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

        <div class="mb-16">
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
                <div class="text-center py-10 bg-gray-50 border border-dashed border-gray-300 rounded-lg">
                    <i class="fas fa-school text-gray-300 text-4xl mb-4"></i>
                    <h4 class="text-lg font-semibold text-gray-600">У вас пока нет классов</h4>
                    <p class="text-gray-500 mt-1">Нажмите "Новый класс", чтобы начать</p>
                </div>
            @else
                <div id="classes-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($classes->take(6) as $class)
                        <div
                            class="class-card bg-white rounded-lg shadow-sm overflow-hidden border-l-4 border-[#6E76C1] transition-all duration-300 hover:shadow-md">
                            <div
                                class="p-4 bg-gradient-to-r from-[#6E76C1] to-[#9CA4F2] flex justify-between items-start relative">
                                <h4 class="font-bold text-lg text-white flex items-center gap-2 truncate">
                                    <i class="fas fa-school text-white/80"></i>
                                    <span class="truncate">{{ $class->name }}</span>
                                </h4>
                                <div class="relative group">
                                    <i
                                        class="fas fa-cog text-white cursor-pointer group-hover:text-[#6E76C1] transition-colors"></i>
                                    <div
                                        class="absolute right-0 top-full mt-2 w-36 bg-white shadow-md rounded-md z-10 menu border border-gray-200">
                                        <a href="{{ route('classes.edit', $class->id) }}"
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

                            <div class="p-4 pt-3 pb-3 space-y-2">

                                <div class="flex justify-between text-sm text-gray-600">
                                    <div class="flex items-center gap-1">
                                        <i class="fas fa-users text-[#6E76C1]"></i>
                                        <span>{{ $class->students()->count() - 1 }} учеников</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <i class="fas fa-book-open text-[#6E76C1]"></i>
                                        <span>{{ $class->assignments->count() }} заданий</span>
                                    </div>
                                </div>

                                @if ($class->description)
                                    <p class="text-base text-gray-500 line-clamp-2 truncate mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>{{ $class->description }}
                                    </p>
                                @else
                                    <div class="flex items-center text-base text-gray-400 mt-1">
                                        <i class="fas fa-info-circle mr-1"></i> Нет описания
                                    </div>
                                @endif
                            </div>

                            <div class="px-4 py-2 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Создан: {{ \Carbon\Carbon::parse($class->created_at)->format('d.m.Y') }}
                                </div>

                                <a href="{{ route('class.show', $class->id) }}"
                                    class="inline-flex items-center ml-2 px-3 py-1 text-xs bg-[#6E76C1] text-white font-medium rounded-md hover:bg-[#6E76C1]/80 transition duration-200">
                                    Подробнее <i class="fas fa-arrow-right ml-1 text-sm"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>

        @if ($user->role === 'teacher')
            <div class="mb-8 w-full">
                <div
                    class="flex justify-between items-center mb-6 bg-gradient-to-r from-[#6E76C1] to-[#9CA4F2] text-white py-4 px-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold">Задания на проверку</h3>
                </div>
                <div class="mb-6 flex flex-wrap gap-4 sm:gap-6">
                    <div class="flex-1 min-w-[200px]">
                        <label for="filter-title" class="block text-sm font-medium text-gray-700 mb-1">Фильтр по
                            названию</label>
                        <input type="text" id="filter-title" placeholder="Например: Задание 1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-[#6E76C1] focus:border-transparent transition duration-200"
                            oninput="filterByTitle()">
                    </div>

                    <div class="self-end">
                        <button id="clear-filter" type="button"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md transition duration-200"
                            onclick="clearFilter()">
                            Сбросить
                        </button>
                    </div>
                </div>

                <div id="assignments-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 relative">
                    @forelse ($assignmentsToGrade->filter(fn($assignment) => $assignment->status === 'submitted') as $assignment)
                        <div class="assignment-card w-[380px] bg-white rounded-lg shadow-md border-l-4 border-yellow-500 border-gray-200 p-6 flex flex-col justify-between hover:shadow-lg relative transition"
                            data-title="{{ strtolower($assignment->assignment->title) }}">
                            <span
                                class="absolute top-6 right-6 px-6 py-1 inline-block mt-2 px-2 py-1 text-xs font-medium rounded text-yellow-600 bg-yellow-100 border-yellow-600}}">
                                {{ $assignment->status === 'submitted' ? 'На проверке' : 'Проверено' }}
                            </span>

                            <div>
                                <h5 class="font-semibold text-gray-900 mb-2">
                                    {{ Str::limit($assignment->assignment->title, 40) }}</h5>
                                <p class="text-sm text-gray-500 flex items-center mb-1">
                                    <i class="fas fa-chalkboard-teacher text-[#6E76C1] mr-2"></i>
                                    Класс: {{ optional($assignment->assignment->class)->name ?? 'Не указан' }}
                                </p>
                                <p class="text-sm text-gray-500 flex items-center">
                                    <i class="fas fa-user-graduate text-[#6E76C1] mr-2"></i>
                                    Ученик:
                                    {{ $assignment->user?->name . ' ' . $assignment->user?->surname ?: 'Имя не найдено' }}
                                </p>
                            </div>

                            <div class="mt-4 py-2 border-t border-gray-100 flex justify-between items-center">
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Создан: {{ \Carbon\Carbon::parse($assignment->created_at)->format('d.m.Y') }}
                                </div>

                                <a href="{{ route('assignment.grade.form', $assignment->assignment->id) }}"
                                    class="inline-flex items-center ml-2 px-3 py-1 text-xs bg-[#6E76C1] text-white font-medium rounded-md hover:bg-[#6E76C1]/80 transition duration-200">
                                    Проверить <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                        <div id="no-results"
                            class="hidden col-span-full text-center py-6 bg-white rounded-lg shadow-sm border border-dashed border-gray-300">
                            <i class="fas fa-search text-gray-400 text-4xl mb-2"></i>
                            <p class="text-gray-500">Нет заданий, соответствующих вашему запросу.</p>
                        </div>
                    @empty
                        <div
                            class="col-span-full text-center py-10 bg-gray-50 border border-dashed border-gray-300 rounded-lg">
                            <i class="fas fa-inbox text-gray-300 text-4xl mb-4"></i>
                            <p class="text-gray-500">Нет заданий на проверку</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif
    </div>

    <script>
        function filterByTitle() {
            const filterValue = document.getElementById('filter-title').value.toLowerCase();
            const assignments = document.querySelectorAll('#assignments-list .assignment-card');
            const noResults = document.getElementById('no-results');

            let visibleCount = 0;

            assignments.forEach(card => {
                const title = card.getAttribute('data-title');
                if (title.includes(filterValue)) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            noResults.classList.toggle('hidden', visibleCount > 0);
        }

        function clearFilter() {
            document.getElementById('filter-title').value = '';
            filterByTitle();
        }

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".class-card .fa-cog").forEach(cogIcon => {
                cogIcon.addEventListener("click", function(event) {
                    event.stopPropagation();
                    const menu = this.nextElementSibling;
                    const isMenuVisible = menu.classList.contains("show");
                    document.querySelectorAll(".class-card .menu").forEach(menu => {
                        menu.classList.remove("show");
                    });
                    if (!isMenuVisible) {
                        menu.classList.add("show");
                    }
                });
            });

            document.addEventListener("click", function() {
                document.querySelectorAll(".class-card .menu").forEach(menu => {
                    menu.classList.remove("show");
                });
            });
        });
    </script>
@endsection

@extends('pages.platform.layout', ['activePage' => 'classes', 'title' => 'Классы', 'quick_action' => 'null'])
@section('content')
    <div class="container-fluid py-6 px-md-4">
        <div class="bg-white rounded-lg shadow-md border-l-4 border-[#6E76C1] mb-6 overflow-hidden">
            <div class="p-6 flex justify-between items-center bg-[#EEF2FF]">
                <div>
                    <h3 class="text-xl font-semibold text-[#555EB1]">Ваши классы</h3>
                    <p class="mt-2 text-sm text-[#6E76C1] font-medium">
                        Посмотрите информацию по вашим классам и заданиям
                    </p>
                </div>
                <a id="open-invitations-modal"
                    class="inline-flex items-center px-4 py-2 bg-[#6E76C1] hover:bg-[#555EB1] text-white font-medium rounded-md transition duration-200">
                    <i class="fas fa-envelope mr-2"></i> Посмотреть приглашения
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center mb-6 relative">
                <h3 class="text-xl font-bold text-gray-900 pl-4 relative">
                    <span
                        class="absolute left-0 top-1/2 transform -translate-y-1/2 w-1 h-5 bg-[#6E76C1] rounded-full"></span>
                    Мои классы
                </h3>
            </div>

            <div class="mb-6 flex flex-wrap gap-4 sm:gap-6">
                <div class="flex-1 min-w-[200px]">
                    <label for="filter-name" class="block text-sm font-medium text-gray-700 mb-1">Фильтр по названию</label>
                    <input type="text" id="filter-name" placeholder="Например: Математика"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-[#6E76C1] focus:border-transparent transition duration-200">
                </div>

                <div class="self-end">
                    <button id="clear-filter" type="button"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md transition duration-200">
                        Сбросить
                    </button>
                </div>
            </div>

            @if ($paginatedItems->isEmpty() && empty($filter))
                <div class="text-center py-10 bg-gray-50 border border-dashed border-gray-300 rounded-lg">
                    <i class="fas fa-school text-gray-300 text-4xl mb-4"></i>
                    <h4 class="text-lg font-semibold text-gray-600">У вас пока нет классов</h4>
                    <p class="text-gray-500 mt-1">Ожидайте приглашения или создайте новый класс.</p>
                </div>
            @elseif ($paginatedItems->isEmpty() && !empty($filter))
                <div class="text-center py-10 bg-gray-50 border border-dashed border-gray-300 rounded-lg">
                    <i class="fas fa-search text-gray-300 text-4xl mb-4"></i>
                    <h4 class="text-lg font-semibold text-gray-600">Результаты не найдены</h4>
                    <p class="text-gray-500 mt-1">Попробуйте изменить параметры поиска</p>
                </div>
            @else
                <div id="classes-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($paginatedItems as $class)
                        <div class="class-card bg-white rounded-lg shadow-sm overflow-hidden border-l-4 border-[#6E76C1] transition-all duration-300 hover:shadow-md"
                            data-name="{{ $class->name }}" data-teacher="{{ $class->teacher->name ?? 'none' }}">
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
                                        <button type="button"
                                            class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100 text-sm flex items-center delete-button"
                                            data-id="{{ $class->id }}" data-name="{{ $class->name }}"
                                            data-type="exitStudent">
                                            <i class="fas fa-door-open mr-2 text-red-600"></i></i> Выйти
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

                                <div class="flex items-center gap-2 text-sm text-gray-500 mt-2">
                                    <i class="fas fa-chalkboard-teacher text-[#6E76C1]"></i>
                                    <span>{{ $class->teacher->name ?? 'Не указан' }}
                                        {{ $class->teacher->surname ?? '' }}</span>
                                </div>

                                @if ($class->description)
                                    <p class="text-base text-gray-500 truncate mt-1" style="white-space: nowrap;">
                                        <i
                                            class="fas fa-info-circle mr-1"></i>{{ Str::limit($class->description, 100, '...') }}
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
                <div class="mt-6 flex justify-center">
                    <nav class="inline-flex rounded-md overflow-hidden shadow-sm">
                        @if ($paginatedItems->onFirstPage())
                            <span class="px-4 py-2 bg-gray-100 text-gray-400 cursor-not-allowed flex items-center gap-1">
                                <i class="fas fa-chevron-left"></i> Назад
                            </span>
                        @else
                            <a href="{{ $paginatedItems->previousPageUrl() }}"
                                class="px-4 py-2 bg-white text-gray-700 border border-gray-300 hover:bg-[#6E76C1] hover:text-white flex items-center gap-1 transition duration-200">
                                <i class="fas fa-chevron-left"></i> Назад
                            </a>
                        @endif

                        @foreach ($paginatedItems->getUrlRange(1, $paginatedItems->lastPage()) as $page => $url)
                            @if ($page == $paginatedItems->currentPage())
                                <span class="px-4 py-2 bg-[#6E76C1] text-white font-medium">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}"
                                    class="px-4 py-2 bg-white text-gray-700 border border-gray-300">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                        @if ($paginatedItems->hasMorePages())
                            <a href="{{ $paginatedItems->nextPageUrl() }}"
                                class="px-4 py-2 bg-white text-gray-700 border border-gray-300">
                                Вперед <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="px-4 py-2 bg-gray-100 text-gray-400 cursor-not-allowed flex items-center gap-1">
                                Вперед <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif
                    </nav>
                </div>
            @endif
        </div>

        <div id="invitations-modal" class="modal hidden">
            <div class="modal-content">
                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Входящие приглашения</h3>
                    <button type="button" id="close-invitations-modal"
                        class="text-gray-500 hover:text-gray-700 text-xl font-semibold">&times;</button>
                </div>

                <div class="modal-body px-6 py-4 bg-gray-50 rounded-b-lg">
                    @if ($invitations->isEmpty())
                        <p class="text-gray-500 text-center italic py-8">У вас нет активных приглашений.</p>
                    @else
                        <ul id="invitations-list" class="divide-y divide-gray-300 max-h-96 overflow-y-auto">
                            @foreach ($invitations as $invitation)
                                <li class="py-3 px-4">
                                    <div class="min-w-0 flex-grow">
                                        <h3
                                            class="text-lg font-semibold text-gray-900 cursor-default transition-colors truncate flex items-center gap-2">
                                            <i class="fas fa-school text-gray-600"></i>
                                            {{ $invitation->class->name }}
                                        </h3>
                                        <p class="text-sm text-gray-600 mt-1 truncate flex items-center gap-2">
                                            <i class="fas fa-chalkboard-teacher text-gray-500"></i>
                                            Преподаватель: <span
                                                class="font-medium">{{ optional($invitation->class->teacher)->name . ' ' . optional($invitation->class->teacher)->surname ?? 'Не указан' }}</span>
                                        </p>
                                    </div>

                                    <div class="flex gap-4 justify-center md:justify-start mt-3 md:mt-0 flex-shrink-0">
                                        <form action="{{ route('invitations.accept', $invitation->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 focus:ring-green-300 focus:outline-none focus:ring-2 focus:ring-offset-1
            text-white px-3 py-1.5 rounded-md font-semibold transition whitespace-nowrap text-sm">
                                                Принять
                                            </button>
                                        </form>

                                        <form action="{{ route('invitations.decline', $invitation->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 focus:ring-red-300 focus:outline-none focus:ring-2 focus:ring-offset-1
            text-white px-3 py-1.5 rounded-md font-semibold transition whitespace-nowrap text-sm">
                                                Отклонить
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <button id="close-invitations-bottom"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md mt-4 w-full">
                    Закрыть
                </button>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById("invitations-modal");
            const openBtn = document.getElementById("open-invitations-modal");
            const closeBtns = document.querySelectorAll("#close-invitations-modal, #close-invitations-bottom");

            openBtn.addEventListener("click", () => {
                modal.classList.add("open");
                modal.classList.remove("hidden");
            });

            closeBtns.forEach((btn) => {
                btn.addEventListener("click", () => {
                    modal.classList.remove("open");
                    setTimeout(() => {
                        modal.classList.add("hidden");
                    }, 300);
                });
            });

            modal.addEventListener("click", (e) => {
                if (e.target === modal) {
                    modal.classList.remove("open");
                    setTimeout(() => {
                        modal.classList.add("hidden");
                    }, 300);
                }
            });
            const filterName = document.getElementById("filter-name");

            if (filterName) {
                filterName.addEventListener("input", function() {
                    const query = this.value.trim().toLowerCase();
                    document.querySelectorAll(".class-card").forEach(card => {
                        const name = card.dataset.name.toLowerCase();
                        card.style.display = name.includes(query) ? "block" : "none";
                    });
                });

                document.getElementById("clear-filter").addEventListener("click", function() {
                    filterName.value = "";
                    document.querySelectorAll(".class-card").forEach(card => {
                        card.style.display = "block";
                    });
                });
            }

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

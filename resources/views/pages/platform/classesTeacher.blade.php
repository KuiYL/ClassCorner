@extends('pages.platform.layout', ['activePage' => 'classes', 'title' => 'Классы', 'quick_action' => 'classes.create'])
@section('content')
    <div class="container-fluid py-6 px-md-4">
        <div class="bg-white rounded-lg shadow-md border-l-4 border-[#6E76C1] mb-6 overflow-hidden">
            <div class="p-6 flex justify-between items-center bg-[#EEF2FF]">
                <div>
                    <h3 class="text-xl font-semibold text-[#555EB1]">Создать новый класс</h3>
                    <p class="mt-2 text-sm text-[#6E76C1] font-medium">
                        Добавьте студентов, материалы и задания
                    </p>
                </div>

                <a href="{{ route('classes.create', ['return_url' => url()->current()]) }}"
                    class="inline-flex items-center px-4 py-2 bg-[#6E76C1] hover:bg-[#555EB1] text-white font-medium rounded-md transition duration-200">
                    <i class="fas fa-plus mr-2"></i> Новый класс
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
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#6E76C1] focus:border-transparent transition duration-200">
                </div>

                <div class="self-end">
                    <button id="clear-filter" type="button"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md transition duration-200">
                        Сбросить
                    </button>
                </div>
            </div>

            @if ($paginatedItems->isEmpty())
                <div class="text-center py-10 bg-gray-50 border border-dashed border-gray-300 rounded-lg">
                    <i class="fas fa-school text-gray-300 text-4xl mb-4"></i>
                    <h4 class="text-lg font-semibold text-gray-600">У вас пока нет классов</h4>
                    <p class="text-gray-500 mt-1">Нажмите "Новый класс", чтобы начать</p>
                </div>
            @else
                <div id="classes-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($paginatedItems as $class)
                        <div class="class-card bg-white rounded-lg shadow-sm overflow-hidden border-l-4 border-[#6E76C1] transition-all duration-300 hover:shadow-md"
                            data-name="{{ $class->name }}" data-teacher="{{ $class->teacher->id ?? 'none' }}">

                            <div class="p-4 bg-[#6E76C1] flex justify-between items-start relative">
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
                                            data-id="{{ $class->id }}" data-name="{{ $class->name }}" data-type="class">
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
                                        {{ $class->description }}
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

    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
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

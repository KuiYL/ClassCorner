@extends('pages.platform.layout', ['activePage' => 'tasks', 'title' => 'Список заданий', 'quick_action' => 'assignments.create'])
@section('content')
    <div class="container-fluid py-6 px-md-4">
        <div class="bg-white rounded-lg shadow-md border-l-4 border-[#6E76C1] mb-6 overflow-hidden">
            <div class="p-6 flex justify-between items-center bg-[#EEF2FF]">
                <div>
                    <h3 class="text-xl font-semibold text-[#555EB1]">Задания для проверки и проверенные</h3>
                    <p class="mt-2 text-sm text-[#6E76C1] font-medium">
                        Проверьте ответы учеников и выставите оценки
                    </p>
                </div>

                <a href="{{ route('assignments.to.grade') }}"
                    class="inline-flex items-center px-4 py-2 bg-[#6E76C1] hover:bg-[#555EB1] text-white font-medium rounded-md transition duration-200">
                    <i class="fas fa-arrow-right mr-2"></i> Перейти
                </a>
            </div>
        </div>

        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">Мои задания</h3>
            <a href="{{ route('assignments.create') }}"
                class="inline-flex items-center px-4 py-2 bg-[#6E76C1] hover:bg-[#616EBD] text-white font-medium rounded-md transition duration-200">
                <i class="fas fa-tasks mr-2"></i> Новое задание
            </a>
        </div>

        <div class="mb-6 flex flex-wrap gap-4 sm:gap-6">
            <div class="flex-1 min-w-[200px]">
                <label for="filter-class" class="block text-sm font-medium text-gray-700 mb-1">Класс</label>
                <select id="filter-class"
                    class="form-select w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#6E76C1] focus:border-transparent">
                    <option value="">Все классы</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->name }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 min-w-[200px]">
                <label for="filter-type" class="block text-sm font-medium text-gray-700 mb-1">Тип вопроса</label>
                <select id="filter-type"
                    class="form-select w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#6E76C1] focus:border-transparent">
                    <option value="">Все типы</option>
                    <option value="Текстовый ответ">Текстовый ответ</option>
                    <option value="Загрузка файла">Загрузка файла</option>
                    <option value="Один выбор">Один выбор</option>
                    <option value="Множественный выбор">Множественный выбор</option>
                </select>
            </div>

            <div class="self-end">
                <button id="clear-filter" type="button"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md transition duration-200">
                    Сбросить фильтр
                </button>
            </div>
        </div>

        <div id="no-results" class="hidden mb-6 p-6 bg-gray-50 border border-dashed border-gray-300 rounded-lg text-center">
            <i class="fas fa-book-open text-gray-300 text-3xl mb-2"></i>
            <p class="text-gray-500 italic">Нет заданий, соответствующих фильтрам</p>
        </div>

        <div id="assignments-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($paginatedItems as $assignment)
                @php
                    $typeTranslations = [
                        'file_upload' => 'Загрузка файла',
                        'multiple_choice' => 'Множественный выбор',
                        'single_choice' => 'Один выбор',
                        'text' => 'Текстовый ответ',
                    ];

                    $decodedOptions = json_decode($assignment->options, true);
                    $questionTypes = [];

                    if (!empty($decodedOptions)) {
                        foreach ($decodedOptions as $question) {
                            $questionTypes[] =
                                $typeTranslations[$question['type']] ??
                                ucfirst(str_replace('_', ' ', $question['type']));
                        }
                        $questionTypes = array_unique($questionTypes);
                    }

                    $typesText = !empty($questionTypes) ? implode(', ', $questionTypes) : 'Отсутствуют';
                @endphp

                <div class="assignment-card bg-white rounded-lg shadow-sm border-l-4 border-[#6E76C1] overflow-hidden"
                    data-name="{{ $assignment->title }}" data-types='["Текстовый ответ", "Множественный выбор"]'>

                    <div class="p-4 bg-white border-b border-gray-200">
                        <h4 class="font-semibold text-gray-900 truncate">{{ $assignment->title }}</h4>
                    </div>

                    <div class="p-4 pt-3 pb-3 space-y-2 text-sm text-gray-600">
                        <p class="text-xs text-gray-500">Дедлайн:
                            {{ \Carbon\Carbon::parse($assignment->due_date)->format('d.m.Y в H:i') }}
                            @if (\Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($assignment->due_date)))
                                <span class="ml-2 px-2 py-1 text-xs font-medium text-white bg-red-600 rounded-full">
                                    Срок сдачи истёк
                                </span>
                            @endif
                        </p>

                        <p data-role="class"><i
                                class="fas fa-chalkboard-teacher mr-1"></i>{{ $assignment->class->name ?? 'Без класса' }}
                        </p>

                        <p><strong>Тип:</strong> {{ $typesText }}</p>

                        <p class="text-sm text-gray-600 mt-2 line-clamp-2">
                            {{ $assignment->description ?: 'Нет описания' }}
                        </p>
                    </div>

                    <div class="p-4 pt-2 pb-3 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                        <a href="{{ route('assignments.show', ['id' => $assignment->id, 'return_url' => url()->current()]) }}"
                            class="inline-flex items-center gap-2 text-sm px-4 py-1.5 text-[#6E76C1] border border-[#6E76C1] rounded-md hover:bg-[#6E76C1] hover:text-white transition duration-200">
                            <i class="fas fa-eye text-sm"></i>
                            <span>Просмотр</span>
                        </a>
                        <a href="{{ route('assignments.edit', ['id' => $assignment->id, 'return_url' => url()->current()]) }}"
                            class="inline-flex items-center gap-2 text-sm text-[#6E76C1] hover:text-[#616EBD]">
                            <i class="fas fa-edit"></i>
                            <span>Изменить</span>
                        </a>
                        <button type="button"
                            class="inline-flex items-center gap-2 text-sm text-red-600 hover:text-red-800 delete-button"
                            data-id="{{ $assignment->id }}" data-name="{{ $assignment->title }}" data-type="assignment">
                            <i class="fas fa-trash text-sm"></i>
                            <span>Удалить</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($paginatedItems->isEmpty())
            <div class="text-center py-8 bg-white rounded-lg shadow-sm border border-dashed border-gray-300">
                <i class="fas fa-book-open text-gray-300 text-4xl mb-2"></i>
                <p class="text-gray-500">У вас пока нет заданий</p>
                <p class="text-sm text-gray-400 mt-1">Создайте новое задание выше</p>
            </div>
        @endif

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
                        <a href="{{ $url }}" class="px-4 py-2 bg-white text-gray-700 border border-gray-300">
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
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cards = document.querySelectorAll(".assignment-card");
            const filterClass = document.getElementById("filter-class");
            const filterType = document.getElementById("filter-type");
            const noResultsMessage = document.getElementById("no-results");

            function filterAssignments() {
                const selectedClass = (filterClass?.value || '').trim().toLowerCase();
                const selectedType = (filterType?.value || '').trim();

                let hasVisibleCard = false;

                cards.forEach(card => {
                    const cardClassElement = card.querySelector("[data-role='class']");
                    const cardClass = (cardClassElement ? cardClassElement.textContent.trim()
                        .toLowerCase() : '');

                    const rawTypes = card.dataset.types;
                    const cardTypes = rawTypes ? rawTypes.split(',').map(t => t.trim().toLowerCase()) : [];

                    const matchClass = !selectedClass || cardClass.includes(selectedClass);
                    const matchType = !selectedType || cardTypes.some(type => type.includes(selectedType
                        .toLowerCase()));

                    if (matchClass && matchType) {
                        card.style.display = "block";
                        hasVisibleCard = true;
                    } else {
                        card.style.display = "none";
                    }
                });

                if (hasVisibleCard) {
                    noResultsMessage.classList.add("hidden");
                } else {
                    noResultsMessage.classList.remove("hidden");
                }
            }

            filterClass?.addEventListener("change", filterAssignments);
            filterType?.addEventListener("change", filterAssignments);

            document.getElementById("clear-filter")?.addEventListener("click", function() {
                filterClass.value = "";
                filterType.value = "";

                cards.forEach(card => card.style.display = "block");
                noResultsMessage.classList.add("hidden");
            });

            filterAssignments();
        });
    </script>
@endsection

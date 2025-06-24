@extends('pages.platform.layout', ['activePage' => 'tasks', 'title' => 'Список заданий', 'quick_action' => 'assignments.create'])
@section('content')
    <div class="container-fluid py-6 px-md-4">
        <div class="bg-white rounded-lg shadow-md border-l-4 border-[#6E76C1] mb-6 overflow-hidden">
            <div class="p-6 flex justify-between items-center bg-[#EEF2FF]">
                <div>
                    <h3 class="text-xl font-semibold text-[#555EB1]">Ваши задания</h3>
                    <p class="mt-2 text-sm text-[#6E76C1] font-medium">
                        Выполняйте задания по вашим классам
                    </p>
                </div>
                <a href="{{ route('user.classes') }}"
                    class="inline-flex items-center px-4 py-2 bg-[#6E76C1] hover:bg-[#555EB1] text-white font-medium rounded-md transition duration-200">
                    <i class="fas fa-arrow-right mr-2"></i> Перейти к классам
                </a>
            </div>
        </div>

        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">Задания на выполнение</h3>
        </div>

        <div class="mb-6 flex flex-wrap gap-4 sm:gap-6">
            <div class="flex-1 min-w-[200px]">
                <label for="filter-status" class="block text-sm font-medium text-gray-700 mb-1">Статус</label>
                <select id="filter-status"
                    class="form-select w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#6E76C1] focus:border-transparent">
                    <option value="">Все</option>
                    <option value="Не выполнено">Не выполнено</option>
                    <option value="На проверке">На проверке</option>
                    <option value="Выполнено">Выполнено</option>
                </select>
            </div>

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
                    <option value="Загрузка файла">Загрузка файла</option>
                    <option value="Множественный выбор">Множественный выбор</option>
                    <option value="Один выбор">Один выбор</option>
                    <option value="Текстовый ответ">Текстовый ответ</option>
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

        @php
            use Carbon\Carbon;
            $now = Carbon::now();
        @endphp

        <div id="assignments-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($assignments as $item)
                @php
                    $dueDate = Carbon::parse($item['assignment']->due_date);
                    $status = $item['submission']->status ?? 'not_submitted';
                @endphp

                @if ($dueDate->lt($now))
                    @continue
                @endif

                @php
                    $statusLabels = [
                        'not_submitted' => 'Не выполнено',
                        'submitted' => 'На проверке',
                        'graded' => 'Выполнено',
                    ];

                    $statusStyles = [
                        'not_submitted' => 'text-red-500 bg-red-100 border-red-500',
                        'submitted' => 'text-yellow-500 bg-yellow-100 border-yellow-600',
                        'graded' => 'text-green-500 bg-green-100 border-green-500',
                    ];
                    $statusStyle = $statusStyles[$status] ?? 'text-gray-500 bg-gray-100 border-gray-500';

                    $typeTranslations = [
                        'file_upload' => 'Загрузка файла',
                        'multiple_choice' => 'Множественный выбор',
                        'single_choice' => 'Один выбор',
                        'text' => 'Текстовый ответ',
                    ];

                    $decodedOptions = json_decode($item['assignment']->options, true);
                    $questionTypes = !empty($decodedOptions)
                        ? array_unique(
                            array_map(fn($q) => $typeTranslations[$q['type']] ?? 'Неизвестно', $decodedOptions),
                        )
                        : [];
                @endphp

                <div class="assignment-card bg-white rounded-lg shadow-sm border-l-4 {{ $statusStyle }} overflow-hidden">
                    <div class="p-4 bg-white border-b border-gray-200 flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 truncate flex items-center">
                                <i
                                    class="fas fa-book-open mr-2 text-[#6E76C1] group-hover:scale-110 transition-transform duration-200"></i>
                                {{ $item['assignment']->title }}
                            </h4>
                            <p class="text-xs text-gray-500 mt-1">Дедлайн: {{ $dueDate->format('d.m.Y H:i') }}</p>
                        </div>

                        <span
                            class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full {{ $statusStyle }}">
                            {{ $statusLabels[$status] ?? 'Неизвестный статус' }}
                        </span>
                    </div>

                    <div class="p-4 text-sm text-gray-600">
                        <p class="mt-1"><i class="fas fa-chalkboard-teacher mr-1 text-[#6E76C1]"></i> <span
                                class="font-medium">{{ $item['class']->name }}</span></p>
                        <p class="mt-1"><i class="fas fa-tags text-[#6E76C1] mr-1"></i><span
                                class="font-medium">{{ implode(', ', $questionTypes) }}</span></p>
                        <p class="mt-2 text-gray-700 truncate" style="white-space: nowrap;">
                            <i class="fas fa-info-circle mr-1 text-gray-500"></i>
                            {{ $item['assignment']->description ?: 'Нет описания' }}
                        </p>
                    </div>

                    <div class="p-3 bg-gray-50 border-t border-gray-100 flex justify-end">
                        @if ($status === 'not_submitted')
                            <a href="{{ route('assignments.show', $item['assignment']->id) }}"
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
            @endforeach
        </div>


        @if ($assignments->isEmpty())
            <div class="text-center py-8 bg-white rounded-lg shadow-sm border border-dashed border-gray-300">
                <i class="fas fa-book-open text-gray-300 text-4xl mb-2"></i>
                <p class="text-gray-500">У вас пока нет заданий</p>
            </div>
        @endif
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusFilter = document.getElementById('filter-status');
            const classFilter = document.getElementById('filter-class');
            const typeFilter = document.getElementById('filter-type');
            const clearFilterBtn = document.getElementById('clear-filter');
            const assignmentsGrid = document.getElementById('assignments-grid');
            const noResults = document.getElementById('no-results');

            const assignmentCards = Array.from(assignmentsGrid.getElementsByClassName('assignment-card'));

            function filterAssignments() {
                const selectedStatus = statusFilter.value;
                const selectedClass = classFilter.value;
                const selectedType = typeFilter.value;

                let hasVisibleCards = false;

                assignmentCards.forEach(card => {
                    const cardStatus = card.querySelector('span').innerText.trim();
                    const cardClass = card.querySelector('p:nth-of-type(2) span').innerText.trim();
                    const cardType = card.querySelector('p:nth-of-type(3) span').innerText.trim();

                    const statusMatch = !selectedStatus || cardStatus === selectedStatus;
                    const classMatch = !selectedClass || cardClass === selectedClass;
                    const typeMatch = !selectedType || cardType.includes(selectedType);

                    if (statusMatch && classMatch && typeMatch) {
                        card.style.display = 'block';
                        hasVisibleCards = true;
                    } else {
                        card.style.display = 'none';
                    }
                });

                noResults.classList.toggle('hidden', hasVisibleCards);
            }

            [statusFilter, classFilter, typeFilter].forEach(filter =>
                filter.addEventListener('change', filterAssignments)
            );

            clearFilterBtn.addEventListener('click', function() {
                statusFilter.value = '';
                classFilter.value = '';
                typeFilter.value = '';
                filterAssignments();
            });
        });
    </script>
@endsection

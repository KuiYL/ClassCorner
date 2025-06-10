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

        <div id="assignments-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($assignments as $item)
                @php
                    $status = $item['submission']->status ?? 'not_submitted';
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
                    $typeTranslations = [
                        'file_upload' => 'Загрузка файла',
                        'multiple_choice' => 'Множественный выбор',
                        'single_choice' => 'Один выбор',
                        'text' => 'Текстовый ответ',
                    ];
                    $statusStyle = $statusStyles[$status] ?? 'text-gray-500 bg-gray-100 border-gray-500';
                    $decodedOptions = json_decode($item['assignment']->options, true);
                    $questionTypes = !empty($decodedOptions)
                        ? array_unique(
                            array_map(fn($q) => $typeTranslations[$q['type']] ?? 'Неизвестно', $decodedOptions),
                        )
                        : [];
                @endphp

                <div class="assignment-card bg-white rounded-lg shadow-sm border-l-4 {{ $statusStyle }} overflow-hidden">
                    <!-- Заголовок и статус -->
                    <div class="p-4 bg-white border-b border-gray-200">
                        <h4 class="font-semibold text-gray-900 truncate">{{ $item['assignment']->title }}</h4>
                        <span class="inline-block mt-2 px-2 py-1 text-sm font-medium rounded {{ $statusStyle }}">
                            {{ $statusLabels[$status] ?? 'Неизвестный статус' }}
                        </span>
                    </div>

                    <!-- Основная информация -->
                    <div class="p-4 text-sm text-gray-600">
                        <p class="text-xs text-gray-500">Дедлайн: {{ $item['assignment']->due_date }}</p>
                        <p class="mt-1">Класс: <span class="font-medium">{{ $item['class']->name }}</span></p>
                        <p class="mt-1">Тип: <span class="font-medium">{{ implode(', ', $questionTypes) }}</span></p>
                        <p class="mt-2 text-gray-700 line-clamp-2">{{ $item['assignment']->description }}</p>
                    </div>

                    <!-- Действия -->
                    <div class="p-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                        <a href="{{ route('assignments.show', $item['assignment']->id) }}"
                            class="inline-flex items-center gap-2 text-sm px-4 py-1.5 text-[#6E76C1] border border-[#6E76C1] rounded-md hover:bg-[#6E76C1] hover:text-white transition duration-200">
                            <i class="fas fa-eye text-sm"></i> Перейти
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const clearFilterBtn = document.getElementById('clear-filter');
            clearFilterBtn.addEventListener('click', function() {
                document.getElementById('filter-status').value = '';
                document.getElementById('filter-class').value = '';
                document.getElementById('filter-type').value = '';
                // Optionally trigger filtering logic here
            });
        });
    </script>
@endsection

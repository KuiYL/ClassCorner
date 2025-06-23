@extends('pages.platform.layout', ['activePage' => 'classes', 'title' => $class->name, 'quick_action' => 'null'])
@section('content')
    <div class="container-fluid py-6 px-md-4">

        <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
            <div class="p-6 bg-gradient-to-r from-[#6E76C1] to-[#9CA4F2] text-white rounded-t-lg">
                <h2 class="text-3xl font-bold leading-tight">{{ $class->name }}</h2>
                <p class="mt-2 text-sm opacity-90">{{ $class->description }}</p>
            </div>

            <div class="px-6 py-4 flex flex-wrap gap-6 text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Преподаватель:</span>
                    <strong>{{ $class->teacher->name }} {{ $class->teacher->surname }}</strong>
                </div>

                <div class="flex items-center gap-2">
                    <i class="fas fa-book-open"></i>
                    <span>Задания:</span>
                    <strong>{{ count($assignments) }}</strong>
                </div>

                <div class="flex items-center gap-2">
                    <i class="fas fa-users"></i>
                    <span>Выполненные задания:</span>
                    <strong>{{ $completedAssignments }}</strong>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h3
                    class="text-2xl font-semibold text-gray-900 mb-4 relative inline-block after:content-[''] after:absolute after:left-0 after:bottom-[-4px] after:w-12 after:h-0.5 after:bg-[#6E76C1]">
                    Мои задания
                </h3>
            </div>

            <div class="mb-4 flex flex-wrap gap-3 items-center">
                <label for="filter-status" class="text-sm font-medium text-gray-700">Статус:</label>
                <select id="filter-status" class="form-select w-auto rounded-md border-gray-300">
                    <option value="">Все</option>
                    <option value="not_submitted">Не выполнено</option>
                    <option value="submitted">На проверке</option>
                    <option value="graded">Выполнено</option>
                </select>
                <label for="filter-type" class="text-sm font-medium text-gray-700">Тип:</label>
                <select id="filter-type" class="form-select w-auto rounded-md border-gray-300">
                    <option value="">Все</option>
                    <option value="file_upload">Загрузка файла</option>
                    <option value="multiple_choice">Множественный выбор</option>
                    <option value="single_choice">Один выбор</option>
                    <option value="text">Текстовый ответ</option>
                </select>
                <button type="button" id="clear-filter"
                    class="btn btn-outline-secondary text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-md">
                    <i class="fas fa-times mr-1"></i> Очистить
                </button>
            </div>
            <div id="assignments-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @if (!empty($assignments))
                    @foreach ($assignments as $assignmentData)
                        @php
                            $assignment = is_array($assignmentData) ? $assignmentData['assignment'] : $assignmentData;
                            $submission = is_array($assignmentData) ? $assignmentData['submission'] : null;
                            $averageGrade = $assignmentData['average_grade'] ?? null;

                            $status = $submission?->status ?? 'not_submitted';
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

                            $statusLabel = $statusLabels[$status] ?? 'Неизвестный статус';
                            $statusStyle = $statusStyles[$status] ?? 'text-gray-500 bg-gray-100 border-gray-500';

                            $decodedOptions = json_decode($assignment->options, true);
                            $typeTranslations = [
                                'file_upload' => 'Загрузка файла',
                                'multiple_choice' => 'Множественный выбор',
                                'single_choice' => 'Один выбор',
                                'text' => 'Текстовый ответ',
                            ];

                            $questionTypes = !empty($decodedOptions)
                                ? array_unique(array_map(fn($q) => $q['type'] ?? 'unknown', $decodedOptions))
                                : [];
                        @endphp

                        @php
                            $dueDate = \Carbon\Carbon::parse($assignment->due_date);
                            $now = \Carbon\Carbon::now();
                            $isOverdue = $now->gt($dueDate);
                        @endphp

                        <div class="card h-full shadow-sm hover:shadow-md transition-shadow duration-200 border border-gray-200 rounded-lg overflow-hidden"
                            data-status="{{ $status }}" data-type="{{ implode(',', $questionTypes) }}">
                            <div class="p-4 bg-white">
                                <div class="flex justify-between items-start">
                                    <h4 class="font-bold text-gray-900">{{ $assignment->title }}</h4>
                                    <span
                                        class="inline-block px-2 py-1 text-xs font-medium rounded-full {{ $statusStyle }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mt-2 truncate">
                                    {{ $assignment->description ?: 'Нет описания' }}
                                </p>
                                <div class="mt-4 text-sm text-gray-500 space-y-2">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-calendar-check text-[#6E76C1]"></i>
                                            <strong>Срок:</strong>
                                            {{ $dueDate->format('d.m.Y H:i') }}
                                        </div>
                                        @if ($isOverdue)
                                            <div class="text-red-600 text-xs font-semibold select-none">
                                                Срок сдачи прошёл. Отправка невозможна.
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-star text-[#6E76C1]"></i>
                                        <strong>Оценка:</strong>
                                        @if ($status === 'graded')
                                            {{ $averageGrade }} баллов
                                        @elseif ($status === 'submitted')
                                            На проверке
                                        @else
                                            Не выполнено
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-tags text-[#6E76C1]"></i>
                                        <strong>Типы вопросов:</strong>
                                        <ul class="flex flex-wrap gap-1">
                                            @foreach ($questionTypes as $type)
                                                <li
                                                    class="inline-block px-2 py-1 text-xs font-medium rounded bg-[#6E76C1]/10 text-[#6E76C1]">
                                                    {{ $typeTranslations[$type] ?? 'Неизвестный тип' }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                                @if ($status === 'not_submitted')
                                    @if ($isOverdue)
                                        <button disabled
                                            class="btn cursor-not-allowed text-red-400 border-red-400 rounded-md px-3 py-1 inline-flex items-center"
                                            title="Срок сдачи прошёл, переход невозможен">
                                            <i class="fas fa-arrow-right mr-1"></i> Перейти
                                        </button>
                                    @else
                                        <a href="{{ route('assignments.show', $assignment->id) }}"
                                            class="btn outline-none text-red-600 border-red-600 hover:bg-red-600 hover:text-gray-100 rounded-md px-3 py-1 inline-flex items-center">
                                            <i class="fas fa-arrow-right mr-1"></i> Перейти
                                        </a>
                                    @endif
                                @elseif ($status === 'submitted')
                                    <span
                                        class="inline-flex items-center text-sm text-yellow-600 cursor-default px-3 py-1 rounded-md border border-yellow-600 bg-yellow-50">
                                        На проверке <i class="fas fa-clock ml-1"></i>
                                    </span>
                                @else
                                    <a href="{{ route('assignment.result', ['id' => $submission->id]) }}"
                                        class="btn outline-none text-green-600 border-green-600 hover:bg-green-600 hover:text-gray-100 rounded-md px-3 py-1 inline-flex items-center">
                                        <i class="fas fa-eye mr-1"></i> Результаты
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div
                        class="col-span-full text-center py-6 bg-white rounded-lg shadow-sm border border-dashed border-gray-300">
                        <i class="fas fa-book-open text-gray-400 text-4xl mb-2"></i>
                        <p class="text-gray-600 italic text-lg mb-1">В этом классе пока нет заданий на выполнение.</p>
                        <p class="text-gray-500">Пожалуйста, подождите, пока преподаватель добавит новые задания.</p>
                    </div>
                @endif
            </div>
            <div id="no-results"
                class="hidden col-span-full text-center py-6 bg-white rounded-lg shadow-sm border border-dashed border-gray-300">
                <i class="fas fa-search text-gray-400 text-4xl mb-2"></i>
                <p class="text-gray-500">Нет заданий, соответствующих вашему запросу.</p>
            </div>
        </div>
    </div>

    <script>
        function applyFilters() {
            const statusFilter = document.getElementById('filter-status').value;
            const typeFilter = document.getElementById('filter-type').value;

            document.querySelectorAll('#assignments-grid .card').forEach(card => {
                const matchesStatus = !statusFilter || card.dataset.status === statusFilter;
                const matchesType = !typeFilter || card.dataset.type.includes(typeFilter);

                if (matchesStatus && matchesType) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            const visibleCards = Array.from(document.querySelectorAll('#assignments-grid .card')).some(card => card.style
                .display === 'block');
            document.getElementById('no-results').classList.toggle('hidden', visibleCards);
        }

        document.getElementById('filter-status').addEventListener('change', applyFilters);
        document.getElementById('filter-type').addEventListener('change', applyFilters);
        document.getElementById('clear-filter').addEventListener('click', () => {
            document.getElementById('filter-status').value = '';
            document.getElementById('filter-type').value = '';
            applyFilters();
        });
    </script>

@endsection

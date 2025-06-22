@extends('pages.platform.layout', ['activePage' => 'null', 'title' => 'Задания для проверки и проверенные', 'quick_action' => 'null'])
@section('content')
    <div class="container-fluid py-6 px-md-4">
        <div class="bg-white rounded-lg shadow-md border-l-4 border-[#6E76C1] mb-6 overflow-hidden">
            <div class="p-6 flex justify-between items-center bg-[#EEF2FF]">
                <div>
                    <h3 class="text-xl font-semibold text-[#555EB1]"> <i class="fas fa-tasks mr-3 text-[#555EB1]"></i>Задания
                        для проверки и проверенные</h3>
                    <p class="mt-2 text-sm text-[#6E76C1] font-medium">
                        Фильтруйте и проверяйте ответы учеников.
                    </p>
                </div>

                <a href="{{ route('user.assignments') }}"
                    class="inline-flex items-center px-4 py-2 bg-[#6E76C1] hover:bg-[#555EB1] text-white font-medium rounded-md transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i> Перейти назад
                </a>
            </div>
        </div>

        <div class="mb-8 w-full">
            <div class="flex flex-wrap gap-4 sm:gap-6">
                <div class="flex-1 min-w-[200px]">
                    <label for="filter-title" class="block text-sm font-medium text-gray-700 mb-1">Фильтр по
                        названию</label>
                    <input type="text" id="filter-title" placeholder="Например: Задание 1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-[#6E76C1] focus:border-transparent transition duration-200"
                        oninput="filterAssignments()">
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label for="filter-status" class="block text-sm font-medium text-gray-700 mb-1">Фильтр по
                        статусу</label>
                    <select id="filter-status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#6E76C1] focus:border-transparent transition duration-200"
                        onchange="filterAssignments()">
                        <option value="">Все</option>
                        <option value="На проверке">На проверке</option>
                        <option value="Проверено">Проверено</option>
                    </select>
                </div>

                <div class="self-end">
                    <button id="clear-filters" type="button"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md transition duration-200"
                        onclick="clearFilters()">
                        Сбросить
                    </button>
                </div>
            </div>
        </div>

        <div id="assignments-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($assignmentsToGrade as $assignment)
                @php
                    $status = $assignment->status === 'submitted' ? 'На проверке' : 'Проверено';

                    $statusLabels = [
                        'submitted' => 'На проверке',
                        'graded' => 'Проверено',
                    ];

                    $statusStyles = [
                        'submitted' => 'text-yellow-500 bg-yellow-100 border-yellow-600',
                        'graded' => 'text-green-500 bg-green-100 border-green-500',
                    ];

                    $statusStyle = $statusStyles[$assignment->status] ?? 'text-gray-500 bg-gray-100 border-gray-500';
                @endphp

                <div class="assignment-card bg-white rounded-lg shadow-sm border-l-4 {{ $statusStyle }} overflow-hidden"
                    data-title="{{ strtolower($assignment->assignment->title) }}" data-status="{{ $status }}"
                    data-class="{{ strtolower($assignment->assignment->class->name ?? '') }}"
                    data-assignment-class-name="{{ strtolower($assignment->assignment->class->name ?? '') }}">
                    <div class="p-4 bg-white border-b border-gray-200">
                        <h4 class="font-semibold text-gray-900 truncate">
                            {{ Str::limit($assignment->assignment->title, 40) }}</h4>
                        <span class="inline-block mt-2 px-2 py-1 text-sm font-medium rounded {{ $statusStyle }}">
                            {{ $statusLabels[$assignment->status] ?? 'Неизвестный статус' }}
                        </span>
                    </div>

                    <div class="p-4 text-sm text-gray-600">
                        <p class="text-xs text-gray-500">Дедлайн:
                            {{ \Carbon\Carbon::parse($assignment->assignment->due_date)->format('d.m.Y в H:i') }}
                        </p>
                        <p class="mt-1">Класс:
                            <span
                                class="font-medium">{{ optional($assignment->assignment->class)->name ?? 'Не указан' }}</span>
                        </p>
                        <p class="mt-1">Ученик:
                            <span class="font-medium">
                                {{ $assignment->user?->name . ' ' . $assignment->user?->surname ?: 'Имя не найдено' }}
                            </span>
                        </p>
                    </div>

                    @if ($assignment->status == 'submitted')
                        <div class="p-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                            <a href="{{ route('assignment.grade.form', $assignment->assignment->id) }}"
                                class="inline-flex items-center gap-2 text-sm px-4 py-1.5 text-[#6E76C1] border border-[#6E76C1] rounded-md hover:bg-[#6E76C1] hover:text-white transition duration-200">
                                <i class="fas fa-edit text-sm"></i> Проверить
                            </a>
                        </div>
                    @else
                        <div class="p-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                            <a href="{{ route('assignment.result', ['id' => $assignment->id]) }}"
                                class="inline-flex items-center gap-2 text-sm px-4 py-1.5 text-[#6E76C1] border border-[#6E76C1] rounded-md hover:bg-[#6E76C1] hover:text-white transition duration-200">
                                <i class="fas fa-edit text-sm"></i> Результаты
                            </a>
                        </div>
                    @endif

                </div>
            @empty
                <div class="col-span-full text-center py-10 bg-gray-50 border border-dashed border-gray-300 rounded-lg">
                    <i class="fas fa-inbox text-gray-300 text-4xl mb-4"></i>
                    <p class="text-gray-500">Нет заданий на проверку</p>
                </div>
            @endforelse
        </div>

        <div id="no-results"
            class="hidden text-center py-6 bg-white rounded-lg shadow-sm border border-dashed border-gray-300">
            <i class="fas fa-search text-gray-400 text-4xl mb-2"></i>
            <p class="text-gray-500">Нет заданий, соответствующих вашему запросу.</p>
        </div>
    </div>

    <script>
        function filterAssignments() {
            const titleInput = document.getElementById('filter-title').value.toLowerCase().trim();
            const statusSelect = document.getElementById('filter-status').value;
            const cards = document.querySelectorAll('.assignment-card');
            const noResultsMessage = document.getElementById('no-results');
            let hasVisibleCard = false;

            cards.forEach(card => {
                const cardTitle = card.dataset.title;
                const cardStatus = card.dataset.status;
                const cardClass = card.dataset.class;
                const assignmentClassName = card.dataset.assignmentClassName;

                const matchTitleOrClass = !titleInput ||
                    cardTitle.includes(titleInput) ||
                    cardClass.includes(titleInput) ||
                    assignmentClassName.includes(titleInput);

                const matchStatus = !statusSelect || cardStatus === statusSelect;

                if (matchTitleOrClass && matchStatus) {
                    card.style.display = 'block';
                    hasVisibleCard = true;
                } else {
                    card.style.display = 'none';
                }
            });

            noResultsMessage.classList.toggle('hidden', hasVisibleCard);
        }

        function clearFilters() {
            document.getElementById('filter-title').value = '';
            document.getElementById('filter-status').value = '';
            document.querySelectorAll('.assignment-card').forEach(card => {
                card.style.display = 'block';
            });
            document.getElementById('no-results').classList.add('hidden');
        }
    </script>
@endsection

@extends('pages.platform.layout', ['activePage' => 'null', 'title' => 'Задания на проверку', 'quick_action' => 'null'])
@section('content')
    <div class="container-fluid py-6 px-md-4">

        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-tasks mr-3 text-[#6E76C1]"></i> Задания на проверку
            </h2>
            <p class="mt-1 text-sm text-gray-500">Проверьте ответы учеников и выставите оценки</p>
        </div>

        <div class="mb-6 flex flex-wrap gap-4 sm:gap-6">
            <div class="flex-1 min-w-[200px]">
                <label for="filter-class" class="block text-sm font-medium text-gray-700 mb-1">Фильтр по классу</label>
                <select id="filter-class"
                    class="form-select w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#6E76C1] focus:border-transparent transition duration-200">
                    <option value="">Все классы</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->name }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="self-end">
                <button id="clear-filter"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md transition duration-200">
                    Сбросить фильтр
                </button>
            </div>
        </div>

        <div id="no-assignments-message"
            class="hidden mb-6 p-6 bg-gray-50 border border-dashed border-gray-300 rounded-lg text-center">
            <i class="fas fa-book-open text-gray-300 text-4xl mb-2"></i>
            <p class="text-gray-500">Нет заданий, соответствующих фильтрам.</p>
        </div>

        <div id="assignments-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($assignmentsToGrade as $assignment)
                @php
                    $totalStudents = $assignment['class']->students()->count();
                    $submissionsCount = $assignment['assignment']->studentAssignments
                        ->whereIn('status', ['submitted', 'graded'])
                        ->count();
                @endphp

                <div class="assignment-card bg-white rounded-lg shadow-sm overflow-hidden border-l-4 border-[#6E76C1] transition-all duration-300 hover:shadow-md"
                    data-class="{{ $assignment['class']->name }}" data-type="">
                    <div class="p-4 bg-gradient-to-r from-[#6E76C1] to-blue-500 flex justify-between items-start">
                        <h4 class="font-semibold text-lg text-white truncate">{{ $assignment['assignment']->title }}</h4>
                        <a href="{{ route('assignments.show', [
                            'id' => $assignment['assignment']->id,
                            'return_url' => url()->current(),
                        ]) }}"
                            class="inline-flex items-center ml-2 px-3 py-1 text-xs bg-white/20 text-white font-medium rounded-md hover:bg-white/30 transition duration-200">
                            Подробнее <i class="fas fa-arrow-right ml-1 text-sm"></i>
                        </a>
                    </div>

                    <div class="p-4 pt-3 pb-3 space-y-2">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-chalkboard-teacher mr-2 text-[#6E76C1]"></i>
                            {{ $assignment['class']->name }}
                        </div>

                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar-check mr-2 text-[#6E76C1]"></i>
                            Срок: {{ \Carbon\Carbon::parse($assignment['assignment']->due_date)->format('d.m.Y') }}
                        </div>

                        <div class="flex items-center justify-between text-sm mt-2">
                            <span class="text-gray-600">Учеников:</span>
                            <span class="text-gray-800">{{ $submissionsCount }} из {{ $totalStudents }}</span>
                        </div>

                        <div class="mt-3">
                            <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-1.5 bg-[#6E76C1] rounded-full"
                                    style="width: {{ $totalStudents ? round(($submissionsCount / $totalStudents) * 100) : 0 }}%">
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-xs font-medium text-gray-500">Статус:</span>
                            @if ($submissionsCount == 0)
                                <span
                                    class="badge status-badge status-not_submitted bg-red-500 text-white text-xs px-2 py-1 rounded">
                                    Не выполнено
                                </span>
                            @elseif ($submissionsCount < $totalStudents)
                                <span
                                    class="badge status-badge status-submitted bg-yellow-500 text-white text-xs px-2 py-1 rounded">
                                    Выполняется
                                </span>
                            @else
                                <span
                                    class="badge status-badge status-graded bg-green-500 text-white text-xs px-2 py-1 rounded">
                                    Завершено
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cards = document.querySelectorAll(".assignment-card");
            const filterClass = document.getElementById("filter-class");
            const noMessage = document.getElementById("no-assignments-message");

            filterClass.addEventListener("input", function() {
                const query = this.value.trim().toLowerCase();
                let visibleCards = 0;

                cards.forEach(card => {
                    const cardClass = card.dataset.class.toLowerCase();
                    if (!query || cardClass.includes(query)) {
                        card.style.display = "block";
                        visibleCards++;
                    } else {
                        card.style.display = "none";
                    }
                });

                noMessage.classList.toggle("hidden", visibleCards > 0);
            });

            document.getElementById("clear-filter").addEventListener("click", function() {
                filterClass.value = "";
                cards.forEach(card => card.style.display = "block");
                noMessage.classList.add("hidden");
            });
        });
    </script>
@endsection

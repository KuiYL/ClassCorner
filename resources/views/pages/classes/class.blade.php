@extends('pages.platform.layout', ['activePage' => 'null', 'title' => $class->name, 'quick_action' => 'assignments.create'])
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
                    <i class="fas fa-users"></i>
                    <span>Обучающихся:</span>
                    <strong>{{ count($students) - 1 }}</strong>
                </div>

                <div class="flex items-center gap-2">
                    <i class="fas fa-book-open"></i>
                    <span>Задания:</span>
                    <strong>{{ $assignments->count() }}</strong>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h3
                    class="text-2xl font-semibold text-gray-900 mb-4 relative inline-block after:content-[''] after:absolute after:left-0 after:bottom-[-4px] after:w-12 after:h-0.5 after:bg-[#6E76C1]">
                    Задания
                </h3>
                <a href="{{ route('assignments.create', ['classId' => $class->id, 'return_url' => url()->current()]) }}"
                    class="btn bg-[#6E76C1] outline-none hover:bg-[#616EBD] rounded-md px-4 py-2 text-white">
                    <i class="fas fa-plus mr-2"></i> Новое задание
                </a>
            </div>

            <div class="mb-4 flex flex-wrap gap-3 items-center">
                <label for="filter-type" class="text-sm font-medium text-gray-700">Фильтр:</label>
                <select id="filter-type"
                    class="form-select w-auto rounded-md border-gray-300 focus:ring-[#6E76C1] focus:border-transparent">
                    <option value="">Все типы</option>
                    <option value="text">Текстовый ответ</option>
                    <option value="file_upload">Загрузка файла</option>
                    <option value="single_choice">Один выбор</option>
                    <option value="multiple_choice">Множественный выбор</option>
                </select>
                <button type="button" id="clear-filter"
                    class="btn btn-outline-secondary text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-md">
                    <i class="fas fa-times mr-1"></i> Очистить
                </button>
            </div>

            <div id="assignments-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                @if ($assignments->isNotEmpty())
                    @foreach ($assignments as $assignment)
                        @php
                            $completed = $assignment
                                ->studentAssignments()
                                ->whereIn('status', ['graded'])
                                ->count();
                            $totalStudents = $assignment->students->count();
                            $progress = $totalStudents > 0 ? round(($completed / $totalStudents) * 100) : 0;
                        @endphp

                        <div class="card h-full shadow-sm hover:shadow-md transition-shadow duration-200 border border-gray-200 rounded-lg overflow-hidden"
                            data-id="{{ $assignment->id }}"
                            data-types="{{ json_encode(array_column($assignment->getFields(), 'type')) }}">

                            <div class="p-4 bg-white">
                                <div class="flex justify-between items-start">
                                    <h4 class="font-bold text-gray-900 flex items-center group">
                                        <i
                                            class="fas fa-book-open mr-2 text-[#6E76C1] group-hover:scale-110 transition-transform duration-200"></i>
                                        {{ $assignment->title }}
                                    </h4>
                                    <span
                                        class="inline-block px-2 py-1 text-xs font-medium rounded-full
                                {{ $assignment->status === 'active' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $assignment->statusName }}
                                    </span>
                                </div>

                                <p class="text-sm text-gray-600 mt-2 truncate">
                                    {{ $assignment->description ?: 'Нет описания' }}
                                </p>

                                <div class="mt-4 text-sm text-gray-500 space-y-2">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-calendar-check text-[#6E76C1]"></i>
                                        <strong class="text-gray-700">Срок:</strong>
                                        <span>{{ \Carbon\Carbon::parse($assignment->due_date)->format('d.m.Y') }} в
                                            {{ \Carbon\Carbon::parse($assignment->due_date)->format('H:i') }}</span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-tasks text-[#6E76C1]"></i>
                                        <strong class="text-gray-700">Выполнили:</strong>
                                        <div class="w-full ml-2">
                                            <div class="progress h-2 bg-gray-200 rounded overflow-hidden">
                                                <div class="progress-bar bg-[#6E76C1]" role="progressbar"
                                                    style="width: {{ $progress }}%"
                                                    aria-valuenow="{{ $progress }}" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                            <small class="text-gray-500">{{ $completed }} из {{ $totalStudents }}
                                                выполнили</small>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-tags text-[#6E76C1]"></i>
                                        <strong class="text-gray-700">Типы:</strong>
                                        <ul class="flex flex-wrap gap-1">
                                            @foreach (array_unique(array_column($assignment->getFields(), 'type')) as $type)
                                                @php
                                                    $typeLabels = [
                                                        'text' => 'Текст',
                                                        'file_upload' => 'Файл',
                                                        'single_choice' => 'Один выбор',
                                                        'multiple_choice' => 'Множественный',
                                                    ];
                                                @endphp
                                                <li
                                                    class="inline-block px-2 py-1 text-xs font-medium rounded bg-[#6E76C1]/10 text-[#6E76C1]">
                                                    {{ $typeLabels[$type] ?? 'Неизвестный тип' }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-end gap-2">
                                    <a href="{{ route('assignments.show', [
                                        'id' => $assignment->id,
                                        'return_url' => url()->current(),
                                    ]) }}"
                                        class="btn outline-none text-[#6E76C1] border-[#6E76C1] hover:bg-[#6E76C1] hover:text-gray-100 rounded-md px-3 py-1">
                                        <i class="fas fa-eye mr-1"></i> Просмотр
                                    </a>
                                    <button
                                        class="delete-button btn btn-sm btn-outline-danger text-red-600 hover:text-red-800 hover:bg-red-100 rounded-md px-3 py-1"
                                        data-id="{{ $assignment->id }}" data-name="{{ $assignment->title }}"
                                        data-type="assignment">
                                        <i class="fas fa-trash mr-1"></i> Удалить
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div
                        class="col-span-full text-center py-6 bg-white rounded-lg shadow-sm border border-dashed border-gray-300">
                        <i class="fas fa-book-open text-gray-400 text-4xl mb-2"></i>
                        <p class="text-gray-500 italic">Нет заданий. Добавьте новое задание выше.</p>
                    </div>
                @endif
            </div>

            <div id="no-results"
                class="hidden col-span-full text-center py-6 bg-white rounded-lg shadow-sm border border-dashed border-gray-300">
                <i class="fas fa-search text-gray-400 text-4xl mb-2"></i>
                <p class="text-gray-500">Нет заданий, соответствующих вашему запросу.</p>
            </div>
        </div>


        @php
            $studentCount = collect($studentProgress)
                ->filter(function ($sp) {
                    return $sp['student']->role === 'student';
                })
                ->count();
        @endphp

        @if ($studentCount > 0)
            <div class="card shadow-sm mt-6">
                <div class="card-header bg-white border-b border-gray-200 py-3 px-4 flex justify-between flex-wrap gap-3">
                    <h3 class="text-lg font-semibold text-gray-800">Ученики</h3>
                    <button type="button"
                        class="btn btn-outline-secondary text-[#6E76C1] border-[#6E76C1] hover:bg-[#6E76C1]"
                        data-bs-toggle="modal" data-bs-target="#inviteStudentModal">
                        <i class="fas fa-user-plus mr-2"></i> Пригласить ученика
                    </button>
                </div>
                <div class="card-body p-0 overflow-x-auto">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3 px-4 text-start text-sm text-gray-600 font-medium">Имя</th>
                                <th class="py-3 px-4 text-start text-sm text-gray-600 font-medium">Выполнено</th>
                                <th class="py-3 px-4 text-start text-sm text-gray-600 font-medium">Средний балл</th>
                                <th class="py-3 px-4 text-start text-sm text-gray-600 font-medium">Прогресс</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($studentProgress as $sp)
                                @if ($sp['student']->role == 'student')
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="py-3 px-4 font-medium flex items-center justify-between">
                                            <span>{{ $sp['student']->name }} {{ $sp['student']->surname }}</span>

                                            <form method="POST" action=""
                                                onsubmit="return confirm('Вы уверены, что хотите удалить ученика {{ $sp['student']->name }} из класса?');"
                                                class="inline-block ml-4">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-800 focus:outline-none"
                                                    title="Удалить ученика">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="py-3 px-4">
                                            {{ $sp['completed'] }} из {{ $sp['total'] }}
                                        </td>
                                        <td class="py-3 px-4">
                                            {{ $sp['average_grade'] ?? '-' }}
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-[#6E76C1]" role="progressbar"
                                                    style="width: {{ $sp['percent'] }}%">
                                                </div>
                                            </div>
                                            <small class="text-gray-500 mt-1 block">{{ $sp['percent'] }}%</small>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        @else
            <div class="text-center py-10 bg-gray-50 border border-dashed border-gray-300 rounded-lg mt-6">
                <i class="fas fa-user-graduate text-gray-400 text-4xl mb-2"></i>
                <h4 class="text-lg font-semibold text-gray-600">Нет учеников в этом классе.</h4>
                <p class="text-gray-500 mt-1">Нажмите "Пригласить ученика", чтобы добавить</p>
            </div>
        @endif

        <div class="modal fade" id="inviteStudentModal" tabindex="-1" aria-labelledby="inviteStudentModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-lg shadow-lg">
                    <div class="modal-header border-b pb-3">
                        <h5 class="modal-title text-lg font-semibold text-gray-800" id="inviteStudentModalLabel">
                            <i class="fas fa-user-plus mr-2 text-[#6E76C1]"></i>Пригласить ученика
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="invite-student-form" action="{{ route('classes.invite', $class->id) }}"
                            method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="search-student" class="form-label">Введите имя или email</label>
                                <input type="text" id="search-student" class="form-control" placeholder="Поиск...">
                                <ul id="search-results" class="list-group mt-2 hidden">
                                </ul>
                            </div>
                            <input type="hidden" id="invite-student-email" name="email">
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary bg-[#6E76C1] hover:bg-[#616EBD]">
                                    Отправить приглашение
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("search-student");
            const resultsList = document.getElementById("search-results");
            const emailInput = document.getElementById("invite-student-email");

            const availableStudents = @json($availableStudents);

            if (!searchInput || !resultsList || !emailInput) return;

            searchInput.addEventListener("input", function() {
                const query = this.value.trim().toLowerCase();
                resultsList.innerHTML = "";
                resultsList.classList.add("hidden");

                if (!query) return;

                const filtered = availableStudents.filter(student =>
                    student.name.toLowerCase().includes(query) ||
                    student.email.toLowerCase().includes(query)
                );

                if (filtered.length === 0) {
                    const li = document.createElement("li");
                    li.textContent = "Ученики не найдены";
                    li.style.color = "#888";
                    li.style.textAlign = "center";
                    li.className = "list-group-item list-group-item-action text-center";
                    resultsList.appendChild(li);
                } else {
                    filtered.forEach(student => {
                        const li = document.createElement("li");
                        li.textContent = `${student.name} (${student.email})`;
                        li.setAttribute("data-email", student.email);
                        li.className =
                            "list-group-item list-group-item-action cursor-pointer hover:bg-gray-100";

                        li.addEventListener("click", () => {
                            searchInput.value = `${student.name} (${student.email})`;
                            emailInput.value = student.email;
                            resultsList.innerHTML = "";
                            resultsList.classList.add("hidden");
                        });

                        resultsList.appendChild(li);
                    });
                }

                resultsList.classList.remove("hidden");
            });

            document.addEventListener("click", function(e) {
                if (!resultsList.contains(e.target) && e.target !== searchInput) {
                    resultsList.classList.add("hidden");
                }
            });

            const filterType = document.getElementById("filter-type");
            const cards = document.querySelectorAll("#assignments-grid .card");

            if (!filterType || !cards.length) return;

            filterType.addEventListener("change", function() {
                const selectedType = this.value.trim();

                let hasVisible = false;

                cards.forEach(card => {
                    const cardTypes = card.dataset.types ? JSON.parse(card.dataset.types) : [];

                    if (!selectedType || cardTypes.includes(selectedType)) {
                        card.style.display = "block";
                        hasVisible = true;
                    } else {
                        card.style.display = "none";
                    }
                });

                const noResults = document.getElementById("no-results");
                noResults.classList.toggle("hidden", hasVisible);
            });

            document.getElementById("clear-filter").addEventListener("click", function() {
                filterType.value = "";
                cards.forEach(card => card.style.display = "block");
                document.getElementById("no-results").classList.add("hidden");
            });
        });
    </script>
@endsection

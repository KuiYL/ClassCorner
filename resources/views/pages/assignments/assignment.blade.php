@extends('pages.platform.layout', ['activePage' => 'null', 'title' => $assignment->title, 'quick_action' => 'null'])
@section('content')
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">

        <a href="{{ route('class.show', $assignment->class_id) }}"
            class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-md transition duration-200 mt-6 ml-6">
            <i class="fas fa-arrow-left mr-2"></i> Назад к списку
        </a>

        <div class="bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex-1 min-w-0">
                <h2
                    class="text-2xl font-bold text-gray-900 flex items-center transition-transform duration-300 hover:text-[#6E76C1]">
                    <i class="fas fa-book-open mr-2 text-[#6E76C1]"></i>
                    <span class="truncate max-w-xs sm:max-w-sm md:max-w-xl lg:max-w-2xl" title="{{ $assignment->title }}">
                        {{ $assignment->title }}
                    </span>
                </h2>
            </div>
            <span class="ml-4 text-sm text-gray-500 shrink-0">
                ID: {{ $assignment->id }}
            </span>
        </div>

        @if (isset($stats))
            <div class="px-6 mt-4">
                <div class="bg-gradient-to-r from-[#6E76C1]/5 to-transparent border-l-4 border-[#6E76C1] p-4 rounded-md">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Статистика выполнения</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                        <div class="bg-white p-4 rounded shadow-sm">
                            <div class="text-2xl font-bold text-[#6E76C1]">{{ $stats['completed_count'] }}</div>
                            <div class="text-sm text-gray-600">Выполнили</div>
                        </div>
                        <div class="bg-white p-4 rounded shadow-sm">
                            <div class="text-2xl font-bold text-[#6E76C1]">
                                {{ number_format($stats['average_grade'], 1) }}
                            </div>
                            <div class="text-sm text-gray-600">Средний балл</div>
                        </div>
                        <div class="bg-white p-4 rounded shadow-sm">
                            <div class="text-2xl font-bold text-[#6E76C1]">{{ $stats['total_students'] }}</div>
                            <div class="text-sm text-gray-600">Всего учеников</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="p-6 space-y-6">
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <label class="block text-sm font-medium text-gray-700 mb-2">Описание задания</label>
                <div id="description-text" class="text-gray-800 line-clamp-3 overflow-hidden whitespace-pre-line">
                    {{ $assignment->description ?: 'Нет описания' }}
                </div>
            </div>

            @php
                $dueDate = \Carbon\Carbon::parse($assignment->due_date);
                $now = \Carbon\Carbon::now();
                $isOverdue = $now->gt($dueDate);
            @endphp

            <div class="flex items-center gap-2 text-sm text-gray-600">
                <i class="fas fa-calendar-check text-[#6E76C1]"></i>
                <span class="font-medium">Срок выполнения:</span>
                <strong class="ml-1">
                    {{ $dueDate->format('d.m.Y') }}
                </strong>
                &nbsp;в&nbsp;
                <strong>
                    {{ $dueDate->format('H:i') }}
                </strong>

                @if ($isOverdue)
                    <span class="ml-3 px-2 py-1 text-xs font-semibold rounded-full bg-red-600 text-white select-none"
                        title="Срок сдачи задания прошёл">
                        Срок сдачи истёк
                    </span>
                @endif
            </div>


            @if ($assignment->materials->isNotEmpty())
                <div class="mt-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Материалы задания</h3>
                    <ul class="space-y-2">
                        @foreach ($assignment->materials as $material)
                            <li
                                class="flex items-center gap-2 p-3 bg-gray-50 border border-gray-200 rounded-md hover:bg-gray-100 transition duration-200">
                                <i class="fas fa-paperclip text-[#6E76C1]"></i>
                                <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank"
                                    class="text-[#6E76C1] hover:underline truncate flex-1">
                                    {{ $material->file_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (count($assignmentFields))
                <div class="space-y-4">
                    <h3 class="text-xl font-semibold text-gray-800">Поля задания</h3>

                    @foreach ($assignmentFields as $field)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                            <h4 class="font-medium text-gray-900 mb-3 truncate max-w-full">
                                {{ $field['name'] ?? 'Без названия' }}
                            </h4>

                            @if ($field['type'] === 'text')
                                <div class="bg-purple-50 border-l-4 border-purple-400 text-purple-800 p-3 rounded-md">
                                    <strong class="text-sm uppercase tracking-wide text-purple-600">Тип:</strong>
                                    <span class="ml-2">Текстовый ответ</span>
                                    <p class="mt-2 text-sm italic">Ученик должен ввести свой текст.</p>
                                </div>
                            @elseif ($field['type'] === 'file_upload')
                                <div class="bg-indigo-50 border-l-4 border-indigo-400 text-indigo-800 p-3 rounded-md">
                                    <strong class="text-sm uppercase tracking-wide text-indigo-600">Тип:</strong>
                                    <span class="ml-2">Загрузка файла</span>
                                    <p class="mt-2 text-sm italic">Ученик должен загрузить файл.</p>
                                </div>
                            @elseif (in_array($field['type'], ['multiple_choice', 'single_choice']))
                                <ul class="space-y-2 mt-2">
                                    @foreach ($field['options'] as $option)
                                        <li class="flex items-center gap-2 p-2 rounded-md hover:bg-gray-100 group">
                                            <input type="{{ $field['type'] === 'single_choice' ? 'radio' : 'checkbox' }}"
                                                disabled {{ $option['isCorrect'] ? 'checked' : '' }}
                                                class="text-[#6E76C1] focus:ring-[#6E76C1]">
                                            <span class="text-gray-800 truncate max-w-xs sm:max-w-sm md:max-w-full">
                                                {{ $option['value'] }}
                                            </span>
                                            @if ($option['isCorrect'])
                                                <span
                                                    class="ml-auto inline-flex items-center px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                                                    <i class="fas fa-check mr-1"></i> Правильный
                                                </span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 text-gray-500 italic">
                    <i class="fas fa-info-circle text-gray-400 mr-2"></i>
                    Нет полей в задании.
                </div>
            @endif

            <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200 mt-6">
                <a href="{{ route('assignments.edit', [
                    'id' => $assignment->id,
                    'class_id' => $assignment->class_id,
                    'return_url' => url()->current(),
                ]) }}"
                    class="inline-flex items-center px-4 py-2 bg-[#6E76C1] hover:bg-[#616EBD] text-white font-medium rounded-md shadow-sm transition duration-200">
                    <i class="fas fa-edit mr-2"></i> Изменить
                </a>

                <button
                    class="delete-button inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md shadow-sm transition duration-200"
                    type="button" data-id="{{ $assignment->id }}" data-name="{{ $assignment->title }}"
                    data-type="assignment">
                    <i class="fas fa-trash mr-2"></i> Удалить
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const descriptionText = document.getElementById("description-text");
            const toggleDescriptionBtn = document.getElementById("toggle-description");

            if (descriptionText && toggleDescriptionBtn) {
                toggleDescriptionBtn.addEventListener("click", function() {
                    descriptionText.classList.toggle("line-clamp-3");
                    this.textContent = descriptionText.classList.contains("line-clamp-3") ?
                        "Показать всё" :
                        "Скрыть";
                });
            }
        });
    </script>
@endsection

@extends('pages.platform.layout', ['activePage' => 'null', 'title' => $studentAssignment->assignment->title, 'quick_action' => 'null'])

@section('content')
    <div class="main-platform mx-auto bg-white rounded-xl shadow-sm p-6 max-w-6xl">
        <div class="result-header text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Результат выполнения задания</h2>
            <p class="mt-2 text-lg text-gray-600">
                <strong>Задание:</strong> {{ $studentAssignment->assignment->title }}
            </p>
            <p class="text-sm text-gray-500 flex items-center justify-center gap-2 mt-2">
                <i class="fas fa-user"></i>
                {{ $studentAssignment->user->name }} {{ $studentAssignment->user->surname }}
                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Проверено</span>
            </p>
        </div>

        <section class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="md:col-span-2 space-y-4">
                    @if (!empty($results))
                        @foreach ($results as $item)
                            <div
                                class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow duration-200">
                                <p class="font-medium text-gray-800 mb-3">
                                    <strong>{{ $loop->iteration }}.</strong> {{ $item['question_text'] }}
                                </p>

                                @if ($item['type'] === 'single_choice' || $item['type'] === 'multiple_choice')
                                    @if (!empty($item['student_answer']))
                                        <p class="flex items-center text-gray-700 mb-2">
                                            <i
                                                class="fas fa-check-circle {{ $item['isCorrect'] ? 'text-green-500' : 'text-red-500' }} mr-2"></i>
                                            <strong class="mr-2">Выбранный(е):</strong>
                                            @php
                                                $selectedTexts = [];
                                                foreach ($item['student_answer']['selected_options'] as $idx) {
                                                    if (isset($item['options'][$idx])) {
                                                        $selectedTexts[] = $item['options'][$idx]['value'];
                                                    }
                                                }
                                            @endphp
                                            {{ implode(', ', $selectedTexts) ?: 'Ошибка: варианты не найдены' }}
                                            @unless ($item['isCorrect'])
                                                <span
                                                    class="ml-2 inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-red-500 rounded">
                                                    Неправильно
                                                </span>
                                            @endunless
                                        </p>
                                    @else
                                        <p class="text-gray-400 italic">
                                            <i class="fas fa-times-circle mr-2"></i>
                                            Ответ не предоставлен
                                        </p>
                                    @endif

                                    <div class="mt-3">
                                        <p class="text-sm text-gray-500 mb-2">Правильные варианты:</p>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                            @foreach ($item['options'] as $option)
                                                @if (isset($option['value']))
                                                    <span
                                                        class="{{ $option['isCorrect'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }} px-3 py-1 rounded-full text-center text-sm">
                                                        {{ $option['value'] }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @elseif ($item['type'] === 'text' && !empty($item['student_answer']))
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                        <p class="text-gray-800 whitespace-pre-wrap">{{ $item['student_answer']['value'] }}
                                        </p>
                                    </div>
                                @elseif ($item['type'] === 'file_upload' && !empty($item['student_answer']))
                                    <div class="mt-3">
                                        <p class="text-sm text-gray-500">Загруженный файл:</p>
                                        <a href="{{ asset('storage/' . $item['student_answer']['file_path']) }}"
                                            class="inline-flex items-center gap-2 px-3 py-1 bg-blue-100 text-blue-700 rounded text-sm mt-1">
                                            <i class="fas fa-file-pdf"></i>
                                            {{ $item['student_answer']['file_name'] ?? 'Скачать файл' }}
                                        </a>
                                    </div>
                                @else
                                    <p class="text-gray-400 italic">Нет данных для вывода</p>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-center text-gray-400 italic py-4">Нет вопросов или ответов</p>
                    @endif
                </div>

                <div class="md:col-span-1 space-y-4">
                    <!-- Детали задания + Статистика -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Левая колонка: детали задания -->
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                            <h4 class="text-base font-semibold text-gray-700 mb-4">Детали задания</h4>
                            <ul class="space-y-3">
                                <li class="flex items-start gap-3">
                                    <i class="fas fa-chalkboard-teacher text-indigo-600 mt-1"></i>
                                    <div>
                                        <p class="text-sm text-gray-500">Класс</p>
                                        <p class="font-medium">
                                            {{ optional($studentAssignment->assignment->class)->name ?? 'Не указан' }}</p>
                                    </div>
                                </li>

                                <li class="flex items-start gap-3">
                                    <i class="fas fa-calendar-alt text-indigo-600 mt-1"></i>
                                    <div>
                                        <p class="text-sm text-gray-500">Дедлайн</p>
                                        <p class="font-medium">
                                            {{ \Carbon\Carbon::parse($studentAssignment->assignment->due_date)->format('d.m.Y') }}
                                        </p>
                                    </div>
                                </li>

                                <li class="flex items-start gap-3">
                                    <i class="fas fa-user text-green-600 mt-1"></i>
                                    <div>
                                        <p class="text-sm text-gray-500">Автор</p>
                                        <p class="font-medium">
                                            {{ $studentAssignment->assignment->teacher->name ?? 'Неизвестный преподаватель' }}
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- Правая колонка: статистика -->
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                            <h4 class="text-base font-semibold text-gray-700 mb-4">Ваш результат</h4>

                            <!-- Прогресс -->
                            <div class="mb-4">
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>Процент правильных</span>
                                    <span>{{ $percentCorrect }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                    <div class="bg-indigo-500 h-2.5 transition-all duration-1000"
                                        style="width: {{ $percentCorrect }}%;"></div>
                                </div>
                            </div>

                            <!-- Счётчики -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-green-50 border-l-4 border-green-500 rounded-md pl-3 py-2">
                                    <p class="text-sm font-medium text-green-800">Правильные</p>
                                    <p class="text-xl font-bold text-green-800">
                                        {{ collect($results)->where('isCorrect', true)->count() }}</p>
                                </div>

                                <div class="bg-red-50 border-l-4 border-red-500 rounded-md pl-3 py-2">
                                    <p class="text-sm font-medium text-red-800">Неправильные</p>
                                    <p class="text-xl font-bold text-red-800">
                                        {{ count($results) - collect($results)->where('isCorrect', true)->count() }}</p>
                                </div>
                            </div>

                            <!-- Итоговая оценка -->
                            <div class="mt-4 flex items-center gap-2">
                                <i class="fas fa-star-half-alt text-blue-500"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Итоговая оценка</p>
                                    <p class="text-lg font-medium text-blue-600">{{ $studentAssignment->grade }}/100</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (auth()->user()->role === 'teacher')
                        <div class="top-6">
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                                <h4 class="text-lg font-semibold text-gray-700 mb-4">Оценка и фидбэк</h4>
                                <form action="{{ route('assignment.grade.save', $studentAssignment->id) }}" method="POST"
                                    class="space-y-4">
                                    @csrf
                                    @method('PUT')

                                    <div>
                                        <label for="grade" class="block text-sm font-medium text-gray-700 mb-1">Оценка
                                            (от 0 до 100)</label>
                                        <input type="number" name="grade" id="grade"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                            value="{{ $studentAssignment->grade }}" min="0" max="100" required>
                                    </div>

                                    <div>
                                        <label for="feedback"
                                            class="block text-sm font-medium text-gray-700 mb-1">Комментарий</label>
                                        <textarea name="feedback" id="feedback" rows="5"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                            required>{{ $studentAssignment->feedback }}</textarea>
                                    </div>

                                    <button type="submit"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                        <i class="fas fa-save mr-2"></i>
                                        Сохранить оценку
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        @if ($studentAssignment->feedback)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                                <h4 class="text-lg font-semibold text-gray-700 mb-3">Комментарий преподавателя</h4>
                                <p class="text-gray-800 whitespace-pre-wrap">{{ $studentAssignment->feedback }}</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </section>
    </div>
@endsection

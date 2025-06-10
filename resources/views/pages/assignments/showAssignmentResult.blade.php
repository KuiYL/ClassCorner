@extends('pages.platform.layout', ['activePage' => 'null', 'title' => $studentAssignment->assignment->title, 'quick_action' => 'null'])

@section('content')
    <div class="main-platform mx-auto bg-white rounded-2xl shadow-lg p-8 max-w-6xl">
        <div class="result-header text-center mb-10">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                Результат выполнения задания
            </h1>
            <p class="mt-3 text-lg text-gray-600 font-medium">
                <span class="font-semibold">Задание:</span> {{ $studentAssignment->assignment->title }}
            </p>
            <p class="mt-2 text-sm text-gray-500 flex items-center justify-center gap-3">
                <i class="fas fa-user text-[#6E76C1]"></i>
                <span class="font-semibold text-gray-700">
                    {{ $studentAssignment->user->name }} {{ $studentAssignment->user->surname }}
                </span>
                <span class="bg-green-200 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                    Проверено
                </span>
            </p>
        </div>

        <section class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2 space-y-6">
                @if (!empty($results))
                    @foreach ($results as $item)
                        <article
                            class="bg-gray-50 border border-gray-200 rounded-xl shadow hover:shadow-lg transition-shadow duration-300 p-6">
                            <p class="font-semibold text-lg text-gray-800 mb-4 leading-relaxed">
                                <span class="text-[#6E76C1]">{{ $loop->iteration }}.</span> {{ $item['question_text'] }}
                            </p>

                            @if ($item['type'] === 'single_choice' || $item['type'] === 'multiple_choice')
                                @if (!empty($item['student_answer']))
                                    <p class="flex items-center text-gray-700 mb-3 space-x-2">
                                        <i
                                            class="fas fa-check-circle {{ $item['isCorrect'] ? 'text-green-500' : 'text-red-500' }} text-xl"></i>
                                        <strong class="mr-2">Выбранный(е):</strong>
                                        @php
                                            $selectedTexts = [];
                                            foreach ($item['student_answer']['selected_options'] as $idx) {
                                                if (isset($item['options'][$idx])) {
                                                    $selectedTexts[] = $item['options'][$idx]['value'];
                                                }
                                            }
                                        @endphp
                                        <span>{{ implode(', ', $selectedTexts) ?: 'Ошибка: варианты не найдены' }}</span>

                                        @unless ($item['isCorrect'])
                                            <span
                                                class="ml-3 inline-flex items-center px-3 py-1 text-xs font-semibold text-white bg-red-600 rounded-full">
                                                Неправильно
                                            </span>
                                        @endunless
                                    </p>
                                @else
                                    <p class="text-gray-400 italic flex items-center gap-2">
                                        <i class="fas fa-times-circle text-xl"></i>
                                        Ответ не предоставлен
                                    </p>
                                @endif

                                <div class="mt-4">
                                    <p class="text-sm font-semibold text-gray-600 mb-2">Правильные варианты:</p>
                                    <div class="flex flex-wrap gap-3">
                                        @foreach ($item['options'] as $option)
                                            @if (isset($option['value']))
                                                <span
                                                    class="px-4 py-1 rounded-full text-sm font-medium {{ $option['isCorrect'] ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                                                    {{ $option['value'] }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @elseif ($item['type'] === 'text' && !empty($item['student_answer']))
                                <div class="bg-white border border-gray-300 rounded-lg p-5 shadow-sm">
                                    <p class="text-gray-800 whitespace-pre-wrap leading-relaxed">
                                        {{ $item['student_answer']['value'] }}</p>
                                </div>
                            @elseif ($item['type'] === 'file_upload' && !empty($item['student_answer']))
                                <div class="mt-4">
                                    <p class="text-sm text-gray-600 mb-2">Загруженный файл:</p>
                                    <a href="{{ asset('storage/' . $item['student_answer']['file_path']) }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition">
                                        <i class="fas fa-file-pdf text-lg"></i>
                                        {{ $item['student_answer']['file_name'] ?? 'Скачать файл' }}
                                    </a>
                                </div>
                            @else
                                <p class="text-gray-400 italic">Нет данных для вывода</p>
                            @endif
                        </article>
                    @endforeach
                @else
                    <p class="text-center text-gray-400 italic py-6">Нет вопросов или ответов</p>
                @endif
            </div>

            <aside class="space-y-8">
                <!-- Детали задания -->
                <section class="bg-white border border-gray-200 rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-6 border-b border-gray-200 pb-3">
                        Детали задания
                    </h3>
                    <ul class="space-y-5 text-gray-700">
                        <li class="flex items-center gap-4">
                            <i class="fas fa-chalkboard-teacher text-[#6E76C1] text-xl"></i>
                            <div>
                                <p class="text-sm text-gray-500">Класс</p>
                                <p class="font-medium text-gray-800">
                                    {{ optional($studentAssignment->assignment->class)->name ?? 'Не указан' }}
                                </p>
                            </div>
                        </li>

                        <li class="flex items-center gap-4">
                            <i class="fas fa-calendar-alt text-[#6E76C1] text-xl"></i>
                            <div>
                                <p class="text-sm text-gray-500">Дедлайн</p>
                                <p class="font-medium text-gray-800">
                                    {{ \Carbon\Carbon::parse($studentAssignment->assignment->due_date)->format('d.m.Y') }}
                                </p>
                            </div>
                        </li>

                        <li class="flex items-center gap-4">
                            <i class="fas fa-user text-green-600 text-xl"></i>
                            <div>
                                <p class="text-sm text-[#6E76C1]">Автор</p>
                                <p class="font-medium text-gray-800">
                                    {{ $studentAssignment->assignment->teacher->name ?? 'Неизвестный преподаватель' }}
                                </p>
                            </div>
                        </li>
                    </ul>
                </section>

                <!-- Правая колонка: статистика -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                    <h4 class="text-base font-semibold text-gray-700 mb-4">Ваш результат</h4>

                    <!-- Итоговая оценка -->
                    <div class="mt-4 flex items-center gap-2">
                        <i class="fas fa-star-half-alt text-[#6E76C1]"></i>
                        <div>
                            <p class="text-sm text-gray-500">Итоговая оценка</p>
                            <p class="text-lg font-medium text-[#6E76C1]">{{ $studentAssignment->grade }}/100</p>
                        </div>
                    </div>
                </div>


                @if (auth()->user()->role === 'teacher')
                    <section class="bg-white border border-gray-200 rounded-xl shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-6 border-b border-gray-200 pb-3">
                            Оценка и фидбэк
                        </h3>
                        <form action="{{ route('assignment.grade.save', $studentAssignment->id) }}" method="POST"
                            class="space-y-5">
                            @csrf
                            @method('PUT')

                            <div>
                                <label for="grade" class="block text-sm font-medium text-gray-700 mb-2">
                                    Оценка (от 0 до 100)
                                </label>
                                <input type="number" name="grade" id="grade" min="0" max="100" required
                                    class="w-full rounded-md border border-gray-300 px-4 py-2 text-gray-800 focus:ring-2 focus:ring-[#6E76C1] focus:outline-none"
                                    value="{{ $studentAssignment->grade }}">
                            </div>

                            <div>
                                <label for="feedback" class="block text-sm font-medium text-gray-700 mb-2">
                                    Комментарий
                                </label>
                                <textarea name="feedback" id="feedback" rows="5" required
                                    class="w-full rounded-md border border-gray-300 px-4 py-2 text-gray-800 focus:ring-2 focus:ring-[#6E76C1] focus:outline-none">{{ $studentAssignment->feedback }}</textarea>
                            </div>

                            <button type="submit"
                                class="w-full bg-[#6E76C1] hover:bg-[#6E76C1] text-white font-semibold py-3 rounded-md transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-save"></i> Сохранить оценку
                            </button>
                        </form>
                    </section>
                @else
                    @if ($studentAssignment->feedback)
                        <section class="bg-white border border-gray-200 rounded-xl shadow p-6">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b border-gray-200 pb-2">
                                Комментарий преподавателя
                            </h3>
                            <p class="text-gray-800 whitespace-pre-wrap leading-relaxed">
                                {{ $studentAssignment->feedback }}
                            </p>
                        </section>
                    @endif
                @endif
            </aside>
        </section>
    </div>
@endsection

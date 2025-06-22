@extends('pages.platform.layout', ['activePage' => 'null', 'title' => $studentAssignment->assignment->title, 'quick_action' => 'null'])

@section('content')
    <div class="main-platform mx-auto bg-white rounded-2xl shadow-lg p-8 max-w-6xl min-h-[90vh] flex flex-col">
        <div
            class="bg-white py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sm:gap-0">
            <div class="flex items-center gap-4">
                @if (auth()->user()->role === 'teacher')
                    <a href="{{ route('assignments.to.grade') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-md transition duration-200 ">
                        <i class="fas fa-arrow-left mr-2"></i>Назад
                    </a>
                @else
                    <a href="{{ route('user.assignments') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-md transition duration-200 ">
                        <i class="fas fa-arrow-left mr-2"></i>Назад
                    </a>
                @endif

                <i class="fas fa-clipboard-check text-[#6E76C1] text-2xl flex-shrink-0"></i>
                <h1 class="text-2xl font-bold text-gray-900 truncate max-w-xs sm:max-w-sm md:max-w-xl lg:max-w-2xl transition-transform duration-300 hover:text-[#6E76C1]"
                    title="{{ $studentAssignment->assignment->title }}">
                    {{ $studentAssignment->assignment->title }}
                </h1>
            </div>
        </div>

        <form action="{{ route('assignment.grade.save', $studentAssignment->id) }}" method="POST"
            class="flex flex-col md:flex-row gap-10 mt-6 items-start w-full">
            @csrf
            @method('PUT')
            <div class="pr-2 space-y-8 flex-1">
                <section>
                    <div class="flex items-center justify-between mb-6 border-b border-gray-200 pb-3">
                        <h3 class="text-lg font-semibold text-gray-700">Ответ студента</h3>
                        <div class="flex items-center gap-2 whitespace-nowrap">
                            <i class="fas fa-user text-[#6E76C1]"></i>
                            <span class="font-semibold truncate max-w-[150px]"
                                title="{{ $studentAssignment->user->name }} {{ $studentAssignment->user->surname }}">
                                {{ $studentAssignment->user->name }} {{ $studentAssignment->user->surname }}
                            </span>
                        </div>
                    </div>
                    @if (!empty($results))
                        @foreach ($results as $index => $item)
                            @php
                                $typeTranslations = [
                                    'file_upload' => 'Загрузка файла',
                                    'multiple_choice' => 'Множественный выбор',
                                    'single_choice' => 'Один выбор',
                                    'text' => 'Текстовый ответ',
                                ];
                            @endphp

                            <article
                                class="bg-gray-50 border border-gray-200 rounded-xl shadow-sm p-6 mb-4 hover:shadow-md transition-shadow">
                                <h4 class="font-semibold text-gray-800 mb-3 leading-relaxed">
                                    {{ $item['question_text'] ?? 'Вопрос ' . ($index + 1) }}
                                </h4>

                                <p class="font-semibold text-gray-700 mb-3 leading-relaxed">
                                    Тип: {{ $typeTranslations[$item['type']] ?? 'Неизвестный тип' }}
                                </p>

                                @if (in_array($item['type'], ['single_choice', 'multiple_choice']))
                                    @if (!empty($item['student_answer']))
                                        @php
                                            $selectedTexts = [];
                                            foreach ($item['student_answer']['selected_options'] ?? [] as $idx) {
                                                if (isset($item['options'][$idx])) {
                                                    $selectedTexts[] = $item['options'][$idx]['value'];
                                                }
                                            }
                                        @endphp
                                        <ul class="list-disc ml-5 space-y-1 mb-4">
                                            @foreach ($item['options'] ?? [] as $optIndex => $option)
                                                @php
                                                    $isSelected = in_array(
                                                        $optIndex,
                                                        $item['student_answer']['selected_options'] ?? [],
                                                    );
                                                    $isCorrect = $option['isCorrect'] ?? false;
                                                @endphp
                                                <li
                                                    class="{{ $isCorrect ? 'text-green-600' : 'text-red-600' }} leading-snug">
                                                    {{ $option['value'] ?? 'Без текста' }}
                                                    @if ($isSelected)
                                                        <small
                                                            class="{{ $isCorrect ? 'text-green-600' : 'text-red-600' }} ml-2">
                                                            {{ $isCorrect ? '✅ Выбран и правильный' : '❌ Выбран, но неверный' }}
                                                        </small>
                                                    @elseif ($isCorrect)
                                                        <small class="text-green-600 ml-2">📌 Пропущен правильный
                                                            вариант</small>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-gray-500 italic mb-4">Ответ не предоставлен.</p>
                                    @endif
                                @elseif ($item['type'] === 'text')
                                    @if (!empty($item['student_answer']['value']))
                                        <p class="text-gray-800 ">
                                            {{ $item['student_answer']['value'] ?? 'Без текста' }}
                                        </p>
                                    @else
                                        <p class="text-gray-500 italic">Ответ не предоставлен.</p>
                                    @endif
                                @elseif ($item['type'] === 'file_upload')
                                    @if (!empty($item['student_answer']['file_path']))
                                        <p>
                                            <a href="{{ asset('storage/' . $item['student_answer']['file_path']) }}"
                                                target="_blank" class="text-blue-600 hover:underline font-medium">
                                                {{ $item['student_answer']['file_name'] ?? 'Скачать файл' }}
                                            </a>
                                        </p>
                                    @else
                                        <p class="text-gray-500 italic">Файл не загружен.</p>
                                    @endif
                                @else
                                    <p class="text-gray-400 italic">Нет данных для вывода.</p>
                                @endif
                            </article>
                        @endforeach
                    @else
                        <p class="text-center text-gray-400 italic py-6">Нет вопросов или ответов</p>
                    @endif

                    @if (!empty($detailedStats))
                        <section>
                            <h3 class="text-lg font-semibold text-gray-700 mb-6 border-b border-gray-200 pb-3">Детали по
                                каждому
                                вопросу</h3>
                            <div class="overflow-x-auto rounded-lg shadow-sm border border-gray-200 bg-white">
                                <table class="w-full text-left text-sm text-gray-700">
                                    <thead class="bg-gray-100 border-b border-gray-300">
                                        <tr>
                                            <th class="px-4 py-2">Вопрос</th>
                                            <th class="px-4 py-2">Тип</th>
                                            @php
                                                $showExtraCols = false;
                                                foreach ($detailedStats as $stat) {
                                                    if (!in_array($stat['type'], ['text', 'file_upload'])) {
                                                        $showExtraCols = true;
                                                        break;
                                                    }
                                                }
                                            @endphp
                                            @if ($showExtraCols)
                                                <th class="px-4 py-2">Правильных нужно</th>
                                                <th class="px-4 py-2">Выбрано верно</th>
                                                <th class="px-4 py-2">Процент</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($detailedStats as $stat)
                                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                                <td class="px-4 py-2">{{ $stat['question'] }}</td>
                                                <td class="px-4 py-2">
                                                    {{ $typeTranslations[$stat['type']] ?? 'Неизвестный тип' }}</td>
                                                @if (!in_array($stat['type'], ['text', 'file_upload']))
                                                    <td class="px-4 py-2">{{ $stat['correct_needed'] }}</td>
                                                    <td class="px-4 py-2">{{ $stat['correct_given'] }}</td>
                                                    <td class="px-4 py-2">{{ $stat['percent'] }}%</td>
                                                @else
                                                    @if ($showExtraCols)
                                                        <td class="px-4 py-2"></td>
                                                        <td class="px-4 py-2"></td>
                                                        <td class="px-4 py-2"></td>
                                                    @endif
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    @endif
            </div>

            <aside class="w-full md:w-[320px] bg-white border border-gray-200 rounded-xl shadow p-6">
                <section class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-700 mb-6 border-b border-gray-200 pb-3">Информация о задании
                    </h3>
                    <div class="flex flex-col gap-4 mb-4">
                        <div class="flex items-start gap-4">
                            <i class="fas fa-chalkboard-teacher text-[#6E76C1] text-xl"></i>
                            <div class="flex-1">
                                <p class="text-sm text-gray-500">Класс</p>
                                <p class="font-medium text-gray-800">
                                    {{ optional($studentAssignment->assignment->class)->name ?? 'Не указан' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <i class="fas fa-calendar-alt text-[#6E76C1] text-xl"></i>
                            <div class="flex-1">
                                <p class="text-sm text-gray-500">Дедлайн</p>
                                <p class="font-medium text-gray-800">
                                    {{ \Carbon\Carbon::parse($studentAssignment->assignment->due_date)->format('d.m.Y') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 mb-6">
                        <i class="fas fa-user text-green-600 text-xl"></i>
                        <div class="flex-1">
                            <p class="text-sm text-[#6E76C1]">Преподаватель</p>
                            <p class="font-medium text-gray-800">
                                {{ $studentAssignment->assignment->teacher
                                    ? $studentAssignment->assignment->teacher->name . ' ' . $studentAssignment->assignment->teacher->surname
                                    : 'Неизвестный преподаватель' }}
                            </p>
                        </div>
                    </div>
                </section>

                @if (auth()->user()->role === 'teacher')
                    <section>
                        <h3 class="text-lg font-semibold text-gray-700 mb-6 border-b border-gray-200 pb-3">
                            Оценка и фидбэк
                        </h3>
                        <div class="space-y-5">
                            <div>
                                <label for="final-grade" class="block text-lg font-semibold text-gray-700 mb-1">Итоговая
                                    оценка</label>
                                <p class="text-sm text-gray-500 mb-4">(от 0 до 100)</p>
                                <input type="number" id="final-grade" name="grade" min="0" max="100"
                                    value="{{ old('grade', $studentAssignment->grade ?? 0) }}"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#6E76C1]"
                                    placeholder="Введите оценку (0-100)">
                            </div>

                            <div>
                                <label for="feedback" class="block text-base font-semibold text-gray-700 mb-2">Комментарий
                                    от преподавателя</label>
                                <textarea name="feedback" id="feedback" rows="5"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 resize-y focus:outline-none focus:ring-2 focus:ring-[#6E76C1]"
                                    placeholder="Введите комментарий">{{ old('feedback', $studentAssignment->feedback) }}</textarea>
                            </div>

                            <section class="sticky bottom-0 bg-white border-t border-gray-200 pt-4 pb-2 px-6 -mx-6">
                                <button type="submit"
                                    class="w-full bg-[#6E76C1] text-white font-semibold py-2 rounded-md hover:bg-[#5a65b0] transition">
                                    Сохранить оценку
                                </button>
                            </section>
                        </div>
                    </section>
                @else
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 max-w-[280px] mx-auto">
                        <h4 class="text-lg font-semibold text-gray-700 mb-4 text-center">Ваш результат</h4>

                        @php
                            $grade = $studentAssignment->grade;
                            if ($grade >= 90) {
                                $color = 'green';
                                $message = 'Отлично!';
                            } elseif ($grade >= 70) {
                                $color = 'blue';
                                $message = 'Хорошо';
                            } elseif ($grade >= 50) {
                                $color = 'yellow';
                                $message = 'Удовлетворительно';
                            } else {
                                $color = 'red';
                                $message = 'Нужно улучшить';
                            }
                        @endphp

                        <div class="flex items-center gap-4 mb-4">
                            <div class="rounded-full bg-gray-100 p-2">
                                <i class="fas fa-star-half-alt text-[#6E76C1] text-lg"></i>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-medium text-gray-500 truncate">Оценка</span>
                                    <div class="text-2xl font-bold text-gray-800 truncate">{{ $grade }}/100</div>
                                </div>
                                <div class="mt-2 text-center">
                                    <span
                                        class="inline-block px-3 py-1 text-xs font-semibold rounded-full whitespace-nowrap
                    {{ $color === 'green' ? 'bg-green-100 text-green-700' : '' }}
                    {{ $color === 'blue' ? 'bg-blue-100 text-blue-700' : '' }}
                    {{ $color === 'yellow' ? 'bg-yellow-100 text-yellow-700' : '' }}
                    {{ $color === 'red' ? 'bg-red-100 text-red-700' : '' }}">
                                        {{ $message }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full transition-all duration-500"
                                style="width: {{ $grade }}%; background:
            {{ $color === 'green' ? 'linear-gradient(to right, #4caf50, #8bc34a)' : '' }}
            {{ $color === 'blue' ? 'linear-gradient(to right, #2196f3, #64b5f6)' : '' }}
            {{ $color === 'yellow' ? 'linear-gradient(to right, #ffc107, #ffeb3b)' : '' }}
            {{ $color === 'red' ? 'linear-gradient(to right, #f44336, #ef5350)' : '' }};">
                            </div>
                        </div>
                    </div>

                    @if ($studentAssignment->feedback)
                        <section class="mt-6">
                            <h4 class="text-base font-semibold text-gray-700 mb-4">Комментарий от преподавателя</h4>
                            <textarea name="feedback" id="feedback" rows="5"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 mb-4 resize-y focus:outline-none focus:ring-2 focus:ring-[#6E76C1]"
                                placeholder="Введите комментарий" readonly>{{ $studentAssignment->feedback }}</textarea>
                        </section>
                    @endif
                @endif
            </aside>
        </form>
    </div>
@endsection

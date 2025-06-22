@extends('pages.platform.layout', ['activePage' => 'null', 'title' => $studentAssignment->assignment->title, 'quick_action' => 'null'])

@section('content')
    <div class="main-platform mx-auto bg-white rounded-2xl shadow-lg p-8 max-w-6xl min-h-[90vh] flex flex-col">
        <div
            class="bg-white py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sm:gap-0">
            <div class="flex items-center gap-4">
                <a href="{{ route('assignments.to.grade') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-md transition duration-200 ">
                    <i class="fas fa-arrow-left mr-2"></i>Назад
                </a>
                <i class="fas fa-clipboard-check text-[#6E76C1] text-2xl flex-shrink-0"></i>
                <h1 class="text-2xl font-bold text-gray-900 truncate max-w-xs sm:max-w-sm md:max-w-xl lg:max-w-2xl transition-transform duration-300 hover:text-[#6E76C1]"
                    title="{{ $studentAssignment->assignment->title }}">
                    {{ $studentAssignment->assignment->title }}
                </h1>
            </div>
        </div>

        <form action="{{ route('assignment.grade.save', $studentAssignment->id) }}" method="POST" id="grading-form"
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

                    @php
                        $hasOnlyTextOrFile = true;
                        foreach ($answers as $answer) {
                            if (!in_array($answer['type'], ['text', 'file_upload'])) {
                                $hasOnlyTextOrFile = false;
                                break;
                            }
                        }
                    @endphp

                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md shadow-sm mb-6">
                        <div class="flex items-start gap-4">
                            <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
                            <div class="flex-1">
                                @if ($hasOnlyTextOrFile)
                                    <p class="text-yellow-700 font-medium mb-3">
                                        Автоматическая проверка недоступна: задание содержит только текстовые ответы или
                                        загрузку файлов.
                                    </p>
                                @else
                                    @if (isset($percentCorrect))
                                        <p class="font-medium text-gray-800">
                                            Процент правильных ответов:
                                            <span class="text-[#6E76C1]">{{ $percentCorrect }}%</span>
                                            <span class="relative">
                                                <i class="fas fa-info-circle text-gray-500 cursor-pointer ml-2"
                                                    id="info-icon"></i>
                                                <div id="tooltip"
                                                    class="absolute hidden bg-gray-100 border border-gray-300 text-gray-700 text-sm rounded-md shadow-lg p-4 w-80 z-10">
                                                    Процент правильных ответов рассчитывается как отношение количества
                                                    правильных ответов к
                                                    максимальному числу возможных правильных по всем вопросам (не включая
                                                    текстовые ответы и
                                                    загрузку файлов).
                                                    <br>
                                                    Итоговая оценка — это процент, приведенный к шкале от 0 до 100.
                                                </div>
                                            </span>
                                        </p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    @if (!empty($answers))
                        @foreach ($answers as $index => $answer)
                            @php
                                $field = $assignmentFields[$index] ?? null;
                                $questionTitle = $field['name'] ?? 'Вопрос ' . ($index + 1);
                                $typeTranslations = [
                                    'file_upload' => 'Загрузка файла',
                                    'multiple_choice' => 'Множественный выбор',
                                    'single_choice' => 'Один выбор',
                                    'text' => 'Текстовый ответ',
                                ];

                                if (in_array($answer['type'], ['multiple_choice', 'single_choice'])) {
                                    $totalOptions = count($field['options'] ?? []);
                                    $correctOptions = count(
                                        array_filter($field['options'], fn($option) => $option['isCorrect']),
                                    );
                                    $selectedOptions = $answer['selected_options'] ?? [];
                                    $correctSelected = count(
                                        array_intersect(
                                            $selectedOptions,
                                            array_keys(
                                                array_filter($field['options'], fn($option) => $option['isCorrect']),
                                            ),
                                        ),
                                    );
                                    $percentageCorrect = ($correctSelected / $correctOptions) * 100;

                                    $maxScore = 100;
                                    $calculatedScore = round(($percentageCorrect / 100) * $maxScore);
                                }
                            @endphp

                            <article
                                class="bg-gray-50 border border-gray-200 rounded-xl shadow-sm p-6 mb-4 hover:shadow-md transition-shadow">
                                <h4 class="font-semibold text-gray-800 mb-3">{{ $questionTitle }}</h4>
                                <p class="font-semibold text-gray-700 mb-3">Тип:
                                    {{ $typeTranslations[$answer['type']] ?? 'Неизвестный тип' }}</p>

                                @if ($answer['type'] === 'text')
                                    <p class="text-gray-800 whitespace-pre-wrap">{{ $answer['value'] ?? 'Без текста' }}
                                    </p>
                                @elseif ($answer['type'] === 'file_upload')
                                    <p>
                                        <a href="{{ asset('storage/' . $answer['file_path']) }}" target="_blank"
                                            class="text-blue-600 hover:underline font-medium">
                                            {{ $answer['file_name'] }}
                                        </a>
                                    </p>
                                @elseif (in_array($answer['type'], ['single_choice', 'multiple_choice']))
                                    @if (!empty($field['options']) && is_array($field['options']))
                                        <ul class="list-disc ml-5 space-y-1">
                                            @foreach ($field['options'] as $optionIndex => $option)
                                                @php
                                                    $isSelected = in_array(
                                                        (string) $optionIndex,
                                                        $answer['selected_options'] ?? [],
                                                    );
                                                    $isCorrect = $option['isCorrect'] ?? false;
                                                @endphp
                                                <li
                                                    class="{{ $isCorrect ? 'text-green-600' : 'text-red-600' }} leading-snug">
                                                    {{ $option['value'] }}
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
                                        <p class="text-gray-500 italic">Нет доступных вариантов.</p>
                                    @endif
                                @endif

                                <label for="score-{{ $index }}"
                                    class="block text-sm font-medium text-gray-700 mt-4">Баллы (от 0 до 100):</label>
                                <input type="number" name="scores[{{ $index }}]" id="score-{{ $index }}"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#6E76C1]"
                                    min="0" max="100"
                                    value="{{ old('scores.' . $index, $calculatedScore ?? 0) }}"
                                    oninput="updateTotalGrade()" required>
                            </article>
                        @endforeach
                    @else
                        <p class="text-center text-red-600 italic py-6">Ответ не предоставлен.</p>
                    @endif
                </section>

                @if (!empty($detailedStats))
                    <section>
                        <h3 class="text-lg font-semibold text-gray-700 mb-6 border-b border-gray-200 pb-3">Детали по каждому
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
                <section>
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

                <section>
                    <h4 class="text-lg font-semibold text-gray-700 mb-1">
                        Итоговая оценка
                    </h4>
                    <p class="text-sm text-gray-500 mb-4">(от 0 до 100)</p>
                    <input type="number" id="final-grade" name="grade" min="0" max="100"
                        value="{{ old('grade', $studentAssignment->grade ?? 0) }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mb-4 focus:outline-none focus:ring-2 focus:ring-[#6E76C1]"
                        placeholder="Введите оценку (0-100)">

                    <h4 class="text-base font-semibold text-gray-700 mb-4">Комментарий от преподавателя</h4>
                    <textarea name="feedback" id="feedback" rows="5"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mb-4 resize-y focus:outline-none focus:ring-2 focus:ring-[#6E76C1]"
                        placeholder="Введите комментарий">{{ old('feedback', $autoFeedback) }}</textarea>
                </section>

                <section class="sticky bottom-0 bg-white border-t border-gray-200 pt-4 pb-2 px-6 -mx-6">
                    <button type="submit"
                        class="w-full bg-[#6E76C1] text-white font-semibold py-2 rounded-md hover:bg-[#5a65b0] transition">
                        Сохранить оценку
                    </button>
                </section>
            </aside>


        </form>
    </div>

    <script>
        function updateTotalGradeAndFeedback() {
            const scoreInputs = document.querySelectorAll('input[name^="scores"]');
            const maxScorePerQuestion = 100;
            let totalScore = 0;
            let maxPossibleScore = 0;
            let isValid = true;

            scoreInputs.forEach(input => {
                const val = parseFloat(input.value);
                const maxVal = parseFloat(input.getAttribute('max')) || maxScorePerQuestion;

                if (!isNaN(val)) {
                    if (val < 0 || val > maxVal) {
                        input.classList.add('border-red-500');
                        isValid = false;
                    } else {
                        input.classList.remove('border-red-500');
                        totalScore += val;
                        maxPossibleScore += maxVal;
                    }
                }
            });

            if (!isValid) {
                alert("Убедитесь, что все баллы находятся в допустимом диапазоне!");
                return;
            }

            const finalGradeInput = document.getElementById('final-grade');
            const feedbackTextarea = document.getElementById('feedback');

            const calculatedGrade = Math.min((totalScore / maxPossibleScore) * 100, 100);
            if (finalGradeInput) {
                finalGradeInput.value = Math.round(calculatedGrade);
            }

            if (feedbackTextarea) {
                let feedback = '';
                if (calculatedGrade >= 90) {
                    feedback = "Отличная работа! Все вопросы выполнены на высоком уровне.";
                } else if (calculatedGrade >= 70) {
                    feedback = "Хорошо выполнено. Есть небольшие ошибки.";
                } else if (calculatedGrade >= 50) {
                    feedback = "Работа выполнена удовлетворительно. Рекомендуется повторить материал.";
                } else {
                    feedback = "Много ошибок. Рекомендую пересмотреть тему и попробовать снова.";
                }
                feedbackTextarea.value = feedback;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateTotalGradeAndFeedback();
            document.querySelectorAll('input[name^="scores"]').forEach(input => {
                input.addEventListener('input', updateTotalGradeAndFeedback);
            });

            const finalGradeInput = document.getElementById('final-grade');
            if (finalGradeInput) {
                finalGradeInput.addEventListener('input', updateTotalGradeAndFeedback);
            }

            const infoIcon = document.getElementById('info-icon');
            const tooltip = document.getElementById('tooltip');

            if (infoIcon && tooltip) {
                infoIcon.addEventListener('mouseover', () => {
                    tooltip.classList.remove('hidden');
                });
                infoIcon.addEventListener('mouseout', () => {
                    tooltip.classList.add('hidden');
                });
            }
        });
    </script>
@endsection

@extends('pages.platform.layout', ['activePage' => 'null', 'title' => $studentAssignment->assignment->title, 'quick_action' => 'null'])

@section('content')
    <div class="main-platform mx-auto bg-white rounded-2xl shadow-lg p-8 max-w-6xl">
        <div class="result-header text-center mb-10">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                Проверка задания
            </h1>
            <p class="mt-3 text-lg text-gray-600 font-medium">
                <span class="font-semibold">Задание:</span> {{ $studentAssignment->assignment->title }}
            </p>
            <p class="mt-2 text-sm text-gray-500 flex items-center justify-center gap-3">
                <i class="fas fa-user text-[#6E76C1]"></i>
                <span class="font-semibold text-gray-700">
                    {{ $studentAssignment->user->name }} {{ $studentAssignment->user->surname }}
                </span>
                <span
                    class="px-3 py-1 rounded-full text-xs font-semibold {{ $studentAssignment->status === 'submitted' ? 'bg-red-200 text-red-700' : 'bg-green-200 text-green-800' }}">
                    {{ $studentAssignment->status === 'submitted' ? 'На проверке' : 'Проверено' }}
                </span>
            </p>
            <div class="mt-6">
                <a href="{{ route('assignments.to.grade') }}"
                    class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md transition">
                    Назад
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <!-- Левая большая колонка с ответами -->
            <div class="md:col-span-2 space-y-8">
                <section>
                    <h3 class="text-lg font-semibold text-gray-700 mb-6 border-b border-gray-200 pb-3">Ответ студента</h3>

                    @if (isset($percentCorrect))
                        <p class="mb-6 font-medium text-gray-800">Процент правильных ответов: <span
                                class="text-[#6E76C1]">{{ $percentCorrect }}%</span></p>
                    @endif

                    @if (!empty($answers))
                        @foreach ($answers as $index => $answer)
                            <article class="bg-gray-50 border border-gray-200 rounded-xl shadow-sm p-6">
                                <p class="font-semibold text-gray-700 mb-3">Тип:
                                    {{ $questionTypes[$answer['type']] ?? 'Неизвестный тип' }}</p>

                                @if ($answer['type'] === 'text')
                                    <p class="text-gray-800 whitespace-pre-wrap">{{ $answer['value'] ?? 'Без текста' }}</p>
                                @elseif ($answer['type'] === 'file_upload')
                                    <p>
                                        <a href="{{ asset('storage/' . $answer['file_path']) }}" target="_blank"
                                            class="text-blue-600 hover:underline font-medium">
                                            {{ $answer['file_name'] }}
                                        </a>
                                    </p>
                                @elseif (in_array($answer['type'], ['single_choice', 'multiple_choice']))
                                    @if (isset($assignmentFields[$index]))
                                        @php $field = $assignmentFields[$index]; @endphp
                                        @if (!empty($field['options']) && is_array($field['options']))
                                            <ul class="list-disc ml-5 space-y-1">
                                                @foreach ($field['options'] as $optionIndex => $option)
                                                    @php
                                                        $isSelected = in_array(
                                                            (string) $optionIndex,
                                                            $answer['selected_options'],
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
                                @endif
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
                                        <th class="px-4 py-2">Правильных нужно</th>
                                        <th class="px-4 py-2">Выбрано верно</th>
                                        <th class="px-4 py-2">Процент</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detailedStats as $stat)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="px-4 py-2">{{ $stat['question'] }}</td>
                                            <td class="px-4 py-2">{{ $questionTypes[$stat['type']] ?? 'Неизвестный тип' }}
                                            </td>
                                            <td class="px-4 py-2">{{ $stat['correct_needed'] }}</td>
                                            <td class="px-4 py-2">{{ $stat['correct_given'] }}</td>
                                            <td class="px-4 py-2">{{ $stat['percent'] }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>
                @endif
            </div>

            <!-- Правая колонка с деталями и формой оценки -->
            <aside class="space-y-8">
                <section class="bg-white border border-gray-200 rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-6 border-b border-gray-200 pb-3">Информация о задании
                    </h3>
                    <ul class="space-y-5 text-gray-700">
                        <li class="flex items-center gap-4">
                            <i class="fas fa-chalkboard-teacher text-[#6E76C1] text-xl"></i>
                            <div>
                                <p class="text-sm text-gray-500">Класс</p>
                                <p class="font-medium text-gray-800">
                                    {{ optional($studentAssignment->assignment->class)->name ?? 'Не указан' }}</p>
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
                                    {{ $studentAssignment->assignment->teacher->name ?? 'Неизвестный преподаватель' }}</p>
                            </div>
                        </li>
                    </ul>
                </section>

                <section class="bg-white border border-gray-200 rounded-xl shadow p-6">
                    <form action="{{ route('assignment.grade.save', $studentAssignment->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h4 class="text-base font-semibold text-gray-700 mb-4">Итоговая оценка</h4>
                        <input type="number" name="grade" min="0" max="100"
                            value="{{ old('grade', $studentAssignment->grade) }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 mb-4 focus:outline-none focus:ring-2 focus:ring-[#6E76C1]"
                            placeholder="Введите оценку (0-100)" required>

                        <h4 class="text-base font-semibold text-gray-700 mb-4">Комментарий от преподавателя</h4>
                        <textarea name="feedback" rows="5"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 mb-4 resize-y focus:outline-none focus:ring-2 focus:ring-[#6E76C1]"
                            placeholder="Введите комментарий">{{ old('feedback', $studentAssignment->feedback) }}</textarea>

                        <button type="submit"
                            class="w-full bg-[#6E76C1] text-white font-semibold py-2 rounded-md hover:bg-[#5a65b0] transition">
                            Сохранить оценку
                        </button>
                    </form>
                </section>
            </aside>
        </div>
    </div>
@endsection

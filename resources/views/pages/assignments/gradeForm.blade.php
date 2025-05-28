<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $studentAssignment->assignment->title }}</title>
    <link rel="stylesheet" href="/css/style-platform.css">
    <link rel="stylesheet" href="/css/layout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css ">
    <link rel="icon" href="{{ asset('icon-logo.svg') }}" type="image/svg+xml">

</head>
<style>
    .grading-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .grading-table thead {
        background-color: #f1f3f5;
    }

    .grading-table th,
    .grading-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #dee2e6;
    }

    .grading-table tr:hover {
        background-color: #f8f9fa;
    }
</style>

<body>
    @include('layout.sidebar', ['activePage' => 'assignments'])

    <div class="topbar">
        @include('layout.topbar')

        <main>
            <div class="main-platform">
                <div class="assignment-grade">
                    <div class="card">
                        <div class="card-header">
                            <h2>Проверка задания</h2>
                            <a href="{{ route('assignments.to.grade') }}" class="btn btn-secondary">Назад</a>
                        </div>

                        <div class="card-body">
                            <section class="assignment-info">
                                <h4>Информация о задании</h4>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <p><strong>Заголовок:</strong> {{ $studentAssignment->assignment->title }}</p>
                                        <p><strong>Студент:</strong> {{ $studentAssignment->user->name }}
                                            {{ $studentAssignment->user->surname }}</p>
                                    </div>
                                    <div class="info-item">
                                        <p><strong>Класс:</strong>
                                            {{ optional($studentAssignment->assignment->class)->name ?? 'Не указан' }}
                                        </p>
                                        <p>
                                            <strong>Статус:</strong>
                                            <span
                                                class="badge
                                                {{ $studentAssignment->status === 'submitted' ? 'bg-danger' : 'bg-success' }}">
                                                {{ $studentAssignment->status === 'submitted' ? 'На проверке' : 'Проверено' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </section>
                            @php
                                $questionTypes = [
                                    'text' => 'Текст',
                                    'file_upload' => 'Загрузка файла',
                                    'single_choice' => 'Один вариант',
                                    'multiple_choice' => 'Несколько вариантов',
                                ];
                            @endphp

                            <section class="student-answers">
                                <h4>Ответ студента</h4>
                                @if (isset($percentCorrect))
                                    <div class="grading-stats">
                                        <p><strong>Процент правильных ответов:</strong> {{ $percentCorrect }}%</p>
                                    </div>
                                @endif
                                @if (!empty($answers))
                                    @foreach ($answers as $index => $answer)
                                        <div class="student-answer">
                                            <p><strong>Тип:</strong>
                                                {{ $questionTypes[$answer['type']] ?? 'Неизвестный тип' }}</p>

                                            @if ($answer['type'] === 'text')
                                                <p>{{ $answer['value'] ?? 'Без текста' }}</p>
                                            @elseif ($answer['type'] === 'file_upload')
                                                <p>
                                                    <a href="{{ asset('storage/' . $answer['file_path']) }}"
                                                        target="_blank">
                                                        {{ $answer['file_name'] }}
                                                    </a>
                                                </p>
                                            @elseif (in_array($answer['type'], ['single_choice', 'multiple_choice']))
                                                @if (isset($assignmentFields[$index]))
                                                    @php
                                                        $field = $assignmentFields[$index];
                                                    @endphp

                                                    @if (!empty($field['options']) && is_array($field['options']))
                                                        <ul>
                                                            @foreach ($field['options'] as $optionIndex => $option)
                                                                @php
                                                                    $isSelected = in_array(
                                                                        (string) $optionIndex,
                                                                        $answer['selected_options'],
                                                                    );
                                                                    $isCorrect = $option['isCorrect'] ?? false;
                                                                @endphp

                                                                <li style="color: {{ $isCorrect ? 'green' : 'red' }};">
                                                                    {{ $option['value'] }}

                                                                    @if ($isSelected)
                                                                        <small class="correct"
                                                                            style="color: {{ $isCorrect ? 'green' : 'red' }}">
                                                                            {{ $isCorrect ? '✅ Выбран и правильный' : '❌ Выбран, но неверный' }}
                                                                        </small>
                                                                    @else
                                                                        @if ($isCorrect)
                                                                            <small class="correct"
                                                                                style="color: green;">📌 Пропущен
                                                                                правильный вариант</small>
                                                                        @endif
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <p>Нет доступных вариантов.</p>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <p class="no-answer">Ответ не предоставлен.</p>
                                @endif
                            </section>

                            @if (!empty($detailedStats))
                                <section class="grading-details">
                                    <h4>Детали по каждому вопросу:</h4>
                                    <table class="grading-table">
                                        <thead>
                                            <tr>
                                                <th>Вопрос</th>
                                                <th>Тип</th>
                                                <th>Правильных нужно</th>
                                                <th>Выбрано верно</th>
                                                <th>Процент</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($detailedStats as $stat)
                                                <tr>
                                                    <td>{{ $stat['question'] }}</td>
                                                    <td>{{ $questionTypes[$stat['type']] ?? 'Неизвестный тип' }}</td>
                                                    <td>{{ $stat['correct_needed'] }}</td>
                                                    <td>{{ $stat['correct_given'] }}</td>
                                                    <td>{{ $stat['percent'] }}%</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </section>
                            @endif
                            <section class="grading-form">

                                <form action="{{ route('assignment.grade.save', $studentAssignment->id) }}"
                                    method="POST" style="margin-bottom: 0px">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="grade">Оценка (от 0 до 100):</label>
                                        <input type="number" name="grade" id="grade"
                                            value="{{ old('grade', $studentAssignment->grade ?? $autoGrade) }}"
                                            class="{{ $errors->has('grade') ? 'input-error' : '' }}" min="0"
                                            max="100">

                                        @error('grade')
                                            <span class="error-message">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="feedback">Комментарий:</label>
                                        <textarea name="feedback" id="feedback" rows="5" class="{{ $errors->has('feedback') ? 'input-error' : '' }}">{{ old('feedback', $studentAssignment->feedback ?? $autoFeedback) }}</textarea>

                                        @error('feedback')
                                            <span class="error-message">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <button type="submit" class="action-button">
                                        Сохранить оценку
                                    </button>
                                </form>

                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>

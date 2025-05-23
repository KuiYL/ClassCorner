<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $assignment->title }}</title>
    <link rel="stylesheet" href="{{ asset('css/style-platform.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css ">
</head>

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
                            <a href="{{ route('assignments.to.grade') }}" class="btn secondary">Назад</a>
                        </div>

                        <div class="card-body">
                            <p><strong>Задание:</strong> {{ $studentAssignment->assignment->title }}</p>
                            <p><strong>Студент:</strong> {{ $studentAssignment->user->name }}
                                {{ $studentAssignment->user->surname }}</p>
                            <p><strong>Класс:</strong>
                                {{ optional($studentAssignment->assignment->class)->name ?? 'Не указан' }}</p>
                            <p><strong>Статус:</strong>
                                {{ $studentAssignment->status === 'submitted' ? 'Срочно' : 'Проверено' }}
                            </p>

                            <hr>

                            <h4>Ответ студента</h4>
                            @if (!empty($answers))
                                @foreach ($answers as $answer)
                                    <div class="student-answer">
                                        <p><strong>Вопрос:</strong> {{ $answer['name'] }}</p>

                                        @if ($answer['type'] === 'text')
                                            <p>{{ $answer['value'] }}</p>
                                        @elseif (in_array($answer['type'], ['single_choice', 'multiple_choice']))
                                            <ul>
                                                @foreach ($answer['selected_options'] as $index)
                                                    <li>
                                                        {{ $answer['options'][$index]['value'] }}
                                                        @if ($answer['options'][$index]['isCorrect'])
                                                            🔹Правильный
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <p>Ответ не предоставлен.</p>
                            @endif

                            <hr>

                            <form action="{{ route('assignment.grade.save', $studentAssignment->id) }}" method="POST">
                                @csrf
                                @method('POST')

                                <div class="form-group full">
                                    <label for="grade">Оценка (от 0 до 100):</label>
                                    <input type="number" name="grade" id="grade"
                                        value="{{ old('grade', $studentAssignment->grade) }}" class="form-control"
                                        min="0" max="100">
                                </div>

                                <div class="form-group full">
                                    <label for="feedback">Комментарий:</label>
                                    <textarea name="feedback" id="feedback" rows="5" class="form-control">{{ old('feedback', $studentAssignment->feedback) }}</textarea>
                                </div>

                                <button type="submit" class="btn primary large full-width mt-2">
                                    Сохранить оценку
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>

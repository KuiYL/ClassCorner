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
            <div class="main-platform assignment-detail">
                <div class="assignment-detail">
                    <div class="card">
                        <div class="card-header">
                            <h2>{{ $assignment->title }}</h2>
                        </div>

                        <div class="card-body">
                            <p><strong>Класс:</strong> {{ optional($assignment->class)->name ?? 'Не указан' }}</p>
                            <p><strong>Описание:</strong></p>
                            <div class="description">
                                {{ $assignment->description }}
                            </div>

                            <hr>

                            <div class="due-date-container">
                                <i class="fas fa-calendar-alt"></i>
                                <span class="due-date-label">Срок выполнения:</span>
                                <strong class="due-date-value">
                                    {{ \Carbon\Carbon::parse($assignment->due_date)->format('d.m.Y') }}
                                </strong>
                            </div>

                            <hr>

                            <h3>Вопросы задания:</h3>
                            @if (count($assignmentFields))
                                <form id="student-answer-form" action="{{ route('assignment.submit.answer', $assignment->id) }}" method="POST">
                                    @csrf
                                    <div class="fields">
                                        @foreach ($assignmentFields as $index => $field)
                                            <div class="field-item">
                                                <h4>{{ $field['name'] }}</h4>

                                                @if ($field['type'] === 'text')
                                                    <textarea name="answers[{{ $index }}][value]" rows="3" placeholder="Введите ваш ответ здесь" required></textarea>
                                                @elseif ($field['type'] === 'file_upload')
                                                    <input type="file" name="answers[{{ $index }}][file]"
                                                        required>
                                                @elseif (in_array($field['type'], ['multiple_choice', 'single_choice']))
                                                    <ul class="options-list">
                                                        @foreach ($field['options'] as $optionIndex => $option)
                                                            <li>
                                                                <label>
                                                                    <input
                                                                        type="{{ $field['type'] === 'single_choice' ? 'radio' : 'checkbox' }}"
                                                                        name="answers[{{ $index }}][options][]"
                                                                        value="{{ $optionIndex }}"
                                                                        {{ $option['isCorrect'] ? 'disabled' : '' }}>
                                                                    {{ $option['value'] }}
                                                                </label>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif

                                                <input type="hidden" name="answers[{{ $index }}][type]"
                                                    value="{{ $field['type'] }}">
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="card-footer">
                                        <button type="submit" class="btn primary large full-width">
                                            <i class="fas fa-save"></i> Сохранить ответ
                                        </button>
                                    </div>
                                </form>
                            @else
                                <p>Задание не содержит вопросов.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>

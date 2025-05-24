<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $assignment->title }}</title>
    <link rel="stylesheet" href="{{ asset('css/style-platform.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css ">
    <script src="{{ asset('js/script.js') }}" defer></script>
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
                            <div class="due-date-container">
                                <span class="due-date-label">Срок выполнения:</span>
                                <strong class="due-date-value">
                                    {{ \Carbon\Carbon::parse($assignment->due_date)->format('d.m.Y') }}
                                </strong>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="info">
                                <div class="description">
                                    {{ $assignment->description }}
                                </div>
                            </div>
                            <hr>

                            @if (count($assignmentFields))
                                <div class="fields">
                                    <h3>Поля задания:</h3>

                                    @foreach ($assignmentFields as $field)
                                        <div class="field-item">
                                            <h4>{{ $field['name'] }}</h4>

                                            @if ($field['type'] === 'text')
                                                <div class="field-type-box text-field">
                                                    <p><strong>Тип:</strong> Текст</p>
                                                    <p><em>Студент должен ввести свой ответ.</em></p>
                                                </div>
                                            @endif

                                            @if ($field['type'] === 'file_upload')
                                                <div class="field-type-box file-field">
                                                    <p><strong>Тип:</strong> Загрузка файла</p>
                                                    <p><em>Студент должен загрузить файл.</em></p>
                                                </div>
                                            @endif

                                            @if (in_array($field['type'], ['multiple_choice', 'single_choice']) && !empty($field['options']))
                                                <ul class="options-list">
                                                    @foreach ($field['options'] as $option)
                                                        <li>
                                                            <label>
                                                                <input
                                                                    type="{{ $field['type'] === 'single_choice' ? 'radio' : 'checkbox' }}"
                                                                    disabled
                                                                    {{ $option['isCorrect'] ? 'checked' : '' }}>
                                                                {{ $option['value'] }}
                                                                @if ($option['isCorrect'])
                                                                    <small class="correct">✅ Правильный</small>
                                                                @endif
                                                            </label>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="no-fields">Нет полей в задании.</p>
                            @endif
                        </div>

                        <div class="card-footer">
                            <a href="{{ route('assignments.edit', ['id' => $assignment->id, 'class_id' => $assignment->class_id, 'return_url' => url()->current()]) }}"
                                class="btn btn-edit">
                                <i class="fas fa-edit"></i> Изменить
                            </a>
                            <button class="btn btn-delete delete-button" type="button" data-id="{{ $assignment->id }}"
                                data-name="{{ $assignment->title }}" data-type="assignment">
                                <i class="fas fa-trash"></i> Удалить
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    @include('layout.modal-delete')
</body>

</html>

@extends('pages.platform.layout', ['activePage' => 'tasks', 'title' => $assignment->title, 'quick_action' => 'null'])
@section('content')
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
                                                            disabled {{ $option['isCorrect'] ? 'checked' : '' }}>
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
@endsection

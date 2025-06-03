@extends('pages.platform.layout', ['activePage' => 'null', 'title' => $assignment->title, 'quick_action' => 'null'])
@section('content')
    <style>
        :root {
            --bg: #f8f9fa;
            --card-bg: #fff;
            --primary: #007bff;
            --success: #28a745;
            --danger: #dc3545;
            --text-dark: #212529;
            --text-light: #6c757d;
            --border-radius: 10px;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .field-item {
            background-color: #f9f9f9;
            border-radius: var(--border-radius);
            padding: 1.25rem;
            box-shadow: var(--shadow);
            transition: transform 0.2s ease;
        }

        .field-item:hover {
            transform: translateY(-2px);
        }

        .field-type-box {
            margin-top: 0.5rem;
            padding: 0.75rem;
            border-radius: var(--border-radius);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .text-field {
            background-color: #e9f7ef;
            color: #155724;
        }

        .file-field {
            background-color: #e3f2fd;
            color: #0d47a1;
        }

        .options-list {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        .options-list li {
            margin-bottom: 0.5rem;
        }

        .options-list label {
            display: block;
            padding: 0.5rem 0.75rem;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .options-list label:hover {
            background-color: #f1f3f5;
        }

        .correct {
            color: var(--success);
            font-weight: bold;
            margin-left: 0.5rem;
        }

        .file-upload-container {
            position: relative;
        }

        .custom-file-input {
            opacity: 0;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .custom-file-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            border: 1px solid #ced4da;
            border-radius: var(--border-radius);
            background-color: #fff;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .custom-file-label:hover {
            background-color: #f1f3f5;
        }

        .custom-file-name {
            color: var(--text-light);
            font-size: 0.9rem;
            overflow: hidden;
            white-space: nowrap;
        }

        .file-hint {
            font-size: 0.85rem;
            color: var(--text-light);
            margin-top: 0.5rem;
            display: block;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            border: 1px solid #ced4da;
            border-radius: var(--border-radius);
            outline: none;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2);
        }
    </style>

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
                    <form id="student-answer-form" action="{{ route('assignment.submit.answer', $assignment->id) }}"
                        method="POST" enctype="multipart/form-data"
                        style="display: block; max-width: none; margin-bottom: 0rem;">
                        @csrf
                        @if (count($assignmentFields))
                            <div class="fields">
                                <h3>Поля задания:</h3>
                                @foreach ($assignmentFields as $index => $field)
                                    <div class="field-item">
                                        <h4>{{ $field['name'] }}</h4>

                                        @if ($field['type'] === 'text')
                                            <textarea name="answers[{{ $index }}][value]" rows="4" placeholder="Введите ваш ответ здесь..." required
                                                class="form-control"></textarea>
                                        @elseif ($field['type'] === 'file_upload')
                                            <div class="file-upload-container">
                                                <input type="file" name="answers[{{ $index }}][file]"
                                                    class="custom-file-input" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png"
                                                    required>
                                                <label class="custom-file-label">
                                                    <span class="custom-file-name">Выберите файл...</span>
                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                </label>
                                            </div>
                                            <small class="file-hint">
                                                Поддерживаемые форматы: PDF, DOC, DOCX, TXT, JPG, JPEG, PNG.
                                                Максимальный размер файла — 10 МБ.
                                            </small>
                                        @elseif (in_array($field['type'], ['multiple_choice', 'single_choice']) && !empty($field['options']))
                                            <ul class="options-list">
                                                @foreach ($field['options'] as $optionIndex => $option)
                                                    <li>
                                                        <label>
                                                            <input
                                                                type="{{ $field['type'] === 'single_choice' ? 'radio' : 'checkbox' }}"
                                                                name="answers[{{ $index }}][options][]"
                                                                value="{{ $optionIndex }}">
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
                        @else
                            <p class="no-fields">Нет полей в задании.</p>
                        @endif
                        <button type="submit" class="action-button" style="display: flex; gap:6px">
                            <i class="fas fa-save"></i> Сохранить ответ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.custom-file-input').forEach(input => {
            input.addEventListener('change', function() {
                const fileName = this.files[0]?.name || 'Выберите файл...';
                const label = this.closest('.file-upload-container').querySelector('.custom-file-name');
                if (label) {
                    label.textContent = fileName;
                }
            });
        });
    </script>
@endsection

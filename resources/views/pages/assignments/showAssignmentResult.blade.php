<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $studentAssignment->assignment->title }}</title>
    <link rel="stylesheet" href="{{ asset('css/style-platform.css') }}">
    <link rel="stylesheet" href="/css/layout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css ">
    <link rel="icon" href="{{ asset('icon-logo.svg') }}" type="image/svg+xml">

    <style>
        :root {
            --primary: #007bff;
            --success: #28a745;
            --danger: #dc3545;
            --bg-card: #ffffff;
            --bg-light: #f8f9fa;
            --text-dark: #212529;
            --text-muted: #6c757d;
            --border-radius: 10px;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }

        .main-platform {
            max-width: 900px;
            margin: 2rem auto;
            background-color: var(--bg-card);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 2rem;
        }

        .result-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .result-header h2 {
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .badge {
            display: inline-block;
            padding: 0.3rem 0.7rem;
            font-size: 0.85rem;
            font-weight: bold;
            border-radius: 5px;
            margin-left: 10px;
        }

        .bg-success {
            background-color: #d4edda;
            color: #155724;
        }

        .assignment-details,
        .grading-stats,
        .feedback-section,
        .grading-form {
            margin-bottom: 1.5rem;
        }

        .assignment-details h4,
        .grading-result h4,
        .student-answers h4,
        .grading-form h4 {
            margin-top: 0;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }

        .info-grid {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .info-item {
            flex: 1;
            background-color: #f1f3f5;
            padding: 1rem;
            border-radius: var(--border-radius);
        }

        .info-item p {
            margin: 0.3rem 0;
        }

        .grading-stats {
            background-color: #e9f7ef;
            border-left: 4px solid var(--success);
            padding: 1rem;
            border-radius: var(--border-radius);
        }

        .student-answer {
            margin-top: 1.5rem;
        }

        .option-item {
            display: flex;
            align-items: baseline;
            margin-bottom: 0.5rem;
        }

        .option-correct {
            color: green;
        }

        .option-incorrect {
            color: red;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            border: 1px solid #ced4da;
            border-radius: var(--border-radius);
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2);
        }

        .grade-box {
            background-color: #f1f3f5;
            padding: 1rem;
            border-radius: var(--border-radius);
            font-size: 1.1rem;
        }

        .feedback-section textarea {
            margin-top: 1rem;
            width: 100%;
            min-height: 120px;
            padding: 1rem;
            font-size: 1rem;
            border: 1px solid #ced4da;
            border-radius: var(--border-radius);
            resize: vertical;
            background-color: #f8f9fa;
        }

        .card-footer {
            text-align: center;
            margin-top: 2rem;
        }

        .action-button i {
            margin-right: 0.5rem;
        }

        .student-answer ul {
            list-style-type: none;
            padding-left: 0;
        }

        .student-answer li {
            padding: 0.5rem 0.75rem;
            border-radius: var(--border-radius);
            margin-bottom: 0.3rem;
            transition: background-color 0.2s;
        }

        .student-answer li:hover {
            background-color: #f1f3f5;
        }

        .student-answer small.correct {
            margin-left: 0.5rem;
        }

        @media (max-width: 768px) {
            .info-grid {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    @include('layout.sidebar', ['activePage' => 'assignments'])

    <div class="topbar">
        @include('layout.topbar')

        <main>
            <div class="main-platform">
                <div class="result-header">
                    <h2>Результат выполнения задания</h2>
                    <p><strong>Задание:</strong> {{ $studentAssignment->assignment->title }}</p>
                    <p><strong>Студент:</strong> {{ $studentAssignment->user->name }}
                        {{ $studentAssignment->user->surname }}</p>
                    <span class="badge bg-success">Проверено</span>
                </div>

                <section class="assignment-details">
                    <h4>Информация о задании</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <p><i class="fas fa-chalkboard-teacher"></i> <strong>Класс:</strong>
                                {{ optional($studentAssignment->assignment->class)->name ?? 'Не указан' }}</p>
                            <p><i class="fas fa-calendar-alt"></i> <strong>Дедлайн:</strong>
                                {{ \Carbon\Carbon::parse($studentAssignment->assignment->due_date)->format('d.m.Y') }}
                            </p>
                        </div>
                        <div class="info-item">
                            <p><i class="fas fa-percent"></i> <strong>Процент правильных:</strong>
                                {{ $percentCorrect }}%</p>
                            <p><i class="fas fa-star-half-alt"></i> <strong>Оценка:</strong>
                                <strong style="color: var(--primary);">{{ $studentAssignment->grade }}</strong>/100
                            </p>
                        </div>
                    </div>
                </section>

                @if ($user->role === 'teacher')
                    <section class="grading-form">

                        <form action="{{ route('assignment.grade.save', $studentAssignment->id) }}" method="POST"
                            style="margin-bottom: 0px">
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
                @else
                    <div class="feedback-section">
                        <h4><i class="fas fa-comment-dots"></i> Комментарий преподавателя</h4>
                        <textarea readonly>{{ $studentAssignment->feedback ?? 'Нет комментария' }}</textarea>
                    </div>
                @endif
            </div>
        </main>
    </div>
</body>

</html>

@extends('pages.admin.layout')

@section('content')
    <div class="admin-dashboard">
        <h2>Админ-панель</h2>
        <div class="stats">
            <div class="stat-card">
                <h4>Пользователи</h4>
                <p>{{ $users->count() }}</p>
            </div>
            <div class="stat-card">
                <h4>Классы</h4>
                <p>{{ $classes->count() }}</p>
            </div>
            <div class="stat-card">
                <h4>Задания</h4>
                <p>{{ $assignments->count() }}</p>
            </div>
        </div>

        <div class="section">
            <h3>Последние задания</h3>
            <ul>
                @foreach ($assignments as $assignment)
                    <li>
                        {{ $assignment->title }}
                        <small>({{ optional($assignment->class)->name ?? 'Без класса' }})</small>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="section">
            <h3>Статистика по классам</h3>
            <div class="progress-list">
                @foreach ($classes as $class)
                    <div class="progress-item">
                        <div class="progress-label">
                            <span>{{ $class->name }}</span>
                            <strong>{{ $class->students_count }} студентов</strong>
                        </div>
                        <div classline="progress-bar-container">
                            <div class="progress-bar"
                                style="width: {{ $class->students_count }}%; background-color: #6e76c1;">
                                {{ $class->students_count }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js "></script>
    <script>
        const ctx = document.getElementById('classProgressChart').getContext('2d');

        const labels = [
            @foreach ($classes as $class)
                "{{ $class->name }}",
            @endforeach
        ];

        const dataValues = [
            @foreach ($classes as $class)
                {{ $class->students_count }},
            @endforeach
        ];

        if (ctx && labels.length > 0 && dataValues.some(v => v > 0)) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Количество студентов',
                        data: dataValues,
                        backgroundColor: '#007bff'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        } else {
            const chartContainer = document.querySelector('#classProgressChart').closest('.section');
            if (chartContainer) {
                chartContainer.innerHTML = '<p>Нет данных для отображения графика.</p>';
            }
        }
    </script>
@endsection

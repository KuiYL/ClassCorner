<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Статистика</title>
    <link rel="stylesheet" href="{{ asset('css/style-platform.css') }}">
    <script src="{{ asset('js/script.js') }}" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" href="{{ asset('icon-logo.svg') }}" type="image/svg+xml">

</head>

<body>
    @include('layout.sidebar', ['activePage' => 'statistics'])

    <div class="topbar">
        @include('layout.topbar')

        <main>
            <div class="charts-container">
                <div class="chart-item">
                    <canvas id="lineChart"></canvas>
                </div>
                <div class="chart-item">
                    <canvas id="barChart"></canvas>
                </div>
                <div class="chart-item">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </main>
    </div>

    <a href="{{ route('assignments.create', ['return_url' => url()->current()]) }}" class="floating-btn">
        <button>
            <i class="fas fa-plus"></i>
        </button>
    </a>
    @include('layout.modal-delete')

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const lineCtx = document.getElementById('lineChart').getContext('2d');
            new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн'],
                    datasets: [{
                        label: 'Продажи',
                        data: [120, 190, 300, 500, 200, 300],
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        }
                    }
                }
            });

            // Гистограмма
            const barCtx = document.getElementById('barChart').getContext('2d');
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: ['Продукт 1', 'Продукт 2', 'Продукт 3', 'Продукт 4'],
                    datasets: [{
                        label: 'Количество проданных единиц',
                        data: [50, 75, 100, 125],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        }
                    }
                }
            });

            // Круговая диаграмма
            const pieCtx = document.getElementById('pieChart').getContext('2d');
            new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: ['Категория 1', 'Категория 2', 'Категория 3', 'Категория 4'],
                    datasets: [{
                        label: 'Распределение по категориям',
                        data: [10, 20, 30, 40],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)'
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>

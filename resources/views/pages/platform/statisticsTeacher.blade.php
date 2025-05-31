@extends('pages.platform.layout', ['activePage' => 'statistics', 'title' => 'Статистика', 'quick_action' => 'assignments.create'])
@section('content')
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
@endsection

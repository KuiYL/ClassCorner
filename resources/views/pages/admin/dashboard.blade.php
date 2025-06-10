@extends('pages.admin.layout')

@section('content')
    <div class="admin-dashboard container mx-auto p-6">
        <h2 class="text-4xl font-extrabold mb-10 text-[#6E76C1]">Админ-панель</h2>

        <div class="stats grid grid-cols-1 md:grid-cols-3 gap-8 mb-14">
            <div
                class="stat-card bg-white rounded-xl shadow-lg p-8 text-center border-4 border-[#6E76C1] hover:shadow-xl transition-shadow duration-300">
                <h4 class="text-xl font-semibold mb-3 text-[#4A4F8C]">Пользователи</h4>
                <p class="text-5xl font-extrabold text-[#6E76C1]">{{ $users->count() }}</p>
            </div>
            <div
                class="stat-card bg-white rounded-xl shadow-lg p-8 text-center border-4 border-[#6E76C1] hover:shadow-xl transition-shadow duration-300">
                <h4 class="text-xl font-semibold mb-3 text-[#4A4F8C]">Классы</h4>
                <p class="text-5xl font-extrabold text-[#6E76C1]">{{ $classes->count() }}</p>
            </div>
            <div
                class="stat-card bg-white rounded-xl shadow-lg p-8 text-center border-4 border-[#6E76C1] hover:shadow-xl transition-shadow duration-300">
                <h4 class="text-xl font-semibold mb-3 text-[#4A4F8C]">Задания</h4>
                <p class="text-5xl font-extrabold text-[#6E76C1]">{{ $assignments->count() }}</p>
            </div>
        </div>

        {{-- Обернем "Последние задания" и "Статистика по классам" в flex контейнер --}}
        <div class="flex flex-col md:flex-row md:space-x-12 mb-16 max-w-7xl mx-auto">
            <div class="last-assignments flex-1 mb-10 md:mb-0">
                <h3 class="text-3xl font-semibold mb-6 text-[#6E76C1] border-b-2 border-[#6E76C1] pb-2 max-w-max">Последние
                    задания</h3>
                <ul class="list-disc list-inside space-y-3 bg-white p-8 rounded-xl shadow text-gray-800 max-w-md">
                    @foreach ($assignments as $assignment)
                        <li class="hover:bg-[#f0f2ff] p-2 rounded-md transition-colors duration-200 cursor-pointer">
                            <span class="font-semibold">{{ $assignment->title }}</span>
                            <small
                                class="text-[#888C9B] ml-2">({{ optional($assignment->class)->name ?? 'Без класса' }})</small>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="class-stats flex-1">
                <div class="section max-w-4xl mx-auto">
                    <h3
                        class="text-3xl font-semibold mb-6 text-[#6E76C1] border-b-2 border-[#6E76C1] pb-2 max-w-max mx-auto">
                        График количества учеников по классам</h3>
                    <canvas id="classProgressChart" class="w-full bg-white rounded-xl shadow-lg p-8"></canvas>
                </div>
            </div>
        </div>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('classProgressChart');
            if (!canvas) {
                console.warn('Canvas element for chart not found!');
                return;
            }
            const ctx = canvas.getContext('2d');

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

            if (labels.length === 0 || !dataValues.some(v => v > 0)) {
                const chartContainer = canvas.closest('.section');
                if (chartContainer) {
                    chartContainer.innerHTML =
                        '<p class="text-center text-gray-500 italic">Нет данных для отображения графика.</p>';
                }
                return;
            }

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Количество учеников',
                        data: dataValues,
                        backgroundColor: '#6E76C1',
                        borderRadius: 8,
                        maxBarThickness: 45
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                color: '#4A4F8C',
                                font: {
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                color: '#E0E3F7'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#4A4F8C',
                                font: {
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                color: '#E0E3F7'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#6E76C1',
                            titleColor: '#fff',
                            bodyColor: '#fff'
                        }
                    }
                }
            });
        });
    </script>
@endsection

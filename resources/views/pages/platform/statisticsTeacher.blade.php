@extends('pages.platform.layout', ['activePage' => 'statistics', 'title' => 'Статистика', 'quick_action' => 'null'])

@section('content')
    <div class="container-fluid py-6 px-md-4">
        <div class="bg-white rounded-lg shadow-md border-l-4 border-[#6E76C1] mb-6 overflow-hidden">
            <div class="p-6 flex justify-between items-center bg-[#EEF2FF]">
                <div>
                    <h3 class="text-xl font-semibold text-[#555EB1]"> <i class="fas fa-chart-bar mr-3 text-[#6E76C1]"></i>Ваша
                        статистика</h3>
                    <p class="mt-2 text-sm text-[#6E76C1] font-medium">
                        Посмотрите данные по классам, заданиям и ученикам
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-[#6E76C1]">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-[#6E76C1]/10 text-[#6E76C1]">
                        <i class="fas fa-school text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Классов</p>
                        <h3 class="text-xl font-bold text-gray-800">{{ count($classes) }}</h3>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-[#6E76C1]">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-[#6E76C1]/10 text-[#6E76C1]">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Обучающихся</p>
                        <h3 class="text-xl font-bold text-gray-800">{{ $totalStudents }}</h3>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-[#6E76C1]">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-[#6E76C1]/10 text-[#6E76C1]">
                        <i class="fas fa-tasks text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Всего заданий</p>
                        <h3 class="text-xl font-bold text-gray-800">{{ count($assignments) }}</h3>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-red-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-inbox text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Новых заданий</p>
                        <h3 class="text-xl font-bold text-gray-800">{{ $newAssignmentsCount }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-10 flex items-center space-x-4">
            <form method="GET" action="{{ route('user.statistics') }}">
                <label for="classSelect" class="font-semibold text-gray-700 text-lg whitespace-nowrap">Выберите
                    класс:</label>
                <select name="class_id" id="classSelect" onchange="this.form.submit()"
                    class="border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#6E76C1]">
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" @if ($class->id == $selectedClassId) selected @endif>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <section class="bg-white rounded-lg shadow-md p-6 mb-12 border border-gray-200 max-w-4xl mx-auto">
            <h3 class="text-xl font-semibold text-gray-900 mb-6 border-b border-gray-300 pb-2">Рейтинг учеников в классе
            </h3>
            @if (count($studentRatings) > 0)
                <table class="w-full text-left border-collapse table-auto">
                    <thead>
                        <tr class="bg-[#6E76C1]/10 text-[#6E76C1] uppercase text-sm font-semibold">
                            <th class="py-3 px-5 rounded-tl-lg">Ученик</th>
                            <th class="py-3 px-5 rounded-tr-lg">Средний балл</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($studentRatings as $studentName => $avgGrade)
                            <tr class="border-b border-gray-200 hover:bg-[#6E76C1]/10 transition-colors">
                                <td class="py-3 px-5 font-medium text-gray-800">{{ $studentName }}</td>
                                <td class="py-3 px-5 font-semibold text-gray-900">{{ $avgGrade }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-500 text-center py-10">Нет данных по выбранному классу.</p>
            @endif
        </section>

        <section class="grid grid-cols-1 lg:grid-cols-2 gap-10 max-w-7xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200 flex flex-col">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">Задания по месяцам</h3>
                <div class="flex-grow">
                    <canvas id="lineChart" class="w-full" style="min-height: 320px;"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200 flex flex-col">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">Ученики по классам</h3>
                <div class="flex-grow">
                    <canvas id="barChart" class="w-full" style="min-height: 320px;"></canvas>
                </div>
            </div>
        </section>

        <section
            class="bg-white rounded-lg shadow-md p-6 border border-gray-200 max-w-4xl mx-auto mt-12 flex flex-col lg:flex-row items-center lg:items-start gap-8">
            <div class="flex-grow" style="min-width: 280px; min-height: 280px;">
                <canvas id="pieChart" width="280" height="280"
                    style="display: block; width: 280px; height: 280px;"></canvas>
            </div>

            <div class="w-full lg:w-48">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 text-center lg:text-left">Типы заданий</h3>
                <ul id="pieLegend" class="space-y-4">
                </ul>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const studentCounts = @json($studentCounts);
            const assignmentsByMonth = @json($assignmentsByMonth);
            const assignmentTypes = @json($assignmentTypes);
            const months = ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'];

            const typeTranslations = {
                'file_upload': 'Загрузка файла',
                'multiple_choice': 'Множественный выбор',
                'single_choice': 'Один выбор',
                'text': 'Текстовый ответ'
            };

            const translatedAssignmentTypes = Object.fromEntries(
                Object.entries(assignmentTypes).map(([key, value]) => [typeTranslations[key] || key, value])
            );

            const colors = ['#6E76C1', '#8B9AC0', '#A8B2D1', '#C0C6E0'];

            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: 'rgba(0,0,0,0.7)',
                        titleFont: {
                            size: 16,
                            weight: '700'
                        },
                        bodyFont: {
                            size: 14
                        },
                        padding: 10,
                        cornerRadius: 4,
                        displayColors: false,
                    }
                },
                scales: {},
            };

            new Chart(document.getElementById('lineChart'), {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Задания',
                        data: months.map((_, i) => assignmentsByMonth[String(i + 1).padStart(2,
                            '0')] || 0),
                        borderColor: '#6E76C1',
                        backgroundColor: 'rgba(110, 118, 193, 0.3)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: '#4F51C0',
                        borderWidth: 3,
                        hoverBorderWidth: 4
                    }]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Месяцы',
                                font: {
                                    size: 16,
                                    weight: '600'
                                },
                                color: '#374151'
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Количество заданий',
                                font: {
                                    size: 16,
                                    weight: '600'
                                },
                                color: '#374151'
                            },
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                color: '#E5E7EB'
                            }
                        }
                    }
                }
            });

            new Chart(document.getElementById('barChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(studentCounts),
                    datasets: [{
                        label: 'Ученики',
                        data: Object.values(studentCounts),
                        backgroundColor: '#6E76C1',
                        hoverBackgroundColor: '#4F51C0',
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Классы',
                                font: {
                                    size: 16,
                                    weight: '600'
                                },
                                color: '#374151'
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45,
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Количество учеников',
                                font: {
                                    size: 16,
                                    weight: '600'
                                },
                                color: '#374151'
                            },
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                color: '#E5E7EB'
                            }
                        }
                    },
                    hover: {
                        animationDuration: 300
                    }
                }
            });

            const pieCtx = document.getElementById('pieChart').getContext('2d');

            const pieChart = new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(translatedAssignmentTypes),
                    datasets: [{
                        data: Object.values(translatedAssignmentTypes),
                        backgroundColor: colors,
                        hoverOffset: 30,
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    ...commonOptions,
                    cutout: '70%',
                    plugins: {
                        ...commonOptions.plugins,
                    }
                }
            });

            const legendContainer = document.getElementById('pieLegend');
            const labels = pieChart.data.labels;
            const data = pieChart.data.datasets[0].data;

            labels.forEach((label, i) => {
                const li = document.createElement('li');
                li.className = "flex items-center cursor-pointer select-none";

                const box = document.createElement('span');
                box.className = "w-5 h-5 rounded-full mr-3";
                box.style.backgroundColor = colors[i];
                box.style.flexShrink = '0';

                const text = document.createElement('span');
                text.textContent = `${label} (${data[i]})`;
                text.className = "text-gray-700 font-semibold";

                li.appendChild(box);
                li.appendChild(text);

                li.addEventListener('click', () => {
                    const meta = pieChart.getDatasetMeta(0);
                    const currHidden = meta.data[i].hidden;
                    meta.data[i].hidden = !currHidden;
                    pieChart.update();

                    if (meta.data[i].hidden) {
                        li.classList.add('opacity-50');
                    } else {
                        li.classList.remove('opacity-50');
                    }
                });

                legendContainer.appendChild(li);
            });
        });
    </script>
@endsection

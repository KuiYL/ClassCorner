@extends('pages.platform.layout', ['activePage' => 'statistics', 'title' => 'Статистика', 'quick_action' => 'null'])
@section('content')
    <div class="container-fluid py-6 px-md-4">

        <!-- Баннер -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-chart-bar mr-3 text-[#6E76C1]"></i> Ваша статистика
            </h2>
            <p class="mt-1 text-sm text-gray-500">Посмотрите данные по классам, заданиям и учениками</p>
        </div>

        <!-- Статистика сверху -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Активные классы -->
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-[#6E76C1]">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-[#6E76C1]/10 text-[#6E76C1]">
                        <i class="fas fa-school text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Классов</p>
                        <h3 class="text-xl font-bold text-gray-800">{{ $classes->count() }}</h3>
                    </div>
                </div>
            </div>

            <!-- Обучающихся -->
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

            <!-- Всего заданий -->
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

            <!-- Новых заданий -->
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

        <!-- Фильтр по классу -->
        <div class="mb-8 bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <label for="class-select" class="block text-sm font-medium text-gray-700 mb-2">Выберите класс:</label>
            <select id="class-select"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#6E76C1] focus:border-transparent transition duration-200">
                <option value="">Все классы</option>
                @foreach ($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Графики -->
        <div class="charts-container grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Задания по месяцам -->
            <div class="chart-item bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <h4 class="font-semibold text-gray-800 mb-4">Задания по месяцам</h4>
                <canvas id="lineChart"></canvas>
            </div>

            <!-- Ученики по классам -->
            <div class="chart-item bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <h4 class="font-semibold text-gray-800 mb-4">Ученики по классам</h4>
                <canvas id="barChart"></canvas>
            </div>

            <!-- Типы заданий -->
            <div class="chart-item bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <h4 class="font-semibold text-gray-800 mb-4">Типы заданий</h4>
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // --- Получаем данные из Blade ---
            const originalStudentCounts = @json($studentCounts); // {"Математика": 12, "Физика": 15}
            const originalAssignmentsByMonth = @json($assignmentsByMonth); // {"01": 5, "02": 8}
            const originalAssignmentTypes = @json($assignmentTypes); // {"text": 5, "multiple_choice": 10}

            // --- Список месяцев для линейного графика ---
            const monthLabels = ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя',
                'Дек'
            ];

            // --- Инициализация графиков ---
            let barChart, lineChart, pieChart;

            // --- Линейный график: задания по месяцам ---
            lineChart = new Chart(document.getElementById('lineChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: 'Задания',
                        data: monthLabels.map(m => originalAssignmentsByMonth[m] || 0),
                        borderColor: '#6E76C1',
                        backgroundColor: '#6E76C1/20',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#6E76C1'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: context => `${context.parsed.y} заданий`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                callback: value => value + ' шт.'
                            }
                        }
                    }
                }
            });

            // --- Гистограмма: Ученики по классам ---
            barChart = new Chart(document.getElementById('barChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: Object.keys(originalStudentCounts),
                    datasets: [{
                        label: 'Учеников',
                        data: Object.values(originalStudentCounts),
                        backgroundColor: '#6E76C1'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: context => context.parsed.y + ' обучающихся'
                            }
                        }
                    },
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

            // --- Круговая диаграмма: типы заданий ---
            pieChart = new Chart(document.getElementById('pieChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: Object.keys(originalAssignmentTypes).map(type => {
                        return type === 'single_choice' ? 'Один выбор' :
                            type === 'multiple_choice' ? 'Множественный выбор' :
                            type === 'file_upload' ? 'Загрузка файла' : 'Текстовый ответ';
                    }),
                    datasets: [{
                        data: Object.values(originalAssignmentTypes),
                        backgroundColor: ['#6E76C1', '#8B9AC0', '#A8B2D1', '#C5CAE9']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right'
                        },
                        tooltip: {
                            callbacks: {
                                label: context => context.label + ': ' + context.parsed + ' заданий'
                            }
                        }
                    }
                }
            });

            // --- Фильтрация по классу (через JS) ---
            const classSelect = document.getElementById("class-select");

            classSelect?.addEventListener("change", function() {
                const selectedClass = this.value;

                if (!selectedClass) {
                    // Сброс фильтра
                    lineChart.data.datasets[0].data = monthLabels.map(m => originalAssignmentsByMonth[m] ||
                        0);
                    barChart.data.labels = Object.keys(originalStudentCounts);
                    barChart.data.datasets[0].data = Object.values(originalStudentCounts);
                    pieChart.data.labels = Object.keys(originalAssignmentTypes).map(type =>
                        type === 'single_choice' ? 'Один выбор' :
                        type === 'multiple_choice' ? 'Множественный выбор' :
                        type === 'file_upload' ? 'Загрузка файла' : 'Текстовый ответ'
                    );
                    pieChart.data.datasets[0].data = Object.values(originalAssignmentTypes);

                    lineChart.update();
                    barChart.update();
                    pieChart.update();

                    return;
                }

                // Фильтр только по выбранному классу
                const filteredClasses = @json($classes->keyBy('id')->toArray());
                const selectedClassData = filteredClasses[selectedClass];

                if (!selectedClassData) return;

                // Подсчёт заданий по месяцам для одного класса
                const assignmentsByMonthForClass = {};
                const allAssignments = @json($assignments->groupBy(fn($a) => \Carbon\Carbon::parse($a->due_date)->format('m')));

                if (allAssignments[selectedClass]) {
                    selectedClassData.assignments.forEach(ass => {
                        const month = \Carbon\ Carbon::parse(ass.due_date).format('m');
                        assignmentsByMonthForClass[month] = (assignmentsByMonthForClass[month] ||
                            0) + 1;
                    });
                }

                // Подсчёт Учеников в классе
                const studentCountForClass = selectedClassData.students.length - 1; // минус учитель

                // Подсчёт типов заданий для класса
                const assignmentTypesForClass = {};
                selectedClassData.assignments.forEach(ass => {
                    const options = JSON.parse(ass.options);
                    options.forEach(opt => {
                        const type = opt.type;
                        assignmentTypesForClass[type] = (assignmentTypesForClass[type] ||
                            0) + 1;
                    });
                });

                // --- Обновляем графики ---
                lineChart.data.labels = monthLabels;
                lineChart.data.datasets[0].data = monthLabels.map(m => assignmentsByMonthForClass[m] || 0);
                lineChart.update();

                barChart.data.labels = [selectedClassData.name];
                barChart.data.datasets[0].data = [studentCountForClass];
                barChart.update();

                pieChart.data.labels = Object.keys(assignmentTypesForClass).map(type =>
                    type === 'single_choice' ? 'Один выбор' :
                    type === 'multiple_choice' ? 'Множественный выбор' :
                    type === 'file_upload' ? 'Загрузка файла' : 'Текстовый ответ'
                );
                pieChart.data.datasets[0].data = Object.values(assignmentTypesForClass);
                pieChart.update();
            });

            // --- Сброс фильтров ---
            document.getElementById("clear-filter")?.addEventListener("click", function() {
                classSelect.value = "";
                lineChart.data.labels = monthLabels;
                lineChart.data.datasets[0].data = monthLabels.map(m => originalAssignmentsByMonth[m] || 0);
                lineChart.update();

                barChart.data.labels = Object.keys(originalStudentCounts);
                barChart.data.datasets[0].data = Object.values(originalStudentCounts);
                barChart.update();

                pieChart.data.labels = Object.keys(originalAssignmentTypes).map(type =>
                    type === 'single_choice' ? 'Один выбор' :
                    type === 'multiple_choice' ? 'Множественный выбор' :
                    type === 'file_upload' ? 'Загрузка файла' : 'Текстовый ответ'
                );
                pieChart.data.datasets[0].data = Object.values(originalAssignmentTypes);
                pieChart.update();
            });
        });
    </script>
@endsection

@extends('pages.platform.layout', ['activePage' => 'calendar', 'title' => 'Календарь', 'quick_action' => null])

@section('content')
    <div class="container-fluid py-6 px-md-4">
        <div class="bg-white rounded-lg shadow-md border-l-4 border-[#6E76C1] mb-6 overflow-hidden">
            <div class="p-6 flex justify-between items-center bg-[#EEF2FF]">
                <div>
                    <h3 class="text-xl font-semibold text-[#555EB1]">
                        <i class="fas fa-calendar-alt mr-3"></i>Календарь заданий
                    </h3>
                    <p class="mt-2 text-sm text-[#6E76C1] font-medium">Просмотр дедлайнов и статусов заданий</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div class="lg:col-span-1 bg-white rounded-lg shadow-sm p-4 border border-gray-200 sticky top-6 h-fit">
                <label for="filter-calendar-class" class="block text-sm font-medium text-gray-700 mb-2">Фильтр по
                    классу</label>
                <select id="filter-calendar-class"
                    class="form-select w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#6E76C1] focus:border-transparent transition duration-200">
                    <option value="">Все классы</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-3 bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <button id="prev-month"
                        class="btn btn-outline-secondary hover:bg-[#6E76C1]/10 hover:text-[#6E76C1] rounded-md">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <h3 id="current-month" class="text-xl font-semibold text-gray-800"></h3>
                    <button id="next-month"
                        class="btn btn-outline-secondary hover:bg-[#6E76C1]/10 hover:text-[#6E76C1] rounded-md">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <div class="grid grid-cols-7 gap-1 mb-2 text-center text-xs font-medium text-gray-500">
                    <div>Пн</div>
                    <div>Вт</div>
                    <div>Ср</div>
                    <div>Чт</div>
                    <div>Пт</div>
                    <div>Сб</div>
                    <div>Вс</div>
                </div>

                <div id="calendar-days" class="grid grid-cols-7 gap-1 mb-6 text-center text-sm calendar-day-container">
                </div>

                <div id="day-events" class="space-y-3">
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const calendarEvents = @json($assignmentData);
            const currentMonthEl = document.getElementById("current-month");
            const prevMonthBtn = document.getElementById("prev-month");
            const nextMonthBtn = document.getElementById("next-month");
            const filterClass = document.getElementById("filter-calendar-class");
            const calendarDaysContainer = document.getElementById("calendar-days");
            const dayEventsContainer = document.getElementById("day-events");

            let currentDate = new Date();
            let filteredAssignments = [...calendarEvents];

            function getAssignmentsByDate(date) {
                return filteredAssignments.filter(ass => {
                    const assignmentDate = new Date(ass.due_date);
                    return (
                        assignmentDate.getDate() === date.getDate() &&
                        assignmentDate.getMonth() === date.getMonth() &&
                        assignmentDate.getFullYear() === date.getFullYear()
                    );
                });
            }

            function renderCalendar() {
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth();

                currentMonthEl.textContent = `${['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
                    'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'][month]} ${year}`;

                const firstDay = new Date(year, month, 1).getDay() || 7;
                const lastDay = new Date(year, month + 1, 0).getDate();
                calendarDaysContainer.innerHTML = "";

                for (let i = 1; i < firstDay; i++) {
                    calendarDaysContainer.innerHTML += '<div class="opacity-0">.</div>';
                }

                for (let day = 1; day <= lastDay; day++) {
                    const date = new Date(year, month, day);
                    const assignments = getAssignmentsByDate(date);

                    const hasEvents = assignments.length > 0;

                    const dayDiv = document.createElement("div");
                    dayDiv.className = `
                        p-2 rounded-lg text-center cursor-pointer transition-all duration-300 ease-in-out
                        ${hasEvents ? 'bg-[#6E76C1]/10 hover:bg-[#6E76C1]/20' : 'bg-gray-100 hover:bg-gray-200'}
                    `;
                    dayDiv.dataset.date = date.toISOString().split("T")[0];
                    dayDiv.innerHTML = `
                        <div class="font-medium">${day}</div>
                        <div class="text-xs mt-1">
                            ${hasEvents ? `<span class="inline-block w-2 h-2 bg-[#6E76C1] rounded-full"></span>` : ''}
                        </div>
                    `;
                    dayDiv.addEventListener("click", () => showEventsForDate(date));
                    calendarDaysContainer.appendChild(dayDiv);
                }
            }

            function showEventsForDate(date) {
                const selectedDate = new Date(date);
                const assignmentsForDay = getAssignmentsByDate(selectedDate);

                dayEventsContainer.innerHTML = "";

                if (assignmentsForDay.length === 0) {
                    dayEventsContainer.innerHTML = `
                        <div class="text-center py-6 bg-gray-50 border border-dashed border-gray-300 rounded-lg">
                            <i class="fas fa-calendar-check text-gray-300 text-3xl mb-2"></i>
                            <p class="text-gray-500">Нет заданий на этот день</p>
                        </div>`;
                    return;
                }

                assignmentsForDay.forEach(ass => {
                    let statusText = '';
                    let statusClass = '';
                    let actionHTML = '';

                    switch (ass.status) {
                        case 'not_submitted':
                            statusText = 'Не сдано';
                            statusClass = 'text-red-500 font-semibold';
                            actionHTML = `
            <a href="${ass.url_show}"
               class="btn outline-none text-red-600 border-red-600 hover:bg-red-600 hover:text-gray-100 rounded-md px-3 py-1 inline-flex items-center mt-2">
                <i class="fas fa-arrow-right mr-1"></i> Перейти
            </a>`;
                            break;
                        case 'submitted':
                            statusText = 'На проверке';
                            statusClass = 'text-yellow-500 font-semibold';
                            actionHTML = `
            <span class="inline-flex items-center text-sm text-yellow-600 cursor-default px-3 py-1 rounded-md border border-yellow-600 bg-yellow-50 mt-2">
                На проверке <i class="fas fa-clock ml-1"></i>
            </span>`;
                            break;
                        case 'graded':
                            statusText = 'Проверено';
                            statusClass = 'text-green-600 font-semibold';
                            if (ass.url_result) {
                                actionHTML = `
                <a href="${ass.url_result}"
                   class="btn outline-none text-green-600 border-green-600 hover:bg-green-600 hover:text-gray-100 rounded-md px-3 py-1 inline-flex items-center mt-2">
                    <i class="fas fa-eye mr-1"></i> Результаты
                </a>`;
                            }
                            break;
                        default:
                            statusText = 'Неизвестно';
                            statusClass = 'text-gray-500 font-semibold';
                    }

                    const event = document.createElement("div");
                    event.className =
                        "p-4 bg-gray-50 border border-gray-200 rounded-lg transition";
                    event.innerHTML = `
        <h4 class="font-semibold text-[#6E76C1]">${ass.title}</h4>
        <p class="text-sm text-gray-600 mt-1">${ass.description || 'Нет описания'}</p>
        <p class="text-xs text-gray-500 mt-2">Класс: ${ass.class_name}</p>
        <p class="text-xs text-gray-500 mt-1">Дата сдачи: ${new Date(ass.due_date).toLocaleString()}</p>
        <p class="text-xs mt-2 ${statusClass}">Статус: ${statusText}</p>
        ${actionHTML}
    `;
                    dayEventsContainer.appendChild(event);
                });


            }

            prevMonthBtn.addEventListener("click", () => {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar();
            });

            nextMonthBtn.addEventListener("click", () => {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar();
            });

            filterClass.addEventListener("change", (e) => {
                const selectedClassId = e.target.value;
                filteredAssignments = selectedClassId ?
                    calendarEvents.filter(ass => ass.class_id == selectedClassId) : [...calendarEvents];
                renderCalendar();
            });

            renderCalendar();
        });
    </script>
@endsection

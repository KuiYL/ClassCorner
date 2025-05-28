<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мой календарь</title>
    <link rel="stylesheet" href="{{ asset('css/style-platform.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css ">
    <script src="{{ asset('js/script.js') }}" defer></script>
    <link rel="icon" href="{{ asset('icon-logo.svg') }}" type="image/svg+xml">

</head>

<body>
    @include('layout.sidebar', ['activePage' => 'calendar'])

    <div class="topbar">
        @include('layout.topbar')

        <main>
            <div class="main-platform">
                <div id="calendar">
                    <div class="filters-calendar">
                        <select id="filter-calendar-class">
                            <option value="">Все классы</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="calendar-header">
                        <button id="prev-month"><i class="fas fa-chevron-left"></i></button>
                        <h2 id="current-month"></h2>
                        <button id="next-month"><i class="fas fa-chevron-right"></i></button>
                    </div>
                    <div class="calendar-grid"></div>
                    <div id="days-container"></div>
                </div>

            </div>
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const daysContainer = document.getElementById("days-container");
            const currentMonthElement = document.getElementById("current-month");
            const prevMonthButton = document.getElementById("prev-month");
            const nextMonthButton = document.getElementById("next-month");
            const assignments = @json($groupedAssignments);

            let currentDate = new Date();
            const dayNames = ["Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс"];
            const calendarGrid = document.querySelector(".calendar-grid");

            dayNames.forEach(day => {
                const div = document.createElement("div");
                div.className = "day-name";
                div.textContent = day;
                calendarGrid.appendChild(div);
            });

            function renderCalendar(date) {
                daysContainer.innerHTML = "";
                const year = date.getFullYear();
                const month = date.getMonth();

                const firstDayOfMonth = new Date(year, month, 1);
                const lastDayOfMonth = new Date(year, month + 1, 0);
                const startDayIndex = (firstDayOfMonth.getDay() + 6) % 7;

                const monthNames = [
                    "Январь", "Февраль", "Март", "Апрель",
                    "Май", "Июнь", "Июль", "Август",
                    "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"
                ];
                currentMonthElement.textContent = `${monthNames[month]} ${year}`;

                for (let i = 0; i < startDayIndex; i++) {
                    daysContainer.appendChild(document.createElement("div"));
                }

                const today = new Date();
                const selectedClassId = document.getElementById("filter-calendar-class").value;

                const totalDays = lastDayOfMonth.getDate();

                for (let day = 1; day <= totalDays; day++) {
                    const dayDiv = document.createElement("div");
                    dayDiv.className = "day";
                    dayDiv.textContent = day;

                    const isPastDay = (
                        year < today.getFullYear() ||
                        (year === today.getFullYear() && month < today.getMonth()) ||
                        (year === today.getFullYear() && month === today.getMonth() && day < today.getDate())
                    );

                    if (isPastDay) {
                        dayDiv.classList.add("past-day");
                        dayDiv.style.opacity = "0.6";
                        dayDiv.style.cursor = "not-allowed";
                    } else {
                        dayDiv.classList.add("future-day");
                    }

                    const dateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    const dayAssignments = assignments[dateKey] || [];

                    let filteredAssignments = dayAssignments;

                    if (selectedClassId) {
                        filteredAssignments = dayAssignments.filter(a =>
                            a.class_id?.toString() === selectedClassId
                        );
                    }

                    if (filteredAssignments.length > 0) {
                        dayDiv.classList.add("has-assignments");
                        dayDiv.dataset.assignments = JSON.stringify(filteredAssignments);
                        dayDiv.addEventListener("click", () => openDayActions(dayDiv, dateKey));
                    } else {
                        dayDiv.classList.remove("has-assignments");
                        delete dayDiv.dataset.assignments;
                    }

                    if (
                        year === today.getFullYear() &&
                        month === today.getMonth() &&
                        day === today.getDate()
                    ) {
                        dayDiv.classList.add("today");
                    }

                    daysContainer.appendChild(dayDiv);
                }
            }

            function openDayActions(dayElement, dateKey) {
                const existing = document.querySelector('.day-actions');
                if (existing) existing.remove();

                const actions = document.createElement("div");
                actions.className = "day-actions";

                const rect = dayElement.getBoundingClientRect();
                actions.style.position = "absolute";
                actions.style.top = `${rect.bottom + window.scrollY}px`;
                actions.style.left = `${rect.left + window.scrollX}px`;

                const dayAssignments = JSON.parse(dayElement.dataset.assignments || "[]");

                const title = document.createElement("h3");
                title.textContent = `Задания (${dayAssignments.length})`;
                title.style.marginBottom = "15px";
                title.style.fontSize = "1.2rem";
                title.style.color = "#333";
                actions.appendChild(title);

                dayAssignments.forEach(assignment => {
                    const item = document.createElement("div");
                    item.className = "assignment-item";

                    const link = document.createElement("a");
                    link.href = `/assignment/${assignment.id}`;
                    link.textContent = assignment.title;
                    link.style.display = "block";
                    link.style.fontWeight = "600";
                    link.style.marginBottom = "5px";
                    link.style.textDecoration = "none";
                    link.style.color = "#333";

                    const classLink = document.createElement("a");
                    classLink.href = `/class/${assignment.class_id}`;
                    classLink.textContent =
                        `Класс: ${assignment.class_name || 'ID ' + assignment.class_id}`;
                    classLink.style.fontSize = "0.9rem";
                    classLink.style.color = "#6e76c1";
                    classLink.style.textDecoration = "none";

                    const dueDate = document.createElement("span");
                    dueDate.textContent = `Статус: ${getStatusText(assignment.status)}`;
                    dueDate.style.display = "block";
                    dueDate.style.fontSize = "0.85rem";
                    dueDate.style.color = getStatusColor(assignment.status);
                    dueDate.style.marginTop = "4px";

                    item.appendChild(link);
                    item.appendChild(classLink);
                    item.appendChild(dueDate);
                    actions.appendChild(item);
                });

                document.body.appendChild(actions);

                document.addEventListener("click", function onClickOutside(e) {
                    if (!actions.contains(e.target) && !dayElement.contains(e.target)) {
                        actions.remove();
                        document.removeEventListener("click", onClickOutside);
                    }
                });
            }

            function getStatusText(status) {
                switch (status) {
                    case 'not_submitted':
                        return 'Не выполнено';
                    case 'submitted':
                        return 'На проверке';
                    case 'graded':
                        return 'Выполнено';
                    default:
                        return 'Неизвестный статус';
                }
            }

            function getStatusColor(status) {
                switch (status) {
                    case 'not_submitted':
                        return '#e74c3c'; // красный
                    case 'submitted':
                        return '#f39c12'; // оранжевый
                    case 'graded':
                        return '#27ae60'; // зелёный
                    default:
                        return '#999';
                }
            }

            document.querySelectorAll('#filter-calendar-class').forEach(input => {
                input.addEventListener("change", () => {
                    currentDate = new Date();
                    renderCalendar(currentDate);
                });
            });

            prevMonthButton.addEventListener("click", () => {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar(currentDate);
            });

            nextMonthButton.addEventListener("click", () => {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar(currentDate);
            });

            renderCalendar(currentDate);
        });
    </script>
</body>

</html>

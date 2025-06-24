<div class="topbar d-flex align-items-center justify-content-between px-4 py-3 border-bottom sticky top-0 z-10 w-full">
    <div class="search w-full max-w-md me-auto position-relative">
        <div class="relative">
            <i
                class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 pointer-events-none"></i>

            <input type="text" id="searchInput"
                class="form-control ps-10 py-2 rounded-pill bg-white focus:border-indigo-500 transition-all duration-200"
                placeholder="Поиск по классам и заданиям..." oninput="handleSearch(this.value)" autocomplete="off">

            <div id="searchResults"
                class="position-absolute mt-1 w-100 bg-white border rounded shadow-sm hidden z-20 overflow-hidden"
                style="max-height: 240px; overflow-y: auto; z-index: 1000;">
                <ul id="searchResultsList" class="list-group list-group-flush m-0">
                </ul>
            </div>
        </div>
    </div>

    <div class="account ms-auto d-flex align-items-center gap-3">
        <div class="position-relative">
            <button class="btn btn-link p-0 position-relative text-muted hover:text-indigo-600 transition-colors"
                onclick="toggleNotifications()">
                <i class="fas fa-bell fs-5"></i>
                @php $unreadCount = auth()->user()->notifications->where('read', false)->count(); @endphp
                @if ($unreadCount > 0)
                    <span
                        class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-[#6E76C1] text-white fs-6"
                        style="font-size: 0.6rem; padding: 0.35rem 0.4rem;">
                        {{ $unreadCount }}
                    </span>
                @endif
            </button>

            <div id="notificationDropdown"
                class="position-absolute end-0 mt-1 bg-white border rounded shadow-sm hidden z-20"
                style="width: 340px; max-height: 360px; overflow-y: auto; border-color: #E5E7EB;">

                <div class="p-3 border-bottom" style="background-color: #F9FAFB;">
                    <h6 class="mb-0 font-semibold text-gray-800">Уведомления</h6>
                </div>

                <ul class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                    @php
                        \Carbon\Carbon::setLocale('ru');
                        $notifications = auth()->user()->notifications->sortByDesc('created_at');
                    @endphp

                    @forelse ($notifications as $notif)
                        <li class="list-group-item py-3 px-3 hover:bg-gray-50 transition-colors duration-200">
                            <a href="{{ $notif->data['url'] ?? '#' }}" class="d-block text-decoration-none">
                                <div class="flex items-start gap-3">
                                    <div class="mt-1">
                                        <div class="flex items-start gap-3 mt-2 text-xs text-gray-400">
                                            @switch($notif->type)
                                                @case('assignment_submitted')
                                                    <i class="fas fa-file-upload fa-lg text-blue-500"></i>
                                                @break

                                                @case('assignment_graded')
                                                    <i class="fas fa-star-half-alt fa-lg text-yellow-500"></i>
                                                @break

                                                @case('class_joined')
                                                    <i class="fas fa-user-plus fa-lg text-green-500"></i>
                                                @break

                                                @case('class_invitation')
                                                    <i class="fas fa-envelope fa-lg text-indigo-500"></i>
                                                @break

                                                @case('assignment_created')
                                                    <i class="fas fa-chalkboard-teacher fa-lg text-purple-500"></i>
                                                @break

                                                @case('assignment_reminder')
                                                    <i class="fas fa-clock fa-lg text-red-500"></i>
                                                @break

                                                @case('feedback_received')
                                                    <i class="fas fa-comment-dots fa-lg text-teal-500"></i>
                                                @break

                                                @default
                                                    <i class="fas fa-bell fa-lg text-gray-500"></i>
                                            @endswitch
                                        </div>
                                    </div>

                                    <div class="w-full">
                                        <p class="mb-1 font-medium text-gray-800">{{ $notif->title }}</p>
                                        <small class="text-gray-600">{{ $notif->message }}</small>

                                        <div class="mt-2 flex justify-between items-center text-xs text-gray-400">
                                            <span>
                                                {{ $notif->created_at ? $notif->created_at->diffForHumans() : 'Дата не указана' }}
                                            </span>

                                            <div class="flex gap-2 flex-nowrap"
                                                style="min-width: 120px; justify-content: flex-end;">

                                                @if (!$notif->read)
                                                    <form method="POST"
                                                        action="{{ route('notifications.read', $notif->id) }}"
                                                        style="flex-shrink: 0;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-success"
                                                            style="padding: 0.25rem 0.5rem; font-size: 0.75rem; white-space: nowrap;"
                                                            title="Отметить как прочитанное">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <button type="button"
                                                    class="delete-button btn btn-sm btn-outline-danger"
                                                    style="padding: 0.25rem 0.5rem; font-size: 0.75rem; white-space: nowrap;"
                                                    data-id="{{ $notif->id }}" data-name="уведомление"
                                                    data-type="notif">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        @empty
                            <li class="list-group-item text-center py-4">
                                <i class="fas fa-bell-slash text-gray-400 mb-2"></i>
                                <p class="text-sm text-muted">Нет уведомлений</p>
                            </li>
                        @endforelse
                    </ul>

                </div>
            </div>


            <a href="{{ route('user.profile') }}" class="settings text-muted hover:text-indigo-600 transition-colors"
                title="Настройки профиля">
                <i class="fas fa-cog fs-5"></i>
            </a>

            <div class="d-none d-md-block text-end me-3">
                <p class="mb-0 fw-bold">{{ $user->name }} {{ $user->surname }}</p>
                @php
                    $roles = ['admin' => 'Администратор', 'teacher' => 'Преподаватель', 'student' => 'Ученик'];
                @endphp
                <small class="text-base">{{ $roles[$user->role] ?? 'Неизвестная роль' }}</small>
            </div>

            <img src="{{ isset($user->avatar)
                ? (Str::startsWith($user->avatar, 'avatars/')
                    ? Storage::url($user->avatar)
                    : (Str::startsWith($user->avatar, 'images/')
                        ? asset(preg_replace('(images\/)', 'images/avatar', $user->avatar) . '.svg')
                        : asset('images/default-avatar.svg')))
                : asset('images/default-avatar.svg') }}"
                alt="Профиль" class="rounded-circle object-cover"
                style="width: 68px; height: 68px; border-radius: 50%; border: 4px solid #6e76c1;">
        </div>
    </div>

    <script>
        const allClasses = [
            @foreach ($classes as $class)
                {
                    name: "{{ $class->name }}",
                    url: "{{ route('class.show', $class->id) }}",
                    type: 'class'
                },
            @endforeach
        ];

        @php
            $allAssignments = auth()->user()->availableAssignments;
        @endphp

        const allAssignments = [
            @if ($allAssignments->isNotEmpty())
                @foreach ($allAssignments as $task)
                    {
                        name: "{{ $task->title }}",
                        url: "{{ route('assignments.show', $task->id) }}",
                        type: 'assignment'
                    },
                @endforeach
            @endif
        ];

        const allItems = [...allClasses, ...allAssignments];

        function handleSearch(value) {
            const resultsBox = document.getElementById("searchResults");
            const resultsList = document.getElementById("searchResultsList");
            resultsList.innerHTML = '';
            value = value.toLowerCase().trim();

            if (!value) {
                resultsBox.classList.add("hidden");
                return;
            }

            const filtered = allItems.filter(item => item.name.toLowerCase().includes(value));

            if (filtered.length === 0) {
                resultsList.innerHTML = '<li class="list-group-item text-center"><em>Ничего не найдено</em></li>';
            } else {
                let html = '';

                const classes = filtered.filter(item => item.type === 'class');
                const assignments = filtered.filter(item => item.type === 'assignment');

                if (classes.length > 0) {
                    html += `<div class="mb-2 mt-3 px-3 text-xs font-semibold text-gray-500">КЛАССЫ</div>`;
                    html += classes.map(cls => `
                    <a href="${cls.url}" class="list-group-item list-group-item-action">${cls.name}</a>
                `).join('');
                }

                if (assignments.length > 0) {
                    html += `<div class="mb-2 mt-3 px-3 text-xs font-semibold text-gray-500">ЗАДАНИЯ</div>`;
                    html += assignments.map(ass => `
                    <a href="${ass.url}" class="list-group-item list-group-item-action">${ass.name}</a>
                `).join('');
                }

                resultsList.innerHTML = html;
            }

            resultsBox.classList.remove("hidden");
        }

        document.addEventListener("click", function(e) {
            const searchBox = document.querySelector(".search");
            const resultsBox = document.getElementById("searchResults");

            if (!searchBox.contains(e.target)) {
                resultsBox.classList.add("hidden");
            }
        });

        function toggleNotifications() {
            const dropdown = document.getElementById("notificationDropdown");
            dropdown.classList.toggle("hidden");
        }

        document.addEventListener("click", function(e) {
            const notificationBtn = document.querySelector(".account .fa-bell").parentElement;
            const notificationBox = document.getElementById("notificationDropdown");

            if (!notificationBtn.contains(e.target) && !notificationBox.contains(e.target)) {
                notificationBox.classList.add("hidden");
            }
        });
    </script>

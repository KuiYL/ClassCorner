    <nav class="flex-grow-1 d-flex flex-column overflow-hidden pb-4" style="height: calc(100vh - 70px);">
        <ul class="list-none mb-4 space-y-1 px-2">
            <li>
                <a href="{{ route('user.dashboard') }}"
                    class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg text-[#616161] transition-all duration-200
                    hover:bg-gray-100 hover:text-gray-900
                    {{ $activePage === 'dashboard' ? 'text-[#6E76C1] relative pl-2 pointer-events-none cursor-default active' : '' }}">

                    @if ($activePage === 'dashboard')
                        <span class="absolute left-0 top-0 bottom-0 w-1 bg-[#6E76C1] rounded-r-md"></span>
                    @endif

                    <i class="fas fa-home text-xl"></i>
                    <span class="text-lg font-medium">Главная</span>
                </a>
            </li>

            @if ($user->role !== 'admin')
                <li>
                    <a href="{{ route('user.classes') }}"
                        class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg text-[#616161] transition-all duration-200 hover:bg-gray-100 hover:text-gray-900 {{ $activePage === 'classes' ? 'text-[#6E76C1] relative pl-2 pointer-events-none cursor-default active' : '' }}">

                        @if ($activePage === 'classes')
                            <span class="absolute left-0 top-0 bottom-0 w-1 bg-[#6E76C1] rounded-r-md"></span>
                        @endif

                        <i class="fas fa-chalkboard-teacher text-xl"></i>
                        <span class="text-lg font-medium">Мои классы</span>

                    </a>
                </li>
                <li>
                    <a href="{{ route('user.assignments') }}"
                        class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg text-[#616161] transition-all duration-200 hover:bg-gray-100 hover:text-gray-900 {{ $activePage === 'tasks' ? 'text-[#6E76C1] relative pl-2 pointer-events-none cursor-default active' : '' }}">
                        @if ($activePage === 'tasks')
                            <span class="absolute left-0 top-0 bottom-0 w-1 bg-[#6E76C1] rounded-r-md"></span>
                        @endif

                        <i class="fas fa-tasks text-xl"></i>
                        <span class="text-lg font-medium">Задания</span>

                    </a>
                </li>
                <li>
                    <a href="{{ route('user.calendar') }}"
                        class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg text-[#616161] transition-all duration-200 hover:bg-gray-100 hover:text-gray-900 {{ $activePage === 'calendar' ? 'text-[#6E76C1] relative pl-2 pointer-events-none cursor-default active' : '' }}">
                        @if ($activePage === 'calendar')
                            <span class="absolute left-0 top-0 bottom-0 w-1 bg-[#6E76C1] rounded-r-md"></span>
                        @endif
                        <i class="fas fa-calendar-alt text-xl"></i>
                        <span class="text-lg font-medium">Календарь</span>

                    </a>
                </li>
                @if ($user->role !== 'student')
                    <li>
                        <a href="{{ route('user.statistics') }}"
                            class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg text-[#616161] transition-all duration-200 hover:bg-gray-100 hover:text-gray-900 {{ $activePage === 'statistics' ? 'text-[#6E76C1] relative pl-2 pointer-events-none cursor-default active' : '' }}">
                            @if ($activePage === 'statistics')
                                <span class="absolute left-0 top-0 bottom-0 w-1 bg-[#6E76C1] rounded-r-md"></span>
                            @endif
                            <i class="fas fa-chart-pie text-xl"></i>
                            <span class="text-lg font-medium">Статистика</span>
                        </a>
                    </li>
                @endif
            @else
                <li>
                    <a href="{{ route('admin.users') }}"
                        class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg text-[#616161] transition-all duration-200 hover:bg-gray-100 hover:text-gray-900 {{ $activePage === 'users' ? 'text-[#6E76C1] relative pl-2 pointer-events-none cursor-default active' : '' }}">
                        @if ($activePage === 'users')
                            <span class="absolute left-0 top-0 bottom-0 w-1 bg-[#6E76C1] rounded-r-md"></span>
                        @endif
                        <i class="fas fa-users text-xl"></i>
                        <span class="text-lg font-medium">Пользователи</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.classes') }}"
                        class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg text-[#616161] transition-all duration-200 hover:bg-gray-100 hover:text-gray-900 {{ $activePage === 'classes' ? 'text-[#6E76C1] relative pl-2 pointer-events-none cursor-default active' : '' }}">

                        @if ($activePage === 'classes')
                            <span class="absolute left-0 top-0 bottom-0 w-1 bg-[#6E76C1] rounded-r-md"></span>
                        @endif

                        <i class="fas fa-chalkboard-teacher text-xl"></i>
                        <span class="text-lg font-medium">Классы</span>

                    </a>
                </li>
                <li>
                    <a href="{{ route('user.assignments') }}"
                        class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg text-[#616161] transition-all duration-200 hover:bg-gray-100 hover:text-gray-900 {{ $activePage === 'tasks' ? 'text-[#6E76C1] relative pl-2 pointer-events-none cursor-default active' : '' }}">
                        @if ($activePage === 'tasks')
                            <span class="absolute left-0 top-0 bottom-0 w-1 bg-[#6E76C1] rounded-r-md"></span>
                        @endif

                        <i class="fas fa-tasks text-xl"></i>
                        <span class="text-lg font-medium">Задания</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.statistics') }}"
                        class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg text-[#616161] transition-all duration-200 hover:bg-gray-100 hover:text-gray-900 {{ $activePage === 'statistics' ? 'text-[#6E76C1] relative pl-2 pointer-events-none cursor-default active' : '' }}">
                        @if ($activePage === 'statistics')
                            <span class="absolute left-0 top-0 bottom-0 w-1 bg-[#6E76C1] rounded-r-md"></span>
                        @endif
                        <i class="fas fa-chart-pie text-xl"></i>
                        <span class="text-lg font-medium">Статистика</span>
                    </a>
                </li>
            @endif

            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();"
                    class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg text-danger transition-all duration-200 hover:bg-red-50 hover:text-red-600">
                    <i class="fas fa-sign-out-alt text-xl"></i>
                    <span class="text-lg font-medium">Выход</span>
                </a>
                <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
        <div class="mt-auto d-flex flex-column h-100" style="min-height: 0;">
            <h6 class="text-uppercase text-muted fw-bold fs-6 mb-3 px-2">Мои классы</h6>

            <div class="px-2 space-y-2 flex-grow-1 overflow-y-auto scrollbar-thin" style="min-height: 0;">
                @if ($classes->isEmpty())
                    <p class="text-sm text-gray-500 italic text-center py-2">Нет классов</p>
                @else
                    @foreach ($classes as $class)
                        <a href="{{ route('class.show', $class->id) }}"
                            class="block p-3 rounded-lg border border-gray-200 bg-white hover:bg-indigo-50 transition-all duration-200">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-indigo-500 flex-shrink-0"></span>
                                <h6 class="font-medium text-gray-800 truncate" data-bs-toggle="tooltip"
                                    title="{{ $class->name }}">
                                    {{ $class->name }}
                                </h6>
                            </div>
                            <div class="mt-2 text-xs text-gray-500 flex justify-between">
                                @auth
                                    @if (auth()->user()->role === 'teacher' || auth()->user()->role === 'admin')
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-users text-[10px]"></i>
                                            {{ $class->students()->count() - 1 }} учеников
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-tasks text-[10px]"></i>
                                            {{ $class->assignments->count() }} заданий
                                        </span>
                                    @else
                                        <span class="flex items-center gap-1 w-1/2 truncate" data-bs-toggle="tooltip"
                                            title="{{ $class->teacher->name . ' ' . $class->teacher->surname }}">
                                            <i class="fas fa-user-tie text-[10px]"></i>
                                            <span
                                                class="truncate">{{ $class->teacher->name . ' ' . $class->teacher->surname }}</span>
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-tasks text-[10px]"></i>
                                            {{ $class->assignments->count() }} заданий
                                        </span>
                                    @endif
                                @endauth
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>

            @auth
                @if (auth()->user()->role === 'teacher' || auth()->user()->role === 'admin')
                    <div class="mt-3 px-2">
                        <a href="{{ route('classes.create', ['return_url' => url()->current()]) }}"
                            class="btn btn-sm w-100 d-flex align-items-center justify-content-center gap-1 text-indigo-600 border-indigo-500 hover:bg-[#616EBD] hover:border-indigo-600 hover:text-white transition-colors duration-200"
                            style="border: 1px solid #616EBD;">
                            <i class="fas fa-plus me-1"></i>
                            Создать класс
                        </a>
                    </div>
                @endif
            @endauth
        </div>
    </nav>

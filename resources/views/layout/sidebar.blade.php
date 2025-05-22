<div class="sidebar">
    <div class="logo">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/logo.svg') }}" alt="Логотип">
        </a>
    </div>
    <nav>
        <div class="items">
            <a href="{{ route('user.dashboard') }}" class="{{ $activePage === 'dashboard' ? 'item-active' : 'item' }}">
                <div class="{{ $activePage === 'dashboard' ? 'icon-active' : 'icon' }}">
                    <i class="fas fa-home"></i>
                </div>
                Главная
            </a>
            <a href="{{ route('user.classes') }}" class="{{ $activePage === 'classes' ? 'item-active' : 'item' }}">
                <div class="{{ $activePage === 'classes' ? 'icon-active' : 'icon' }}">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                Мои классы
            </a>
            <a href="{{ route('user.assignments') }}" class="{{ $activePage === 'tasks' ? 'item-active' : 'item' }}">
                <div class="{{ $activePage === 'tasks' ? 'icon-active' : 'icon' }}">
                    <i class="fas fa-tasks"></i>
                </div>
                Задания
            </a>
            <a href="{{ route('user.calendar') }}" class="{{ $activePage === 'calendar' ? 'item-active' : 'item' }}">
                <div class="{{ $activePage === 'calendar' ? 'icon-active' : 'icon' }}">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                Календарь
            </a>
            {{-- <a href="{{ route('user.statistics') }}"
                class="{{ $activePage === 'statistics' ? 'item-active' : 'item' }}">
                <div class="{{ $activePage === 'statistics' ? 'icon-active' : 'icon' }}">
                    <i class="fas fa-chart-pie"></i>
                </div>
                Статистика
            </a> --}}
            <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();"
                class="item">
                <div class="icon">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                Выход
            </a>
            <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>

        <div class="classes">
            <div class="head">
                <h3>Мои классы</h3>
                @auth
                    @if (auth()->user()->role === 'teacher' || auth()->user()->role === 'admin')
                        <a href="{{ route('classes.create') }}">
                            <i class="fas fa-plus"></i>
                        </a>
                    @endif
                @endauth

            </div>
            <div class="class">
                @foreach ($classes as $class)
                    <a href="{{ route('class.show', $class->id) }}">
                        <span></span>
                        {{ $class->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </nav>
</div>

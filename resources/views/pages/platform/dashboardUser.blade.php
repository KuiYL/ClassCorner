@extends('pages.platform.layout', ['activePage' => 'dashboard', 'title' => 'Главная', 'quick_action' => 'null'])
@section('content')
    <div class="container-fluid py-6 px-md-4">
        <div class="bg-[#6E76C1] text-white rounded-lg shadow-lg mb-8 overflow-hidden">
            <div class="p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <h2 class="text-3xl font-bold">Добро пожаловать, {{ $user->name }} {{ $user->surname }}!</h2>
                    <p class="mt-2 text-lg opacity-90">У вас {{ $totalAssignments - $completedAssignments }} невыполненных
                        заданий</p>
                </div>

                <a href="{{ route('user.assignments') }}"
                    class="inline-flex items-center px-5 py-2 bg-white/10 hover:bg-white/20 text-white font-medium rounded-md transition duration-200">
                    <i class="fas fa-book-open mr-2"></i> Мои задания
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-12">
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-[#6E76C1]">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-[#6E76C1]/10 text-[#6E76C1]">
                        <i class="fas fa-school text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Активные классы</p>
                        <h3 class="text-2xl font-semibold text-gray-800">{{ $totalClasses }}</h3>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Выполнено заданий</p>
                        <h3 class="text-2xl font-semibold text-gray-800">{{ $completedAssignments }}</h3>
                    </div>
                </div>
            </div>

            @php
                use Carbon\Carbon;

                $now = Carbon::now();
                $activeAssignments = collect($assignments)->filter(function ($item) use ($now) {
                    $dueDate = Carbon::parse($item['assignment']->due_date);
                    return $dueDate->gte($now);
                });

                $totalActiveAssignments = $activeAssignments->count();
            @endphp

            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-tasks text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Всего заданий</p>
                        <h3 class="text-2xl font-semibold text-gray-800">{{ $totalActiveAssignments }}</h3>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-red-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-hourglass-half text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Осталось</p>
                        <h3 class="text-2xl font-semibold text-gray-800">
                            {{ $totalActiveAssignments - $completedAssignments }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-16">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900 pl-4 relative">
                    <span class="absolute left-0 top-0 h-full w-1 bg-[#6E76C1]"></span>
                    Мои классы
                </h3>
                <a href="{{ route('user.classes') }}" class="text-[#6E76C1] hover:text-[#616EBD] text-sm font-medium">
                    Смотреть все <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            @if ($classes->isEmpty())
                <div class="text-center py-10 bg-white rounded-lg shadow-sm border border-dashed border-gray-300">
                    <i class="fas fa-school text-gray-300 text-4xl mb-4"></i>
                    <h4 class="text-lg font-semibold text-gray-600">Вы не состоите ни в одном классе</h4>
                    <p class="text-gray-500 mt-1">Подключитесь к классу, чтобы начать выполнять задания</p>
                </div>
            @else
                <div id="classes-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($classes->take(6) as $class)
                        <div
                            class="class-card bg-white rounded-lg shadow-sm overflow-hidden border-l-4 border-[#6E76C1] transition-all duration-300 hover:shadow-md">
                            <div
                                class="p-4 bg-gradient-to-r from-[#6E76C1] to-[#9CA4F2] flex justify-between items-start relative">
                                <h4 class="font-bold text-lg text-white flex items-center gap-2 truncate">
                                    <i class="fas fa-school text-white/80"></i>
                                    <span class="truncate">{{ $class->name }}</span>
                                </h4>

                                <div class="relative group">
                                    <i
                                        class="fas fa-cog text-white cursor-pointer group-hover:text-[#6E76C1] transition-colors"></i>
                                    <div
                                        class="absolute right-0 top-full mt-2 w-36 bg-white shadow-md rounded-md z-10 menu border border-gray-200">
                                        <button type="button"
                                            class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100 text-sm flex items-center delete-button"
                                            data-id="{{ $class->id }}" data-name="{{ $class->name }}"
                                            data-type="exitStudent">
                                            <i class="fas fa-door-open mr-2 text-red-600"></i></i> Выйти
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 pt-3 pb-3 space-y-2">
                                <div class="flex justify-between text-sm text-gray-600">
                                    <div class="flex items-center gap-1">
                                        <i class="fas fa-users text-[#6E76C1]"></i>
                                        <span>{{ $class->students()->count() - 1 }} учеников</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <i class="fas fa-book-open text-[#6E76C1]"></i>
                                        <span>{{ $class->assignments->count() }} заданий</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 text-sm text-gray-500 mt-2">
                                    <i class="fas fa-chalkboard-teacher text-[#6E76C1]"></i>
                                    <span>{{ $class->teacher->name ?? 'Не указан' }}
                                        {{ $class->teacher->surname ?? '' }}</span>
                                </div>

                                @if ($class->description)
                                    <p class="text-base text-gray-500 line-clamp-2 truncate mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>{{ $class->description }}
                                    </p>
                                @else
                                    <div class="flex items-center text-base text-gray-400 mt-1">
                                        <i class="fas fa-info-circle mr-1"></i> Нет описания
                                    </div>
                                @endif
                            </div>

                            <div class="px-4 py-2 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Создан: {{ \Carbon\Carbon::parse($class->created_at)->format('d.m.Y') }}
                                </div>
                                <a href="{{ route('class.show', $class->id) }}"
                                    class="inline-flex items-center ml-2 px-3 py-1 text-xs bg-[#6E76C1] text-white font-medium rounded-md hover:bg-[#6E76C1]/80 transition duration-200">
                                    Подробнее <i class="fas fa-arrow-right ml-1 text-sm"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="mb-8 w-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900 pl-4 relative">
                    <span class="absolute left-0 top-0 h-full w-1 bg-[#6E76C1]"></span>
                    Задания на выполнение
                </h3>
                <a href="{{ route('user.assignments') }}" class="text-sm text-[#6E76C1] hover:text-[#616EBD] font-medium">
                    Смотреть все <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            @php

                $filteredAssignments = array_filter($assignments, function ($item) {
                    $status = $item['submission']?->status ?? 'not_submitted';
                    $dueDate = Carbon::parse($item['assignment']->due_date);
                    $now = Carbon::now();

                    return $status === 'not_submitted' && $dueDate->isFuture();
                });
            @endphp

            <div id="assignments-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @forelse ($filteredAssignments as $item)
                    @php
                        $assignment = $item['assignment'];
                        $status = $item['submission']?->status ?? 'not_submitted';

                        $badgeClass = 'bg-red-50 text-red-600';
                        $deadline = \Carbon\Carbon::parse($assignment->due_date);
                        $description = $assignment->description ?: 'Нет описания';
                    @endphp

                    <div class="card h-full shadow-sm hover:shadow-md transition-shadow duration-200 border border-gray-200 rounded-lg overflow-hidden bg-white"
                        data-id="{{ $assignment->id }}">

                        <div class="p-4">
                            <div class="flex justify-between items-start">
                                <h4 class="font-bold text-gray-900 flex items-center group truncate">
                                    <i
                                        class="fas fa-book-open mr-2 text-[#6E76C1] group-hover:scale-110 transition-transform duration-200"></i>
                                    {{ $assignment->title }}
                                </h4>
                                <span class="inline-block px-2 py-1 text-xs font-medium rounded-full {{ $badgeClass }}">
                                    Не выполнено
                                </span>
                            </div>

                            <p class="text-sm text-gray-600 mt-2 truncate">{{ $description }}</p>
                            <p class="mt-1">Класс: <span class="font-medium">{{ $assignment->class->name }}</span></p>
                            <div class="mt-2 text-sm text-gray-500 space-y-2">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-calendar-check text-[#6E76C1]"></i>
                                    <strong class="text-gray-700">Дедлайн:</strong>
                                    <span>{{ $deadline->format('d.m.Y') }} в {{ $deadline->format('H:i') }}</span>
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-200 flex justify-end gap-2">
                                <a href="{{ route('assignments.show', $assignment->id) }}"
                                    class="btn outline-none text-red-600 border-red-600 hover:bg-red-600 hover:text-gray-100 rounded-md px-3 py-1 inline-flex items-center">
                                    <i class="fas fa-arrow-right mr-1"></i> Перейти
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-full text-center py-10 bg-white rounded-lg shadow-sm border border-dashed border-gray-300">
                        <i class="fas fa-folder-open text-gray-300 text-4xl mb-2"></i>
                        <p class="text-gray-500">Нет заданий на выполнение.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".class-card .fa-cog").forEach(cogIcon => {
                cogIcon.addEventListener("click", function(event) {
                    event.stopPropagation();
                    const menu = this.nextElementSibling;
                    const isMenuVisible = menu.classList.contains("show");
                    document.querySelectorAll(".class-card .menu").forEach(menu => {
                        menu.classList.remove("show");
                    });
                    if (!isMenuVisible) {
                        menu.classList.add("show");
                    }
                });
            });

            document.addEventListener("click", function() {
                document.querySelectorAll(".class-card .menu").forEach(menu => {
                    menu.classList.remove("show");
                });
            });
        });
    </script>
@endsection

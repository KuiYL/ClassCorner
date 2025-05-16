<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Платформа для преподавателей</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            scrollbar-width: thin;
            scrollbar-color: #4f46e5 #f1f1f1;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background-color: #4f46e5;
            border-radius: 6px;
        }

        .class-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
        }

        .floating-btn {
            box-shadow: 0 4px 14px 0 rgba(79, 70, 229, 0.3);
            transition: all 0.3s ease;
        }

        .floating-btn:hover {
            transform: scale(1.1);
        }

        .task-completed {
            background-color: #ecfdf5;
            border-color: #10b981;
        }

        .task-urgent {
            background-color: #fef2f2;
            border-color: #ef4444;
        }

        .animate-pop {
            animation: pop 0.3s ease-out;
        }

        @keyframes pop {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>
</head>

<body class="bg-gray-50 font-sans flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div class="sidebar w-64 bg-white border-r border-gray-200 flex flex-col overflow-y-auto">
        <div class="p-4 flex items-center space-x-3">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo.svg') }}" alt="Логотип">
            </a>
        </div>
        <nav class="flex-1 px-3 py-4">
            <div class="space-y-1">
                <a href="{{ route('user.dashboard') }}"
                    class="flex items-center px-3 py-2 text-md font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-home mr-3 text-gray-500"></i>
                    Главная
                </a>
                <a href="{{ route('user.classes') }}"
                    class="flex items-center px-3 py-2 text-md font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-chalkboard-teacher mr-3 text-gray-500"></i>
                    Мои классы
                </a>
                <a href="#"
                    class="flex items-center px-3 py-2 text-md font-medium rounded-lg bg-indigo-50 text-[#6E76C1]">
                    <i class="fas fa-tasks mr-3text-[#6E76C1]"></i>
                    Задания
                </a>
                <a href="#"
                    class="flex items-center px-3 py-2 text-md font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-calendar-alt mr-3 text-gray-500"></i>
                    Календарь
                </a>
                <a href="#"
                    class="flex items-center px-3 py-2 text-md font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-file-alt mr-3 text-gray-500"></i>
                    Материалы
                </a>
            </div>

            <div class="mt-8">
                <div class="flex items-center justify-between px-3 mb-2">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Мои классы</h3>
                    <a href="{{ route('classes.create') }}" class="text-xs text-[#6E76C1] hover:text-indigo-800">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
                <div class="mt-2 space-y-1">
                    @foreach ($classes as $class)
                        <a href="#"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                            <span class="w-2 h-2 mr-3 rounded-full bg-red-500"></span>
                            {{ $class->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </nav>
    </div>

    <!-- Основной контент -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center justify-between">
            <div class="flex items-center">
                <button class="mr-4 text-gray-500 md:hidden">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="relative w-96">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Поиск по классам, заданиям...">
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <img class="w-16 h-16 rounded-full"
                    src="{{ isset($user->avatar) && Str::startsWith($user->avatar, 'images/')
                        ? asset(Str::replace('images/', 'images/avatar', $user->avatar) . '.svg')
                        : asset('images/default-avatar.svg') }}"
                    alt="Профиль">
                <div class="flex flex-col items-start">
                    <div class="ml-3">
                        <p class="text-md font-medium text-gray-900">{{ $user->name . ' ' . $user->surname }}</p>
                    </div>
                    <div class="ml-3">
                        <p class="text-[15px] font-medium text-gray-800">Преподаватель</p>
                    </div>
                </div>

                <button class="ml-auto text-gray-400 hover:text-gray-500">
                    <i class="fas fa-cog"></i>
                </button>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
            <div class="p-6 bg-gray-50">
                <div class="max-w-7xl mx-auto">
                    <div class="mb-4 flex justify-between items-center">
                        <h1 class="text-2xl font-bold text-gray-800">Мои классы</h1>
                    </div>
                    <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 mb-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-medium text-indigo-800">Создать новый класс</h3>
                                <p class="text-sm text-[#6E76C1]">Добавьте студентов, материалы и задания</p>
                            </div>
                            <a href="{{ route('assignments.create') }}"
                                class="bg-[#6E76C1] hover:bg-[#555EB1] text-white rounded-lg px-4 py-2 flex items-center">
                                <i class="fas fa-plus mr-2"></i>
                                Новый класс
                            </a>
                        </div>
                    </div>
                    @if ($classes->isEmpty())
                        <div class="p-4 bg-yellow-100 text-yellow-700 rounded-lg">
                            У вас пока нет классов. Нажмите "Добавить класс", чтобы создать новый.
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($classes as $class)
                                <div class="class-card bg-white shadow-sm rounded-lg p-4">
                                    <h2 class="font-bold text-lg text-gray-800">{{ $class->name }}</h2>
                                    <div class="mt-2 flex space-x-2">
                                        <a href="{{ route('assignments.edit', $class->id) }}"
                                            class="text-blue-500 hover:underline">Изменить</a>
                                        <form action="{{ route('assignments.destroy', $class->id) }}" method="POST"
                                            onsubmit="return confirm('Удалить класс?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline">Удалить</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <a href="{{ route('assignments.create') }}"
        class="floating-btn fixed bottom-6 right-6 w-14 h-14 rounded-full bg-[#6E76C1] text-white flex items-center justify-center text-xl hover:bg-[#6E76C1]">
        <i class="fas fa-plus"></i>
    </a>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const classCards = document.querySelectorAll('.class-card');
            classCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100 + index * 100);
            });

            const createBtn = document.querySelector('.floating-btn');
            createBtn.style.opacity = '0';
            createBtn.style.transform = 'scale(0.5)';
            setTimeout(() => {
                createBtn.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                createBtn.style.opacity = '1';
                createBtn.style.transform = 'scale(1)';
            }, 500);

            const interactiveItems = document.querySelectorAll(
                '.class-card, .task-completed, .task-urgent, [href]');
            interactiveItems.forEach(item => {
                item.style.transition = 'all 0.2s ease';
                item.addEventListener('mousedown', () => {
                    item.classList.add('animate-pop');
                });
                item.addEventListener('animationend', () => {
                    item.classList.remove('animate-pop');
                });
            });
        });
    </script>
</body>

</html>

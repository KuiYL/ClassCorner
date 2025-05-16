</html>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Платформа для студентов</title>
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

        .task-due-soon {
            background-color: #fffbeb;
            border-color: #f59e0b;
        }

        .task-overdue {
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

        .progress-ring__circle {
            transition: stroke-dashoffset 0.5s ease;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div class="sidebar w-64 bg-white border-r border-gray-200 flex flex-col overflow-y-auto">
        <div class="p-4 flex items-center space-x-3">
            <img src="{{ asset('images/logo.svg') }}" alt="">
        </div>

        <nav class="flex-1 px-3 py-4">
            <div class="space-y-1">
                <a href="#"
                    class="flex items-center px-3 py-2 text-md font-medium rounded-lg bg-indigo-50 text-[#6E76C1]">
                    <i class="fas fa-home mr-3 text-[#6E76C1]"></i>
                    Главная
                </a>
                <a href="#"
                    class="flex items-center px-3 py-2 text-md font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-chalkboard mr-3 text-gray-500"></i>
                    Мои классы
                </a>
                <a href="#"
                    class="flex items-center px-3 py-2 text-md font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-tasks mr-3 text-gray-500"></i>
                    Мои задания
                </a>
                <a href="#"
                    class="flex items-center px-3 py-2 text-md font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-calendar-alt mr-3 text-gray-500"></i>
                    Расписание
                </a>
            </div>

            <div class="mt-8">
                <div class="flex items-center justify-between px-3 mb-2">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Мои классы</h3>
                </div>
                <div class="mt-2 space-y-1">
                    <a href="#"
                        class="flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                        <span class="w-2 h-2 mr-3 rounded-full bg-red-500"></span>
                        Математика 10-А
                    </a>
                    <a href="#"
                        class="flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                        <span class="w-2 h-2 mr-3 rounded-full bg-blue-500"></span>
                        Физика 11-Б
                    </a>
                    <a href="#"
                        class="flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                        <span class="w-2 h-2 mr-3 rounded-full bg-green-500"></span>
                        Web-разработка
                    </a>
                    <a href="#"
                        class="flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                        <span class="w-2 h-2 mr-3 rounded-full bg-yellow-500"></span>
                        Английский язык
                    </a>
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
                        <p class="text-[15px] font-medium text-gray-800">Обучающийся</p>
                    </div>
                </div>
                <button class="ml-auto text-gray-400 hover:text-gray-500">
                    <i class="fas fa-cog"></i>
                </button>
            </div>
        </header>


        <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
            <div class="max-w-7xl mx-auto">
                <div
                    class="mb-8 flex flex-col lg:flex-row justify-between items-center bg-gradient-to-r from-blue-50 via-indigo-50 to-purple-50 p-6 rounded-lg shadow-xl">
                    <div class="mb-6 lg:mb-0">
                        <h2 class="text-3xl font-bold text-gray-900">Добро пожаловать, {{ $user->name }}!</h2>
                        <p class="text-lg text-gray-600 mt-2">У вас 2 задания на сегодня и 1 новое сообщение</p>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                        <div class="bg-white p-6 rounded-2xl shadow-lg hover:bg-gray-50 transition duration-300">
                            <p class="text-sm text-gray-600">Мои классы</p>
                            <p class="text-2xl font-semibold text-[#6E76C1]">4</p>
                        </div>
                        <div class="bg-white p-6 rounded-2xl shadow-lg hover:bg-gray-50 transition duration-300">
                            <p class="text-sm text-gray-600">Активные задания</p>
                            <p class="text-2xl font-semibold text-[#6E76C1]">7</p>
                        </div>
                        <div class="bg-white p-6 rounded-2xl shadow-lg hover:bg-gray-50 transition duration-300">
                            <p class="text-sm text-gray-600">Выполнено</p>
                            <p class="text-2xl font-semibold text-[#6E76C1]">23</p>
                        </div>
                        <div class="bg-white p-6 rounded-2xl shadow-lg hover:bg-gray-50 transition duration-300">
                            <p class="text-sm text-gray-600">Пропущенные</p>
                            <p class="text-2xl font-semibold text-[#6E76C1]">2</p>
                        </div>
                    </div>
                </div>

                <!-- Мои классы -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Мои классы</h3>
                        <a href="#" class="text-sm text-[#6E76C1] font-medium">Все классы</a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- @foreach ($classes as $class) --}}
                        <div
                            class="class-card bg-white rounded-xl shadow-sm overflow-hidden transition-all duration-300">
                            <div class="h-24 bg-gradient-to-r from-[#6E76C1] to-[#A5A9D1] relative">
                                <button class="absolute top-2 right-2 text-white hover:text-gray-200">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                            <div class="p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-semibold text-gray-800"></h4>
                                    <span class="text-xs py-1 px-2 rounded-full bg-gray-100 text-gray-800">
                                        ученика</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600 mb-3">
                                    <i class="fas fa-user-tie mr-2"></i>
                                    <span></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="text-xs text-gray-500">
                                        <i class="fas fa-tasks mr-1"></i>
                                        <span> активных задания</span>
                                    </div>
                                    <a href="" class="text-sm text-[#6E76C1] font-medium">Открыть</a>
                                </div>
                            </div>
                        </div>
                        {{-- @endforeach --}}
                    </div>
                </div>

                <!-- Мои задания -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Мои задания</h3>
                        <div class="flex space-x-2">
                            <a href="#" class="text-sm text-[#6E76C1] font-medium">Смотреть все</a>
                            <div class="relative inline-block text-left">
                                <button class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <!-- Задание 1 -->
                        <div class="p-4 border rounded-lg bg-yellow-50">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-1">
                                        <span class="font-medium text-gray-800">Домашняя работа: квадратные
                                            уравнения</span>
                                        <span
                                            class="ml-2 text-xs py-0.5 px-2 rounded-full bg-yellow-100 text-yellow-800">Срок:
                                            завтра</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2">Математика 10-А · Д.В. Иванов</p>
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center text-xs text-gray-500">
                                            <i class="fas fa-clock mr-1"></i>
                                            <span>Срок сдачи: до 12:00</span>
                                        </div>
                                        <span
                                            class="text-xs font-medium bg-gray-100 text-gray-800 py-1 px-2 rounded-full">В
                                            процессе</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="w-8 h-8 relative">
                                        <svg class="w-full h-full" viewBox="0 0 36 36">
                                            <circle class="progress-ring__background stroke-gray-200" stroke-width="3"
                                                fill="none" r="16" cx="18" cy="18"></circle>
                                            <circle class="progress-ring__circle stroke-indigo-500" stroke-width="3"
                                                stroke-dasharray="100" stroke-dashoffset="30" fill="none" r="16"
                                                cx="18" cy="18"></circle>
                                        </svg>
                                        <div
                                            class="absolute inset-0 flex items-center justify-center text-xs font-medium">
                                            70%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Задание 2 -->
                        <div class="p-4 border rounded-lg bg-red-50">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-1">
                                        <span class="font-medium text-gray-800">Лабораторная работа по механике</span>
                                        <span
                                            class="ml-2 text-xs py-0.5 px-2 rounded-full bg-red-100 text-red-800">Просрочено</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2">Физика 11-Б · Д.В. Иванов</p>
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center text-xs text-gray-500">
                                            <i class="fas fa-exclamation-circle mr-1 text-red-500"></i>
                                            <span>Срок сдачи: 2 дня назад</span>
                                        </div>
                                        <span
                                            class="text-xs font-medium bg-red-100 text-red-800 py-1 px-2 rounded-full">Не
                                            сдано</span>
                                    </div>
                                </div>
                                <button class="ml-4 p-2 text-gray-400 hover:text-[#6E76C1]">
                                    <i class="fas fa-arrow-up"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Задание 3 -->
                        <div class="p-4 border rounded-lg bg-green-50">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-1">
                                        <span class="font-medium text-gray-800">Верстка макета на HTML/CSS</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2">Web-разработка · Д.В. Иванов</p>
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center text-xs text-gray-500">
                                            <i class="fas fa-clock mr-1"></i>
                                            <span>Срок сдачи: до 15 июня</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span
                                                class="text-xs font-medium bg-green-100 text-green-800 py-1 px-2 rounded-full mr-2">Сдано</span>
                                            <span class="text-xs font-bold text-gray-800">8/10</span>
                                        </div>
                                    </div>
                                </div>
                                <button class="ml-4 p-2 text-gray-400 hover:text-[#6E76C1]">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>

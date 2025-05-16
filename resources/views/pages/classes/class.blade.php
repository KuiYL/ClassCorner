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

        .banner {
            height: 300px;
            background-image: url('your-banner-image.jpg');
            /* Замените на вашу картинку */
            background-size: cover;
            background-position: center;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .banner-overlay {
            background: rgba(231, 233, 250, 0.7);
            /* Цвет фона */
            height: 100%;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: #3b3b3b;
        }

        .banner h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: #201C44;
            /* Цвет текста */
        }

        .banner p {
            font-size: 1.2rem;
            margin-bottom: 20px;
            color: #201C44;
        }

        .banner img {
            max-width: 150px;
            border-radius: 50%;
            margin-top: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .class-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .class-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #4f46e5;
            color: white;
            border-radius: 6px;
            padding: 8px 16px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #4338ca;
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .table-striped tbody tr:hover {
            background-color: #e2e8f0;
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
                    class="flex items-center px-3 py-2 text-md font-medium rounded-lg bg-indigo-50 text-[#6E76C1]">
                    <i class="fas fa-home mr-3 text-[#6E76C1]"></i>
                    Главная
                </a>
                <a href="{{ route('user.classes') }}"
                    class="flex items-center px-3 py-2 text-md font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-chalkboard-teacher mr-3 text-gray-500"></i>
                    Мои классы
                </a>
                <a href="#"
                    class="flex items-center px-3 py-2 text-md font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-tasks mr-3 text-gray-500"></i>
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
                    @foreach ($classes as $item)
                        <a href="#"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                            <span class="w-2 h-2 mr-3 rounded-full bg-red-500"></span>
                            {{ $item->name }}
                        </a>
                    @endforeach
                </div>
            </div>

        </nav>
    </div>

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
            <div class="banner relative mb-6">
                <div
                    class="banner-overlay absolute inset-0 flex flex-col justify-center items-center text-white bg-opacity-50 p-6">
                    <h1 class="text-4xl font-bold">{{ $class->name }}</h1>
                    <p class="text-xl mt-2">{{ $class->description }}</p>
                </div>
            </div>

            <div>
                <h2 class="text-lg font-medium text-gray-900 mb-4">Задания</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($assignments as $assignment)
                        <div
                            class="class-card p-4 border border-gray-200 rounded-lg bg-white shadow-sm hover:shadow-md transition-transform transform hover:-translate-y-1">
                            <h3 class="text-md font-bold text-gray-900">{{ $assignment->title }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $assignment->description }}</p>
                            <p class="text-xs text-gray-400 mt-2">Срок: {{ $assignment->due_date }}</p>
                            <a href="" class="text-sm text-indigo-600 hover:underline mt-2 block">Подробнее</a>
                        </div>
                    @empty
                        <p class="text-gray-500">Нет заданий.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <h2 class="text-lg font-medium text-gray-900 mb-4">Студенты</h2>
                <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Имя
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Прогресс
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Действия
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($students->skip(1) as $student)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $student->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $student->progress }}%</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('students.show', $student->id) }}"
                                            class="text-indigo-600 hover:underline text-sm">Подробнее</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">Нет студентов.</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </main>
    </div>

    <a href="{{ route('assignments.create') }}"
        class="floating-btn fixed bottom-6 right-6 w-14 h-14 rounded-full bg-[#6E76C1] text-white flex items-center justify-center text-xl hover:bg-[#6E76C1]">
        <i class="fas fa-plus"></i>
    </a>
</body>

</html>

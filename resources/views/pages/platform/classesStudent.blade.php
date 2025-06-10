@extends('pages.platform.layout', ['activePage' => 'classes', 'title' => 'Классы', 'quick_action' => 'null'])
@section('content')
    <div class="container-fluid py-6 px-md-4">
        <div class="bg-white rounded-lg shadow-md border-l-4 border-[#6E76C1] mb-6 overflow-hidden">
            <div class="p-6 flex justify-between items-center bg-[#EEF2FF]">
                <div>
                    <h3 class="text-xl font-semibold text-[#555EB1]">Ваши классы</h3>
                    <p class="mt-2 text-sm text-[#6E76C1] font-medium">
                        Посмотрите информацию по вашим классам и заданиям
                    </p>
                </div>
                <a id="open-invitations-modal"
                   class="inline-flex items-center px-4 py-2 bg-[#6E76C1] hover:bg-[#555EB1] text-white font-medium rounded-md transition duration-200">
                    <i class="fas fa-envelope mr-2"></i> Посмотреть приглашения
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center mb-6 relative">
                <h3 class="text-xl font-bold text-gray-900 pl-4 relative">
                    <span
                        class="absolute left-0 top-1/2 transform -translate-y-1/2 w-1 h-5 bg-[#6E76C1] rounded-full"></span>
                    Мои классы
                </h3>
            </div>

            @if ($classes->isEmpty())
                <div class="text-center py-10 bg-gray-50 border border-dashed border-gray-300 rounded-lg">
                    <i class="fas fa-school text-gray-300 text-4xl mb-4"></i>
                    <h4 class="text-lg font-semibold text-gray-600">У вас пока нет классов</h4>
                    <p class="text-gray-500 mt-1">Ожидайте приглашения или создайте новый класс.</p>
                </div>
            @else
                <div id="classes-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($classes as $class)
                        <div class="class-card bg-white rounded-lg shadow-sm overflow-hidden border-l-4 border-[#6E76C1] transition-all duration-300 hover:shadow-md">
                            <div class="p-4 bg-gradient-to-r from-[#6E76C1] to-[#9CA4F2] flex justify-between items-start relative">
                                <h4 class="font-bold text-lg text-white flex items-center gap-2 truncate">
                                    <i class="fas fa-school text-white/80"></i>
                                    <span class="truncate">{{ $class->name }}</span>
                                </h4>
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

                                @if ($class->description)
                                    <p class="text-base text-gray-500 line-clamp-2 truncate mt-1">
                                        {{ $class->description }}
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

        <div id="invitations-modal" class="modal-invite hidden">
            <div class="modal-invite-content bg-white rounded-lg shadow-lg p-6">
                <span class="close-btn text-gray-400 cursor-pointer" id="close-invitations-modal">&times;</span>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Входящие приглашения</h3>

                @if ($invitations->isEmpty())
                    <p class="text-gray-500">У вас нет активных приглашений.</p>
                @else
                    <ul id="invitations-list" class="divide-y divide-gray-200">
                        @foreach ($invitations as $invitation)
                            <li class="py-4">
                                <strong class="block text-gray-800">{{ $invitation->class->name }}</strong>
                                <small class="block text-gray-600">Преподаватель: {{ optional($invitation->class->teacher)->name }}</small>

                                <div class="mt-2 flex gap-2">
                                    <form action="{{ route('invitations.accept', $invitation->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn primary bg-green-500 text-white px-4 py-2 rounded-md">Принять</button>
                                    </form>

                                    <form action="{{ route('invitations.decline', $invitation->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn secondary bg-red-500 text-white px-4 py-2 rounded-md">Отклонить</button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
                <button id="close-invitations-bottom" class="btn secondary bg-gray-200 px-4 py-2 rounded-md mt-4">Закрыть</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById('invitations-modal');
            const openBtn = document.getElementById('open-invitations-modal');
            const closeBtns = document.querySelectorAll('#close-invitations-modal, #close-invitations-bottom');

            openBtn.addEventListener('click', () => {
                modal.classList.remove('hidden');
            });

            closeBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    modal.classList.add('hidden');
                });
            });
        });
    </script>
@endsection

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои классы</title>
    <link rel="stylesheet" href="{{ asset('css/style-platform.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css ">
    <script src="{{ asset('js/script.js') }}" defer></script>
</head>

<body>
    @include('layout.sidebar', ['activePage' => 'classes'])

    <div class="topbar">
        @include('layout.topbar')

        <main>
            <div class="main-platform">
                <div class="banner-new-class">
                    <div class="wrapper">
                        <div>
                            <h3>Ваши классы</h3>
                            <p>Посмотрите информацию по вашим классам и заданиям</p>
                        </div>
                        <a id="open-invitations-modal">Посмотреть приглашения</a>
                    </div>
                </div>

                <div class="classes">
                    <div class="head">
                        <h3>Мои классы</h3>
                    </div>
                    @if ($classes->isEmpty())
                        <div class="warning-message">
                            Вы пока не состоите ни в одном классе.
                        </div>
                    @else
                        <div class="items">
                            @foreach ($classes as $class)
                                <div class="class-card">
                                    <div class="class-card-info">
                                        <div class="card">
                                        </div>
                                    </div>
                                    <div class="info">
                                        <div class="info-student">
                                            <h4>{{ $class->name }}</h4>
                                            <span>{{ $class->students()->count() - 1 }} студентов</span>
                                        </div>
                                        <div class="info-teacher">
                                            <i class="fas fa-user-tie"></i>
                                            <span>{{ $class->teacher->name }}</span>
                                        </div>
                                        <div class="info-assigments">
                                            <div class="assigments-text">
                                                <i class="fas fa-tasks"></i>
                                                <span>{{ $class->assignments->count() }} заданий</span>
                                            </div>
                                            <a href="{{ route('class.show', $class->id) }}"
                                                class="text-sm text-[#6E76C1] font-medium">Открыть</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <div id="invitations-modal" class="modal-invite hidden">
        <div class="modal-invite-content">
            <span class="close-btn" id="close-invitations-modal">&times;</span>
            <h3>Входящие приглашения</h3>
            @if ($invitations->isEmpty())
                <p>У вас нет активных приглашений.</p>
            @else
                <ul id="invitations-list" class="search-results">
                    @foreach ($invitations as $invitation)
                        <li class="invitation-item" data-id="{{ $invitation->id }}">
                            <strong>{{ $invitation->class->name }}</strong><br>
                            <small>Преподаватель: {{ optional($invitation->class->teacher)->name }}</small><br>
                            <small>Статус: {{ $invitation->status }}</small>

                            <div style="margin-top: 10px; display: flex; gap: 10px;">
                                <form action="{{ route('invitations.accept', $invitation->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn primary">Принять</button>
                                </form>

                                <form action="{{ route('invitations.decline', $invitation->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn secondary">Отклонить</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
            <button id="close-invitations-bottom" class="btn secondary">Закрыть</button>
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
</body>

</html>

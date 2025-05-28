<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль</title>
    <link rel="stylesheet" href="{{ asset('css/style-platform.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/script.js') }}" defer></script>
    <link rel="icon" href="{{ asset('icon-logo.svg') }}" type="image/svg+xml">

</head>

<body>
    @include('layout.sidebar', ['activePage' => 'profile'])

    <div class="topbar">
        @include('layout.topbar')
        <main>
            <div class="main-platform">
                <div class="profile-container">
                    <section class="profile-header">
                        <div class="profile-avatar">
                            <a href="{{ route('user.choose-avatar') }}">
                                <img src="{{ isset($user->avatar)
                                    ? (Str::startsWith($user->avatar, 'avatars/')
                                        ? Storage::url($user->avatar)
                                        : (Str::startsWith($user->avatar, 'images/')
                                            ? asset(preg_replace('/^(images\/)/', 'images/avatar', $user->avatar) . '.svg')
                                            : asset('images/default-avatar.svg')))
                                    : asset('images/default-avatar.svg') }}"
                                    alt="Профиль">
                            </a>
                        </div>
                        <div class="profile-info">
                            <h1 class="profile-name"> {{ $user->name }} {{ $user->surname }}</h1>
                            <p class="profile-email">{{ $user->email }}</p>
                        </div>
                        <button class="edit-profile-btn" onclick="showForm('editUserForm')">
                            <i class="fas fa-edit"></i> Редактировать
                        </button>
                    </section>

                    <section class="profile-details">
                        <h2>Информация</h2>
                        <ul class="profile-details-list">
                            <li><strong>Имя и фамилия:</strong> {{ $user->name }} {{ $user->surname }}</li>
                            <li><strong>Email:</strong> {{ $user->email }}</li>
                            @php
                                $roles = [
                                    'admin' => 'Администратор',
                                    'teacher' => 'Преподаватель',
                                    'student' => 'Студент',
                                ];
                            @endphp

                            <li><strong>Роль:</strong> {{ $roles[$user->role] ?? 'Неизвестная роль' }}</li>
                            <li><strong>Дата регистрации:</strong> {{ $user->created_at }}</li>
                        </ul>


                    </section>

                    <section class="profile-actions">
                        <h2>Действия</h2>
                        <div class="actions-buttons">
                            <button class="btn btn-primary" onclick="showForm('changePasswordForm')">
                                <i class="fas fa-key"></i> Сменить пароль
                            </button>
                            <button class="btn delete-btn delete-button" type="button" data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}" data-type="user">
                                <i class="fas fa-trash"></i> Удалить аккаунт
                            </button>
                        </div>
                    </section>

                    @php
                        $showChangePasswordForm =
                            $errors->has('current_password') ||
                            $errors->has('password') ||
                            old('current_password') ||
                            old('password');

                        $showEditUserForm =
                            $errors->has('name') ||
                            $errors->has('surname') ||
                            $errors->has('email') ||
                            old('name') ||
                            old('surname') ||
                            old('email');
                    @endphp

                    <div id="editUserModal" class="modal-form">
                        <div class="modal-form-content">
                            <span class="close-button" onclick="closeEditUserModal()">&times;</span>
                            @include('layout.edit-profile')
                        </div>
                    </div>

                    <div id="changePasswordModal" class="modal-form">
                        <div class="modal-form-content">
                            <span class="close-button" onclick="closeChangePasswordModal()">&times;</span>
                            @include('layout.change-password')
                        </div>
                    </div>


                </div>
            </div>
        </main>
    </div>

    @include('layout.modal-delete')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const editUserModal = document.getElementById('editUserModal');
            const changePasswordModal = document.getElementById('changePasswordModal');

            function closeModal(modal) {
                modal.classList.remove('active');
                modal.addEventListener('transitionend', function handler() {
                    if (!modal.classList.contains('active')) {
                        modal.style.display = 'none';
                    }
                    modal.removeEventListener('transitionend', handler);
                });
            }

            function openModal(modal) {
                modal.style.display = 'flex';
                setTimeout(() => {
                    modal.classList.add('active');
                }, 10);
            }

            document.querySelector('.edit-profile-btn').addEventListener('click', () => openModal(editUserModal));
            document.querySelector('.btn.btn-primary').addEventListener('click', () => openModal(
                changePasswordModal));

            document.querySelectorAll('.close-button').forEach(button => {
                button.addEventListener('click', () => {
                    const modal = button.closest('.modal-form');
                    closeModal(modal);
                });
            });

            [editUserModal, changePasswordModal].forEach(modal => {
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        closeModal(modal);
                    }
                });
            });

            const showEditUserForm = @json($showEditUserForm);
            const showChangePasswordForm = @json($showChangePasswordForm);

            if (showEditUserForm) {
                openModal(editUserModal);
            } else if (showChangePasswordForm) {
                openModal(changePasswordModal);
            }
        });
    </script>
</body>

</html>

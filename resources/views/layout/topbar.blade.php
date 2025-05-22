<div class="header-app">
    <div class="search">
        <div class="block-input">
            <div class="icon">
                <i class="fas fa-search"></i>
            </div>
            <input type="text" placeholder="Поиск по классам, заданиям...">
        </div>
    </div>
    <div class="account">
        <a href="{{ route('user.profile') }}" class="settings" title="Настройки">
            <i class="fas fa-cog"></i>
        </a>
        <div class="text">
            <p class="name">{{ $user->name . ' ' . $user->surname }}</p>
            @php
                $roles = [
                    'admin' => 'Администратор',
                    'teacher' => 'Преподаватель',
                    'student' => 'Студент',
                ];
            @endphp

            <p class="position">{{ $roles[$user->role] ?? 'Неизвестная роль' }}</p>
        </div>
        <img src="{{ isset($user->avatar)
            ? (Str::startsWith($user->avatar, 'avatars/')
                ? Storage::url($user->avatar)
                : (Str::startsWith($user->avatar, 'images/')
                    ? asset(preg_replace('/^(images\/)/', 'images/avatar', $user->avatar) . '.svg')
                    : asset('images/default-avatar.svg')))
            : asset('images/default-avatar.svg') }}"
            alt="Профиль">
    </div>
</div>

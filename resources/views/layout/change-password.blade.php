<form action="{{ route('user.change-password') }}" method="POST" class="form-change-password">
    @csrf
    <div class="head-block">
        <h2><span class="attention-title">Смена</span> пароля</h2>
    </div>
    <div class="form-group">
        <label for="current_password">Текущий пароль <span>*</span></label>
        <div class="password-wrapper">
            <input type="password" id="current_password" name="current_password" placeholder="Введите текущий пароль"
                class="{{ $errors->has('current_password') ? 'input-error' : '' }}">
            <button type="button" class="toggle-password">
                <img src="{{ asset('images/showPassword.svg') }}" alt="Показать пароль" class="icon-show">
                <img src="{{ asset('images/hidePassword.svg') }}" alt="Скрыть пароль" class="icon-hide hidden">
            </button>
        </div>
        @error('current_password')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label for="password">Новый пароль <span>*</span></label>
        <div class="password-wrapper">
            <input type="password" id="password" name="password" placeholder="Введите новый пароль"
                class="{{ $errors->has('password') ? 'input-error' : '' }}">
            <button type="button" class="toggle-password">
                <img src="{{ asset('images/showPassword.svg') }}" alt="Показать пароль" class="icon-show">
                <img src="{{ asset('images/hidePassword.svg') }}" alt="Скрыть пароль" class="icon-hide hidden">
            </button>
        </div>
        @error('password')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label for="password_confirmation">Подтвердите новый пароль <span>*</span></label>
        <div class="password-wrapper">
            <input type="password" id="password_confirmation" name="password_confirmation"
                placeholder="Подтвердите новый пароль"
                class="{{ $errors->has('password_confirmation') ? 'input-error' : '' }}">
            <button type="button" class="toggle-password">
                <img src="{{ asset('images/showPassword.svg') }}" alt="Показать пароль" class="icon-show">
                <img src="{{ asset('images/hidePassword.svg') }}" alt="Скрыть пароль" class="icon-hide hidden">
            </button>
        </div>
        @error('password_confirmation')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
    <button type="submit" class="action-button w-full max-w-full">Сохранить</button>
</form>

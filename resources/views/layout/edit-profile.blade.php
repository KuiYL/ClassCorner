<form action="{{ route('user.edit-profile') }}" method="POST" class="form-edit-profile">
    @csrf
    @method('PUT')
    <div class="head-block">
        <h2><span class="attention-title">Редактирование</span> пользователя</h2>
    </div>
    <div class="form-group">
        <label for="name">Имя <span>*</span></label>
        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" placeholder="Ваше имя"
            class="{{ $errors->has('name') ? 'input-error' : '' }}">
        @error('name')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label for="surname">Фамилия <span>*</span></label>
        <input type="text" id="surname" name="surname" value="{{ old('surname', $user->surname) }}"
            placeholder="Ваша фамилия" class="{{ $errors->has('surname') ? 'input-error' : '' }}">
        @error('surname')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label for="email">Электронная почта <span>*</span></label>
        <input type="email" id="email" name="email" placeholder="Введите электронную почту"
            value="{{ old('email', $user->email) }}" class="{{ $errors->has('email') ? 'input-error' : '' }}">
        @error('email')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
    <button type="submit" class="action-button">Сохранить</button>
</form>

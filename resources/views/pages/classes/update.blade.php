@extends('pages.platform.layout', ['activePage' => 'classes', 'title' => 'Редактирование класса', 'quick_action' => 'null'])
@section('content')
    <div class="main-platform">

        <form action="{{ route('classes.update', $class->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="head-block">
                <h2><span class="attention-title">Изменить</span> данные класса</h1>
            </div>
            <div class="form-group">
                <label for="name">Название <span>*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $class->name) }}"
                    class="{{ $errors->has('name') ? 'input-error' : '' }}">
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Описание (необязательно)</label>
                <textarea id="description" name="description" rows="4"
                    class="{{ $errors->has('description') ? 'input-error' : '' }}">{{ old('description', $class->description) }}</textarea>
                @error('description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <input type="hidden" name="return_url" value="{{ request('return_url', route('user.classes')) }}">

            <button type="submit" class="action-button">Сохранить изменения</button>
        </form>
    </div>
@endsection

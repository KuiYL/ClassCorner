@extends('pages.platform.layout', ['activePage' => 'classes', 'title' => 'Создание класса', 'quick_action' => 'null'])
@section('content')
    <div class="main-platform">

        <form action="{{ route('classes.store') }}" method="POST" class="mt-6">
            @csrf
            <div class="head-block">
                <h2><span class="attention-title">Добавить</span> новый класс</h1>
            </div>
            <div class="form-group">
                <label for="name">Название класса <span>*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="{{ $errors->has('name') ? 'input-error' : '' }}">
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Описание (необязательно)</label>
                <textarea name="description" id="description" rows="4"
                    class="{{ $errors->has('description') ? 'input-error' : '' }}">{{ old('description') }}</textarea>
                @error('description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Преподаватель <span>*</span></label>
                <p class="teacher-name">{{ auth()->user()->name }} {{ auth()->user()->surname }}</p>
                <input type="hidden" name="teacher_id" value="{{ auth()->user()->id }}">
                @error('teacher_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <input type="hidden" name="return_url" value="{{ request('return_url', route('user.classes')) }}">

            <button type="submit" class="action-button">Создать класс</button>
        </form>
    </div>
@endsection

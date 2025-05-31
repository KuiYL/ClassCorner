@extends('pages.platform.layout', ['activePage' => 'profile', 'title' => 'Выбор аватара', 'quick_action' => 'null'])
@section('content')
    <div class="main-platform">
        <div class="choose-avatar">
            <div class="wrapper">
                <div class="head-block">
                    <h2><span class="attention-title">Выбери</span> свой аватар</h2>
                    <p>Можно загрузить свой аватар или выбрать из доступных</p>
                </div>
                <form action="{{ route('user.avatar') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="choice">
                        <div class="upload-section">
                            <label for="custom_avatar" class="upload-label">
                                <img src="{{ asset('images/Upload file area.svg') }}" alt="Загрузите файл"
                                    id="upload-preview">
                            </label>
                            <input type="file" name="avatar" id="custom_avatar" class="hidden-input">
                        </div>

                        <div class="avatar-grid">
                            @foreach (range(1, 16) as $id)
                                <img src="{{ asset('images/avatar' . $id . '.svg') }}" alt="Аватар {{ $id }}"
                                    class="avatar-option" data-avatar-id="{{ $id }}">
                            @endforeach
                        </div>
                    </div>
                    <input type="hidden" name="default_avatar" id="selected-avatar" value="">
                    <div class="buttons">
                        <a href="{{ route('user.profile') }}">
                            <div class="button-next">
                                <p>Вернуться назад</p>
                                <img src="{{ asset('images/arrow-right button.svg') }}" alt="">
                            </div>
                        </a>
                        <button type="submit">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

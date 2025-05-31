@extends('pages.platform.layout', ['activePage' => 'classes', 'title' => 'Классы', 'quick_action' => 'classes.create'])
@section('content')
    <div class="main-platform">
        <div class="banner-new-class">
            <div class="wrapper">
                <div>
                    <h3>Создать новый класс</h3>
                    <p>Добавьте студентов, материалы и задания</p>
                </div>
                <a href="{{ route('classes.create', ['return_url' => url()->current()]) }}">
                    <i class="fas fa-plus"></i>
                    Новый класс
                </a>
            </div>
        </div>

        <div class="classes">
            <div class="head">
                <h3>Мои классы</h3>
            </div>
            @if ($classes->isEmpty())
                <div class="warning-message">
                    У вас пока нет классов. Нажмите "Новый класс", чтобы создать новый.
                </div>
            @else
                <div class="items">
                    @foreach ($classes as $class)
                        <div class="class-card">
                            <div class="class-card-info">
                                <div class="card">
                                    <div class="class-settings">
                                        <i class="fas fa-cog"></i>
                                        <div class="settings-menu hidden">
                                            <a class="setting-menu-action" href="{{ route('classes.edit', $class->id) }}">
                                                <i class="fas fa-edit"></i> Изменить
                                            </a>
                                            <button class="setting-menu-action delete-button" type="button"
                                                data-id="{{ $class->id }}" data-name="{{ $class->name }}"
                                                data-type="class">
                                                <i class="fas fa-trash"></i>Удалить
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="info">
                                <div class="info-student">
                                    <h4>{{ $class->name }}</h4>
                                    <span>{{ $class->students()->count() - 1 }} обучающихся</span>
                                </div>
                                <div class="info-teacher">
                                    <i class="fas fa-user-tie"></i>
                                    <span>{{ $class->teacher->name }} {{ $class->teacher->surname }}</span>
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

@endsection

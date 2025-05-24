@extends('pages.admin.layout', ['activePage' => 'classes'])

@section('content')
    <div class="admin-dashboard">
        <h2>Классы</h2>
        <a href="{{ route('classes.create') }}" class="btn btn-primary">Создать новый</a>

        <div class="section">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Преподаватель</th>
                        <th>Студенты</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($classes as $class)
                        <tr>
                            <td data-label="ID">{{ $class->id }}</td>
                            <td data-label="Название">{{ $class->name }}</td>
                            <td data-label="Преподаватель">
                                {{ optional($class->teacher)->name ?? 'Не назначен' }}
                            </td>
                            <td data-label="Студенты">
                                {{ $class->students_count }}
                            </td>
                            <td data-label="Действия" class="actions">
                                <a href="{{ route('classes.edit', $class->id) }}" class="btn btn-sm btn-edit">Изменить</a>
                                <form action="{{ route('classes.destroy', $class->id) }}" method="POST"
                                    class="action-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-delete">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination">
                {{ $classes->links() }}
            </div>
        </div>
    </div>
@endsection

@extends('pages.admin.layout', ['activePage' => 'assignments'])

@section('content')
    <div class="admin-dashboard">
        <h2>Задания</h2>
        <a href="{{ route('assignments.create', ['classId' => request('classId')]) }}" class="btn btn-primary">Создать
            новое</a>

        <div class="section">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Класс</th>
                        <th>Дедлайн</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($assignments as $assignment)
                        <tr>
                            <td data-label="ID">{{ $assignment->id }}</td>
                            <td data-label="Название">{{ $assignment->title }}</td>
                            <td data-label="Класс">{{ optional($assignment->class)->name ?? 'Без класса' }}</td>
                            <td data-label="Дедлайн">{{ $assignment->due_date }}</td>
                            <td data-label="Действия" class="actions">
                                <form action="{{ route('assignments.destroy', $assignment->id) }}" method="POST"
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
                {{ $assignments->links() }}
            </div>
        </div>
    </div>
@endsection

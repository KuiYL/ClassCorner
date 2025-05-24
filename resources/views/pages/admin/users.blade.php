@extends('pages.admin.layout', ['activePage' => 'users'])

@section('content')
    <div class="admin-dashboard">
        <h2>Пользователи</h2>
        <div class="section">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Роль</th>
                        <th>Классы</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td data-label="ID">{{ $user->id }}</td>
                            <td data-label="Имя">{{ $user->name }} {{ $user->surname }}</td>
                            <td data-label="Email">{{ $user->email }}</td>
                            <td data-label="Роль">{{ ucfirst($user->role) }}</td>
                            <td data-label="Классы">
                                {{ optional($user->classes)->count() }}
                            </td>
                            <td data-label="Действия" class="actions">
                                <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="action-form">
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
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection

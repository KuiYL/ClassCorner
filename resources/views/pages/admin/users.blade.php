@extends('pages.platform.layout', ['activePage' => 'users', 'title' => 'Пользователи', 'quick_action' => 'null'])

@section('content')
    <div class="container-fluid py-6 px-md-4">
        <h2 class="text-4xl font-extrabold mb-8 text-[#6E76C1]">Управление пользователями</h2>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-[#6E76C1] text-white uppercase text-sm font-semibold">
                            <th class="px-6 py-3 text-left">ID</th>
                            <th class="px-6 py-3 text-left">Имя</th>
                            <th class="px-6 py-3 text-left">Email</th>
                            <th class="px-6 py-3 text-left">Роль</th>
                            <th class="px-6 py-3 text-left">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usersSites as $userSite)
                            <tr class="border-b last:border-b-0 hover:bg-gray-100">
                                <td class="px-6 py-4 text-gray-800">{{ $userSite->id }}</td>
                                <td class="px-6 py-4 text-gray-800">{{ $userSite->name }} {{ $userSite->surname }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $userSite->email }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ ucfirst($userSite->role) }}</td>
                                <td class="px-6 py-4">
                                    <button
                                        onclick="openModal({{ $userSite->id }}, '{{ $userSite->name }}', '{{ $userSite->email }}', '{{ $userSite->role }}', '{{ $userSite->surname }}')"
                                        class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded-lg text-sm transition">
                                        Изменить
                                    </button>
                                    <form action="{{ route('user.destroy', $userSite->id) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Вы уверены, что хотите удалить этого пользователя?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded-lg text-sm transition">
                                            Удалить
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-gray-500">Нет зарегистрированных
                                    пользователей.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="editModal" class="modal fixed inset-0 z-1070 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg">
            <h3 class="text-lg font-semibold text-[#6E76C1]" id="modalTitle">Редактировать пользователя</h3>
            <form id="editForm" method="POST" action="">
                @csrf
                <input type="hidden" id="userId" name="id">

                <div class="my-1">
                    <label for="userName" class="block text-gray-600 font-medium">Имя</label>
                    <input type="text" id="userName" name="name" class="w-full border rounded-lg p-2 mt-1">
                </div>

                <div class="my-1">
                    <label for="userSurname" class="block text-gray-600 font-medium">Фамилия</label>
                    <input type="text" id="userSurname" name="surname" class="w-full border rounded-lg p-2 mt-1">
                </div>

                <div class="my-1">
                    <label for="userEmail" class="block text-gray-600 font-medium">Email</label>
                    <input type="email" id="userEmail" name="email" class="w-full border rounded-lg p-2 mt-1">
                </div>

                <div class="my-1">
                    <label for="userRole" class="block text-gray-600 font-medium">Роль</label>
                    <select id="userRole" name="role" class="w-full border rounded-lg p-2 mt-1">
                        <option value="admin">Администратор</option>
                        <option value="teacher">Учитель</option>
                        <option value="student">Ученик</option>
                    </select>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg text-gray-700 mr-2">
                        Отмена
                    </button>
                    <button type="submit" class="px-4 py-2 bg-[#6E76C1] hover:bg-[#5a639e] text-white rounded-lg">
                        Сохранить
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(userId, userName, userEmail, userRole, userSurname) {
            document.getElementById('modalTitle').innerText = `Редактировать пользователя: ${userName}`;
            document.getElementById('userId').value = userId;
            document.getElementById('userName').value = userName;
            document.getElementById('userSurname').value = userSurname;
            document.getElementById('userEmail').value = userEmail;
            document.getElementById('userRole').value = userRole;

            const form = document.getElementById('editForm');
            form.action = `/admin/users/${userId}/update`;

            document.getElementById('editModal').classList.remove('hidden');
        }


        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
@endsection

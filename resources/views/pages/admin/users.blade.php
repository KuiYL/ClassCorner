@extends('pages.admin.layout', ['activePage' => 'users'])

@section('content')
    <div class="admin-dashboard max-w-7xl mx-auto p-6">
        <h2 class="text-4xl font-extrabold mb-8 text-[#6E76C1]">Пользователи</h2>

        <div class="section bg-white rounded-xl shadow-lg p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-[#6E76C1] text-white uppercase text-sm font-semibold">
                            <th class="px-6 py-3 text-left">ID</th>
                            <th class="px-6 py-3 text-left">Имя</th>
                            <th class="px-6 py-3 text-left">Email</th>
                            <th class="px-6 py-3 text-left">Роль</th>
                            <th class="px-6 py-3 text-left">Классы</th>
                            <th class="px-6 py-3 text-left">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="border-b last:border-b-0 hover:bg-[#EFF2FF] transition-colors duration-200">
                                <td class="px-6 py-4 text-[#4A4F8C] font-semibold" data-label="ID">{{ $user->id }}</td>
                                <td class="px-6 py-4 text-[#2E3250]" data-label="Имя">{{ $user->name }}
                                    {{ $user->surname }}</td>
                                <td class="px-6 py-4 text-[#4A4F8C]" data-label="Email">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-[#6E76C1] font-medium" data-label="Роль">
                                    {{ ucfirst($user->role) }}</td>
                                <td class="px-6 py-4 text-[#4A4F8C]" data-label="Классы">
                                    {{ optional($user->classes)->count() }}</td>
                                <td class="px-6 py-4" data-label="Действия">
                                    <button
                                        onclick="openModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}')"
                                        class="inline-block mr-3 text-sm bg-[#6E76C1] hover:bg-[#5a639e] text-white font-semibold py-1.5 px-4 rounded-lg transition-colors duration-200">
                                        Изменить
                                    </button>
                                    <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Вы уверены, что хотите удалить этого пользователя?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-sm bg-red-600 hover:bg-red-700 text-white font-semibold py-1.5 px-4 rounded-lg transition-colors duration-200">
                                            Удалить
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination mt-6 flex justify-center">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg">
            <h3 class="text-lg font-semibold text-[#6E76C1]" id="modalTitle">Редактировать пользователя</h3>
            <form id="editForm">
                <div class="my-4">
                    <label for="userName" class="block text-gray-600 font-medium">Имя</label>
                    <input type="text" id="userName" class="w-full border rounded-lg p-2 mt-1"
                        placeholder="Введите имя">
                </div>
                <div class="my-4">
                    <label for="userEmail" class="block text-gray-600 font-medium">Email</label>
                    <input type="email" id="userEmail" class="w-full border rounded-lg p-2 mt-1"
                        placeholder="Введите email">
                </div>
                <div class="my-4">
                    <label for="userRole" class="block text-gray-600 font-medium">Роль</label>
                    <select id="userRole" class="w-full border rounded-lg p-2 mt-1">
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
        function openModal(userId, userName, userEmail, userRole) {
            document.getElementById('modalTitle').innerText = `Редактировать пользователя: ${userName}`;
            document.getElementById('userName').value = userName;
            document.getElementById('userEmail').value = userEmail;
            document.getElementById('userRole').value = userRole;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
@endsection

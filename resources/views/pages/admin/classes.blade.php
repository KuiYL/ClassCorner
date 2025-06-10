@extends('pages.admin.layout', ['activePage' => 'classes'])

@section('content')
    <div class="admin-dashboard max-w-7xl mx-auto p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <h2 class="text-4xl font-extrabold text-[#6E76C1] mb-4 md:mb-0">Классы</h2>
            <a href="{{ route('classes.create') }}"
                class="inline-block bg-[#6E76C1] hover:bg-[#5a639e] text-white font-semibold py-2 px-5 rounded-lg transition-colors duration-200">
                Создать новый
            </a>
        </div>

        <div class="section bg-white rounded-xl shadow-lg p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-[#6E76C1] text-white uppercase text-sm font-semibold">
                            <th class="px-6 py-3 text-left">ID</th>
                            <th class="px-6 py-3 text-left">Название</th>
                            <th class="px-6 py-3 text-left">Преподаватель</th>
                            <th class="px-6 py-3 text-left">Ученики</th>
                            <th class="px-6 py-3 text-left">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($classes as $class)
                            <tr class="border-b last:border-b-0 hover:bg-[#EFF2FF] transition-colors duration-200">
                                <td class="px-6 py-4 text-[#4A4F8C] font-semibold" data-label="ID">{{ $class->id }}</td>
                                <td class="px-6 py-4 text-[#2E3250]" data-label="Название">{{ $class->name }}</td>
                                <td class="px-6 py-4 text-[#4A4F8C]" data-label="Преподаватель">
                                    {{ optional($class->teacher)->name ?? 'Не назначен' }}
                                </td>
                                <td class="px-6 py-4 text-[#4A4F8C]" data-label="Ученики">{{ $class->students_count }}</td>
                                <td class="px-6 py-4" data-label="Действия">
                                    <a href="{{ route('classes.edit', $class->id) }}"
                                        class="inline-block mr-3 text-sm bg-[#6E76C1] hover:bg-[#5a639e] text-white font-semibold py-1.5 px-4 rounded-lg transition-colors duration-200">
                                        Изменить
                                    </a>
                                    <form action="{{ route('classes.destroy', $class->id) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Вы уверены, что хотите удалить этот класс?');">
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
                {{ $classes->links() }}
            </div>
        </div>
    </div>
@endsection

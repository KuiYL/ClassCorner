@extends('pages.platform.layout', ['activePage' => 'null', 'title' => 'Редактирование класса', 'quick_action' => 'null'])
@section('content')
    <div class="container-fluid py-6 px-md-4">
        <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
            <div class="p-6 bg-gradient-to-r from-[#6E76C1] to-[#9CA4F2] text-white rounded-t-lg">
                <h2 class="text-2xl font-bold flex items-center">
                    <i class="fas fa-edit mr-3 text-xl"></i>
                    Редактировать данные класса
                </h2>
                <p class="mt-1 text-sm opacity-90">Измените информацию о классе ниже</p>
            </div>
            <form action="{{ route('classes.update', $class->id) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Название класса <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $class->name) }}"
                        placeholder="Например: Математика, 10А"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#6E76C1] focus:border-transparent transition duration-200 {{ $errors->has('name') ? 'border-red-500 ring-1 ring-red-500' : '' }}">
                    @error('name')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Описание (необязательно)
                    </label>
                    <textarea id="description" name="description" rows="4" placeholder="Введите описание класса..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#6E76C1] focus:border-transparent transition duration-200 {{ $errors->has('description') ? 'border-red-500 ring-1 ring-red-500' : '' }}">{{ old('description', $class->description) }}</textarea>
                    @error('description')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <input type="hidden" name="return_url" value="{{ request('return_url', route('user.classes')) }}">

                <div class="flex justify-end pt-4 border-t border-gray-200 mt-6">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-[#6E76C1] hover:bg-[#616EBD] text-white font-medium rounded-lg shadow-sm transition duration-200">
                        <i class="fas fa-save mr-2"></i> Сохранить изменения
                    </button>
                </div>
            </form>
        </div>

        <div class="text-center text-sm text-gray-500 mt-4">
            После сохранения вы вернётесь на предыдущую страницу
        </div>
    </div>
@endsection

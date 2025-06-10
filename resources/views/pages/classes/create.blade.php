@extends('pages.platform.layout', ['activePage' => 'null', 'title' => 'Создание класса', 'quick_action' => 'null'])
@section('content')
    <div class="container-fluid py-6 px-md-4">
        <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
            <div class="p-6 bg-gradient-to-r from-[#6E76C1] to-[#9CA4F2] text-white rounded-t-lg">
                <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                    <svg viewBox="0 0 1200 100" class="w-full h-full fill-current text-white">
                        <path d="M0,90 C300,100 600,40 900,90 L1200,100 L1200,0 L0,0 Z"></path>
                    </svg>
                </div>
                <div class="relative z-10">
                    <h2 class="text-2xl font-bold flex items-center group">
                        <i
                            class="fas fa-chalkboard-teacher mr-3 transform transition-transform duration-300 group-hover:rotate-3 group-hover:scale-110"></i>
                        Создание нового класса
                    </h2>
                    <p class="mt-1 text-sm opacity-90">Заполните поля ниже, чтобы создать новый учебный класс</p>
                </div>
            </div>
            <form action="{{ route('classes.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Название класса <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        placeholder="Например: Математика, 10А"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#6E76C1] focus:border-transparent transition duration-200 {{ $errors->has('name') ? 'border-red-500 ring-1 ring-red-500' : '' }}">
                    @error('name')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Описание
                        (необязательно)</label>
                    <textarea id="description" name="description" rows="4" placeholder="Введите описание класса..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#6E76C1] focus:border-transparent transition duration-200 {{ $errors->has('description') ? 'border-red-500 ring-1 ring-red-500' : '' }}">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Преподаватель</label>
                    <div class="px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-user-circle text-[#6E76C1]"></i>
                            <span>{{ auth()->user()->name }} {{ auth()->user()->surname }}</span>
                        </div>
                    </div>
                    <input type="hidden" name="teacher_id" value="{{ auth()->user()->id }}">
                    @error('teacher_id')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <input type="hidden" name="return_url" value="{{ request('return_url', route('user.classes')) }}">

                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-[#6E76C1] hover:bg-[#616EBD] text-white font-medium rounded-lg shadow-sm transition duration-200">
                        <i class="fas fa-plus mr-2"></i> Создать класс
                    </button>
                </div>
            </form>
        </div>

        <div class="text-center text-sm text-gray-500 mt-4">
            После создания вы сможете добавлять Учеников и задания
        </div>
    </div>
@endsection

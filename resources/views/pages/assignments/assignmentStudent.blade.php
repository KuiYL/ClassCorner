@extends('pages.platform.layout', ['activePage' => 'null', 'title' => $assignment->title, 'quick_action' => 'null'])
@section('content')
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">

        <a href="{{ route('class.show', $assignment->class_id) }}"
            class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-md transition duration-200 mt-6 ml-6">
            <i class="fas fa-arrow-left mr-2"></i> Назад к списку
        </a>

        <div class="bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex-1 min-w-0">
                <h2
                    class="text-2xl font-bold text-gray-900 flex items-center transition-transform duration-300 hover:text-[#6E76C1]">
                    <i class="fas fa-book-open mr-2 text-[#6E76C1]"></i>
                    <span class="truncate max-w-xs sm:max-w-sm md:max-w-xl lg:max-w-2xl" title="{{ $assignment->title }}">
                        {{ $assignment->title }}
                    </span>
                </h2>
            </div>
            <span class="ml-4 text-sm text-gray-500 shrink-0">
                ID: {{ $assignment->id }}
            </span>
        </div>

        <div class="p-6 space-y-6">
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <label class="block text-sm font-medium text-gray-700 mb-2">Описание задания</label>
                <div id="description-text" class="text-gray-800 line-clamp-3 overflow-hidden whitespace-pre-line">
                    {{ $assignment->description ?: 'Нет описания' }}
                </div>
            </div>

            <div class="flex items-center gap-2 text-sm text-gray-600">
                <i class="fas fa-calendar-check text-[#6E76C1]"></i>
                <span class="font-medium">Срок выполнения:</span>
                <strong class="ml-1">
                    {{ \Carbon\Carbon::parse($assignment->due_date)->format('d.m.Y H:i') }}
                </strong>
            </div>

            @if (count($assignmentFields))
                <div class="space-y-4">
                    <h3 class="text-xl font-semibold text-gray-800">Поля задания</h3>
                    <form id="student-answer-form" action="{{ route('assignment.submit.answer', $assignment->id) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @foreach ($assignmentFields as $index => $field)
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                                <h4 class="font-medium text-gray-900 mb-3 truncate max-w-full">{{ $field['name'] }}</h4>

                                @if ($field['type'] === 'text')
                                    <textarea name="answers[{{ $index }}][value]" rows="4" placeholder="Введите ваш ответ здесь..." required
                                        class="form-control w-full"></textarea>
                                @elseif ($field['type'] === 'file_upload')
                                    <div class="file-upload-container">
                                        <input type="file" name="answers[{{ $index }}][file]"
                                            class="custom-file-input" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png"
                                            required>
                                        <label class="custom-file-label">
                                            <span class="custom-file-name">Выберите файл...</span>
                                        </label>
                                    </div>
                                @elseif (in_array($field['type'], ['multiple_choice', 'single_choice']))
                                    <ul class="space-y-2 mt-2">
                                        @foreach ($field['options'] as $optionIndex => $option)
                                            <li>
                                                <label class="flex items-center gap-2">
                                                    <input
                                                        type="{{ $field['type'] === 'single_choice' ? 'radio' : 'checkbox' }}"
                                                        name="answers[{{ $index }}][options][]"
                                                        value="{{ $optionIndex }}" class="form-checkbox">
                                                    <span>{{ $option['value'] }}</span>
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                                <input type="hidden" name="answers[{{ $index }}][type]"
                                    value="{{ $field['type'] }}">
                            </div>
                        @endforeach
                        <button type="submit"
                            class="inline-flex justify-center px-6 py-3 items-center bg-[#6E76C1] hover:bg-[#616EBD] text-white font-medium rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#6E76C1] transition duration-200">
                            <i class="fas fa-save mr-2"></i>Сохранить ответ
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.querySelectorAll('.custom-file-input').forEach(input => {
            input.addEventListener('change', function() {
                const fileName = this.files[0]?.name || 'Выберите файл...';
                const label = this.closest('.file-upload-container').querySelector('.custom-file-name');
                if (label) {
                    label.textContent = fileName;
                }
            });
        });
    </script>
@endsection

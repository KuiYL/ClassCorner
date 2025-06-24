@extends('pages.platform.layout', ['activePage' => 'null', 'title' => $assignment->title, 'quick_action' => 'null'])
@section('content')
    @php
        $dueDate = \Carbon\Carbon::parse($assignment->due_date);
        $now = \Carbon\Carbon::now();
        $isOverdue = $now->gt($dueDate);
    @endphp

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
                    {{ $dueDate->format('d.m.Y H:i') }}
                </strong>

                @if ($isOverdue)
                    <span class="ml-3 px-2 py-1 text-xs font-semibold rounded-full bg-red-600 text-white select-none"
                        title="Срок сдачи задания прошёл">
                        Просрочено — отправка невозможна
                    </span>
                @endif
            </div>

            @if ($assignment->materials->isNotEmpty())
                <div class="mt-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Материалы задания</h3>
                    <ul class="space-y-2">
                        @foreach ($assignment->materials as $material)
                            <li
                                class="flex items-center gap-2 p-3 bg-gray-50 border border-gray-200 rounded-md hover:bg-gray-100 transition duration-200">
                                <i class="fas fa-paperclip text-[#6E76C1]"></i>
                                <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank"
                                    class="text-[#6E76C1] hover:underline truncate flex-1">
                                    {{ $material->file_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (count($assignmentFields))
                <div class="space-y-4">
                    <h3 class="text-xl font-semibold text-gray-800">Поля задания</h3>

                    @if ($errors->any())
                        <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-300 text-red-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (!$isOverdue)
                        <form id="student-answer-form" action="{{ route('assignment.submit.answer', $assignment->id) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @foreach ($assignmentFields as $index => $field)
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                                    <h4 class="font-medium text-gray-900 mb-3 break-words max-w-full">
                                        {{ $field['name'] ?? 'Без названия' }}
                                    </h4>

                                    @if ($field['type'] === 'text')
                                        <textarea name="answers[{{ $index }}][value]" rows="4" placeholder="Введите ваш ответ здесь..."
                                            class="form-control w-full">{{ old("answers.$index.value") }}</textarea>
                                    @elseif ($field['type'] === 'file_upload')
                                        <div class="file-upload-container">
                                            <input type="file" name="answers[{{ $index }}][file]"
                                                class="custom-file-input"
                                                accept=".pdf,.jpeg,.jpg,.png,.doc,.docx,.xls,.xlsx,.csv,.ppt,.pptx,.txt,.zip,.rar">
                                            <div class="text-sm text-gray-500 mt-1">
                                                Поддерживаются файлы: pdf, jpeg, jpg, png, doc, docx, xls, xlsx, csv, ppt,
                                                pptx,
                                                txt, zip, rar
                                            </div>
                                        </div>
                                    @elseif (in_array($field['type'], ['multiple_choice', 'single_choice']))
                                        <ul class="space-y-2 mt-2">
                                            @foreach ($field['options'] as $optionIndex => $option)
                                                <li>
                                                    <label
                                                        class="flex items-center gap-3 p-2 rounded-md hover:bg-gray-100 group cursor-pointer w-full">
                                                        <input
                                                            type="{{ $field['type'] === 'single_choice' ? 'radio' : 'checkbox' }}"
                                                            name="answers[{{ $index }}][options][]"
                                                            value="{{ $optionIndex }}"
                                                            class="shrink-0 w-5 h-5 mt-0.5 text-[#6E76C1] focus:ring-[#6E76C1] border-gray-300 rounded-full"
                                                            @if (is_array(old("answers.$index.options")) && in_array($optionIndex, old("answers.$index.options"))) checked @endif>

                                                        <span
                                                            class="text-gray-800 break-words whitespace-normal max-w-full">
                                                            {{ $option['value'] }}
                                                        </span>
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
                    @else
                        <div class="text-center p-6 bg-red-50 border border-red-300 rounded-md text-red-700 font-semibold">
                            Срок сдачи задания прошёл. Отправка ответа невозможна.
                        </div>
                    @endif

                </div>
            @endif
        </div>
    </div>
@endsection

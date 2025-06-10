@extends('pages.platform.layout', ['activePage' => 'null', 'title' => 'Редактирование задания', 'quick_action' => 'null'])
@section('content')
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
        <div class="bg-white px-6 py-4 border-b border-gray-200">
            <h2
                class="text-2xl font-bold text-gray-900 flex items-center transition-transform duration-300 hover:text-[#6E76C1]">
                <i class="fas fa-book-open mr-2 text-[#6E76C1]"></i>
                Редактировать задание
            </h2>
        </div>

        <form id="assignment-form" action="{{ route('assignments.update', $assignment->id) }}" method="POST"
            class="p-6 space-y-6" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Название задания
                    <span>*</span></label>
                <div class="relative">
                    <input type="text" id="title" name="title" value="{{ old('title', $assignment->title) }}"
                        class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#6E76C1] focus:border-transparent transition duration-200 {{ $errors->has('title') ? 'input-error error' : '' }}"
                        placeholder="Введите название задания">
                </div>
                @error('title')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">Выберите класс
                        <span>*</span></label>
                    <div class="relative">
                        <select id="class_id" name="class_id"
                            class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#6E76C1] focus:border-transparent transition duration-200 {{ $errors->has('class_id') ? 'input-error error' : '' }}">
                            <option value="">-- Выберите класс --</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}"
                                    {{ old('class_id', $assignment->class_id) == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('class_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Дата сдачи
                        <span>*</span></label>
                    <div class="relative">
                        <input type="date" id="due_date" name="due_date"
                            value="{{ old('due_date', $assignment->due_date ? \Carbon\Carbon::parse($assignment->due_date)->format('Y-m-d') : '') }}"
                            class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#6E76C1] focus:border-transparent transition duration-200 {{ $errors->has('due_date') ? 'input-error error' : '' }}">
                    </div>
                    @error('due_date')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="due_time" class="block text-sm font-medium text-gray-700 mb-1">Время сдачи
                        <span>*</span></label>
                    <div class="relative">
                        <input type="time" id="due_time" name="due_time"
                            value="{{ old('due_time', $assignment->due_date ? \Carbon\Carbon::parse($assignment->due_date)->format('H:i') : '23:59') }}"
                            class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#6E76C1] focus:border-transparent transition duration-200 {{ $errors->has('due_time') ? 'input-error error' : '' }}">
                    </div>
                    @error('due_time')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Описание задания</label>
                <textarea id="description" name="description" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#6E76C1] focus:border-transparent transition duration-200 {{ $errors->has('description') ? 'input-error error' : '' }}"
                    placeholder="Введите описание задания">{{ old('description', $assignment->description) }}</textarea>
                @error('description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="materials-section">
                <label for="materials" class="block text-sm font-medium text-gray-700 mb-1">
                    Добавить материалы (необязательно)
                </label>
                <input type="file" id="materials" name="materials[]" multiple
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#6E76C1] focus:border-transparent transition duration-200">
                <small class="text-gray-500">Вы можете загрузить несколько файлов</small>
                @error('materials')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            @if ($assignment->materials->isNotEmpty())
                <div class="mt-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Текущие материалы</h4>
                    <ul class="space-y-2">
                        @foreach ($assignment->materials as $material)
                            <li class="flex items-center gap-2 p-3 bg-gray-50 border border-gray-200 rounded-md group">
                                <i class="fas fa-paperclip text-[#6E76C1] mr-2"></i>
                                <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank"
                                    class="text-[#6E76C1] hover:underline truncate flex-1">
                                    {{ $material->file_name }}
                                </a>
                                <button type="button"
                                    class="ml-2 text-red-600 hover:text-red-800 material-delete-btn delete-button"
                                    data-id="{{ $material->id }}" data-name="{{ $material->file_name }}"
                                    data-type="assignmentMaterial">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3 transition-colors duration-300 hover:text-[#6E76C1]">
                    Конструктор задания
                </h3>
                <div id="fields-container" class="space-y-4 mb-4"></div>
                <button type="button" id="add-field-btn"
                    class="inline-flex items-center px-4 py-2 bg-[#6E76C1] hover:bg-[#616EBD] text-white text-sm font-medium rounded-md shadow transition duration-200">
                    <i class="fas fa-plus mr-2"></i> Добавить поле
                </button>
            </div>

            <input type="hidden" name="fields_json" id="fields-json" value="{{ old('fields_json', '[]') }}">

            <input type="hidden" name="return_url" value="{{ request('return_url', route('user.assignments')) }}">

            <div class="pt-4 flex justify-between">
                <a href="{{ url()->previous() }}"
                    class="inline-flex justify-center px-6 py-3 items-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i> Назад
                </a>
                <button type="submit"
                    class="inline-flex justify-center px-6 py-3 items-center bg-[#6E76C1] hover:bg-[#616EBD] text-white font-medium rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#6E76C1] transition duration-200">
                    <i class="fas fa-save mr-2"></i> Сохранить задание
                </button>
            </div>
        </form>
    </div>

    <div id="validation-toast"
        class="fixed bottom-10 right-16 bg-gradient-to-r from-red-500 to-pink-600 text-white text-base font-medium py-3 px-5 rounded-lg shadow-lg flex items-center space-x-2 z-50 opacity-0 hidden transition-all duration-300 ease-in-out transform translate-y-4">
        <i class="fas fa-exclamation-circle animate-bounce"></i>
        <span>Пожалуйста, исправьте ошибки в форме.</span>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fieldsContainer = document.getElementById("fields-container");
            const addFieldBtn = document.getElementById("add-field-btn");
            const dueDateInput = document.getElementById("due_date");
            const toast = document.getElementById("validation-toast");

            dueDateInput.setAttribute("min", new Date().toISOString().split("T")[0]);
            let fieldIndex = 0;

            function updateOptionInputs(container, type) {
                container.querySelectorAll(".option").forEach((option, index) => {
                    const radioOrCheckbox = option.querySelector(".correct-option");
                    const namePrefix = `field-${container.closest(".field").dataset.index}`;
                    const isRadio = type === "single_choice";
                    radioOrCheckbox.type = isRadio ? "radio" : "checkbox";
                    radioOrCheckbox.name = isRadio ? `${namePrefix}-correct-answer` :
                        `${namePrefix}-correct-answer-${index}`;
                });
            }

            function createFieldElement(fieldData = null) {
                const fieldDiv = document.createElement("div");
                fieldDiv.className =
                    "field bg-gray-50 p-4 rounded-lg border border-gray-200 relative group space-y-4";
                fieldDiv.dataset.index = fieldIndex++;

                fieldDiv.innerHTML = `
                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Название вопроса:</label>
                    <input type="text" class="field-name w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#6E76C1] focus:border-transparent transition duration-200" placeholder="Введите вопрос">
                </div>
                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Тип вопроса:</label>
                    <select class="field-type w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#6E76C1] focus:border-transparent transition duration-200">
                        <option value="text">Текстовый ответ</option>
                        <option value="file_upload">Загрузка файла</option>
                        <option value="multiple_choice">Множественный выбор</option>
                        <option value="single_choice">Одиночный выбор</option>
                    </select>
                </div>
                <div class="options-container hidden space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-medium text-gray-700">Варианты:</label>
                        <button type="button"
                            class="btn secondary add-option-btn inline-flex items-center px-3 py-1 bg-[#6E76C1] hover:bg-[#616EBD] text-white text-sm font-medium rounded shadow transition duration-200">
                            <i class="fas fa-plus mr-1"></i> Добавить вариант
                        </button>
                    </div>
                    <div class="options-list space-y-2"></div>
                </div>
                <button type="button"
                    class="remove-field-btn btn danger inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded shadow transition duration-200">
                    <i class="fas fa-trash mr-1"></i> Удалить поле
                </button>
            `;

                const fieldTypeSelect = fieldDiv.querySelector(".field-type");
                const optionsContainer = fieldDiv.querySelector(".options-container");
                const optionsList = fieldDiv.querySelector(".options-list");

                // --- Событие изменения типа ---
                fieldTypeSelect.addEventListener("change", function() {
                    const selectedType = this.value;
                    const isSingleChoice = selectedType === "single_choice";
                    const isMultipleChoice = selectedType === "multiple_choice";

                    optionsList.innerHTML = ""; // Очистка старых вариантов

                    if (isSingleChoice || isMultipleChoice) {
                        optionsContainer.classList.remove("hidden");

                        if (!fieldData || !fieldData.options || fieldData.options.length === 0) {
                            const defaultOption = document.createElement("div");
                            defaultOption.className = "flex items-center gap-2";
                            defaultOption.innerHTML = `
                            <input type="${isSingleChoice ? 'radio' : 'checkbox'}" class="correct-option h-4 w-4 text-[#6E76C1] focus:ring-[#6E76C1] border-gray-300">
                            <input type="text" class="option-value w-full px-3 py-1 border border-gray-300 rounded focus:ring-2 focus:ring-[#6E76C1] focus:border-transparent" placeholder="Введите вариант">
                            <button type="button" class="remove-option-btn inline-flex items-center justify-center h-8 w-8 rounded-full bg-red-100 text-red-600 hover:bg-red-200">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        `;
                            defaultOption.querySelector(".remove-option-btn").addEventListener("click",
                                () => defaultOption.remove());
                            optionsList.appendChild(defaultOption);
                        } else {
                            fieldData.options.forEach(opt => {
                                const optionDiv = document.createElement("div");
                                optionDiv.className = "flex items-center gap-2";
                                optionDiv.innerHTML = `
                                <input type="${isSingleChoice ? 'radio' : 'checkbox'}" class="correct-option h-4 w-4 text-[#6E76C1] focus:ring-[#6E76C1] border-gray-300" ${opt.isCorrect ? 'checked' : ''}>
                                <input type="text" class="option-value w-full px-3 py-1 border border-gray-300 rounded focus:ring-2 focus:ring-[#6E76C1] focus:border-transparent" value="${opt.value || ''}" placeholder="Введите вариант">
                                <button type="button" class="remove-option-btn inline-flex items-center justify-center h-8 w-8 rounded-full bg-red-100 text-red-600 hover:bg-red-200">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            `;

                                optionDiv.querySelector(".remove-option-btn").addEventListener(
                                    "click", () => optionDiv.remove());

                                optionDiv.querySelector(".correct-option").addEventListener("click",
                                    function(e) {
                                        if (isSingleChoice) {
                                            [...optionsList.querySelectorAll(".correct-option")]
                                            .forEach(cb => cb.checked = false);
                                            e.target.checked = true;
                                        }
                                    });

                                optionsList.appendChild(optionDiv);
                            });
                        }

                        updateOptionInputs(optionsList, fieldTypeSelect.value);
                    } else {
                        optionsContainer.classList.add("hidden");
                    }
                });

                // --- Добавление нового варианта ---
                fieldDiv.querySelector(".add-option-btn").addEventListener("click", function() {
                    const optionDiv = document.createElement("div");
                    optionDiv.className = "flex items-center gap-2";
                    const isSingleChoice = fieldTypeSelect.value === "single_choice";

                    optionDiv.innerHTML = `
                    <input type="${isSingleChoice ? 'radio' : 'checkbox'}" class="correct-option h-4 w-4 text-[#6E76C1] focus:ring-[#6E76C1] border-gray-300">
                    <input type="text" class="option-value w-full px-3 py-1 border border-gray-300 rounded focus:ring-2 focus:ring-[#6E76C1] focus:border-transparent" placeholder="Введите вариант">
                    <button type="button" class="remove-option-btn inline-flex items-center justify-center h-8 w-8 rounded-full bg-red-100 text-red-600 hover:bg-red-200">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                `;

                    optionDiv.querySelector(".remove-option-btn").addEventListener("click", () => optionDiv
                        .remove());

                    optionDiv.querySelector(".correct-option").addEventListener("click", function(e) {
                        if (fieldTypeSelect.value === "single_choice") {
                            [...optionsList.querySelectorAll(".correct-option")].forEach(cb => cb
                                .checked = false);
                            e.target.checked = true;
                        }
                    });

                    optionsList.appendChild(optionDiv);
                    updateOptionInputs(optionsList, fieldTypeSelect.value);
                });

                fieldDiv.querySelector(".remove-field-btn").addEventListener("click", function() {
                    fieldDiv.remove();
                });

                // --- Заполняем поля, если есть fieldData ---
                if (fieldData) {
                    fieldDiv.querySelector(".field-name").value = fieldData.name || '';
                    fieldDiv.querySelector(".field-type").value = fieldData.type || 'text';
                    fieldTypeSelect.dispatchEvent(new Event('change'));
                }

                return fieldDiv;
            }

            // --- 🔄 Восстановление данных ---
            const savedFieldsJson = @json(old('fields_json'));

            if (savedFieldsJson) {
                try {
                    const savedFields = JSON.parse(savedFieldsJson);
                    savedFields.forEach(field => {
                        const fieldDiv = createFieldElement(field);
                        fieldsContainer.appendChild(fieldDiv);
                    });
                } catch (e) {
                    console.error("Ошибка восстановления данных:", e);
                }
            } else if (@json($fields).length > 0) {
                @json($fields).forEach(field => {
                    const fieldDiv = createFieldElement({
                        name: field.name,
                        type: field.type,
                        options: field.options.map(opt => ({
                            value: opt.value,
                            isCorrect: opt.isCorrect
                        }))
                    });
                    fieldsContainer.appendChild(fieldDiv);
                });
            }

            // --- ➕ Добавление новых полей ---
            addFieldBtn.addEventListener("click", function() {
                const newField = createFieldElement(null);
                fieldsContainer.appendChild(newField);
            });

            // --- ✅ Отправка формы ---
            document.getElementById("assignment-form").addEventListener("submit", function(e) {
                e.preventDefault();
                let isValid = true;

                document.querySelectorAll(".field-name, .option-value").forEach(el => el.classList.remove(
                    "input-error"));

                const filledFields = [];

                document.querySelectorAll(".field").forEach(fieldDiv => {
                    const nameInput = fieldDiv.querySelector(".field-name");
                    const name = nameInput.value.trim();
                    const type = fieldDiv.querySelector(".field-type").value;
                    const optionsList = fieldDiv.querySelector(".options-list");

                    if (!name) {
                        showValidationError("Заполните название вопроса.");
                        nameInput.classList.add("input-error");
                        isValid = false;
                    }

                    const options = [...optionsList.querySelectorAll(".option-value")].map(
                        input => {
                            const value = input.value.trim();
                            if (!value) {
                                input.classList.add("input-error");
                                showValidationError("Все варианты должны быть заполнены.");
                                isValid = false;
                            }
                            return {
                                value,
                                isCorrect: input.closest(".flex").querySelector(
                                    ".correct-option")?.checked || false
                            };
                        });

                    filledFields.push({
                        name,
                        type,
                        options
                    });
                });

                if (!isValid) return;

                document.getElementById("fields-json").value = JSON.stringify(filledFields);
                e.target.submit();
            });

            // --- 💬 Тост с ошибками ---
            function showValidationError(message) {
                toast.textContent = message;
                toast.classList.remove("hidden", "opacity-0");
                toast.classList.add("toast-enter");

                setTimeout(() => {
                    toast.classList.remove("toast-enter");
                    toast.classList.add("toast-enter-active");
                }, 10);

                setTimeout(() => {
                    toast.classList.remove("toast-enter-active");
                    toast.classList.add("toast-leave");

                    setTimeout(() => {
                        toast.classList.remove("toast-leave", "hidden", "opacity-100");
                        toast.classList.add("hidden");
                    }, 300);
                }, 3000);
            }
        });
    </script>
@endsection

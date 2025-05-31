@extends('pages.platform.layout', ['activePage' => 'tasks', 'title' => 'Обновление задания', 'quick_action' => 'null'])
@section('content')
    <div class="main-platform">
        <div class="assignment-form">
            <form id="assignment-form" action="{{ route('assignments.update', $assignment->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-layout">
                    <div class="form-grid">
                        <h2><span class="attention-title">Редактировать</span> задание</h2>
                        <div class="form-group full">
                            <label for="title">Название задания:</label>
                            <input type="text" id="title" name="title"
                                value="{{ old('title', $assignment->title) }}"
                                class="{{ $errors->has('title') ? 'input-error error' : '' }}">
                            @error('title')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="class_id">Выберите класс:</label>
                                <select id="class_id" name="class_id"
                                    class="{{ $errors->has('class_id') ? 'input-error error' : '' }}">
                                    <option value="">-- Выберите класс --</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}"
                                            {{ old('class_id', $assignment->class_id) == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="due_date">Дата сдачи:</label>
                                <input type="date" id="due_date" name="due_date"
                                    value="{{ old('due_date', $assignment->due_date) }}"
                                    class="{{ $errors->has('due_date') ? 'input-error error' : '' }}">
                                @error('due_date')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group full">
                            <label for="description">Описание задания:</label>
                            <textarea id="description" name="description" rows="5"
                                class="{{ $errors->has('description') ? 'input-error error' : '' }}">{{ old('description', $assignment->description) }}</textarea>
                            @error('description')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-fields-section">
                        <h3>Конструктор задания</h3>
                        <div class="fields-container" id="fields-container"></div>
                        <button type="button" id="add-field-btn" class="btn primary add-field-btn">
                            <i class="fas fa-plus"></i> Добавить поле
                        </button>
                    </div>
                </div>

                <input type="hidden" name="fields_json" id="fields-json">
                <input type="hidden" name="return_url" value="{{ request('return_url', route('user.assignments')) }}">
                <button type="submit" class="btn primary large full-width mt-2">
                    Сохранить задание
                </button>
            </form>
        </div>
    </div>
    <div id="validation-toast" class="validation-toast"></div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fieldsContainer = document.getElementById("fields-container");
            const addFieldBtn = document.getElementById("add-field-btn");
            const dueDateInput = document.getElementById("due_date");
            const toast = document.getElementById("validation-toast");

            dueDateInput.setAttribute("min", new Date().toISOString().split("T")[0]);

            let fieldIndex = 0;

            function updateOptionInputs(container, type) {
                container.querySelectorAll(".option").forEach(option => {
                    const radioOrCheckbox = option.querySelector(".correct-option");
                    const isRadio = type === "single_choice";
                    radioOrCheckbox.type = isRadio ? "radio" : "checkbox";
                    radioOrCheckbox.name = isRadio ? `option-${fieldIndex}` : "";
                });
            }

            function createFieldElement(fieldData = null) {
                const fieldDiv = document.createElement("div");
                fieldDiv.className = "field";
                fieldDiv.dataset.index = fieldIndex++;

                fieldDiv.innerHTML = `
                    <div class="form-group">
                        <label>Название вопроса:</label>
                        <input type="text" class="field-name" placeholder="Введите вопрос">
                    </div>
                    <div class="form-group">
                        <label>Тип вопроса:</label>
                        <select class="field-type">
                            <option value="text">Текстовый ответ</option>
                            <option value="file_upload">Загрузка файла</option>
                            <option value="multiple_choice">Множественный выбор</option>
                            <option value="single_choice">Одиночный выбор</option>
                        </select>
                    </div>
                    <div class="options-container hidden">
                        <label>Варианты:</label>
                        <div class="options-list"></div>
                        <button type="button" class="btn secondary add-option-btn">
                            <i class="fas fa-plus"></i> Добавить вариант
                        </button>
                    </div>
                    <button type="button" class="remove-field-btn btn danger">Удалить поле</button>
                `;

                const fieldTypeSelect = fieldDiv.querySelector(".field-type");
                const optionsContainer = fieldDiv.querySelector(".options-container");
                const optionsList = fieldDiv.querySelector(".options-list");

                if (fieldData) {
                    fieldDiv.querySelector(".field-name").value = fieldData.name || "";
                    fieldTypeSelect.value = fieldData.type || "text";

                    if (["single_choice", "multiple_choice"].includes(fieldData.type)) {
                        optionsContainer.classList.remove("hidden");
                        if (fieldData.options && fieldData.options.length > 0) {
                            fieldData.options.forEach(opt => {
                                const optionDiv = document.createElement("div");
                                optionDiv.className = "option";
                                const isSingleChoice = fieldData.type === "single_choice";

                                optionDiv.innerHTML = `
                                    <input type="${isSingleChoice ? 'radio' : 'checkbox'}" class="correct-option" name="option-${fieldIndex}" ${opt.isCorrect ? 'checked' : ''}>
                                    <input type="text" class="option-value" value="${opt.value || ""}" placeholder="Вариант ответа">
                                    <button type="button" class="remove-option-btn"><i class="fas fa-trash"></i></button>
                                `;

                                optionDiv.querySelector(".remove-option-btn").addEventListener("click",
                                    () => {
                                        optionDiv.remove();
                                    });

                                optionsList.appendChild(optionDiv);
                            });
                        }
                    }
                }

                fieldTypeSelect.addEventListener("change", function() {
                    const selectedType = this.value;
                    if (selectedType === "single_choice" || selectedType === "multiple_choice") {
                        optionsContainer.classList.remove("hidden");
                        updateOptionInputs(optionsList, selectedType);
                    } else {
                        optionsContainer.classList.add("hidden");
                    }
                });

                fieldDiv.querySelector(".add-option-btn").addEventListener("click", function() {
                    const isSingleChoice = fieldTypeSelect.value === "single_choice";
                    const optionDiv = document.createElement("div");
                    optionDiv.className = "option";

                    optionDiv.innerHTML = `
                        <input type="${isSingleChoice ? 'radio' : 'checkbox'}" class="correct-option" name="option-${fieldIndex}">
                        <input type="text" class="option-value" placeholder="Вариант ответа">
                        <button type="button" class="remove-option-btn"><i class="fas fa-trash"></i></button>
                    `;

                    optionDiv.querySelector(".remove-option-btn").addEventListener("click", () => {
                        optionDiv.remove();
                    });

                    optionDiv.querySelector(".correct-option").addEventListener("click", function(e) {
                        if (fieldTypeSelect.value === "single_choice") {
                            [...optionsList.querySelectorAll(".correct-option")].forEach(cb => cb
                                .checked = false);
                            e.target.checked = true;
                        }
                    });

                    optionsList.appendChild(optionDiv);
                    updateOptionInputs(optionsList, fieldTypeSelect.value);

                    optionDiv.querySelector(".option-value").addEventListener("input", function(e) {
                        e.target.classList.toggle("error", !e.target.value.trim());
                    });
                });

                fieldDiv.querySelector(".remove-field-btn").addEventListener("click", function() {
                    fieldDiv.remove();
                });

                return fieldDiv;
            }

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
                    const fieldDiv = createFieldElement(field);
                    fieldsContainer.appendChild(fieldDiv);
                });
            }

            addFieldBtn.addEventListener("click", function() {
                const newField = createFieldElement();
                fieldsContainer.appendChild(newField);
            });

            document.getElementById("assignment-form").addEventListener("submit", function(e) {
                e.preventDefault();

                let isValid = true;
                document.querySelectorAll(".form-group .error").forEach(el => el.classList.remove("error"));

                const filledFields = [];

                document.querySelectorAll(".field").forEach(fieldDiv => {
                    const nameInput = fieldDiv.querySelector(".field-name");
                    const name = nameInput.value.trim();
                    const type = fieldDiv.querySelector(".field-type").value;
                    const optionsList = fieldDiv.querySelector(".options-list");

                    if (!name) {
                        nameInput.classList.add("error");
                        showValidationError("Заполните название вопроса.");
                        isValid = false;
                    }

                    const options = [...optionsList.querySelectorAll(".option")].map(optionDiv => {
                        const input = optionDiv.querySelector(".option-value");
                        const isChecked = optionDiv.querySelector(".correct-option")
                            ?.checked || false;
                        const value = input.value.trim();

                        if (!value) {
                            input.classList.add("error");
                            showValidationError("Все варианты должны содержать текст.");
                            isValid = false;
                        }

                        return {
                            value,
                            isCorrect: isChecked
                        };
                    });

                    if ((type === "single_choice" || type === "multiple_choice") && options.length >
                        0) {
                        const hasCorrectAnswer = options.some(opt => opt.isCorrect);
                        if (!hasCorrectAnswer) {
                            showValidationError(`Выберите правильный вариант для "${name}"`);
                            isValid = false;
                        }
                    }

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

            function showValidationError(message) {
                toast.textContent = message;
                toast.classList.add("show");
                setTimeout(() => {
                    toast.classList.remove("show");
                }, 3000);
            }
        });
    </script>
@endsection

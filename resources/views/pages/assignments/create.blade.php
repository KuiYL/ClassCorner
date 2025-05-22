<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавление задание для класса</title>
    <link rel="stylesheet" href="{{ asset('css/style-platform.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/script.js') }}" defer></script>
</head>
<style>
    /* === Форма создания задания === */
    .assignment-form {
        max-width: 900px;
        margin: 2rem auto;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
        padding: 2rem;
    }

    .assignment-form h2 {
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
        color: #333;
    }

    .assignment-form h3 {
        font-size: 1.4rem;
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: #555EB1;
    }

    /* === Сетка форм === */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        align-items: start;
    }

    .form-group.full {
        grid-column: span 2;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #555;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        border-color: #555EB1;
        outline: none;
    }

    .form-divider {
        margin: 2rem 0;
        border: none;
        height: 1px;
        background: #eee;
    }

    /* === Конструктор полей === */
    .fields-container {
        margin-top: 1rem;
    }

    .field {
        background-color: #f9f9f9;
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        position: relative;
        transition: transform 0.2s ease;
    }

    .field:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .field h4 {
        margin: 0 0 1rem 0;
        font-size: 1rem;
        color: #333;
    }

    /* === Заголовок поля === */
    .field .form-group:first-child {
        margin-bottom: 1rem;
    }

    /* === Тип поля === */
    .field .form-group:nth-child(2) {
        margin-bottom: 1rem;
    }

    /* === Блок вариантов === */
    .options-container {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #eee;
    }

    .option {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .option input[type="text"] {
        flex: 1;
        padding: 0.5rem 0.75rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 1rem;
    }

    .option button.remove-option-btn {
        background-color: #e74c3c;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 0.4rem 0.6rem;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .option button.remove-option-btn:hover {
        background-color: #c0392b;
    }

    .add-field-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.75rem;
        font-size: 1rem;
        border-radius: 8px;
        background-color: #6E76C1;
        color: white;
        border: none;
        transition: background-color 0.3s ease;
        cursor: pointer;
    }

    .add-field-btn:hover {
        background-color: #555EB1;
    }

    .remove-field-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #e74c3c;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 0.4rem 0.6rem;
        cursor: pointer;
        font-size: 0.9rem;
        transition: background-color 0.2s ease;
    }

    .remove-field-btn:hover {
        background-color: #c0392b;
    }

    /* === Кнопка сохранения === */
    .btn.primary.large.full-width {
        width: 100%;
        font-size: 1.1rem;
        padding: 1rem;
        margin-top: 2rem;
    }

    .options-container {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #eee;
    }

    .options-container.hidden {
        display: none;
    }

    .option {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .option input[type="text"] {
        flex: 1;
        padding: 0.5rem 0.75rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 1rem;
    }

    .option .remove-option-btn {
        background-color: #e74c3c;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 0.4rem 0.6rem;
        cursor: pointer;
    }

    .option .remove-option-btn:hover {
        background-color: #c0392b;
    }

    /* === Кнопка "Добавить вариант" === */
    .add-option-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background-color: #6E76C1;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        font-size: 0.95rem;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .add-option-btn:hover {
        background-color: #555EB1;
    }

    /* === Вариант ответа (поле ввода) === */
    .option input[type="text"] {
        flex: 1;
        padding: 0.6rem 1rem;
        font-size: 1rem;
        border: 1px solid #ccc;
        border-radius: 6px;
        min-width: 200px;
    }

    /* === Радио/чекбоксы === */
    .option input[type="radio"],
    .option input[type="checkbox"] {
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        width: auto;
    }

    /* === Кнопка удаления варианта === */
    .remove-option-btn {
        background-color: #e74c3c;
        color: white;
        border: none;
        padding: 0.4rem 0.6rem;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: background-color 0.2s ease;
    }

    .remove-option-btn:hover {
        background-color: #c0392b;
    }
</style>

<body>
    @include('layout.sidebar', ['activePage' => 'tasks'])
    <div class="topbar">
        @include('layout.topbar')
        <main>
            <div class="main-platform">
                <form id="assignment-form" action="{{ route('assignment.store') }}" method="POST"
                    class="assignment-form">
                    @csrf

                    <h2>Создать новое задание</h2>

                    <!-- Основные поля -->
                    <div class="form-grid">
                        <div class="form-group full">
                            <label for="title">Название задания:</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}">
                            @error('title')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="class_id">Выберите класс:</label>
                                <select id="class_id" name="class_id">
                                    <option value="">-- Выберите класс --</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}"
                                            {{ old('class_id') == $class->id ? 'selected' : '' }}>
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
                                <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}">
                                @error('due_date')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group full">
                            <label for="description">Описание задания:</label>
                            <textarea id="description" name="description" rows="5">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="form-divider">

                    <!-- Конструктор задания -->
                    <h3>Конструктор задания</h3>

                    <div class="fields-container">
                        <div id="fields-container"></div>

                        <button type="button" id="add-field-btn" class="btn primary add-field-btn">
                            <i class="fas fa-plus"></i> Добавить поле
                        </button>
                    </div>

                    <!-- Скрытый JSON для отправки данных -->
                    <input type="hidden" name="fields_json" id="fields-json">

                    <button type="submit" class="btn primary large full-width mt-2">
                        Сохранить задание
                    </button>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fieldsContainer = document.getElementById("fields-container");
            const addFieldBtn = document.getElementById("add-field-btn");

            // === Форма календаря и другие поля ===
            const dueDateInput = document.getElementById("due_date");
            dueDateInput.setAttribute("min", new Date().toISOString().split("T")[0]);

            let fieldIndex = 0;

            function createFieldElement() {
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

                const field = {
                    name: "",
                    type: "text",
                    options: []
                };

                // === Изменение типа поля ===
                fieldTypeSelect.addEventListener("change", function() {
                    const selectedType = this.value;

                    if (selectedType === "single_choice" || selectedType === "multiple_choice") {
                        optionsContainer.classList.remove("hidden");
                        field.type = selectedType;
                        updateOptionInputs(optionsList, selectedType);
                    } else {
                        optionsContainer.classList.add("hidden");
                        field.options = [];
                    }
                });

                // === Обработчик добавления варианта ===
                fieldDiv.querySelector(".add-option-btn").addEventListener("click", function() {
                    const optionDiv = document.createElement("div");
                    optionDiv.className = "option";

                    const isSingleChoice = fieldTypeSelect.value === "single_choice";

                    optionDiv.innerHTML = `
                <input type="${isSingleChoice ? 'radio' : 'checkbox'}" class="correct-option" name="option-${fieldIndex}">
                <input type="text" class="option-value" placeholder="Вариант ответа">
                <button type="button" class="remove-option-btn"><i class="fas fa-trash"></i></button>
            `;

                    // === Удаление варианта ===
                    optionDiv.querySelector(".remove-option-btn").addEventListener("click", function() {
                        optionDiv.remove();
                    });

                    // === Если тип изменяется, обновляем тип инпутов ===
                    function updateOptionInputs(container, type) {
                        container.querySelectorAll(".option").forEach(option => {
                            const radioOrCheckbox = option.querySelector(".correct-option");
                            const isRadio = type === "single_choice";
                            radioOrCheckbox.type = isRadio ? "radio" : "checkbox";
                            radioOrCheckbox.name = isRadio ? `option-${fieldIndex}` : "";
                        });
                    }

                    // === Обработка клика по варианту ===
                    optionDiv.querySelector(".correct-option").addEventListener("click", function(e) {
                        if (fieldTypeSelect.value === "single_choice") {
                            [...optionsList.querySelectorAll(".correct-option")].forEach(cb => {
                                cb.checked = false;
                            });
                            e.target.checked = true;
                        }
                    });

                    optionsList.appendChild(optionDiv);

                    field.options.push({
                        value: "",
                        isCorrect: false,
                        element: optionDiv
                    });

                    optionDiv.querySelector(".option-value").addEventListener("input", function(e) {
                        field.options.forEach(opt => {
                            if (opt.element === e.target.parentElement) {
                                opt.value = e.target.value;
                            }
                        });
                    });

                    optionDiv.querySelector(".correct-option").addEventListener("change", function(e) {
                        const isChecked = e.target.checked;
                        field.options.forEach(opt => {
                            if (opt.element === e.target.parentElement) {
                                opt.isCorrect = isChecked;
                            }
                        });
                    });
                });

                // === Удаление всего поля ===
                fieldDiv.querySelector(".remove-field-btn").addEventListener("click", function() {
                    fieldDiv.remove();
                });

                return fieldDiv;
            }

            // === Добавление нового поля ===
            addFieldBtn.addEventListener("click", function() {
                const newField = createFieldElement();
                fieldsContainer.appendChild(newField);
            });

            // === Отправка формы ===
            document.getElementById("assignment-form").addEventListener("submit", function(e) {
                e.preventDefault();

                const filledFields = [];

                document.querySelectorAll(".field").forEach(fieldDiv => {
                    const name = fieldDiv.querySelector(".field-name").value.trim();
                    const type = fieldDiv.querySelector(".field-type").value;
                    const optionsList = fieldDiv.querySelector(".options-list");

                    const options = [...optionsList.querySelectorAll(".option")].map(optionDiv => ({
                        value: optionDiv.querySelector(".option-value").value.trim(),
                        isCorrect: optionDiv.querySelector(".correct-option").checked
                    }));

                    if (!name) {
                        alert("Заполните название вопроса.");
                        e.preventDefault();
                        return;
                    }

                    filledFields.push({
                        name,
                        type,
                        options
                    });
                });

                document.getElementById("fields-json").value = JSON.stringify(filledFields);
                e.target.submit();
            });
        });
    </script>
</body>

</html>

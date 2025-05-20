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

<body>
    @include('layout.sidebar', ['activePage' => 'dashboard'])
    <div class="topbar">
        @include('layout.topbar')
        <main>
            <div class="main-platform">
                <form id="assignment-form" action="{{ route('assignment.store') }}" method="POST">
                    @csrf

                    @if ($errors->has('global'))
                        <div class="error-message global-error">
                            {{ $errors->first('global') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="error-message global-error">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="success-message">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div>
                        <label for="title">Название задания:</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required>
                        @if ($errors->has('title'))
                            <div class="error-message">{{ $errors->first('title') }}</div>
                        @endif
                    </div>

                    <div>
                        <label for="description">Описание задания:</label>
                        <textarea id="description" name="description" required>{{ old('description') }}</textarea>
                        @if ($errors->has('description'))
                            <div class="error-message">{{ $errors->first('description') }}</div>
                        @endif
                    </div>

                    <div>
                        <label for="due_date">Дата сдачи:</label>
                        <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}" required>
                        @if ($errors->has('due_date'))
                            <div class="error-message">{{ $errors->first('due_date') }}</div>
                        @endif
                    </div>
                    <div>
                        <label for="class_id">Выберите класс:</label>
                        <select id="class_id" name="class_id" required>
                            <option value="" disabled
                                {{ old('class_id', $selectedClass->id ?? '') == '' ? 'selected' : '' }}>-- Выберите
                                класс --</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}"
                                    {{ old('class_id', $selectedClass->id ?? '') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('class_id'))
                            <div class="error-message">{{ $errors->first('class_id') }}</div>
                        @endif
                    </div>
                    <input type="hidden" name="fields_json" id="fields-json">
                    <h3>Поля задания:</h3>
                    <div id="fields-container"></div>
                    <button type="button" id="add-field-btn">Добавить поле</button>
                    @if ($errors->has('type'))
                        <div class="error-message">{{ $errors->first('type') }}</div>
                    @endif
                    <button type="submit">Сохранить задание</button>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const fieldsContainer = document.getElementById('fields-container');
            const addFieldBtn = document.getElementById('add-field-btn');
            const dueDateInput = document.getElementById('due_date');
            const fields = [];

            // Установить минимальную дату для дедлайна
            dueDateInput.setAttribute('min', new Date().toISOString().split('T')[0]);

            // Добавить новое поле
            addFieldBtn.addEventListener('click', () => {
                const fieldIndex = fields.length;
                const fieldDiv = document.createElement('div');
                fieldDiv.classList.add('field');
                fieldDiv.dataset.index = fieldIndex;

                fieldDiv.innerHTML = `
                    <div>
                        <label>Название поля:</label>
                        <input type="text" class="field-name" required>
                    </div>
                    <div>
                        <label>Тип поля:</label>
                        <select class="field-type">
                            <option value="text">Текст</option>
                            <option value="file_upload">Загрузка файла</option>
                            <option value="multiple_choice">Множественный выбор</option>
                            <option value="single_choice">Одиночный выбор</option>
                        </select>
                    </div>
                    <div class="options-container" style="display: none;">
                        <label>Варианты:</label>
                        <div class="options-list"></div>
                        <button type="button" class="add-option-btn">Добавить вариант</button>
                    </div>
                    <button type="button" class="remove-field-btn">Удалить поле</button>
                `;

                fieldsContainer.appendChild(fieldDiv);

                const field = {
                    name: '',
                    type: 'text',
                    options: [],
                };
                fields.push(field);

                // Обработчики для поля
                fieldDiv.querySelector('.field-name').addEventListener('input', (e) => {
                    field.name = e.target.value.trim();
                });

                fieldDiv.querySelector('.field-type').addEventListener('change', (e) => {
                    field.type = e.target.value;
                    const optionsContainer = fieldDiv.querySelector('.options-container');
                    if (field.type === 'multiple_choice' || field.type === 'single_choice') {
                        optionsContainer.style.display = 'block';
                    } else {
                        optionsContainer.style.display = 'none';
                        field.options = [];
                    }
                });

                fieldDiv.querySelector('.add-option-btn').addEventListener('click', () => {
                    const optionsList = fieldDiv.querySelector('.options-list');
                    const optionIndex = field.options.length;

                    const optionDiv = document.createElement('div');
                    optionDiv.classList.add('option');
                    optionDiv.innerHTML = `
                        <input type="text" class="option-value" placeholder="Вариант ответа" required>
                        <input type="${field.type === 'single_choice' ? 'radio' : 'checkbox'}" class="correct-option">
                        <button type="button" class="remove-option-btn">Удалить вариант</button>
                    `;

                    optionsList.appendChild(optionDiv);

                    const option = {
                        value: '',
                        isCorrect: false
                    };
                    field.options.push(option);

                    // Обработчики для варианта
                    optionDiv.querySelector('.option-value').addEventListener('input', (e) => {
                        option.value = e.target.value.trim();
                    });

                    optionDiv.querySelector('.correct-option').addEventListener('change', (e) => {
                        option.isCorrect = e.target.checked;
                        if (field.type === 'single_choice') {
                            field.options.forEach((opt) => opt.isCorrect = false);
                            option.isCorrect = true;
                        }
                    });

                    optionDiv.querySelector('.remove-option-btn').addEventListener('click', () => {
                        field.options.splice(optionIndex, 1);
                        optionDiv.remove();
                    });
                });

                fieldDiv.querySelector('.remove-field-btn').addEventListener('click', () => {
                    fields.splice(fieldIndex, 1);
                    fieldDiv.remove();
                });
            });

            // Обработчик отправки формы
            document.getElementById('assignment-form').addEventListener('submit', (e) => {
                e.preventDefault();

                const filledFields = fields.filter(field => field.name);
                if (filledFields.length === 0) {
                    alert('Добавьте хотя бы одно поле.');
                    return;
                }

                document.getElementById('fields-json').value = JSON.stringify(filledFields);
                e.target.submit();
            });
        });
    </script>
</body>

</html>

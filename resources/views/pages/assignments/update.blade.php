<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Обновление задания для класса</title>
    <link rel="stylesheet" href="{{ asset('css/style-platform.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/script.js') }}" defer></script>
</head>

<body>
    @include('layout.sidebar', ['activePage' => 'tasks'])

    <div class="topbar">
        @include('layout.topbar')
        <main>
            <div class="main-platform">
                <form id="assignment-form" action="{{ route('assignments.update', $assignment->id) }}" method="POST">
                    @csrf
                    @method('PUT')

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
                        <input type="text" id="title" name="title"
                            value="{{ old('title', $assignment->title) }}">
                        @error('title')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="description">Описание задания:</label>
                        <textarea id="description" name="description">{{ old('description', $assignment->description) }}</textarea>
                        @error('description')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="due_date">Дата сдачи:</label>
                        <input type="date" id="due_date" name="due_date"
                            value="{{ old('due_date', $assignment->due_date) }}">
                        @error('due_date')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="class_id">Выберите класс:</label>
                        <select id="class_id" name="class_id">
                            <option value="" disabled>-- Выберите класс --</option>
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

                    <input type="hidden" name="fields_json" id="fields-json">
                    <h3>Поля задания:</h3>
                    <div id="fields-container"></div>
                    <button type="button" id="add-field-btn">Добавить поле</button>
                    @error('type')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

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
            const existingFields = @json($fields);
            const fields = [...existingFields];

            dueDateInput.setAttribute('min', new Date().toISOString().split('T')[0]);

            const createFieldHTML = (field, index) => {
                const fieldDiv = document.createElement('div');
                fieldDiv.classList.add('field');
                fieldDiv.dataset.index = index;

                fieldDiv.innerHTML = `
            <div>
                <label>Название поля:</label>
                <input type="text" class="field-name" value="${field.name}" required>
            </div>
            <div>
                <label>Тип поля:</label>
                <select class="field-type">
                    <option value="text" ${field.type === 'text' ? 'selected' : ''}>Текст</option>
                    <option value="file_upload" ${field.type === 'file_upload' ? 'selected' : ''}>Загрузка файла</option>
                    <option value="multiple_choice" ${field.type === 'multiple_choice' ? 'selected' : ''}>Множественный выбор</option>
                    <option value="single_choice" ${field.type === 'single_choice' ? 'selected' : ''}>Одиночный выбор</option>
                </select>
            </div>
            <div class="options-container" style="display: ${field.type === 'multiple_choice' || field.type === 'single_choice' ? 'block' : 'none'};">
                <label>Варианты:</label>
                <div class="options-list"></div>
                <button type="button" class="add-option-btn">Добавить вариант</button>
            </div>
            <button type="button" class="remove-field-btn">Удалить поле</button>
        `;

                fieldsContainer.appendChild(fieldDiv);

                const fieldNameInput = fieldDiv.querySelector('.field-name');
                const fieldTypeSelect = fieldDiv.querySelector('.field-type');
                const optionsContainer = fieldDiv.querySelector('.options-container');
                const optionsList = fieldDiv.querySelector('.options-list');

                fieldNameInput.addEventListener('input', (e) => {
                    field.name = e.target.value.trim();
                });

                fieldTypeSelect.addEventListener('change', (e) => {
                    field.type = e.target.value;
                    optionsContainer.style.display = field.type === 'multiple_choice' ||
                        field.type === 'single_choice' ? 'block' : 'none';
                    if (field.type !== 'multiple_choice' && field.type !==
                        'single_choice') {
                        field.options = [];
                        optionsList.innerHTML = '';
                    }
                });

                if (field.options) {
                    field.options.forEach((option, optionIndex) => {
                        addOptionToField(optionsList, field, option, optionIndex);
                    });
                }

                const addOptionBtn = fieldDiv.querySelector('.add-option-btn');
                addOptionBtn.addEventListener('click', () => {
                    const option = {
                        value: '',
                        isCorrect: false
                    };
                    field.options.push(option);
                    addOptionToField(optionsList, field, option, field.options.length - 1);
                });

                const removeFieldBtn = fieldDiv.querySelector('.remove-field-btn');
                removeFieldBtn.addEventListener('click', () => {
                    fields.splice(index, 1);
                    fieldDiv.remove();
                });
            };

            const addOptionToField = (optionsList, field, option, index) => {
                const optionDiv = document.createElement('div');
                optionDiv.classList.add('option');
                optionDiv.innerHTML = `
            <input type="text" class="option-value" value="${option.value}" placeholder="Вариант ответа" required>
            <input type="${field.type === 'single_choice' ? 'radio' : 'checkbox'}" class="correct-option" ${option.isCorrect ? 'checked' : ''}>
            <button type="button" class="remove-option-btn">Удалить вариант</button>
        `;

                optionsList.appendChild(optionDiv);

                const optionValueInput = optionDiv.querySelector('.option-value');
                const correctOptionInput = optionDiv.querySelector('.correct-option');
                const removeOptionBtn = optionDiv.querySelector('.remove-option-btn');

                optionValueInput.addEventListener('input', (e) => {
                    option.value = e.target.value.trim();
                });

                correctOptionInput.addEventListener('change', (e) => {
                    option.isCorrect = e.target.checked;
                    if (field.type === 'single_choice') {
                        field.options.forEach(opt => opt.isCorrect = false);
                        option.isCorrect = true;
                    }
                });

                removeOptionBtn.addEventListener('click', () => {
                    field.options.splice(index, 1);
                    optionDiv.remove();
                });
            };

            existingFields.forEach((field, index) => createFieldHTML(field, index));

            addFieldBtn.addEventListener('click', () => {
                const field = {
                    name: '',
                    type: 'text',
                    options: []
                };
                fields.push(field);
                createFieldHTML(field, fields.length - 1);
            });

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

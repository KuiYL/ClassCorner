<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить класс</title>
</head>

<body>
    <form id="assignment-form" action="{{ route('assignment.store') }}" method="POST">
        @csrf
        <!-- Основная информация о задании -->
        <div>
            <label>Название задания:</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div>
            <label>Описание задания:</label>
            <textarea id="description" name="description" required></textarea>
        </div>
        <div>
            <label>Дата сдачи:</label>
            <input type="date" id="due_date" name="due_date" required>
        </div>

        <!-- Динамические поля -->
        <h3>Поля задания:</h3>
        <div id="fields-container"></div>
        <button type="button" id="add-field-btn">Добавить поле</button>

        <!-- Кнопка отправки -->
        <button type="submit">Сохранить задание</button>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('assignment-form');
            const fieldsContainer = document.getElementById('fields-container');
            const addFieldBtn = document.getElementById('add-field-btn');
            const dueDateInput = document.getElementById('due_date');

            const today = new Date().toISOString().split('T')[0];
            dueDateInput.setAttribute('min', today);
            // Список для хранения полей
            let fields = [];

            // Добавить новое поле
            addFieldBtn.addEventListener('click', function() {
                const fieldIndex = fields.length;
                fields.push({
                    name: '',
                    type: 'text',
                    options: []
                });

                const fieldDiv = document.createElement('div');
                fieldDiv.className = 'field';
                fieldDiv.dataset.index = fieldIndex;

                fieldDiv.innerHTML = `
            <label>Название поля:</label>
            <input type="text" class="field-name" data-index="${fieldIndex}" required>
            <label>Тип поля:</label>
            <select class="field-type" data-index="${fieldIndex}">
                <option value="text">Текст</option>
                <option value="file_upload">Загрузка файла</option>
                <option value="multiple_choice">Множественный выбор</option>
                <option value="single_choice">Одиночный выбор</option>
            </select>
            <div class="options-container" data-index="${fieldIndex}" style="display: none;">
                <label>Варианты:</label>
                <div class="options-list"></div>
                <button type="button" class="add-option-btn" data-index="${fieldIndex}">Добавить вариант</button>
            </div>
            <button type="button" class="remove-field-btn" data-index="${fieldIndex}">Удалить поле</button>
        `;

                fieldsContainer.appendChild(fieldDiv);

                // События для нового поля
                fieldDiv.querySelector('.field-type').addEventListener('change', handleTypeChange);
                fieldDiv.querySelector('.add-option-btn').addEventListener('click', addOption);
                fieldDiv.querySelector('.remove-field-btn').addEventListener('click', removeField);
            });

            // Обработчик изменения типа поля
            function handleTypeChange(event) {
                const index = event.target.dataset.index;
                const type = event.target.value;
                const optionsContainer = fieldsContainer.querySelector(`.options-container[data-index="${index}"]`);
                const optionsList = optionsContainer.querySelector('.options-list');

                if (type === 'multiple_choice' || type === 'single_choice') {
                    optionsContainer.style.display = 'block';
                    optionsList.innerHTML = ''; // Очищаем, чтобы избежать конфликтов
                    fields[index].options = []; // Очищаем массив вариантов
                } else {
                    optionsContainer.style.display = 'none';
                }

                if (type === 'file_upload') {
                    // Если тип - загрузка файла, генерируем поле для загрузки файла
                    optionsList.innerHTML = `
            <label>Загрузить файл:</label>
            <input type="file" class="file-upload" data-index="${index}">
        `;
                    fields[index].options = []; // Очищаем варианты
                } else {
                    optionsList.innerHTML = ''; // Удаляем любые элементы, не относящиеся к выбранному типу
                }

                fields[index].type = type;
            }


            function addOption(event) {
                const index = event.target.dataset.index; // Получаем индекс текущего поля
                const type = fields[index].type;
                const optionsContainer = fieldsContainer.querySelector(`.options-container[data-index="${index}"]`);
                const optionsList = optionsContainer.querySelector('.options-list');
                const optionIndex = fields[index].options.length;

                fields[index].options.push({
                    value: '',
                    isCorrect: false
                });

                const optionDiv = document.createElement('div');
                optionDiv.className = 'option';
                optionDiv.dataset.optionIndex = optionIndex;

                if (type === 'single_choice') {
                    optionDiv.innerHTML = `
            <input type="radio" name="single-choice-${index}" class="correct-option" data-field-index="${index}" data-option-index="${optionIndex}">
            <input type="text" class="option-value" data-field-index="${index}" data-option-index="${optionIndex}" placeholder="Вариант ответа">
            <button type="button" class="remove-option-btn" data-field-index="${index}" data-option-index="${optionIndex}">Удалить</button>
        `;
                } else {
                    optionDiv.innerHTML = `
            <input type="checkbox" class="correct-option" data-field-index="${index}" data-option-index="${optionIndex}">
            <input type="text" class="option-value" data-field-index="${index}" data-option-index="${optionIndex}" placeholder="Вариант ответа">
            <button type="button" class="remove-option-btn" data-field-index="${index}" data-option-index="${optionIndex}">Удалить</button>
        `;
                }

                optionsList.appendChild(optionDiv);

                // Обработчик изменения текста варианта
                optionDiv.querySelector('.option-value').addEventListener('input', function(event) {
                    const fieldIndex = event.target.dataset.fieldIndex;
                    const optionIndex = event.target.dataset.optionIndex;
                    fields[fieldIndex].options[optionIndex].value = event.target.value;
                });

                // Обработчик изменения правильности ответа
                if (type === 'single_choice') {
                    optionDiv.querySelector('.correct-option').addEventListener('change', function(event) {
                        const fieldIndex = event.target.dataset.fieldIndex;
                        const optionIndex = event.target.dataset.optionIndex;
                        fields[fieldIndex].options.forEach((option, idx) => {
                            option.isCorrect = (idx ==
                                optionIndex); // Только один вариант может быть правильным
                        });
                    });
                } else {
                    optionDiv.querySelector('.correct-option').addEventListener('change', function(event) {
                        const fieldIndex = event.target.dataset.fieldIndex;
                        const optionIndex = event.target.dataset.optionIndex;
                        fields[fieldIndex].options[optionIndex].isCorrect = event.target.checked;
                    });
                }

                // Обработчик удаления варианта
                optionDiv.querySelector('.remove-option-btn').addEventListener('click', removeOption);
            }


            // Удалить вариант ответа
            function removeOption(event) {
                const fieldIndex = event.target.dataset.fieldIndex;
                const optionIndex = event.target.dataset.optionIndex;
                fields[fieldIndex].options.splice(optionIndex, 1);

                // Удаляем элемент из DOM
                const optionDiv = event.target.closest('.option');
                optionDiv.remove();
            }

            // Удалить поле
            function removeField(event) {
                const index = event.target.dataset.index;
                fields.splice(index, 1);

                // Удаляем элемент из DOM
                const fieldDiv = event.target.closest('.field');
                fieldDiv.remove();
            }

            document.querySelector('form').addEventListener('submit', (event) => {
                const formData = new FormData(event.target);
                console.log([...formData.entries()]);
            });
        });
    </script>
</body>

</html>

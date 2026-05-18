// Логика «Инженерной лаборатории»

function openLabModal(system) {
    const modal = document.getElementById('lab-modal');
    const title = document.getElementById('lab-title');
    const formulasDiv = document.getElementById('lab-formulas');
    const inputsDiv = document.getElementById('lab-inputs');
    const resultDiv = document.getElementById('lab-result');

    const buildingType = document.getElementById('building-type').value;
    const area = parseFloat(document.getElementById('total-area').value) || 0;
    const people = parseFloat(document.getElementById('people-count').value) || 0;
    const floors = parseFloat(document.getElementById('floors-count').value) || 0;
    const volume = parseFloat(document.getElementById('building-volume').value) || 0;

    // Получаем данные из нормативов
    const normsModule = ENGINEERING_NORMS.modules[system === 'vk' ? 'water' : system === 'ov' ? 'heat' : 'electricity'];
    title.textContent = normsModule.title;

    // Отображаем формулы
    formulasDiv.innerHTML = '<h3>Формулы:</h3><ul>' +
        normsModule.formulas_display.map(f => `<li>${f}</li>`).join('') + '</ul>';

    // Создаем инпуты для параметров
    let inputsHTML = '<h3>Параметры (по умолчанию из нормативов):</h3>';
    inputsHTML += '<div class="lab-inputs-container">';
    for (const [key, param] of Object.entries(normsModule.parameters)) {
        inputsHTML += `
        <div class="input-row">
            <label>${param.label} (${param.unit}):</label>
            <input type="number" step="any" class="norm-input" data-param="${key}" value="${param.value}" ${param.editable ? '' : 'disabled'}>
            <span class="source">${param.source}</span>
        </div>
        `;
    }
    inputsHTML += '</div>';

    // Добавляем кнопку расчета
    inputsHTML += '<button id="calculate-btn" class="system-btn">Рассчитать</button>';
    inputsDiv.innerHTML = inputsHTML;

    // Обработчик расчета
    document.getElementById('calculate-btn').addEventListener('click', () => {
        // Собираем актуальные значения из инпутов
        const inputs = document.querySelectorAll('.norm-input');
        inputs.forEach(input => {
            const paramKey = input.getAttribute('data-param');
            const newValue = parseFloat(input.value);
            if (!isNaN(newValue)) {
                normsModule.parameters[paramKey].value = newValue;
            }
        });

        let resultText = '';
        if (system === 'vk') {
            const res = Calculator.calculateWater(buildingType, people, floors, volume);
            // Формат: Результат = Переменная * Коэффициент
            resultText = `Результат = ${res.formula}`;
        } else if (system === 'ov') {
            const tOutInput = document.querySelector('.norm-input[data-param="t_out"]');
            const tOut = tOutInput ? parseFloat(tOutInput.value) : -25;
            const res = Calculator.calculateHeat(buildingType, volume, tOut);
            resultText = `Результат = ${res.formula}`;
        } else if (system === 'eom') {
            const res = Calculator.calculateElectricity(buildingType, area, people);
            resultText = `Результат = ${res.formula}`;
        }
        resultDiv.innerHTML = `<div class="result-box">${resultText}</div>`;
    });

    // Показываем модальное окно
    modal.style.display = 'block';

    // Закрытие модального окна
    document.querySelector('.close-btn').addEventListener('click', () => {
        modal.style.display = 'none';
    });
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
}
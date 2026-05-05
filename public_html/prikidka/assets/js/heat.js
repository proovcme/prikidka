// Глобальная переменная для хранения справочников тепла
let heatDirectories = {};

// Функция для получения данных из API
async function fetchHeatDirectories() {
    try {
        const response = await fetch('backend/api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ action: 'get_cost_directories' }),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        heatDirectories = {
            heating: data.heat_heating || [],
            vent: data.heat_vent || [],
        };
        populateHeatSelects();
    } catch (error) {
        console.error('Ошибка при загрузке справочников тепла:', error);
    }
}

// Функция для заполнения выпадающего списка "Тип объекта"
function populateHeatSelects() {
    const objectTypeSelect = document.getElementById('heat-object-type');

    if (heatDirectories.heating) {
        heatDirectories.heating.forEach(item => {
            const option = document.createElement('option');
            option.value = item.name;
            option.textContent = item.name;
            objectTypeSelect.appendChild(option);
        });
    }
}

// Функция для получения удельного расхода тепла на отопление по типу объекта
function getHeatingLoad(objectTypeName) {
    const item = heatDirectories.heating.find(i => i.name === objectTypeName);
    return item ? parseFloat(item.value) : 0;
}

// Функция для получения удельного расхода тепла на вентиляцию по типу объекта
function getVentLoad(objectTypeName) {
    const item = heatDirectories.vent.find(i => i.name === objectTypeName);
    return item ? parseFloat(item.value) : 0;
}

// Функция для расчета тепловых нагрузок
function calculateHeat() {
    const objectTypeSelect = document.getElementById('heat-object-type');
    const areaInput = document.getElementById('heat-area');
    const ceilingHeightInput = document.getElementById('heat-ceiling-height');
    const glazingSelect = document.getElementById('heat-glazing');
    const heatingResultElement = document.getElementById('heat-heating-result');
    const ventResultElement = document.getElementById('heat-vent-result');
    const totalResultElement = document.getElementById('heat-total-result');
    const totalGcalElement = document.getElementById('heat-total-gcal');

    const objectTypeName = objectTypeSelect.value;
    const area = parseFloat(areaInput.value) || 0;
    const ceilingHeight = parseFloat(ceilingHeightInput.value) || 3;
    const glazingPercent = parseFloat(glazingSelect ? glazingSelect.value : 20);

    const heatingLoadPerSqm = getHeatingLoad(objectTypeName);
    const ventLoadPerSqm = getVentLoad(objectTypeName);

    // Базовые нагрузки
    let heatingPower = (area * heatingLoadPerSqm) / 1000;
    let ventPower = (area * ventLoadPerSqm) / 1000;

    // Коэффициент высоты потолков
    const heightCoef = ceilingHeight / 3;
    heatingPower *= heightCoef;
    ventPower *= heightCoef;

    // Итоговая тепловая нагрузка
    let totalPower = heatingPower + ventPower;

    // Коэффициент остекления
    if (glazingPercent === 50) {
        totalPower *= 1.2;
    } else if (glazingPercent === 80) {
        totalPower *= 1.5;
    }

    // Перевод в Гкал/ч: 1 Гкал/ч = 1163 кВт
    const totalGcal = totalPower / 1163;

    heatingResultElement.textContent = heatingPower.toFixed(2) + ' кВт';
    ventResultElement.textContent = ventPower.toFixed(2) + ' кВт';
    totalResultElement.textContent = totalPower.toFixed(2) + ' кВт';
    if (totalGcalElement) {
        totalGcalElement.textContent = totalGcal.toFixed(4) + ' Гкал/ч';
    }
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    fetchHeatDirectories();

    document.getElementById('heat-object-type').addEventListener('change', calculateHeat);
    document.getElementById('heat-area').addEventListener('input', calculateHeat);
    document.getElementById('heat-ceiling-height').addEventListener('input', calculateHeat);
    document.getElementById('heat-glazing').addEventListener('change', calculateHeat);
});
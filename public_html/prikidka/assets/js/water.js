// Глобальная переменная для хранения справочников воды
let waterDirectories = {};

// Функция для получения данных из API
async function fetchWaterDirectories() {
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
        waterDirectories = {
            dailyRate: data.water_daily_rate || [],
        };
        populateWaterSelects();
    } catch (error) {
        console.error('Ошибка при загрузке справочников воды:', error);
    }
}

// Функция для заполнения выпадающего списка "Тип объекта"
function populateWaterSelects() {
    const objectTypeSelect = document.getElementById('water-object-type');

    if (waterDirectories.dailyRate) {
        waterDirectories.dailyRate.forEach(item => {
            const option = document.createElement('option');
            option.value = item.name;
            option.textContent = item.name;
            objectTypeSelect.appendChild(option);
        });
    }
}

// Функция для получения нормы расхода воды в сутки по типу объекта
function getDailyRate(objectTypeName) {
    const item = waterDirectories.dailyRate.find(i => i.name === objectTypeName);
    return item ? parseFloat(item.value) : 0;
}

// Функция для расчета водопотребления
function calculateWater() {
    const objectTypeSelect = document.getElementById('water-object-type');
    const usersInput = document.getElementById('water-users');
    const inequalityCoefInput = document.getElementById('water-inequality-coef');
    const firePipeCheckbox = document.getElementById('water-fire-pipe');
    const sprinklerCheckbox = document.getElementById('water-sprinkler');
    const dailyResultElement = document.getElementById('water-daily-result');
    const hourlyResultElement = document.getElementById('water-hourly-result');

    const objectTypeName = objectTypeSelect.value;
    const users = parseFloat(usersInput.value) || 0;
    const inequalityCoef = parseFloat(inequalityCoefInput.value) || 1;
    const hasFirePipe = firePipeCheckbox ? firePipeCheckbox.checked : false;
    const hasSprinkler = sprinklerCheckbox ? sprinklerCheckbox.checked : false;

    const dailyRatePerPerson = getDailyRate(objectTypeName);

    // Суточный расход (м³)
    const dailyConsumption = (users * dailyRatePerPerson) / 1000;

    // Базовый часовой расход (м³/час)
    let hourlyConsumption = (dailyConsumption / 24) * inequalityCoef;

    // Если ВПВ активен — прибавляем 5 м³/ч
    if (hasFirePipe) {
        hourlyConsumption += 5;
    }

    // Если АУПТ активен — прибавляем 30 м³/ч
    if (hasSprinkler) {
        hourlyConsumption += 30;
    }

    dailyResultElement.textContent = dailyConsumption.toFixed(2) + ' м³/сут';
    hourlyResultElement.textContent = hourlyConsumption.toFixed(2) + ' м³/час';
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    fetchWaterDirectories();

    document.getElementById('water-object-type').addEventListener('change', calculateWater);
    document.getElementById('water-users').addEventListener('input', calculateWater);
    document.getElementById('water-inequality-coef').addEventListener('input', calculateWater);
    document.getElementById('water-fire-pipe').addEventListener('change', calculateWater);
    document.getElementById('water-sprinkler').addEventListener('change', calculateWater);
});
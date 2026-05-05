// Глобальная переменная для хранения справочников электричества
let electroDirectories = {};

// Функция для получения данных из API
async function fetchElectroDirectories() {
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
        electroDirectories = {
            specific: data.electro_specific || [],
            demand: data.electro_demand || [],
        };
        populateElectroSelects();
    } catch (error) {
        console.error('Ошибка при загрузке справочников электричества:', error);
    }
}

// Функция для заполнения выпадающего списка "Тип объекта"
function populateElectroSelects() {
    const objectTypeSelect = document.getElementById('electro-object-type');

    if (electroDirectories.specific) {
        electroDirectories.specific.forEach(item => {
            const option = document.createElement('option');
            option.value = item.name;
            option.textContent = item.name;
            objectTypeSelect.appendChild(option);
        });
    }
}

// Функция для получения удельной нагрузки по типу объекта
function getSpecificLoad(objectTypeName) {
    const item = electroDirectories.specific.find(i => i.name === objectTypeName);
    return item ? parseFloat(item.value) : 0;
}

// Функция для получения коэффициента спроса по типу объекта
function getDemandFactor(objectTypeName) {
    const item = electroDirectories.demand.find(i => i.name === objectTypeName);
    return item ? parseFloat(item.value) : 1;
}

// Функция для расчета мощности
function calculateElectroPower() {
    const objectTypeSelect = document.getElementById('electro-object-type');
    const areaInput = document.getElementById('electro-area');
    const additionalEqInput = document.getElementById('electro-additional-eq');
    const cookingTypeSelect = document.getElementById('electro-cooking-type');
    const emChargingInput = document.getElementById('electro-em-charging');
    const smokeCheckbox = document.getElementById('electro-smoke');
    const calculatedPowerElement = document.getElementById('electro-calculated-power');
    const installedPowerElement = document.getElementById('electro-installed-power');
    const totalKvaElement = document.getElementById('electro-total-kva');

    const objectTypeName = objectTypeSelect.value;
    const area = parseFloat(areaInput.value) || 0;
    const additionalEq = parseFloat(additionalEqInput.value) || 0;
    const cookingType = cookingTypeSelect ? cookingTypeSelect.value : 'gas';
    const emCharging = parseFloat(emChargingInput.value) || 0;
    const hasSmoke = smokeCheckbox ? smokeCheckbox.checked : false;

    const specificLoad = getSpecificLoad(objectTypeName);
    const demandFactor = getDemandFactor(objectTypeName);

    // Базовая установленная мощность
    let installedPower = (area * specificLoad / 1000) + additionalEq;

    // Если электрические плиты — умножаем базовую нагрузку на 1.3
    if (cookingType === 'electric') {
        installedPower *= 1.3;
    }

    // Добавляем зарядку ЭМ (7 кВт на машиноместо)
    installedPower += emCharging * 7;

    // Расчетная мощность
    let calculatedPower = (installedPower - additionalEq) * demandFactor + additionalEq;

    // Если ДУ включен — +15% к итоговой активной мощности
    if (hasSmoke) {
        calculatedPower *= 1.15;
    }

    // Полная мощность трансформатора: S = P / 0.85
    const totalKva = calculatedPower / 0.85;

    installedPowerElement.textContent = installedPower.toFixed(2) + ' кВт';
    calculatedPowerElement.textContent = calculatedPower.toFixed(2) + ' кВт';
    if (totalKvaElement) {
        totalKvaElement.textContent = totalKva.toFixed(2) + ' кВА';
    }
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    fetchElectroDirectories();

    document.getElementById('electro-object-type').addEventListener('change', calculateElectroPower);
    document.getElementById('electro-area').addEventListener('input', calculateElectroPower);
    document.getElementById('electro-additional-eq').addEventListener('input', calculateElectroPower);
    document.getElementById('electro-cooking-type').addEventListener('change', calculateElectroPower);
    document.getElementById('electro-em-charging').addEventListener('input', calculateElectroPower);
    document.getElementById('electro-smoke').addEventListener('change', calculateElectroPower);
});
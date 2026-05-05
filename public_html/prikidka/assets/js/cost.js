// Глобальная переменная для хранения справочников
let costDirectories = {};

// Функция для форматирования чисел с пробелами между тысячами и знаком рубля
function formatCurrency(amount) {
    return amount.toLocaleString('ru-RU', { maximumFractionDigits: 0 }) + ' ₽';
}

// Функция для получения данных из API
async function fetchCostDirectories() {
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
        costDirectories = data;
        populateSelects();
    } catch (error) {
        console.error('Ошибка при загрузке справочников:', error);
    }
}

// Функция для заполнения выпадающих списков
function populateSelects() {
    const ncsBuildingTypeSelect = document.getElementById('ncs-building-type');
    const ncsRegionSelect = document.getElementById('ncs-region');
    const pirBuildingTypeSelect = document.getElementById('pir-building-type');
    const indexPeriodSelect = document.getElementById('index-period');

    if (costDirectories.ncs_base) {
        costDirectories.ncs_base.forEach(item => {
            const option = document.createElement('option');
            option.value = item.value;
            option.textContent = item.name;
            ncsBuildingTypeSelect.appendChild(option);
        });
    }

    if (costDirectories.region_coef) {
        costDirectories.region_coef.forEach(item => {
            const option = document.createElement('option');
            option.value = item.value;
            option.textContent = item.name;
            ncsRegionSelect.appendChild(option);
        });
    }

    if (costDirectories.pir_percent) {
        costDirectories.pir_percent.forEach(item => {
            const option = document.createElement('option');
            option.value = item.value;
            option.textContent = item.name;
            pirBuildingTypeSelect.appendChild(option);
        });
    }

    if (costDirectories.fgis_index) {
        costDirectories.fgis_index.forEach(item => {
            const option = document.createElement('option');
            option.value = item.value;
            option.textContent = item.name;
            indexPeriodSelect.appendChild(option);
        });
    }
}

// Функция для получения коэффициента GLA по типу здания и классу
function getGlaCoefficient(buildingTypeName, objectClass) {
    // Склады: GLA = 95%
    if (buildingTypeName && buildingTypeName.toLowerCase().includes('склад')) {
        return 0.95;
    }
    // Эконом: GLA = 80%
    if (objectClass === 'econom') {
        return 0.80;
    }
    // Комфорт, Бизнес, Офисы: GLA = 70%
    return 0.70;
}

// Функция для расчета стоимости по НЦС
function calculateNcs() {
    const buildingTypeSelect = document.getElementById('ncs-building-type');
    const areaInput = document.getElementById('ncs-area');
    const regionSelect = document.getElementById('ncs-region');
    const undergroundSelect = document.getElementById('ncs-underground');
    const classSelect = document.getElementById('ncs-class');
    const soilSelect = document.getElementById('ncs-soil');
    const ndsCheckbox = document.getElementById('ncs-nds');
    const resultElement = document.getElementById('ncs-result');
    const sellableAreaElement = document.getElementById('cost-sellable-area');

    const pricePerSqm = parseFloat(buildingTypeSelect.value) || 0;
    const area = parseFloat(areaInput.value) || 0;
    const regionCoef = parseFloat(regionSelect.value) || 1;
    const underground = parseInt(undergroundSelect ? undergroundSelect.value : 0);
    const objectClass = classSelect ? classSelect.value : 'econom';
    const soilType = soilSelect ? soilSelect.value : 'normal';
    const includeNds = ndsCheckbox ? ndsCheckbox.checked : false;
    const buildingTypeName = buildingTypeSelect.options[buildingTypeSelect.selectedIndex]?.text || '';

    // Базовая стоимость
    let totalCost = pricePerSqm * area * regionCoef;

    // Коэффициент подземных этажей: каждый этаж +15%
    if (underground === 1) {
        totalCost *= 1.15;
    } else if (underground >= 2) {
        totalCost *= 1.30;
    }

    // Коэффициент класса объекта
    if (objectClass === 'comfort') {
        totalCost *= 1.15;
    } else if (objectClass === 'business') {
        totalCost *= 1.4;
    }

    // Грунтовые условия: сложные свайные +15%
    if (soilType === 'difficult') {
        totalCost *= 1.15;
    }

    // НДС 22%
    if (includeNds) {
        totalCost *= 1.22;
    }

    // Расчет GLA
    const glaCoef = getGlaCoefficient(buildingTypeName, objectClass);
    const sellableArea = Math.round(area * glaCoef);

    resultElement.textContent = formatCurrency(totalCost);
    if (sellableAreaElement) {
        sellableAreaElement.textContent = sellableArea.toLocaleString('ru-RU') + ' м²';
    }
}

// Функция для расчета стоимости ПИР
function calculatePir() {
    const smrCostInput = document.getElementById('pir-smr-cost');
    const buildingTypeSelect = document.getElementById('pir-building-type');
    const percentInput = document.getElementById('pir-percent');
    const ndsCheckbox = document.getElementById('ncs-nds');
    const resultElement = document.getElementById('pir-result');

    const smrCost = parseFloat(smrCostInput.value) || 0;
    const buildingTypePercent = parseFloat(buildingTypeSelect.value) || 0;
    const manualPercent = parseFloat(percentInput.value);
    const includeNds = ndsCheckbox ? ndsCheckbox.checked : false;

    const finalPercent = manualPercent !== null && !isNaN(manualPercent) ? manualPercent : buildingTypePercent;

    let pirCost = smrCost * (finalPercent / 100);

    // НДС 22% для ПИР тоже
    if (includeNds) {
        pirCost *= 1.22;
    }

    resultElement.textContent = formatCurrency(pirCost);
}

// Функция для расчета обновленной стоимости по индексам
function calculateIndex() {
    const historicalCostInput = document.getElementById('index-historical-cost');
    const periodSelect = document.getElementById('index-period');
    const resultElement = document.getElementById('index-result');

    const historicalCost = parseFloat(historicalCostInput.value) || 0;
    const indexValue = parseFloat(periodSelect.value) || 1;

    const updatedCost = historicalCost * indexValue;
    resultElement.textContent = formatCurrency(updatedCost);
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    fetchCostDirectories();

    // Обработчики событий для вкладок
    const tabButtons = document.querySelectorAll('.tabs .btn[data-tab]');
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            tabButtons.forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

            button.classList.add('active');
            const targetTab = button.getAttribute('data-tab');
            document.getElementById(targetTab).classList.add('active');
        });
    });

    // Обработчики событий для полей ввода НЦС
    document.getElementById('ncs-building-type').addEventListener('change', calculateNcs);
    document.getElementById('ncs-area').addEventListener('input', calculateNcs);
    document.getElementById('ncs-region').addEventListener('change', calculateNcs);
    document.getElementById('ncs-underground').addEventListener('change', calculateNcs);
    document.getElementById('ncs-class').addEventListener('change', calculateNcs);
    document.getElementById('ncs-soil').addEventListener('change', calculateNcs);
    document.getElementById('ncs-nds').addEventListener('change', calculateNcs);

    // Обработчики событий для полей ввода ПИР
    document.getElementById('pir-smr-cost').addEventListener('input', calculatePir);
    document.getElementById('pir-building-type').addEventListener('change', calculatePir);
    document.getElementById('pir-percent').addEventListener('input', calculatePir);

    // Обработчики событий для полей ввода Индексов
    document.getElementById('index-historical-cost').addEventListener('input', calculateIndex);
    document.getElementById('index-period').addEventListener('change', calculateIndex);
});
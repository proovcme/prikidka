// Глобальная переменная для хранения справочников ПНР
let pnrDirectories = {};

// Функция для получения данных из API
async function fetchPnrDirectories() {
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
        pnrDirectories = {
            costPercent: data.pnr_cost_percent || [],
            timeBase: data.pnr_time_base || [],
        };
    } catch (error) {
        console.error('Ошибка при загрузке справочников ПНР:', error);
    }
}

// Функция для получения процента стоимости ПНР по имени системы
function getCostPercent(systemName) {
    const item = pnrDirectories.costPercent.find(i => i.name === systemName);
    return item ? parseFloat(item.value) : 0;
}

// Функция для получения базового срока ПНР по имени системы
function getTimeBase(systemName) {
    const item = pnrDirectories.timeBase.find(i => i.name === systemName);
    return item ? parseFloat(item.value) : 0;
}

// Функция для форматирования валюты
function formatCurrency(amount) {
    return amount.toLocaleString('ru-RU', { maximumFractionDigits: 0 }) + ' ₽';
}

// Функция для расчета ПНР
function calculatePnr() {
    const smrCostInput = document.getElementById('pnr-smr-cost');
    const automationSelect = document.getElementById('pnr-automation');
    const costResultElement = document.getElementById('pnr-cost-result');
    const timeResultElement = document.getElementById('pnr-time-result');

    const smrCost = parseFloat(smrCostInput.value) || 0;
    const automation = automationSelect ? automationSelect.value : 'basic';

    const systemCheckboxes = [
        { id: 'pnr-electro', name: 'Электрика (ЭОМ)', costPercent: 0.5, timeBase: 0.5 },
        { id: 'pnr-vent', name: 'Вентиляция и кондиционирование (ОВ)', costPercent: 0.8, timeBase: 0.5 },
        { id: 'pnr-weak', name: 'Слаботочные системы и пожарная безопасность (СС и АПС)', costPercent: 0.3, timeBase: 0.3 },
        { id: 'pnr-bms', name: 'Диспетчеризация и автоматика (АК / BMS)', costPercent: 1.0, timeBase: 1.0 },
        { id: 'pnr-smoke', name: 'Противодымная вентиляция и клапаны (ДУ)', costPercent: 0.5, timeBase: 0.5 },
    ];

    let totalCost = 0;
    let maxTime = 0;

    systemCheckboxes.forEach(system => {
        const checkbox = document.getElementById(system.id);
        if (checkbox && checkbox.checked) {
            // Используем значения из справочника, если есть, иначе из объекта
            const percent = getCostPercent(system.name) || system.costPercent;
            const time = getTimeBase(system.name) || system.timeBase;

            totalCost += (smrCost * percent) / 100;

            if (time > maxTime) {
                maxTime = time;
            }
        }
    });

    // Уровни сложности автоматизации:
    // basic — базовая (×1)
    // ems — продвинутая EMS (×1.5 стоимость, +1 мес)
    // bms — полная BMS (×2.5 стоимости, +2 мес)
    if (automation === 'ems') {
        totalCost *= 1.5;
        maxTime += 1;
    } else if (automation === 'bms') {
        totalCost *= 2.5;
        maxTime += 2;
    }

    const finalTime = maxTime > 0 ? maxTime + 0.5 : 0;

    costResultElement.textContent = formatCurrency(totalCost);
    timeResultElement.textContent = finalTime.toFixed(1) + ' мес';
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    fetchPnrDirectories();

    document.getElementById('pnr-smr-cost').addEventListener('input', calculatePnr);
    document.getElementById('pnr-automation').addEventListener('change', calculatePnr);
    document.getElementById('pnr-electro').addEventListener('change', calculatePnr);
    document.getElementById('pnr-vent').addEventListener('change', calculatePnr);
    document.getElementById('pnr-weak').addEventListener('change', calculatePnr);
    document.getElementById('pnr-bms').addEventListener('change', calculatePnr);
    document.getElementById('pnr-smoke').addEventListener('change', calculatePnr);
});
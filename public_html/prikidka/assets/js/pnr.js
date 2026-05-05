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
        { id: 'pnr-electro', name: 'Электрика (ЭОМ)' },
        { id: 'pnr-vent', name: 'Вентиляция и кондиционирование (ОВ)' },
        { id: 'pnr-weak', name: 'Слаботочные системы и пожарная безопасность (СС и АПС)' },
        { id: 'pnr-bms', name: 'Диспетчеризация и автоматика (АК / BMS)' },
    ];

    let totalCost = 0;
    let maxTime = 0;

    systemCheckboxes.forEach(system => {
        const checkbox = document.getElementById(system.id);
        if (checkbox && checkbox.checked) {
            const percent = getCostPercent(system.name);
            totalCost += (smrCost * percent) / 100;

            const time = getTimeBase(system.name);
            if (time > maxTime) {
                maxTime = time;
            }
        }
    });

    // Умное здание: бюджет и сроки * 1.5
    if (automation === 'smart') {
        totalCost *= 1.5;
        maxTime *= 1.5;
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
});
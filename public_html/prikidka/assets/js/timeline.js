// Глобальная переменная для хранения справочников сроков
let timelineDirectories = {};

// Функция для получения данных из API
async function fetchTimelineDirectories() {
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
        timelineDirectories = {
            designBase: data.timeline_design_base || [],
            designMult: data.timeline_design_mult || [],
            buildBase: data.timeline_build_base || [],
            buildMult: data.timeline_build_mult || [],
        };
        populateTimelineSelects();
    } catch (error) {
        console.error('Ошибка при загрузке справочников сроков:', error);
    }
}

// Функция для заполнения выпадающего списка "Тип объекта"
function populateTimelineSelects() {
    const objectTypeSelect = document.getElementById('timeline-object-type');

    if (timelineDirectories.designBase) {
        timelineDirectories.designBase.forEach(item => {
            const option = document.createElement('option');
            option.value = item.name;
            option.textContent = item.name;
            objectTypeSelect.appendChild(option);
        });
    }
}

// Функция для получения базового срока ПИР по типу объекта
function getDesignBase(objectTypeName) {
    const item = timelineDirectories.designBase.find(i => i.name === objectTypeName);
    return item ? parseFloat(item.value) : 0;
}

// Функция для получения множителя ПИР по типу объекта
function getDesignMult(objectTypeName) {
    const item = timelineDirectories.designMult.find(i => i.name === objectTypeName);
    return item ? parseFloat(item.value) : 0;
}

// Функция для получения базового срока СМР по типу объекта
function getBuildBase(objectTypeName) {
    const item = timelineDirectories.buildBase.find(i => i.name === objectTypeName);
    return item ? parseFloat(item.value) : 0;
}

// Функция для получения множителя СМР по типу объекта
function getBuildMult(objectTypeName) {
    const item = timelineDirectories.buildMult.find(i => i.name === objectTypeName);
    return item ? parseFloat(item.value) : 0;
}

// Функция для расчета сроков
function calculateTimeline() {
    const objectTypeSelect = document.getElementById('timeline-object-type');
    const areaInput = document.getElementById('timeline-area');
    const expertiseCheckbox = document.getElementById('timeline-expertise');
    const pirStageSelect = document.getElementById('timeline-pir-stage');
    const undergroundCheckbox = document.getElementById('timeline-underground');
    const designResultElement = document.getElementById('timeline-design-result');
    const expertiseResultElement = document.getElementById('timeline-expertise-result');
    const buildResultElement = document.getElementById('timeline-build-result');
    const totalResultElement = document.getElementById('timeline-total-result');

    const objectTypeName = objectTypeSelect.value;
    const area = parseFloat(areaInput.value) || 0;
    const hasExpertise = expertiseCheckbox.checked;
    const pirStage = pirStageSelect ? pirStageSelect.value : 'sequential';
    const hasUnderground = undergroundCheckbox ? undergroundCheckbox.checked : false;

    const designBase = getDesignBase(objectTypeName);
    const designMult = getDesignMult(objectTypeName);
    const buildBase = getBuildBase(objectTypeName);
    const buildMult = getBuildMult(objectTypeName);

    const baseArea = 1000;
    const effectiveArea = Math.max(area, baseArea);
    const additionalArea = effectiveArea > baseArea ? (effectiveArea - baseArea) / 1000 : 0;

    // Формула ПИР
    let designTime = designBase + (additionalArea * designMult);

    // Параллельное проектирование: срок ПИР * 0.7
    if (pirStage === 'parallel') {
        designTime *= 0.7;
    }

    // Формула СМР
    let buildTime = buildBase + (additionalArea * buildMult);

    // Подземная часть: +3 месяца к стройке
    if (hasUnderground) {
        buildTime += 3;
    }

    // Экспертиза
    const expertiseTime = hasExpertise ? 1.5 : 0;

    // Итоговый срок
    const totalTime = designTime + expertiseTime + buildTime;

    designResultElement.textContent = designTime.toFixed(1) + ' мес';
    expertiseResultElement.textContent = expertiseTime.toFixed(1) + ' мес';
    buildResultElement.textContent = buildTime.toFixed(1) + ' мес';
    totalResultElement.textContent = totalTime.toFixed(1) + ' мес';
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    fetchTimelineDirectories();

    document.getElementById('timeline-object-type').addEventListener('change', calculateTimeline);
    document.getElementById('timeline-area').addEventListener('input', calculateTimeline);
    document.getElementById('timeline-expertise').addEventListener('change', calculateTimeline);
    document.getElementById('timeline-pir-stage').addEventListener('change', calculateTimeline);
    document.getElementById('timeline-underground').addEventListener('change', calculateTimeline);
});
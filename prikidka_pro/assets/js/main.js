// Основная логика дашборда

document.addEventListener('DOMContentLoaded', () => {
    const objectType = document.getElementById('object-type');
    const taskType = document.getElementById('task-type');
    const buildingType = document.getElementById('building-type');
    const objectCard = document.getElementById('object-card');
    const systemsOrbit = document.getElementById('systems-orbit');
    const totalAreaInput = document.getElementById('total-area');
    const volumeInput = document.getElementById('building-volume');
    const floorsInput = document.getElementById('floors-count');
    const toggleModeBtn = document.getElementById('toggle-mode');

    function checkSelection() {
        if (objectType.value && taskType.value && buildingType.value) {
            objectCard.style.display = 'block';
            systemsOrbit.style.display = 'block';
        } else {
            objectCard.style.display = 'none';
            systemsOrbit.style.display = 'none';
        }
    }

    objectType.addEventListener('change', checkSelection);
    taskType.addEventListener('change', checkSelection);
    buildingType.addEventListener('change', checkSelection);

    // Авторасчет объема
    totalAreaInput.addEventListener('input', () => {
        const area = parseFloat(totalAreaInput.value);
        const floors = parseFloat(floorsInput.value);
        if (area && floors) {
            volumeInput.value = (area * floors).toFixed(2);
        }
    });
    floorsInput.addEventListener('input', () => {
        const area = parseFloat(totalAreaInput.value);
        const floors = parseFloat(floorsInput.value);
        if (area && floors) {
            volumeInput.value = (area * floors).toFixed(2);
        }
    });

    // Инициализация кнопок систем
    document.querySelectorAll('.system-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const system = btn.getAttribute('data-system');
            openLabModal(system);
        });
    });

    // Дуальный режим CLI/MGMT
    toggleModeBtn.addEventListener('click', () => {
        document.body.classList.toggle('mgmt-mode');
        toggleModeBtn.textContent = document.body.classList.contains('mgmt-mode') ? 'Режим CLI' : 'Режим MGMT';
    });
});

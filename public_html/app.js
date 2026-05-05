// ══════════════════════════════════════════════════════════════
//  TOC.CHERNETCHENKO.PRO — app.js  v4.7 (Final + GIP Section 5)
//  + Облачное хранение (?id=...)
//  + Ролевая модель (Директор / ГИП)
//  + Встроенный экспорт/импорт CSV
//  + Умный раздел 05 для ГИПа (Ресурсная ведомость)
// ══════════════════════════════════════════════════════════════

let currentTasks       = [];
let ganttInstance      = null;
let currentEditingTaskId = null;
let projectRisks       = [];

// ──────────────────────────────────────────────────────────────
//  МАСШТАБ ГАНТА И РЕЖИМ ПРИЛОЖЕНИЯ
// ──────────────────────────────────────────────────────────────
let currentGanttView = 'Day';

const LS_MODE     = 'toc_app_mode';
const LS_RATES    = 'toc_resource_rates';
const LS_CAPEX    = 'toc_capex_items';
const LS_ECON     = 'toc_economics';
const LS_INITDATA = 'toc_initial_data';

function lsSave(key, val) { try { localStorage.setItem(key, JSON.stringify(val)); } catch(e) {} }
function lsLoad(key, fallback) { try { const v = localStorage.getItem(key); return v ? JSON.parse(v) : fallback; } catch(e) { return fallback; } }

function isGipRole() {
    return new URLSearchParams(window.location.search).get('role') === 'gip';
}

let appMode = lsLoad(LS_MODE, 'simple');

const DEFAULT_RATES = {
    'ГИП':            12000,
    'ГАП':            10000,
    'Архитектор':      7000,
    'Конструктор':     7000,
    'Специалист ТИМ':  8000,
    'Сметчик':         6000,
    'Технолог':        7000,
};

let resourceRates = lsLoad(LS_RATES, DEFAULT_RATES);
let availableResources = Object.keys(resourceRates);
let capexItems = lsLoad(LS_CAPEX, []);

const ECON_FIELDS = [
    'coef_urgency','coef_client','coef_region','coef_extra',
    'oh_print_val','oh_print_type','oh_trip_val','oh_trip_type',
    'oh_siz_val','oh_siz_type','oh_amort_val','oh_amort_type',
    'oh_soft_val','oh_soft_type','oh_tech_val','oh_tech_type',
    'calc-mgmt','calc-profit','calc-tax','calc-advance',
    'exp-checkbox','exp-type','exp-value','exp-calc-type',
    'sbc_base','sbc_index','sbc_stage_pct',
    'kp_payment_terms'
];

function saveEconomics() {
    const data = {};
    ECON_FIELDS.forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        data[id] = el.type === 'checkbox' ? el.checked : el.value;
    });
    lsSave(LS_ECON, data);
}

function loadEconomics() {
    const data = lsLoad(LS_ECON, null);
    if (!data) return;
    ECON_FIELDS.forEach(id => {
        const el = document.getElementById(id);
        if (!el || data[id] === undefined) return;
        if (el.type === 'checkbox') { el.checked = data[id]; }
        else { el.value = data[id]; }
    });
    const expCb = document.getElementById('exp-checkbox');
    if (expCb?.checked) {
        ['exp-type','exp-calc-type'].forEach(id => { const el=document.getElementById(id); if(el) el.style.display='inline-block'; });
        const w = document.getElementById('exp-val-wrapper'); if(w) w.style.display='flex';
    }
}

const INITIAL_DATA_CATALOG = [
    { group: 'Градостроительные', items: [
        { id: 'd_gpzu',    name: 'ГПЗУ (Градостроительный план зем. участка)',        critical: true,  defDelay: 30, defCost: 300000, note: 'ГрК РФ ст. 57.3' },
        { id: 'd_agr',     name: 'АГР / АГО (Архитектурно-градостроительный облик)',  critical: false, defDelay: 14, defCost: 100000, note: 'Для особых зон' },
        { id: 'd_pzz',     name: 'Выписка из ПЗЗ (виды использования, регламенты)',   critical: true,  defDelay: 14, defCost: 150000, note: 'ГрК РФ ст. 30' },
        { id: 'd_genplan', name: 'Схема генерального плана застройщика',              critical: false, defDelay:  7, defCost:  50000, note: 'Для ПЗУ' },
        { id: 'd_krt',     name: 'Документация по КРТ',                               critical: false, defDelay: 21, defCost: 200000, note: 'ГрК РФ ст. 67.1' },
        { id: 'd_opzh',    name: 'Ограничения ООПТ / охранные зоны / сервитуты',      critical: false, defDelay: 14, defCost: 200000, note: 'ЕГРН / ГИСОГД' },
    ]},
    { group: 'Инженерные изыскания', items: [
        { id: 'd_igi',  name: 'ИГИ - Инженерно-геологические изыскания',           critical: true,  defDelay: 45, defCost: 500000, note: 'СП 47.13330' },
        { id: 'd_igeo', name: 'ИГ  - Инженерно-геодезические изыскания',           critical: true,  defDelay: 21, defCost: 250000, note: 'Топоподоснова' },
        { id: 'd_ige',  name: 'ИЭ  - Инженерно-экологические изыскания',           critical: false, defDelay: 30, defCost: 200000, note: 'Экология' },
        { id: 'd_igh',  name: 'ИГМ - Инженерно-гидрометеорологические изыскания', critical: false, defDelay: 30, defCost: 200000, note: 'Подтопления' },
        { id: 'd_iob',  name: 'Обследование несущих конструкций',                  critical: false, defDelay: 21, defCost: 300000, note: 'ГОСТ 31937' },
    ]},
    { group: 'Технические условия', items: [
        { id: 'd_tu_el',  name: 'ТУ на электроснабжение',                           critical: true,  defDelay: 60, defCost: 400000, note: 'ПП РФ № 861' },
        { id: 'd_tu_vk',  name: 'ТУ на водоснабжение и водоотведение',              critical: true,  defDelay: 45, defCost: 300000, note: 'ФЗ № 416' },
        { id: 'd_tu_gas', name: 'ТУ на газоснабжение',                             critical: false, defDelay: 90, defCost: 500000, note: 'ПП РФ № 1149' },
        { id: 'd_tu_tep', name: 'ТУ на теплоснабжение',                            critical: false, defDelay: 45, defCost: 300000, note: 'ФЗ № 190' },
        { id: 'd_tu_ss',  name: 'ТУ на слаботочные сети / связь',                  critical: false, defDelay: 21, defCost: 100000, note: 'Связь' },
        { id: 'd_tu_rd',  name: 'Согласование с ГИБДД / дорожниками',              critical: false, defDelay: 30, defCost: 150000, note: 'Примыкания' },
    ]},
    { group: 'Задание на проектирование', items: [
        { id: 'd_zadanie', name: 'Задание на проектирование (ГК РФ ст. 759)', critical: true,  defDelay: 14, defCost: 200000, note: 'Обязательный документ' },
        { id: 'd_tp',      name: 'Техническое задание (ТЗ) на разделы',      critical: true,  defDelay: 10, defCost: 100000, note: 'Смежники' },
        { id: 'd_bim_ez',  name: 'EIR / Требования к ТИМ',                   critical: false, defDelay:  7, defCost:  80000, note: 'ПП РФ № 331' },
    ]},
    { group: 'Договорные и финансовые', items: [
        { id: 'd_dog',    name: 'Договор подряда (подписан)',                   critical: true,  defDelay:  0, defCost: 1000000, note: 'Основание' },
        { id: 'd_grafin', name: 'График финансирования',                        critical: true,  defDelay: 14, defCost: 300000,  note: 'Оплата' },
    ]}
];

const INITIAL_DATA_FLAT = INITIAL_DATA_CATALOG.flatMap(g => g.items.map(item => ({ ...item, group: g.group, status: 'unknown' })));
let initialDataList = INITIAL_DATA_FLAT.map(item => ({ ...item }));

const SECTIONS_DATA = {
    'И':   { 'ИГ':'Инженерно-геодезические', 'ГИ':'Инженерно-геологические', 'ИЭ':'Инженерно-экологические', 'ОБ':'Обследование' },
    'ЭС':  { 'АР':'Эскизный проект / Концепция', 'ГП':'Генеральный план (ЭП)' },
    'П':   { 'ПЗ':'Раздел 1. Пояснительная записка', 'ПЗУ':'Раздел 2. Схема планировочной организации', 'АР':'Раздел 3. Архитектурные решения', 'КР':'Раздел 4. Конструктивные решения', 'ИОС1':'Раздел 5.1 Электроснабжение', 'ИОС2':'Раздел 5.2 Водоснабжение', 'ИОС3':'Раздел 5.3 Водоотведение', 'ИОС4':'Раздел 5.4 ОВиК', 'ИОС5':'Раздел 5.5 Сети связи', 'ИОС6':'Раздел 5.6 Газоснабжение', 'ПОС':'Раздел 6. Проект организации строительства', 'СЭ':'Раздел 11. Смета на строительство' },
    'Р':   { 'ГП':'Генеральный план', 'АР':'Архитектурные решения', 'КЖ':'Конструкции железобетонные', 'КМ':'Конструкции металлические', 'ВК':'Водопровод и канализация', 'ОВ':'Отопление и вентиляция', 'ЭМ':'Силовое электрооборудование', 'СС':'Системы связи' },
    'ТИМ': { 'СКА':'3D-сканирование', 'МРД':'Моделирование по РД', 'КОР':'BIM-координация' }
};
let currentStage = 'П';

function getLocalDateString(date) { const d = date ? new Date(date) : new Date(); return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`; }
function fmt(n) { return Math.round(n).toLocaleString('ru-RU'); }
function setTxt(id, val) { const el = document.getElementById(id); if (el) el.textContent = fmt(val); }

function calcTaskFot(task) {
    if (task.isMilestone || task.isBuffer) return 0;
    const dur = Math.max(1, Math.ceil((new Date(task.end) - new Date(task.start)) / 86400000) + 1);
    const roles = task.resource ? task.resource.split(',').map(r => r.trim()).filter(Boolean) : [];
    const util = (task.utilization !== undefined ? task.utilization : 100) / 100;
    if (roles.length === 0) {
        const avgRate = Object.values(resourceRates).reduce((a, b) => a + b, 0) / Object.keys(resourceRates).length;
        return dur * avgRate * util;
    }
    return roles.reduce((sum, role) => sum + dur * (resourceRates[role] || 5000) * util, 0);
}

// ──────────────────────────────────────────────────────────────
//  ОБЛАЧНОЕ ХРАНЕНИЕ И ССЫЛКИ ДЛЯ РОЛЕЙ
// ──────────────────────────────────────────────────────────────
window.saveToCloud = async function() {
    const btn = document.getElementById('btn-save-cloud');
    if (!btn) return;
    const origText = btn.textContent;
    btn.textContent = '💾 Загрузка...';
    btn.disabled = true;

    const econData = {};
    ECON_FIELDS.forEach(id => {
        const el = document.getElementById(id);
        if (el) econData[id] = el.type === 'checkbox' ? el.checked : el.value;
    });

    const reqData = {
        client: document.getElementById('req_client')?.value || '',
        object: document.getElementById('req_object')?.value || '',
        num: document.getElementById('req_num')?.value || '',
        date: document.getElementById('req_date')?.value || ''
    };

    const payload = {
        appMode,
        resourceRates,
        capexItems,
        projectRisks,
        currentTasks,
        initialData: initialDataList.map(d => ({ id: d.id, status: d.status })),
        econData,
        reqData
    };

    try {
        const res = await fetch('api_projects.php?action=save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const data = await res.json();

        if (data.status === 'success') {
            const linkZone = document.getElementById('cloud-link-zone');
            const urlBase = window.location.origin + window.location.pathname;
            
            const linkDir = document.getElementById('link-dir');
            const linkGip = document.getElementById('link-gip');
            
            if (linkDir) linkDir.textContent = urlBase + '?id=' + data.id + '&role=dir';
            if (linkGip) linkGip.textContent = urlBase + '?id=' + data.id + '&role=gip';
            
            if (!isGipRole()) {
                if (linkZone) linkZone.style.display = 'flex';
            } else {
                let gipAlert = document.getElementById('gip-success-alert');
                if (!gipAlert) {
                    gipAlert = document.createElement('div');
                    gipAlert.id = 'gip-success-alert';
                    gipAlert.style.cssText = 'background:#e8f4ec; border:1px solid var(--green); border-radius:4px; padding:15px; margin-bottom:20px;';
                    const header = document.querySelector('header');
                    header.parentNode.insertBefore(gipAlert, header.nextSibling);
                }
                const dirUrl = urlBase + '?id=' + data.id + '&role=dir';
                gipAlert.innerHTML = `
                    <div style="font-size:13px; color:var(--navy); font-weight:bold; margin-bottom:8px;">✅ График успешно сохранен!</div>
                    <div style="font-size:12px; color:var(--navy); margin-bottom:8px;">Скопируйте ссылку ниже и отправьте руководителю на проверку:</div>
                    <div style="display:flex; align-items:center; justify-content:space-between; background:#fff; padding:6px 10px; border:1px solid #ccc; border-radius:3px;">
                        <code style="font-size:12px; color:var(--navy); user-select:all;">${dirUrl}</code>
                        <button class="btn btn-outline" onclick="navigator.clipboard.writeText('${dirUrl}').then(()=>alert('Скопировано!'))" style="font-size:11px; padding:3px 10px; border-color:var(--green); color:var(--green);">Копировать</button>
                    </div>
                `;
                gipAlert.style.display = 'block';
            }
            
            const currentRole = new URLSearchParams(window.location.search).get('role');
            let newUrl = '?id=' + data.id;
            if (currentRole) newUrl += '&role=' + currentRole;
            window.history.replaceState(null, '', newUrl);
        } else {
            alert('Ошибка сохранения: ' + data.message);
        }
    } catch (err) {
        alert('Сбой сети при сохранении: ' + err.message);
    }

    btn.textContent = origText;
    btn.disabled = false;
};

// ──────────────────────────────────────────────────────────────
//  ИНИЦИАЛИЗАЦИЯ И МАРШРУТИЗАЦИЯ
// ──────────────────────────────────────────────────────────────
async function initApp() {
    const urlParams = new URLSearchParams(window.location.search);
    const projectId = urlParams.get('id');
    const isGip = isGipRole();

    if (projectId) {
        try {
            const res = await fetch('api_projects.php?action=load&id=' + projectId);
            if (!res.ok) throw new Error('Проект не найден на сервере');
            const data = await res.json();
            
            if (data.appMode) appMode = data.appMode;
            if (data.resourceRates) { resourceRates = data.resourceRates; availableResources = Object.keys(resourceRates); }
            if (data.capexItems) capexItems = data.capexItems;
            if (data.projectRisks) projectRisks = data.projectRisks;
            if (data.currentTasks) currentTasks = data.currentTasks;
            
            if (data.initialData) {
                data.initialData.forEach(saved => {
                    const d = initialDataList.find(x => x.id === saved.id);
                    if (d) d.status = saved.status;
                });
            }

            if (data.econData) {
                ECON_FIELDS.forEach(id => {
                    const el = document.getElementById(id);
                    if (el && data.econData[id] !== undefined) {
                        if (el.type === 'checkbox') el.checked = data.econData[id];
                        else el.value = data.econData[id];
                    }
                });
                const expCb = document.getElementById('exp-checkbox');
                if (expCb?.checked) {
                    ['exp-type','exp-calc-type'].forEach(id => { const el=document.getElementById(id); if(el) el.style.display='inline-block'; });
                    const w = document.getElementById('exp-val-wrapper'); if(w) w.style.display='flex';
                }
            }

            if (data.reqData) {
                if (document.getElementById('req_client')) document.getElementById('req_client').value = data.reqData.client || '';
                if (document.getElementById('req_object')) document.getElementById('req_object').value = data.reqData.object || '';
                if (document.getElementById('req_num')) document.getElementById('req_num').value = data.reqData.num || '';
                if (document.getElementById('req_date')) document.getElementById('req_date').value = data.reqData.date || '';
            }

            const linkZone = document.getElementById('cloud-link-zone');
            if (linkZone && !isGip) {
                const urlBase = window.location.origin + window.location.pathname;
                if (document.getElementById('link-dir')) document.getElementById('link-dir').textContent = urlBase + '?id=' + projectId + '&role=dir';
                if (document.getElementById('link-gip')) document.getElementById('link-gip').textContent = urlBase + '?id=' + projectId + '&role=gip';
                linkZone.style.display = 'flex';
            }

            bootstrapUI(false); 

        } catch (e) {
            alert('Сбой загрузки проекта из облака: ' + e.message);
            bootstrapUI(true);
        }
    } else {
        (function() { const s = lsLoad(LS_INITDATA, null); if (s && typeof s === 'object') initialDataList.forEach(d => { if(s[d.id]) d.status = s[d.id]; }); })();
        loadEconomics();
        bootstrapUI(true);
    }
}

function bootstrapUI(loadFromGanttApi) {
    const modeSelect = document.getElementById('app-mode-select');
    if (modeSelect) modeSelect.value = appMode;
    applyAppMode();
    initSections();
    renderInitialData();
    renderRates();          
    renderCapex();          
    renderCapexPresets();   
    setupModalListeners();
    setupDocParser();
    setupButtons();
    
    if (loadFromGanttApi) {
        refreshApp(); 
    } else {
        syncBufferTask();
        renderUI();
        renderRisks();
    }
}

document.addEventListener('DOMContentLoaded', initApp);

// ──────────────────────────────────────────────────────────────
//  РЕЖИМЫ (СМЕТА / ТОС) И РОЛИ (ДИРЕКТОР / ГИП)
// ──────────────────────────────────────────────────────────────
window.toggleAppMode = function() {
    appMode = document.getElementById('app-mode-select').value;
    lsSave(LS_MODE, appMode);
    applyAppMode();
    renderUI(); 
    renderEconomics();
};

function applyAppMode() {
    const isToc = (appMode === 'toc');
    const isGip = isGipRole();
    
    // В простом режиме убираем риски, но для ГИПа Исходные данные и Гант оставляем всегда
    const displayGantt = (isToc || isGip) ? 'block' : 'none';
    const displayRisks = isToc ? 'block' : 'none';
    
    const elInit = document.getElementById('sec-initial-data'); if(elInit) elInit.style.display = displayGantt;
    const elGantt = document.getElementById('gantt-viz-zone'); if(elGantt) elGantt.style.display = displayGantt;
    const elRisks = document.getElementById('sec-risks'); if(elRisks) elRisks.style.display = displayRisks;

    const secCapex = document.getElementById('sec-capex');
    if (secCapex) secCapex.style.display = (!isToc || isGip) ? 'none' : 'block';

    ['row-toc-buffer', 'row-toc-risk'].forEach(id => { const el = document.getElementById(id); if (el) el.style.display = isToc ? 'table-row' : 'none'; });

    const btb = document.getElementById('block-toc-buffer'); if (btb) btb.style.display = isToc ? 'flex' : 'none';
    const titleTasks = document.getElementById('title-tasks'); if (titleTasks) titleTasks.textContent = isToc ? '03 — Календарный график и Ресурсы (ТОС)' : '03 — Ресурсная ведомость';
    const badge = document.getElementById('badge-toc-buffer'); if (badge) badge.style.display = isToc ? 'inline' : 'none';
    const thDep = document.querySelector('.col-dependency'); if (thDep) thDep.style.display = isToc ? 'table-cell' : 'none';

    // ⚡ РОЛЕВАЯ МОДЕЛЬ (Скрываем финансы для ГИПа)
    const secTotals = document.getElementById('sec-totals');
    if (isGip) {
        // Скрываем финансовые разделы
        ['sec-rates', 'sec-coefficients', 'sec-sbc'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.style.display = 'none';
        });
        
        const fotTh = document.getElementById('col-fot-th');
        if (fotTh) fotTh.style.display = 'none';
        
        const modeSwitcher = document.getElementById('app-mode-select');
        if (modeSwitcher && modeSwitcher.parentElement) modeSwitcher.parentElement.style.display = 'none';
        
        const cloudLinkZone = document.getElementById('cloud-link-zone');
        if (cloudLinkZone) cloudLinkZone.style.display = 'none';

        // Переделываем Раздел 05 под нужды ГИПа
        if (secTotals) {
            secTotals.style.display = 'block'; // Оставляем блок видимым
            
            // Скрываем финансовую таблицу и чаты ИИ
            const body = secTotals.querySelector('.section-body');
            if (body) body.style.display = 'none';
            const aiExp = document.getElementById('ai-explanation');
            if (aiExp) aiExp.style.display = 'none';
            const chatInp = document.getElementById('chat-input');
            if (chatInp && chatInp.parentElement && chatInp.parentElement.parentElement) chatInp.parentElement.parentElement.style.display = 'none';
            
            // Меняем заголовок
            const title = secTotals.querySelector('.section-title span:first-child');
            if (title) title.textContent = '05 — Экспорт и передача графика';

            // Меняем текст кнопки скачивания
            const btnDl = document.getElementById('btn-dl-calc');
            if (btnDl) btnDl.textContent = '↓ Скачать ресурсную ведомость (CSV)';

            // Добавляем красивую инструкцию для ГИПа (если еще нет)
            let gipInfo = document.getElementById('gip-section5-info');
            if (!gipInfo) {
                gipInfo = document.createElement('div');
                gipInfo.id = 'gip-section5-info';
                gipInfo.style.cssText = 'padding:20px; background:#e8f4ec; border:1px solid var(--green); border-radius:4px; text-align:center; color:var(--navy); font-size:14px; margin-top:15px;';
                gipInfo.innerHTML = '<b>Работа с графиком завершена!</b><br><br>Не забудьте нажать зеленую кнопку <b>«💾 В облако»</b> в самом верху страницы, чтобы передать готовый график руководителю.<br><br>Вы также можете скачать Ресурсную ведомость для себя (кнопка выше).';
                secTotals.appendChild(gipInfo);
            }
            gipInfo.style.display = 'block';
        }

    } else {
        // Восстанавливаем оригинальный вид Раздела 05 для Директора
        if (secTotals) {
            secTotals.style.display = 'block';
            const title = secTotals.querySelector('.section-title span:first-child');
            if (title) title.textContent = '05 — Итоговый расчёт стоимости';
            const body = secTotals.querySelector('.section-body');
            if (body) body.style.display = 'block';
            
            const chatInp = document.getElementById('chat-input');
            if (chatInp && chatInp.parentElement && chatInp.parentElement.parentElement) chatInp.parentElement.parentElement.style.display = 'block';
            
            const btnDl = document.getElementById('btn-dl-calc');
            if (btnDl) btnDl.textContent = '↓ Скачать расчёт (CSV)';
            
            const gipInfo = document.getElementById('gip-section5-info');
            if (gipInfo) gipInfo.style.display = 'none';
        }
    }
}

// ──────────────────────────────────────────────────────────────
//  ФУНКЦИИ СТАВОК (RATES)
// ──────────────────────────────────────────────────────────────
function renderRates() {
    const tbody = document.getElementById('rates-tbody');
    if (!tbody) return;
    let html = '';
    availableResources.forEach(role => {
        html += `<tr>
            <td style="padding:6px 10px;font-size:13px;">${role}</td>
            <td style="padding:4px 8px;">
                <div style="display:flex;align-items:center;gap:4px;">
                    <input type="number" value="${resourceRates[role] || 5000}" min="0" step="500"
                        class="inline-input" style="width:90px;text-align:right;"
                        onchange="updateRate('${role}', this.value)">
                    <span style="font-size:11px;color:var(--muted);">₽/чел-день</span>
                </div>
            </td>
            <td style="padding:4px 8px;text-align:right;font-size:12px;color:var(--muted);">
                ~${fmt((resourceRates[role]||5000)*22)} ₽/мес
            </td>
            <td style="padding:4px 8px;text-align:center;">
                <button onclick="deleteRole('${role}')" class="btn btn-outline" style="padding:2px 8px;color:var(--red);border-color:var(--red);">✕</button>
            </td>
        </tr>`;
    });
    tbody.innerHTML = html;
}

window.updateRate = function(role, val) {
    resourceRates[role] = parseFloat(val) || 0;
    lsSave(LS_RATES, resourceRates);
    renderRates();
    renderUI();
};

window.deleteRole = function(role) {
    delete resourceRates[role];
    availableResources = availableResources.filter(r => r !== role);
    lsSave(LS_RATES, resourceRates);
    renderRates();
    renderUI();
};

window.addRole = function() {
    const nameEl = document.getElementById('new-role-name');
    const rateEl = document.getElementById('new-role-rate');
    const name = nameEl?.value.trim();
    const rate = parseFloat(rateEl?.value) || 5000;
    if (!name) return;
    if (!availableResources.includes(name)) availableResources.push(name);
    resourceRates[name] = rate;
    lsSave(LS_RATES, resourceRates);
    if (nameEl) nameEl.value = '';
    renderRates();
};

window.importRates = function(rows) {
    rows.forEach((row, i) => {
        if (i === 0) return; 
        const role = (row[0] || '').trim();
        const rate = parseFloat(row[1]) || 0;
        if (!role) return;
        if (!availableResources.includes(role)) availableResources.push(role);
        resourceRates[role] = rate;
    });
    lsSave(LS_RATES, resourceRates);
    renderRates();
    renderUI();
};

// ──────────────────────────────────────────────────────────────
//  ФУНКЦИИ КАПЗАТРАТ (CAPEX)
// ──────────────────────────────────────────────────────────────
const CAPEX_PRESETS = [
    { name: 'Autodesk Revit (лицензия)',     cost: 180000, life: 3, type: 'software' },
    { name: 'AutoCAD (лицензия)',             cost: 90000,  life: 3, type: 'software' },
    { name: 'Navisworks (лицензия)',          cost: 120000, life: 3, type: 'software' },
    { name: 'Рабочая станция (ПК)',           cost: 150000, life: 5, type: 'hardware' },
    { name: 'Ноутбук проектировщика',         cost: 120000, life: 4, type: 'hardware' },
    { name: '3D-сканер (аренда, мес)',        cost: 80000,  life: 1, type: 'rent' },
    { name: 'Плоттер (покупка)',              cost: 200000, life: 7, type: 'hardware' },
    { name: 'Сервер/NAS (хранилище)',         cost: 250000, life: 5, type: 'hardware' },
    { name: 'MS Office (лицензия)',           cost: 15000,  life: 1, type: 'software' },
    { name: 'Антивирус + безопасность',       cost: 8000,   life: 1, type: 'software' },
];

function renderCapex() {
    const tbody = document.getElementById('capex-tbody');
    if (!tbody) return;
    if (capexItems.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;color:var(--muted);padding:14px;font-size:13px;">
            Добавьте позиции через «+ Из списка» или «+ Своя позиция»
        </td></tr>`;
        renderEconomics();
        return;
    }
    let html = '';
    let totalAmort = 0;
    capexItems.forEach((item, idx) => {
        const projectMonths = getProjectMonths();
        const amortMonthly = item.cost / Math.max(1, item.life) / 12;
        const amortProject = amortMonthly * projectMonths;
        totalAmort += amortProject;
        html += `<tr>
            <td style="padding:6px 10px;font-size:12px;">${item.name}</td>
            <td style="padding:4px 8px;text-align:right;font-size:12px;">${fmt(item.cost)} ₽</td>
            <td style="padding:4px 8px;text-align:center;font-size:12px;">
                <input type="number" value="${item.life}" min="1" max="20" class="inline-input" style="width:45px;text-align:center;"
                    onchange="updateCapexLife(${idx}, this.value)"> лет
            </td>
            <td style="padding:4px 8px;text-align:right;font-size:12px;color:var(--muted);">${fmt(amortMonthly)} ₽/мес</td>
            <td style="padding:4px 8px;text-align:right;font-size:12px;font-weight:600;color:var(--navy);">${fmt(amortProject)} ₽</td>
            <td style="padding:4px 8px;text-align:center;">
                <button onclick="removeCapex(${idx})" class="btn btn-outline" style="padding:2px 8px;color:var(--red);border-color:var(--red);">✕</button>
            </td>
        </tr>`;
    });
    html += `<tr style="background:#f5f3f0;font-weight:700;border-top:2px solid var(--border);">
        <td colspan="4" style="padding:8px 10px;font-size:12px;">Итого амортизация за проект:</td>
        <td style="padding:8px 10px;text-align:right;color:var(--navy);">${fmt(totalAmort)} ₽</td>
        <td></td>
    </tr>`;
    tbody.innerHTML = html;
    const amortInput = document.getElementById('oh_amort_val');
    if (amortInput) { amortInput.value = Math.round(totalAmort); renderEconomics(); }
    else renderEconomics();
}

function getProjectMonths() {
    const normal = currentTasks.filter(t => !t.isBuffer && !t.isMilestone);
    if (normal.length < 2) return 1;
    const starts = normal.map(t => new Date(t.start)).filter(d => !isNaN(d));
    const ends   = normal.map(t => new Date(t.end)).filter(d => !isNaN(d));
    if (!starts.length || !ends.length) return 1;
    const diffMs = Math.max(...ends.map(d=>d.getTime())) - Math.min(...starts.map(d=>d.getTime()));
    return Math.max(1, Math.ceil(diffMs / 1000 / 60 / 60 / 24 / 30));
}

window.addCapexFromPreset = function(idx) {
    const p = CAPEX_PRESETS[idx];
    capexItems.push({ ...p });
    lsSave(LS_CAPEX, capexItems);
    renderCapex();
};

window.addCapexCustom = function() {
    const nameEl = document.getElementById('capex-custom-name');
    const costEl = document.getElementById('capex-custom-cost');
    const lifeEl = document.getElementById('capex-custom-life');
    const name = nameEl?.value.trim();
    const cost = parseFloat(costEl?.value) || 0;
    const life = parseFloat(lifeEl?.value) || 3;
    if (!name || !cost) return alert('Введите название и стоимость');
    capexItems.push({ name, cost, life, type: 'custom' });
    if (nameEl) nameEl.value = '';
    if (costEl) costEl.value = '';
    lsSave(LS_CAPEX, capexItems);
    renderCapex();
};

window.removeCapex = function(idx) {
    capexItems.splice(idx, 1);
    lsSave(LS_CAPEX, capexItems);
    renderCapex();
};

window.updateCapexLife = function(idx, val) {
    capexItems[idx].life = parseFloat(val) || 1;
    lsSave(LS_CAPEX, capexItems);
    renderCapex();
};

window.toggleCapexPresets = function() {
    const el = document.getElementById('capex-presets-list');
    if (el) el.style.display = el.style.display === 'none' ? 'block' : 'none';
};

function renderCapexPresets() {
    const el = document.getElementById('capex-presets-list');
    if (!el) return;
    el.innerHTML = CAPEX_PRESETS.map((p, i) =>
        `<div style="display:flex;justify-content:space-between;align-items:center;padding:6px 10px;border-bottom:1px solid #eee;font-size:12px;">
            <span>${p.name} <span style="color:var(--muted);">${fmt(p.cost)} ₽ · ${p.life} лет</span></span>
            <button onclick="addCapexFromPreset(${i})" class="btn btn-outline" style="padding:2px 10px;font-size:11px;">+ Добавить</button>
        </div>`
    ).join('');
}

// ──────────────────────────────────────────────────────────────
//  ПАРСИНГ ДОКУМЕНТОВ
// ──────────────────────────────────────────────────────────────
function setupDocParser() {
    const fileInput = document.getElementById('parse-file-input');
    const statusEl = document.getElementById('parse-status');
    if (!fileInput || !statusEl) return;

    fileInput.addEventListener('change', async (e) => {
        const file = e.target.files[0];
        if (!file) return;

        statusEl.innerHTML = 'Читаю файл...';
        try {
            let extractedText = '';
            if (file.name.endsWith('.txt')) {
                extractedText = await file.text();
            } else if (file.name.endsWith('.docx')) {
                const arrayBuffer = await file.arrayBuffer();
                const result = await mammoth.extractRawText({ arrayBuffer: arrayBuffer });
                extractedText = result.value;
            } else if (file.name.endsWith('.pdf')) {
                if (typeof pdfjsLib === 'undefined') throw new Error('PDF.js не загружена');
                const arrayBuffer = await file.arrayBuffer();
                const pdf = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
                let numPages = Math.min(pdf.numPages, 3); 
                for (let i = 1; i <= numPages; i++) {
                    const page = await pdf.getPage(i);
                    const content = await page.getTextContent();
                    extractedText += content.items.map(item => item.str).join(' ') + '\n';
                }
            } else throw new Error('Формат не поддерживается');

            extractedText = extractedText.trim().substring(0, 4000);
            if (!extractedText) throw new Error('Файл пуст или скан.');

            statusEl.innerHTML = 'Анализирую нейросетью...';
            const response = await fetch('api_chat.php', {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'parse_doc', text: extractedText })
            });
            const data = await response.json();
            
            if (data.explanation) {
                const parsed = JSON.parse(data.explanation);
                let found = false;
                if (parsed.client && parsed.client !== "Не найдено") { document.getElementById('req_client').value = parsed.client; found = true; }
                if (parsed.object && parsed.object !== "Не найдено") { document.getElementById('req_object').value = parsed.object; found = true; }
                
                if (found) { statusEl.innerHTML = '<span style="color:var(--green);">✓ Успешно!</span>'; saveEconomics(); } 
                else statusEl.innerHTML = '<span style="color:var(--orange);">Данные не найдены.</span>';
            } else throw new Error(data.error?.message || 'Ошибка API');
        } catch (err) { statusEl.innerHTML = '<span style="color:var(--red);">Ошибка: ' + err.message + '</span>'; }
        fileInput.value = '';
    });
}

// ──────────────────────────────────────────────────────────────
//  КНОПКИ И ГЛОБАЛЬНЫЕ СЛУШАТЕЛИ
// ──────────────────────────────────────────────────────────────
function setupButtons() {
    document.querySelectorAll('.stage-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            document.querySelectorAll('.stage-btn').forEach(b => { b.classList.remove('active'); b.style.cssText=''; });
            e.target.classList.add('active'); e.target.style.background='var(--green)'; e.target.style.color='#fff'; e.target.style.borderColor='var(--green)';
            currentStage = e.target.dataset.stage; initSections();
        });
    });

    document.getElementById('btn-sel-all')?.addEventListener('click', () => document.querySelectorAll('.sec-cb').forEach(cb=>cb.checked=true));
    document.getElementById('btn-desel-all')?.addEventListener('click', () => document.querySelectorAll('.sec-cb').forEach(cb=>cb.checked=false));

    document.querySelectorAll('.btn-gantt-view').forEach(btn => {
        btn.addEventListener('click', (e) => {
            document.querySelectorAll('.btn-gantt-view').forEach(b => { b.classList.remove('active'); b.style.background = 'transparent'; b.style.color = 'var(--navy)'; });
            e.target.classList.add('active'); e.target.style.background = 'var(--green)'; e.target.style.color = '#fff';
            currentGanttView = e.target.dataset.view;
            if (ganttInstance) ganttInstance.change_view_mode(currentGanttView);
        });
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('[id^="res-drop-"]') && !e.target.classList.contains('btn-res-toggle')) {
            document.querySelectorAll('[id^="res-drop-"]').forEach(d => d.style.display='none');
        }
    });

    document.getElementById('btn-sync-tasks')?.addEventListener('click', () => {
        const sel = document.querySelectorAll('.sec-cb:checked'); if(!sel.length) return alert('Отметьте разделы.');
        const today = getLocalDateString(); let added=0;
        sel.forEach(cb => {
            if (!currentTasks.find(t=>t.name.startsWith(`[${cb.dataset.code}]`))) {
                currentTasks.push({ id:'Task_'+Date.now()+Math.floor(Math.random()*1000), name:`[${cb.dataset.code}] ${cb.dataset.name}`, start:today, end:today, progress:0, dependencies:[], resource:'', utilization:100 }); added++;
            }
        });
        if(added>0){ cascadeDates(); syncBufferTask(); saveToServer(currentTasks); } else alert('Уже в графике.');
    });

    document.getElementById('btn-add-task')?.addEventListener('click', () => { currentTasks.push({ id:'Task_'+Date.now(), name:'Новая задача', start:getLocalDateString(), end:getLocalDateString(), progress:0, dependencies:[], resource:'', utilization:100 }); cascadeDates(); syncBufferTask(); saveToServer(currentTasks); });
    document.getElementById('btn-add-milestone')?.addEventListener('click', () => { currentTasks.push({ id:'Milestone_'+Date.now(), name:'◆ Новая веха', start:getLocalDateString(), end:getLocalDateString(), progress:0, dependencies:[], resource:'', utilization:0, isMilestone:true }); cascadeDates(); syncBufferTask(); saveToServer(currentTasks); });
    
    document.getElementById('btn-reset-all')?.addEventListener('click', () => {
        if(!confirm('Удалить всё?')) return;
        currentTasks=[]; projectRisks=[]; capexItems=[]; initialDataList.forEach(d=>d.status='unknown');
        [LS_RATES, LS_CAPEX, LS_ECON, LS_INITDATA].forEach(k=>localStorage.removeItem(k));
        resourceRates={...DEFAULT_RATES}; availableResources=Object.keys(resourceRates);
        document.querySelectorAll('.sec-cb').forEach(cb=>cb.checked=false);
        renderInitialData(); renderCapex(); renderRates(); renderUI(); renderRisks(); 
        
        window.history.replaceState(null, '', window.location.pathname);
        const clz = document.getElementById('cloud-link-zone');
        if(clz) clz.style.display = 'none';

        saveToServer(currentTasks);
    });

    document.getElementById('btn-toggle-mgmt')?.addEventListener('click', () => {
        const i=currentTasks.findIndex(t=>t.isManagement);
        if(i>-1) currentTasks.splice(i,1); else currentTasks.unshift({ id:'Task_Mgmt_'+Date.now(), name:'[УП] Управление проектом', start:getLocalDateString(), end:getLocalDateString(), progress:0, dependencies:[], resource:'ГИП', utilization:100, isManagement:true });
        cascadeDates(); syncBufferTask(); saveToServer(currentTasks);
    });

    document.getElementById('btn-add-manual-sec')?.addEventListener('click', () => {
        const c=document.getElementById('manual-sec-code').value.trim(), n=document.getElementById('manual-sec-name').value.trim();
        if(!c||!n) return alert('Введите шифр и название.');
        currentTasks.push({ id:'Task_'+Date.now(), name:`[${c}] ${n}`, start:getLocalDateString(), end:getLocalDateString(), progress:0, dependencies:[], resource:'', utilization:100 });
        document.getElementById('manual-sec-code').value=''; document.getElementById('manual-sec-name').value=''; cascadeDates(); syncBufferTask(); saveToServer(currentTasks);
    });

    document.getElementById('btn-add-risk')?.addEventListener('click', () => { projectRisks.push({ id:'Risk_'+Date.now(), desc:'Новый риск...', prob:2, impact:2, cost:100000, delay:5 }); syncBufferTask(); renderUI(); renderRisks(); });

    document.getElementById('exp-checkbox')?.addEventListener('change', e => {
        const s=e.target.checked; ['exp-type','exp-calc-type'].forEach(id=>{const el=document.getElementById(id);if(el)el.style.display=s?'inline-block':'none';});
        const w=document.getElementById('exp-val-wrapper'); if(w) w.style.display=s?'flex':'none'; renderEconomics(); saveEconomics();
    });
    
    document.getElementById('exp-type')?.addEventListener('change', e => {
        if(document.getElementById('exp-calc-type')?.value==='pct') { const v=document.getElementById('exp-value'); if(v) v.value=e.target.value==='gge'?20:e.target.value==='gos'?15:10; }
        renderEconomics(); saveEconomics();
    });

    const ecoIds=['exp-value','sbc_base','sbc_index','sbc_stage_pct','coef_urgency','coef_client','coef_region','coef_extra','oh_print_val','oh_print_type','oh_trip_val','oh_trip_type','oh_siz_val','oh_siz_type','oh_amort_val','oh_amort_type','oh_soft_val','oh_soft_type','oh_tech_val','oh_tech_type','calc-mgmt','calc-profit','calc-tax','calc-advance','exp-calc-type'];
    ecoIds.forEach(id => { const el=document.getElementById(id); if(el) { el.addEventListener('input',()=>{renderEconomics();saveEconomics();}); el.addEventListener('change',()=>{renderEconomics();saveEconomics();}); } });
    document.getElementById('kp_payment_terms')?.addEventListener('input', saveEconomics);

    // ВСТРОЕННЫЙ ПАРСЕР CSV ДЛЯ ИМПОРТА СТАВОК
    document.getElementById('csv-import-rates')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(event) {
            const text = event.target.result;
            const rows = text.split('\n').map(row => row.trim()).filter(row => row.length > 0).map(row => {
                if (row.includes(';')) return row.split(';');
                return row.split(',');
            });
            window.importRates(rows);
        };
        reader.readAsText(file);
        e.target.value = ''; 
    });

    // ⚡ ЭКСПОРТ РЕСУРСНОЙ ВЕДОМОСТИ В CSV (Раздел 03)
    document.getElementById('btn-export-tasks')?.addEventListener('click', () => {
        if (!currentTasks || currentTasks.length === 0) return alert('График пуст');
        
        const isGip = isGipRole();
        const headers = ['№', 'Наименование задачи', 'Предшественник (№)', 'Исполнители', 'Загрузка (%)', 'Начало', 'Конец', 'Длительность (дн.)'];
        if (!isGip) headers.push('ФОТ (руб.)');

        const rows = [headers];
        let totalFot = 0;

        currentTasks.forEach((t, i) => {
            const dur = t.isMilestone || t.isBuffer ? 0 : Math.max(1, Math.ceil((new Date(t.end) - new Date(t.start)) / 86400000) + 1);
            const tf = calcTaskFot(t);
            totalFot += tf;
            
            const deps = t.dependencies ? t.dependencies.map(dId => {
                const idx = currentTasks.findIndex(x => x.id === dId);
                return idx > -1 ? (idx + 1) : '';
            }).filter(Boolean).join(', ') : '';
            
            const row = [
                i + 1,
                t.name,
                deps,
                t.resource || '',
                t.isBuffer ? 0 : Math.round(t.utilization || 100),
                t.start,
                t.end,
                t.isBuffer ? 0 : dur
            ];
            
            if (!isGip) row.push(Math.round(tf));
            rows.push(row);
        });

        if (!isGip) {
            rows.push(['', 'ИТОГО ФОТ ПО ГРАФИКУ:', '', '', '', '', '', '', Math.round(totalFot)]);
        }

        let csvContent = "data:text/csv;charset=utf-8,\uFEFF" + rows.map(e => e.map(i => `"${String(i).replace(/"/g, '""')}"`).join(";")).join("\n");
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "Ресурсная_ведомость.csv");
        document.body.appendChild(link);
        link.click();
        link.remove();
    });

    // ⚡ УМНЫЙ ЭКСПОРТ РАСЧЕТА ИЗ 5-ГО РАЗДЕЛА
    document.getElementById('btn-dl-calc')?.addEventListener('click', () => {
        const isGip = isGipRole();
        
        // ЕСЛИ ЭТО ГИП -> ОН ПРОСТО СКАЧИВАЕТ ГРАФИК
        if (isGip) {
            document.getElementById('btn-export-tasks').click();
            return;
        }
        
        // ЕСЛИ ЭТО ДИРЕКТОР -> КАЧАЕМ КОМБИНИРОВАННЫЙ ОТЧЕТ (График + Смета)
        const rows = [];
        
        // Часть 1: График
        rows.push(['--- ДЕТАЛЬНЫЙ ГРАФИК И РЕСУРСЫ ---']);
        rows.push(['№', 'Наименование задачи', 'Предшественник (№)', 'Исполнители', 'Загрузка (%)', 'Начало', 'Конец', 'Длительность (дн.)', 'ФОТ (руб.)']);

        let totalFot = 0;
        currentTasks.forEach((t, i) => {
            const dur = t.isMilestone || t.isBuffer ? 0 : Math.max(1, Math.ceil((new Date(t.end) - new Date(t.start)) / 86400000) + 1);
            const tf = calcTaskFot(t);
            totalFot += tf;
            
            const deps = t.dependencies ? t.dependencies.map(dId => {
                const idx = currentTasks.findIndex(x => x.id === dId);
                return idx > -1 ? (idx + 1) : '';
            }).filter(Boolean).join(', ') : '';
            
            rows.push([ i + 1, t.name, deps, t.resource || '', t.isBuffer ? 0 : Math.round(t.utilization || 100), t.start, t.end, t.isBuffer ? 0 : dur, Math.round(tf) ]);
        });
        rows.push(['', 'ИТОГО ФОТ ПО ГРАФИКУ:', '', '', '', '', '', '', Math.round(totalFot)]);

        // Часть 2: Смета
        rows.push(['']);
        rows.push(['--- СВОДНАЯ СМЕТА ---']);
        rows.push(['Статья', 'Значение']);
        rows.push(['ФОТ по графику, ₽', (document.getElementById('res_fot')?.textContent || '0').replace(/\s+/g, '')]);
        rows.push(['С коэффициентами, ₽', (document.getElementById('res_coeffs')?.textContent || '0').replace(/\s+/g, '')]);
        rows.push(['Накладные расходы, ₽', (document.getElementById('res_extra')?.textContent || '0').replace(/\s+/g, '')]);
        rows.push(['Управление проектом, ₽', (document.getElementById('res_mgmt')?.textContent || '0').replace(/\s+/g, '')]);
        rows.push(['Фонд рисков EMV, ₽', (document.getElementById('res_risk_fund')?.textContent || '0').replace(/\s+/g, '')]);
        rows.push(['Маржа, ₽', (document.getElementById('res_margin')?.textContent || '0').replace(/\s+/g, '')]);
        rows.push(['ИТОГО без НДС, ₽', (document.getElementById('res_total_no_vat')?.textContent || '0').replace(/\s+/g, '')]);
        rows.push(['НДС, ₽', (document.getElementById('res_vat')?.textContent || '0').replace(/\s+/g, '')]);
        rows.push(['ИТОГО с НДС, ₽', (document.getElementById('res_total')?.textContent || '0').replace(/\s+/g, '')]);
        rows.push(['Аванс, ₽', (document.getElementById('res_advance')?.textContent || '0').replace(/\s+/g, '')]);
        rows.push(['Остаток, ₽', (document.getElementById('res_remainder')?.textContent || '0').replace(/\s+/g, '')]);
        rows.push(['Срок выполнения', document.getElementById('kp_duration_txt')?.value || '']);

        let csvContent = "data:text/csv;charset=utf-8,\uFEFF" + rows.map(e => e.map(i => `"${String(i).replace(/"/g, '""')}"`).join(";")).join("\n");
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "расчет_ПИР_полный.csv");
        document.body.appendChild(link);
        link.click();
        link.remove();
    });
}

// ──────────────────────────────────────────────────────────────
//  ИСХОДНЫЕ ДАННЫЕ И РИСКИ
// ──────────────────────────────────────────────────────────────
function renderInitialData() {
    const grid = document.getElementById('initial-data-grid'); if(!grid) return;
    const c={unknown:'#888',ok:'var(--green)',wait:'var(--gold)',missing:'var(--red)',na:'#aaa'}, bg={unknown:'#f9f9f9',ok:'#f0fbf4',wait:'#fffbe6',missing:'#fff5f5',na:'#f5f5f5'};
    let html='';
    INITIAL_DATA_CATALOG.forEach(g => {
        html += `<div style="grid-column:1/-1;margin:8px 0 4px;font-size:11px;font-weight:700;text-transform:uppercase;color:var(--muted);border-bottom:1px solid var(--border);padding-bottom:4px;">${g.group}</div>`;
        g.items.forEach(def => {
            const d=initialDataList.find(x=>x.id===def.id); if(!d) return; const st=d.status;
            html += `<div data-doc-id="${d.id}" style="display:flex;gap:10px;align-items:flex-start;background:${bg[st]};border:1px solid ${st==='missing'?'var(--red)':'var(--border)'};border-left:3px solid ${c[st]};border-radius:4px;padding:9px 12px;">
                <div style="flex:1;min-width:0;">
                    <div style="font-size:12px;font-weight:600;color:var(--navy);line-height:1.3;">${d.name}${d.critical?' <span style="color:var(--red);font-size:10px;">●&nbsp;Критичный</span>':''}</div>
                    <div style="font-size:10px;color:var(--muted);margin-top:2px;">${d.note||''}</div>
                </div>
                <select onchange="updateDocStatus('${d.id}',this.value)" class="std-select" style="flex-shrink:0;width:130px;padding:5px 6px;font-size:11px;border-color:${c[st]};color:${c[st]};font-weight:600;">
                    <option value="unknown" ${st==='unknown'?'selected':''}>Неизвестно</option><option value="ok" ${st==='ok'?'selected':''}>✓ В наличии</option><option value="wait" ${st==='wait'?'selected':''}>⏳ Ожидаем</option><option value="missing" ${st==='missing'?'selected':''}>✗ Отсутствует</option><option value="na" ${st==='na'?'selected':''}>— Не требуется</option>
                </select></div>`;
        });
    });
    grid.innerHTML=html; updateDataSummary();
}
function updateDataSummary() {
    const el=document.getElementById('data-summary'); if(!el) return;
    const ok=initialDataList.filter(d=>d.status==='ok').length, w=initialDataList.filter(d=>d.status==='wait').length, m=initialDataList.filter(d=>d.status==='missing').length;
    el.innerHTML=`<span style="color:var(--green);">✓ Есть: ${ok}</span> | <span style="color:var(--gold);">⏳ Ждём: ${w}</span> | <span style="color:var(--red);">✗ Нет: ${m}</span>`;
}
window.updateDocStatus = function(id, val) {
    const d=initialDataList.find(x=>x.id===id); if(!d) return; d.status=val;
    const sm={}; initialDataList.forEach(x=>{if(x.status!=='unknown')sm[x.id]=x.status;}); lsSave(LS_INITDATA,sm);
    const rid='AutoRisk_'+id;
    if(val==='missing') { if(!projectRisks.find(r=>r.id===rid)) projectRisks.push({id:rid,desc:`Отсутствует: ${d.name}`,prob:d.critical?3:2,impact:d.critical?3:2,cost:d.defCost||100000,delay:d.defDelay||5,isAuto:true}); }
    else if(val==='wait') { if(!projectRisks.find(r=>r.id===rid)) projectRisks.push({id:rid,desc:`Задержка: ${d.name}`,prob:2,impact:d.critical?3:2,cost:(d.defCost||100000)*0.5,delay:Math.round((d.defDelay||10)*0.5),isAuto:true}); }
    else projectRisks=projectRisks.filter(r=>r.id!==rid);
    renderInitialData(); syncBufferTask(); renderUI(); renderRisks();
};

function renderRisks() {
    const tbody=document.getElementById('risks-tbody'); if(!tbody) return;
    for(let p=1;p<=3;p++) for(let i=1;i<=3;i++){const c=document.getElementById(`rm-${p}-${i}`);if(c)c.textContent='0';}
    let td=0, tc=0;
    const rows=projectRisks.map(r=>{
        const c=document.getElementById(`rm-${r.prob}-${r.impact}`); if(c) c.textContent=parseInt(c.textContent||0)+1;
        const s=r.prob*r.impact; const z=s>=6?'<span style="color:var(--red);">● ВЫСОКИЙ</span>':s>=3?'<span style="color:var(--gold);">● СРЕДНИЙ</span>':'<span style="color:var(--green);">● НИЗКИЙ</span>';
        td+=r.delay||0; tc+=r.cost||0;
        return `<tr style="background:${r.isAuto?'#fff8f8':'transparent'}">
            <td style="padding:4px 6px;"><input type="text" value="${r.desc}" class="inline-input" style="width:100%;" onchange="updateRisk('${r.id}','desc',this.value)" ${r.isAuto?'readonly':''}></td>
            <td style="text-align:center;"><input type="number" value="${r.prob}" min="1" max="3" class="inline-input" style="width:40px;text-align:center;" onchange="updateRisk('${r.id}','prob',this.value)"></td>
            <td style="text-align:center;"><input type="number" value="${r.impact}" min="1" max="3" class="inline-input" style="width:40px;text-align:center;" onchange="updateRisk('${r.id}','impact',this.value)"></td>
            <td style="text-align:center;font-weight:bold;">${z}</td>
            <td style="text-align:right;"><input type="number" value="${r.cost}" step="10000" class="inline-input" style="width:90px;text-align:right;" onchange="updateRisk('${r.id}','cost',this.value)"></td>
            <td style="text-align:center;"><input type="number" value="${r.delay||0}" class="inline-input" style="width:50px;text-align:center;" onchange="updateRisk('${r.id}','delay',this.value)"></td>
            <td style="text-align:center;">${r.isAuto?'—':`<button onclick="delRisk('${r.id}')" class="btn btn-outline" style="color:var(--red);padding:2px 8px;">✕</button>`}</td>
        </tr>`;
    });
    tbody.innerHTML=rows.join('') + (projectRisks.length?`<tr style="background:#f5f3f0;font-weight:700;"><td colspan="4" style="padding:6px 8px;">ИТОГО:</td><td style="text-align:right;color:var(--red);">${fmt(tc)} ₽</td><td style="text-align:center;color:var(--red);">${Math.ceil(td)} дн.</td><td></td></tr>`:'<tr><td colspan="7" style="text-align:center;color:var(--muted);">Риски не заданы</td></tr>');
    renderEconomics();
}
window.updateRisk = function(id, f, v) { const r=projectRisks.find(x=>x.id===id); if(!r) return; r[f]=f==='desc'?v:(parseFloat(v)||0); if(f==='prob'||f==='impact')r[f]=Math.min(3,Math.max(1,r[f])); syncBufferTask(); renderUI(); renderRisks(); };
window.delRisk = function(id) { projectRisks=projectRisks.filter(x=>x.id!==id); syncBufferTask(); renderUI(); renderRisks(); };

function syncBufferTask() {
    if (appMode === 'simple') {
        currentTasks = currentTasks.filter(t => !t.isBuffer);
        return;
    }
    let tbd = 0; projectRisks.forEach(r => { if(r.prob*r.impact>=4) tbd+=(r.delay||0)*(r.prob===3?0.8:r.prob===2?0.5:0.2); });
    tbd=Math.ceil(tbd); let mx=null; currentTasks.forEach(t=>{if(!t.isBuffer&&t.end){const d=new Date(t.end);if(!isNaN(d)&&(!mx||d>new Date(mx)))mx=t.end;}});
    if(tbd>0&&mx) {
        const s=new Date(mx); s.setDate(s.getDate()+1); const e=new Date(s); e.setDate(e.getDate()+tbd-1);
        const l=`[ТОС] Проектный буфер (${tbd} раб.дн.)`; const i=currentTasks.findIndex(t=>t.isBuffer);
        if(i>-1) { currentTasks[i].start=getLocalDateString(s); currentTasks[i].end=getLocalDateString(e); currentTasks[i].name=l; const b=currentTasks.splice(i,1)[0]; currentTasks.push(b); }
        else currentTasks.push({id:'Buffer_TOC',name:l,start:getLocalDateString(s),end:getLocalDateString(e),progress:0,dependencies:[],resource:'ТОС',utilization:0,isBuffer:true});
    } else currentTasks=currentTasks.filter(t=>!t.isBuffer);
}
function initSections() { const grid=document.getElementById('sections-grid'); if(!grid) return; grid.innerHTML=''; const s=SECTIONS_DATA[currentStage]; for(const c in s) grid.innerHTML+=`<label style="display:flex;align-items:center;gap:8px;font-size:12px;background:#f9f9f9;padding:8px 10px;border:1px solid var(--border);border-radius:4px;cursor:pointer;"><input type="checkbox" class="sec-cb" data-code="${c}" data-name="${s[c]}" style="accent-color:var(--green);width:16px;height:16px;"><b style="color:var(--navy);min-width:45px;">${c}</b><span>${s[c]}</span></label>`; }

// ──────────────────────────────────────────────────────────────
//  РЕНДЕР ГАНТА И ТАБЛИЦЫ
// ──────────────────────────────────────────────────────────────
function refreshApp() { fetch('api_gantt.php?nocache='+Date.now()).then(r=>r.json()).then(d=>{currentTasks=(Array.isArray(d)?d:(d.tasks||[])).map(t=>({...t,dependencies:Array.isArray(t.dependencies)?t.dependencies:(t.dependencies?t.dependencies.split(',').map(x=>x.trim()):[]),resource:t.resource||'',utilization:t.utilization!==undefined?parseInt(t.utilization):100})); syncBufferTask(); renderUI(); renderRisks(); }).catch(e=>console.error(e)); }
function saveToServer(d) { fetch('api_save.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(d)}).then(r=>r.json()).catch(e=>console.error(e)); renderUI(); renderRisks(); }

function renderUI() {
    const isToc = (appMode === 'toc');
    const container = document.getElementById('gantt-container');
    renderTaskTable();
    
    if (!container || (!isToc && !isGipRole())) return; 

    if (currentTasks.length===0) { container.innerHTML="<p style='padding:20px;text-align:center;'>График пуст.</p>"; renderEconomics(); return; }
    if (typeof Gantt==='undefined') { setTimeout(renderUI,100); return; }
    
    const validIds = new Set(currentTasks.map(t=>t.id));
    const gt = currentTasks.map((t,i) => ({ id:t.id, name:`${i+1}. ${t.name}`, start:isNaN(new Date(t.start).getTime())?getLocalDateString():t.start, end:isNaN(new Date(t.end).getTime())?getLocalDateString():t.end, progress:0, dependencies:(t.dependencies||[]).filter(d=>validIds.has(d)).join(', '), custom_class:t.isMilestone?'bar-milestone':t.isManagement?'bar-management':t.isBuffer?'bar-buffer':'' }));
    container.innerHTML='';
    try {
        ganttInstance = new Gantt('#gantt-container', gt, {
            header_height:50, view_mode: currentGanttView, language:'ru', on_click: task => openModal(task),
            on_date_change: (task,s,e) => { const t=currentTasks.find(x=>x.id===task.id); if(t&&!t.isBuffer){t.start=getLocalDateString(s);t.end=getLocalDateString(new Date(e.getTime()-43200000));if(t.isMilestone)t.end=t.start;cascadeDates();syncBufferTask();saveToServer(currentTasks);} }
        });
    } catch(e) {}
    renderEconomics();
}

function renderTaskTable() {
    const tbody = document.getElementById('task-table-body'); if (!tbody) return;
    const isToc = (appMode === 'toc');
    const isGip = isGipRole();
    const displayFot = isGip ? 'none' : 'table-cell'; 
    
    let fot = 0; let html = '';
    currentTasks.forEach((t,i) => {
        const tf = calcTaskFot(t); fot += tf;
        const dur = t.isMilestone||t.isBuffer ? 0 : Math.max(1, Math.ceil((new Date(t.end)-new Date(t.start))/86400000)+1);
        let depOpt = '<option value="">Нет</option>';
        currentTasks.forEach((ct,ci)=>{if(ct.id!==t.id&&!ct.isBuffer) depOpt+=`<option value="${ct.id}" ${(t.dependencies?.includes(ct.id))?'selected':''}>${ci+1}. ${ct.name}</option>`;});
        const resArr=t.resource?t.resource.split(',').map(r=>r.trim()).filter(Boolean):[];
        const rCb = availableResources.map(r=>`<label style="display:block;font-size:11px;margin-bottom:4px;cursor:pointer;"><input type="checkbox" class="cb-res-${t.id}" value="${r}" ${resArr.includes(r)?'checked':''}> ${r}</label>`).join('');
        const isL = t.isManagement||t.isBuffer;
        const trBg = t.isBuffer?'#fff4e5':t.isManagement?'#f0fbf4':t.isMilestone?'#fff5f5':'transparent';
        
        html += `<tr style="background:${trBg};" data-index="${i}">
            <td style="text-align:center;"><input type="number" class="inline-input inline-order" data-id="${t.id}" value="${i+1}" min="1" style="width:38px;" ${isL?'disabled':''}></td>
            <td>
                <input type="text" class="inline-input inline-name-input" data-id="${t.id}" value="${t.name.replace(/"/g, '&quot;')}" 
                       style="width:100%;font-weight:${isL?'700':'400'};color:${t.isBuffer?'var(--orange)':t.isMilestone?'var(--red)':'inherit'};font-size:12px;background:transparent;border:none;" ${isL?'readonly':''}>
            </td>
            <td style="display:${(isToc||isGip)?'table-cell':'none'};"><select class="inline-input inline-dep-select" data-id="${t.id}" style="font-size:11px;width:130px;">${depOpt}</select></td>
            <td style="position:relative;min-width:110px;">
                ${!t.isBuffer ? `<button class="btn btn-outline btn-res-toggle" data-id="${t.id}" style="width:100%;font-size:10px;padding:3px 6px;margin-bottom:4px;">Назначить ▾</button>
                <div id="res-drop-${t.id}" style="display:none;position:absolute;top:30px;left:0;z-index:200;background:#fff;border:1px solid var(--navy);padding:8px;border-radius:4px;width:200px;">${rCb}<button class="btn btn-dark btn-res-apply" data-id="${t.id}" style="margin-top:6px;width:100%;padding:4px;">Ок</button></div>` : ''}
                <div style="font-size:10px;color:var(--muted);">${t.isBuffer?'—':resArr.join('<br>')||'—'}</div>
            </td>
            <td><input type="number" class="inline-input inline-util-input" data-id="${t.id}" value="${t.isBuffer?0:Math.round(t.utilization||100)}" min="0" style="width:42px;text-align:center;" ${isL?'readonly':''}> %</td>
            <td><input type="date" class="inline-input inline-start-input" data-id="${t.id}" value="${t.start}" style="width:115px;" ${isL?'readonly':''}></td>
            <td><input type="date" class="inline-input inline-end-input" data-id="${t.id}" value="${t.end}" style="width:115px;" ${t.isBuffer||t.isMilestone?'readonly':''}></td>
            <td><input type="number" class="inline-input inline-dur-input" data-id="${t.id}" value="${t.isBuffer?0:dur}" min="1" style="width:45px;text-align:center;" ${isL?'readonly':''}> дн.</td>
            <td style="text-align:right;font-weight:600;font-size:12px;display:${displayFot};">${t.isBuffer?'—':fmt(tf)+' ₽'}</td>
        </tr>`;
    });
    
    const colSpan = (isToc || isGip) ? 8 : 7;
    html += `<tr style="background:var(--navy);font-weight:700;"><td colspan="${colSpan}" style="padding:8px 12px;color:rgba(255,255,255,0.7);font-size:12px;">ИТОГО ФОТ по графику:</td><td style="padding:8px 12px;text-align:right;color:#fff;font-size:15px;white-space:nowrap;display:${displayFot};">${fmt(fot)} ₽</td></tr>`;
    tbody.innerHTML = html;

    tbody.querySelectorAll('.inline-name-input').forEach(el=>el.addEventListener('change', e=>{
        const t=currentTasks.find(x=>x.id===e.target.dataset.id);
        if(t){ t.name=e.target.value; saveToServer(currentTasks); }
    }));

    tbody.querySelectorAll('.inline-dep-select').forEach(el=>el.addEventListener('change', e=>{const t=currentTasks.find(x=>x.id===e.target.dataset.id);if(t){t.dependencies=e.target.value?[e.target.value]:[];cascadeDates();syncBufferTask();saveToServer(currentTasks);}}));
    tbody.querySelectorAll('.btn-res-toggle').forEach(btn => {
        btn.addEventListener('click', e => { 
            e.preventDefault(); e.stopPropagation(); 
            const id=e.currentTarget.dataset.id, drop=document.getElementById('res-drop-'+id), isH=drop?drop.style.display==='none':false;
            document.querySelectorAll('[id^="res-drop-"]').forEach(d=>d.style.display='none');
            if(drop) drop.style.display=isH?'block':'none'; 
        });
    });
    tbody.querySelectorAll('.btn-res-apply').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault(); e.stopPropagation();
            const id=e.currentTarget.dataset.id, t=currentTasks.find(x=>x.id===id);
            if(t){ t.resource=Array.from(document.querySelectorAll(`.cb-res-${id}:checked`)).map(cb=>cb.value).join(', '); document.getElementById('res-drop-'+id).style.display='none'; saveToServer(currentTasks); }
        });
    });
    tbody.querySelectorAll('[id^="res-drop-"]').forEach(d=>d.addEventListener('click', e=>e.stopPropagation()));
    tbody.querySelectorAll('.inline-util-input').forEach(el=>el.addEventListener('change', e=>{const t=currentTasks.find(x=>x.id===e.target.dataset.id);if(t){t.utilization=Math.min(100,Math.max(0,parseInt(e.target.value)||0));saveToServer(currentTasks);}}));
    
    tbody.querySelectorAll('.inline-start-input,.inline-end-input').forEach(el=>el.addEventListener('change', e=>{
        const t=currentTasks.find(x=>x.id===e.target.dataset.id);
        if(t&&!t.isManagement&&!t.isBuffer){
            if(e.target.classList.contains('inline-start-input')){
                t.start=e.target.value;
                if(t.isMilestone) t.end=t.start; 
            } else {
                if(!t.isMilestone) t.end=e.target.value;
                else t.end=t.start;
            }
            if(new Date(t.start)>new Date(t.end)) t.end=t.start;
            cascadeDates();syncBufferTask();saveToServer(currentTasks);
        }
    }));
    
    tbody.querySelectorAll('.inline-dur-input').forEach(el=>el.addEventListener('change', e=>{const t=currentTasks.find(x=>x.id===e.target.dataset.id);if(t&&!t.isManagement&&!t.isMilestone&&!t.isBuffer){const s=new Date(t.start);const end=new Date(s);end.setDate(s.getDate()+(parseInt(e.target.value)||1)-1);t.end=getLocalDateString(end);cascadeDates();syncBufferTask();saveToServer(currentTasks);}}));
    tbody.querySelectorAll('.inline-order').forEach(el=>el.addEventListener('change', e=>{const oi=currentTasks.findIndex(x=>x.id===e.target.dataset.id);if(currentTasks[oi].isManagement||currentTasks[oi].isBuffer)return;let ni=Math.min(Math.max(0,parseInt(e.target.value)-1),currentTasks.length-1);if(currentTasks[ni]?.isManagement||currentTasks[ni]?.isBuffer)ni=1;const item=currentTasks.splice(oi,1)[0];currentTasks.splice(ni,0,item);cascadeDates();syncBufferTask();saveToServer(currentTasks);}));
}

function renderEconomics() {
    const isToc = (appMode === 'toc');

    let fot=0, minStart=null, maxEnd=null;
    currentTasks.forEach(t => {
        if (!t.isMilestone&&!t.isBuffer) fot += calcTaskFot(t);
        if (!t.isBuffer) {
            if (!minStart||new Date(t.start)<new Date(minStart)) minStart=t.start;
            if (!maxEnd||new Date(t.end)>new Date(maxEnd)) maxEnd=t.end;
        }
    });

    const bufTask=currentTasks.find(t=>t.isBuffer);
    const timeBufferDays=(isToc && bufTask?.start && bufTask?.end) ? Math.max(0,Math.ceil((new Date(bufTask.end)-new Date(bufTask.start))/86400000)+1) : 0;
    const projectEnd=timeBufferDays>0&&maxEnd?(()=>{const d=new Date(maxEnd);d.setDate(d.getDate()+timeBufferDays);return getLocalDateString(d);})():maxEnd;
    const totalDays=(minStart&&projectEnd)?Math.ceil((new Date(projectEnd)-new Date(minStart))/86400000)+1:0;

    const cUrg=parseFloat(document.getElementById('coef_urgency')?.value)||1;
    const cCli=parseFloat(document.getElementById('coef_client')?.value)||1;
    const cReg=parseFloat(document.getElementById('coef_region')?.value)||1;
    const cExt=parseFloat(document.getElementById('coef_extra')?.value)||1;
    const totalCoef=cUrg*cCli*cReg*cExt;

    if(document.getElementById('coef_total_display')) document.getElementById('coef_total_display').textContent=`×${totalCoef.toFixed(2)}`;
    if(document.getElementById('coef_breakdown')) document.getElementById('coef_breakdown').innerHTML=`${cUrg}×${cCli}×${cReg}×${cExt} = <b style="color:var(--navy);">${totalCoef.toFixed(2)}</b>`;

    const baseCost = isToc ? (fot * 1.5) : fot; 
    const costWithCoef = baseCost * totalCoef;

    const calcOH = pfx => { const v=parseFloat(document.getElementById(pfx+'_val')?.value)||0; return document.getElementById(pfx+'_type')?.value==='pct' ? fot*(v/100) : v; };
    const totalOH=calcOH('oh_print')+calcOH('oh_trip')+calcOH('oh_siz')+calcOH('oh_amort')+calcOH('oh_soft')+calcOH('oh_tech');

    let expCost=0;
    if (document.getElementById('exp-checkbox')?.checked) {
        const v=parseFloat(document.getElementById('exp-value')?.value)||0;
        expCost=document.getElementById('exp-calc-type')?.value==='pct'?costWithCoef*(v/100):v;
    }
    const totalExtra=totalOH+expCost;

    const mgmtPct=parseFloat(document.getElementById('calc-mgmt')?.value)||0;
    const mgmtMoney=fot*(mgmtPct/100); 

    let riskFund=0;
    if (isToc) {
        projectRisks.forEach(r => { if(r.prob*r.impact>=4){const pp=r.prob===3?0.8:r.prob===2?0.5:0.2; riskFund+=(r.cost||0)*pp;} });
    }

    const profitPct=parseFloat(document.getElementById('calc-profit')?.value)||0;
    const costBeforeMargin=costWithCoef+totalExtra+mgmtMoney+riskFund;
    const marginMoney=costBeforeMargin*(profitPct/100);
    const totalNoVat=costBeforeMargin+marginMoney;
    
    const taxPct=parseFloat(document.getElementById('calc-tax')?.value)||0;
    if(document.getElementById('tax-rate-display')) document.getElementById('tax-rate-display').textContent = taxPct>0?taxPct+'%':'0%';
    const taxMoney=totalNoVat*(taxPct/100);
    const finalTotal=totalNoVat+taxMoney;
    const advancePct=parseFloat(document.getElementById('calc-advance')?.value)||0;
    const advanceMoney=finalTotal*(advancePct/100);

    setTxt('res_fot',fot);
    setTxt('res_coeffs',costWithCoef);
    setTxt('res_extra',totalExtra);
    setTxt('res_mgmt',mgmtMoney);
    setTxt('res_risk_fund',riskFund);
    setTxt('res_margin',marginMoney);
    setTxt('res_total_no_vat',totalNoVat);
    setTxt('res_vat',taxMoney);
    setTxt('res_total',finalTotal);
    setTxt('res_advance',advanceMoney);
    setTxt('res_remainder',finalTotal-advanceMoney);
    setTxt('res_time_buffer',timeBufferDays);

    const elDur=document.getElementById('kp_duration_txt');
    if (elDur) elDur.value = totalDays > 0 ? (isToc ? `${totalDays} кал.дн. (с буфером ${timeBufferDays} дн.)` : `${totalDays} кал.дн.`) : '0 дн.';

    const elPay=document.getElementById('kp_payment_terms');
    if (elPay && advancePct>0) elPay.value=`Аванс ${advancePct}% (${fmt(advanceMoney)} ₽) при подписании, остаток по факту`;

    const sbcTotal = (parseFloat(document.getElementById('sbc_base')?.value)||0) * (parseFloat(document.getElementById('sbc_index')?.value)||1) * ((parseFloat(document.getElementById('sbc_stage_pct')?.value)||100)/100);
    if(document.getElementById('sbc_total_calc')) document.getElementById('sbc_total_calc').textContent=fmt(sbcTotal)+' ₽';
    const elAlert=document.getElementById('sbc_diff_alert');
    if (elAlert&&sbcTotal>0) {
        const dp=((totalNoVat-sbcTotal)/sbcTotal)*100; const s=dp>=0?'+':'';
        elAlert.innerHTML=Math.abs(dp)<=10?`<div style="background:#e8f4ec;border:1px solid var(--green);color:var(--green);padding:8px;border-radius:3px;">✓ Норма (${s}${dp.toFixed(1)}%)</div>`:dp>10?`<div style="background:#fffbe6;border:1px solid var(--gold);color:var(--gold);padding:8px;border-radius:3px;">⚠ Выше норматива на ${dp.toFixed(1)}%</div>`:`<div style="background:#fff5f5;border:1px solid var(--red);color:var(--red);padding:8px;border-radius:3px;">↓ Ниже норматива на ${Math.abs(dp).toFixed(1)}%</div>`;
    }

    const elBuf=document.getElementById('toc-buffer-badge');
    if (elBuf) { elBuf.textContent=timeBufferDays>0?`ТОС-буфер: ${timeBufferDays} раб.дн.`:'ТОС-буфер: нет'; elBuf.style.color=timeBufferDays>0?'var(--orange)':'var(--green)'; }
}

function cascadeDates() {
    let iterations=0,changed=true;
    while (changed&&iterations<100) {
        changed=false; iterations++;
        for (const t of currentTasks) {
            if (t.isBuffer||!t.dependencies?.length) continue;
            let maxEnd=null;
            for (const depId of t.dependencies) { const dep=currentTasks.find(x=>x.id===depId); if(dep){const d=new Date(dep.end);if(!maxEnd||d>maxEnd)maxEnd=d;} }
            if (maxEnd) {
                const reqStart=new Date(maxEnd); reqStart.setDate(reqStart.getDate()+1);
                if (new Date(t.start).getTime()!==reqStart.getTime()) {
                    const diff=Math.round((new Date(t.end)-new Date(t.start))/86400000);
                    t.start=getLocalDateString(reqStart); const newEnd=new Date(reqStart); newEnd.setDate(newEnd.getDate()+diff);
                    t.end=getLocalDateString(newEnd); if (t.isMilestone) t.end=t.start; changed=true;
                }
            }
        }
    }
    const mgmt=currentTasks.find(t=>t.isManagement);
    if (mgmt) { const n=currentTasks.filter(t=>!t.isManagement&&!t.isBuffer); if(n.length){mgmt.start=n.map(t=>t.start).sort()[0]; mgmt.end=n.map(t=>t.end).sort().reverse()[0];} }
}

function openModal(task) {
    const t = currentTasks.find(x=>x.id===task.id); if (!t||t.isManagement||t.isBuffer) return;
    currentEditingTaskId=task.id;
    document.getElementById('modal-task-name').value=t.name.replace(/^\d+\.\s*/,''); document.getElementById('modal-task-start').value=t.start; document.getElementById('modal-task-end').value=t.end;
    if(document.getElementById('modal-end-row')) document.getElementById('modal-end-row').style.display=t.isMilestone?'none':'flex';
    const d=document.getElementById('modal-task-dependency'); d.innerHTML='<option value="">Нет</option>';
    currentTasks.forEach((ct,i)=>{if(ct.id!==t.id&&!ct.isBuffer) d.innerHTML+=`<option value="${ct.id}" ${t.dependencies?.includes(ct.id)?'selected':''}>${i+1}. ${ct.name}</option>`;});
    document.getElementById('task-modal').style.display='flex';
}
function closeModal() { document.getElementById('task-modal').style.display='none'; currentEditingTaskId=null; }
function saveTask() {
    const t=currentTasks.find(x=>x.id===currentEditingTaskId); if (!t) return;
    t.name=document.getElementById('modal-task-name').value; t.start=document.getElementById('modal-task-start').value; t.end=t.isMilestone?t.start:document.getElementById('modal-task-end').value;
    const dep=document.getElementById('modal-task-dependency').value; t.dependencies=dep?[dep]:[];
    cascadeDates(); syncBufferTask(); saveToServer(currentTasks); closeModal();
}
function setupModalListeners() { document.getElementById('btn-close-modal')?.addEventListener('click', closeModal); document.getElementById('btn-save-modal')?.addEventListener('click', saveTask); }

// ──────────────────────────────────────────────────────────────
//  АУДИТ ГРАФИКА И ИОНА
// ──────────────────────────────────────────────────────────────
window.askIona = function(block) {
    const inputEl=document.getElementById(`iona-input-${block}`);
    const btnEl=document.getElementById(`btn-iona-${block}`);
    const responseEl=document.getElementById(`iona-response-${block}`);
    if (!btnEl||!responseEl) return;
    const userMessage=inputEl?.value.trim()||'Полный аудит.';
    let contextStr='';

    if (block==='gantt') {
        if (!currentTasks.length) return alert('График пуст.');
        contextStr=currentTasks.map(t=>{const type=t.isManagement?'УП':t.isBuffer?'БУФЕР':t.isMilestone?'Веха':'Работа'; return `[${type}] ${t.name} | Исполнитель: ${t.resource||'—'} | Загрузка: ${t.utilization}% | ${t.start} → ${t.end} | ФОТ: ${fmt(calcTaskFot(t))} ₽`;}).join('\n');
        const missing=initialDataList.filter(d=>d.status==='missing');
        const waiting=initialDataList.filter(d=>d.status==='wait');
        if (missing.length||waiting.length) { contextStr+='\n\nДЕФИЦИТ ИД:\n'; missing.forEach(d=>contextStr+=`✗ ${d.name} | Задержка: ${d.defDelay} дн.\n`); waiting.forEach(d=>contextStr+=`⏳ ${d.name} | Риск: до ${Math.round(d.defDelay*0.5)} дн.\n`); }
    } else if (block==='risks') {
        const missing=initialDataList.filter(d=>d.status==='missing');
        const waiting=initialDataList.filter(d=>d.status==='wait');
        const unknown=initialDataList.filter(d=>d.status==='unknown'&&d.critical);
        const bufTask=currentTasks.find(t=>t.isBuffer);
        const bufDays=bufTask?Math.ceil((new Date(bufTask.end)-new Date(bufTask.start))/86400000)+1:0;
        contextStr=`=== ИД ===\nОтсутствуют: ${missing.map(d=>d.name).join('; ')||'нет'}\nОжидаются: ${waiting.map(d=>d.name).join('; ')||'нет'}\nКритичные неизвестны: ${unknown.map(d=>d.name).join('; ')||'нет'}\n\n=== РИСКИ ===\n`;
        projectRisks.forEach(r=>{const sc=r.prob*r.impact; contextStr+=`[${sc>=6?'ВЫСОКИЙ':sc>=3?'СРЕДНИЙ':'НИЗКИЙ'}] ${r.desc} | В:${r.prob} Вл:${r.impact} | ${fmt(r.cost)} ₽ | ${r.delay||0} дн.\n`;});
        let emv=0; projectRisks.forEach(r=>{if(r.prob*r.impact>=4)emv+=r.cost*(r.prob===3?0.8:r.prob===2?0.5:0.2);});
        contextStr+=`\n=== БУФЕР ===\n${bufDays>0?`${bufDays} раб.дн. (EMV)`:0}\nEMV: ${fmt(emv)} ₽`;
    }

    btnEl.disabled=true; btnEl.textContent='Иона думает...';
    responseEl.style.display='block'; responseEl.innerHTML=`<span style="color:var(--muted);">Анализирую ограничения…</span>`;
    fetch('api_chat.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({message:userMessage,context:contextStr})})
        .then(async res=>{const txt=await res.text();try{return JSON.parse(txt);}catch{throw new Error(`Не JSON: ${txt.substring(0,500)}`);} })
        .then(data=>{
            btnEl.disabled=false; btnEl.textContent=block==='gantt'?'Анализировать график':'Анализ рисков';
            if (data.explanation) {
                let raw=data.explanation.replace(/[\u4e00-\u9fa5]/g,'');
                responseEl.innerHTML=`<div style="background:#fdfdfd;padding:14px;font-size:13px;line-height:1.6;">${raw.replace(/^\d+\.\s+(.+?):/gm,'<div style="color:var(--navy);font-weight:700;font-size:14px;margin-top:14px;">$1:</div>').replace(/^[–—-]\s+(.+)/gm,'<div style="margin-left:10px;margin-bottom:5px;padding-left:14px;position:relative;"><span style="position:absolute;left:0;color:var(--orange);font-weight:700;">→</span>$1</div>').replace(/\*\*(.+?)\*\*/g,'<b>$1</b>')}</div>`;
            } else if (data.error) responseEl.innerHTML=`<span style="color:var(--red);">Ошибка: ${data.error.message}</span>`;
        })
        .catch(err=>{btnEl.disabled=false;btnEl.textContent=block==='gantt'?'Анализировать':'Анализ рисков';responseEl.innerHTML=`<span style="color:var(--red);">Сбой: ${err.message}</span>`;});
};
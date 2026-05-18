
function esc(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

// ══════════════════════════════════════════════════════════════
//  TOC.CHERNETCHENKO.PRO — app.js  v5.0 (Hybrid TOC+EMV)
//  ✅ Удаление задач, Порог рисков, Разделение времени/денег
// ══════════════════════════════════════════════════════════════

let currentTasks       = [];
let ganttInstance      = null;
let currentEditingTaskId = null;
let projectRisks       = [];

// ⬇️ ДОБАВИТЬ ЭТО
window.RISK_THRESHOLD = 4; // Порог по умолчанию (≥4)
// Загрузка сохраненного значения, если есть
try { 
    const savedTh = localStorage.getItem('toc_risk_threshold');
    if (savedTh) window.RISK_THRESHOLD = parseInt(savedTh); 
} catch(e) {}

// ──────────────────────────────────────────────────────────────
//  МАСШТАБ ГАНТА И РЕЖИМ ПРИЛОЖЕНИЯ
// ──────────────────────────────────────────────────────────────
let currentGanttView = 'Day';
const LS_MODE     = 'toc_app_mode';
const LS_RATES    = 'toc_resource_rates';
const LS_CAPEX    = 'toc_capex_items';
const LS_ECON     = 'toc_economics';
const LS_INITDATA = 'toc_initial_data';
const LS_RISK_THRESH = 'toc_risk_threshold';

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
window.resourceRates = resourceRates;
window.availableResources = availableResources;
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


const INITIAL_DATA_CATALOG = [
{ group: 'Градостроительные', items: [
{ id: 'd_gpzu',    name: 'ГПЗУ (Градостроительный план зем. участка)',        critical: true,  defDelay: 30, defCost: 300000, note: 'ГрК РФ ст. 57.3' },
{ id: 'd_agr',     name: 'АГР / АГО (Архитектурно-градостроительный облик)',  critical: false, defDelay: 14, defCost: 100000, note: 'Для  особых зон' },
{ id: 'd_pzz',     name: 'Выписка из ПЗЗ (виды использования, регламенты)',   critical: true,  defDelay: 14, defCost: 150000, note: 'ГрК РФ ст. 30' },
{ id: 'd_genplan', name: 'Схема генерального плана застройщика',              critical: false, defDelay:  7, defCost:  50000, note: 'Для ПЗУ' },
{ id: 'd_krt',     name: 'Документация по КРТ',                                critical: false, defDelay: 21, defCost: 200000, note: 'ГрК РФ ст. 67.1' },
{ id: 'd_opzh',    name: 'Ограничения ООПТ / охранные зоны / сервитуты',       critical: false, defDelay: 14, defCost: 200000, note: 'ЕГРН / ГИСОГД' },
]},
{ group: 'Инженерные изыскания', items: [
{ id: 'd_igi',  name: 'ИГИ - Инженерно-геологические изыска ния',           critical: true,  defDelay: 45, defCost: 500000, note: 'СП 47.13330' },
{ id: 'd_igeo', name: 'ИГ  - Инженерно-геодезические изыскания',           critical: true,  defDelay: 21, defCost: 250000, note: 'Топоподоснова' },
{ id: 'd_ige',  name: 'ИЭ  - Инженерно-экологические изыскания',           critical: false, defDelay: 30, defCost: 200000, note: 'Экология' },
{ id: 'd_igh',  name: 'ИГМ - Инженерно-гидрометеорологические изыскания', critical: false, defDelay: 30, defCost: 200000, note: 'Подтопления' },
{ id: 'd_iob',  name: 'Обследование несущих конструкций',                  critical: false, defDelay: 21, defCost: 300000, note: 'ГОСТ 31937' },
]},
{ group: 'Технические условия', items: [
{ id:  'd_tu_el',  name: 'ТУ на электроснабжение',                           critical: true,  defDelay: 60, defCost: 400000, note: 'ПП РФ № 861' },
{ id: 'd_tu_vk',  name: 'ТУ на водоснабж ение и водоотведение',              critical: true,  defDelay: 45, defCost: 300000, note: 'ФЗ № 416' },
{ id: 'd_tu_gas', name: 'ТУ на газоснабжение',                             critical: false, defDelay: 90, defCost: 500000, note: 'ПП РФ № 1149' },
{ id: 'd_tu_tep', name: 'ТУ на теплоснабжение',                            critical: false, defDelay: 45, defCost: 300000, note: 'ФЗ № 190' },
{ id: 'd_tu_ss',  name: 'ТУ на слаботочные сети / связь',                  critical: false, defDelay: 21, defCost: 100000, note: 'Связь' },
{ id:  'd_tu_rd',  name: 'Согласование с ГИБДД / дорожниками',              critical: false, defDelay: 30, defCost: 150000, note: 'Примыкания' },
]},
{ group: 'Задание на проектирование',  items: [
{ id: 'd_zadanie', name: 'Задание на проектирование (ГК РФ ст. 759)', critical: true,  defDelay: 14, defCost: 200000, note: 'Обязательный документ' },
{ id: 'd_tp',       name: 'Техническое задание (ТЗ) на разделы',      critical: true,  defDelay: 10, defCost: 100000, note: 'Смежники' },
{ id: 'd_bim_ez',  name: 'EIR / Требования к ТИМ',                    critical: false, defDelay:  7, defCost:  80000, note: 'ПП РФ № 331' },
]},
{ group: 'Договорные и финансовые', items: [
{ id: 'd_dog',    name: 'Договор подряда (подписан)',                    critical: true,  defDelay:  0, defCost: 1000000, note: 'Основание' },
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


// ──────────────────────────────────────────────────────────────
//  ⬇️ ОБНОВЛЕННАЯ ЛОГИКА РИСКОВ (ТОС + EMV)
// ──────────────────────────────────────────────────────────────

// ⬇️ НОВАЯ ФУНКЦИЯ: Единый расчет резервов (Дни + Деньги)

// ⬇️ ФУНКЦИЯ УСТАНОВКИ ПОРОГА (для вызова из HTML)

// Обновленная функция буфера (Только время)

// ⬇️ УДАЛЕНИЕ ЗАДАЧИ

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
                gipAlert.style.cssText = 'background:#e8f4ec; border:1px solid var(--c-toc); border-radius:4px; padding:15px; margin-bottom:20px;';
                const header = document.querySelector('header');
                header.parentNode.insertBefore(gipAlert, header.nextSibling);
            }
            const dirUrl = urlBase + '?id=' + data.id + '&role=dir';
            gipAlert.innerHTML = `
                 <div style="font-size:13px; color:var(--text-main); font-weight:bold; margin-bottom:8px;">✅ График успешно сохранен!</div>
                 <div style="font-size:12px; color:var(--text-main); margin-bottom:8px;">Скопируйте ссылку ниже и отправьте руководителю на проверку:</div>
                 <div style="display:flex; align-items:center; justify-content:space-between; background:#fff; padding:6px 10px; border:1px solid #ccc; border-radius:3px;">
                     <code style="font-size:12px; color:var(--text-main); user-select:all;">${dirUrl}</code>
                     <button class="btn btn-outline" onclick="navigator.clipboard.writeText('${dirUrl}').then(()=>alert('Скопировано!'))" style="font-size:11px; padding:3px 10px; border-color:var(--c-toc); color:var(--c-toc);">Копировать</button>
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
        if (data.resourceRates) { resourceRates = data.resourceRates; availableResources = Object.keys(resourceRates); window.resourceRates = resourceRates; window.availableResources = availableResources; }
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
    
    // ⬇️ Установка значения селекта порога при старте
    const sel = document.getElementById('risk-threshold-select');
    if (sel) sel.value = window.RISK_THRESHOLD;

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
if (typeof setupCsvManagers === 'function') {
    setupCsvManagers();
}
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
const titleTasks = document.getElementById('title-tasks'); if (titleTasks) titleTasks.textContent = isToc ? '04 — Календарный график и Ресурсы (ТОС)' : '03 — Ресурсная ведомость';
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
        const body = secTotals.querySelector('.calc-section-body');
        if (body) body.style.display = 'none';
        const aiExp = document.getElementById('ai-explanation');
        if (aiExp) aiExp.style.display = 'none';
        const chatInp = document.getElementById('chat-input');
        if (chatInp && chatInp.parentElement && chatInp.parentElement.parentElement) chatInp.parentElement.parentElement.style.display = 'none';
        
        // Меняем заголовок
        const title = secTotals.querySelector('h3 span');
        if (title) title.textContent = isToc ? '05 — Экспорт и передача графика' : '04 — Экспорт и передача графика';

        // Меняем текст кнопки скачивания
        const btnDl = document.getElementById('btn-dl-calc');
        if (btnDl) btnDl.textContent = '↓ Скачать ресурсную ведомость (CSV)';

        // Добавляем красивую инструкцию для ГИПа (если еще нет) 
        let gipInfo = document.getElementById('gip-section5-info');
        if (!gipInfo) {
            gipInfo = document.createElement('div');
            gipInfo.id = 'gip-section5-info';
            gipInfo.style.cssText = 'padding:20px; background:#e8f4ec; border:1px solid var(--c-toc); border-radius:4px; text-align:center; color:var(--text-main); font-size:14px; margin-top:15px;';
            gipInfo.innerHTML = '<b>Работа с графиком завершена!</b><br><br>Не забудьте нажать зеленую кнопку <b>«💾 В облако»</b> в самом верху страницы, чтобы передать готовый график руководителю.<br><br>Вы также можете скачать Ресурсную ведомость для себя (кнопка выше).';
            secTotals.appendChild(gipInfo);
        }
        gipInfo.style.display = 'block';
    }

} else {
    // Восстанавливаем оригинальный вид Раздела 05 для Директора
    if (secTotals) {
        secTotals.style.display = 'block';
        const title = secTotals.querySelector('h3 span');
        if (title) title.textContent = isToc ? '07 — Итоговый расчёт стоимости' : '05 — Итоговый расчёт стоимости';
        const body = secTotals.querySelector('.calc-section-body');
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




window.importRates = function(rows) {
rows.forEach((row, i) => {
if (i === 0) return;
const role = (row[0] || '').trim();
const rate = parseFloat(row[1]) || 0;
if (!role) return;
if (!availableResources.includes(role)) availableResources.push(role);
resourceRates[role] = rate;
});
window.availableResources = availableResources;
window.resourceRates = resourceRates;
lsSave(LS_RATES, resourceRates);
renderRates();
renderUI();
};

// ──────────────────────────────────────────────────────────────
//  ФУНКЦИИ КАПЗАТРАТ (CAPEX)
// ──────────────────────────────────────────────────────────────
const CAPEX_PRESETS = window.CAPEX_PRESETS || [];


function getProjectMonths() {
const normal = currentTasks.filter(t => !t.isBuffer && !t.isMilestone);
if (normal.length < 2) return 1;
const starts = normal.map(t => new Date(t.start)).filter(d => !isNaN(d));
const ends   = normal.map(t => new Date(t.end)).filter(d => !isNaN(d));
if (!starts.length || !ends.length) return 1;
const diffMs = Math.max(...ends.map(d=>d.getTime())) - Math.min(...starts.map(d=>d.getTime()));
return Math.max(1, Math.ceil(diffMs / 1000 / 60 / 60 / 24 / 30));
}


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



window.toggleCapexPresets = function() {
const el = document.getElementById('capex-presets-list');
if (el) el.style.display = el.style.display === 'none' ? 'block' : 'none';
};


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
            if (parsed.client && parsed.client !== 'Не найдено') { document.getElementById('req_client').value = parsed.client; found = true; }
            if (parsed.object && parsed.object !== 'Не найдено') { document.getElementById('req_object').value = parsed.object; found = true; }
            
            if (found) { statusEl.innerHTML = '<span style="color:var(--c-toc);">✓ Успешно!</span>'; saveEconomics(); } 
            else statusEl.innerHTML = '<span style="color:var(--c-fun);">Данные не найдены.</span>';
        } else throw new Error(data.error?.message || 'Ошибка API');
    } catch (err) { statusEl.innerHTML = '<span style="color:var(--c-ai);">Ошибка: ' + err.message + '</span>'; }
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
e.target.classList.add('active'); e.target.style.background='var(--c-toc)'; e.target.style.color='#fff'; e.target.style.borderColor='var(--c-toc)';
currentStage = e.target.dataset.stage; initSections();
});
});
document.getElementById('btn-sel-all')?.addEventListener('click', () => document.querySelectorAll('.sec-cb').forEach(cb=>cb.checked=true));
document.getElementById('btn-desel-all')?.addEventListener('click', () => document.querySelectorAll('.sec-cb').forEach(cb=>cb.checked=false));

document.querySelectorAll('.btn-gantt-view').forEach(btn => {
    btn.addEventListener('click', (e) => {
        document.querySelectorAll('.btn-gantt-view').forEach(b => { b.classList.remove('active'); b.style.background = 'transparent'; b.style.color = 'var(--text-main)'; });
        e.target.classList.add('active'); e.target.style.background = 'var(--c-toc)'; e.target.style.color = '#fff';
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
    if(added >0){ cascadeDates(); syncBufferTask(); saveToServer(currentTasks); } else alert('Уже в графике.');
});

document.getElementById('btn-add-task')?.addEventListener('click', () => { currentTasks.push({ id:'Task_'+Date.now(), name:'Новая задача', start:getLocalDateString(), end:getLocalDateString(), progress:0, dependencies:[], resource:'', utilization:100 }); cascadeDates(); syncBufferTask(); saveToServer(currentTasks); });
document.getElementById('btn-add-milestone')?.addEventListener('click', () => { currentTasks.push({ id:'Milestone_'+Date.now(), name:'◆ Новая веха', start:getLocalDateString(), end:getLocalDateString(), progress:0, dependencies:[], resource:'', utilization:0, isMilestone:true }); cascadeDates(); syncBufferTask(); saveToServer(currentTasks); });

document.getElementById('btn-reset-all')?.addEventListener('click', () => {
    if(!confirm('Удалить всё?')) return;
    currentTasks=[]; projectRisks=[]; capexItems=[]; initialDataList.forEach(d=>d.status='unknown');
    [LS_RATES, LS_CAPEX, LS_ECON, LS_INITDATA].forEach(k=>localStorage.removeItem(k));
    resourceRates={...DEFAULT_RATES}; availableResources=Object.keys(resourceRates);
    window.resourceRates = resourceRates; window.availableResources = availableResources;
    document.querySelectorAll('.sec-cb').forEach(cb=>cb.checked=false);
    renderInitialData(); renderCapex(); renderRates(); renderUI(); renderRisks(); 
    
    window.history.replaceState(null, '', window.location.pathname);
    const clz = document.getElementById('cloud-link-zone');
    if(clz) clz.style.display = 'none';

    saveToServer(currentTasks);
});

document.getElementById('btn-toggle-mgmt')?.addEventListener('click', () => {
    const i=currentTasks.findIndex(t=>t.isManagement);
    if(i >-1) currentTasks.splice(i,1); else currentTasks.unshift({ id:'Task_Mgmt_'+Date.now(), name:'[УП] Управление проектом', start:getLocalDateString(), end:getLocalDateString(), progress:0, dependencies:[], resource:'ГИП', utilization:100, isManagement:true });
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
const c={unknown:'#6b6b6b',ok:'var(--c-toc)',wait:'var(--c-fun)',missing:'var(--c-ai)',na:'#767676'} , bg={unknown:'#f9f9f9',ok:'#f0fbf4',wait:'#fffbe6',missing:'#fff5f5',na:'#f5f5f5'};
let html='';
INITIAL_DATA_CATALOG.forEach(g => {
html += `<div style="grid-column:1/-1;margin:8px 0 4px;font-size:11px;font-weight:700;text-transform:uppercase;color:var(--muted);border-bottom:1px solid var(--border);padding-bottom:4px;">${g.group}</div>`;
g.items.forEach(def => {
const d=initialDataList.find(x=>x.id===def.id); if(!d) return; const st=d.status;
html += `<div data-doc-id="${d.id}" style="display:flex;gap:10px;align-items:flex-start;background:${bg[st]};border:1px solid ${st==='missing'?'var(--c-ai)':'var(--border)'};border-left:3px solid ${c[st]};border-radius:4px;padding:9px 12px;"><div style="flex:1;min-width:0;"><div style="font-size:12px;font-weight:600;color:var(--text-main);line-height:1.3;">${d.name}${d.critical?' <span style="color:var(--c-ai);font-size:10px;">●&nbsp;Критичный</span>':''}</div><div style="font-size:10px;color:var(--muted);margin-top:2px;">${d.note||''}</div></div><select onchange="updateDocStatus('${d.id}',this.value)" class="std-select" style="flex-shrink:0;width:130px;padding:5px 6px;font-size:11px;border-color:${c[st]};color:${c[st]};font-weight:600;"><option value="unknown" ${st==='unknown'?'selected':''}>Неизвестно</option><option value="ok" ${st==='ok'?'selected':''}>✓ В наличии</option><option value="wait" ${st==='wait'?'selected':''}>⏳ Ожидаем</option><option value="missing" ${st==='missing'?'selected':''}>✗ Отсутствует</option><option value="na" ${st==='na'?'selected':''}>— Не требуется</option></select></div>`;
});
});
grid.innerHTML=html; updateDataSummary();
}

function updateDataSummary() {
const el=document.getElementById('data-summary'); if(!el) return;
const ok=initialDataList.filter(d=>d.status==='ok').length, w=initialDataList.filter(d=>d.status==='wait').length, m=initialDataList.filter(d=>d.status==='missing').length;
el.innerHTML=`<span style="color:var(--c-toc);">✓ Есть: ${ok}</span> | <span style="color:var(--c-fun);">⏳ Ждём: ${w}</span> | <span style="color:var(--c-ai);">✗ Нет: ${m}</span>`;
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




// ──────────────────────────────────────────────────────────────
//  РЕНДЕР ГАНТА И ТАБЛИЦЫ
// ──────────────────────────────────────────────────────────────





function openModal(task) {
const t = currentTasks.find(x=>x.id===task.id); if (!t||t.isManagement||t.isBuffer) return;
currentEditingTaskId=task.id;
document.getElementById('modal-task-name').value=t.name.replace(/^\d+.\s*/,''); document.getElementById('modal-task-start').value=t.start; document.getElementById('modal-task-end').value=t.end;
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

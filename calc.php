<?php
$pageTitle       = 'ПИР-калькулятор онлайн | Расчёт стоимости проектирования по ТОС';
$pageDescription = 'Бесплатный онлайн-калькулятор стоимости ПИР. Ресурсный график, гантт-диаграмма, матрица рисков, ТОС-буфер. Коэффициенты сложности, накладные, маржа, НДС. Генерация КП для проектного бюро.';
$pageKeywords    = 'ПИР калькулятор онлайн, расчёт стоимости проектирования, коммерческое предложение проектная, ресурсный график ГИП, Гантт проектирование, СБЦ сравнение';
$canonicalUrl    = 'https://toc.chernetchenko.pro/calc';
$needsGantt      = true;
$breadcrumbs     = [
['name' => 'Главная',          'url' => 'https://toc.chernetchenko.pro/'],
['name' => 'ПИР-калькулятор', 'url' => 'https://toc.chernetchenko.pro/calc'],
];
$siteId = 'toc';
include 'header.php';
?>
<style>
/* ─── CALC PAGE STYLES (OVC DS) ─── */
.calc-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; flex-wrap:wrap; gap:16px; }
.calc-header h1 { margin:0; font-size:clamp(1.5rem, 3vw, 2rem); color:var(--text-main); font-family:var(--font-ui); font-weight:900; }
.calc-mode-select { display:flex; align-items:center; gap:10px; background:var(--bg-tertiary); border:1px solid var(--border); padding:6px 15px; border-radius:50px; }
.calc-mode-select span { font-size:12px; font-weight:bold; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; }
.calc-mode-select select { background:transparent; border:none; font-size:13px; font-weight:700; color:var(--c-toc); outline:none; cursor:pointer; }
.calc-info-panel { background:var(--bg-tertiary); border:1px solid var(--border); border-left:4px solid var(--c-toc); border-radius:var(--r-sm); padding:14px 18px; margin-bottom:20px; font-size:12px; color:var(--text-main); line-height:1.6; }
.calc-info-panel b { color:var(--c-toc); }
.calc-cloud-zone { display:none; background:var(--bg-tertiary); border:1px solid var(--c-toc); border-radius:var(--r-sm); padding:18px; margin-bottom:20px; flex-direction:column; gap:12px; }
.calc-cloud-zone code { font-size:12px; color:var(--c-toc); user-select:all; background:var(--bg-secondary); padding:6px 10px; border:1px solid var(--border); border-radius:var(--r-sm); display:block; flex:1; }
.calc-section { margin:0 0 24px; background:var(--bg-secondary); border:1px solid var(--border); border-radius:var(--r-lg); overflow:hidden; }
.calc-section h3 { font-family:var(--font-ui); font-size:1rem; font-weight:800; color:var(--text-main); padding:16px 20px; margin:0; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; background:var(--bg-tertiary); border-bottom:1px solid var(--border); }
.calc-section-body { padding:20px; }
.calc-input-grid { display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-bottom:15px; }
.calc-csv-panel { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
.calc-csv-panel span { font-size:11px; font-weight:bold; color:var(--text-muted); }
.calc-stage-btns { display:flex; gap:8px; margin-bottom:15px; flex-wrap:wrap; }
.calc-sections-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:8px; margin-bottom:20px; }
.calc-add-row { display:flex; gap:10px; align-items:flex-end; flex-wrap:wrap; background:var(--bg-tertiary); padding:14px; border-radius:var(--r-sm); border:1px solid var(--border); }
.calc-table-wrap { overflow-x:auto; margin-bottom:15px; border:1px solid var(--border); border-radius:var(--r-sm); }
.calc-table { width:100%; border-collapse:collapse; font-size:13px; }
.calc-table thead { background:var(--bg-tertiary); }
.calc-table th { padding:10px 14px; text-align:left; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.3px; color:var(--text-muted); border-bottom:2px solid var(--border); }
.calc-table td { padding:10px 14px; border-bottom:1px solid var(--border); vertical-align:top; }
.calc-table tr:last-child td { border-bottom:none; }
.calc-gantt-zone { border:1px solid var(--border); border-radius:var(--r-sm); min-height:200px; background:var(--bg-secondary); padding:16px; }
.calc-gantt-controls { display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; flex-wrap:wrap; gap:10px; }
.calc-iona-box { margin-top:20px; border:1px solid var(--border); border-left:4px solid var(--c-rag); border-radius:var(--r-sm); padding:18px; background:var(--bg-tertiary); }
.calc-iona-box h4 { margin:0 0 12px; font-size:13px; font-weight:800; color:var(--c-rag); text-transform:uppercase; }
.calc-coeff-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:12px; margin-bottom:16px; }
.calc-coeff-card { background:var(--bg-tertiary); border:1px solid var(--border); border-radius:var(--r-sm); padding:14px; }
.calc-coeff-card label { font-size:10px; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.4px; display:block; margin-bottom:6px; }
.calc-coeff-total { background:var(--bg-secondary); color:var(--text-main); border:2px solid var(--border); border-radius:var(--r-sm); padding:14px 20px; display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; }
.calc-oh-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
.calc-oh-item { display:flex; align-items:center; justify-content:space-between; gap:10px; background:var(--bg-tertiary); border:1px solid var(--border); border-radius:var(--r-sm); padding:10px 12px; }
.calc-risk-matrix { display:flex; gap:16px; flex-wrap:wrap; }
.calc-risk-table-wrap { flex:1; overflow-x:auto; }
.calc-risk-viz { display:grid; grid-template-columns:repeat(3, 48px); grid-template-rows:repeat(3, 48px); gap:4px; align-self:center; }
.calc-risk-cell { display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:800; border-radius:var(--r-sm); }
.calc-risk-cell.rm-green  { background:rgba(90,156,66,0.15); color:var(--c-toc); border:1px solid var(--c-toc); }
.calc-risk-cell.rm-yellow { background:rgba(194,153,34,0.15); color:var(--c-rag); border:1px solid var(--c-rag); }
.calc-risk-cell.rm-red    { background:rgba(176,48,48,0.15); color:var(--c-ai); border:1px solid var(--c-ai); }
.calc-totals-table { width:100%; border-collapse:collapse; font-size:13px; }
.calc-totals-table th { padding:10px 16px; text-align:left; font-size:11px; text-transform:uppercase; letter-spacing:0.4px; color:var(--text-muted); font-weight:700; border-bottom:2px solid var(--border); }
.calc-totals-table td { padding:11px 16px; border-bottom:1px solid var(--border); }
.calc-totals-footer { background:var(--bg-secondary); border:2px solid var(--border); border-radius:var(--r-lg); overflow:hidden; margin-top:20px; }
.calc-totals-footer .row-main { display:flex; align-items:center; justify-content:space-between; padding:16px 20px; border-bottom:1px solid var(--border); }
.calc-totals-footer .row-total { display:flex; align-items:center; justify-content:space-between; padding:20px; background:var(--bg-tertiary); border-bottom:1px solid var(--border); }
.calc-totals-footer .row-total span:first-child { font-size:15px; font-weight:800; text-transform:uppercase; letter-spacing:1px; color:var(--text-main); }
.calc-totals-footer .row-total span:last-child { font-size:32px; font-weight:900; color:var(--c-toc); }
.calc-toc-buffer-row { display:flex; align-items:center; justify-content:space-between; padding:12px 20px; background:rgba(232,101,26,0.08); border-top:1px solid var(--border); }
.calc-sbc-zone { margin-bottom:20px; border-left:4px solid var(--c-toc); }
.calc-sbc-body { display:none; padding:20px; background:var(--bg-tertiary); border-radius:var(--r-sm); }
.calc-sbc-info { background:var(--bg-secondary); border:1px solid var(--border); border-left:3px solid var(--c-toc); border-radius:var(--r-sm); padding:12px 16px; margin-bottom:16px; font-size:12px; color:var(--text-main); }
.calc-sbc-result { background:var(--bg-secondary); border:1px solid var(--c-toc); border-radius:var(--r-sm); padding:12px 18px; min-width:160px; }
.calc-chat-box { background:var(--bg-tertiary); border:1px solid var(--border); border-radius:var(--r-sm); padding:16px; margin:0 0 20px; }
.calc-chat-messages { max-height:200px; overflow-y:auto; margin-bottom:10px; padding:10px; background:var(--bg-secondary); border-radius:var(--r-sm); border:1px solid var(--border); font-size:12px; line-height:1.5; }

/* ── АДАПТИВ ── */
@media (max-width: 768px) {
  .calc-input-grid, .calc-oh-grid { grid-template-columns:1fr; }
  .calc-header { flex-direction:column; align-items:flex-start; }
  .calc-coeff-grid { grid-template-columns:1fr; }
}

/* ── GANTT.JS COMPATIBILITY BRIDGE (ГЛОБАЛЬНО!) ── */
:root { --muted: var(--text-muted); } /* Фикс невидимого текста из JS */

/* Возврат классов для динамических кнопок таблицы */
.btn-dark { background: var(--c-toc) !important; color: #111 !important; border: none !important; border-radius: var(--r-sm) !important; }
.btn-outline { background: transparent !important; color: var(--c-toc) !important; border: 1px solid var(--c-toc) !important; border-radius: var(--r-sm) !important; }
.btn-outline:hover { background: var(--c-toc) !important; color: #111 !important; }

/* Фикс строки ИТОГО (JS ставит светлый фон + белый текст = невидимка) */
#task-table-body tr[style*="background:var(--text-main)"] {
  background: var(--bg-tertiary) !important;
  color: var(--text-main) !important;
}
#task-table-body tr[style*="background:var(--text-main)"] td {
  color: var(--text-main) !important;
}

/* Контрастность инпутов в таблице задач */
#task-table-body tr td { color: var(--text-main); }
#task-table-body tr td input, 
#task-table-body tr td select { 
  color: var(--text-main) !important; 
  background: var(--bg-secondary) !important; 
  border: 1px solid var(--border) !important; 
  border-radius: var(--r-sm) !important;
}

/* Светлый островок для Гантта (убирает чёрное окно на всех экранах) */
#gantt-container { background: #fff !important; color: #111 !important; }
#gantt-container svg { background: transparent !important; }
#gantt-container .gantt .bar { fill: var(--c-toc) !important; }
#gantt-container .gantt .bar-progress { fill: var(--c-rag) !important; }
#gantt-container .gantt .grid-header { fill: #f5f5f5 !important; }
#gantt-container .gantt .grid-row { fill: #fff !important; }
#gantt-container .gantt .grid-row:nth-child(even) { fill: #fafafa !important; }
#gantt-container .gantt text { fill: #333 !important; font-weight: 600; }
#gantt-container .gantt .line, 
#gantt-container .gantt .tick-line { stroke: #e0e0e0 !important; }
</style>

<main class="container" style="max-width:1100px; margin:0 auto; padding:48px 48px 80px;">
<style>@media (max-width:900px) { .container { padding:32px 28px 64px !important; } }
@media (max-width:600px) { .container { padding:20px 16px 48px !important; } }</style>

<header class="calc-header">
<h1><span class="cli-only">$ </span>Оценка и Планирование ПИР</h1>
<div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
<div class="calc-mode-select">
<span>Режим:</span>
<select id="app-mode-select" onchange="toggleAppMode()">
<option value="simple">Простой (Смета)</option>
<option value="toc">Продвинутый (ТОС)</option>
</select>
</div>
<button class="btn btn-action c-toc" id="btn-save-cloud" onclick="saveToCloud()">💾 В облако</button>
<button class="btn btn-action c-ai" id="btn-reset-all">🗑 Сброс</button>
<button class="btn btn-action c-toc" style="background:#555; color:#fff; border-color:#555;" onclick="showAppManualModal()">📖 Инструкция</button>
</div>
</header>

<div id="app-info-panel" class="calc-info-panel">
<div style="display:flex; justify-content:space-between; align-items:flex-start;">
<div>
<b>💡 Как работает калькулятор:</b><br>
<span style="display:inline-block; margin-top:4px;">
• <b>Режим (в шапке):</b> Меняет <i>математику</i>. В простом режиме — классическая смета, в продвинутом (ТОС) — буферы времени и фонд рисков EMV.<br>
• <b>Роли (Директор / ГИП):</b> Разделяют <i>доступы</i>. Чтобы ГИП собрал график без доступа к финансам, нажмите «💾 В облако» и отправьте ссылку ГИПа.
</span>
</div>
<button onclick="document.getElementById('app-info-panel').style.display='none'" style="background:none; border:none; color:var(--text-muted); font-size:18px; cursor:pointer; padding:0 0 0 10px;">×</button>
</div>
</div>

<div id="cloud-link-zone" class="calc-cloud-zone">
<div>
<div style="font-size:11px; font-weight:bold; color:var(--text-muted); text-transform:uppercase;">👑 Ссылка Руководителя (Со сметой и деньгами)</div>
<div style="display:flex; align-items:center; justify-content:space-between; gap:10px; margin-top:6px;">
<code id="link-dir">...</code>
<button class="btn btn-action c-toc" onclick="copyLink('link-dir')" style="font-size:11px; padding:6px 12px; white-space:nowrap;">Копировать</button>
</div>
</div>
<div>
<div style="font-size:11px; font-weight:bold; color:var(--c-rag); text-transform:uppercase;">👷 Ссылка для ГИПа (Только график и ресурсы, без денег)</div>
<div style="display:flex; align-items:center; justify-content:space-between; gap:10px; margin-top:6px;">
<code id="link-gip">...</code>
<button class="btn btn-action c-rag" onclick="copyLink('link-gip')" style="font-size:11px; padding:6px 12px; white-space:nowrap;">Копировать</button>
</div>
</div>
</div>

<!-- 01 — РЕКВИЗИТЫ -->
<section class="calc-section">
<h3>
<span>01 — Реквизиты проекта (для КП)</span>
<button class="btn btn-action c-toc" onclick="document.getElementById('doc-parser-zone').style.display='block'" style="font-size:11px; padding:6px 12px;">🤖 Автозаполнение из файла</button>
</h3>
<div class="calc-section-body">
<div id="doc-parser-zone" style="display:none; background:var(--bg-tertiary); border:1px dashed var(--c-toc); border-radius:var(--r-sm); padding:18px; margin-bottom:18px; text-align:center;">
<div style="font-size:12px; color:var(--text-main); font-weight:bold; margin-bottom:10px;">Загрузите ТЗ или Договор (PDF, DOCX, TXT) для извлечения реквизитов</div>
<input type="file" id="parse-file-input" accept=".pdf,.docx,.txt" style="display:none;">
<button class="btn btn-action c-toc" onclick="document.getElementById('parse-file-input').click()">Выбрать файл</button>
<div id="parse-status" style="margin-top:10px; font-size:11px; color:var(--text-muted);"></div>
</div>
<div class="calc-input-grid">
<div class="input-group"><label>Заказчик <span class="sub-label">(Компания)</span></label><input type="text" id="req_client" class="std-input" placeholder="ООО «Ромашка»"></div>
<div class="input-group"><label>Объект <span class="sub-label">(Название/Адрес)</span></label><input type="text" id="req_object" class="std-input" placeholder="Жилой комплекс..."></div>
</div>
<div class="calc-input-grid">
<div class="input-group"><label>№ КП <span class="sub-label">(Номер)</span></label><input type="text" id="req_num" class="std-input" placeholder="КП-01/26"></div>
<div class="input-group"><label>Дата <span class="sub-label">(Составления)</span></label><input type="date" id="req_date" class="std-input"></div>
</div>
</div>
</section>

<!-- 01.3 — СТАВКИ -->
<section class="calc-section" id="sec-rates" style="border-left:4px solid var(--c-toc);">
<h3>
<span>01.3 — Ставки исполнителей</span>
<div class="calc-csv-panel">
<span>СТАВКИ (CSV):</span>
<button id="btn-tpl-rates" class="btn btn-action c-toc" style="padding:4px 10px; font-size:11px;">📄 Шаблон</button>
<button id="btn-export-rates" class="btn btn-action c-toc" style="padding:4px 10px; font-size:11px;">📤 Экспорт</button>
<label class="btn btn-action c-toc" style="margin:0; padding:4px 10px; font-size:11px; cursor:pointer;">📥 Импорт<input type="file" id="csv-import-rates" accept=".csv" style="display:none;"></label>
</div>
</h3>
<div class="calc-section-body">
<div class="calc-table-wrap">
<table class="calc-table">
<thead><tr>
<th style="text-align:left;">Роль / Должность</th>
<th style="text-align:right;">Ставка</th>
<th style="text-align:right;">≈ Месяц</th>
<th style="width:60px;"></th>
</tr></thead>
<tbody id="rates-tbody"></tbody>
</table>
</div>
<div class="calc-add-row">
<div class="input-group" style="min-width:180px;">
<label>Новая роль</label>
<input type="text" id="new-role-name" class="std-input" placeholder="Сметчик">
</div>
<div class="input-group">
<label>Ставка, ₽/чел-день</label>
<input type="number" id="new-role-rate" class="std-input" value="6000" min="0" step="500" style="width:120px;">
</div>
<button onclick="addRole()" class="btn btn-action c-toc">+ Добавить роль</button>
</div>
</div>
</section>

<!-- 01.5 — ИСХОДНЫЕ ДАННЫЕ -->
<section class="calc-section" id="sec-initial-data" style="border-left:4px solid var(--c-rag);">
<h3>01.5 — Комплектность исходных данных</h3>
<div class="calc-section-body">
<div style="background:var(--bg-tertiary); border:1px solid var(--border); border-radius:var(--r-sm); padding:12px 16px; margin-bottom:14px; font-size:12px; color:var(--text-main);" id="data-summary">Загрузка...</div>
<div id="initial-data-grid" style="display:grid; grid-template-columns:1fr 1fr; gap:8px;"></div>
</div>
</section>

<!-- 02 — СОСТАВ РАБОТ -->
<section class="calc-section">
<h3>02 — Состав работ и Экспертиза</h3>
<div class="calc-section-body">
<div style="background:var(--bg-tertiary); padding:14px 18px; border-radius:var(--r-sm); border:1px solid var(--c-toc); display:flex; gap:16px; align-items:center; margin-bottom:20px; flex-wrap:wrap;">
<div class="input-group" style="flex-direction:row; align-items:center; gap:10px;">
<input type="checkbox" id="exp-checkbox" style="width:18px; height:18px; accent-color:var(--c-toc); cursor:pointer;">
<div>
<label style="cursor:pointer; color:var(--text-main); font-size:13px;">Прохождение экспертизы</label>
<div class="sub-label">Учитывается в Доп. расходах</div>
</div>
</div>
<select id="exp-type" class="std-select" style="display:none; width:180px;">
<option value="gge">Главгосэкспертиза (ГГЭ)</option>
<option value="gos">Госэкспертиза (Регион)</option>
<option value="private">Частная экспертиза</option>
</select>
<div id="exp-val-wrapper" style="display:none; border:1px solid var(--border); border-radius:var(--r-sm); overflow:hidden; background:var(--bg-secondary);">
<input type="number" id="exp-value" value="20" style="width:70px; padding:6px; border:none; text-align:center; outline:none; background:transparent; color:var(--text-main);">
<select id="exp-calc-type" style="padding:6px; border:none; border-left:1px solid var(--border); background:var(--bg-tertiary); outline:none; cursor:pointer; color:var(--text-main);"><option value="pct">%</option><option value="fix">₽</option></select>
</div>
</div>
<div class="calc-stage-btns">
<button class="btn btn-action c-toc stage-btn active" data-stage="П">Стадия П (Пост. 87)</button>
<button class="btn btn-action c-toc stage-btn" data-stage="Р">Стадия Р (ГОСТ 21)</button>
<button class="btn btn-action c-toc stage-btn" data-stage="И">Изыскания</button>
<button class="btn btn-action c-toc stage-btn" data-stage="ЭС">Эскиз / АГР</button>
<button class="btn btn-action c-toc stage-btn" data-stage="ТИМ">ТИМ / Scan-to-BIM</button>
</div>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; flex-wrap:wrap; gap:10px;">
<div id="tim-modifiers" style="display:none; align-items:center; gap:10px; background:var(--bg-tertiary); padding:8px 14px; border-radius:var(--r-sm); border:1px solid var(--c-toc);">
<span style="font-size:12px; font-weight:bold; color:var(--c-toc);">LOD:</span>
<label style="font-size:12px; cursor:pointer;"><input type="radio" name="tim_lod" value="1" checked> 300 (×1.0)</label>
<label style="font-size:12px; cursor:pointer;"><input type="radio" name="tim_lod" value="1.2"> 400/500 (×1.2)</label>
</div>
<div style="display:flex; gap:8px; margin-left:auto;">
<button id="btn-sel-all" class="btn btn-action c-toc">Выбрать все</button>
<button id="btn-desel-all" class="btn btn-action c-toc">Снять выбор</button>
</div>
</div>
<div id="sections-grid" class="calc-sections-grid"></div>
<div style="background:var(--bg-tertiary); padding:16px; border-radius:var(--r-sm); border:1px solid var(--border); display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap;">
<div class="input-group" style="width:100px;">
<label>Шифр</label>
<input type="text" id="manual-sec-code" class="std-input" placeholder="ПЗ">
</div>
<div class="input-group" style="flex:1;">
<label>Наименование раздела</label>
<input type="text" id="manual-sec-name" class="std-input" placeholder="Введите название...">
</div>
<button id="btn-add-manual-sec" class="btn btn-action c-toc" style="height:36px;">+ Добавить</button>
</div>
<div style="border-top:1px solid var(--border); margin-top:20px; padding-top:20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
<button class="btn btn-action c-toc" id="btn-sync-tasks">Перенести выбранные разделы в график ↓</button>
<div class="calc-csv-panel">
<span>РАЗДЕЛЫ (CSV):</span>
<button id="btn-tpl-sec" class="btn btn-action c-toc" style="padding:4px 10px; font-size:11px;">📄 Шаблон</button>
<button id="btn-export-sec" class="btn btn-action c-toc" style="padding:4px 10px; font-size:11px;">📤 Экспорт</button>
<label class="btn btn-action c-toc" style="margin:0; padding:4px 10px; font-size:11px; cursor:pointer;">📥 Импорт<input type="file" id="csv-import-sec" accept=".csv" style="display:none;"></label>
</div>
</div>
</div>
</section>

<!-- 03 — РИСКИ -->
<section class="calc-section" id="sec-risks" style="border-left:4px solid var(--c-ai);">
<h3>
<span style="display:flex; align-items:center; gap:8px;">
    03 — Матрица рисков и Финансовый резерв
    <button type="button" onclick="showRisksHelpModal()" class="btn-help-circle" style="padding:0; width:18px; height:18px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-size:11px; font-weight:bold; background:rgba(0,0,0,0.05); border:1px solid var(--border); color:var(--text-muted); cursor:pointer; min-width:unset; line-height:1; font-family:inherit;" title="Справка по рискам и буферам">?</button>
</span>
<button id="btn-add-risk" class="btn btn-action c-ai">+ Добавить риск</button>
</h3>
<div class="calc-section-body">
<div class="calc-risk-matrix">
<div class="calc-risk-table-wrap">
<table class="calc-table" id="risks-table">
<thead>
<tr>
<th style="width:36%; text-align:left;">Описание риска / угрозы</th>
<th style="width:8%; text-align:center;">Вер.<br><span style="font-weight:400; font-size:10px;">(1–3)</span></th>
<th style="width:8%; text-align:center;">Влияние<br><span style="font-weight:400; font-size:10px;">(1–3)</span></th>
<th style="width:10%; text-align:center;">Зона</th>
<th style="width:16%; text-align:right;">Ущерб<br><span style="font-weight:400; font-size:10px;">₽</span></th>
<th style="width:14%; text-align:center;">Задержка<br><span style="font-weight:400; font-size:10px;">раб.дн.</span></th>
<th style="width:8%; text-align:center;">✕</th>
</tr>
</thead>
<tbody id="risks-tbody"></tbody>
</table>
<div style="margin-top:15px; font-size:12px; color:var(--text-muted); padding:12px; background:var(--bg-tertiary); border:1px solid var(--c-rag); border-radius:var(--r-sm);">
<b>Логика ТОС:</b> Риски с рейтингом ≥4 (Жёлтая и Красная зоны) входят в финансовый резерв через EMV. Задержки автоматически формируют ТОС-буфер в графике.
</div>
</div>
<div class="calc-risk-viz" id="risk-matrix-viz">
<div class="calc-risk-cell rm-yellow" id="rm-3-1">0</div>
<div class="calc-risk-cell rm-red"    id="rm-3-2">0</div>
<div class="calc-risk-cell rm-red"    id="rm-3-3">0</div>
<div class="calc-risk-cell rm-green"  id="rm-2-1">0</div>
<div class="calc-risk-cell rm-yellow" id="rm-2-2">0</div>
<div class="calc-risk-cell rm-red"    id="rm-2-3">0</div>
<div class="calc-risk-cell rm-green"  id="rm-1-1">0</div>
<div class="calc-risk-cell rm-green"  id="rm-1-2">0</div>
<div class="calc-risk-cell rm-yellow" id="rm-1-3">0</div>
</div>
</div>
</div>
</section>

<!-- 04 — ГРАФИК И РЕСУРСЫ -->
<section class="calc-section">
<h3 style="flex-wrap:wrap;">
<div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
<span id="title-tasks">04 — Календарный график и Ресурсы (ТОС)</span>
<span id="badge-toc-buffer" style="font-size:11px; font-weight:600; padding:4px 10px; border-radius:var(--r-sm); background:rgba(232,101,26,0.1); color:var(--c-rag);">ТОС-буфер: не рассчитан</span>
<div class="calc-csv-panel" style="padding:4px 10px;">
<span style="font-size:10px; color:var(--text-muted);">ГРАФИК (CSV):</span>
<button id="btn-tpl-tasks" class="btn btn-action c-toc" style="padding:3px 8px; font-size:10px;">📄 Шаблон</button>
<button id="btn-export-tasks" class="btn btn-action c-toc" style="padding:3px 8px; font-size:10px;">📤 Экспорт</button>
<label class="btn btn-action c-toc" style="margin:0; padding:3px 8px; font-size:10px; cursor:pointer;">📥 Импорт<input type="file" id="csv-import-tasks" accept=".csv" style="display:none;"></label>
</div>
</div>
<div style="display:flex; gap:8px; flex-wrap:wrap;">
<button id="btn-toggle-mgmt" class="btn btn-action c-toc">УП (Вкл/Выкл)</button>
<button id="btn-add-milestone" class="btn btn-action c-ai">+ Веха</button>
<button id="btn-add-task" class="btn btn-action c-toc">+ Задача</button>
</div>
</h3>
<div class="calc-section-body" style="padding:12px;">
<div class="calc-table-wrap">
<table class="calc-table">
<thead>
<tr>
<th rowspan="2" style="width:40px;">№</th>
<th rowspan="2" style="text-align:left;">Наименование задачи / раздела</th>
<th rowspan="2" class="col-dependency" style="width:120px;">Предшественник</th>
<th colspan="2">Ресурсы</th>
<th rowspan="2" style="width:100px;">Начало</th>
<th rowspan="2" style="width:100px;">Конец</th>
<th rowspan="2" style="width:50px;">Дни</th>
<th rowspan="2" id="col-fot-th" style="width:90px; text-align:right;">ФОТ, ₽</th>
</tr>
<tr>
<th style="width:130px; font-size:10px;">Исполнители</th>
<th style="width:60px; font-size:10px;">Загрузка %</th>
</tr>
</thead>
<tbody id="task-table-body"></tbody>
</table>
</div>
<div id="gantt-viz-zone">
<div class="calc-gantt-controls">
<div style="font-size:12px; font-weight:bold; color:var(--text-muted);">Масштаб графика:</div>
<div style="display:flex; gap:6px;">
<button type="button" class="btn btn-action c-toc btn-gantt-view active" data-view="Day">День</button>
<button type="button" class="btn btn-action c-toc btn-gantt-view" data-view="Week">Неделя</button>
<button type="button" class="btn btn-action c-toc btn-gantt-view" data-view="Month">Месяц</button>
</div>
</div>
<div id="gantt-container" class="calc-gantt-zone"></div>
</div>
</div>
</section>

<!-- 05 — КАПЕКС -->
<section class="calc-section" id="sec-capex" style="border-left:4px solid var(--text-muted);">
<h3>
<span>05 — Капитальные затраты и Амортизация</span>
<button onclick="toggleCapexPresets()" class="btn btn-action c-toc" style="font-size:12px;">📋 + Из списка</button>
</h3>
<div class="calc-section-body">
<div style="font-size:12px; color:var(--text-muted); margin-bottom:14px;">
Амортизация рассчитывается автоматически: <b>Стоимость ÷ Срок службы ÷ 12 мес × Длительность проекта</b>. Итог подставляется в строку «Амортизация» раздела 04.
</div>
<div id="capex-presets-list" style="display:none; background:var(--bg-tertiary); border:1px solid var(--border); border-radius:var(--r-sm); margin-bottom:14px; max-height:260px; overflow-y:auto;">
<?php
$presets = [
['Autodesk Revit (лицензия)', 180000, 3],
['AutoCAD (лицензия)', 90000, 3],
['Navisworks (лицензия)', 120000, 3],
['Рабочая станция (ПК)', 150000, 5],
['Ноутбук проектировщика', 120000, 4],
['3D-сканер (аренда, мес)', 80000, 1],
['Плоттер (покупка)', 200000, 7],
['Сервер / NAS (хранилище)', 250000, 5],
['MS Office (лицензия)', 15000, 1],
['Антивирус и безопасность', 8000, 1],
];
?>
<script>
window.CAPEX_PRESETS = [
<?php foreach ($presets as $p): ?>
  { name: <?= json_encode($p[0]) ?>, cost: <?= $p[1] ?>, life: <?= $p[2] ?>, type: 'custom' },
<?php endforeach; ?>
];
</script>
<?php
foreach ($presets as $i => $p): ?>
<div style="display:flex; justify-content:space-between; align-items:center; padding:10px 14px; border-bottom:1px solid var(--border); font-size:12px;">
<span><?= $p[0] ?> <span style="color:var(--text-muted);"><?= number_format($p[1], 0, '', ' ') ?> ₽ · <?= $p[2] ?> лет</span></span>
<button onclick="addCapexFromPreset(<?= $i ?>)" class="btn btn-action c-toc" style="padding:3px 10px; font-size:11px;">+ Добавить</button>
</div>
<?php endforeach; ?>
</div>
<div class="calc-table-wrap">
<table class="calc-table">
<thead><tr>
<th style="text-align:left;">Наименование</th>
<th style="text-align:right; width:110px;">Стоимость, ₽</th>
<th style="text-align:center; width:100px;">Срок службы</th>
<th style="text-align:right; width:110px;">₽/мес</th>
<th style="text-align:right; width:120px;">За проект, ₽</th>
<th style="width:40px;"></th>
</tr></thead>
<tbody id="capex-tbody"></tbody>
</table>
</div>
<div class="calc-add-row">
<div class="input-group" style="flex:2; min-width:200px;">
<label>Наименование</label>
<input type="text" id="capex-custom-name" class="std-input" placeholder="Лицензия Revit MEP">
</div>
<div class="input-group">
<label>Стоимость, ₽</label>
<input type="number" id="capex-custom-cost" class="std-input" value="" min="0" placeholder="150000" style="width:120px;">
</div>
<div class="input-group">
<label>Срок, лет</label>
<input type="number" id="capex-custom-life" class="std-input" value="3" min="1" max="20" style="width:70px;">
</div>
<button onclick="addCapexCustom()" class="btn btn-action c-toc">+ Добавить</button>
</div>
</div>
</section>

<!-- 06 — КОЭФФИЦИЕНТЫ -->
<section class="calc-section" id="sec-coefficients" style="border-left:4px solid var(--c-toc);">
<h3>06 — Коэффициенты сложности и Дополнительные расходы</h3>
<div class="calc-section-body">
<h4 style="margin:0 0 12px; font-size:11px; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.4px;">Коэффициенты к ФОТ — формируют итоговую цену</h4>
<div class="calc-coeff-grid">
<div class="calc-coeff-card">
<label>Срочность</label>
<select id="coef_urgency" class="std-select" style="width:100%;">
<option value="1">Стандартные сроки ×1.0</option>
<option value="1.25">Сокращённые на 25% ×1.25</option>
<option value="1.5">Срочно (−30% срока) ×1.5</option>
<option value="2">Экспресс (−50% срока) ×2.0</option>
</select>
<div style="font-size:10px; color:var(--text-muted); margin-top:5px;">Надбавка за сжатие сроков проекта</div>
</div>
<div class="calc-coeff-card">
<label>Тип заказчика</label>
<select id="coef_client" class="std-select" style="width:100%;">
<option value="1">Коммерческий ×1.0</option>
<option value="1.15">Госзаказчик (44-ФЗ) ×1.15</option>
<option value="0.9">Повторный / лояльный ×0.9</option>
<option value="1.2">VIP / нестандартные требования ×1.2</option>
</select>
<div style="font-size:10px; color:var(--text-muted); margin-top:5px;">Административная нагрузка и бюрократия</div>
</div>
<div class="calc-coeff-card">
<label>Регион объекта</label>
<select id="coef_region" class="std-select" style="width:100%;">
<option value="1.2">Москва / МО ×1.2</option>
<option value="1.1">Санкт-Петербург ×1.1</option>
<option value="1" selected>Город-миллионник ×1.0</option>
<option value="0.9">Регион ×0.9</option>
<option value="0.85">Удалённый / труднодоступный ×0.85</option>
</select>
<div style="font-size:10px; color:var(--text-muted); margin-top:5px;">Стоимость командировок и согласований</div>
</div>
<div class="calc-coeff-card">
<label>Доп. коэффициент</label>
<div style="display:flex; align-items:center; gap:8px;">
<input type="number" id="coef_extra" value="1" step="0.05" min="0.1" max="5" class="std-input" style="width:80px; text-align:center; font-size:16px; font-weight:700; padding:8px;">
<span style="font-size:22px; font-weight:800; color:var(--text-muted);">×</span>
</div>
<div style="font-size:10px; color:var(--text-muted); margin-top:5px;">Любой специфичный множитель вручную</div>
</div>
</div>
<div class="calc-coeff-total">
<div>
<div style="font-size:10px; opacity:0.7; text-transform:uppercase; letter-spacing:0.4px;">Суммарный коэффициент</div>
<div style="font-size:11px; opacity:0.6; margin-top:2px;">Срочность × Заказчик × Регион × Доп.</div>
</div>
<div style="font-size:28px; font-weight:800; color:var(--c-toc);" id="coef_total_display">×1.00</div>
</div>
<h4 style="margin:0 0 12px; font-size:11px; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.4px;">Накладные и дополнительные расходы</h4>
<div class="calc-oh-grid">
<?php
$ohItems = [
['id'=>'oh_print', 'label'=>'Печать и копирование'],
['id'=>'oh_trip',  'label'=>'Командировки'],
['id'=>'oh_siz',   'label'=>'СИЗ и охрана труда'],
['id'=>'oh_amort', 'label'=>'Амортизация (авто из 03.5)'],
['id'=>'oh_soft',  'label'=>'Закупка / аренда ПО'],
['id'=>'oh_tech',  'label'=>'Закупка техники'],
];
foreach ($ohItems as $item): ?>
<div class="calc-oh-item">
<div>
<div style="font-size:13px; color:var(--text-main);"><?= $item['label'] ?></div>
<div class="oh-pct-base" style="font-size:10px; color:var(--text-muted);">при % — от ФОТ</div>
</div>
<div style="display:flex; border:1px solid var(--border); border-radius:var(--r-sm); overflow:hidden; background:var(--bg-secondary); flex-shrink:0;">
<input type="number" id="<?= $item['id'] ?>_val" value="0" min="0" style="width:80px; padding:6px; border:none; text-align:right; outline:none; font-size:12px; background:transparent; color:var(--text-main);">
<select id="<?= $item['id'] ?>_type" style="padding:6px; border:none; border-left:1px solid var(--border); background:var(--bg-tertiary); outline:none; cursor:pointer; font-size:12px; color:var(--text-main);">
<option value="fix">₽</option>
<option value="pct">%</option>
</select>
</div>
</div>
<?php endforeach; ?>
</div>
</div>
</section>



<!-- 07 — ИТОГОВЫЙ РАСЧЁТ -->
<section class="calc-section" id="sec-totals" style="border-top:3px solid var(--c-toc);">
<h3>
<span>07 — Итоговый расчёт стоимости</span>
<div style="display:flex; gap:8px;">
<button id="btn-dl-calc" class="btn btn-action c-toc">↓ Скачать расчёт (CSV)</button>
<button class="btn btn-action c-toc" onclick="window.print()">⎙ Печать</button>
</div>
</h3>
<div class="calc-section-body">
<div id="ai-explanation" style="display:none; background:var(--bg-tertiary); border:1px solid var(--border); border-radius:var(--r-sm); padding:18px; margin:0 0 20px;">
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
<div style="font-weight:700; color:var(--text-main); font-size:13px;">🤖 ИИ-объяснение</div>
<button onclick="document.getElementById('ai-explanation').style.display='none'" style="background:none; border:none; color:var(--text-muted); cursor:pointer; font-size:16px; padding:0;">×</button>
</div>
<div id="ai-explanation-text" style="font-size:13px; line-height:1.6; color:var(--text-main);"></div>
</div>
<div class="calc-chat-box">
<div style="font-weight:700; color:var(--text-main); font-size:13px; margin-bottom:10px;">💬 Задать вопрос по ПИР</div>
<div id="chat-messages" class="calc-chat-messages"></div>
<div style="display:flex; gap:8px;">
<input type="text" id="chat-input" class="std-input" placeholder="Спроси про ПИР, ТОС, коэффициенты..." style="flex:1;">
<button onclick="askChatAI()" class="btn btn-action c-toc">Спросить</button>
</div>
</div>
<table class="calc-totals-table">
<colgroup><col style="width:44%"><col style="width:28%"><col style="width:28%"></colgroup>
<thead>
<tr>
<th>Статья</th>
<th style="text-align:center;">Параметр / Основание</th>
<th style="text-align:right;">Сумма, ₽</th>
</tr>
</thead>
<tbody>
<tr>
<td>ФОТ по графику<div style="font-size:10px; color:var(--text-muted); margin-top:2px;">ставка × дни × загрузка по всем задачам</div></td>
<td style="text-align:center;">
<div style="font-size:11px; color:var(--text-muted);">Средняя Ставка:</div>
<div style="font-size:13px; font-weight:600; color:var(--text-main);"><span id="display_rate">Расчётная</span> ₽/день</div>
</td>
<td style="text-align:right; font-weight:600; color:var(--text-main); font-size:15px;"><span id="res_fot">0</span> ₽</td>
</tr>
<tr id="row-toc-buffer" style="background:rgba(232,101,26,0.05);">
<td style="color:var(--c-rag);">Буфер ТОС на вариативность<div style="font-size:10px; color:var(--text-muted); margin-top:2px;">Защита от студенческого синдрома и Паркинсона</div></td>
<td style="text-align:center; font-size:13px; color:var(--c-rag); font-weight:700;">50% от ФОТ → ×1.5</td>
<td style="text-align:right; font-size:12px; color:var(--c-rag);">в базе ↓</td>
</tr>
<tr>
<td>С коэффициентами<div style="font-size:10px; color:var(--text-muted); margin-top:2px;">Срочность · Заказчик · Регион · Доп. множитель</div></td>
<td style="text-align:center;"><div id="coef_breakdown" style="font-size:11px; color:var(--text-muted); line-height:1.6;">—</div></td>
<td style="text-align:right; font-weight:600; color:var(--text-main); font-size:15px;"><span id="res_coeffs">0</span> ₽</td>
</tr>
<tr>
<td>Дополнительные расходы<div style="font-size:10px; color:var(--text-muted); margin-top:2px;">Печать, командировки, ПО, техника, экспертиза</div></td>
<td style="text-align:center; font-size:11px; color:var(--text-muted);">сумма статей раздела 04</td>
<td style="text-align:right; font-weight:600; color:var(--text-main); font-size:15px;"><span id="res_extra">0</span> ₽</td>
</tr>
<tr>
<td>Управление проектом<div style="font-size:10px; color:var(--text-muted); margin-top:2px;">ГИП в графике, координация</div></td>
<td style="text-align:center;">
<div style="display:flex; align-items:center; justify-content:center; gap:6px;">
<input type="number" id="calc-mgmt" value="10" min="0" max="50" class="std-input" style="width:52px; text-align:center; padding:4px 6px; font-size:13px; font-weight:700;">
<span style="font-size:11px; color:var(--text-muted);" id="mgmt-pct-label">% от ФОТ</span>
</div>
</td>
<td style="text-align:right; font-weight:600; color:var(--text-main); font-size:15px;"><span id="res_mgmt">0</span> ₽</td>
</tr>
<tr id="row-toc-risk" style="background:rgba(176,48,48,0.05);">
<td style="color:var(--c-ai);">Фонд рисков (EMV)<div style="font-size:10px; color:var(--text-muted); margin-top:2px;">Риски ≥4 баллов из матрицы × вероятность</div></td>
<td style="text-align:center; font-size:11px; color:var(--c-ai);">авторасчёт из раздела 04.5</td>
<td style="text-align:right; font-weight:600; color:var(--c-ai); font-size:15px;"><span id="res_risk_fund">0</span> ₽</td>
</tr>
<tr>
<td>Маржа<div style="font-size:10px; color:var(--text-muted); margin-top:2px;">Рентабельность бюро</div></td>
<td style="text-align:center;">
<div style="display:flex; align-items:center; justify-content:center; gap:6px;">
<input type="number" id="calc-profit" value="25" min="0" max="100" class="std-input" style="width:52px; text-align:center; padding:4px 6px; font-size:13px; font-weight:700;">
<span style="font-size:11px; color:var(--text-muted);" id="profit-pct-label">% от себест.</span>
</div>
</td>
<td style="text-align:right; font-weight:600; color:var(--text-main); font-size:15px;"><span id="res_margin">0</span> ₽</td>
</tr>
</tbody>
</table>

<div class="calc-totals-footer">
<div style="display:flex; gap:0; border-bottom:1px solid var(--border);">
<div style="flex:1; padding:14px 20px; border-right:1px solid var(--border);">
<div style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; color:var(--text-muted); margin-bottom:6px;">Маржа</div>
<div style="display:flex; align-items:center; gap:8px;">
<input type="number" id="calc-profit-bottom" value="25" min="0" max="100" onchange="document.getElementById('calc-profit').value=this.value; document.getElementById('calc-profit').dispatchEvent(new Event('change'));" style="width:54px; text-align:center; padding:5px 6px; font-size:15px; font-weight:700; background:var(--bg-tertiary); border:1px solid var(--border); color:var(--text-main); border-radius:var(--r-sm); outline:none;">
<span style="font-size:11px; color:var(--text-muted);">% от себест.</span>
</div>
</div>
<div style="flex:1; padding:14px 20px; border-right:1px solid var(--border);">
<div style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; color:var(--text-muted); margin-bottom:6px;">НДС</div>
<select id="calc-tax" style="background:var(--bg-tertiary); border:1px solid var(--border); color:var(--text-main); padding:6px 10px; border-radius:var(--r-sm); outline:none; font-size:14px; font-weight:700; cursor:pointer;">
<option value="22">22%</option>
<option value="20">20%</option>
<option value="6">6%</option>
<option value="0">Без НДС</option>
</select>
</div>
<div style="flex:1; padding:14px 20px;">
<div style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; color:var(--text-muted); margin-bottom:6px;">Аванс</div>
<div style="display:flex; align-items:center; gap:8px;">
<input type="number" id="calc-advance" value="30" min="0" max="100" style="width:54px; text-align:center; padding:5px 6px; font-size:15px; font-weight:700; background:var(--bg-tertiary); border:1px solid var(--border); color:var(--text-main); border-radius:var(--r-sm); outline:none;">
<span style="font-size:11px; color:var(--text-muted);">% от итога</span>
</div>
</div>
</div>
<div class="row-main">
<span style="color:var(--text-muted); font-size:12px; font-weight:600; text-transform:uppercase;">ИТОГО без НДС</span>
<span style="color:var(--text-main); font-size:18px; font-weight:700;"><span id="res_total_no_vat">0</span> ₽</span>
</div>
<div style="display:flex; align-items:center; justify-content:space-between; padding:10px 20px 10px 32px; border-bottom:1px solid var(--border);">
<span style="color:var(--text-muted); font-size:11px;">└ НДС <span id="tax-rate-display" style="opacity:0.7;">22%</span></span>
<span style="color:var(--text-muted); font-size:13px; font-weight:500;"><span id="res_vat">0</span> ₽</span>
</div>
<div class="row-total">
<span>ИТОГО С НДС</span>
<span><span id="res_total">0</span> ₽</span>
</div>
<div class="row-main">
<span style="color:var(--text-muted); font-size:11px;">Аванс при подписании</span>
<span style="color:var(--text-muted); font-size:13px; font-weight:600;"><span id="res_advance">0</span> ₽</span>
</div>
<div class="row-main">
<span style="color:var(--text-muted); font-size:11px;">Остаток по факту</span>
<span style="color:var(--text-muted); font-size:13px; font-weight:600;"><span id="res_remainder">0</span> ₽</span>
</div>
<div class="calc-toc-buffer-row">
<span style="color:var(--c-rag); font-size:11px;">⊕ ТОС-буфер времени <span style="color:var(--text-muted); font-size:10px;">EMV ≥4</span></span>
<span style="color:var(--c-rag); font-size:13px; font-weight:700;"><span id="res_time_buffer">0</span> раб.дн.</span>
</div>
</div>

<div style="padding:16px; border-top:1px solid var(--border); background:var(--bg-tertiary);">
<div style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.4px; color:var(--text-muted); margin-bottom:8px;">Условия для КП</div>
<div style="display:grid; grid-template-columns:300px 1fr; gap:10px; align-items:end;">
<div class="input-group">
<label>Срок <span class="sub-label">(авторасчёт по графику)</span></label>
<input type="text" id="kp_duration_txt" readonly class="std-input" placeholder="Рассчитывается автоматически" style="background:var(--bg-secondary); color:var(--text-main); font-weight:600;">
</div>
<div class="input-group">
<label>Условия оплаты <span class="sub-label">(редактируемо)</span></label>
<input type="text" id="kp_payment_terms" class="std-input" value="Аванс 30% при подписании, остаток по факту сдачи">
</div>
</div>
</div>
</div>
</section>

<!-- СБЦ -->
<section class="calc-section calc-sbc-zone">
<h3 style="cursor:pointer;" onclick="const b=document.getElementById('sbc-body');const a=document.getElementById('sbc-arrow');b.style.display=b.style.display==='none'?'block':'none';a.textContent=b.style.display==='none'?'▸':'▾';">
<span style="color:var(--c-toc);">Сравнение с нормативом СБЦ (2001)</span>
<span id="sbc-arrow" style="font-size:16px; color:var(--c-toc);">▸</span>
</h3>
<div id="sbc-body" class="calc-sbc-body">
<div class="calc-sbc-info">
<b>Как считается СБЦ:</b> Формула норматива: <code>(А + В × Х)</code>, где <b>Х</b> — площадь, объем, мощность или протяженность. Калькулятор не требует ввода "Х", так как справочников сотни. <b>Просто рассчитайте Базовую цену (в ценах 2001г) в вашей сметной программе (Гранд-Смета, Адепт) и впишите итоговую сумму в поле ниже.</b>
</div>
<div style="display:flex; gap:15px; align-items:flex-end; flex-wrap:wrap;">
<div class="input-group">
<label style="color:var(--c-toc);" title="Рассчитайте базу (А+В*Х) в профильной смете и введите сюда">Базовая цена по СБЦ <span class="sub-label">ⓘ из сметы, ₽</span></label>
<input type="number" id="sbc_base" value="500000" class="std-input" style="width:170px;">
</div>
<div class="input-group">
<label style="color:var(--c-toc);">Индекс Минстроя</label>
<input type="number" id="sbc_index" value="6.5" step="0.1" class="std-input" style="width:110px;">
</div>
<div class="input-group">
<label style="color:var(--c-toc);">Доля стадии <span class="sub-label">%</span></label>
<input type="number" id="sbc_stage_pct" value="40" class="std-input" style="width:80px;">
</div>
<div class="calc-sbc-result">
<div style="font-size:10px; color:var(--c-toc); font-weight:700; text-transform:uppercase; letter-spacing:0.4px; margin-bottom:4px;">Итого по нормативу:</div>
<div id="sbc_total_calc" style="font-size:20px; font-weight:800; color:var(--c-toc);">0 ₽</div>
</div>
<div id="sbc_diff_alert" style="flex:1; min-width:220px;"></div>
</div>
</div>
</section>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.21/mammoth.browser.min.js"></script>
<script>
if (typeof pdfjsLib !== 'undefined') {
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
}
function copyLink(elementId) {
const text = document.getElementById(elementId).textContent;
navigator.clipboard.writeText(text).then(() => {
const btn = event.target;
const orig = btn.textContent;
btn.textContent = 'Скопировано!';
setTimeout(() => btn.textContent = orig, 2000);
});
}
</script>
<script>
function explainWithAI() {
const result = document.getElementById('res_total')?.textContent || '0';
const params = {
message: "Обоснуй эту смету перед заказчиком простым языком, почему это стоит своих денег.",
result: result,
params: {
rate: document.getElementById('display_rate')?.textContent || '5000',
fot: document.getElementById('res_fot')?.textContent || '0',
coeffs: document.getElementById('res_coeffs')?.textContent || '0',
extra: document.getElementById('res_extra')?.textContent || '0',
mgmt: document.getElementById('res_mgmt')?.textContent || '0',
risk: document.getElementById('res_risk_fund')?.textContent || '0',
margin: document.getElementById('res_margin')?.textContent || '0'
}
};
const explanationDiv = document.getElementById('ai-explanation');
explanationDiv.style.display = 'block';
document.getElementById('ai-explanation-text').innerHTML = '🤖 Анализирую смету...';
fetch('api_chat.php', {
method: 'POST',
headers: { 'Content-Type': 'application/json' },
body: JSON.stringify(params)
})
.then(response => response.json())
.then(data => {
if (data.explanation) {
document.getElementById('ai-explanation-text').innerHTML = data.explanation;
} else {
document.getElementById('ai-explanation-text').innerHTML = 'Ошибка: ' + (data.error?.message || 'Неизвестная ошибка API');
}
})
.catch(error => {
document.getElementById('ai-explanation-text').innerHTML = 'Ошибка соединения: ' + error.message;
});
}
function askChatAI() {
const input = document.getElementById('chat-input');
const question = input.value.trim();
if (!question) return;
const messagesDiv = document.getElementById('chat-messages');
messagesDiv.innerHTML += `<div style="margin-bottom:8px;text-align:right;"><div style="display:inline-block;background:var(--c-toc);color:#111;padding:8px 12px;border-radius:18px 18px 4px 18px;max-width:80%;word-wrap:break-word;">${question}</div></div>`;
input.value = '';
messagesDiv.scrollTop = messagesDiv.scrollHeight;
messagesDiv.innerHTML += `<div id="ai-thinking" style="margin-bottom:8px;text-align:left;"><div style="display:inline-block;background:var(--bg-tertiary);color:var(--text-main);padding:8px 12px;border-radius:18px 18px 18px 4px;max-width:80%;word-wrap:break-word;">🤖 Думаю...</div></div>`;
messagesDiv.scrollTop = messagesDiv.scrollHeight;
const params = {
message: question,
result: document.getElementById('res_total')?.textContent || '0',
params: {
rate: document.getElementById('display_rate')?.textContent || '5000',
fot: document.getElementById('res_fot')?.textContent || '0',
coeffs: document.getElementById('res_coeffs')?.textContent || '0',
extra: document.getElementById('res_extra')?.textContent || '0',
mgmt: document.getElementById('res_mgmt')?.textContent || '0',
risk: document.getElementById('res_risk_fund')?.textContent || '0',
margin: document.getElementById('res_margin')?.textContent || '0'
}
};
fetch('api_chat.php', {
method: 'POST',
headers: { 'Content-Type': 'application/json' },
body: JSON.stringify(params)
})
.then(response => response.json())
.then(data => {
document.getElementById('ai-thinking').remove();
if (data.explanation) {
messagesDiv.innerHTML += `<div style="margin-bottom:8px;text-align:left;"><div style="display:inline-block;background:var(--bg-tertiary);color:var(--text-main);padding:8px 12px;border-radius:18px 18px 18px 4px;max-width:80%;word-wrap:break-word;">${data.explanation}</div></div>`;
} else {
messagesDiv.innerHTML += `<div style="margin-bottom:8px;text-align:left;"><div style="display:inline-block;background:rgba(176,48,48,0.1);color:var(--c-ai);padding:8px 12px;border-radius:18px 18px 18px 4px;max-width:80%;word-wrap:break-word;">Ошибка: ${data.error?.message || 'Неизвестная ошибка API'}</div></div>`;
}
messagesDiv.scrollTop = messagesDiv.scrollHeight;
})
.catch(error => {
document.getElementById('ai-thinking').remove();
messagesDiv.innerHTML += `<div style="margin-bottom:8px;text-align:left;"><div style="display:inline-block;background:rgba(176,48,48,0.1);color:var(--c-ai);padding:8px 12px;border-radius:18px 18px 18px 4px;max-width:80%;word-wrap:break-word;">Ошибка соединения: ${error.message}</div></div>`;
messagesDiv.scrollTop = messagesDiv.scrollHeight;
});
}
</script>


<script src="frappe-gantt.min.js"></script>
<script src="csv-manager.js"></script>
<script src="economics.js"></script>
<script src="risks.js"></script>
<script src="gantt.js"></script>
<script src="app.js"></script>

<!-- МОДАЛЬНОЕ ОКНО С ИНСТРУКЦИЕЙ ПО РИСКАМ -->
<div id="risks-help-modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100%; height:100%; overflow:auto; background-color:rgba(0,0,0,0.4); backdrop-filter:blur(4px); align-items:center; justify-content:center;">
    <div style="background:var(--bg-secondary); border:1px solid var(--border); border-radius:var(--r-md); padding:24px; max-width:550px; width:90%; box-shadow:0 10px 25px rgba(0,0,0,0.25); position:relative; animation:modalFadeIn 0.25s ease; font-family:inherit;">
        <button onclick="closeRisksHelpModal()" style="position:absolute; right:16px; top:16px; background:none; border:none; color:var(--text-muted); cursor:pointer; font-size:20px; font-weight:bold; padding:4px; line-height:1;">&times;</button>
        
        <h3 style="margin-top:0; color:var(--text-main); font-size:16px; border-bottom:1px solid var(--border); padding-bottom:12px; display:flex; align-items:center; gap:8px;">
            <span>❓ Инструкция: Риски и буферы в ТОС</span>
        </h3>
        
        <div style="font-size:13px; line-height:1.6; color:var(--text-main); overflow-y:auto; max-height:420px; padding-right:8px;">
            <p>Этот раздел позволяет оценить неопределенности (риски) проекта и автоматически рассчитать резервы времени и стоимости на основе <b>Теории ограничений (TOC)</b>.</p>
            
            <h4 style="color:var(--c-ai); margin:14px 0 6px; font-size:13px;">1. Оценка рисков (Матрица 1–3)</h4>
            <ul style="padding-left:18px; margin:0 0 12px; list-style-type:disc;">
                <li><b>Вероятность (1–3):</b> 1 — маловероятно, 2 — средняя частота, 3 — высокая вероятность.</li>
                <li><b>Влияние (1–3):</b> 1 — слабое, 2 — существенное, 3 — критический ущерб / срыв срока.</li>
                <li><b>Зона риска (Вероятность × Влияние):</b>
                    <br>🟩 <span style="color:#52c41a; font-weight:600;">Зелёная зона (рейтинг &lt; 4):</span> Низкие риски, не учитываются в резервах.
                    <br>🟨 <span style="color:#faad14; font-weight:600;">Жёлтая зона (рейтинг 4):</span> Средние риски, включаются в резервы.
                    <br>🟥 <span style="color:#f5222d; font-weight:600;">Красная зона (рейтинг &gt; 4):</span> Критические угрозы, включаются в резервы.
                </li>
            </ul>

            <h4 style="color:var(--c-rag); margin:14px 0 6px; font-size:13px;">2. Расчёт ТОС-буфера времени (дни)</h4>
            <p style="margin:0 0 12px;">Задержки из Жёлтой и Красной зон суммируются с учётом вероятности по методу математического ожидания:
            <br><code style="background:var(--bg-tertiary); padding:2px 6px; border-radius:3px; font-family:monospace;">Буфер = Задержка × Вероятность</code>.
            Полученные дни автоматически пристраиваются в конец календарного графика (Раздел 04) в виде единого защитного буфера времени.</p>

            <h4 style="color:var(--c-toc); margin:14px 0 6px; font-size:13px;">3. Финансовый резерв (ущерб в ₽)</h4>
            <p style="margin:0 0 12px;">Для критических рисков рассчитывается денежное математическое ожидание ущерба (EMV):
            <br><code style="background:var(--bg-tertiary); padding:2px 6px; border-radius:3px; font-family:monospace;">Резерв = Ущерб × Вероятность</code>.
            Итоговая сумма суммируется и автоматически добавляется в <b>Раздел 07</b> (Итоговый расчёт стоимости) как резерв на риски.</p>
        </div>
        
        <div style="margin-top:20px; text-align:right; border-top:1px solid var(--border); padding-top:14px;">
            <button onclick="closeRisksHelpModal()" class="btn btn-action c-toc" style="padding:6px 20px; font-size:12px; font-weight:bold;">Понятно</button>
        </div>
    </div>
</div>

<!-- МОДАЛЬНОЕ ОКНО С ПОЛНОЙ ИНСТРУКЦИЕЙ ПО КАЛЬКУЛЯТОРУ -->
<div id="app-manual-modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100%; height:100%; overflow:auto; background-color:rgba(0,0,0,0.4); backdrop-filter:blur(4px); align-items:center; justify-content:center;">
    <div style="background:var(--bg-secondary); border:1px solid var(--border); border-radius:var(--r-md); padding:24px; max-width:700px; width:92%; box-shadow:0 10px 25px rgba(0,0,0,0.25); position:relative; animation:modalFadeIn 0.25s ease; font-family:inherit;">
        <button onclick="closeAppManualModal()" style="position:absolute; right:16px; top:16px; background:none; border:none; color:var(--text-muted); cursor:pointer; font-size:20px; font-weight:bold; padding:4px; line-height:1;">&times;</button>
        
        <h3 style="margin-top:0; color:var(--text-main); font-size:18px; border-bottom:1px solid var(--border); padding-bottom:12px; display:flex; align-items:center; gap:8px;">
            <span>📖 Руководство пользователя: ПИР-калькулятор</span>
        </h3>
        
        <div style="font-size:13px; line-height:1.6; color:var(--text-main); overflow-y:auto; max-height:480px; padding-right:12px;">
            <p>Этот профессиональный калькулятор предназначен для оценки стоимости и планирования сроков проектирования (ПИР) на основе двух подходов: классического затратного метода и <b>Теории ограничений систем (TOC)</b>.</p>
            
            <h4 style="color:var(--c-toc); margin:14px 0 6px; font-size:14px; border-left:3px solid var(--c-toc); padding-left:8px;">1. Режимы работы (Простой vs ТОС)</h4>
            <p style="margin:0 0 10px;">Переключатель находится в шапке калькулятора:
            <br>• <b>Простой (Смета):</b> классический расчет трудоемкости и стоимости. Риски и буферы скрыты.
            <br>• <b>Продвинутый (ТОС):</b> активирует оценку неопределенностей (Раздел 03) и строит защитный временной буфер на диаграмме Ганта (Раздел 04).</p>

            <h4 style="color:var(--c-toc); margin:14px 0 6px; font-size:14px; border-left:3px solid var(--c-toc); padding-left:8px;">2. Ролевая модель (Директор / ГИП)</h4>
            <p style="margin:0 0 10px;">Определяется через параметр <code style="background:var(--bg-tertiary); padding:2px 4px;">?role=gip</code> или <code style="background:var(--bg-tertiary); padding:2px 4px;">?role=director</code> в URL:
            <br>• <b>Директор:</b> видит все финансовые поля, ФОТ, накладные расходы, коэффициенты и прибыль.
            <br>• <b>ГИП (Главный инженер проекта):</b> работает в конфиденциальном режиме. Финансовые поля и таблицы ФОТ скрыты. ГИП управляет только сроками, комплектностью, составом работ, рисками и экспортом графика.</p>

            <h4 style="color:var(--c-toc); margin:14px 0 6px; font-size:14px; border-left:3px solid var(--c-toc); padding-left:8px;">3. Шаг за шагом: Как пользоваться</h4>
            
            <ul style="padding-left:18px; margin:0 0 12px; list-style-type:decimal;">
                <li style="margin-bottom:8px;"><b>Раздел 01 &amp; 01.3: Задайте реквизиты и ставки.</b> Укажите шифр проекта и проверьте ставки специалистов (₽/чел-день). Вы можете добавить свою роль внизу таблицы (например, <i>Сметчик — 6000 ₽</i>) — данные мгновенно обновят базу ролей.</li>
                <li style="margin-bottom:8px;"><b>Раздел 01.5: Оцените исходные данные.</b> Отметьте галочками документы, которые уже предоставлены заказчиком. Неотмеченные пункты считаются рисками сдвига начала работ.</li>
                <li style="margin-bottom:8px;"><b>Раздел 02: Состав работ и Экспертиза.</b> Выберите стадию (П, Р, Изыскания, ЭС, ТИМ) и галочками отметьте нужные разделы проекта. При необходимости добавьте свои кастомные разделы через форму «Добавить». Нажмите кнопку <b>«Перенести выбранные разделы в график ↓»</b>.</li>
                <li style="margin-bottom:8px;"><b>Раздел 03: Матрица рисков (Режим ТОС).</b> Добавьте специфические риски проекта. Укажите вероятность (1–3), влияние (1–3), возможный ущерб в рублях и задержку в днях. Калькулятор автоматически посчитает денежный резерв (EMV) и буферные дни.</li>
                <li style="margin-bottom:8px;"><b>Раздел 04: Календарный график и Гант.</b> Задайте для каждой задачи даты начала и конца, укажите предшественников (связи) и назначьте исполнителей через кнопку <b>«Назначить ▾»</b> (можно выбрать несколько ролей и настроить процент загрузки). График автоматически пересчитает трудоемкость, даты и ФОТ в реальном времени. В режиме ТОС в конце графика автоматически пристроится <b>защитный ТОС-буфер</b>.</li>
                <li style="margin-bottom:8px;"><b>Раздел 05: Капитализация и Амортизация (CAPEX).</b> Добавьте позиции программного обеспечения или оборудования, используемые на проекте (из списка шаблонов или свои), укажите срок амортизации. Сумма автоматически спишется в накладные расходы пропорционально длительности проекта.</li>
                <li style="margin-bottom:8px;"><b>Раздел 06: Коэффициенты сложности и Накладные.</b> Настройте накладные расходы (печать, командировки, СИЗ, аренда), укажите коэффициенты сложности (срочность, тип клиента, регион) и заложите прибыльность, резервы управления и налоги.</li>
                <li style="margin-bottom:8px;"><b>Раздел 07: Итоговый расчет.</b> Ознакомьтесь с полной структурой себестоимости и итоговой стоимостью для заказчика. Настройте график платежей (аванс и финальный расчет).</li>
            </ul>

            <h4 style="color:var(--c-toc); margin:14px 0 6px; font-size:14px; border-left:3px solid var(--c-toc); padding-left:8px;">4. Облачное сохранение и Импорт</h4>
            <p style="margin:0 0 10px;">• Кнопка <b>«💾 В облако»</b> сохраняет текущие настройки проекта на сервере.
            <br>• Кнопка <b>«🗑 Сброс»</b> полностью очищает временное хранилище браузера (LocalStorage) и возвращает настройки по умолчанию.
            <br>• ГИП может скачать сформированный график в формате CSV для импорта в сторонние системы.</p>
        </div>
        
        <div style="margin-top:20px; text-align:right; border-top:1px solid var(--border); padding-top:14px;">
            <button onclick="closeAppManualModal()" class="btn btn-action c-toc" style="padding:6px 20px; font-size:12px; font-weight:bold;">Понятно</button>
        </div>
    </div>
</div>

<script>
window.showRisksHelpModal = function() {
    const m = document.getElementById('risks-help-modal');
    if (m) m.style.display = 'flex';
};
window.closeRisksHelpModal = function() {
    const m = document.getElementById('risks-help-modal');
    if (m) m.style.display = 'none';
};
window.showAppManualModal = function() {
    const m = document.getElementById('app-manual-modal');
    if (m) m.style.display = 'flex';
};
window.closeAppManualModal = function() {
    const m = document.getElementById('app-manual-modal');
    if (m) m.style.display = 'none';
};
</script>

<style>
@keyframes modalFadeIn {
    from { opacity: 0; transform: translateY(-15px); }
    to { opacity: 1; transform: translateY(0); }
}
.btn-help-circle:hover {
    background: var(--border) !important;
    color: var(--text-main) !important;
}
</style>
<?php include 'footer.php'; ?>
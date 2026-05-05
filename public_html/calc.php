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
include 'header.php';
?>

<div class="container">
    <header style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;flex-wrap:wrap;gap:15px;">
        <h1 style="margin:0;font-size:24px;color:var(--navy);">Оценка и Планирование ПИР</h1>
        
        <div style="display:flex;align-items:center;gap:15px;">
            <div style="display:flex; align-items:center; gap:10px; background:#f0f4f8; border:1px solid #d9e2ec; padding:6px 15px; border-radius:20px;">
                <span style="font-size:12px; font-weight:bold; color:var(--navy); text-transform:uppercase; letter-spacing:0.5px;">Режим:</span>
                <select id="app-mode-select" onchange="toggleAppMode()" style="background:transparent; border:none; font-size:13px; font-weight:700; color:var(--navy); outline:none; cursor:pointer;">
                    <option value="simple">Простой (Смета)</option>
                    <option value="toc">Продвинутый (ТОС)</option>
                </select>
            </div>
            
            <button class="btn btn-dark" id="btn-save-cloud" onclick="saveToCloud()" style="background:var(--green); border:none;">💾 В облако</button>
            <button class="btn" id="btn-reset-all" style="background:var(--red);color:#fff;border:none;">🗑 Сброс</button>
        </div>
    </header>

    <div id="app-info-panel" style="background:#e6f7ff; border:1px solid #91d5ff; border-radius:4px; padding:12px 16px; margin-bottom:20px; font-size:12px; color:var(--navy); line-height:1.5;">
        <div style="display:flex; justify-content:space-between; align-items:flex-start;">
            <div>
                <b style="font-size:13px; color:#0050b3;">💡 Как работает калькулятор:</b><br>
                <span style="display:inline-block; margin-top:4px;">
                    • <b>Режим (в шапке):</b> Меняет <i>математику</i>. В простом режиме считается классическая смета, в продвинутом (ТОС) добавляются буферы времени и фонд рисков EMV. Режим не скрывает деньги.<br>
                    • <b>Роли (Директор / ГИП):</b> Разделяют <i>доступы</i>. Чтобы ГИП мог собрать график без доступа к финансовым данным (ставкам, ФОТ, марже), нажмите «💾 В облако» и отправьте ему специальную ссылку ГИПа.
                </span>
            </div>
            <button onclick="document.getElementById('app-info-panel').style.display='none'" style="background:none; border:none; color:#0050b3; font-size:16px; cursor:pointer; padding:0 0 0 10px;">×</button>
        </div>
    </div>

    <div id="cloud-link-zone" style="display:none; background:#e8f4ec; border:1px solid var(--green); border-radius:4px; padding:15px; margin-bottom:20px; flex-direction:column; gap:10px;">
        <div>
            <div style="font-size:11px; font-weight:bold; color:var(--navy); text-transform:uppercase;">👑 Ссылка Руководителя (Со сметой и деньгами)</div>
            <div style="display:flex; align-items:center; justify-content:space-between; background:#fff; padding:6px 10px; border:1px solid #ccc; border-radius:3px; margin-top:4px;">
                <code id="link-dir" style="font-size:12px; color:var(--navy); user-select:all;">...</code>
                <button class="btn btn-outline" onclick="copyLink('link-dir')" style="font-size:11px; padding:3px 10px; border-color:var(--green); color:var(--green);">Копировать</button>
            </div>
        </div>
        <div>
            <div style="font-size:11px; font-weight:bold; color:var(--orange); text-transform:uppercase;">👷 Ссылка для ГИПа (Только график и ресурсы, без денег)</div>
            <div style="display:flex; align-items:center; justify-content:space-between; background:#fff; padding:6px 10px; border:1px solid #ccc; border-radius:3px; margin-top:4px;">
                <code id="link-gip" style="font-size:12px; color:var(--navy); user-select:all;">...</code>
                <button class="btn btn-outline" onclick="copyLink('link-gip')" style="font-size:11px; padding:3px 10px; border-color:var(--orange); color:var(--orange);">Копировать</button>
            </div>
        </div>
    </div>

    <section class="section" style="margin:0 0 20px;">
        <h3 class="section-title" style="display:flex; justify-content:space-between; align-items:center;">
            <span>01 — Реквизиты проекта (для КП)</span>
            <button class="btn btn-outline" onclick="document.getElementById('doc-parser-zone').style.display='block'" style="font-size:11px; padding:4px 8px; border-color:var(--navy); color:var(--navy);">🤖 Автозаполнение из файла</button>
        </h3>
        <div class="section-body">
            
            <div id="doc-parser-zone" style="display:none; background:#f9f9f9; border:1px dashed var(--navy); border-radius:4px; padding:15px; margin-bottom:15px; text-align:center;">
                <div style="font-size:12px; color:var(--navy); font-weight:bold; margin-bottom:10px;">Загрузите ТЗ или Договор (PDF, DOCX, TXT) для извлечения реквизитов</div>
                <input type="file" id="parse-file-input" accept=".pdf,.docx,.txt" style="display:none;">
                <button class="btn btn-dark" onclick="document.getElementById('parse-file-input').click()" style="padding:6px 15px; font-size:12px;">Выбрать файл</button>
                <div id="parse-status" style="margin-top:10px; font-size:11px; color:var(--muted);"></div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;margin-bottom:15px;">
                <div class="input-group"><label>Заказчик <span class="sub-label">(Компания)</span></label><input type="text" id="req_client" class="std-input" placeholder="ООО «Ромашка»"></div>
                <div class="input-group"><label>Объект <span class="sub-label">(Название/Адрес)</span></label><input type="text" id="req_object" class="std-input" placeholder="Жилой комплекс..."></div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                <div class="input-group"><label>№ КП <span class="sub-label">(Номер)</span></label><input type="text" id="req_num" class="std-input" placeholder="КП-01/26"></div>
                <div class="input-group"><label>Дата <span class="sub-label">(Составления)</span></label><input type="date" id="req_date" class="std-input"></div>
            </div>
        </div>
    </section>

    <section class="section" id="sec-rates" style="border-left:4px solid var(--navy);">
        <h3 class="section-title" style="display:flex;justify-content:space-between;align-items:center;">
            <span>01.3 — Ставки исполнителей</span>
            <div class="csv-panel">
                <span style="font-size:11px;font-weight:bold;color:var(--muted);margin-right:5px;">СТАВКИ (CSV):</span>
                <button id="btn-tpl-rates" class="btn btn-outline" style="padding:4px 8px;">📄 Шаблон</button>
                <button id="btn-export-rates" class="btn btn-outline" style="padding:4px 8px;">📤 Экспорт</button>
                <label class="btn btn-outline" style="margin:0;padding:4px 8px;cursor:pointer;">📥 Импорт<input type="file" id="csv-import-rates" accept=".csv" style="display:none;"></label>
            </div>
        </h3>
        <div class="section-body">
            <table class="res-table" style="margin-bottom:14px;">
                <thead style="background:#f5f3f0;"><tr>
                    <th style="text-align:left;">Роль / Должность</th>
                    <th style="text-align:right;">Ставка</th>
                    <th style="text-align:right;">≈ Месяц</th>
                    <th style="width:60px;"></th>
                </tr></thead>
                <tbody id="rates-tbody"></tbody>
            </table>
            <div style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
                <div class="input-group" style="min-width:180px;">
                    <label>Новая роль</label>
                    <input type="text" id="new-role-name" class="std-input" placeholder="Сметчик">
                </div>
                <div class="input-group">
                    <label>Ставка, ₽/чел-день</label>
                    <input type="number" id="new-role-rate" class="std-input" value="6000" min="0" step="500" style="width:120px;">
                </div>
                <button onclick="addRole()" class="btn btn-dark" style="height:35px;">+ Добавить роль</button>
            </div>
        </div>
    </section>

    <section class="section" id="sec-initial-data" style="border-left:4px solid var(--orange);">
        <h3 class="section-title">01.5 — Комплектность исходных данных</h3>
        <div class="section-body">
            <div style="background:#f5f3f0;border:1px solid var(--border);border-radius:4px;padding:10px 14px;margin-bottom:14px;font-size:12px;" id="data-summary">Загрузка...</div>
            <div id="initial-data-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:8px;"></div>
        </div>
    </section>

    <section class="section">
        <h3 class="section-title">02 — Состав работ и Экспертиза</h3>
        <div class="section-body">
            <div style="background:#e8f4ec;padding:12px 15px;border-radius:4px;border:1px solid var(--green);display:flex;gap:15px;align-items:center;margin-bottom:20px;">
                <div class="input-group" style="flex-direction:row;align-items:center;gap:10px;">
                    <input type="checkbox" id="exp-checkbox" style="width:18px;height:18px;accent-color:var(--green);cursor:pointer;">
                    <div>
                        <label style="cursor:pointer;color:var(--navy);font-size:13px;">Прохождение экспертизы</label>
                        <div class="sub-label">Учитывается в Доп. расходах</div>
                    </div>
                </div>
                <select id="exp-type" class="std-select" style="display:none;width:180px;">
                    <option value="gge">Главгосэкспертиза (ГГЭ)</option>
                    <option value="gos">Госэкспертиза (Регион)</option>
                    <option value="private">Частная экспертиза</option>
                </select>
                <div id="exp-val-wrapper" style="display:none;border:1px solid #ccc;border-radius:3px;overflow:hidden;background:#fff;">
                    <input type="number" id="exp-value" value="20" style="width:70px;padding:6px;border:none;text-align:center;outline:none;">
                    <select id="exp-calc-type" style="padding:6px;border:none;border-left:1px solid #eee;background:#f9f9f9;outline:none;cursor:pointer;"><option value="pct">%</option><option value="fix">₽</option></select>
                </div>
            </div>

            <div style="display:flex;gap:8px;margin-bottom:15px;flex-wrap:wrap;">
                <button class="btn btn-outline stage-btn active" data-stage="П" style="background:var(--green);color:#fff;border-color:var(--green);">Стадия П (Пост. 87)</button>
                <button class="btn btn-outline stage-btn" data-stage="Р">Стадия Р (ГОСТ 21)</button>
                <button class="btn btn-outline stage-btn" data-stage="И">Изыскания</button>
                <button class="btn btn-outline stage-btn" data-stage="ЭС">Эскиз / АГР</button>
                <button class="btn btn-outline stage-btn" data-stage="ТИМ">ТИМ / Scan-to-BIM</button>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
                <div id="tim-modifiers" style="display:none;align-items:center;gap:10px;background:#e8f4ec;padding:6px 12px;border-radius:3px;border:1px solid var(--green);">
                    <span style="font-size:12px;font-weight:bold;color:var(--green);">LOD:</span>
                    <label style="font-size:12px;cursor:pointer;"><input type="radio" name="tim_lod" value="1" checked> 300 (×1.0)</label>
                    <label style="font-size:12px;cursor:pointer;"><input type="radio" name="tim_lod" value="1.2"> 400/500 (×1.2)</label>
                </div>
                <div style="display:flex;gap:8px;margin-left:auto;">
                    <button id="btn-sel-all" class="btn btn-outline">Выбрать все</button>
                    <button id="btn-desel-all" class="btn btn-outline">Снять выбор</button>
                </div>
            </div>

            <div id="sections-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:8px;margin-bottom:20px;"></div>

            <div style="background:#f9f9f9;padding:15px;border-radius:4px;border:1px solid #eee;display:flex;gap:15px;align-items:flex-end;">
                <div class="input-group" style="width:100px;">
                    <label>Шифр</label>
                    <input type="text" id="manual-sec-code" class="std-input" placeholder="ПЗ">
                </div>
                <div class="input-group" style="flex:1;">
                    <label>Наименование раздела</label>
                    <input type="text" id="manual-sec-name" class="std-input" placeholder="Введите название...">
                </div>
                <button id="btn-add-manual-sec" class="btn btn-outline" style="height:35px;border-color:var(--green);color:var(--green);font-weight:bold;">+ Добавить</button>
            </div>

            <div style="border-top:1px solid #eee;margin-top:20px;padding-top:20px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:15px;">
                <button class="btn btn-dark" id="btn-sync-tasks">Перенести выбранные разделы в график ↓</button>
                <div class="csv-panel">
                    <span style="font-size:11px;font-weight:bold;color:var(--muted);margin-right:5px;">РАЗДЕЛЫ (CSV):</span>
                    <button id="btn-tpl-sec" class="btn btn-outline" style="padding:4px 8px;">📄 Шаблон</button>
                    <button id="btn-export-sec" class="btn btn-outline" style="padding:4px 8px;">📤 Экспорт</button>
                    <label class="btn btn-outline" style="margin:0;padding:4px 8px;cursor:pointer;">📥 Импорт<input type="file" id="csv-import-sec" accept=".csv" style="display:none;"></label>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <h3 class="section-title" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                <span id="title-tasks">03 — Календарный график и Ресурсы (ТОС)</span>
                <span id="badge-toc-buffer" style="font-size:11px;font-weight:600;padding:3px 10px;border-radius:3px;background:rgba(232,101,26,0.1);color:var(--orange);">ТОС-буфер: не рассчитан</span>
                <div class="csv-panel" style="padding:4px 10px;">
                    <span style="font-size:10px;color:var(--muted);">ГРАФИК (CSV):</span>
                    <button id="btn-tpl-tasks" class="btn btn-outline" style="padding:3px 6px;font-size:10px;">📄 Шаблон</button>
                    <button id="btn-export-tasks" class="btn btn-outline" style="padding:3px 6px;font-size:10px;">📤 Экспорт</button>
                    <label class="btn btn-outline" style="margin:0;padding:3px 6px;font-size:10px;cursor:pointer;">📥 Импорт<input type="file" id="csv-import-tasks" accept=".csv" style="display:none;"></label>
                </div>
            </div>
            <div style="display:flex;gap:8px;">
                <button id="btn-toggle-mgmt" class="btn btn-outline" style="background:var(--green);color:#fff;border:none;">УП (Вкл/Выкл)</button>
                <button id="btn-add-milestone" class="btn btn-outline" style="background:var(--red);color:#fff;border:none;">+ Веха</button>
                <button id="btn-add-task" class="btn btn-outline" style="background:var(--navy);color:#fff;border:none;">+ Задача</button>
            </div>
        </h3>
        <div class="section-body" style="padding:10px;">
            <div style="overflow-x:auto;margin-bottom:15px;border:1px solid var(--navy);border-radius:4px;">
                <table class="res-table">
                    <thead style="background:#e6dac3;border-bottom:2px solid var(--navy);">
                        <tr>
                            <th rowspan="2" style="width:40px;border-bottom:1px solid var(--navy);">№</th>
                            <th rowspan="2" style="text-align:left;border-bottom:1px solid var(--navy);">Наименование задачи / раздела</th>
                            <th rowspan="2" class="col-dependency" style="width:120px;border-bottom:1px solid var(--navy);">Предшественник</th>
                            <th colspan="2" style="border-bottom:1px solid #ccc;">Ресурсы</th>
                            <th rowspan="2" style="width:100px;border-bottom:1px solid var(--navy);">Начало</th>
                            <th rowspan="2" style="width:100px;border-bottom:1px solid var(--navy);">Конец</th>
                            <th rowspan="2" style="width:50px;border-bottom:1px solid var(--navy);">Дни</th>
                            <th rowspan="2" id="col-fot-th" style="width:90px;border-bottom:1px solid var(--navy);text-align:right;">ФОТ, ₽</th>
                        </tr>
                        <tr>
                            <th style="width:130px;font-size:10px;border-bottom:1px solid var(--navy);">Исполнители</th>
                            <th style="width:60px;font-size:10px;border-bottom:1px solid var(--navy);">Загрузка %</th>
                        </tr>
                    </thead>
                    <tbody id="task-table-body"></tbody>
                </table>
            </div>

            <div id="gantt-viz-zone">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                    <div style="font-size:12px; font-weight:bold; color:var(--navy);">Масштаб графика:</div>
                    <div style="display:flex; gap:5px;">
                        <button type="button" class="btn btn-outline btn-gantt-view active" data-view="Day" style="padding:3px 10px; font-size:11px; background:var(--green); color:#fff; border-color:var(--green);">День</button>
                        <button type="button" class="btn btn-outline btn-gantt-view" data-view="Week" style="padding:3px 10px; font-size:11px;">Неделя</button>
                        <button type="button" class="btn btn-outline btn-gantt-view" data-view="Month" style="padding:3px 10px; font-size:11px;">Месяц</button>
                    </div>
                </div>

                <div id="gantt-container" style="border:1px solid var(--navy);border-radius:4px;min-height:200px;background:#fff;"></div>
                
                <div style="margin-top:20px;border:1px solid var(--navy);border-left:4px solid var(--gold);border-radius:4px;padding:15px;background:#fff;">
                    <div style="font-weight:bold;color:var(--navy);margin-bottom:10px;font-size:13px;text-transform:uppercase;">🤖 Спросить Иону (Аудит графика по ТОС)</div>
                    <div style="display:flex;gap:10px;margin-bottom:10px;">
                        <input type="text" id="iona-input-gantt" class="std-input" placeholder="Вопрос по перегрузам..." style="flex:1;">
                        <button onclick="askIona('gantt')" id="btn-iona-gantt" class="btn btn-dark">Анализировать график</button>
                    </div>
                    <div id="iona-response-gantt" style="display:none;background:#f9f9f9;border:1px dashed #ccc;padding:15px;border-radius:4px;font-size:13px;line-height:1.5;color:#333;"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="sec-capex" style="border-left:4px solid var(--steel, #4a5a7a);">
        <h3 class="section-title" style="display:flex;justify-content:space-between;align-items:center;">
            <span>03.5 — Капитальные затраты и Амортизация</span>
            <div style="display:flex;gap:8px;">
                <button onclick="toggleCapexPresets()" class="btn btn-outline" style="font-size:12px;">📋 + Из списка</button>
            </div>
        </h3>
        <div class="section-body">

            <div style="font-size:12px;color:var(--muted);margin-bottom:14px;">
                Амортизация рассчитывается автоматически: <b>Стоимость ÷ Срок службы ÷ 12 мес × Длительность проекта</b>.
                Итоговая сумма подставляется в строку «Амортизация» раздела 04.
            </div>

            <div id="capex-presets-list" style="display:none;background:#f5f3f0;border:1px solid var(--border);border-radius:4px;margin-bottom:14px;max-height:260px;overflow-y:auto;">
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
                foreach ($presets as $i => $p): ?>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 12px;border-bottom:1px solid #eee;font-size:12px;">
                    <span><?= $p[0] ?> <span style="color:var(--muted);"><?= number_format($p[1], 0, '', ' ') ?> ₽ · <?= $p[2] ?> лет</span></span>
                    <button onclick="addCapexFromPreset(<?= $i ?>)" class="btn btn-outline" style="padding:2px 10px;font-size:11px;">+ Добавить</button>
                </div>
                <?php endforeach; ?>
            </div>

            <table class="res-table" style="margin-bottom:14px;">
                <thead style="background:#f5f3f0;"><tr>
                    <th style="text-align:left;">Наименование</th>
                    <th style="text-align:right;width:110px;">Стоимость, ₽</th>
                    <th style="text-align:center;width:100px;">Срок службы</th>
                    <th style="text-align:right;width:110px;">₽/мес</th>
                    <th style="text-align:right;width:120px;">За проект, ₽</th>
                    <th style="width:40px;"></th>
                </tr></thead>
                <tbody id="capex-tbody"></tbody>
            </table>

            <div style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;background:#f9f9f9;padding:12px;border-radius:4px;border:1px solid var(--border);">
                <div class="input-group" style="flex:2;min-width:200px;">
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
                <button onclick="addCapexCustom()" class="btn btn-dark" style="height:35px;">+ Добавить</button>
            </div>

        </div>
    </section>

    <section class="section" id="sec-coefficients" style="border-left:4px solid var(--navy);">
        <h3 class="section-title" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
            <span>04 — Коэффициенты сложности и Дополнительные расходы</span>
        </h3>
        <div class="section-body">
            <h4 style="margin:0 0 12px;font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:0.4px;">Коэффициенты к ФОТ — формируют итоговую цену</h4>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;margin-bottom:16px;">
                <div class="card" style="padding:14px;">
                    <div style="font-size:10px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.4px;margin-bottom:6px;">Срочность</div>
                    <select id="coef_urgency" class="std-select" style="width:100%;">
                        <option value="1">Стандартные сроки ×1.0</option>
                        <option value="1.25">Сокращённые на 25% ×1.25</option>
                        <option value="1.5">Срочно (−30% срока) ×1.5</option>
                        <option value="2">Экспресс (−50% срока) ×2.0</option>
                    </select>
                    <div style="font-size:10px;color:var(--muted);margin-top:5px;">Надбавка за сжатие сроков проекта</div>
                </div>

                <div class="card" style="padding:14px;">
                    <div style="font-size:10px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.4px;margin-bottom:6px;">Тип заказчика</div>
                    <select id="coef_client" class="std-select" style="width:100%;">
                        <option value="1">Коммерческий ×1.0</option>
                        <option value="1.15">Госзаказчик (44-ФЗ) ×1.15</option>
                        <option value="0.9">Повторный / лояльный ×0.9</option>
                        <option value="1.2">VIP / нестандартные требования ×1.2</option>
                    </select>
                    <div style="font-size:10px;color:var(--muted);margin-top:5px;">Административная нагрузка и бюрократия</div>
                </div>

                <div class="card" style="padding:14px;">
                    <div style="font-size:10px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.4px;margin-bottom:6px;">Регион объекта</div>
                    <select id="coef_region" class="std-select" style="width:100%;">
                        <option value="1.2">Москва / МО ×1.2</option>
                        <option value="1.1">Санкт-Петербург ×1.1</option>
                        <option value="1" selected>Город-миллионник ×1.0</option>
                        <option value="0.9">Регион ×0.9</option>
                        <option value="0.85">Удалённый / труднодоступный ×0.85</option>
                    </select>
                    <div style="font-size:10px;color:var(--muted);margin-top:5px;">Стоимость командировок и согласований</div>
                </div>

                <div class="card" style="padding:14px;">
                    <div style="font-size:10px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.4px;margin-bottom:6px;">Доп. коэффициент</div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <input type="number" id="coef_extra" value="1" step="0.05" min="0.1" max="5" class="std-input" style="width:80px;text-align:center;font-size:16px;font-weight:700;padding:8px;">
                        <span style="font-size:22px;font-weight:800;color:var(--muted);">×</span>
                    </div>
                    <div style="font-size:10px;color:var(--muted);margin-top:5px;">Любой специфичный множитель вручную</div>
                </div>
            </div>

            <div style="background:var(--navy);color:#fff;border-radius:4px;padding:12px 18px;display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
                <div>
                    <div style="font-size:10px;opacity:0.7;text-transform:uppercase;letter-spacing:0.4px;">Суммарный коэффициент</div>
                    <div style="font-size:11px;opacity:0.6;margin-top:2px;">Срочность × Заказчик × Регион × Доп.</div>
                </div>
                <div style="font-size:28px;font-weight:800;" id="coef_total_display">×1.00</div>
            </div>

            <h4 style="margin:0 0 12px;font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:0.4px;">Накладные и дополнительные расходы</h4>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
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
                <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;background:#f9f9f9;border:1px solid var(--border);border-radius:4px;padding:9px 12px;">
                    <div>
                        <div style="font-size:13px;color:var(--navy);"><?= $item['label'] ?></div>
                        <div class="oh-pct-base" style="font-size:10px;color:var(--muted);">при % — от ФОТ</div>
                    </div>
                    <div style="display:flex;border:1px solid #ccc;border-radius:3px;overflow:hidden;background:#fff;flex-shrink:0;">
                        <input type="number" id="<?= $item['id'] ?>_val" value="0" min="0"
                               style="width:80px;padding:5px 6px;border:none;text-align:right;outline:none;font-size:12px;">
                        <select id="<?= $item['id'] ?>_type"
                                style="padding:5px 6px;border:none;border-left:1px solid #eee;background:#f5f3f0;outline:none;cursor:pointer;font-size:12px;">
                            <option value="fix">₽</option>
                            <option value="pct">%</option>
                        </select>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>

    <section class="section" id="sec-risks" style="border-left:4px solid var(--red);">
        <h3 class="section-title" style="display:flex;justify-content:space-between;align-items:center;">
            <span>04.5 — Матрица рисков и Финансовый резерв</span>
            <button id="btn-add-risk" class="btn btn-outline" style="border-color:var(--red);color:var(--red);">+ Добавить риск</button>
        </h3>
        <div class="section-body">
            <div class="risk-matrix-container">
                <div style="flex:1;overflow-x:auto;">
                    <table class="res-table" id="risks-table">
                        <thead style="background:#f5f3f0;">
                            <tr>
                                <th style="width:36%;text-align:left;">Описание риска / угрозы</th>
                                <th style="width:8%;text-align:center;">Вер.<br><span style="font-weight:400;font-size:10px;">(1–3)</span></th>
                                <th style="width:8%;text-align:center;">Влияние<br><span style="font-weight:400;font-size:10px;">(1–3)</span></th>
                                <th style="width:10%;text-align:center;">Зона</th>
                                <th style="width:16%;text-align:right;">Ущерб<br><span style="font-weight:400;font-size:10px;">₽</span></th>
                                <th style="width:14%;text-align:center;">Задержка<br><span style="font-weight:400;font-size:10px;">раб.дн.</span></th>
                                <th style="width:8%;text-align:center;">✕</th>
                            </tr>
                        </thead>
                        <tbody id="risks-tbody"></tbody>
                    </table>
                    <div style="margin-top:15px;font-size:12px;color:#666;padding:10px;background:#fff8e6;border:1px solid #e6c84a;border-radius:3px;">
                        <b>Логика ТОС:</b> Риски с рейтингом ≥4 (Жёлтая и Красная зоны) входят в финансовый резерв через EMV. Задержки автоматически формируют ТОС-буфер в графике.
                    </div>
                </div>
                <div class="risk-matrix" id="risk-matrix-viz">
                    <div class="rm-label-y">Вероятность</div>
                    <div class="rm-cell rm-yellow" id="rm-3-1">0</div>
                    <div class="rm-cell rm-red"    id="rm-3-2">0</div>
                    <div class="rm-cell rm-red"    id="rm-3-3">0</div>
                    <div class="rm-cell rm-green"  id="rm-2-1">0</div>
                    <div class="rm-cell rm-yellow" id="rm-2-2">0</div>
                    <div class="rm-cell rm-red"    id="rm-2-3">0</div>
                    <div class="rm-cell rm-green"  id="rm-1-1">0</div>
                    <div class="rm-cell rm-green"  id="rm-1-2">0</div>
                    <div class="rm-cell rm-yellow" id="rm-1-3">0</div>
                    <div class="rm-label-x">Влияние (Ущерб)</div>
                </div>
            </div>
            <div style="margin-top:20px;border:1px solid var(--navy);border-left:4px solid var(--red);padding:15px;background:#fff;border-radius:4px;">
                <div style="font-weight:bold;color:var(--navy);margin-bottom:10px;font-size:13px;text-transform:uppercase;">🤖 Аудит рисков (Иона)</div>
                <div style="display:flex;gap:10px;margin-bottom:10px;">
                    <input type="text" id="iona-input-risks" class="std-input" placeholder="Задай Ионе вопрос по рискам..." style="flex:1;" value="Проанализируй нехватку данных и риски. Дай таблицу: Риск - Критичность - Компенсация.">
                    <button onclick="askIona('risks')" id="btn-iona-risks" class="btn btn-dark" style="background:var(--red);border-color:var(--red);">Анализ рисков</button>
                </div>
                <div id="iona-response-risks" style="display:none;background:#fdfdfd;border:1px dashed var(--orange);border-left:4px solid var(--orange);padding:15px;border-radius:4px;font-size:13px;line-height:1.5;color:#333;"></div>
            </div>
        </div>
    </section>

    <section class="section" id="sec-totals" style="border-top:3px solid var(--navy);margin-bottom:20px;">
        <h3 class="section-title" style="display:flex;justify-content:space-between;align-items:center;">
            <span>05 — Итоговый расчёт стоимости</span>
            <div style="display:flex;gap:8px;">
                <button id="btn-dl-calc" class="btn btn-primary">↓ Скачать расчёт (CSV)</button>
                <button class="btn btn-dark" onclick="window.print()">⎙ Печать</button>
            </div>
        </h3>

        <div id="ai-explanation" style="display:none;background:rgba(59,130,246,0.06);border:1px solid rgba(59,130,246,0.2);border-radius:8px;padding:16px 20px;margin:0 0 20px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                <div style="font-weight:700;color:var(--navy);font-size:13px;">🤖 ИИ-объяснение</div>
                <button onclick="document.getElementById('ai-explanation').style.display='none'" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:16px;padding:0;">×</button>
            </div>
            <div id="ai-explanation-text" style="font-size:13px;line-height:1.6;color:var(--navy);"></div>
        </div>

        <div style="background:rgba(245,243,240,0.5);border:1px solid var(--border);border-radius:8px;padding:16px 20px;margin:0 0 20px;">
            <div style="font-weight:700;color:var(--navy);font-size:13px;margin-bottom:10px;">💬 Задать вопрос по ПИР</div>
            <div id="chat-messages" style="max-height:200px;overflow-y:auto;margin-bottom:10px;padding:8px;background:#fff;border-radius:4px;font-size:12px;line-height:1.5;border:1px solid var(--border);"></div>
            <div style="display:flex;gap:8px;">
                <input type="text" id="chat-input" class="std-input" placeholder="Спроси про ПИР, ТОС, коэффициенты..." style="flex:1;">
                <button onclick="askChatAI()" class="btn btn-dark" style="padding:6px 14px;">Спросить</button>
            </div>
        </div>

        <div class="section-body" style="padding:0;">
            <table style="width:100%;border-collapse:collapse;font-size:13px;">
                <colgroup>
                    <col style="width:44%">
                    <col style="width:28%">
                    <col style="width:28%">
                </colgroup>
                <thead>
                    <tr style="background:#f5f3f0;border-bottom:2px solid var(--border);">
                        <th style="padding:10px 16px;text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:0.4px;color:var(--muted);font-weight:700;">Статья</th>
                        <th style="padding:10px 16px;text-align:center;font-size:11px;text-transform:uppercase;letter-spacing:0.4px;color:var(--muted);font-weight:700;">Параметр / Основание</th>
                        <th style="padding:10px 16px;text-align:right;font-size:11px;text-transform:uppercase;letter-spacing:0.4px;color:var(--muted);font-weight:700;">Сумма, ₽</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom:1px solid #eee;">
                        <td style="padding:11px 16px;color:var(--navy);">ФОТ по графику
                            <div style="font-size:10px;color:var(--muted);margin-top:2px;">ставка × дни × загрузка по всем задачам</div>
                        </td>
                        <td style="padding:11px 16px;text-align:center;">
                            <div style="font-size:11px;color:var(--muted);">Средняя Ставка:</div>
                            <div style="font-size:13px;font-weight:600;color:var(--navy);"><span id="display_rate">Расчётная</span> ₽/день</div>
                        </td>
                        <td style="padding:11px 16px;text-align:right;font-weight:600;color:var(--navy);font-size:15px;"><span id="res_fot">0</span> ₽</td>
                    </tr>
                    
                    <tr id="row-toc-buffer" style="border-bottom:1px solid #eee;background:#fffaf6;">
                        <td style="padding:11px 16px;color:var(--orange);">Буфер ТОС на вариативность
                            <div style="font-size:10px;color:var(--muted);margin-top:2px;">Защита от студенческого синдрома и Паркинсона</div>
                        </td>
                        <td style="padding:11px 16px;text-align:center;font-size:13px;color:var(--orange);font-weight:700;">50% от ФОТ → ×1.5</td>
                        <td style="padding:11px 16px;text-align:right;font-size:12px;color:var(--orange);">в базе ↓</td>
                    </tr>
                    
                    <tr style="border-bottom:1px solid #eee;">
                        <td style="padding:11px 16px;color:var(--navy);">С коэффициентами
                            <div style="font-size:10px;color:var(--muted);margin-top:2px;">Срочность · Заказчик · Регион · Доп. множитель</div>
                        </td>
                        <td style="padding:11px 16px;text-align:center;">
                            <div id="coef_breakdown" style="font-size:11px;color:var(--muted);line-height:1.6;">—</div>
                        </td>
                        <td style="padding:11px 16px;text-align:right;font-weight:600;color:var(--navy);font-size:15px;"><span id="res_coeffs">0</span> ₽</td>
                    </tr>
                    <tr style="border-bottom:1px solid #eee;">
                        <td style="padding:11px 16px;color:var(--navy);">Дополнительные расходы
                            <div style="font-size:10px;color:var(--muted);margin-top:2px;">Печать, командировки, ПО, техника, экспертиза</div>
                        </td>
                        <td style="padding:11px 16px;text-align:center;font-size:11px;color:var(--muted);">сумма статей раздела 04</td>
                        <td style="padding:11px 16px;text-align:right;font-weight:600;color:var(--navy);font-size:15px;"><span id="res_extra">0</span> ₽</td>
                    </tr>
                    <tr style="border-bottom:1px solid #eee;">
                        <td style="padding:11px 16px;color:var(--navy);">Управление проектом
                            <div style="font-size:10px;color:var(--muted);margin-top:2px;">ГИП в графике, координация</div>
                        </td>
                        <td style="padding:11px 16px;text-align:center;">
                            <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                <input type="number" id="calc-mgmt" value="10" min="0" max="50" class="std-input" style="width:52px;text-align:center;padding:4px 6px;font-size:13px;font-weight:700;">
                                <span style="font-size:11px;color:var(--muted);" id="mgmt-pct-label">% от ФОТ</span>
                            </div>
                        </td>
                        <td style="padding:11px 16px;text-align:right;font-weight:600;color:var(--navy);font-size:15px;"><span id="res_mgmt">0</span> ₽</td>
                    </tr>
                    
                    <tr id="row-toc-risk" style="border-bottom:2px solid var(--border);background:#fff5f5;">
                        <td style="padding:11px 16px;color:var(--red);">Фонд рисков (EMV)
                            <div style="font-size:10px;color:var(--muted);margin-top:2px;">Риски ≥4 баллов из матрицы × вероятность</div>
                        </td>
                        <td style="padding:11px 16px;text-align:center;font-size:11px;color:var(--red);">авторасчёт из раздела 04.5</td>
                        <td style="padding:11px 16px;text-align:right;font-weight:600;color:var(--red);font-size:15px;"><span id="res_risk_fund">0</span> ₽</td>
                    </tr>
                    
                    <tr style="border-bottom:1px solid #eee;">
                        <td style="padding:11px 16px;color:var(--navy);">Маржа
                            <div style="font-size:10px;color:var(--muted);margin-top:2px;">Рентабельность бюро</div>
                        </td>
                        <td style="padding:11px 16px;text-align:center;">
                            <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                <input type="number" id="calc-profit" value="25" min="0" max="100" class="std-input" style="width:52px;text-align:center;padding:4px 6px;font-size:13px;font-weight:700;">
                                <span style="font-size:11px;color:var(--muted);" id="profit-pct-label">% от себест.</span>
                            </div>
                        </td>
                        <td style="padding:11px 16px;text-align:right;font-weight:600;color:var(--navy);font-size:15px;"><span id="res_margin">0</span> ₽</td>
                    </tr>
                </tbody>
            </table>

            <div style="background:var(--navy);overflow:hidden;">
                <div style="background:#1a2a42;border-bottom:1px solid rgba(255,255,255,0.1);padding:12px 20px;display:flex;gap:0;">
                    <div style="flex:1;padding-right:20px;border-right:1px solid rgba(255,255,255,0.1);">
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:rgba(255,255,255,0.4);margin-bottom:6px;">Маржа</div>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <input type="number" id="calc-profit-bottom" value="25" min="0" max="100" onchange="document.getElementById('calc-profit').value=this.value; document.getElementById('calc-profit').dispatchEvent(new Event('change'));" style="width:54px;text-align:center;padding:5px 6px;font-size:15px;font-weight:700;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);color:#fff;border-radius:3px;outline:none;">
                            <span style="font-size:11px;color:rgba(255,255,255,0.4);">% от себест.</span>
                        </div>
                    </div>
                    <div style="flex:1;padding:0 20px;border-right:1px solid rgba(255,255,255,0.1);">
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:rgba(255,255,255,0.4);margin-bottom:6px;">НДС</div>
                        <select id="calc-tax" style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);color:#fff;padding:5px 8px;border-radius:3px;outline:none;font-size:14px;font-weight:700;cursor:pointer;">
                            <option value="22" style="background:#1a2540;">22%</option>
                            <option value="20" style="background:#1a2540;">20%</option>
                            <option value="6"  style="background:#1a2540;">6%</option>
                            <option value="0"  style="background:#1a2540;">Без НДС</option>
                        </select>
                    </div>
                    <div style="flex:1;padding-left:20px;">
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:rgba(255,255,255,0.4);margin-bottom:6px;">Аванс</div>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <input type="number" id="calc-advance" value="30" min="0" max="100" style="width:54px;text-align:center;padding:5px 6px;font-size:15px;font-weight:700;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);color:#fff;border-radius:3px;outline:none;">
                            <span style="font-size:11px;color:rgba(255,255,255,0.4);">% от итога</span>
                        </div>
                    </div>
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;padding:13px 20px;border-bottom:1px solid rgba(255,255,255,0.07);">
                    <span style="color:rgba(255,255,255,0.55);font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">ИТОГО без НДС</span>
                    <span style="color:rgba(255,255,255,0.8);font-size:18px;font-weight:700;"><span id="res_total_no_vat">0</span> ₽</span>
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;padding:9px 20px 9px 32px;border-bottom:1px solid rgba(255,255,255,0.07);">
                    <span style="color:rgba(255,255,255,0.35);font-size:11px;">└ НДС <span id="tax-rate-display" style="opacity:0.7;">22%</span></span>
                    <span style="color:rgba(255,255,255,0.4);font-size:13px;font-weight:500;"><span id="res_vat">0</span> ₽</span>
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 20px;border-bottom:1px solid rgba(255,255,255,0.1);background:rgba(0,0,0,0.2);">
                    <span style="color:#fff;font-size:15px;font-weight:800;text-transform:uppercase;letter-spacing:1px;">ИТОГО С НДС</span>
                    <span style="color:#fff;font-size:32px;font-weight:900;"><span id="res_total">0</span> ₽</span>
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 20px;border-bottom:1px solid rgba(255,255,255,0.05);">
                    <span style="color:rgba(255,255,255,0.45);font-size:11px;">Аванс при подписании</span>
                    <span style="color:rgba(255,255,255,0.65);font-size:13px;font-weight:600;"><span id="res_advance">0</span> ₽</span>
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 20px;border-bottom:1px solid rgba(232,101,26,0.25);">
                    <span style="color:rgba(255,255,255,0.45);font-size:11px;">Остаток по факту</span>
                    <span style="color:rgba(255,255,255,0.65);font-size:13px;font-weight:600;"><span id="res_remainder">0</span> ₽</span>
                </div>

                <div id="block-toc-buffer" style="display:flex;align-items:center;justify-content:space-between;padding:10px 20px;background:rgba(232,101,26,0.1);">
                    <span style="color:var(--orange);font-size:11px;">⊕ ТОС-буфер времени <span style="color:rgba(255,255,255,0.25);font-size:10px;">EMV ≥4</span></span>
                    <span style="color:var(--orange);font-size:13px;font-weight:700;"><span id="res_time_buffer">0</span> раб.дн.</span>
                </div>

            </div>

            <div style="padding:14px 16px;border-top:1px solid var(--border);background:#fafafa;">
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.4px;color:var(--muted);margin-bottom:8px;">Условия для КП</div>
                <div style="display:grid;grid-template-columns:300px 1fr;gap:10px;align-items:end;">
                    <div class="input-group">
                        <label>Срок <span class="sub-label">(авторасчёт по графику)</span></label>
                        <input type="text" id="kp_duration_txt" readonly class="std-input" placeholder="Рассчитывается автоматически" style="background:#f5f3f0;color:var(--navy);font-weight:600;">
                    </div>
                    <div class="input-group">
                        <label>Условия оплаты <span class="sub-label">(редактируемо)</span></label>
                        <input type="text" id="kp_payment_terms" class="std-input" value="Аванс 30% при подписании, остаток по факту сдачи">
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="section" id="sec-sbc" style="margin-bottom:20px;border-left:4px solid #91d5ff;">
        <h3 class="section-title" style="display:flex;justify-content:space-between;align-items:center;cursor:pointer;"
            onclick="const b=document.getElementById('sbc-body');const a=document.getElementById('sbc-arrow');b.style.display=b.style.display==='none'?'block':'none';a.textContent=b.style.display==='none'?'▸':'▾';">
            <span style="color:#0050b3;">Сравнение с нормативом СБЦ (2001)</span>
            <span id="sbc-arrow" style="font-size:16px;color:#0050b3;">▸</span>
        </h3>
        <div id="sbc-body" style="display:none;">
            <div class="section-body">
                
                <div style="margin-bottom:15px; font-size:12px; color:var(--navy); background:#e6f7ff; padding:10px 14px; border-radius:4px; border-left:3px solid #0050b3;">
                    <b>Как считается СБЦ:</b> Формула норматива: <code>(А + В × Х)</code>, где <b>Х</b> — это площадь, объем, мощность или протяженность. Калькулятор не требует ввода "Х", так как справочников сотни. <b>Просто рассчитайте Базовую цену (в ценах 2001г) в вашей сметной программе (Гранд-Смета, Адепт) и впишите итоговую сумму в поле ниже.</b>
                </div>

                <div style="display:flex;gap:15px;align-items:flex-end;flex-wrap:wrap;">
                    <div class="input-group">
                        <label style="color:#0050b3;" title="Рассчитайте базу (А+В*Х) в профильной смете (например, Гранд-Смета) и введите сюда">Базовая цена по СБЦ <span class="sub-label">ⓘ из сметы, ₽</span></label>
                        <input type="number" id="sbc_base" value="500000" class="std-input" style="width:170px;">
                    </div>
                    <div class="input-group">
                        <label style="color:#0050b3;">Индекс Минстроя</label>
                        <input type="number" id="sbc_index" value="6.5" step="0.1" class="std-input" style="width:110px;">
                    </div>
                    <div class="input-group">
                        <label style="color:#0050b3;">Доля стадии <span class="sub-label">%</span></label>
                        <input type="number" id="sbc_stage_pct" value="40" class="std-input" style="width:80px;">
                    </div>
                    <div style="background:#e6f7ff;border:1px solid #91d5ff;border-radius:4px;padding:10px 18px;min-width:160px;">
                        <div style="font-size:10px;color:#0050b3;font-weight:700;text-transform:uppercase;letter-spacing:0.4px;margin-bottom:4px;">Итого по нормативу:</div>
                        <div id="sbc_total_calc" style="font-size:20px;font-weight:800;color:#0050b3;">0 ₽</div>
                    </div>
                    <div id="sbc_diff_alert" style="flex:1;min-width:220px;"></div>
                </div>
            </div>
        </div>
    </section>

</div>

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
    messagesDiv.innerHTML += `<div style="margin-bottom:8px;text-align:right;"><div style="display:inline-block;background:var(--navy);color:#fff;padding:8px 12px;border-radius:18px 18px 4px 18px;max-width:80%;word-wrap:break-word;">${question}</div></div>`;
    input.value = '';
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
    
    messagesDiv.innerHTML += `<div id="ai-thinking" style="margin-bottom:8px;text-align:left;"><div style="display:inline-block;background:rgba(59,130,246,0.1);color:var(--navy);padding:8px 12px;border-radius:18px 18px 18px 4px;max-width:80%;word-wrap:break-word;">🤖 Думаю...</div></div>`;
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
            messagesDiv.innerHTML += `<div style="margin-bottom:8px;text-align:left;"><div style="display:inline-block;background:rgba(59,130,246,0.1);color:var(--navy);padding:8px 12px;border-radius:18px 18px 18px 4px;max-width:80%;word-wrap:break-word;">${data.explanation}</div></div>`;
        } else {
            messagesDiv.innerHTML += `<div style="margin-bottom:8px;text-align:left;"><div style="display:inline-block;background:rgba(255,0,0,0.1);color:var(--red);padding:8px 12px;border-radius:18px 18px 18px 4px;max-width:80%;word-wrap:break-word;">Ошибка: ${data.error?.message || 'Неизвестная ошибка API'}</div></div>`;
        }
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    })
    .catch(error => {
        document.getElementById('ai-thinking').remove();
        messagesDiv.innerHTML += `<div style="margin-bottom:8px;text-align:left;"><div style="display:inline-block;background:rgba(255,0,0,0.1);color:var(--red);padding:8px 12px;border-radius:18px 18px 18px 4px;max-width:80%;word-wrap:break-word;">Ошибка соединения: ${error.message}</div></div>`;
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    });
}
</script>

<?php include 'footer.php'; ?>
<?php 
  $pageTitle = "ПОРЕШАТОР-2077 | Департамент Стратегических Решений";
  include 'header.php'; 
?>
<style>
.p77-wrap { max-width: 1060px; margin: 0 auto; padding: 0 20px 60px; }

/* Шапка-статус */
.p77-hero { background: var(--navy); color: #fff; padding: 28px 30px 22px; border-bottom: 3px solid var(--orange); margin-bottom: 28px; display: flex; justify-content: space-between; align-items: center; }
.p77-hero-title { font-size: 13px; letter-spacing: 2px; text-transform: uppercase; opacity: 0.6; }
.p77-hero-name { font-size: 22px; font-weight: 800; letter-spacing: 1px; margin-top: 4px; }
.p77-status-badge { font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; padding: 6px 14px; border-radius: 2px; border: 1.5px solid rgba(255,255,255,0.3); color: rgba(255,255,255,0.7); }

/* Секция ввода */
.p77-section { background: #fff; border: 1px solid var(--border); border-radius: 4px; margin-bottom: 20px; overflow: hidden; }
.p77-section-head { background: #f5f3f0; border-bottom: 1px solid var(--border); padding: 11px 18px; display: flex; align-items: center; gap: 10px; }
.p77-section-num { width: 22px; height: 22px; background: var(--navy); color: #fff; border-radius: 50%; font-size: 11px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.p77-section-title { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--navy); }
.p77-section-body { padding: 20px 18px; display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 14px; }

/* Кнопка */
.p77-btn { display: block; width: 100%; background: var(--red); color: #fff; border: none; border-radius: 4px; padding: 22px; font-size: 22px; font-weight: 900; letter-spacing: 3px; text-transform: uppercase; cursor: pointer; margin-top: 28px; transition: transform 0.08s, box-shadow 0.08s; box-shadow: 0 6px 20px rgba(176,48,48,0.4); position: relative; overflow: hidden; }
.p77-btn::before { content: ''; position: absolute; inset: 0; background: linear-gradient(120deg, transparent 30%, rgba(255,255,255,0.07) 50%, transparent 70%); animation: p77-sweep 4s infinite; }
@keyframes p77-sweep { 0%{transform:translateX(-100%)} 100%{transform:translateX(200%)} }
.p77-btn:active { transform: translateY(3px); box-shadow: 0 2px 8px rgba(176,48,48,0.3); }

/* ── ДОКУМЕНТ ── */
.p77-doc { display: none; margin-top: 32px; background: #fff; border: 1px solid #bbb; box-shadow: 0 12px 40px rgba(0,0,0,0.18); padding: 52px 62px; font-family: "Times New Roman", Times, serif; color: #111; position: relative; }

.p77-doc-watermark { position: absolute; top: 42%; left: 50%; transform: translate(-50%,-50%) rotate(-38deg); font-size: 110px; font-weight: 900; color: rgba(0,0,0,0.03); white-space: nowrap; pointer-events: none; z-index: 0; }

/* Гриф */
.p77-stamp { position: absolute; top: 28px; right: 34px; border: 3px solid; padding: 8px 16px; font-size: 12px; font-weight: 900; text-transform: uppercase; letter-spacing: 1.5px; transform: rotate(-7deg); z-index: 2; background: #fff; box-shadow: 0 3px 10px rgba(0,0,0,0.1); }
.stamp-reject { color: var(--red); border-color: var(--red); }
.stamp-warn   { color: #c47a00; border-color: #c47a00; }
.stamp-ok     { color: var(--navy); border-color: var(--navy); }

.p77-doc-header { text-align: right; margin-bottom: 38px; font-size: 14.5px; line-height: 1.6; position: relative; z-index: 1; }
.p77-doc-title  { text-align: center; font-size: 21px; font-weight: bold; text-transform: uppercase; margin-bottom: 24px; position: relative; z-index: 1; }
.p77-doc-intro  { font-size: 14.5px; line-height: 1.7; text-align: justify; margin-bottom: 22px; position: relative; z-index: 1; }

.p77-table { width: 100%; border-collapse: collapse; margin-bottom: 28px; font-size: 13.5px; position: relative; z-index: 1; }
.p77-table th, .p77-table td { border: 1px solid #111; padding: 8px 7px; vertical-align: top; }
.p77-table th { background: #eee; text-align: center; font-weight: bold; font-size: 12px; }
.p77-table .g { background: #e8e4dc; font-style: italic; font-weight: bold; text-align: center; }
.p77-table .r { text-align: right; font-weight: bold; white-space: nowrap; }
.p77-total { text-align: right; font-size: 16px; font-weight: bold; border-top: 2px solid #111; padding-top: 10px; margin-bottom: 28px; position: relative; z-index: 1; }

.p77-terms { background: #f8f8f6; border-left: 4px solid var(--navy); padding: 18px 20px; font-size: 13.5px; color: #333; line-height: 1.7; margin-bottom: 28px; position: relative; z-index: 1; }
.p77-sign { display: flex; justify-content: space-between; align-items: flex-end; margin-top: 50px; font-size: 14px; position: relative; z-index: 1; }

.p77-risk-bar { width: 100%; height: 10px; background: #eee; border-radius: 5px; margin: 18px 0 4px; overflow: hidden; position: relative; z-index: 1; }
.p77-risk-fill { height: 100%; border-radius: 5px; transition: width 0.4s ease; }

.p77-doc-actions { display: none; margin-top: 14px; gap: 10px; }

@media print { .p77-hero,.p77-section,.p77-btn,.p77-doc-actions{ display:none!important; } .p77-doc{ display:block!important; box-shadow:none; border:none; padding:0; } }
</style>

<div class="p77-hero">
    <div>
        <div class="p77-hero-title">toc.chernetchenko.pro</div>
        <div class="p77-hero-name">Департамент Стратегических Решений</div>
    </div>
    <div class="p77-status-badge">Регламент ДОПУСК-2077</div>
</div>

<div class="p77-wrap">

    <!-- 1. ИДЕНТИФИКАЦИЯ -->
    <div class="p77-section">
        <div class="p77-section-head">
            <div class="p77-section-num">1</div>
            <div class="p77-section-title">Идентификация объекта</div>
        </div>
        <div class="p77-section-body">
            <div class="input-group"><label>Заказчик (юридическое лицо)</label><input type="text" id="p_client" class="std-input" value="ООО «ИнвестСтройГрупп»"></div>
            <div class="input-group"><label>ФИО лица, принимающего решения</label><input type="text" id="p_boss" class="std-input" value="г-ну Пронину А.Б."></div>
            <div class="input-group"><label>Наименование объекта</label><input type="text" id="p_obj" class="std-input" value="МФЦ «Горизонт событий»"></div>
            <div class="input-group"><label>Площадь, м²</label><input type="number" id="p_area" class="std-input" value="12000"></div>
        </div>
    </div>

    <!-- 2. СТРАТЕГИЧЕСКИЙ КОНТЕКСТ -->
    <div class="p77-section">
        <div class="p77-section-head">
            <div class="p77-section-num">2</div>
            <div class="p77-section-title">Матрица целеполагания — Совет директоров</div>
        </div>
        <div class="p77-section-body">
            <div class="input-group"><label>Тип объекта</label>
                <select id="p_type" class="std-select">
                    <option value="office">Офисы / Административные здания</option>
                    <option value="boutique">Бутиковые офисы (до 2000 м²)</option>
                    <option value="museum">Музеи / Культурные объекты</option>
                    <option value="datacenter">ЦОД / Телеком</option>
                    <option value="hotel">Отели 4–5★</option>
                    <option value="lux_dev">Девелопмент люкс-сегмента</option>
                    <option value="light_ind">Лёгкая промышленность</option>
                    <option value="unique">Уникальные (спорт, ивент)</option>
                    <option value="other">Прочее / Нецелевое</option>
                </select>
            </div>
            <div class="input-group"><label>Источник финансирования</label>
                <select id="p_finance" class="std-select">
                    <option value="private">Частный капитал / Инвестор</option>
                    <option value="corporate">Корпоративные средства</option>
                    <option value="gov">Государственные (44-ФЗ, 223-ФЗ)</option>
                    <option value="own">Строительство за свой счёт</option>
                </select>
            </div>
            <div class="input-group"><label>Стратегический статус</label>
                <select id="p_strat" class="std-select">
                    <option value="std">Рядовой проект</option>
                    <option value="corp">Стратегический актив (Совет директоров)</option>
                    <option value="founder">Личный проект Фаундера (абсолютная власть)</option>
                    <option value="pilot">Инновационный пилот (ищем синергию)</option>
                </select>
            </div>
            <div class="input-group"><label>История взаимодействия</label>
                <select id="p_history" class="std-select">
                    <option value="new">Новый заказчик</option>
                    <option value="good">Постоянный, платит вовремя (редкость)</option>
                    <option value="bad">Постоянный, платит через суд</option>
                    <option value="disaster">Уже судились. Они всё равно вернулись.</option>
                </select>
            </div>
        </div>
    </div>

    <!-- 3. ТЕХНИЧЕСКИЕ ПАРАМЕТРЫ -->
    <div class="p77-section">
        <div class="p77-section-head">
            <div class="p77-section-num">3</div>
            <div class="p77-section-title">Технические и нормативные параметры</div>
        </div>
        <div class="p77-section-body">
            <div class="input-group"><label>Стадия / вид работ</label>
                <select id="p_scope" class="std-select">
                    <option value="pir_p">ПИР: Стадия П (экспертиза)</option>
                    <option value="pir_r">ПИР: Стадия Р (рабочая документация)</option>
                    <option value="pir_pr">ПИР: П + Р (полный цикл)</option>
                    <option value="smr">СМР (строительство + поставка)</option>
                    <option value="smr_only">Только СМР без поставки</option>
                    <option value="bim">BIM-консалтинг / аудит модели</option>
                    <option value="audit">Технический аудит</option>
                </select>
            </div>
            <div class="input-group"><label>Качество ТЗ / исходных данных</label>
                <select id="p_tz" class="std-select">
                    <option value="good">Утверждено по ГОСТ 21.101</option>
                    <option value="napkin">Эскиз в мессенджере / салфетка</option>
                    <option value="vibes">«Вы профессионалы, предложите сами»</option>
                    <option value="verbal">Устные договорённости (ст. 759 ГК)</option>
                    <option value="none">ТЗ нет, зато есть референсы из Pinterest</option>
                </select>
            </div>
            <div class="input-group"><label>Уровень ТИМ (BIM)</label>
                <select id="p_bim" class="std-select">
                    <option value="none">Не требуется</option>
                    <option value="300">LOD 300 (базовая модель)</option>
                    <option value="400">LOD 400 (производственная)</option>
                    <option value="dt">Цифровой двойник (6D/7D)</option>
                    <option value="scan">Scan-to-BIM</option>
                </select>
            </div>
            <div class="input-group"><label>Экспертиза</label>
                <select id="p_exp" class="std-select">
                    <option value="none">Не требуется</option>
                    <option value="private">Частная экспертиза</option>
                    <option value="gos">Государственная (регион)</option>
                    <option value="gge">Главгосэкспертиза (ГГЭ)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- 4. ОПЕРАЦИОННЫЕ РИСКИ -->
    <div class="p77-section">
        <div class="p77-section-head">
            <div class="p77-section-num">4</div>
            <div class="p77-section-title">Операционные риски и условия сделки</div>
        </div>
        <div class="p77-section-body">
            <div class="input-group"><label>Локация объекта</label>
                <select id="p_loc" class="std-select">
                    <option value="rf">Центральная Россия</option>
                    <option value="far">Удалённые регионы (Сибирь, Приморье)</option>
                    <option value="north">Крайний Север / вечная мерзлота</option>
                    <option value="combat">Зона боевых действий</option>
                    <option value="abroad">За рубежом</option>
                </select>
            </div>
            <div class="input-group"><label>Регламент сроков</label>
                <select id="p_speed" class="std-select">
                    <option value="norm">Нормативный график</option>
                    <option value="yesterday">Сжатый цикл («Сдать вчера»)</option>
                    <option value="parallel">Параллельно стройке (риски переделок)</option>
                    <option value="retro">Ретропроект (постфактум)</option>
                    <option value="sprint">«За три дня — это много»</option>
                </select>
            </div>
            <div class="input-group"><label>Токсичность заказчика</label>
                <select id="p_toxic" class="std-select">
                    <option value="low">Адекватный (пишет в рабочее время)</option>
                    <option value="mid">Тревожный (ежедневные планёрки)</option>
                    <option value="high">Агрессивный (звонит в 23:00 в воскресенье)</option>
                    <option value="paranoid">Параноидальный (хочет присутствовать при проектировании)</option>
                    <option value="committee">Комитет из 14 человек с противоречащими позициями</option>
                </select>
            </div>
            <div class="input-group"><label>Схема оплаты</label>
                <select id="p_pay" class="std-select">
                    <option value="100">100% аванс (стандарт безопасности)</option>
                    <option value="50">50% аванс</option>
                    <option value="30">30% аванс (гос. стандарт боли)</option>
                    <option value="0">Постоплата 90 дней (кассовый разрыв)</option>
                    <option value="negative">Нас попросили проинвестировать стройку</option>
                </select>
            </div>
        </div>
    </div>

    <button class="p77-btn" onclick="p77Calculate()">
        ВЫПОЛНИТЬ РЕГЛАМЕНТНУЮ ФИЛЬТРАЦИЮ И ПОРЕШАТЬ
    </button>

    <!-- ДОКУМЕНТ -->
    <div id="p77-doc" class="p77-doc">
        <div class="p77-doc-watermark" id="p77-wm">РЕШЕНИЕ</div>
        <div class="p77-stamp stamp-ok" id="p77-stamp">СТАТУС: ДОПУЩЕН</div>

        <div class="p77-doc-header">
            <b>Лицу, принимающему решения:</b><br>
            <span id="p77-client" style="font-weight:700;font-size:16px;"></span><br>
            <span id="p77-boss"></span><br><br>
            <b>Исх. № ФИЛЬТР-<span id="p77-id"></span>/2077</b><br>
            <b>от <span id="p77-date"></span></b><br>
            <span style="font-size:12px;color:#666;">Департамент Стратегических Решений · Регламент ДОПУСК-2077</span>
        </div>

        <div class="p77-doc-title" id="p77-title">АКТ ВХОДНОГО АУДИТА И КОММЕРЧЕСКОЕ РЕШЕНИЕ</div>
        <div class="p77-doc-intro" id="p77-intro">...</div>

        <!-- Индикатор рискоскора -->
        <div id="p77-risk-section" style="position:relative;z-index:1;margin-bottom:20px;">
            <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#555;margin-bottom:4px;">Индекс операционного риска: <span id="p77-risk-val">0</span> / 20</div>
            <div class="p77-risk-bar"><div class="p77-risk-fill" id="p77-risk-fill" style="width:0%;background:var(--green);"></div></div>
            <div style="font-size:11px;color:#888;margin-top:3px;" id="p77-risk-label">Зелёная зона</div>
        </div>

        <table class="p77-table">
            <thead><tr>
                <th style="width:4%">№</th>
                <th style="width:50%">Наименование этапов и компенсируемых операций</th>
                <th style="width:8%">Ед.</th>
                <th style="width:7%">Кол.</th>
                <th style="width:12%">Цена, ₽</th>
                <th style="width:19%">Сумма, ₽</th>
            </tr></thead>
            <tbody id="p77-tbody"></tbody>
        </table>

        <div class="p77-total" id="p77-total">...</div>
        <div class="p77-terms" id="p77-terms">...</div>

        <div class="p77-sign">
            <div>
                <b>Руководитель Департамента стратегических решений</b><br><br>
                _________________ / подпись /
            </div>
            <div style="text-align:center;">
                <br><i style="font-size:18px;">М.П.</i><br>
                <span style="font-size:11px;color:#999;">Сформировано автоматически<br>Системой регламентной фильтрации v2077</span>
            </div>
            <div style="text-align:right;">
                <div id="p77-verdict-box" style="border:2px solid var(--navy);padding:12px 16px;font-size:12px;font-weight:700;color:var(--navy);transform:rotate(4deg);line-height:1.5;">
                    КП ДЕЙСТВИТЕЛЬНО<br>10 РАБОЧИХ ДНЕЙ<br>(при наличии здравого смысла<br>у обеих сторон)
                </div>
            </div>
        </div>

        <div style="margin-top:40px;text-align:center;font-size:11px;color:#aaa;position:relative;z-index:1;">
            toc.chernetchenko.pro · Управление реальностью в проектировании · Никакой магии, только расчёт
        </div>
    </div>

    <div id="p77-actions" class="p77-doc-actions">
        <button class="btn btn-dark" onclick="window.print()">⎙ Распечатать</button>
        <button class="btn btn-outline" onclick="document.getElementById('p77-doc').style.display='none';document.getElementById('p77-actions').style.display='none';">✕ Скрыть</button>
    </div>

</div>

<script>
function p77Calculate() {
    const client  = document.getElementById('p_client').value  || 'Заказчик без реквизитов';
    const boss    = document.getElementById('p_boss').value    || 'ЛПР';
    const obj     = document.getElementById('p_obj').value     || 'Объект';
    const area    = Math.abs(parseInt(document.getElementById('p_area').value)) || 100;
    const type    = document.getElementById('p_type').value;
    const finance = document.getElementById('p_finance').value;
    const strat   = document.getElementById('p_strat').value;
    const history = document.getElementById('p_history').value;
    const scope   = document.getElementById('p_scope').value;
    const tz      = document.getElementById('p_tz').value;
    const bim     = document.getElementById('p_bim').value;
    const exp     = document.getElementById('p_exp').value;
    const loc     = document.getElementById('p_loc').value;
    const speed   = document.getElementById('p_speed').value;
    const toxic   = document.getElementById('p_toxic').value;
    const pay     = document.getElementById('p_pay').value;

    // ── СТОП-ФАКТОРЫ: жёсткий отказ ──────────────────────────
    let stopReasons = [];
    let riskScore   = 0;

    if (finance === 'gov')       stopReasons.push('Государственное финансирование (44-ФЗ / 223-ФЗ). Это не наш профиль — это цирк с тендерами.');
    if (scope === 'smr_only')    stopReasons.push('Только СМР без поставки. Строим чужими руками за чужие деньги без контроля качества.');
    if (loc === 'north')         stopReasons.push('Крайний Север / вечная мерзлота. Логистика, вечная мерзлота и уходящая под землю смета не совместимы с нашим продуктом.');
    if (loc === 'combat')        stopReasons.push('Зона боевых действий. Без комментариев. Даже Голдратт не придумал буфер против этого.');
    if (pay === 'negative')      stopReasons.push('Нас попросили проинвестировать стройку. Мы проектное бюро, а не банк.');
    if (history === 'disaster' && pay === '0') stopReasons.push('Комбинация «уже судились» + «постоплата 90 дней» — это не риск, это уверенность в следующем суде.');

    // ── РИСКОСКОР: жёлтая зона ───────────────────────────────
    if (finance === 'own')      riskScore += 3;
    if (pay === '0')            riskScore += 4;
    if (pay === '30')           riskScore += 1;
    if (loc === 'far')          riskScore += 2;
    if (loc === 'abroad')       riskScore += 2;
    if (speed === 'yesterday')  riskScore += 3;
    if (speed === 'parallel')   riskScore += 4;
    if (speed === 'retro')      riskScore += 4;
    if (speed === 'sprint')     riskScore += 5;
    if (toxic === 'high')       riskScore += 3;
    if (toxic === 'paranoid')   riskScore += 4;
    if (toxic === 'committee')  riskScore += 4;
    if (strat === 'founder')    riskScore += 5;
    if (tz === 'verbal')        riskScore += 3;
    if (tz === 'none')          riskScore += 3;
    if (history === 'bad')      riskScore += 3;
    if (history === 'disaster') riskScore += 5;

    const isReject = stopReasons.length > 0;
    const isWarn   = !isReject && riskScore >= 10;
    const status   = isReject ? 'reject' : (isWarn ? 'warn' : 'ok');

    // ── ТАБЛИЦА РАСЧЁТА ───────────────────────────────────────
    const rates = { pir_p: 1400, pir_r: 2200, pir_pr: 3200, smr: 65000, smr_only: 58000, bim: 1800, audit: 900 };
    const baseCost = area * (rates[scope] || 2000);

    let html = '', total = 0, n = 1;
    const grp  = t  => html += `<tr><td colspan="6" class="g">${t}</td></tr>`;
    const line = (name, unit, qty, price) => {
        if (!qty || !price) return;
        const sum = Math.round(qty * price);
        total += sum;
        html += `<tr><td style="text-align:center">${n++}</td><td>${name}</td><td style="text-align:center">${unit}</td><td style="text-align:center">${typeof qty === 'number' ? qty.toLocaleString('ru-RU') : qty}</td><td class="r">${Math.round(price).toLocaleString('ru-RU')}</td><td class="r">${sum.toLocaleString('ru-RU')}</td></tr>`;
    };

    if (!isReject) {
        // I. Основные работы
        const snames = { pir_p:'Проектная документация (Стадия П)', pir_r:'Рабочая документация (Стадия Р)', pir_pr:'ПД + РД (полный цикл)', smr:'Строительно-монтажные работы', bim:'BIM-консалтинг', audit:'Технический аудит' };
        grp('I. Основные проектные и изыскательские работы');
        line(snames[scope] || 'Проектные работы', 'м²', area, rates[scope] || 2000);
        if (tz === 'napkin')  line('Дешифровка ТЗ с салфетки (работа аналитика-ясновидящего)', 'час', 48, 5500);
        if (tz === 'vibes')   line('Генерация ТЗ за заказчика (потому что «вы профессионалы»)', 'усл.ед.', 1, baseCost * 0.18);
        if (tz === 'verbal')  line('Документирование устных договорённостей + юридическое прикрытие', 'комплект', 1, 200000);
        if (tz === 'none')    line('Психическая реконструкция намерений заказчика по косвенным признакам', 'сессия', 8, 28000);

        // II. BIM
        if (bim !== 'none') {
            grp('II. Информационное моделирование (ТИМ / BIM)');
            if (bim === '300')  { line('Разработка информационной модели LOD 300', 'м²', area, 380); line('Clash detection и сводная модель', 'итерация', 4, 48000); }
            if (bim === '400')  { line('Детализация до LOD 400 (включая болты, которые заменят сваркой)', 'м²', area, 720); line('Параметрические семейства по ТЗ заказчика', 'шт.', 20, 16000); }
            if (bim === 'dt')   { line('Интеграция 6D/7D-параметров', 'м²', area, 1100); line('Аренда вычислительных мощностей', 'мес', 4, 200000); line('Документация о документации о модели', 'комплект', 1, 380000); }
            if (bim === 'scan') { line('3D-сканирование (мобилизация + работа + обработка)', 'тыс.м²', Math.ceil(area/1000), 900000); line('Разработка As-Built модели по сканам', 'м²', area, 950); }
        }

        // III. Экспертиза
        if (exp !== 'none') {
            grp('III. Сопровождение экспертизы');
            const expRates = { private: baseCost * 0.1, gos: baseCost * 0.15, gge: baseCost * 0.22 };
            line('Подготовка и сопровождение прохождения экспертизы', 'комплект', 1, expRates[exp] || 0);
            if (exp === 'gge') line('Устранение замечаний ГГЭ (в среднем 3 итерации)', 'итерация', 3, 140000);
        }

        // IV. Стратегия и стейкхолдеры
        grp('IV. Управление стейкхолдерами и операционное трение');
        if (strat === 'founder')  { line('Аудит сходимости видения Фаундера с законами физики и ГрК РФ', 'сессия', 4, baseCost * 0.12); line('Компенсация за внесение правок после ввода в эксплуатацию', 'пакет', 1, baseCost * 0.45); }
        else if (strat === 'corp') { line('Прохождение внутренних комитетов и служб безопасности', 'итерация', 5, 95000); line('Презентации для Совета директоров (с анимацией и кофе)', 'шт.', 6, 50000); }
        else if (strat === 'pilot') { line('Внедрение инновационных практик без гарантий успеха', 'услуга', 1, baseCost * 0.28); line('Формирование отраслевого кейса для конференций', 'комплект', 1, 350000); }
        else { line('Стандартное взаимодействие с проектной командой', 'мес', 2, 18000); }

        if (toxic === 'mid')       line('Участие ГИПа в ежедневных Zoom-планёрках вместо проектирования', 'час', 100, 8000);
        if (toxic === 'high')      { line('Психотерапевт для команды (3 курса)', 'курс', 3, 150000); line('Налог на чтение капслока в мессенджере', 'сообщение', 1200, 800); }
        if (toxic === 'paranoid')  { line('Выселение наблюдателя заказчика с рабочего места проектировщика', 'попытка', 5, 40000); line('Дополнительный монитор для наблюдателя (чтобы хоть что-то делал)', 'шт.', 1, 65000); }
        if (toxic === 'committee') { line('Координация 14 согласующих с взаимоисключающими позициями', 'страдание', 30, 18000); line('Финальный арбитраж между фракциями заказчика (3 раунда)', 'раунд', 3, 90000); }

        // V. ТОС-буфер и сроки
        grp('V. Управление рисками и ТОС-буфер (по Голдратту)');
        let buf = 0.25;
        if (speed === 'yesterday')  { line('Надбавка за сжатие цикла (ТК РФ ст. 99, практика)', 'надбавка', 1, baseCost * 0.45); buf = 0.45; }
        if (speed === 'parallel')   { line('Режим параллельного проектирования и стройки', 'надбавка', 1, baseCost * 0.70); buf = 0.55; }
        if (speed === 'retro')      { line('Ретропроектирование постфактум (юридические риски)', 'надбавка', 1, baseCost * 0.90); buf = 0.60; }
        if (speed === 'sprint')     { line('«За три дня» — экспресс-режим с полным отключением совести', 'надбавка', 1, baseCost * 1.20); buf = 0.80; }
        line('Проектный буфер ТОС (защита критической цепи)', 'резерв', 1, baseCost * buf);

        // VI. Финансовые риски
        if (pay === '0')    line('Хеджирование кассового разрыва (факторинг за ваш счёт)', 'коэфф.', 1, baseCost * 0.22);
        if (pay === '30')   line('Компенсация разрывов при авансе 30%', 'коэфф.', 1, baseCost * 0.09);
        if (loc === 'far')  line('Командировки (авиа, гостиница, суточные)', 'поездка', 6, 110000);
        if (loc === 'abroad') { line('Адаптация к иностранному законодательству', 'мес', 3, 220000); line('Валютные риски + двойная конвертация', 'надбавка', 1, total * 0.15); }
        if (history === 'bad')      line('Надбавка за историческую склонность к задержкам платежей', 'опыт', 1, baseCost * 0.2);
        if (history === 'disaster') { line('Надбавка «уже судились, рискуем снова»', 'прецедент', 1, baseCost * 0.5); line('Авансирование услуг адвоката (превентивно)', 'услуга', 1, 450000); }

        line('Кофе для команды (без него ТОС не работает, Голдратт согласен)', 'литр', 200, 1400);
        line('Амортизация морального ресурса команды', 'усл.ед.', 1, 180000);
    }

    // ── ВЫВОД ─────────────────────────────────────────────────
    const vat   = total * 0.2;
    const gross = total + vat;

    // Рискоскор-бар
    const riskPct = Math.min(100, (riskScore / 20) * 100);
    document.getElementById('p77-risk-val').textContent = riskScore;
    document.getElementById('p77-risk-fill').style.width = riskPct + '%';
    document.getElementById('p77-risk-fill').style.background = riskScore < 7 ? 'var(--green)' : riskScore < 12 ? 'var(--orange)' : 'var(--red)';
    document.getElementById('p77-risk-label').textContent = riskScore < 7 ? 'Зелёная зона — работаем' : riskScore < 12 ? 'Жёлтая зона — условно допустимо, торгуемся' : 'Красная зона — ценник заставит их передумать';

    // Гриф
    const stamp = document.getElementById('p77-stamp');
    const wm    = document.getElementById('p77-wm');
    const vb    = document.getElementById('p77-verdict-box');
    if (status === 'reject') {
        stamp.className = 'p77-stamp stamp-reject';
        stamp.textContent = 'ОТКЛОНЁН ПО РЕГЛАМЕНТУ';
        wm.textContent = 'ОТКАЗ';
        vb.style.borderColor = 'var(--red)'; vb.style.color = 'var(--red)';
        vb.innerHTML = 'ПРОЕКТ ИСКЛЮЧЁН<br>ИЗ ВОРОНКИ<br>БЕЗ ПРАВА<br>НА ОБЖАЛОВАНИЕ';
    } else if (status === 'warn') {
        stamp.className = 'p77-stamp stamp-warn';
        stamp.textContent = 'УСЛОВНО ДОПУЩЕН';
        wm.textContent = 'РИСК';
        vb.style.borderColor = '#c47a00'; vb.style.color = '#c47a00';
    } else {
        stamp.className = 'p77-stamp stamp-ok';
        stamp.textContent = 'СТАТУС: ДОПУЩЕН';
        wm.textContent = 'РЕШЕНИЕ';
    }

    // Заголовок и intro
    document.getElementById('p77-title').textContent = isReject
        ? 'АКТ ОБ ОТКАЗЕ В РАССМОТРЕНИИ ПРЕДЛОЖЕНИЯ'
        : 'АКТ ВХОДНОГО АУДИТА И КОММЕРЧЕСКОЕ РЕШЕНИЕ';

    const introBase = `На основании п. 3.1 Регламента ДОПУСК-2077 проведена формализация исходных данных по объекту <b>«${obj}»</b> (площадь: <b>${area.toLocaleString('ru-RU')} м²</b>).`;

    if (isReject) {
        document.getElementById('p77-intro').innerHTML = `${introBase}<br><br><b>Результат:</b> Проект классифицирован как <b style="color:var(--red)">НЕЦЕЛЕСООБРАЗНЫЙ К РАССМОТРЕНИЮ</b>.<br><br><b>Выявленные стоп-факторы:</b><ul style="margin:10px 0 18px 22px;line-height:1.8;">${stopReasons.map(r => `<li>${r}</li>`).join('')}</ul>Согласно п. 1.4 Регламента ДОПУСК-2077, объекты с данными параметрами исключаются из воронки продаж без права на обжалование. Департамент не распыляет ресурсы на нестратегические активы.`;
        document.getElementById('p77-tbody').innerHTML = `<tr><td colspan="6" style="text-align:center;padding:40px;font-weight:bold;color:var(--red);font-size:15px;">РАСЧЁТ СТОИМОСТИ НЕ ПРОИЗВОДИЛСЯ.<br>ПРОЕКТ ИСКЛЮЧЁН ИЗ ВОРОНКИ.</td></tr>`;
        document.getElementById('p77-total').innerHTML = 'ИТОГО: <b>—</b>';
    } else {
        const warnNote = isWarn ? `<br><br><b style="color:#c47a00;">⚠ Предупреждение:</b> Рискоскор проекта <b>${riskScore}/20</b>. Ценовое предложение скорректировано буферами рисков. Условно рекомендовано к согласованию на Совете директоров.` : '';
        document.getElementById('p77-intro').innerHTML = `${introBase}${warnNote} Расчёт произведён методом ТОС с применением Коэффициента Абсолютного Здравого Смысла (КАЗС) и поправки на Волатильность Исходных Данных (ВИД).`;
        document.getElementById('p77-tbody').innerHTML = html;
        document.getElementById('p77-total').innerHTML = `ИТОГО БЕЗ НДС: <span style="font-size:18px;">${Math.round(total).toLocaleString('ru-RU')} ₽</span><br>НДС 20%: <span style="color:#666;">${Math.round(vat).toLocaleString('ru-RU')} ₽</span><br><div style="margin-top:8px;padding-top:8px;border-top:1px dashed #000;">ИТОГО С НДС: <span style="font-size:22px;font-weight:900;color:var(--red);">${Math.round(gross).toLocaleString('ru-RU')} ₽</span></div>`;
    }

    // Условия
    const payLabel   = document.getElementById('p_pay').options[document.getElementById('p_pay').selectedIndex].text;
    const stratLabel = document.getElementById('p_strat').options[document.getElementById('p_strat').selectedIndex].text;
    const speedLabel = document.getElementById('p_speed').options[document.getElementById('p_speed').selectedIndex].text;
    document.getElementById('p77-terms').innerHTML = isReject ? '<b>Резолюция:</b> Проект не передаётся в работу. Дальнейшее взаимодействие возможно после устранения стоп-факторов и повторной подачи заявки.' : `<b>Условия сотрудничества (по Голдратту, ст. 759 ГК РФ и здравому смыслу):</b><br><br>1. <b>Финансирование:</b> схема «${payLabel}». При кассовых разрывах активируется п. 5.2 Регламента (Стоп-Кран).<br>2. <b>Сроки:</b> режим «${speedLabel}». Буфер ТОС заложен. Промежуточных дедлайнов по разделам нет — только дедлайн проекта.<br>3. <b>Исходные данные:</b> каждый отсутствующий документ переносит старт на срок получения + 3 рабочих дня акклиматизации команды.<br>4. <b>Согласования:</b> задержки свыше 5 рабочих дней переносят дедлайн без доп. соглашений.<br>5. <b>Стратегический статус:</b> «${stratLabel}». Несоответствие критериям допуска → экономическая фильтрация (см. итог).<br>6. <b>Кофе</b> включён в смету. Это не шутка.`;

    // Служебные поля
    document.getElementById('p77-client').textContent = client;
    document.getElementById('p77-boss').textContent   = boss;
    document.getElementById('p77-id').textContent     = Math.floor(Math.random() * 800) + 100;
    document.getElementById('p77-date').textContent   = new Date().toLocaleDateString('ru-RU');

    const doc = document.getElementById('p77-doc');
    doc.style.display = 'block';
    document.getElementById('p77-actions').style.display = 'flex';
    doc.scrollIntoView({ behavior: 'smooth', block: 'start' });
}
</script>

<?php include 'footer.php'; ?>

<?php
$pageTitle       = 'Зачем ТОС проектировщику | DBR анимация, сравнение подходов';
$pageDescription = 'Как применить ТОС в проектном бюро. Интерактивная анимация DBR. Сравнение традиционного подхода и ТОС. Пошаговый сценарий внедрения для ГИП, начальника отдела, директора бюро.';
$pageKeywords    = 'ТОС проектирование, DBR барабан буфер канат, внедрение ТОС, управление проектным бюро, очередность задач проектировщик, принцип эстафеты';
$canonicalUrl    = 'https://toc.chernetchenko.pro/why';
$breadcrumbs     = [
['name' => 'Главная',       'url' => 'https://toc.chernetchenko.pro/'],
['name' => 'Зачем это?', 'url' => 'https://toc.chernetchenko.pro/why'],
];
$siteId = 'toc';
include 'header.php';
?>
<style>
/* ── Анимация DBR ── */
.dbr-wrap {
background: var(--bg-secondary);
border: 1px solid var(--border);
border-radius: var(--r-sm);
padding: 28px 24px 20px;
box-shadow: var(--sh-sm);
margin-bottom: 36px;
}
.dbr-title {
font-size: 13px;
font-weight: 700;
color: var(--text-muted);
text-transform: uppercase;
letter-spacing: 0.5px;
margin-bottom: 6px;
}
.dbr-subtitle {
font-size: 13px;
color: var(--text-muted);
margin-bottom: 20px;
}
.dbr-canvas {
position: relative;
width: 100%;
height: 130px;
background: var(--bg-tertiary);
border-radius: var(--r-sm);
overflow: hidden;
border: 1px solid var(--border);
}
/* Дорожка */
.dbr-track {
position: absolute;
bottom: 28px;
left: 0;
right: 0;
height: 2px;
background: var(--border);
}
/* Буфер */
.dbr-buffer {
position: absolute;
right: 0;
bottom: 0;
top: 0;
width: 100px;
background: rgba(232, 101, 26, 0.15);
border-left: 3px solid var(--c-rag);
display: flex;
flex-direction: column;
align-items: center;
justify-content: center;
gap: 2px;
}
.dbr-buffer-label {
font-size: 11px;
font-weight: 700;
color: var(--c-rag);
text-align: center;
line-height: 1.3;
}
/* Барабан */
.dbr-drum {
position: absolute;
bottom: 16px;
font-size: 22px;
transition: left 0.05s linear;
filter: drop-shadow(var(--sh-sm));
z-index: 5;
}
.dbr-drum-label {
position: absolute;
bottom: 0px;
font-size: 9px;
font-weight: 700;
color: var(--c-toc);
white-space: nowrap;
transform: translateX(-50%);
}
/* Коты-исполнители */
.dbr-cat {
position: absolute;
bottom: 16px;
font-size: 20px;
transition: left 0.05s linear;
z-index: 4;
}
.dbr-cat-label {
position: absolute;
bottom: 0px;
font-size: 9px;
color: var(--text-muted);
white-space: nowrap;
transform: translateX(-50%);
}
/* Канат */
.dbr-rope {
position: absolute;
bottom: 30px;
height: 2px;
background: var(--c-rag);
z-index: 3;
transform-origin: left center;
}
/* Эстафетная палочка */
.dbr-baton {
position: absolute;
bottom: 22px;
width: 18px;
height: 6px;
background: var(--c-rag);
border-radius: var(--r-sm);
z-index: 6;
transition: left 0.05s linear;
}
/* Контролы */
.dbr-controls {
display: flex;
align-items: center;
gap: 12px;
margin-top: 12px;
flex-wrap: wrap;
}
.dbr-speed-label {
font-size: 12px;
color: var(--text-muted);
white-space: nowrap;
}
.dbr-speed-btn {
padding: 5px 12px;
font-size: 12px;
font-weight: 700;
border: 1px solid var(--border);
background: var(--bg-secondary);
color: var(--c-toc);
border-radius: var(--r-sm);
cursor: pointer;
transition: background 0.15s;
}
.dbr-speed-btn.active {
background: var(--c-toc);
color: #111;
border-color: var(--c-toc);
}
.dbr-toggle {
padding: 6px 16px;
font-size: 12px;
font-weight: 700;
background: var(--c-rag);
color: #111;
border: none;
border-radius: var(--r-sm);
cursor: pointer;
transition: opacity 0.15s;
margin-left: auto;
}
.dbr-toggle:hover { opacity: 0.85; }
.dbr-status {
font-size: 12px;
color: var(--text-muted);
margin-top: 8px;
min-height: 16px;
}
/* Легенда */
.dbr-legend {
display: flex;
gap: 20px;
margin-top: 14px;
flex-wrap: wrap;
}
.dbr-legend-item {
display: flex;
align-items: center;
gap: 6px;
font-size: 12px;
color: var(--text-muted);
}
.legend-dot {
width: 12px;
height: 12px;
border-radius: var(--r-lg);
flex-shrink: 0;
}
/* Сравнительная таблица */
.compare-table {
width: 100%;
border-collapse: collapse;
font-size: 13.5px;
}
.compare-table th {
padding: 12px 16px;
text-align: left;
font-size: 12px;
font-weight: 700;
text-transform: uppercase;
letter-spacing: 0.3px;
border-bottom: 2px solid var(--border);
}
.compare-table td {
padding: 11px 16px;
border-bottom: 1px solid var(--border);
vertical-align: top;
line-height: 1.5;
}
.compare-table tr:last-child td { border-bottom: none; }
.compare-table .col-old {
color: var(--text-muted);
text-decoration: none;
}
.compare-table .col-new {
color: var(--c-toc);
font-weight: 500;
}
.col-label {
font-weight: 700;
color: var(--c-toc);
white-space: nowrap;
}
/* Кому карточки */
.audience-grid {
display: grid;
grid-template-columns: 1fr 1fr 1fr;
gap: 18px;
margin-top: 28px;
}
.audience-card {
background: var(--bg-secondary);
border: 1px solid var(--border);
border-top: 3px solid var(--c-toc);
border-radius: var(--r-sm);
padding: 20px;
box-shadow: var(--sh-sm);
}
.audience-card h3 {
margin-top: 0;
font-size: 15px;
color: var(--c-toc);
margin-bottom: 6px;
}
.audience-card .role {
font-size: 11px;
font-weight: 700;
text-transform: uppercase;
color: var(--c-rag);
letter-spacing: 0.4px;
margin-bottom: 12px;
}
.audience-card p {
font-size: 13px;
color: var(--text-main);
margin: 0;
line-height: 1.55;
}
/* Боли */
.pain-grid {
display: grid;
grid-template-columns: 1fr 1fr;
gap: 14px;
margin-top: 22px;
}
.pain-item {
display: flex;
gap: 12px;
background: var(--bg-secondary);
border: 1px solid var(--border);
border-radius: var(--r-sm);
padding: 14px 16px;
align-items: flex-start;
}
.pain-icon {
font-size: 20px;
flex-shrink: 0;
margin-top: 1px;
}
.pain-item h4 {
margin: 0 0 4px;
font-size: 14px;
color: var(--c-toc);
}
.pain-item p {
margin: 0;
font-size: 13px;
color: var(--text-muted);
line-height: 1.5;
}
/* Трек применения */
.apply-steps {
margin-top: 28px;
display: flex;
flex-direction: column;
gap: 0;
}
.apply-step {
display: flex;
gap: 0;
position: relative;
}
.apply-step-line {
display: flex;
flex-direction: column;
align-items: center;
width: 40px;
flex-shrink: 0;
}
.apply-step-num {
width: 28px;
height: 28px;
border-radius: var(--r-lg);
background: var(--c-toc);
color: #111;
font-size: 13px;
font-weight: 700;
display: flex;
align-items: center;
justify-content: center;
flex-shrink: 0;
z-index: 1;
}
.apply-step-connector {
width: 2px;
flex: 1;
background: var(--border);
min-height: 20px;
}
.apply-step:last-child .apply-step-connector { display: none; }
.apply-step-content {
padding: 2px 0 24px 16px;
flex: 1;
}
.apply-step-content h4 {
margin: 0 0 5px;
font-size: 15px;
color: var(--c-toc);
line-height: 1.3;
}
.apply-step-content p {
margin: 0;
font-size: 13.5px;
color: var(--text-main);
line-height: 1.55;
}
.apply-step-content .step-tag {
display: inline-block;
font-size: 10px;
font-weight: 700;
text-transform: uppercase;
letter-spacing: 0.4px;
color: var(--c-rag);
border: 1px solid rgba(232,101,26,0.3);
padding: 2px 7px;
border-radius: var(--r-sm);
margin-bottom: 5px;
}
</style>

<main class="container" style="max-width: 900px; margin: 0 auto; padding: 48px 48px 80px;">
<style>@media (max-width: 900px) { .container { padding: 32px 28px 64px !important; } }
@media (max-width: 600px) { .container { padding: 20px 16px 48px !important; } }</style>

<article itemscope itemtype="https://schema.org/TechArticle">
<header class="article-hero">
<h1 itemprop="headline">Зачем это проектировщику?</h1>
<p class="hero-desc" itemprop="description">
Теория ограничений (ТОС) — это не просто философия, а строгий инженерный подход к управлению проектным бюро. Если вы устали от сорванных сроков, непредсказуемой себестоимости ПИР и вечного режима «тушения пожаров», внедрение ТОС и метода критической цепи (CCPM) станет единственным системным решением.
</p>
<div class="text-lead" style="margin-top:2rem;">
В этой статье мы подробно разберем, почему традиционное планирование проектов терпит крах на этапе реализации, как правильно рассчитывать стоимость проектно-изыскательских работ (ПИР) и зачем каждому ГИПу нужен ресурсный график.
</div>
</header>

<h2 class="section-title">Системный кризис управления ПИР</h2>
<div class="content-block" style="padding-top:0;">
<p class="text-main">
Управление проектным институтом или архитектурным бюро кардинально отличается от управления классическим конвейерным производством. Проектировщики работают в условиях высокой степени неопределенности: постоянные изменения исходных данных, задержки в выдаче технических условий, новые требования заказчиков и корректировки от экспертизы. В таких условиях традиционные методы проектного управления (PMBOK, классический метод критического пути) дают серьезные сбои.
</p>
<p class="text-main">
Главная проблема заключается в локальной оптимизации. Руководители отделов стремятся загрузить своих специалистов на 100%, полагая, что это обеспечит максимальную рентабельность. Однако физика процессов доказывает обратное: система, загруженная на 100%, теряет пропускную способность, а любые колебания приводят к экспоненциальному росту очередей задач. Результат — постоянное срывание сроков и неконтролируемый рост накладных расходов.
</p>
<p class="text-main">
<b>Расчет стоимости ПИР</b> в таких условиях превращается в гадание. Классические сметы по сборникам базовых цен (СБЦ) часто не отражают реальных трудозатрат, а отсутствие достоверного ресурсного графика не позволяет ГИПу оценить реальную себестоимость проекта до его начала.
</p>
</div>

<!-- КОМУ -->
<h2 class="section-title">Кому это нужно</h2>
<p style="color:var(--text-muted);font-size:0.95rem;">Три роли, которые страдают от одних и тех же проблем по-разному.</p>
<div class="audience-grid">
<div class="audience-card" style="border-top-color: var(--c-rag);">
<div class="role">ГИП / Руководитель проекта</div>
<h3>Проект постоянно горит</h3>
<p>Вы ведёте 3–5 проектов одновременно, и в каждом что-то опаздывает. Исполнители говорят «скоро», а потом оказывается, что они даже не начинали. Вы тушите пожары вместо того, чтобы управлять.</p>
<p style="margin-top:10px; font-size:13px; color:var(--c-rag); font-weight:600;">ТОС даст: ресурсный график, буфер проекта и видимость реального статуса — без ежедневных планёрок.</p>
</div>
<div class="audience-card" style="border-top-color: var(--c-rag);">
<div class="role">Начальник отдела</div>
<h3>Люди загружены, толку нет</h3>
<p>У вас все заняты. Никто не сидит без дела. Но разделы выходят с опозданием, и вы не понимаете почему. Скорее всего — многозадачность и неправильная очерёдность работ.</p>
<p style="margin-top:10px; font-size:13px; color:var(--c-rag); font-weight:600;">ТОС даст: принцип эстафеты, управление очерёдностью, понимание где реальное узкое место.</p>
</div>
<div class="audience-card" style="border-top-color: var(--c-rag);">
<div class="role">Директор / BIM-директор</div>
<h3>Система даёт сбои системно</h3>
<p>Героизм и переработки стали нормой. Хорошие инженеры выгорают. Каждый проект — отдельная история срыва. Дело не в людях — дело в процессе и метриках, по которым их оценивают.</p>
<p style="margin-top:10px; font-size:13px; color:var(--c-rag); font-weight:600;">ТОС даст: системный взгляд на причины срывов, правильные KPI и инструмент для их исправления.</p>
</div>
</div>

<!-- БОЛИ -->
<h2 class="section-title" style="margin-top:48px;">Узнаваемые боли</h2>
<p style="color:var(--text-muted); font-size:14px; margin-bottom:0;">Каждая из них имеет системную причину — и системное решение.</p>
<div class="pain-grid">
<div class="pain-item">
<div class="pain-icon">📅</div>
<div>
<h4>«Договорной график — это фантастика»</h4>
<p>Сроки в договоре ставятся под давлением коммерции, без учёта реальной загрузки. Потом начинается игра в исходные данные, смежников и форс-мажор. ТОС учит отделять договорной график от проектного и строить буфер на разницу.</p>
</div>
</div>
<div class="pain-item">
<div class="pain-icon">🔁</div>
<div>
<h4>«Все заняты — ничего не двигается»</h4>
<p>100% загрузка команды при нулевом прогрессе проекта — классический симптом плохой многозадачности и неправильных приоритетов. Занятость и продуктивность — разные вещи.</p>
</div>
</div>
<div class="pain-item">
<div class="pain-icon">🚒</div>
<div>
<h4>«Мы всегда работаем в режиме пожара»</h4>
<p>Режим пожара — это не признак сложности проекта. Это признак отсутствия буфера и плохого управления очерёдностью. Пожар приходит туда, где нет защитного слоя времени.</p>
</div>
</div>
<div class="pain-item">
<div class="pain-icon">📊</div>
<div>
<h4>«Отчёты есть, управления нет»</h4>
<p>Еженедельные отчёты о процентах готовности — это данные, а не информация. Руководителю нужен один индикатор: насколько буфер израсходован? Это и есть реальный статус проекта.</p>
</div>
</div>
<div class="pain-item">
<div class="pain-icon">🧑‍💻</div>
<div>
<h4>«Внедрили BIM — стало только сложнее»</h4>
<p>Технология снимает физическое ограничение, но не управленческое. Если правила работы не меняются вместе с инструментом — выгода от BIM равна нулю. Автоматизированный хаос хуже ручного.</p>
</div>
</div>
<div class="pain-item">
<div class="pain-icon">😤</div>
<div>
<h4>«Хорошие люди уходят, средние остаются»</h4>
<p>Если система наказывает тех, кто сдаёт задачи раньше срока (урезая им следующие сроки), и поощряет тех, кто держит всё до дедлайна — хорошие люди уйдут туда, где их не обманывают.</p>
</div>
</div>
</div>

<!-- АНИМАЦИЯ DBR -->
<h2 class="section-title" style="margin-top:48px;">Барабан — Буфер — Канат (DBR)</h2>
<p style="color:var(--text-muted); font-size:14px; margin-bottom:22px;">Механизм управления потоком в производстве и проектировании. Один ресурс задаёт темп всей системы — остальные подстраиваются.</p>
<div class="dbr-wrap">
<div class="dbr-title">Интерактивная модель</div>
<div class="dbr-subtitle">Барабан (🥁) — ограничение системы. Канат держит остальных в правильном темпе. Буфер защищает дедлайн.</div>
<div class="dbr-canvas" id="dbr-canvas">
<div class="dbr-track"></div>
<div class="dbr-buffer">
<div class="dbr-buffer-label">БУФЕР<br>проекта</div>
</div>
<!-- DOM элементы создаются через JS -->
</div>
<div class="dbr-controls">
<span class="dbr-speed-label">Скорость барабана:</span>
<button class="dbr-speed-btn btn btn-action c-toc active" data-speed="1" onclick="setDrumSpeed(1, this)">Норма</button>
<button class="dbr-speed-btn btn btn-action c-toc" data-speed="0.5" onclick="setDrumSpeed(0.5, this)">Медленно</button>
<button class="dbr-speed-btn btn btn-action c-toc" data-speed="2" onclick="setDrumSpeed(2, this)">Быстро</button>
<button class="dbr-toggle btn btn-action c-rag" id="dbr-toggle-btn" onclick="toggleDBR()"><span class="cli-only">▶</span><span class="mgmt-only">Запустить</span></button>
</div>
<div class="dbr-status" id="dbr-status">Нажмите «Запустить» чтобы увидеть как работает система</div>
<div class="dbr-legend">
<div class="dbr-legend-item">
<span style="font-size:16px;">🥁</span> Барабан — узкое место, задаёт темп
</div>
<div class="dbr-legend-item">
<span style="width:20px; height:2px; background:var(--c-rag); display:inline-block; margin-top:5px;"></span>
&nbsp;Канат — синхронизация подачи работ
</div>
<div class="dbr-legend-item">
<div class="legend-dot" style="background: rgba(232,101,26,0.5); border: 2px solid var(--c-rag);"></div>
Буфер — защита дедлайна
</div>
<div class="dbr-legend-item">
<span style="font-size:16px;">🟠</span> Эстафетная палочка — передача задачи
</div>
</div>
</div>

<!-- КАК ПРИМЕНЯТЬ -->
<h2 class="section-title">Как применить в своём отделе</h2>
<p style="color:var(--text-muted); font-size:14px; margin-bottom:8px;">Пошаговый сценарий для проектного бюро. Без консультантов и реорганизаций.</p>
<div class="apply-steps">
<div class="apply-step">
<div class="apply-step-line">
<div class="apply-step-num">1</div>
<div class="apply-step-connector"></div>
</div>
<div class="apply-step-content">
<span class="step-tag">Диагностика</span>
<h4>Найти реальное узкое место</h4>
<p>Возьмите последние три сорванных проекта. На каком этапе и у кого они стояли дольше всего? Чаще всего это один-два человека или одна согласующая инстанция. Это и есть ваш барабан.</p>
</div>
</div>
<div class="apply-step">
<div class="apply-step-line">
<div class="apply-step-num">2</div>
<div class="apply-step-connector"></div>
</div>
<div class="apply-step-content">
<span class="step-tag">Планирование</span>
<h4>Построить ресурсный график — не только работы</h4>
<p>Стандартный Гант показывает задачи. Ресурсный график показывает людей. Только он позволяет увидеть перегрузку до начала проекта, а не в момент срыва. Без этого планирование — это гадание.</p>
</div>
</div>
<div class="apply-step">
<div class="apply-step-line">
<div class="apply-step-num">3</div>
<div class="apply-step-connector"></div>
</div>
<div class="apply-step-content">
<span class="step-tag">Расчёт</span>
<h4>Срезать индивидуальные страховки — собрать Проектный буфер</h4>
<p>Попросите команду оценить задачи по медиане (50% вероятности выполнить в срок), а не по максимуму. Сэкономленное время не раздавайте обратно — положите в буфер в конце проекта. Буфер один и виден всем.</p>
</div>
</div>
<div class="apply-step">
<div class="apply-step-line">
<div class="apply-step-num">4</div>
<div class="apply-step-connector"></div>
</div>
<div class="apply-step-content">
<span class="step-tag">Управление</span>
<h4>Отслеживать расход буфера — не процент выполнения</h4>
<p>Статус «готово на 80%» ничего не говорит. Статус «буфер израсходован на 60% при 40% пройденного пути» — говорит всё. Это единственная метрика, которая позволяет вмешаться до катастрофы.</p>
</div>
</div>
<div class="apply-step">
<div class="apply-step-line">
<div class="apply-step-num">5</div>
<div class="apply-step-connector"></div>
</div>
<div class="apply-step-content">
<span class="step-tag">Культура</span>
<h4>Ввести принцип эстафеты — убить многозадачность</h4>
<p>Правило простое: взял задачу — доделай до конца, не переключайся. Передал — свободен для следующей. ГИП не имеет права подкидывать промежуточные задачи исполнителю, который уже в работе. Это не про дисциплину — это про физику потока.</p>
</div>
</div>
</div>

<!-- СРАВНЕНИЕ -->
<h2 class="section-title" style="margin-top:48px;">Традиционный подход vs ТОС</h2>
<div class="card" style="overflow:hidden; margin-top:22px;">
<table class="compare-table">
<thead>
<tr style="background:var(--bg-tertiary);">
<th style="width:22%; color:var(--c-toc);">Что делаем</th>
<th style="width:39%; color:var(--text-muted);">Традиционный подход</th>
<th style="width:39%; color:var(--c-toc);">Подход ТОС</th>
</tr>
</thead>
<tbody>
<tr>
<td class="col-label">Оценка сроков задач</td>
<td class="col-old">Максимальная оценка с запасом «на всякий случай» — каждый страхуется сам</td>
<td class="col-new">Медианная оценка. Страховка изымается и кладётся в общий Проектный буфер</td>
</tr>
<tr>
<td class="col-label">Статус проекта</td>
<td class="col-old">Процент выполнения по задачам. «Готово на 70%» — и непонятно, успеем ли</td>
<td class="col-new">Расход буфера vs прогресс по критической цепи. Один график вместо сотни</td>
</tr>
<tr>
<td class="col-label">Многозадачность</td>
<td class="col-old">Норма жизни. Инженер ведёт 3–5 задач параллельно, переключается по требованию ГИПа</td>
<td class="col-new">Запрещена. Один человек — одна задача. Принцип эстафеты</td>
</tr>
<tr>
<td class="col-label">Дедлайны по задачам</td>
<td class="col-old">Жёсткий дедлайн на каждую задачу. Опоздание видно сразу, пессимизм нарастает</td>
<td class="col-new">Нет дедлайнов на задачи — только приоритеты. Дедлайн один — у проекта</td>
</tr>
<tr>
<td class="col-label">Загрузка ресурсов</td>
<td class="col-old">Стремление к 100% занятости каждого. Нет свободного — значит, работает</td>
<td class="col-new">Целевая загрузка 80%. Резерв — не безделье, а защита от вариативности</td>
</tr>
<tr>
<td class="col-label">Реакция на отставание</td>
<td class="col-old">Добавить людей. Назначить ответственного. Ввести ежедневный контроль</td>
<td class="col-new">Проверить, через какое ограничение «вытекает» буфер. Снять именно его</td>
</tr>
</tbody>
</table>
</div>

<div class="orange-banner" style="margin-top:28px;">
Если вы оцениваете инженера по локальной эффективности его задачи — не жалуйтесь на срыв всего проекта.
</div>
<pre class="footer-cat">
/\_____/\
(  ~   ~  )
=( Y = Y )=
)  DBR  (
(_)-(_)-(_)
</pre>
</div>
</article>
</main>

<script>
// ── DBR Animation ──
const canvas = document.getElementById('dbr-canvas');
const statusEl = document.getElementById('dbr-status');
const toggleBtn = document.getElementById('dbr-toggle-btn');
let running = false;
let animId = null;
let drumSpeed = 1;
// Параметры
const CANVAS_W = () => canvas.offsetWidth;
const BUFFER_W = 100;
const START_X = 40;
const END_X = () => CANVAS_W() - BUFFER_W - 10;
const DRUM_OFFSET = 0;   // барабан — впереди
// Состояние котов (3 исполнителя + барабан)
let state = {
drum:   { x: START_X + 10, emoji: '🥁', label: 'Барабан (ограничение)' },
cats: [
{ x: START_X - 50, emoji: '😼', label: 'Раздел АР', color: '#1a2540' },
{ x: START_X - 100, emoji: '😾', label: 'Раздел КР', color: '#1a2540' },
{ x: START_X - 150, emoji: '🙀', label: 'Раздел ОВиК', color: '#1a2540' },
],
baton: { x: START_X - 30, active: 1 },  // у кого сейчас палочка
lap: 0,
bufferUsed: 0,
};
// DOM элементы
let drumEl, drumLabelEl, catEls = [], catLabelEls = [], ropeEl, batonEl;
function buildDOM() {
// Убираем старые
canvas.querySelectorAll('.dbr-drum,.dbr-cat,.dbr-rope,.dbr-baton,.dbr-drum-label,.dbr-cat-label').forEach(e => e.remove());
// Канат
ropeEl = document.createElement('div');
ropeEl.className = 'dbr-rope';
canvas.appendChild(ropeEl);
// Барабан
drumEl = document.createElement('div');
drumEl.className = 'dbr-drum';
drumEl.textContent = state.drum.emoji;
canvas.appendChild(drumEl);
drumLabelEl = document.createElement('div');
drumLabelEl.className = 'dbr-drum-label';
drumLabelEl.textContent = 'Барабан';
canvas.appendChild(drumLabelEl);
// Эстафета
batonEl = document.createElement('div');
batonEl.className = 'dbr-baton';
canvas.appendChild(batonEl);
// Коты
state.cats.forEach((cat, i) => {
const el = document.createElement('div');
el.className = 'dbr-cat';
el.textContent = cat.emoji;
canvas.appendChild(el);
catEls[i] = el;
const lbl = document.createElement('div');
lbl.className = 'dbr-cat-label';
lbl.textContent = cat.label;
canvas.appendChild(lbl);
catLabelEls[i] = lbl;
});
}
function updateDOM() {
const W = CANVAS_W();
const endX = W - BUFFER_W - 14;
// Барабан
const dx = Math.min(state.drum.x, endX);
drumEl.style.left = dx + 'px';
drumLabelEl.style.left = (dx + 11) + 'px';
// Коты
state.cats.forEach((cat, i) => {
const cx = Math.max(0, Math.min(cat.x, endX));
catEls[i].style.left = cx + 'px';
catLabelEls[i].style.left = (cx + 10) + 'px';
});
// Эстафета — у активного кота
const activeIdx = state.baton.active;
if (activeIdx >= 0 && activeIdx < state.cats.length) {
batonEl.style.left = (Math.min(state.cats[activeIdx].x + 18, endX)) + 'px';
} else {
batonEl.style.left = (Math.min(state.drum.x - 10, endX)) + 'px';
}
// Канат — от первого кота до барабана
const ropeStart = Math.max(0, state.cats[state.cats.length - 1].x + 10);
const ropeEnd = dx;
ropeEl.style.left = ropeStart + 'px';
ropeEl.style.width = Math.max(0, ropeEnd - ropeStart) + 'px';
// Статус
const progress = Math.min(100, Math.round((state.drum.x - START_X) / (endX - START_X) * 100));
statusEl.textContent = `Прогресс барабана: ${progress}% | Буфер израсходован: ${state.bufferUsed}% | Эстафета у: ${state.cats[state.baton.active]?.label || '—'}`;
}
function resetState() {
const W = CANVAS_W();
state.drum.x = START_X + 10;
state.cats[0].x = START_X - 45;
state.cats[1].x = START_X - 90;
state.cats[2].x = START_X - 135;
state.baton.active = 0;
state.bufferUsed = 0;
updateDOM();
}
let tick = 0;
function step() {
const W = CANVAS_W();
const endX = W - BUFFER_W - 14;
const drumStep = 0.5 * drumSpeed;
// Барабан двигается по своей скорости
state.drum.x += drumStep;
// Канат: коты не могут обогнать барабан и не должны отставать больше чем на 60px
const ropeLength = 60;
state.cats.forEach((cat, i) => {
const target = state.drum.x - ropeLength * (i + 1);
// Кот догоняет если слишком отстал, тянется если слишком близко к барабану
if (cat.x < target - 2) {
cat.x += Math.min(drumStep * 1.3, target - cat.x);
} else if (cat.x > target + 2) {
cat.x -= Math.min(drumStep * 0.5, cat.x - target);
} else {
cat.x += drumStep * 0.95;
}
});
// Передача эстафеты: когда активный кот достигает следующего
tick++;
if (tick % 120 === 0) {
state.baton.active = (state.baton.active + 1) % state.cats.length;
}
// Если барабан дошёл до буфера — расходуем буфер, перезапускаем
if (state.drum.x >= endX) {
state.bufferUsed = Math.min(100, state.bufferUsed + Math.round(20 / drumSpeed));
// Новый круг
state.drum.x = START_X + 10;
state.cats[0].x = START_X - 45;
state.cats[1].x = START_X - 90;
state.cats[2].x = START_X - 135;
if (state.bufferUsed >= 100) {
statusEl.textContent = '⚠️ Буфер исчерпан! Проект под угрозой. Снизьте скорость барабана или добавьте буфер.';
stopDBR();
return;
}
}
updateDOM();
animId = requestAnimationFrame(step);
}
function startDBR() {
running = true;
toggleBtn.textContent = '⏸ Пауза';
animId = requestAnimationFrame(step);
}
function stopDBR() {
running = false;
toggleBtn.textContent = '▶ Продолжить';
if (animId) cancelAnimationFrame(animId);
}
function toggleDBR() {
if (!running) {
startDBR();
} else {
stopDBR();
}
}
function setDrumSpeed(speed, btn) {
drumSpeed = speed;
document.querySelectorAll('.dbr-speed-btn').forEach(b => b.classList.remove('active'));
btn.classList.add('active');
// Сброс буфера при смене скорости
state.bufferUsed = 0;
statusEl.textContent = `Скорость изменена. Буфер сброшен.`;
}
// Инициализация
window.addEventListener('load', () => {
buildDOM();
resetState();
});
window.addEventListener('resize', () => {
buildDOM();
resetState();
});
</script>
<?php include 'footer.php'; ?>
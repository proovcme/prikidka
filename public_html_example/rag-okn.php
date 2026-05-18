<?php
$siteId = 'rag';
$pageTitle = 'Сохранность документации ОКН: от бумажного архива к живой памяти | WeAreFired';
$pageDescription = 'Как внедрить RAG для реставрации и сохранения ОКН. Оцифровка, Vision LLM, CRAG, Safe RAG и экономика локального ИИ. По материалам доклада БИМАК 2026.';
$pageKeywords = 'RAG ОКН, реставрация ИИ, цифровизация архивов, HBIM, Vision LLM, CRAG, Safe RAG, локальный ИИ, БИМАК 2026, Чернетченко';
$pageAuthor = 'Олег Чернетченко';
$pageDate = '2026-05-24';
$pageModified = '2026-05-24';
$pageUrl = 'https://rag.ovc.me/rag-okn';
$pageImage = 'https://rag.ovc.me/img/og-rag-okn.jpg';
include 'header.php';
?>
<style>
/* ─── СТИЛИ RAG-OKN.PHP (OVC DS, акцент RAG) ─── */
.article-hero { padding: 4.5rem 0 3rem; border-bottom: 2px solid var(--text-main); margin-bottom: 2rem; }
.article-hero h1 { font-family: var(--font-ui); font-size: clamp(2rem, 6vw, 3.5rem); font-weight: 900; line-height: 1.1; margin-bottom: 1.2rem; letter-spacing: -0.02em; color: var(--text-main); }
.article-hero h1 span { color: var(--c-rag); }
.article-hero .hero-desc { font-size: 1.05rem; color: var(--text-muted); max-width: 680px; line-height: 1.7; margin-bottom: 0; }
.content-block { padding: 4rem 0; border-bottom: 1px dashed var(--border); }
.content-block:last-child { border-bottom: none; }
.section-num { font-family: var(--font-code); font-size: 0.78rem; color: var(--c-rag); font-weight: 900; margin-bottom: 1rem; display: block; letter-spacing: 0.15em; text-transform: uppercase; }
.section-title { font-family: var(--font-ui); font-size: clamp(1.5rem, 3vw, 2.1rem); font-weight: 900; margin-bottom: 1.5rem; color: var(--text-main); line-height: 1.2; }
.text-lead { font-family: var(--font-ui); font-size: 1.2rem; line-height: 1.65; color: var(--text-main); margin-bottom: 2rem; font-weight: 700; }
.text-main { font-size: 1.05rem; line-height: 1.8; color: var(--text-muted); margin-bottom: 1.5rem; }
.text-main:last-child { margin-bottom: 0; }
.text-main a { color: var(--c-rag); text-decoration: underline; font-weight: 700; }
.nav-group { display: flex; flex-direction: column; gap: 0.8rem; background: var(--bg-secondary); padding: 25px; border: 2px solid var(--text-main); border-radius: var(--r-lg); margin: 2rem 0 3rem; box-shadow: 6px 6px 0 var(--border); }
.nav-label { font-family: var(--font-code); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--text-muted); font-weight: 700; }
.nav-row { display: flex; flex-wrap: wrap; gap: 0.8rem; }
.btn { padding: 0.85rem 1.4rem; border-radius: var(--r-md); font-family: var(--font-ui); font-size: 0.8rem; font-weight: 800; text-transform: uppercase; text-decoration: none; transition: all 0.2s; text-align: center; letter-spacing: 0.05em; display: inline-block; cursor: pointer; }
.btn-outline { background: #fff; color: var(--c-rag); border: 2px solid var(--c-rag); }
.btn-outline:hover { background: var(--c-rag); color: #fff; transform: translateY(-2px); }
.btn-teal { background: #fff; color: var(--c-toc); border: 2px solid var(--c-toc); }
.btn-teal:hover { background: var(--c-toc); color: #fff; }
.btn-punch { background: #fff; color: var(--c-rag); border: 2px solid var(--c-rag); }
.btn-punch:hover { background: var(--c-rag); color: #fff; }
.btn-ghost { background: transparent; color: var(--c-rag); border: 2px solid var(--c-rag); }
.btn-ghost:hover { background: var(--c-rag); color: #fff; }
.step-list { list-style: none; display: grid; gap: 1.2rem; margin: 1.5rem 0; }
.step-item { display: flex; gap: 1.2rem; align-items: flex-start; padding: 1.5rem; background: var(--bg-secondary); border: 1px solid var(--border); border-radius: var(--r-lg); opacity: 0; transform: translateY(20px); animation: slideUp 0.5s forwards; }
@keyframes slideUp { to { opacity: 1; transform: translateY(0); } }
.step-item:nth-child(1) { animation-delay: 0.1s; } .step-item:nth-child(2) { animation-delay: 0.2s; } .step-item:nth-child(3) { animation-delay: 0.3s; } .step-item:nth-child(4) { animation-delay: 0.4s; }
.step-num { font-family: var(--font-code); font-size: 1.2rem; font-weight: 900; color: var(--c-rag); flex-shrink: 0; line-height: 1; min-width: 2rem; }
.step-title { font-family: var(--font-ui); font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.4rem; }
.step-text { font-size: 1.05rem; color: var(--text-muted); line-height: 1.6; }
.insight-box { background: var(--bg-secondary); border-left: 4px solid var(--c-toc); padding: 2rem; margin: 2rem 0; border-radius: 0 var(--r-lg) var(--r-lg) 0; opacity: 0; animation: fadeIn 0.6s forwards; animation-delay: 0.3s; }
@keyframes fadeIn { to { opacity: 1; } }
.insight-box h3 { font-family: var(--font-ui); font-size: 1.05rem; margin-bottom: 0.7rem; color: var(--c-toc); }
.insight-box.warn { border-left-color: var(--c-amber); } .insight-box.warn h3 { color: var(--c-amber); }
.insight-box.danger { border-left-color: var(--c-rag); } .insight-box.danger h3 { color: var(--c-rag); }
.tools-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.2rem; margin: 2rem 0; }
.tool-card { background: var(--bg-secondary); border: 2px solid var(--text-main); padding: 1.8rem; border-radius: var(--r-lg); transition: all 0.2s; display: flex; flex-direction: column; }
.tool-card:hover { transform: translateY(-4px); box-shadow: 6px 6px 0 var(--text-main); }
.tool-tag { font-family: var(--font-code); font-size: 0.65rem; text-transform: uppercase; font-weight: 900; margin-bottom: 0.8rem; display: block; letter-spacing: 0.05em; color: var(--text-muted); }
.tool-name { font-family: var(--font-ui); font-size: 1.2rem; font-weight: 900; margin-bottom: 0.8rem; color: var(--text-main); }
.tool-desc { font-size: 0.95rem; color: var(--text-muted); line-height: 1.6; margin: 0; }
.case-card { background: var(--bg-main); border: 2px solid var(--border); padding: 2rem; border-radius: var(--r-lg); margin-bottom: 1.5rem; }
.case-title { font-family: var(--font-ui); font-size: 1.2rem; font-weight: 900; color: var(--c-rag); margin-bottom: 0.8rem; }
.case-text { font-size: 1rem; color: var(--text-muted); line-height: 1.7; margin-bottom: 0.5rem; }
.faq-item { border: 1px solid var(--border); border-radius: var(--r-lg); margin-bottom: 0.8rem; overflow: hidden; background: var(--bg-main); }
.faq-item summary { padding: 1.2rem 1.5rem; font-family: var(--font-ui); font-weight: 700; font-size: 1.05rem; cursor: pointer; list-style: none; display: flex; justify-content: space-between; align-items: center; color: var(--text-main); }
.faq-item summary:hover { background: var(--bg-secondary); }
.faq-item summary::after { content: "+"; font-size: 1.5rem; font-weight: 300; color: var(--c-rag); margin-left: 1rem; }
.faq-item[open] summary::after { content: "-"; }
.faq-body { padding: 1.2rem 1.5rem; font-size: 0.98rem; line-height: 1.7; color: var(--text-muted); border-top: 1px solid var(--border); background: var(--bg-secondary); }
.outro-block { background: var(--c-rag); color: #111; padding: 4rem; border-radius: var(--r-xl); margin: 2rem 0 4rem; }
.outro-block h2 { font-family: var(--font-ui); color: #111; font-size: 1.8rem; margin-bottom: 1.5rem; }
.outro-block p { font-size: 1.05rem; opacity: 0.9; line-height: 1.75; margin-bottom: 1.5rem; max-width: 700px; }
.outro-block .btn { background: #111; color: var(--c-rag); border-color: #111; }
.outro-block .btn:hover { background: transparent; color: #111; border-color: #111; }
.outro-links { display: flex; gap: 15px; flex-wrap: wrap; margin-top: 2rem; }
@media (max-width: 640px) {
  .outro-block { padding: 2rem 1.5rem; }
  .tools-grid { grid-template-columns: 1fr; }
}
</style>
<main class="container">
<article itemscope itemtype="https://schema.org/TechArticle">
<meta itemprop="datePublished" content="<?= $pageDate ?>">
<meta itemprop="dateModified" content="<?= $pageModified ?>">
<meta itemprop="author" content="Олег Чернетченко">
<header class="article-hero">
  <h1 itemprop="headline">Сохранность документации ОКН:<br><span>от бумажного архива к живой памяти</span></h1>
  <p class="hero-desc" itemprop="description">Научно-проектная документация порождает сотни томов. Знания заперты в бумаге и PDF. Разбираем как RAG, Vision LLM и локальный контур превращают мёртвый архив в интеллектуальную систему поддержки реставрации. По материалам доклада БИМАК 2026.</p>
</header>

<div class="nav-group">
  <span class="nav-label">Содержание доклада</span>
  <div class="nav-row">
    <a href="#paradox" class="btn btn-outline">01 / Парадокс</a>
    <a href="#storage" class="btn btn-teal">02 / Хранение</a>
    <a href="#photos" class="btn btn-punch">03 / Фотоархив</a>
    <a href="#digit" class="btn btn-outline">04 / Легализация</a>
    <a href="#sed" class="btn btn-teal">05 / СЭД vs RAG</a>
    <a href="#cases" class="btn btn-punch">06 / Кейсы</a>
    <a href="#eco" class="btn btn-outline">07 / Экономика</a>
    <a href="#roadmap" class="btn btn-teal">08 / Дорожная карта</a>
    <a href="#risks" class="btn btn-ghost">09 / Риски</a>
  </div>
</div>

<section id="paradox" class="content-block" itemprop="articleBody">
  <span class="section-num">01 / Paradox</span>
  <h2 class="section-title">Информационный парадокс ОКН</h2>
  <p class="text-lead">ГОСТ Р 55528-2013 предписывает 5 стадий проектирования. Каждая порождает тома. На один объект — от 50 до 500+ файлов. Приказ Минкультуры № 1840 требует сдачи отчёта за 3 месяца. Хранение — вечное.</p>
  
  <div class="tools-grid">
    <div class="tool-card"><div style="font-size:2.5rem;margin-bottom:10px;">📚</div><h3 style="font-size:1.8rem;color:var(--c-rag);margin:0;">50-500+</h3><p>PDF-файлов на один объект ОКН (ГОСТ Р 55528-2013)</p></div>
    <div class="tool-card c-teal"><div style="font-size:2.5rem;margin-bottom:10px;">∞</div><h3 style="font-size:1.8rem;color:var(--c-toc);margin:0;">∞</h3><p>Срок хранения документации по паспортам (ст. 802 Приказа № 558)</p></div>
    <div class="tool-card c-punch"><div style="font-size:2.5rem;margin-bottom:10px;">⏱️</div><h3 style="font-size:1.8rem;color:var(--c-waf);margin:0;">3 мес.</h3><p>Срок сдачи отчета в орган охраны (Приказ № 1840)</p></div>
    <div class="tool-card"><div style="font-size:2.5rem;margin-bottom:10px;">🔍</div><h3 style="font-size:1.8rem;color:var(--text-main);margin:0;">~3 нед.</h3><p>Среднее время поиска нужного документа в бумаге</p></div>
  </div>

  <div class="insight-box warn">
    <h3>Практическая проблема</h3>
    <p class="text-main" style="margin: 0;">Поиск рецептуры раствора реставрации 1996 года в бумажном архиве занимает недели. Знания заперты в тысячах томов. Это «тёмные данные»: до 80% корпоративной информации не индексируется и не работает.</p>
  </div>
</section>

<section id="storage" class="content-block">
  <span class="section-num">02 / Physical Storage</span>
  <h2 class="section-title">Физический фундамент: нормы хранения бумаги</h2>
  <p class="text-main">Цифра без физического фундамента теряет юридическую ценность. Бумага деградирует. Ниже — реальные сроки и условия хранения.</p>
  
  <ul class="step-list">
    <li class="step-item"><div class="step-num">17-19°</div><div><div class="step-title">Бумага (чертежи, кальки, синьки)</div><div class="step-text">Выцветание, ломкость, плесень. Тушь на кальке слепнет через 30-50 лет. Влажность 50-55%.</div></div></li>
    <li class="step-item"><div class="step-num">≤15°</div><div><div class="step-title">Фото/негативы ч/б (желатин-серебро)</div><div class="step-text">Разрушение основы, пятна. Негативы 1920-1960-х уже на грани. Влажность 30-40%.</div></div></li>
    <li class="step-item"><div class="step-num">≤-5°</div><div><div class="step-title">Фото/слайды цветные</div><div class="step-text">Выцветание красителей за 20-40 лет при комнатной температуре. Требуют низкой влажности.</div></div></li>
    <li class="step-item"><div class="step-num">HDD</div><div><div class="step-title">Жесткие магнитные диски</div><div class="step-text">Размагничивание, механика. Срок без обслуживания ~7-10 лет. 8-18°C, влажность 45-65%.</div></div></li>
    <li class="step-item"><div class="step-num">SSD</div><div><div class="step-title">Твердотельные накопители</div><div class="step-text">Потеря заряда ячеек без питания за 1-5 лет хранения. Не для архива!</div></div></li>
    <li class="step-item"><div class="step-num">LTO</div><div><div class="step-title">LTO-лента (магнитная)</div><div class="step-text">Золотой стандарт архивов. Срок 50+ лет при соблюдении условий. 16-25°C, влажность 20-50%.</div></div></li>
  </ul>

  <div class="insight-box">
    <h3>Правило 3-2-1</h3>
    <p class="text-main" style="margin: 0;">3 копии данных на 2 разных носителях, 1 географически удалённая. Это минимум для надёжной защиты архива. Подлинники незаменимы (73-ФЗ об ОКН: хранение вечное).</p>
  </div>
</section>

<section id="photos" class="content-block">
  <span class="section-num">03 / Photo Archive</span>
  <h2 class="section-title">Исторические снимки: незаменимый источник</h2>
  <p class="text-main">Фотография 1910 года фиксирует детали лепнины, карнизов, оконных наличников до их разрушения. Без них невозможно разграничить подлинный материал от позднейших вмешательств.</p>
  
  <div class="case-card">
    <h3 class="case-title">❌ Проблема: деградация и потери</h3>
    <p class="case-text"><strong>Негативы на нитрооснове:</strong> Самовозгораются, разрушаются. Часть фондов 1900-1940-х безвозвратно утрачена ещё в архивах.</p>
    <p class="case-text"><strong>Цветные слайды и диафильмы:</strong> Выцветают за 20-30 лет при комнатной температуре. Неоцифрованные фонды 1970-80-х уже на грани.</p>
    <p class="case-text"><strong>Неописанные фонды:</strong> Тысячи конвертов в региональных архивах не описаны. Информация теряется физически вместе с носителем.</p>
  </div>

  <div class="tools-grid">
    <div class="tool-card c-rag"><span class="tool-tag">Шаг 1</span><h3 class="tool-name">Оцифровка 600+ dpi</h3><p class="tool-desc">Планшетный сканер с подсветкой для негативов. Результат: TIFF (архив) + JPG (рабочий). Метаданные: дата, автор, объект, ракурс.</p></div>
    <div class="tool-card c-teal"><span class="tool-tag">Шаг 2</span><h3 class="tool-name">Vision LLM</h3><p class="tool-desc">Qwen2.5-VL описывает фото текстом: «Северный фасад, 1934 г., видны сандрики». Описание индексируется в RAG.</p></div>
    <div class="tool-card c-punch"><span class="tool-tag">Шаг 3</span><h3 class="tool-name">Запрос по смыслу</h3><p class="tool-desc">«Покажи снимки северного фасада до 1950 года» — система находит фото по описанию, а не по имени файла.</p></div>
  </div>
</section>

<section id="digit" class="content-block">
  <span class="section-num">04 / Digitization</span>
  <h2 class="section-title">Легализация цифры: от скана до документа</h2>
  <p class="text-main">PDF/A (ISO 19005) — единственный формат для юридически значимого архива. Встраивает шрифты, запрещает внешние ссылки. Совместимость гарантирована на 50+ лет.</p>
  
  <ul class="step-list">
    <li class="step-item"><div class="step-num">01</div><div><div class="step-title">Физическая подготовка</div><div class="step-text">Реставрация носителя (отдельная профессия). Планшетный сканер с подсветкой для кальки. Минимум 300-600 dpi (ГОСТ Р 7.0.8-2013).</div></div></li>
    <li class="step-item"><div class="step-num">02</div><div><div class="step-title">Распознавание (OCR)</div><div class="step-text">ABBYY FineReader — лучший для русских исторических документов. Машинопись, типографские шрифты, рукописные правки. Tesseract — свободная альтернатива.</div></div></li>
    <li class="step-item"><div class="step-num">03</div><div><div class="step-title">Формат PDF/A</div><div class="step-text">Встраивает шрифты, запрещает внешние ссылки. Долгосрочное хранение без потери читаемости.</div></div></li>
    <li class="step-item" style="border-color: var(--c-toc); background: var(--bg-secondary);"><div class="step-num" style="color: var(--c-toc);">04</div><div><div class="step-title" style="color: var(--c-toc);">УКЭП + метаданные</div><div class="step-text">Усиленная квалифицированная ЭП придаёт документу юридическую силу. ГОСТ Р 7.0.109-2024 определяет состав метаданных для СХЭД.</div></div></li>
  </ul>
</section>

<section id="sed" class="content-block">
  <span class="section-num">05 / SЭD vs RAG</span>
  <h2 class="section-title">Цифровое ожирение: почему СЭД не помогает инженеру</h2>
  <p class="text-main">СЭД ищет только по названию файла и атрибутам. Запрос «рецептура известкового раствора» — 0 результатов, если слова нет в имени папки. Проектная документация ОКН — это не «договор» и не «счёт». 80% содержания документов не индексируется.</p>
  
  <div class="tools-grid">
    <div class="tool-card"><span class="tool-tag">Файловый сервер</span><h3 class="tool-name">По имени папки</h3><p class="tool-desc">Содержание PDF недоступно. Ответ на вопрос: Н/П. Темные данные: информация заперта в папках.</p></div>
    <div class="tool-card c-teal"><span class="tool-tag">СЭД / СОД</span><h3 class="tool-name">По атрибутам</h3><p class="tool-desc">Частично доступно. Версионность без семантики: знает что есть v3 и v4, но не знает ЧТО изменилось.</p></div>
    <div class="tool-card c-punch"><span class="tool-tag">Локальный RAG</span><h3 class="tool-name">По смыслу</h3><p class="tool-desc">Полностью доступно. Ответ Да + ссылка. Галлюцинации контролируемы. 100% локально.</p></div>
  </div>

  <div class="insight-box">
    <h3>ЦИМ и RAG: связка по ГОСТ Р 10.0.03-2019</h3>
    <p class="text-main" style="margin: 0;">ЦИМ (Цифровая Информационная Модель) — совокупность данных: геометрия (IFC), документация (PDF/A), фото (TIFF), мониторинг. RAG — интеллектуальный интерфейс к ЦИМ: он не хранит данные, а делает их доступными через вопрос на естественном языке. Порядок в ЦИМ = качество ответов RAG.</p>
  </div>
</section>

<section id="cases" class="content-block">
  <span class="section-num">06 / Cases</span>
  <h2 class="section-title">Кейсы из практики: цена потери информации</h2>
  <p class="text-main">Два объекта. Две истории. Один вывод: потеря документации в стройке — критическая проблема. RAG не избавит от боли задним числом. Он работает только с тем, что сохранено.</p>
  
  <div class="case-card">
    <h3 class="case-title">КЕЙС 01: «Изыскательская археология»</h3>
    <p class="case-text"><strong>Объект:</strong> Офисное здание нулевых годов. Не историческое.</p>
    <p class="case-text"><strong>Проблема:</strong> Электронный архив почти пуст. Документация в подвале, в безымянных коробках без маркировки. Поиск чертежа — физическое перекапывание.</p>
    <p class="case-text"><strong>Что сделали:</strong> Трёхмерное лазерное сканирование всего здания. Сканирование всей бумаги из подвала. Недели ручной каталогизации. Срыв сроков.</p>
    <p class="case-text"><strong>Итог:</strong> Модель получена — но какой ценой?</p>
  </div>

  <div class="case-card">
    <h3 class="case-title">КЕЙС 02: «Административно-бытовой корпус»</h3>
    <p class="case-text"><strong>Объект:</strong> Строился как Коулун. Документации толком нет.</p>
    <p class="case-text"><strong>Проблема:</strong> Здание расширялось хаотично. Каждый новый ввод систем делался поверх старого. Пришлось отслеживать каждый кабель и искать стояки в стенах.</p>
    <p class="case-text"><strong>Что сделали:</strong> Полевой обход с фиксацией каждого элемента. Термосъёмка. Вскрытие конструкций. Составление схем «от руки» с оцифровкой.</p>
    <p class="case-text"><strong>Итог:</strong> Сотни часов полевой работы. Модель получена ценой уничтоженных сроков и бюджета.</p>
  </div>
</section>

<section id="eco" class="content-block">
  <span class="section-num">07 / Economics</span>
  <h2 class="section-title">Экономика: стоимость внедрения vs цена ошибки</h2>
  <p class="text-main">Внедрение окупается за 6-18 месяцев для архива от 500 документов. Экономия времени — до 40 часов в месяц. Поиск нужного документа сокращается с 3 недель до 10 секунд.</p>
  
  <div class="tools-grid">
    <div class="tool-card"><span class="tool-tag">Пилот</span><h3 class="tool-name">Ноутбук / 1 человек</h3><p class="tool-desc">8-16 ГБ RAM, 4 ядра CPU, 50 ГБ диск. Модели 3-7B. GPU не нужен. NotebookLM или Open WebUI.</p></div>
    <div class="tool-card c-teal"><span class="tool-tag">Отдел</span><h3 class="tool-name">Офисный сервер / 5-10 чел.</h3><p class="tool-desc">32 ГБ RAM, 8 ядер CPU, 100 ГБ диск, GPU 8 ГБ VRAM. Модели 7-13B (Q4_K_M). Ollama + RAGFlow + WebUI.</p></div>
    <div class="tool-card c-punch"><span class="tool-tag">Организация</span><h3 class="tool-name">Выделенный сервер / весь архив</h3><p class="tool-desc">64+ ГБ RAM, 16+ ядер CPU, 200+ ГБ, NVIDIA 16-24 ГБ VRAM. Модели 13-34B, ABBYY Server. Полный стек + СХЭД.</p></div>
  </div>

  <div class="insight-box warn">
    <h3>Цена ошибки (без системы)</h3>
    <p class="text-main" style="margin: 0;">до 15 млн руб. штраф за утечку ПДн (поправки КоАП 2025) • до 3% выручки при повторном нарушении • до 5 лет уголовная ответственность за КТ (ФЗ-175) • 300-700 тыс. обработка ПДн без согласия (ст. 13.11 КоАП)</p>
  </div>
</section>

<section id="roadmap" class="content-block">
  <span class="section-num">08 / Roadmap</span>
  <h2 class="section-title">Дорожная карта: как построить RAG-машину</h2>
  <p class="text-main">Пошаговый план от аудита до постоянной поддержки.</p>
  
  <ul class="step-list">
    <li class="step-item"><div class="step-num">01</div><div><div class="step-title">Аудит и подготовка (1-2 мес.)</div><div class="step-text">Инвентаризация всех носителей. Оценка состояния. Выбор архивных форматов (PDF/A, TIFF). Система метаданных: объект, год, тип, автор, стадия.</div></div></li>
    <li class="step-item"><div class="step-num">02</div><div><div class="step-title">Оцифровка архива (2-6 мес.)</div><div class="step-text">Сканирование 300-600 dpi. OCR через ABBYY/Tesseract. Базовое именование файлов. Результат: структурированный цифровой архив.</div></div></li>
    <li class="step-item"><div class="step-num">03</div><div><div class="step-title">Развертывание стека (2-4 нед.)</div><div class="step-text">Сервер/VPS: 32+ ГБ RAM, GPU 8+ ГБ. Установка: Ollama + bge-m3 + RAGFlow/Qdrant + WebUI. Индексация и тестирование 20 контрольных вопросов.</div></div></li>
    <li class="step-item" style="border-color: var(--c-toc); background: var(--bg-secondary);"><div class="step-num" style="color: var(--c-toc);">04</div><div><div class="step-title" style="color: var(--c-toc);">Запуск и сопровождение (постоянно)</div><div class="step-text">Обучение сотрудников: 1-2 дня. Регламент пополнения: каждый новый документ → в RAG. Обновление моделей 1 раз в 6-12 мес.</div></div></li>
  </ul>

  <div class="tools-grid" style="margin-top: 2rem;">
    <div class="tool-card"><span class="tool-tag">Затраты на запуск</span><h3 class="tool-name">340 тыс. – 1.3 млн ₽</h3><p class="tool-desc">Аудит + оцифровка + сервер + настройка.</p></div>
    <div class="tool-card c-teal"><span class="tool-tag">Ежегодное сопровождение</span><h3 class="tool-name">30-60 тыс. ₽ / год</h3><p class="tool-desc">Администрирование и обновление.</p></div>
  </div>
</section>

<section id="risks" class="content-block">
  <span class="section-num">09 / Risks</span>
  <h2 class="section-title">Ограничения и риски: реальный взгляд</h2>
  <p class="text-main">Продавать технологию как волшебную таблетку — плохая идея. Коллеги быстро раскусят подвох.</p>
  
  <ul class="step-list">
    <li class="step-item"><div class="step-num">⚠️</div><div><div class="step-title">Кривой OCR и «грязные» данные</div><div class="step-text">Рукописные акты, выцветшие синьки 1970-х — нейросеть не извлечет смыслы. Очистка сканов съедает львиную долю времени. Это системная работа, не задача на день.</div></div></li>
    <li class="step-item"><div class="step-num">📐</div><div><div class="step-title">Слепота к геометрии и САПР</div><div class="step-text">Базовый RAG переваривает текст из PDF. AutoCAD, NanoCAD, Revit — это не текст. Нужно писать коннекторы на C# или Python. Готовых кнопок пока нет.</div></div></li>
    <li class="step-item"><div class="step-num">💻</div><div><div class="step-title">Инфраструктура и кадровый голод</div><div class="step-text">Сервер с GPU нужно купить и охладить. Нужен специалист по векторной базе и чанкингу. Обычный сисадмин с принтерами здесь не справится.</div></div></li>
    <li class="step-item"><div class="step-num">🚫</div><div><div class="step-title">Иллюзия абсолютной правоты</div><div class="step-text">Ссылка на источник ≠ правильный вывод. RAG найдет абзац про усиление фундамента 1985 года, но не поймет, что он про соседний литер. Проверка обязательна всегда.</div></div></li>
  </ul>

  <div class="insight-box danger">
    <h3>Ответственность — у инженера</h3>
    <p class="text-main" style="margin: 0;">RAG найдет идеальный абзац, но не несет юридической ответственности. Если проектировщик копирует ответ без перехода по ссылке и проверки оригинала — это прямой путь к фатальной ошибке. RAG не заменяет архив. RAG делает архив живым.</p>
  </div>
</section>

<section id="norms" class="content-block">
  <span class="section-num">10 / Norms</span>
  <h2 class="section-title">Нормативная база и источники</h2>
  <div class="tools-grid">
    <div class="tool-card c-rag"><span class="tool-tag">Законодательство</span><h3 class="tool-name">73-ФЗ, 152-ФЗ, 125-ФЗ</h3><p class="tool-desc">Об ОКН, о персональных данных, об архивном деле. Режим хранения и защита КТ (98-ФЗ).</p></div>
    <div class="tool-card"><span class="tool-tag">Приказы</span><h3 class="tool-name">Минкультуры № 1840, Росархива № 526/77</h3><p class="tool-desc">Порядок сдачи отчетности, правила хранения, сроки по перечням типовых документов.</p></div>
    <div class="tool-card c-teal"><span class="tool-tag">ГОСТы</span><h3 class="tool-name">55528, 55567, 10.0.03, 57310</h3><p class="tool-desc">Научно-проектная документация, ЦИМ, НБИМ. Порядок проведения исследований.</p></div>
  </div>
</section>

<div class="outro-block">
  <h2>Итоги: Порядок в архиве = Интеллект нейросети</h2>
  <p><strong>1. БУМАГА — нулевой цикл:</strong> Физическое хранение по 73-ФЗ и 125-ФЗ, климат-контроль по ВНИИДАД (17-19°C, 50-55%), правило 3-2-1. Без этого цифра теряет юридическую ценность.</p>
  <p><strong>2. ЦИФРА — правовой фундамент:</strong> PDF/A + УКЭП + метаданные по ГОСТ Р 7.0.109-2024. Три кита юридически значимого архива. СХЭД — хранилище доказательной базы, не просто папка на сервере.</p>
  <p><strong>3. RAG — живая память:</strong> RAG не замена инженеру. Это экскаватор вместо лопаты. Копать всё равно придётся самому — но с другой скоростью. Ответ за 10 секунд вместо трёх недель. Мусор на входе — мусор на выходе.</p>
  <div class="outro-links">
    <a href="/rag-simple" class="btn btn-punch" style="padding: 1.2rem 3rem;">← Основы RAG</a>
    <a href="/infra" class="btn btn-outline" style="padding: 1.2rem 3rem;">Домашний сервер</a>
    <a href="/" class="btn btn-outline" style="padding: 1.2rem 3rem;">На главную RAG</a>
  </div>
</div>
</article>
</main>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"TechArticle","headline":"<?= htmlspecialchars($pageTitle) ?>","description":"<?= htmlspecialchars($pageDescription) ?>","image":"<?= $pageImage ?>","author":{"@type":"Person","name":"Олег Чернетченко","url":"https://chernetchenko.pro"},"publisher":{"@type":"Organization","name":"WeAreFired","logo":{"@type":"ImageObject","url":"https://rag.ovc.me/img/logo.svg"}},"datePublished":"<?= $pageDate ?>","dateModified":"<?= $pageModified ?>","url":"<?= $pageUrl ?>","keywords":"<?= htmlspecialchars($pageKeywords) ?>","inLanguage":"ru"}
</script>
<?php include 'footer.php'; ?>
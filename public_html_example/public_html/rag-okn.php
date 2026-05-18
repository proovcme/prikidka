<?php
$siteId = 'rag';
$pageTitle = 'RAG для объектов культурного наследия: от архива к живой памяти | WeAreFired';
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
.step-item { display: flex; gap: 1.2rem; align-items: flex-start; padding: 1.5rem; background: var(--bg-secondary); border: 1px solid var(--border); border-radius: var(--r-lg); }
.step-num { font-family: var(--font-code); font-size: 1.2rem; font-weight: 900; color: var(--c-rag); flex-shrink: 0; line-height: 1; min-width: 2rem; }
.step-title { font-family: var(--font-ui); font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.4rem; }
.step-text { font-size: 1.05rem; color: var(--text-muted); line-height: 1.6; }
.insight-box { background: var(--bg-secondary); border-left: 4px solid var(--c-toc); padding: 2rem; margin: 2rem 0; border-radius: 0 var(--r-lg) var(--r-lg) 0; }
.insight-box h3 { font-family: var(--font-ui); font-size: 1.05rem; margin-bottom: 0.7rem; color: var(--c-toc); }
.insight-box.warn { border-left-color: var(--c-amber); }
.insight-box.warn h3 { color: var(--c-amber); }
.insight-box.danger { border-left-color: var(--c-rag); }
.insight-box.danger h3 { color: var(--c-rag); }
.tools-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.2rem; margin: 2rem 0; }
.tool-card { background: var(--bg-secondary); border: 2px solid var(--text-main); padding: 1.8rem; border-radius: var(--r-lg); transition: all 0.2s; display: flex; flex-direction: column; }
.tool-card:hover { transform: translateY(-4px); box-shadow: 6px 6px 0 var(--text-main); }
.tool-tag { font-family: var(--font-code); font-size: 0.65rem; text-transform: uppercase; font-weight: 900; margin-bottom: 0.8rem; display: block; letter-spacing: 0.05em; color: var(--text-muted); }
.tool-name { font-family: var(--font-ui); font-size: 1.2rem; font-weight: 900; margin-bottom: 0.8rem; color: var(--text-main); }
.tool-desc { font-size: 0.95rem; color: var(--text-muted); line-height: 1.6; margin: 0; }
.kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin: 2rem 0; }
.kpi-card { background: var(--bg-secondary); border: 2px solid var(--border); border-radius: var(--r-lg); padding: 1.5rem; text-align: center; }
.kpi-value { font-family: var(--font-ui); font-size: 2.5rem; font-weight: 900; color: var(--c-rag); margin: 0.5rem 0; }
.kpi-label { font-size: 0.9rem; color: var(--text-muted); }
.faq-item { border: 1px solid var(--border); border-radius: var(--r-lg); margin-bottom: 0.8rem; overflow: hidden; background: #fff; }
.faq-item summary { padding: 1.2rem 1.5rem; font-family: var(--font-ui); font-weight: 700; font-size: 1.05rem; cursor: pointer; list-style: none; display: flex; justify-content: space-between; align-items: center; color: var(--text-main); }
.faq-item summary:hover { background: var(--bg-secondary); }
.faq-item summary::after { content: "+"; font-size: 1.5rem; font-weight: 300; color: var(--c-rag); margin-left: 1rem; }
.faq-item[open] summary::after { content: "-"; }
.faq-body { padding: 1.2rem 1.5rem; font-size: 0.98rem; line-height: 1.7; color: var(--text-muted); border-top: 1px solid var(--border); background: var(--bg-secondary); }
.faq-body a { color: var(--c-toc); font-weight: 700; text-decoration: underline; }
.outro-block { background: var(--text-main); color: #fff; padding: 4rem; border-radius: var(--r-xl); margin: 2rem 0 4rem; }
.outro-block h2 { font-family: var(--font-ui); color: #fff; font-size: 1.8rem; margin-bottom: 1.5rem; }
.outro-block p { font-size: 1.05rem; opacity: 0.85; line-height: 1.75; margin-bottom: 1.5rem; max-width: 700px; }
.outro-block .btn { background: transparent; color: #fff; border-color: #fff; }
.outro-block .btn:hover { background: #fff; color: var(--text-main); border-color: #fff; }
.outro-links { display: flex; gap: 15px; flex-wrap: wrap; margin-top: 2rem; }
@media (max-width: 640px) {
  .outro-block { padding: 2rem 1.5rem; }
  .tools-grid { grid-template-columns: 1fr; }
  .kpi-grid { grid-template-columns: 1fr; }
}
</style>
<main class="container">
<article itemscope itemtype="https://schema.org/TechArticle">
<meta itemprop="datePublished" content="<?= $pageDate ?>">
<meta itemprop="dateModified" content="<?= $pageModified ?>">
<meta itemprop="author" content="Олег Чернетченко">
<header class="article-hero">
  <h1 itemprop="headline">Сохранность документации ОКН:<br><span>от бумажного архива к живой памяти</span></h1>
  <p class="hero-desc" itemprop="description">Научно-проектная документация порождает сотни томов. Знания заперты в бумаге и PDF. Разбираем как RAG, Vision LLM и локальный контур превращают мёртвый архив в интеллектуальную систему поддержки реставрации.</p>
</header>

<div class="nav-group">
  <span class="nav-label">Содержание доклада</span>
  <div class="nav-row">
    <a href="#paradox" class="btn btn-outline">01 / Парадокс</a>
    <a href="#foundation" class="btn btn-teal">02 / Фундамент</a>
    <a href="#rag" class="btn btn-punch">03 / RAG</a>
    <a href="#economics" class="btn btn-outline">04 / Экономика</a>
    <a href="#faq" class="btn btn-ghost">FAQ</a>
  </div>
</div>

<section id="paradox" class="content-block" itemprop="articleBody">
  <span class="section-num">01 / Paradox</span>
  <h2 class="section-title">Информационный парадокс ОКН</h2>
  <p class="text-lead">ГОСТ Р 55528-2013 предписывает 5 стадий проектирования. Каждая порождает тома. На один объект — от 50 до 500+ файлов. Знания физически существуют, но недоступны.</p>
  
  <div class="kpi-grid">
    <div class="kpi-card"><div style="font-size:0.85rem;color:var(--text-muted);text-transform:uppercase;">PDF на объект</div><div class="kpi-value">50-500+</div><div class="kpi-label">ГОСТ Р 55528-2013</div></div>
    <div class="kpi-card"><div style="font-size:0.85rem;color:var(--text-muted);text-transform:uppercase;">Срок хранения</div><div class="kpi-value">∞</div><div class="kpi-label">Паспорта ОКН (ст. 802)</div></div>
    <div class="kpi-card"><div style="font-size:0.85rem;color:var(--text-muted);text-transform:uppercase;">Поиск рецептуры</div><div class="kpi-value">~3 нед.</div><div class="kpi-label">В бумажном архиве</div></div>
  </div>

  <div class="insight-box warn">
    <h3>Практическая проблема</h3>
    <p class="text-main" style="margin: 0;">Поиск рецептуры раствора реставрации 1996 года в бумажном архиве занимает недели. Это «тёмные данные»: до 80% корпоративной информации не индексируется и не работает.</p>
  </div>
</section>

<section id="foundation" class="content-block">
  <span class="section-num">02 / Foundation</span>
  <h2 class="section-title">Физический фундамент и легализация цифры</h2>
  <p class="text-main">Цифра без физического фундамента теряет юридическую ценность. Бумага деградирует: тушь на кальке слепнет через 30-50 лет, цветные слайды выцветают за 20 лет.</p>
  
  <ul class="step-list">
    <li class="step-item"><div class="step-num">01</div><div><div class="step-title">Оцифровка и OCR</div><div class="step-text">Реставрация носителя, сканирование 300-600 dpi. ABBYY FineReader или Tesseract для распознавания. Рукописи и фото — мультимодальными моделями (VLM).</div></div></li>
    <li class="step-item"><div class="step-num">02</div><div><div class="step-title">Формат PDF/A (ISO 19005)</div><div class="step-text">Единственный формат для юридически значимого архива. Встраивает шрифты, запрещает внешние ссылки. Совместимость гарантирована на 50+ лет.</div></div></li>
    <li class="step-item"><div class="step-num">03</div><div><div class="step-title">УКЭП и метаданные</div><div class="step-text">Усиленная квалифицированная ЭП придаёт документу юридическую силу. ГОСТ Р 7.0.109-2024 определяет обязательный состав метаданных для СХЭД.</div></div></li>
  </ul>
</section>

<section id="rag" class="content-block">
  <span class="section-num">03 / RAG</span>
  <h2 class="section-title">Как RAG оживляет архив</h2>
  <p class="text-main">СЭД ищет только по атрибутам. RAG ищет по смыслу. Технический конвейер от архивной полки до ответа занимает миллисекунды.</p>
  
  <div class="tools-grid">
    <div class="tool-card"><span class="tool-tag">Retrieval</span><h3 class="tool-name">Поиск</h3><p class="tool-desc">Система прочёсывает 10 000 томов. Нарезка на чанки по 200-500 токенов. Векторизация через bge-m3. Поиск по косинусному расстоянию.</p></div>
    <div class="tool-card c-teal"><span class="tool-tag">Augmented</span><h3 class="tool-name">Контекст</h3><p class="tool-desc">Документы превращаются в эмбеддинги. Двухэтапный поиск: HNSW (Top-50) + Reranker (Top-5). Формируется точный контекст для модели.</p></div>
    <div class="tool-card c-punch"><span class="tool-tag">Generation</span><h3 class="tool-name">Ответ</h3><p class="tool-desc">LLM (Qwen2.5 / Gemma) формулирует ответ строго по найденным фрагментам. Каждый факт — со ссылкой на том и страницу.</p></div>
  </div>

  <div class="insight-box">
    <h3>Исторические снимки и Vision LLM</h3>
    <p class="text-main" style="margin: 0;">Vision LLM (Qwen2.5-VL) описывает содержание снимка текстом: «Северный фасад, 1934 г., видны сандрики». Этот текст индексируется в RAG. Запрос «Покажи снимки фасада до 1950» находит фото по смыслу, а не по имени файла.</p>
  </div>
</section>

<section id="economics" class="content-block">
  <span class="section-num">04 / Economics</span>
  <h2 class="section-title">Экономика и дорожная карта</h2>
  <p class="text-main">Внедрение окупается за 6-18 месяцев для архива от 500 документов. Экономия времени команды — до 40 часов в месяц. Поиск нужного документа сокращается с 3 недель до 10 секунд.</p>
  
  <div class="tools-grid">
    <div class="tool-card"><span class="tool-tag">Пилот</span><h3 class="tool-name">Ноутбук / 1 человек</h3><p class="tool-desc">16 ГБ RAM, CPU. Модели 3-7B. NotebookLM или Open WebUI. Для тестирования гипотез.</p></div>
    <div class="tool-card c-teal"><span class="tool-tag">Отдел</span><h3 class="tool-name">Офисный сервер / 5-10 чел.</h3><p class="tool-desc">32 ГБ RAM, GPU 8 ГБ. Модели 7-13B. Ollama + RAGFlow + WebUI. Для рабочей группы.</p></div>
    <div class="tool-card c-punch"><span class="tool-tag">Организация</span><h3 class="tool-name">Выделенный сервер / весь архив</h3><p class="tool-desc">64+ ГБ RAM, GPU 24 ГБ. Модели 13-34B. Полный стек + СХЭД. Для бюро 20-50 чел.</p></div>
  </div>

  <div class="insight-box danger">
    <h3>Риски: реальный взгляд</h3>
    <p class="text-main" style="margin: 0;">Грязные данные — мусор на входе, мусор на выходе. Слепота к САПР — ИИ не читает DWG/RVT из коробки. Иллюзия правоты — ссылка на источник ≠ правильный вывод. Проверка инженером обязательна всегда.</p>
  </div>
</section>

<section id="faq" class="content-block">
  <span class="section-num">05 / FAQ</span>
  <h2 class="section-title">Частые вопросы</h2>
  <details class="faq-item"><summary>С чего начать оцифровку архива ОКН?</summary><div class="faq-body">Начните с аудита: инвентаризация носителей, оценка состояния, приоритет оцифровки. Выберите архивные форматы (PDF/A, TIFF). Разработайте систему метаданных. Только потом сканируйте.</div></details>
  <details class="faq-item"><summary>Какой сервер нужен для RAG архива?</summary><div class="faq-body">Для пилота — ноутбук 16 ГБ RAM. Для отдела — сервер с GPU 8 ГБ VRAM. Для полного архива — 64+ ГБ RAM, GPU 24 ГБ. Модели 7-13B работают на офисном железе.</div></details>
  <details class="faq-item"><summary>RAG заменит архив?</summary><div class="faq-body">Нет. RAG не заменяет архив. RAG делает архив живым. Порядок в ЦИМ = качество ответов ИИ. Начните с аудита и оцифровки.</div></details>
</section>

<div class="outro-block">
  <h2>RAG делает архив живым</h2>
  <p>Порядок в ЦИМ = качество ответов ИИ. Начните с аудита и оцифровки. Разверните локальный контур. Обучите персонал задавать вопросы.</p>
  <p>Ответ за 10 секунд вместо трёх недель поиска — это новая реальность реставрации.</p>
  <div class="outro-links">
    <a href="/rag-simple" class="btn btn-punch" style="padding: 1.2rem 3rem;">← Основы RAG</a>
    <a href="/infra" class="btn btn-outline" style="padding: 1.2rem 3rem;">Домашний сервер</a>
    <a href="/" class="btn btn-outline" style="padding: 1.2rem 3rem;">На главную RAG</a>
  </div>
</div>
</article>
</main>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "TechArticle",
  "headline": "<?= htmlspecialchars($pageTitle) ?>",
  "description": "<?= htmlspecialchars($pageDescription) ?>",
  "image": "<?= $pageImage ?>",
  "author": { "@type": "Person", "name": "Олег Чернетченко", "url": "https://chernetchenko.pro" },
  "publisher": { "@type": "Organization", "name": "WeAreFired", "logo": { "@type": "ImageObject", "url": "https://rag.ovc.me/img/logo.svg" } },
  "datePublished": "<?= $pageDate ?>",
  "dateModified": "<?= $pageModified ?>",
  "url": "<?= $pageUrl ?>",
  "keywords": "<?= htmlspecialchars($pageKeywords) ?>",
  "inLanguage": "ru"
}
</script>
<?php include 'footer.php'; ?>
<?php
$siteId = 'rag';
$pageTitle = 'RAG для чайников: как научить ИИ читать ГОСТы | WeAreFired';
$pageDescription = 'Что такое RAG (Retrieval-Augmented Generation). Как заставить нейросеть отвечать по твоим документам, а не выдумывать. Пошаговый разбор технологии.';
$pageKeywords = 'RAG, Retrieval-Augmented Generation, что такое RAG, нейросеть по документам, чанкинг, эмбеддинги, векторный поиск, ChromaDB, локальный ИИ, ГОСТ, СП';
$pageAuthor = 'WeAreFired';
$pageDate = '2025-05-10';
$pageModified = '2026-05-24';
$pageUrl = 'https://rag.ovc.me/rag-simple';
$pageImage = 'https://rag.ovc.me/img/og-rag.jpg';
include 'header.php';
?>
<style>
/* ─── СТИЛИ ДЛЯ RAG-SIMPLE.PHP (OVC DS, акцент RAG) ─── */
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
/* НАВИГАЦИЯ И КНОПКИ */
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
/* ШАГИ И СПИСКИ */
.step-list { list-style: none; display: grid; gap: 1.2rem; margin: 1.5rem 0; }
.step-item { display: flex; gap: 1.2rem; align-items: flex-start; padding: 1.5rem; background: var(--bg-secondary); border: 1px solid var(--border); border-radius: var(--r-lg); }
.step-num { font-family: var(--font-code); font-size: 1.2rem; font-weight: 900; color: var(--c-rag); flex-shrink: 0; line-height: 1; min-width: 2rem; }
.step-title { font-family: var(--font-ui); font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.4rem; }
.step-text { font-size: 1.05rem; color: var(--text-muted); line-height: 1.6; }
/* КАРТОЧКИ 3 КОЛОНКИ */
.rag-simple-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem; margin: 2rem 0; }
.rag-simple-card { background: var(--bg-main); border: 2px solid var(--border); border-radius: var(--r-lg); padding: 1.8rem; box-shadow: 0 4px 10px rgba(0,0,0,0.02); transition: transform 0.2s, box-shadow 0.2s; }
.rag-simple-card:hover { transform: translateY(-4px); box-shadow: 0 12px 25px rgba(0,0,0,0.06); border-color: var(--c-rag); }
/* ИНСАЙТ БЛОКИ */
.insight-box { background: var(--bg-secondary); border-left: 4px solid var(--c-toc); padding: 2rem; margin: 2rem 0; border-radius: 0 var(--r-lg) var(--r-lg) 0; }
.insight-box h3 { font-family: var(--font-ui); font-size: 1.05rem; margin-bottom: 0.7rem; color: var(--c-toc); }
.insight-box.warn { border-left-color: var(--c-amber); }
.insight-box.warn h3 { color: var(--c-amber); }
.insight-box.danger { border-left-color: var(--c-rag); }
.insight-box.danger h3 { color: var(--c-rag); }
/* АНАЛОГИЯ */
.analogy-box { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 0; border: 2px solid var(--border); border-radius: var(--r-lg); overflow: hidden; margin-bottom: 4rem; }
.analogy-col { padding: 2.5rem; background: var(--bg-main); }
.analogy-col:first-child { border-right: 2px solid var(--border); background: var(--bg-secondary); }
.analogy-col:last-child { background: var(--bg-secondary); }
.analogy-title { font-family: var(--font-ui); font-size: 1.25rem; font-weight: 900; margin-bottom: 1rem; color: var(--text-main); }
.analogy-text { font-size: 1rem; color: var(--text-muted); line-height: 1.6; }
/* ЧЕКЛИСТ */
.rag-checklist { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.2rem; margin: 2rem 0; }
.rag-checklist label { display: flex; align-items: flex-start; gap: 1rem; font-size: 1rem; cursor: pointer; color: var(--text-muted); background: var(--bg-main); padding: 1.5rem; border: 2px solid var(--border); border-radius: var(--r-md); transition: all 0.2s; }
.rag-checklist label:hover { border-color: var(--text-main); transform: translateY(-2px); }
.rag-checklist input { margin-top: 0.2rem; accent-color: var(--c-rag); transform: scale(1.3); }
/* ИНТЕРАКТИВНОЕ ДЕМО */
.rag-demo-zone { background: var(--bg-secondary); border: 2px solid var(--border); border-radius: var(--r-lg); padding: 3rem; margin: 4rem 0; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
.rag-demo-questions { display: grid; gap: 1rem; margin-bottom: 2rem; }
.rag-demo-qbtn { text-align: left; padding: 1.5rem; background: var(--bg-main); border: 2px solid var(--border); border-radius: var(--r-md); cursor: pointer; transition: all 0.2s; font-family: var(--font-ui); }
.rag-demo-qbtn:hover { border-color: var(--text-main); transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0,0,0,0.05); }
.rag-demo-qbtn strong { display: block; margin-bottom: 0.5rem; font-size: 1.1rem; color: var(--text-main); font-weight: 700; }
.rag-demo-qbtn small { color: var(--text-muted); font-size: 0.85rem; font-family: var(--font-code); }
#ragDemoContainer { display: none; margin-top: 2rem; }
.rag-demo-step { opacity: 0; transform: translateY(10px); transition: all 0.4s ease; background: var(--bg-main); border: 2px solid var(--border); border-radius: var(--r-md); padding: 1.8rem; margin-bottom: 1.2rem; }
.rag-demo-step.visible { opacity: 1; transform: translateY(0); }
.rag-demo-spinner { width: 24px; height: 24px; border: 3px solid var(--border); border-top-color: var(--c-rag); border-radius: 50%; animation: spin 1s linear infinite; display: inline-block; margin-right: 1rem; vertical-align: middle; }
@keyframes spin { to { transform: rotate(360deg); } }
.rag-chunk-item { background: var(--bg-secondary); border-left: 4px solid var(--c-rag); padding: 1.2rem; border-radius: 0 var(--r-md) var(--r-md) 0; font-size: 0.95rem; opacity: 0; transform: translateX(-10px); animation: slideIn 0.4s forwards; margin-bottom: 1rem; }
@keyframes slideIn { to { opacity: 1; transform: translateX(0); } }
.rag-demo-context { background: var(--bg-secondary); padding: 1.5rem; border-radius: var(--r-md); font-family: var(--font-code); font-size: 0.9rem; line-height: 1.6; border: 1px solid var(--border); overflow-x: auto; color: var(--text-muted); }
.rag-demo-answer { background: var(--bg-secondary); border: 2px solid var(--c-toc); border-radius: var(--r-md); padding: 2rem; }
.rag-demo-reset { display: none; margin-top: 2rem; background: var(--text-main); color: var(--bg-main); border: none; padding: 1rem 2rem; border-radius: var(--r-md); cursor: pointer; font-family: var(--font-ui); font-weight: 900; text-transform: uppercase; font-size: 0.95rem; transition: all 0.2s; letter-spacing: 0.05em; }
.rag-demo-reset:hover { background: var(--c-rag); transform: translateY(-2px); }
/* FAQ */
.faq-item { border: 1px solid var(--border); border-radius: var(--r-lg); margin-bottom: 0.8rem; overflow: hidden; background: #fff; }
.faq-item summary { padding: 1.2rem 1.5rem; font-family: var(--font-ui); font-weight: 700; font-size: 1.05rem; cursor: pointer; list-style: none; display: flex; justify-content: space-between; align-items: center; color: var(--text-main); }
.faq-item summary:hover { background: var(--bg-secondary); }
.faq-item summary::after { content: "+"; font-size: 1.5rem; font-weight: 300; color: var(--c-rag); margin-left: 1rem; }
.faq-item[open] summary::after { content: "-"; }
.faq-body { padding: 1.2rem 1.5rem; font-size: 0.98rem; line-height: 1.7; color: var(--text-muted); border-top: 1px solid var(--border); background: var(--bg-secondary); }
.faq-body a { color: var(--c-toc); font-weight: 700; text-decoration: underline; }
/* ИТОГОВЫЙ БЛОК */
.outro-block { background: var(--text-main); color: #fff; padding: 4rem; border-radius: var(--r-xl); margin: 2rem 0 4rem; }
.outro-block h2 { font-family: var(--font-ui); color: #fff; font-size: 1.8rem; margin-bottom: 1.5rem; }
.outro-block p { font-size: 1.05rem; opacity: 0.85; line-height: 1.75; margin-bottom: 1.5rem; max-width: 700px; }
.outro-block .btn { background: transparent; color: #fff; border-color: #fff; }
.outro-block .btn:hover { background: #fff; color: var(--text-main); border-color: #fff; }
.outro-links { display: flex; gap: 15px; flex-wrap: wrap; margin-top: 2rem; }
@media (max-width: 640px) {
  .outro-block { padding: 2rem 1.5rem; }
  .rag-simple-grid { grid-template-columns: 1fr; }
  .analogy-col:first-child { border-right: none; border-bottom: 2px solid var(--border); }
  .rag-demo-zone { padding: 1.5rem; }
}
</style>
<main class="container">
<article itemscope itemtype="https://schema.org/TechArticle">
<meta itemprop="datePublished" content="<?= $pageDate ?>">
<meta itemprop="dateModified" content="<?= $pageModified ?>">
<meta itemprop="author" content="WeAreFired">
<header class="article-hero">
  <h1 itemprop="headline">Технология RAG:<br><span>как научить ИИ читать ГОСТы</span></h1>
  <p class="hero-desc" itemprop="description">Retrieval-Augmented Generation (Генерация, дополненная поиском). Главная технология корпоративного ИИ. Именно она позволяет засунуть в нейросеть свои СП, чертежи и договоры, чтобы она отвечала строго по вашим правилам.</p>
</header>

<div class="nav-group">
  <span class="nav-label">Содержание руководства</span>
  <div class="nav-row">
    <a href="#concept" class="btn btn-outline">01 / Концепция</a>
    <a href="#how" class="btn btn-teal">02 / Как работает</a>
    <a href="#chunking" class="btn btn-punch">03 / Чанкинг</a>
    <a href="#checklist" class="btn btn-outline">04 / Чек-лист</a>
    <a href="#demo" class="btn btn-teal">05 / Демо</a>
    <a href="#faq" class="btn btn-ghost">FAQ</a>
  </div>
</div>

<section id="concept" class="content-block" itemprop="articleBody">
  <span class="section-num">01 / Concept</span>
  <h2 class="section-title">Почему ИИ не может просто «запомнить» все твои чертежи</h2>
  <p class="text-lead">RAG — это когда ИИ не «вспоминает» факты из своей базы обучения, а ищет нужную информацию в твоих личных документах и формирует ответ строго на основе найденного.</p>
  
  <div class="insight-box">
    <h3>Аналогия со студентом на экзамене</h3>
    <p class="text-main" style="margin: 0;">Ты не помнишь наизусть все СП и ГОСТы. Когда возникает спор с подрядчиком, ты открываешь нужный документ, находишь раздел, читаешь и даёшь обоснованный ответ. RAG делает то же самое, только за доли секунды и в тысячах документов одновременно.</p>
  </div>

  <div class="rag-simple-grid">
    <div class="rag-simple-card">
      <div style="font-size: 2.5rem; margin-bottom: 15px;">🧠</div>
      <strong style="font-family: var(--font-ui); font-size: 1.1rem; display: block; margin-bottom: 10px;">Контекст ограничен</strong>
      <p style="color:var(--text-muted);font-size:0.95rem;line-height:1.5;">В «оперативную память» ИИ влезает около 100–300 страниц текста. А у тебя на сервере 10 000 файлов проекта. Нельзя запихнуть всё сразу.</p>
    </div>
    <div class="rag-simple-card">
      <div style="font-size: 2.5rem; margin-bottom: 15px;">📚</div>
      <strong style="font-family: var(--font-ui); font-size: 1.1rem; display: block; margin-bottom: 10px;">Знания устаревают</strong>
      <p style="color:var(--text-muted);font-size:0.95rem;line-height:1.5;">Модель ChatGPT обучалась в 2023 году. Она физически «не знает» об изменениях в СП, которые вышли в 2024 или 2025 году.</p>
    </div>
    <div class="rag-simple-card">
      <div style="font-size: 2.5rem; margin-bottom: 15px;">🔐</div>
      <strong style="font-family: var(--font-ui); font-size: 1.1rem; display: block; margin-bottom: 10px;">Конфиденциальность</strong>
      <p style="color:var(--text-muted);font-size:0.95rem;line-height:1.5;">Твои сметы, КП и договоры — коммерческая тайна. ИИ их не видел в интернете, но благодаря локальному RAG сможет с ними работать.</p>
    </div>
  </div>
</section>

<section id="how" class="content-block">
  <span class="section-num">02 / How it works</span>
  <h2 class="section-title">Как это работает (Шаг за шагом)</h2>
  <p class="text-main">Вы загружаете папку с PDF-файлами строительных норм в систему. Что происходит под капотом?</p>
  
  <ul class="step-list">
    <li class="step-item">
      <div class="step-num">01</div>
      <div>
        <div class="step-title">Чанкование (Нарезка)</div>
        <div class="step-text">ИИ не может проглотить тысячу страниц за раз. Скрипт нарезает все PDF на маленькие кусочки (чанки) — по одному-два абзаца. Таблицы и графики аккуратно извлекаются.</div>
      </div>
    </li>
    <li class="step-item">
      <div class="step-num">02</div>
      <div>
        <div class="step-title">Векторизация (Эмбеддинги)</div>
        <div class="step-text">Каждый текстовый кусочек прогоняется через модель, которая превращает смысл текста в массив чисел (вектор). Система строит «математическую карту смыслов».</div>
      </div>
    </li>
    <li class="step-item">
      <div class="step-num">03</div>
      <div>
        <div class="step-title">Ваш запрос и поиск</div>
        <div class="step-text">Вы пишете: «Какая минимальная ширина коридора по пожарным нормам?» Система превращает вопрос в числа и находит кусочки, максимально похожие по смыслу.</div>
      </div>
    </li>
    <li class="step-item" style="border-color: var(--c-toc); background: var(--bg-secondary);">
      <div class="step-num" style="color: var(--c-toc);">04</div>
      <div>
        <div class="step-title" style="color: var(--c-toc);">Ответ нейросети (Генерация)</div>
        <div class="step-text">Система берёт найденный кусок из СП и отправляет в ИИ с инструкцией: «Ответь, используя ТОЛЬКО этот текст». ИИ компилирует ответ со ссылкой на документ.</div>
      </div>
    </li>
  </ul>

  <div class="insight-box" style="border-left-color: var(--c-rag);">
    <h3 style="color: var(--c-rag);">Почему RAG — это будущее проектирования?</h3>
    <p class="text-main" style="margin: 0;">Нейросеть не нужно переобучать каждый раз, когда выходит новый СП. Вы просто закидываете новый PDF в базу знаний, и система мгновенно начинает отвечать по новым правилам. База данных (знания) и нейросеть (мозги) разделены.</p>
  </div>
</section>

<section id="chunking" class="content-block">
  <span class="section-num">03 / Chunking</span>
  <h2 class="section-title">Почему нарезка текста — это 80% успеха</h2>
  
  <div class="insight-box warn">
    <h3>⚠️ Главная ошибка новичков</h3>
    <p class="text-main" style="margin: 0;">Резать документы «вслепую», просто отсчитывая каждые 500 символов. Таблица с характеристиками бетона окажется разорвана пополам, контекст потерян, модель начнёт галлюцинировать.</p>
  </div>

  <div class="rag-simple-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
    <div class="rag-simple-card" style="border-top: 4px solid var(--c-rag);">
      <strong style="color: var(--c-rag); font-size: 1.2rem; display: block; margin-bottom: 15px;">❌ Наивный чанкинг</strong>
      <p style="color:var(--text-muted);font-size:1rem;line-height:1.5;">Режет текст строго каждые 300 слов. Таблицы разорваны, списки обрезаны на середине. Контекст потерян.</p>
    </div>
    <div class="rag-simple-card" style="border-top: 4px solid var(--c-toc);">
      <strong style="color: var(--c-toc); font-size: 1.2rem; display: block; margin-bottom: 15px;">✅ Структурный чанкинг</strong>
      <p style="color:var(--text-muted);font-size:1rem;line-height:1.5;">Умный парсер режет документ по заголовкам (H1, H2, H3), сохраняет целостность разделов и таблиц. Каждый чанк — логически завершённый блок.</p>
    </div>
  </div>
</section>

<section id="checklist" class="content-block">
  <span class="section-num">04 / Checklist</span>
  <h2 class="section-title">Готов ли твой проектный отдел к RAG?</h2>
  <p class="text-main">Если вы скармливаете ИИ мусор — вы получите мусор. Перед развёртыванием базы знаний проверьте свои файлы:</p>
  
  <div class="rag-checklist">
    <label><input type="checkbox"> <span>Документы в текстовом формате (DOCX, нормальный PDF), а не отсканированные картинки.</span></label>
    <label><input type="checkbox"> <span>В документах есть структура: стили заголовков, разделы, нумерация списков.</span></label>
    <label><input type="checkbox"> <span>Таблицы оформлены корректно (встроенными инструментами, а не картинкой).</span></label>
    <label><input type="checkbox"> <span>Понятно версионирование: система знает, какой файл реально актуальный.</span></label>
  </div>

  <div class="insight-box" style="margin-top: 3rem;">
    <h3>💡 Практический совет для старта</h3>
    <p class="text-main" style="margin: 0;">Не пытайтесь сразу загрузить в RAG весь архив компании за 10 лет. Начните с одного актуального СП или внутреннего регламента. Разрежьте аккуратно, загрузите, протестируйте на 10–15 рабочих вопросах. Если ИИ отвечает точно со ссылками — масштабируйте.</p>
  </div>
</section>

<section id="demo" class="content-block">
  <span class="section-num">05 / Interactive Demo</span>
  <h2 class="section-title">Интерактивная демо-зона RAG</h2>
  <p class="text-main">Сыграйте роль инженера. Выберите любой рабочий вопрос и посмотрите, как система найдёт ответ в нормативной базе без галлюцинаций.</p>
  
  <div class="rag-demo-zone">
    <div class="rag-demo-questions">
      <button onclick="window.runRagDemo('fire_rating')" class="rag-demo-qbtn">
        <strong>🔥 Какая огнестойкость у перегородок в коридоре эвакуации?</strong>
        <small>Векторный поиск по СП 2.13130.2020</small>
      </button>
      <button onclick="window.runRagDemo('exit_requirements')" class="rag-demo-qbtn">
        <strong>🚪 Назови требования к эвакуационным выходам</strong>
        <small>Синтез ответа из нескольких разделов СП</small>
      </button>
      <button onclick="window.runRagDemo('oim_definition')" class="rag-demo-qbtn">
        <strong>📐 Что такое ОИМ и ТИМ в терминологии?</strong>
        <small>Поиск строгих определений по ГОСТу</small>
      </button>
    </div>
    <div id="ragDemoContainer">
      <div id="stepQuestion" class="rag-demo-step">
        <div style="font-size:0.85rem;color:var(--text-muted);margin-bottom:.8rem; font-family: var(--font-ui); text-transform: uppercase; font-weight: 700;">ШАГ 1: Пользовательский запрос</div>
        <div id="demoQuestion" style="font-weight:900;font-size:1.4rem; color: var(--c-rag); font-family: var(--font-ui);"></div>
      </div>
      <div id="stepSearch" class="rag-demo-step">
        <div style="font-size:0.85rem;color:var(--text-muted);margin-bottom:1.2rem; font-family: var(--font-ui); text-transform: uppercase; font-weight: 700;">ШАГ 2: Векторный поиск по базе (ChromaDB)</div>
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;">
          <div class="rag-demo-spinner" id="searchSpinner"></div>
          <div style="color:var(--text-muted);font-size:1.05rem;" id="searchText">Превращаем запрос в эмбеддинг и сканируем 10,000 чанков...</div>
        </div>
        <div id="foundChunks" style="display:none;">
          <div style="color:var(--c-toc);font-size:1rem;margin-bottom:1.2rem; font-weight: 700;">📄 Найдено 3 релевантных документа:</div>
          <div id="chunksList" style="display:flex;flex-direction:column;"></div>
        </div>
      </div>
      <div id="stepContext" class="rag-demo-step">
        <div style="font-size:0.85rem;color:var(--text-muted);margin-bottom:.8rem; font-family: var(--font-ui); text-transform: uppercase; font-weight: 700;">ШАГ 3: Сборка скрытого системного промпта</div>
        <p style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 12px;">Этот текст отправляется в LLM «за кулисами»:</p>
        <div id="contextContent" class="rag-demo-context"></div>
      </div>
      <div id="stepAnswer" class="rag-demo-step" style="padding: 0; border: none; background: transparent;">
        <div class="rag-demo-answer">
          <div style="font-size:0.85rem;color:var(--c-toc);margin-bottom:1.2rem;font-weight:700;font-family: var(--font-ui); text-transform: uppercase;">ШАГ 4: Финальный ответ ИИ</div>
          <div id="demoAnswer" style="font-size:1.15rem;line-height:1.6;color:var(--text-main);"></div>
          <div style="margin-top:2rem;padding-top:1.2rem;border-top:1px dashed rgba(10, 124, 106, 0.4);font-size:0.9rem;color:var(--text-muted);">
            <strong>Сгенерировано на основе:</strong> <span id="answerSources" style="color: var(--c-toc); font-weight: 700;"></span>
          </div>
        </div>
      </div>
    </div>
    <button onclick="window.resetRagDemo()" id="resetDemoBtn" class="rag-demo-reset">Попробовать другой запрос ↺</button>
  </div>
</section>

<section id="faq" class="content-block">
  <span class="section-num">06 / FAQ</span>
  <h2 class="section-title">Частые вопросы</h2>
  <details class="faq-item">
    <summary>Что такое RAG простыми словами?</summary>
    <div class="faq-body">RAG — это технология, которая заставляет нейросеть искать ответы в ваших документах перед генерацией ответа. Вместо того чтобы «вспоминать» факты из обучающей выборки, ИИ читает ваши СП, ГОСТы и договоры, и отвечает строго по ним.</div>
  </details>
  <details class="faq-item">
    <summary>Почему нейросеть придумывает ГОСТы без RAG?</summary>
    <div class="faq-body">Это называется галлюцинацией. Языковая модель предсказывает следующее слово, а не ищет факты. Она генерирует убедительно звучащий текст, который может оказаться ложью. RAG решает проблему, привязывая ответ к реальным документам.</div>
  </details>
  <details class="faq-item">
    <summary>Можно ли использовать RAG с коммерческой тайной?</summary>
    <div class="faq-body">Только в локальном контуре. Загрузка договоров в облачный ChatGPT нарушает 98-ФЗ. Локальный RAG (Ollama + RAGFlow) работает на вашем сервере без выхода в интернет. Данные не покидают периметр организации.</div>
  </details>
  <details class="faq-item">
    <summary>Какой сервер нужен для RAG?</summary>
    <div class="faq-body">Для пилота хватит ноутбука с 16 ГБ RAM. Для отдела — сервер с GPU 8 ГБ VRAM. Для полного архива — выделенный сервер с 64+ ГБ RAM и GPU 24 ГБ. Модели 7–13B работают на обычном офисном железе.</div>
  </details>
</section>

<div class="outro-block">
  <h2>От слов к делу</h2>
  <p>Загружать коммерческую документацию в облачный RAG — значит нарушать закон. Поэтому мы собрали готовую архитектуру для запуска своего собственного RAG-сервера прямо в офисе.</p>
  <p>Начните с одного документа. Проверьте точность. Масштабируйте.</p>
  <div class="outro-links">
    <a href="/rag-okn" class="btn btn-punch" style="padding: 1.2rem 3rem;">RAG для памятников архитектуры →</a>
    <a href="/infra" class="btn btn-outline" style="padding: 1.2rem 3rem;">Домашний сервер</a>
    <a href="https://ai.ovc.me/lessons" class="btn btn-outline" style="padding: 1.2rem 3rem;">Как пользоваться ИИ</a>
  </div>
</div>
</article>
</main>

<script>
// ─── ЛОГИКА ИНТЕРАКТИВНОГО ДЕМО RAG (Safari-safe) ───
var ragDemoTimeouts = [];
var ragDemoRunning = false;

var ragChunks = {
  fire_rating: [
    {text: 'СП 2.13130.2020, п. 5.4.3: Перегородки в путях эвакуации (коридорах) должны иметь предел огнестойкости не менее REI-45.', source: 'СП 2.13130.2020', relevance: 0.94},
    {text: 'СП 1.13130.2020, п. 4.1.2: Коридоры эвакуационные это пути эвакуации, соединяющие выходы из помещений с эвакуационными выходами на улицу.', source: 'СП 1.13130.2020', relevance: 0.87},
    {text: 'ГОСТ Р 10.00.00.01-2025: ОИМ объект информационного моделирования, ТИМ технология информационного моделирования.', source: 'ГОСТ Р 10.00.00.01-2025', relevance: 0.12}
  ],
  exit_requirements: [
    {text: 'СП 1.13130.2020, п. 4.2.5: Ширина эвакуационного выхода из помещений должна быть не менее 0.8 метра в чистоте.', source: 'СП 1.13130.2020', relevance: 0.91},
    {text: 'СП 1.13130.2020, п. 4.2.8: Количество эвакуационных выходов из помещения должно быть не менее двух, если площадь превышает норму.', source: 'СП 1.13130.2020', relevance: 0.89},
    {text: 'СП 2.13130.2020, п. 5.1.1: Эвакуационные выходы из здания должны располагаться рассредоточенно для безопасности.', source: 'СП 2.13130.2020', relevance: 0.85}
  ],
  oim_definition: [
    {text: 'ГОСТ Р 10.00.00.01-2025, п. 3.1: ОИМ здание, сооружение или комплекс, в отношении которого осуществляется информационное моделирование.', source: 'ГОСТ Р 10.00.00.01-2025', relevance: 0.96},
    {text: 'ГОСТ Р 10.00.00.01-2025, п. 3.2: ТИМ совокупность методов, способов и инструментов создания, ведения и использования информационной модели.', source: 'ГОСТ Р 10.00.00.01-2025', relevance: 0.95},
    {text: 'ГОСТ Р 10.00.00.01-2025, п. 3.5: BIM (Building Information Modeling) синоним ТИМ, применяемый в международной практике.', source: 'ГОСТ Р 10.00.00.01-2025', relevance: 0.88}
  ]
};

var ragAnswers = {
  fire_rating: 'Согласно <strong>СП 2.13130.2020 (п. 5.4.3)</strong>, перегородки в коридорах эвакуации должны иметь предел огнестойкости <strong>не менее REI-45</strong>.<br><br><em style="color: var(--text-muted); font-size: 0.95rem;">(R — потеря несущей способности, E — потеря целостности, I — потеря теплоизолирующей способности. Значение 45 означает 45 минут).</em>',
  exit_requirements: 'Базовые требования к эвакуационным выходам (согласно <strong>СП 1.13130.2020</strong> и <strong>СП 2.13130.2020</strong>):<br><br>1. <strong>Ширина:</strong> не менее 0.8 метра (п. 4.2.5).<br>2. <strong>Количество:</strong> как правило, не менее двух выходов (п. 4.2.8).<br>3. <strong>Расположение:</strong> выходы должны располагаться рассредоточенно (п. 5.1.1).',
  oim_definition: 'Согласно <strong>ГОСТ Р 10.00.00.01-2025</strong>:<br><br>• <strong>ОИМ</strong> — это само здание или сооружение, для которого создается модель.<br>• <strong>ТИМ</strong> — это методы и инструменты для создания этой модели.<br><br>В международной практике вместо аббревиатуры ТИМ используется термин <strong>BIM</strong>.'
};

window.ragClearAllTimeouts = function() {
  for (var i = 0; i < ragDemoTimeouts.length; i++) {
    clearTimeout(ragDemoTimeouts[i]);
  }
  ragDemoTimeouts = [];
  ragDemoRunning = false;
};

window.runRagDemo = function(type) {
  if (ragDemoRunning) { window.ragClearAllTimeouts(); }
  ragDemoRunning = true;
  
  document.getElementById('resetDemoBtn').style.display = 'none';
  var container = document.getElementById('ragDemoContainer');
  container.style.display = 'block';
  
  ['stepQuestion', 'stepSearch', 'stepContext', 'stepAnswer'].forEach(function(id) {
    document.getElementById(id).classList.remove('visible');
  });
  
  var questions = {
    fire_rating: 'Какая минимальная огнестойкость у перегородок в коридоре эвакуации?',
    exit_requirements: 'Назови основные требования к эвакуационным выходам',
    oim_definition: 'Что такое ОИМ и ТИМ в терминологии?'
  };
  
  document.getElementById('demoQuestion').textContent = '«' + questions[type] + '»';
  
  var t1 = setTimeout(function() {
    document.getElementById('stepQuestion').classList.add('visible');
  }, 100);
  ragDemoTimeouts.push(t1);
  
  var t2 = setTimeout(function() {
    document.getElementById('stepSearch').classList.add('visible');
    var t3 = setTimeout(function() {
      document.getElementById('searchSpinner').style.display = 'none';
      document.getElementById('searchText').style.display = 'none';
      document.getElementById('foundChunks').style.display = 'block';
      var chunksList = document.getElementById('chunksList');
      chunksList.innerHTML = '';
      var relevantChunks = ragChunks[type].filter(function(c) { return c.relevance > 0.5; });
      relevantChunks.forEach(function(chunk, index) {
        var d = document.createElement('div');
        d.className = 'rag-chunk-item';
        d.style.animationDelay = (index * 0.2) + 's';
        d.innerHTML = '<div style="font-size:0.8rem; color:var(--text-muted); margin-bottom:.5rem; font-family:var(--font-code); text-transform:uppercase; font-weight:700;">📄 Файл: ' + chunk.source + ' &nbsp;|&nbsp; <span style="color:var(--c-toc);">🎯 Совпадение: ' + (chunk.relevance * 100).toFixed(0) + '%</span></div><div style="font-size:1rem; color: var(--text-main); line-height: 1.5;">' + chunk.text + '</div>';
        chunksList.appendChild(d);
      });
    }, 1500);
    ragDemoTimeouts.push(t3);
  }, 800);
  ragDemoTimeouts.push(t2);
  
  var t4 = setTimeout(function() {
    document.getElementById('stepContext').classList.add('visible');
    var relevantTexts = ragChunks[type].filter(function(c) { return c.relevance > 0.5; }).map(function(c, i) { return '[Документ ' + (i+1) + ']: ' + c.text; }).join('\n');
    var promptText = 'СИСТЕМНАЯ ИНСТРУКЦИЯ:\nТы эксперт-проектировщик. Ответь на вопрос пользователя, используя ТОЛЬКО предоставленные ниже документы.\n\nНАЙДЕННЫЕ ДОКУМЕНТЫ:\n' + relevantTexts + '\n\nВОПРОС ПОЛЬЗОВАТЕЛЯ:\n' + questions[type];
    document.getElementById('contextContent').textContent = promptText;
  }, 3200);
  ragDemoTimeouts.push(t4);
  
  var t5 = setTimeout(function() {
    document.getElementById('stepAnswer').classList.add('visible');
    document.getElementById('demoAnswer').innerHTML = ragAnswers[type];
    var sources = ragChunks[type].filter(function(c) { return c.relevance > 0.5; }).map(function(c) { return c.source; }).filter(function(v, i, a) { return a.indexOf(v) === i; }).join(', ');
    document.getElementById('answerSources').textContent = sources;
    var t6 = setTimeout(function() {
      document.getElementById('resetDemoBtn').style.display = 'inline-block';
      ragDemoRunning = false;
    }, 800);
    ragDemoTimeouts.push(t6);
  }, 4500);
  ragDemoTimeouts.push(t5);
};

window.resetRagDemo = function() {
  window.ragClearAllTimeouts();
  document.getElementById('ragDemoContainer').style.display = 'none';
  document.getElementById('resetDemoBtn').style.display = 'none';
  document.getElementById('searchSpinner').style.display = 'inline-block';
  document.getElementById('searchText').style.display = 'block';
  document.getElementById('foundChunks').style.display = 'none';
};
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "TechArticle",
  "headline": "<?= htmlspecialchars($pageTitle) ?>",
  "description": "<?= htmlspecialchars($pageDescription) ?>",
  "image": "<?= $pageImage ?>",
  "author": { "@type": "Organization", "name": "WeAreFired", "url": "https://rag.ovc.me" },
  "publisher": { "@type": "Organization", "name": "WeAreFired", "logo": { "@type": "ImageObject", "url": "https://rag.ovc.me/img/logo.svg" } },
  "datePublished": "<?= $pageDate ?>",
  "dateModified": "<?= $pageModified ?>",
  "url": "<?= $pageUrl ?>",
  "keywords": "<?= htmlspecialchars($pageKeywords) ?>",
  "inLanguage": "ru"
}
</script>

<?php include 'footer.php'; ?>
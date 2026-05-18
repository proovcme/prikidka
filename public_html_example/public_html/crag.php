<?php
$siteId = 'rag';
$pageTitle = 'CRAG: Corrective RAG — самокоррекция ИИ | WeAreFired';
$pageDescription = 'Архитектура Corrective RAG: как заставить нейросеть проверять собственные ответы и исправлять галлюцинации до выдачи результата.';
$pageKeywords = 'CRAG, Corrective RAG, самокоррекция ИИ, фильтрация галлюцинаций, RAG архитектура, верификация ответов';
$pageAuthor = 'WeAreFired';
$pageDate = '2026-05-24';
$pageModified = '2026-05-24';
$pageUrl = 'https://rag.ovc.me/crag';
$pageImage = 'https://rag.ovc.me/img/og-crag.jpg';
include 'header.php';
?>
<style>
/* ─── СТИЛИ CRAG.PHP (OVC DS, акцент RAG) ─── */
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
}
</style>
<main class="container">
<article itemscope itemtype="https://schema.org/TechArticle">
<meta itemprop="datePublished" content="<?= $pageDate ?>">
<meta itemprop="dateModified" content="<?= $pageModified ?>">
<meta itemprop="author" content="WeAreFired">
<header class="article-hero">
  <h1 itemprop="headline">CRAG:<br><span>Corrective RAG</span></h1>
  <p class="hero-desc" itemprop="description">Архитектура с самокоррекцией. Система оценивает релевантность найденных документов до генерации ответа, отбрасывает шум и перезапрашивает поиск, если контекст недостаточен.</p>
</header>

<div class="nav-group">
  <span class="nav-label">Содержание руководства</span>
  <div class="nav-row">
    <a href="#concept" class="btn btn-outline">01 / Концепция</a>
    <a href="#pipeline" class="btn btn-teal">02 / Пайплайн</a>
    <a href="#comparison" class="btn btn-punch">03 / Сравнение</a>
    <a href="#faq" class="btn btn-ghost">FAQ</a>
  </div>
</div>

<section id="concept" class="content-block" itemprop="articleBody">
  <span class="section-num">01 / Concept</span>
  <h2 class="section-title">Почему базового RAG недостаточно</h2>
  <p class="text-lead">Базовый RAG минимизирует галлюцинации. Но для инженерных решений этого мало. В реставрации и проектировании ошибка стоит слишком дорого.</p>
  <p class="text-main">CRAG (Corrective RAG) — это поисковик с самопроверкой. Система не просто находит фрагменты, она оценивает их качество до того, как модель начнёт генерировать ответ. Если контекст слабый — CRAG переформулирует запрос или честно говорит «не знаю».</p>
  
  <div class="insight-box">
    <h3>Главное отличие</h3>
    <p class="text-main" style="margin: 0;">CRAG знает, когда сказать «не знаю» — и не молчит об этом. Это критично для задач, где выдуманный ответ опаснее отсутствия ответа.</p>
  </div>
</section>

<section id="pipeline" class="content-block">
  <span class="section-num">02 / Pipeline</span>
  <h2 class="section-title">Как работает самокоррекция</h2>
  <p class="text-main">Пайплайн CRAG добавляет этап оценки (Evaluator) между поиском и генерацией. Каждый найденный фрагмент проходит строгий фильтр.</p>
  
  <ul class="step-list">
    <li class="step-item">
      <div class="step-num">01</div>
      <div>
        <div class="step-title">Поиск фрагментов</div>
        <div class="step-text">Система находит Top-N релевантных фрагментов из архива — как в обычном RAG. Векторный поиск по эмбеддингам.</div>
      </div>
    </li>
    <li class="step-item">
      <div class="step-num">02</div>
      <div>
        <div class="step-title">Оценка качества (Evaluator)</div>
        <div class="step-text">Каждый фрагмент оценивается: насколько он реально отвечает на вопрос? Три класса: «Верно» / «Неверно» / «Неопределённо».</div>
      </div>
    </li>
    <li class="step-item">
      <div class="step-num">03</div>
      <div>
        <div class="step-title">Корректирующее действие</div>
        <div class="step-text">«Верно» → используем. «Неверно» → отбрасываем, расширяем поиск. «Неопределённо» → переформулируем запрос и ищем снова.</div>
      </div>
    </li>
    <li class="step-item" style="border-color: var(--c-toc); background: var(--bg-secondary);">
      <div class="step-num" style="color: var(--c-toc);">04</div>
      <div>
        <div class="step-title" style="color: var(--c-toc);">Генерация или отказ</div>
        <div class="step-text">Если контекст достаточный — модель формирует ответ. Если нет — система явно сообщает об отсутствии данных вместо выдумки.</div>
      </div>
    </li>
  </ul>
</section>

<section id="comparison" class="content-block">
  <span class="section-num">03 / Comparison</span>
  <h2 class="section-title">RAG vs CRAG vs Safe-RAG</h2>
  <p class="text-main">Три уровня надёжности. Каждый решает свою задачу. В связке они дают максимальную защиту для инженерных решений.</p>
  
  <div class="tools-grid">
    <div class="tool-card">
      <span class="tool-tag">Базовый уровень</span>
      <h3 class="tool-name">RAG</h3>
      <p class="tool-desc">Поисковик. Находит фрагменты по смыслу и генерирует ответ со ссылкой на источник. Минимизирует галлюцинации, но не гарантирует точность.</p>
    </div>
    <div class="tool-card c-teal">
      <span class="tool-tag">Самопроверка</span>
      <h3 class="tool-name">CRAG</h3>
      <p class="tool-desc">Поисковик с самопроверкой. Оценивает качество найденного до генерации. Переформулирует запрос или честно отказывается отвечать.</p>
    </div>
    <div class="tool-card c-punch">
      <span class="tool-tag">Защита периметра</span>
      <h3 class="tool-name">Safe-RAG</h3>
      <p class="tool-desc">Поисковик с охраной на входе. Фильтрует токсичные промпты, маскирует PII, контролирует провенанс источников и защищает от отравления базы.</p>
    </div>
  </div>
</section>

<section id="faq" class="content-block">
  <span class="section-num">04 / FAQ</span>
  <h2 class="section-title">Частые вопросы</h2>
  <details class="faq-item">
    <summary>Когда нужен CRAG вместо обычного RAG?</summary>
    <div class="faq-body">Когда цена ошибки высока: реставрация ОКН, медицинские протоколы, юридические заключения. CRAG добавляет слой верификации, который отсекает слабые контексты до генерации.</div>
  </details>
  <details class="faq-item">
    <summary>CRAG работает медленнее?</summary>
    <div class="faq-body">Да, на 20-40% медленнее базового RAG из-за этапа оценки. Но для инженерных задач точность важнее скорости. Запрос за 3 секунды с гарантией лучше запроса за 1 секунду с риском галлюцинации.</div>
  </details>
  <details class="faq-item">
    <summary>Можно ли комбинировать CRAG и Safe-RAG?</summary>
    <div class="faq-body">Да, это рекомендуемая архитектура для корпоративного контура. Safe-RAG фильтрует входные данные и промпты, CRAG верифицирует найденный контекст. Вместе они дают максимальную надёжность.</div>
  </details>
</section>

<div class="outro-block">
  <h2>Надёжность через архитектуру</h2>
  <p>CRAG не заменяет инженера. Он даёт инженеру инструмент, который знает свои границы. Когда система говорит «в базе нет информации» — это не баг, это фича.</p>
  <p>Изучите базовую архитектуру RAG перед внедрением CRAG. Самокоррекция строится поверх фундаментальных концепций чанкования и векторного поиска.</p>
  <div class="outro-links">
    <a href="/rag-simple" class="btn btn-punch" style="padding: 1.2rem 3rem;">← Основы RAG</a>
    <a href="/safe-rag" class="btn btn-outline" style="padding: 1.2rem 3rem;">Safe-RAG →</a>
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
<?php
$siteId = 'rag';
$pageTitle = 'CRAG: Corrective RAG — как заставить ИИ проверять себя | WeAreFired';
$pageDescription = 'Архитектура Corrective RAG: почему обычный поиск выдает галлюцинации, как работает модуль Evaluator и самокоррекция запросов в инженерных системах.';
$pageKeywords = 'CRAG, Corrective RAG, самокоррекция ИИ, Evaluator, RAG пайплайн, фильтрация галлюцинаций, инженерный ИИ';
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
.faq-item { border: 1px solid var(--border); border-radius: var(--r-lg); margin-bottom: 0.8rem; overflow: hidden; background: var(--bg-main); }
.faq-item summary { padding: 1.2rem 1.5rem; font-family: var(--font-ui); font-weight: 700; font-size: 1.05rem; cursor: pointer; list-style: none; display: flex; justify-content: space-between; align-items: center; color: var(--text-main); }
.faq-item summary:hover { background: var(--bg-secondary); }
.faq-item summary::after { content: "+"; font-size: 1.5rem; font-weight: 300; color: var(--c-rag); margin-left: 1rem; }
.faq-item[open] summary::after { content: "-"; }
.faq-body { padding: 1.2rem 1.5rem; font-size: 0.98rem; line-height: 1.7; color: var(--text-muted); border-top: 1px solid var(--border); background: var(--bg-secondary); }
.faq-body a { color: var(--c-toc); font-weight: 700; text-decoration: underline; }
/* GOLDEN OUTRO */
.outro-block { background: var(--c-rag); color: #111; padding: 4rem; border-radius: var(--r-xl); margin: 2rem 0 4rem; }
.outro-block h2 { font-family: var(--font-ui); color: #111; font-size: 1.8rem; margin-bottom: 1.5rem; }
.outro-block p { font-size: 1.05rem; opacity: 0.9; line-height: 1.75; margin-bottom: 1.5rem; max-width: 700px; }
.outro-block .btn { background: #111; color: var(--c-rag); border-color: #111; }
.outro-block .btn:hover { background: transparent; color: #111; border-color: #111; }
.outro-links { display: flex; gap: 15px; flex-wrap: wrap; margin-top: 2rem; }
/* CRAG DEMO */
.crag-demo-zone { background: var(--bg-secondary); border: 2px solid var(--border); border-radius: var(--r-lg); padding: 3rem; margin: 4rem 0; }
.crag-demo-btn { text-align: left; padding: 1.5rem; background: var(--bg-main); border: 2px solid var(--border); border-radius: var(--r-md); cursor: pointer; transition: all 0.2s; width: 100%; font-family: var(--font-ui); }
.crag-demo-btn:hover { border-color: var(--c-rag); transform: translateY(-2px); }
.crag-demo-btn strong { display: block; margin-bottom: 0.5rem; font-size: 1.1rem; color: var(--text-main); font-weight: 700; }
.crag-demo-btn small { color: var(--text-muted); font-size: 0.85rem; font-family: var(--font-code); }
#cragDemoContainer { display: none; margin-top: 2rem; }
.crag-step { opacity: 0; transform: translateY(10px); transition: all 0.4s ease; background: var(--bg-main); border: 2px solid var(--border); border-radius: var(--r-md); padding: 1.8rem; margin-bottom: 1.2rem; }
.crag-step.visible { opacity: 1; transform: translateY(0); }
.crag-spinner { width: 22px; height: 22px; border: 3px solid var(--border); border-top-color: var(--c-rag); border-radius: 50%; animation: spin 1s linear infinite; display: inline-block; margin-right: 1rem; vertical-align: middle; }
@keyframes spin { to { transform: rotate(360deg); } }
.score-tag { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 4px; font-family: var(--font-code); font-size: 0.85rem; font-weight: 900; }
.score-reject { background: #ff4d4d; color: #fff; }
.score-accept { background: #4dff4d; color: #111; }
.crag-reset { display: none; margin-top: 2rem; background: var(--text-main); color: var(--bg-main); border: none; padding: 1rem 2rem; border-radius: var(--r-md); cursor: pointer; font-family: var(--font-ui); font-weight: 900; text-transform: uppercase; font-size: 0.95rem; transition: all 0.2s; letter-spacing: 0.05em; }
.crag-reset:hover { background: var(--c-rag); color: #111; transform: translateY(-2px); }
@media (max-width: 640px) {
  .outro-block { padding: 2rem 1.5rem; }
  .tools-grid { grid-template-columns: 1fr; }
  .crag-demo-zone { padding: 1.5rem; }
}
</style>
<main class="container">
<article itemscope itemtype="https://schema.org/TechArticle">
<meta itemprop="datePublished" content="<?= $pageDate ?>">
<meta itemprop="dateModified" content="<?= $pageModified ?>">
<meta itemprop="author" content="WeAreFired">
<header class="article-hero">
  <h1 itemprop="headline">CRAG:<br><span>Corrective RAG</span></h1>
  <p class="hero-desc" itemprop="description">Архитектура с самокоррекцией. Система оценивает релевантность найденных документов до генерации, отбрасывает шум и перезапрашивает поиск, если контекст недостаточен.</p>
</header>

<div class="nav-group">
  <span class="nav-label">Содержание руководства</span>
  <div class="nav-row">
    <a href="#concept" class="btn btn-outline">01 / Концепция</a>
    <a href="#how" class="btn btn-teal">02 / Как работает</a>
    <a href="#comparison" class="btn btn-punch">03 / Сравнение</a>
    <a href="#faq" class="btn btn-ghost">FAQ</a>
  </div>
</div>

<section id="concept" class="content-block" itemprop="articleBody">
  <span class="section-num">01 / Concept</span>
  <h2 class="section-title">Почему базового RAG недостаточно</h2>
  <p class="text-lead">Базовый RAG минимизирует галлюцинации. Но для инженерных решений этого мало. В проектировании ошибка стоит слишком дорого.</p>
  <p class="text-main">CRAG (Corrective RAG) — это поисковик с самопроверкой. Он добавляет модуль <strong>Evaluator</strong> между поиском и генерацией. Каждый найденный фрагмент проходит строгий фильтр релевантности. Если контекст слабый — CRAG честно говорит «не знаю» или переформулирует запрос.</p>
  
  <div class="insight-box danger">
    <h3>Главное отличие</h3>
    <p class="text-main" style="margin: 0;">CRAG знает, когда сказать «не знаю» — и не молчит об этом. Обычный RAG выдаст красивый ответ даже по мусорному чанку. CRAG отбросит его и расширит поиск.</p>
  </div>
</section>

<section id="how" class="content-block">
  <span class="section-num">02 / How it works</span>
  <h2 class="section-title">Как работает самокоррекция</h2>
  <p class="text-main">Нажмите кнопку ниже, чтобы увидеть пайплайн CRAG в действии. Обратите внимание на этап оценки (Evaluator) и перезапрос.</p>
  
  <div class="crag-demo-zone">
    <button onclick="window.runCragDemo()" class="crag-demo-btn">
      <strong>🧪 Запустить симуляцию CRAG</strong>
      <small>Запрос: «Марка бетона для ленточного фундамента на пучинистых грунтах»</small>
    </button>
    <div id="cragDemoContainer">
      <div id="cStepQuery" class="crag-step"><div style="font-size:0.85rem;color:var(--text-muted);margin-bottom:.8rem; font-weight:700;">ШАГ 1: Запрос пользователя</div><div style="font-weight:900;font-size:1.3rem; color:var(--c-rag);">«Марка бетона для ленточного фундамента на пучинистых грунтах»</div></div>
      
      <div id="cStepSearch" class="crag-step"><div style="font-size:0.85rem;color:var(--text-muted);margin-bottom:1rem; font-weight:700;">ШАГ 2: Первичный поиск (Vector DB)</div><div style="display:flex;align-items:center;gap:1rem;"><div class="crag-spinner" id="cSearchSpin"></div><div style="color:var(--text-muted);" id="cSearchText">Сканируем 5,000 чанков...</div></div></div>
      
      <div id="cStepEval" class="crag-step"><div style="font-size:0.85rem;color:var(--text-muted);margin-bottom:1rem; font-weight:700;">ШАГ 3: Evaluator (Оценка качества)</div><div id="cEvalList"></div></div>
      
      <div id="cStepRewrite" class="crag-step"><div style="font-size:0.85rem;color:var(--text-muted);margin-bottom:.8rem; font-weight:700;">ШАГ 4: Переформулировка (Query Rewriter)</div><div style="padding:1rem; background:var(--bg-secondary); border-radius:var(--r-md); border-left: 4px solid var(--c-amber); font-family:var(--font-code); font-size:0.9rem; color:var(--text-main);">⚠️ Низкая уверенность (<span id="cConfScore">32%</span>). Переписываю запрос: <strong>«СП 63.13330 бетон класс по прочности пучинистые грунты»</strong></div></div>
      
      <div id="cStepFinal" class="crag-step"><div style="font-size:0.85rem;color:var(--text-muted);margin-bottom:.8rem; font-weight:700;">ШАГ 5: Расширенный поиск + Генерация</div><div style="display:flex; align-items:flex-start; gap:1rem;"><div style="background:var(--bg-secondary); padding:1.5rem; border-radius:var(--r-md); border:2px solid var(--c-toc); flex:1;"><div style="font-size:0.85rem;color:var(--c-toc);margin-bottom:.5rem; font-weight:700;">✅ Ответ CRAG</div><div style="line-height:1.6;">Согласно <strong>СП 63.13330.2018 (п. 7.1.2)</strong> для бетонных и железобетонных конструкций на пучинистых грунтах минимальный класс бетона по прочности на сжатие должен быть не ниже <strong>B15 (М200)</strong>. Требуется учет морозостойкости (F75) и водонепроницаемости (W4).</div></div></div></div>
    </div>
    <button onclick="window.resetCragDemo()" id="cResetBtn" class="crag-reset">Запустить снова ↺</button>
  </div>
</section>

<section id="comparison" class="content-block">
  <span class="section-num">03 / Comparison</span>
  <h2 class="section-title">RAG vs CRAG</h2>
  <p class="text-main">Обычный RAG — линейный процесс. CRAG добавляет ветвление и самопроверку.</p>
  
  <div class="tools-grid">
    <div class="tool-card">
      <span class="tool-tag">Линейный</span>
      <h3 class="tool-name">Базовый RAG</h3>
      <p class="tool-desc">Запрос → Поиск Top-K → Генерация. Берёт первые попавшиеся чанки. Если база грязная — галлюцинирует уверенно.</p>
    </div>
    <div class="tool-card c-teal">
      <span class="tool-tag">Циклический</span>
      <h3 class="tool-name">CRAG</h3>
      <p class="tool-desc">Запрос → Поиск → Evaluator → (Принять / Отклонить → Переписать → Искать снова) → Генерация. Отсеивает шум до ответа.</p>
    </div>
  </div>
</section>

<section id="faq" class="content-block">
  <span class="section-num">04 / FAQ</span>
  <h2 class="section-title">Частые вопросы</h2>
  <details class="faq-item"><summary>Когда нужен CRAG вместо обычного RAG?</summary><div class="faq-body">Когда цена ошибки высока: реставрация ОКН, медицинские протоколы, юридические заключения, нормоконтроль. CRAG добавляет слой верификации, который отсекает слабые контексты до генерации.</div></details>
  <details class="faq-item"><summary>CRAG работает медленнее?</summary><div class="faq-body">Да, на 20-40% медленнее базового RAG из-за этапа оценки и возможного перезапроса. Но для инженерных задач точность важнее скорости. Ответ за 4 сек с гарантией лучше ответа за 1.5 сек с риском.</div></details>
  <details class="faq-item"><summary>Как работает Evaluator?</summary><div class="faq-body">Это отдельная легковесная модель или LLM-промпт, который получает пару (вопрос, чанк) и возвращает score 0.0–1.0. Threshold обычно 0.7. Ниже — чанк отбрасывается или идёт в расширенный поиск.</div></details>
</section>

<div class="outro-block">
  <h2>Надёжность через архитектуру</h2>
  <p>CRAG не заменяет инженера. Он даёт инженеру инструмент, который знает свои границы. Когда система говорит «в базе нет информации» — это не баг, это фича.</p>
  <p>Изучите Safe-RAG для защиты контура. В связке CRAG + Safe-RAG дают максимальную точность и безопасность.</p>
  <div class="outro-links">
    <a href="/safe-rag" class="btn btn-punch" style="padding: 1.2rem 3rem;">Safe-RAG →</a>
    <a href="/rag-simple" class="btn btn-outline" style="padding: 1.2rem 3rem;">Основы RAG</a>
    <a href="/" class="btn btn-outline" style="padding: 1.2rem 3rem;">На главную RAG</a>
  </div>
</div>
</article>
</main>
<script>
var cragTimeouts = [];
var cragRunning = false;
window.cragClear = function() { for(var i=0;i<cragTimeouts.length;i++) clearTimeout(cragTimeouts[i]); cragTimeouts=[]; cragRunning=false; };
window.runCragDemo = function() {
  if(cragRunning) window.cragClear();
  cragRunning = true;
  document.getElementById('cResetBtn').style.display='none';
  var c = document.getElementById('cragDemoContainer'); c.style.display='block';
  ['cStepQuery','cStepSearch','cStepEval','cStepRewrite','cStepFinal'].forEach(function(id){ document.getElementById(id).classList.remove('visible'); });
  document.getElementById('cSearchSpin').style.display='inline-block'; document.getElementById('cSearchText').style.display='block'; document.getElementById('cEvalList').innerHTML=''; document.getElementById('cConfScore').textContent='32%';
  
  var t1 = setTimeout(function(){ document.getElementById('cStepQuery').classList.add('visible'); }, 100); cragTimeouts.push(t1);
  var t2 = setTimeout(function(){ document.getElementById('cStepSearch').classList.add('visible'); }, 800); cragTimeouts.push(t2);
  
  var t3 = setTimeout(function(){
    document.getElementById('cSearchSpin').style.display='none'; document.getElementById('cSearchText').style.display='none';
    document.getElementById('cStepEval').classList.add('visible');
    var list = document.getElementById('cEvalList');
    list.innerHTML = '<div style="margin-bottom:0.8rem; padding:1rem; background:var(--bg-secondary); border-radius:var(--r-md); border-left:4px solid #ff4d4d;">📄 «Бетон М150 для отмосток и дорожек...» <span class="score-tag score-reject">Score: 0.21 | REJECT</span></div><div style="padding:1rem; background:var(--bg-secondary); border-radius:var(--r-md); border-left:4px solid #ff4d4d;">📄 «Отчет по вентиляции 2019...» <span class="score-tag score-reject">Score: 0.14 | REJECT</span></div>';
    var t4 = setTimeout(function(){ document.getElementById('cStepRewrite').classList.add('visible'); }, 1500); cragTimeouts.push(t4);
  }, 2200); cragTimeouts.push(t3);
  
  var t5 = setTimeout(function(){
    document.getElementById('cStepFinal').classList.add('visible');
    var t6 = setTimeout(function(){ document.getElementById('cResetBtn').style.display='inline-block'; cragRunning=false; }, 1000); cragTimeouts.push(t6);
  }, 4500); cragTimeouts.push(t5);
};
window.resetCragDemo = function() { window.cragClear(); document.getElementById('cragDemoContainer').style.display='none'; document.getElementById('cResetBtn').style.display='none'; };
</script>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"TechArticle","headline":"<?= htmlspecialchars($pageTitle) ?>","description":"<?= htmlspecialchars($pageDescription) ?>","image":"<?= $pageImage ?>","author":{"@type":"Organization","name":"WeAreFired"},"datePublished":"<?= $pageDate ?>","dateModified":"<?= $pageModified ?>","url":"<?= $pageUrl ?>","keywords":"<?= htmlspecialchars($pageKeywords) ?>","inLanguage":"ru"}
</script>
<?php include 'footer.php'; ?>
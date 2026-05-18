<?php
$siteId = 'rag';
$pageTitle = 'Л.Е.С. v2.0: Локальная Единая Система | Суверенный RAG-стек';
$pageDescription = 'Архитектура Л.Е.С. v2.0: FastAPI + Qdrant + Ollama. Полный отказ от тяжёлых зависимостей. Structure-Aware чанкинг, нативный CRAG и zero-cloud контур.';
$pageKeywords = 'ЛЕС v2.0, локальный RAG, FastAPI Qdrant, Ollama инженерный ИИ, CRAG валидация, суверенный стек, структура RAG';
$pageAuthor = 'WeAreFired';
$pageDate = '2026-05-24';
$pageModified = '2026-05-24';
$pageUrl = 'https://rag.ovc.me/les';
$pageImage = 'https://rag.ovc.me/img/og-les-v2.jpg';
include 'header.php';
?>
<style>
/* ─── СТИЛИ LES.PHP v2.0 (OVC DS, акцент RAG) ─── */
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
.insight-box.warn { border-left-color: var(--c-amber); } .insight-box.warn h3 { color: var(--c-amber); }
.insight-box.danger { border-left-color: var(--c-rag); } .insight-box.danger h3 { color: var(--c-rag); }
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
.outro-block { background: var(--c-rag); color: #111; padding: 4rem; border-radius: var(--r-xl); margin: 2rem 0 4rem; }
.outro-block h2 { font-family: var(--font-ui); color: #111; font-size: 1.8rem; margin-bottom: 1.5rem; }
.outro-block p { font-size: 1.05rem; opacity: 0.9; line-height: 1.75; margin-bottom: 1.5rem; max-width: 700px; }
.outro-block .btn { background: #111; color: var(--c-rag); border-color: #111; }
.outro-block .btn:hover { background: transparent; color: #111; border-color: #111; }
.outro-links { display: flex; gap: 15px; flex-wrap: wrap; margin-top: 2rem; }

/* АРХИВ v1.5 */
.legacy-archive { background: var(--bg-tertiary); border: 2px dashed var(--border); border-radius: var(--r-lg); padding: 0; margin: 3rem 0; opacity: 0.75; transition: opacity 0.3s; }
.legacy-archive:hover { opacity: 1; }
.legacy-archive summary { padding: 1.5rem 2rem; font-family: var(--font-code); font-size: 0.85rem; font-weight: 700; color: var(--text-dim); cursor: pointer; list-style: none; display: flex; align-items: center; gap: 1rem; text-transform: uppercase; letter-spacing: 0.1em; }
.legacy-archive summary::after { content: "▼"; margin-left: auto; font-size: 0.8rem; transition: transform 0.2s; }
.legacy-archive[open] summary::after { transform: rotate(180deg); }
.legacy-content { padding: 2rem; border-top: 1px solid var(--border); }
.legacy-content h3 { font-family: var(--font-ui); font-size: 1.1rem; color: var(--text-dim); margin-bottom: 1rem; }
.legacy-content p, .legacy-content li { color: var(--text-dim); font-size: 0.95rem; line-height: 1.6; }
.legacy-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1rem; margin-top: 1rem; }
.legacy-card { background: var(--bg-secondary); border: 1px solid var(--border); padding: 1.2rem; border-radius: var(--r-md); opacity: 0.8; }
.legacy-card strong { color: var(--text-muted); display: block; margin-bottom: 0.4rem; }
.legacy-card span { font-size: 0.85rem; color: var(--text-dim); }

@media (max-width: 640px) {
  .outro-block { padding: 2rem 1.5rem; }
  .tools-grid { grid-template-columns: 1fr; }
  .legacy-archive summary { padding: 1.2rem; }
}
</style>
<main class="container">
<article itemscope itemtype="https://schema.org/TechArticle">
<meta itemprop="datePublished" content="<?= $pageDate ?>">
<meta itemprop="dateModified" content="<?= $pageModified ?>">
<meta itemprop="author" content="WeAreFired">
<header class="article-hero">
  <h1 itemprop="headline">Л.Е.С. v2.0:<br><span>Локальная Единая Система</span></h1>
  <p class="hero-desc" itemprop="description">Суверенный инженерный RAG-стек без внешних зависимостей. FastAPI + Qdrant + Ollama. Zero-Cloud, lightweight-архитектура и нативный CRAG-пайплайн.</p>
</header>

<div class="nav-group">
  <span class="nav-label">Содержание паспорта v2.0</span>
  <div class="nav-row">
    <a href="#arch" class="btn btn-outline">01 / Архитектура</a>
    <a href="#pipeline" class="btn btn-teal">02 / Пайплайн</a>
    <a href="#infra" class="btn btn-punch">03 / Инфраструктура</a>
    <a href="#tests" class="btn btn-outline">04 / Испытания</a>
    <a href="#roadmap" class="btn btn-teal">05 / Roadmap</a>
    <a href="#legacy" class="btn btn-ghost">Архив v1.5</a>
  </div>
</div>

<section id="arch" class="content-block" itemprop="articleBody">
  <span class="section-num">01 / Architecture</span>
  <h2 class="section-title">Отказ от тяжёлого стека</h2>
  <p class="text-lead">В v2.0 полностью исключены RAGFlow, Elasticsearch, MySQL, MinIO, Redis и Celery. Ядро переписано на FastAPI + LlamaIndex + Qdrant. Конфиденциальные данные никогда не покидают локальный контур.</p>
  
  <div class="tools-grid">
    <div class="tool-card"><span class="tool-tag">ЯДРО</span><h3 class="tool-name">FastAPI Proxy</h3><p class="tool-desc">Единая точка входа, API Gateway, оркестрация CRAG, SSE-логи и метрики. Uvicorn в production-режиме без hot-reload.</p></div>
    <div class="tool-card c-teal"><span class="tool-tag">ВЕКТОРЫ</span><h3 class="tool-name">Qdrant</h3><p class="tool-desc">Лёгкая векторная БД. Хранение чанков и payload. Persistence через Docker volumes. UI на порту 6333.</p></div>
    <div class="tool-card c-punch"><span class="tool-tag">МОДЕЛИ</span><h3 class="tool-name">Ollama Native</h3><p class="tool-desc">qwen3:14b (чат/RAG), qwen2.5-coder:14b (код), bge-m3 (эмбеддинги). Автовыгрузка неактивных моделей, лимит RAM.</p></div>
  </div>

  <div class="insight-box">
    <h3>Метаданные и хранение</h3>
    <p class="text-main" style="margin: 0;">SQLite (`les_meta.db`, `les_metrics.db`) вместо MySQL. UUID-датасеты в `storage/datasets/`. Исходники в `RAG_Content/`. Данные переживают рестарты контейнеров благодаря проброшенным volumes.</p>
  </div>
</section>

<section id="pipeline" class="content-block">
  <span class="section-num">02 / Pipeline</span>
  <h2 class="section-title">Пайплайн обработки и модули v2.0</h2>
  <p class="text-main">Полный цикл: Файл → ConverterRouter → Structure-Aware Chunking → bge-m3 → Qdrant → Retrieval → qwen3 → CRAG → Ответ.</p>
  
  <ul class="step-list">
    <li class="step-item"><div class="step-num">📥</div><div><div class="step-title">ConverterRouter</div><div class="step-text">Lightweight-парсинг без нейросетей. `pymupdf4llm` (PDF), `mammoth` (DOCX), `extract-msg` (EML/MSG), `pandas` (XLSX/CSV).</div></div></li>
    <li class="step-item"><div class="step-num">✂️</div><div><div class="step-title">Structure-Aware Chunking</div><div class="step-text">Нарезка по заголовкам и структуре документов (MarkdownNodeParser + SentenceSplitter). Сохранение контекста ГОСТ/СП, разрывов пунктов нет.</div></div></li>
    <li class="step-item"><div class="step-num">🔍</div><div><div class="step-title">Т.О.С.К.А. (CRAG v2.0)</div><div class="step-text">Нативный Python-пайплайн в прокси. Pre-Check → Retrieval → Generation → Post-Check. Прозрачная валидация без чёрных ящиков. Фильтрация по датасетам.</div></div></li>
    <li class="step-item" style="border-color: var(--c-toc); background: var(--bg-secondary);"><div class="step-num" style="color: var(--c-toc);">🦉</div><div><div class="step-title" style="color: var(--c-toc);">С.О.В.У.Ш.К.А. (UI v2.0)</div><div class="step-text">Чат, Chart.js графики, real-time метрики, SSE-логи, фильтры. Вкладка Датасеты: маппинг Источник → Индекс, кнопка синхронизации, автообновление статусов.</div></div></li>
  </ul>

  <div class="insight-box warn">
    <h3>П.Р.О.Р.А.Б. (Метрики)</h3>
    <p class="text-main" style="margin: 0;">Фоновый async-коллектор каждые 3 сек. `psutil` + SQLite + Qdrant. CPU/RAM/latency/CRAG-score/очередь/скорость. HTTP-запросы не блокируются (`asyncio.to_thread`).</p>
  </div>
</section>

<section id="infra" class="content-block">
  <span class="section-num">03 / Infrastructure</span>
  <h2 class="section-title">Инфраструктура и ресурсы</h2>
  <p class="text-main">Архитектура оптимизирована под Mac Mini M4 / 24 GB RAM. Zero-Cloud, P2P-сеть, полная изоляция.</p>
  
  <div class="tools-grid">
    <div class="tool-card"><span class="tool-tag">ЖЕЛЕЗО</span><h3 class="tool-name">Mac Mini M4 / 24 GB</h3><p class="tool-desc">FileVault Off, автологин, авторестарт после сбоя питания, запрет сна. Ethernet приоритет №1. ZeroTier P2P.</p></div>
    <div class="tool-card c-teal"><span class="tool-tag">КОНТЕЙНЕРЫ</span><h3 class="tool-name">Docker Compose</h3><p class="tool-desc">2 контейнера: `les-qdrant` (~1.5 GB) + `les-proxy` (~0.5 GB). Hot-reload отключён. Volumes: `./data`, `./storage`, `./RAG_Content`.</p></div>
    <div class="tool-card c-punch"><span class="tool-tag">УСТОЙЧИВОСТЬ</span><h3 class="tool-name">Concurrency Control</h3><p class="tool-desc">`asyncio.Semaphore(2)` на индексацию. Защита Ollama от перегрузки. Строгая Pydantic-валидация чата. Swap = 0.</p></div>
  </div>

  <div class="insight-box">
    <h3>Топология портов и RAM-бюджет</h3>
    <p class="text-main" style="margin: 0;">
      <strong>8050</strong> → Proxy (Л.Е.С.) | <strong>6333</strong> → Qdrant | <strong>11434</strong> → Ollama (хост)<br>
      Параллельно в RAM живут только чат-модель + эмбеддинг (~10.5 GB). Контейнеры ~2 GB. Итого ~14–16 ГБ под штатной нагрузкой. Система работает без свопа и рестартов.
    </p>
  </div>
</section>

<section id="tests" class="content-block">
  <span class="section-num">04 / Tests</span>
  <h2 class="section-title">Результаты испытаний (10.05.2026)</h2>
  <p class="text-main">Программа испытаний v2.0 Core подтвердила работоспособность облегчённого стека и CRAG-пайплайна. Индексировано 807 файлов, 1316 чанков.</p>
  
  <div class="tools-grid">
    <div class="tool-card"><span class="tool-tag">ПРОКСИ</span><h3 class="tool-name">4/4 теста</h3><p class="tool-desc">Health, SSE, Metrics, No-Cache работают стабильно.</p></div>
    <div class="tool-card c-teal"><span class="tool-tag">RAG</span><h3 class="tool-name">7/7 тестов</h3><p class="tool-desc">PDF/DOCX/JSON парсинг, Delta-Sync, рекурсия, чанкинг.</p></div>
    <div class="tool-card c-punch"><span class="tool-tag">CRAG</span><h3 class="tool-name">4/4 теста</h3><p class="tool-desc">Чат, NO_DATA fallback, цитирование, фильтрация по датасетам.</p></div>
    <div class="tool-card"><span class="tool-tag">UI</span><h3 class="tool-name">4/4 теста</h3><p class="tool-desc">UI, SSE-логи, чат, темы, состояние чипов сохранено.</p></div>
  </div>

  <div class="insight-box" style="border-left-color: var(--c-rag);">
    <h3 style="color: var(--c-rag);">Итог: 22/23 пройдено (95.6%)</h3>
    <p class="text-main" style="margin: 0;">Критических ошибок нет. Система готова к опытной эксплуатации. Требуется доработка latency-тестов под пиковой нагрузкой и проверка парсинга EML/MSG на реальных письмах.</p>
  </div>
</section>

<section id="roadmap" class="content-block">
  <span class="section-num">05 / Roadmap</span>
  <h2 class="section-title">Дорожная карта v2.1 → v2.2+</h2>
  <p class="text-main">Система стабильна. Развитие идёт в сторону автоматизации синхронизации, безопасности и мультимодальности.</p>
  
  <ul class="step-list">
    <li class="step-item"><div class="step-num">v2.1</div><div><div class="step-title">Краткосрочно</div><div class="step-text">Retry-логика в прокси (graceful fallback). Folder Watcher для автосинхронизации. RBAC v2.0 (JWT, роли, маскирование .env). С.У.Х.А.Р.И.К. v2.0 (снапшоты Qdrant, инкрементальные бэкапы). CRAG Post-Check v2 (проверка цитат, детекция противоречий).</div></div></li>
    <li class="step-item"><div class="step-num">v2.2+</div><div><div class="step-title">Среднесрочно</div><div class="step-text">Deep BIM Linking (семантика → ExpressID в IFC). Сравнение версий нормативов (дифф СП/ГОСТ). Multi-project Support (изоляция проектов). Plugin Architecture (Revit, Tekla, NanoCAD). Voice Control (Whisper). Mobile Dashboard.</div></div></li>
  </ul>
</section>

<details id="legacy" class="legacy-archive">
  <summary>📦 Архив: Архитектура Л.Е.С. v1.5 (НЕАКТУАЛЬНО)</summary>
  <div class="legacy-content">
    <h3>Тяжёлый стек v1.5 (RAGFlow + ES + MySQL + MinIO + Redis + Celery)</h3>
    <p>Полностью заменён в v2.0 на FastAPI + Qdrant + SQLite. Сохранено для истории и миграции данных.</p>
    <div class="legacy-grid">
      <div class="legacy-card"><strong>Ж.А.Б.А.</strong><span>Изолированный серверный кластер, физический фундамент.</span></div>
      <div class="legacy-card"><strong>С.О.В.У.Ш.К.А.</strong><span>RAG Manager, BIM Viewer, сканирование датасетов.</span></div>
      <div class="legacy-card"><strong>Е.Ж.И.К. v2.0</strong><span>Почтовый коллектор: EML, MSG, PST, IMAP, OCR v2.1.</span></div>
      <div class="legacy-card"><strong>С.А.М.О.В.А.Р.</strong><span>RAGFlow v0.24.0, индексация нормативов и СП.</span></div>
      <div class="legacy-card"><strong>К.Р.О.Т.</strong><span>Парсинг IFC → Markdown → RAGFlow (`/api/ingest/ifc`).</span></div>
      <div class="legacy-card"><strong>Т.О.С.К.А.</strong><span>SCRAG-верификация, хардкод ключей убран (`os.getenv`).</span></div>
      <div class="legacy-card"><strong>К.О.Т.</strong><span>Интеграция со Speckle GraphQL API, ETL BIM-элементов.</span></div>
      <div class="legacy-card"><strong>В.О.Л.К.</strong><span>RBAC: 4 роли, JWT-сессии, защита endpoints.</span></div>
      <div class="legacy-card"><strong>С.У.Х.А.Р.И.К.</strong><span>Снапшоты MySQL/ES в MinIO S3 с ротацией.</span></div>
      <div class="legacy-card"><strong>П.Р.О.Р.А.Б.</strong><span>Мониторинг нагрузки, диагностика узлов, отчёты.</span></div>
    </div>
    <p style="margin-top: 1.5rem;">Пайплайн v1.5: Пользователь → С.О.В.У.Ш.К.А. → Т.О.С.К.А. → С.А.М.О.В.А.Р. (RAGFlow) → К.О.Т. → Ответ. Защита: В.О.Л.К. + Ж.А.Б.А. + С.У.Х.А.Р.И.К.</p>
  </div>
</details>

<div class="outro-block">
  <h2>Суверенный контур департамента</h2>
  <p>Л.Е.С. v2.0 — это экскаватор вместо лопаты. Fully Local / Zero-Cloud / Lightweight. Порядок в `RAG_Content/` = качество ответов CRAG.</p>
  <p>Начните с аудита и оцифровки. Разверните локальный стек. Обучите персонал задавать вопросы. Ответ за 2-5 секунд вместо трёх недель поиска — это новая реальность проектирования.</p>
  <div class="outro-links">
    <a href="/rag-simple" class="btn btn-punch" style="padding: 1.2rem 3rem;">← Основы RAG</a>
    <a href="/rag-okn" class="btn btn-outline" style="padding: 1.2rem 3rem;">RAG для ОКН</a>
    <a href="/" class="btn btn-outline" style="padding: 1.2rem 3rem;">На главную RAG</a>
  </div>
</div>
</article>
</main>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"TechArticle","headline":"<?= htmlspecialchars($pageTitle) ?>","description":"<?= htmlspecialchars($pageDescription) ?>","image":"<?= $pageImage ?>","author":{"@type":"Organization","name":"WeAreFired","url":"https://rag.ovc.me"},"publisher":{"@type":"Organization","name":"WeAreFired","logo":{"@type":"ImageObject","url":"https://rag.ovc.me/img/logo.svg"}},"datePublished":"<?= $pageDate ?>","dateModified":"<?= $pageModified ?>","url":"<?= $pageUrl ?>","keywords":"<?= htmlspecialchars($pageKeywords) ?>","inLanguage":"ru"}
</script>
<?php include 'footer.php'; ?>
<?php
$siteId = 'rag';
$pageTitle = 'RAGFlow на Windows: Гайд по выживанию | Локальный RAG-стек';
$pageDescription = 'Как развернуть RAGFlow v0.25 на Windows через Docker Desktop + WSL2. Фиксы конфигов, интеграция Ollama, Safe-RAG пайплайн и парсинг IFC. Production-Ready гайд.';
$pageKeywords = 'RAGFlow Windows, Docker Desktop WSL2, локальный RAG, Ollama интеграция, Safe-RAG, CRAG валидация, парсинг IFC, Elasticsearch 8, MySQL 8, MinIO';
$pageAuthor = 'WeAreFired';
$pageDate = '2026-04-28';
$pageModified = '2026-05-24';
$pageUrl = 'https://rag.ovc.me/ragflow';
$pageImage = 'https://rag.ovc.me/img/og-ragflow.jpg';
include 'header.php';
?>
<style>
/* ─── СТИЛИ RAGFLOW.PHP (OVC DS, акцент RAG) ─── */
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
/* КОД И ТЕРМИНАЛ */
.code-block { background: #111; color: #dde1e7; font-family: var(--font-code); font-size: 0.88rem; line-height: 1.6; padding: 1.5rem; border-radius: var(--r-md); margin: 1.5rem 0; overflow-x: auto; border: 1px solid var(--border); }
.code-block .comment { color: #6a737d; }
.code-block .key { color: var(--c-rag); }
.code-block .str { color: var(--c-toc); }
.code-inline { background: var(--bg-secondary); color: var(--c-rag); font-family: var(--font-code); font-size: 0.9em; padding: 0.2rem 0.4rem; border-radius: 4px; border: 1px solid var(--border); }
.checklist { list-style: none; display: grid; gap: 0.8rem; margin: 1.5rem 0; }
.checklist li { display: flex; align-items: flex-start; gap: 0.8rem; font-size: 1.05rem; color: var(--text-muted); line-height: 1.6; }
.checklist li::before { content: "☐"; color: var(--c-rag); font-weight: 900; font-size: 1.2rem; line-height: 1; }
@media (max-width: 640px) {
  .outro-block { padding: 2rem 1.5rem; }
  .tools-grid { grid-template-columns: 1fr; }
  .code-block { font-size: 0.8rem; padding: 1rem; }
}
</style>
<main class="container">
<article itemscope itemtype="https://schema.org/TechArticle">
<meta itemprop="datePublished" content="<?= $pageDate ?>">
<meta itemprop="dateModified" content="<?= $pageModified ?>">
<meta itemprop="author" content="WeAreFired">
<header class="article-hero">
  <h1 itemprop="headline">RAGFlow на Windows:<br><span>Гайд по выживанию</span></h1>
  <p class="hero-desc" itemprop="description">Развернуть RAGFlow на Linux — это скучно. Развернуть его на Windows через Docker Desktop — это квест на выживание, где каждый сервис норовит упасть, а конфиги игнорируются. Мы прошли этот путь, собрали все грабли и делимся инструкцией.</p>
  <div style="margin-top: 1.5rem; display: flex; gap: 1rem; flex-wrap: wrap; font-family: var(--font-code); font-size: 0.85rem; color: var(--text-muted);">
    <span>📅 29 апреля 2026</span>
    <span style="color: var(--c-toc);">✅ Production-Ready (Local)</span>
    <span>🐳 Docker Desktop + WSL2 + RAGFlow v0.25 + Ollama</span>
  </div>
</header>

<div class="nav-group">
  <span class="nav-label">Содержание руководства</span>
  <div class="nav-row">
    <a href="#intro" class="btn btn-outline">01 / Что такое RAGFlow</a>
    <a href="#arch" class="btn btn-teal">02 / Архитектура</a>
    <a href="#wsl" class="btn btn-punch">03 / WSL2 и Docker</a>
    <a href="#docker" class="btn btn-outline">04 / Конфиги и фиксы</a>
    <a href="#ollama" class="btn btn-teal">05 / Ollama</a>
    <a href="#saferag" class="btn btn-punch">06 / Safe-RAG</a>
    <a href="#bim" class="btn btn-outline">07 / BIM / IFC</a>
    <a href="#launch" class="btn btn-ghost">08 / Запуск</a>
  </div>
</div>

<section id="intro" class="content-block" itemprop="articleBody">
  <span class="section-num">01 / Intro</span>
  <h2 class="section-title">Что такое RAGFlow и зачем он нужен</h2>
  <p class="text-lead">RAGFlow — это open-source платформа оркестрации RAG, заточенная под глубокое понимание сложных документов. Она не просто «читает» текст, она видит структуру: таблицы, колонки, формулы, штампы и схемы.</p>
  <p class="text-main">В отличие от лёгких скриптов на LlamaIndex или LangChain, RAGFlow берёт на себя полный цикл: от парсинга «грязных» PDF и DOCX до гибридного поиска (BM25 + Vector) и управления датасетами через веб-интерфейс. Это тяжёлый, но невероятно точный инструмент для инженерных, юридических и научных архивов.</p>
  
  <div class="tools-grid">
    <div class="tool-card"><span class="tool-tag">ЧТО ТАЩИТ</span><h3 class="tool-name">Зависимости</h3><p class="tool-desc">Elasticsearch (векторы + полнотекст), MySQL (метаданные), MinIO (S3-хранилище файлов), Redis (кэш и очереди). Требует 16+ ГБ RAM и SSD.</p></div>
    <div class="tool-card c-teal"><span class="tool-tag">ЧТО УМЕЕТ</span><h3 class="tool-name">Возможности</h3><p class="tool-desc">Глубокий парсинг PDF/Word/Excel/PPT/изображений. Автоматический чанкинг с сохранением таблиц. Управление датасетами, ролями, API-ключами. Встроенный чат и тестирование ретривера.</p></div>
    <div class="tool-card c-punch"><span class="tool-tag">КАК РАБОТАЕТ</span><h3 class="tool-name">Пайплайн</h3><p class="tool-desc">Файл → DeepDoc Parser → Структурный чанкинг → Эмбеддинги → Индексация в ES → Гибридный поиск → LLM-генерация. Всё управляется через UI или REST API.</p></div>
  </div>

  <div class="insight-box warn">
    <h3>Почему Windows?</h3>
    <p class="text-main" style="margin: 0;">Linux-деплой тривиален. Windows + Docker Desktop + WSL2 создаёт сетевые и файловые коллизии. Контейнеры теряют конфиги, DNS ломается, пути не маунтятся. Этот гайд закрывает все известные точки отказа.</p>
  </div>
</section>

<section id="arch" class="content-block">
  <span class="section-num">02 / Architecture</span>
  <h2 class="section-title">Архитектура: Почему именно так?</h2>
  <p class="text-main">Мы отказались от облачных API в пользу Local-First архитектуры. Наш контур выглядит так:</p>
  
  <ul class="step-list">
    <li class="step-item"><div class="step-num">🧩</div><div><div class="step-title">RAGFlow (v0.25+)</div><div class="step-text">Оркестратор. Отвечает за парсинг сложных документов (PDF, IFC, Excel), управление чанками и гибридный поиск.</div></div></li>
    <li class="step-item"><div class="step-num">🔍</div><div><div class="step-title">Elasticsearch 8.11</div><div class="step-text">Векторная база данных + BM25. Гибридный поиск дает лучшую точность на технических текстах, чем чистые векторы.</div></div></li>
    <li class="step-item"><div class="step-num">🗄️</div><div><div class="step-title">MySQL 8.0</div><div class="step-text">Хранение метаданных, пользователей и связей между документами.</div></div></li>
    <li class="step-item"><div class="step-num">📦</div><div><div class="step-title">MinIO + OpenDAL</div><div class="step-text">S3-совместимое хранилище для сырых файлов и кэша парсера.</div></div></li>
    <li class="step-item"><div class="step-num">🧠</div><div><div class="step-title">Ollama (Local LLM)</div><div class="step-text">Модель <span class="code-inline">qwen3.5:9b</span> для генерации ответов и <span class="code-inline">bge-m3</span> для эмбеддингов. Работает локально, никаких токенов.</div></div></li>
  </ul>

  <div class="insight-box">
    <h3>Главный принцип</h3>
    <p class="text-main" style="margin: 0;">Никаких внешних зависимостей при работе. Все модели, библиотеки и индексы лежат на вашем SSD. Контур полностью автономен и не сливает данные в облако.</p>
  </div>
</section>

<section id="wsl" class="content-block">
  <span class="section-num">03 / Environment</span>
  <h2 class="section-title">Шаг 1: Подготовка среды (WSL2)</h2>
  <p class="text-main">Docker Desktop на Windows использует WSL2. Это удобно, но создает проблемы с сетью и файловой системой.</p>
  
  <ul class="step-list">
    <li class="step-item"><div class="step-num">1</div><div><div class="step-title">Установите Docker Desktop</div><div class="step-text">Включите WSL2 backend в настройках. Убедитесь, что интеграция с вашим дистрибутивом (Ubuntu/Debian) активна.</div></div></li>
    <li class="step-item"><div class="step-num">2</div><div><div class="step-title">Выделите ресурсы</div><div class="step-text">Минимум <strong>16 GB RAM</strong> и <strong>4 CPU</strong>. RAGFlow прожорлив, особенно при парсинге PDF и работе Elasticsearch. Настройте в Docker Desktop → Resources.</div></div></li>
    <li class="step-item"><div class="step-num">3</div><div><div class="step-title">Создайте проектную папку</div><div class="step-text">Например, <span class="code-inline">C:\Users\You\Documents\LRAGM</span>. Внутри будут лежать <span class="code-inline">docker-compose.yml</span> и папки для томов (volumes).</div></div></li>
  </ul>
</section>

<section id="docker" class="content-block">
  <span class="section-num">04 / Docker & Configs</span>
  <h2 class="section-title">Шаг 2: Docker Compose и борьба с конфигурациями</h2>
  <p class="text-main">Самая большая боль RAGFlow на Windows — это потеря конфигов при перезапуске контейнеров. Docker часто игнорирует <span class="code-inline">.env</span>, если пути указаны неправильно, или сбрасывает пароли MySQL.</p>
  
  <div class="insight-box warn">
    <h3>Фикс №1: Хардкод кредов</h3>
    <p class="text-main" style="margin: 0;">Не надейтесь на <span class="code-inline">.env</span>. В <span class="code-inline">docker-compose.yml</span> явно пропишите пароли для MySQL и Redis.</p>
  </div>
  <div class="code-block">
<span class="key">services:</span>
  <span class="key">mysql:</span>
    <span class="key">image:</span> mysql:8.0
    <span class="key">environment:</span>
      <span class="key">MYSQL_ROOT_PASSWORD:</span> <span class="str">"your_strong_password"</span>
      <span class="key">MYSQL_DATABASE:</span> <span class="str">"rag_flow"</span>
    <span class="key">volumes:</span>
      - ./mysql-data:/var/lib/mysql <span class="comment"># Важно: маунтим локальную папку!</span>
    <span class="key">command:</span> --default-authentication-plugin=mysql_native_password

  <span class="key">ragflow:</span>
    <span class="key">image:</span> infiniflow/ragflow:v0.25.0-slim
    <span class="key">depends_on:</span> [mysql, elasticsearch, redis, minio]
    <span class="key">ports:</span>
      - <span class="str">"18080:80"</span>
    <span class="key">volumes:</span>
      - ./conf:/ragflow/conf
      - ./shared:/ragflow/shared_data
    <span class="key">environment:</span>
      - <span class="key">MYSQL_HOST</span>=mysql
      - <span class="key">MYSQL_PASSWORD</span>=your_strong_password
  </div>

  <div class="insight-box warn">
    <h3>Фикс №2: DNS и имена хостов</h3>
    <p class="text-main" style="margin: 0;">RAGFlow внутри контейнера ищет Elasticsearch по имени <span class="code-inline">es01</span>. Но в стандартном compose-файле сервис может называться <span class="code-inline">elasticsearch</span>.</p>
    <p class="text-main" style="margin: 0.5rem 0 0;"><strong>Решение:</strong> Либо переименуйте сервис в <span class="code-inline">es01</span>, либо в конфиге <span class="code-inline">service_conf.yaml</span> (папка <span class="code-inline">conf</span>) укажите правильный хост:</p>
  </div>
  <div class="code-block">
<span class="key">es:</span>
  <span class="key">hosts:</span> [<span class="str">"http://elasticsearch:9200"</span>] <span class="comment"># Проверьте имя сервиса в docker-compose</span>
  </div>

  <div class="insight-box warn">
    <h3>Фикс №3: Missing Config Files</h3>
    <p class="text-main" style="margin: 0;">При первом запуске RAGFlow может упасть с ошибкой <span class="code-inline">FileNotFoundError: mapping.json</span>. Это баг образа v0.25.</p>
    <p class="text-main" style="margin: 0.5rem 0 0;"><strong>Решение:</strong> Создайте пустой файл <span class="code-inline">system_settings.json</span> в папке <span class="code-inline">conf/</span>. Скачайте актуальный <span class="code-inline">mapping.json</span> из репозитория RAGFlow и положите туда же. Убедитесь, что права доступа к папке <span class="code-inline">conf</span> позволяют контейнеру читать файлы.</p>
  </div>
</section>

<section id="ollama" class="content-block">
  <span class="section-num">05 / Local LLM</span>
  <h2 class="section-title">Шаг 3: Подключение локального LLM (Ollama)</h2>
  <p class="text-main">Чтобы не зависеть от интернета, мы используем Ollama. Установите Ollama на хост-машину (Windows) и загрузите модели:</p>
  <div class="code-block">
ollama pull qwen3.5:9b   <span class="comment"># Для чата (баланс скорости и ума)</span>
ollama pull bge-m3       <span class="comment"># Для эмбеддингов (лучший мультиязычный векторизатор)</span>
  </div>
  <p class="text-main">В настройках RAGFlow (через UI или API) укажите endpoint Ollama:</p>
  <div class="code-block">http://host.docker.internal:11434</div>
  <div class="insight-box">
    <h3>Важно про DNS</h3>
    <p class="text-main" style="margin: 0;"><span class="code-inline">host.docker.internal</span> — это специальный DNS, который позволяет контейнеру видеть службы на хост-машине Windows. Без него RAGFlow не достучится до Ollama.</p>
  </div>
</section>

<section id="saferag" class="content-block">
  <span class="section-num">06 / Safe-RAG Pipeline</span>
  <h2 class="section-title">Шаг 4: Safe-RAG Pipeline (Наша кастомная доработка)</h2>
  <p class="text-main">Стандартный парсинг RAGFlow хорош, но для технической документации (СП, ГОСТ) он часто ломает таблицы. Мы внедрили прослойку Safe-RAG / CRAG.</p>
  
  <ul class="step-list">
    <li class="step-item"><div class="step-num">1</div><div><div class="step-title">Pre-processing</div><div class="step-text">Скрипт <span class="code-inline">safe_crag_pipeline.py</span> берет PDF и конвертирует его в Markdown через <span class="code-inline">MarkItDown</span>.</div></div></li>
    <li class="step-item"><div class="step-num">2</div><div><div class="step-title">CRAG Validation</div><div class="step-text">Проверяет качество MD: есть ли ключевые слова (СП, ГОСТ, п.)? Сохранена ли структура таблиц? Достаточная ли длина текста?</div></div></li>
    <li class="step-item"><div class="step-num">3</div><div><div class="step-title">Dual Upload</div><div class="step-text">Если MD качественный → загружаем в RAGFlow как основной источник. Если MD «битый» → загружаем исходный PDF как fallback (для встроенного OCR RAGFlow).</div></div></li>
    <li class="step-item"><div class="step-num">4</div><div><div class="step-title">Metadata Injection</div><div class="step-text">В начало каждого MD-файла вшиваем JSON-блок с мета-данными (<span class="code-inline">source</span>, <span class="code-inline">crag_valid</span>, <span class="code-inline">role</span>). Это помогает ретриверу понимать, какому источнику доверять.</div></div></li>
  </ul>

  <div class="code-block">
<span class="key">def</span> validate_crag(text: str) -> bool:
    <span class="key">if</span> len(text.strip()) < 300: <span class="key">return</span> False
    <span class="key">if not</span> re.search(<span class="str">r'(СП|ГОСТ|ФЗ|п\.)'</span>, text): <span class="key">return</span> False
    <span class="key">return</span> True
  </div>
</section>

<section id="bim" class="content-block">
  <span class="section-num">07 / BIM Integration</span>
  <h2 class="section-title">Шаг 5: Интеграция с BIM (IFC)</h2>
  <p class="text-main">RAGFlow не умеет нативно читать IFC. Мы написали парсер <span class="code-inline">parse_ifc_to_rag.py</span> на базе <span class="code-inline">IfcOpenShell</span>.</p>
  
  <ul class="step-list">
    <li class="step-item"><div class="step-num">1</div><div><div class="step-title">Извлечение элементов</div><div class="step-text">Парсер считывает стены, колонны, перекрытия и их свойства (Property Sets).</div></div></li>
    <li class="step-item"><div class="step-num">2</div><div><div class="step-title">Фикс Psets</div><div class="step-text">В IFC значения лежат в <span class="code-inline">NominalValue.wrappedValue</span>, а не в <span class="code-inline">Value</span>. Не забудьте это учесть, иначе получите пустые поля.</div></div></li>
    <li class="step-item"><div class="step-num">3</div><div><div class="step-title">Экспорт и загрузка</div><div class="step-text">Данные сохраняются в CSV (для аналитики) и MD (для RAG). MD-файлы загружаются в RAGFlow. Теперь бот знает, какая огнестойкость у конкретной стены по GUID.</div></div></li>
  </ul>
</section>

<section id="launch" class="content-block">
  <span class="section-num">08 / Launch & Checklist</span>
  <h2 class="section-title">Запуск и проверка</h2>
  <p class="text-main">Запустите стек командой <span class="code-inline">docker-compose up -d</span>. Откройте <span class="code-inline">http://localhost:18080</span>. Зайдите в настройки моделей и убедитесь, что Ollama подключен. Создайте датасет, загрузите тестовый PDF (прогнанный через Safe-RAG) и задайте вопрос.</p>
  
  <div class="insight-box">
    <h3>Чек-лист успешного деплоя</h3>
    <ul class="checklist">
      <li>MySQL не падает с <span class="code-inline">Access Denied</span>.</li>
      <li>Elasticsearch доступен по имени хоста из контейнера RAGFlow.</li>
      <li>Ollama отвечает на запросы изнутри Docker-сети.</li>
      <li>Таблицы в PDF распознаются корректно (благодаря Safe-RAG).</li>
      <li>3D-вьювер (если есть) работает локально без CORS-ошибок.</li>
    </ul>
  </div>
</section>

<section id="conclusion" class="content-block">
  <span class="section-num">09 / Conclusions</span>
  <h2 class="section-title">Итоги</h2>
  <p class="text-main">Поднять RAGFlow на Windows можно, но нужно быть готовым к ручной настройке конфигов и обходу сетевых ограничений Docker.</p>
  
  <div class="tools-grid">
    <div class="tool-card"><span class="tool-tag">ВЫВОД 1</span><h3 class="tool-name">Локальность — это сила</h3><p class="tool-desc">Никаких API-ключей, полная приватность данных. Контур работает даже при отключении интернета.</p></div>
    <div class="tool-card c-teal"><span class="tool-tag">ВЫВОД 2</span><h3 class="tool-name">Без валидации RAG слеп</h3><p class="tool-desc">Всегда проверяйте качество парсинга перед индексацией. CRAG-прослойка спасает от мусорных чанков.</p></div>
    <div class="tool-card c-punch"><span class="tool-tag">ВЫВОД 3</span><h3 class="tool-name">BIM и Text — разные миры</h3><p class="tool-desc">Используйте специализированные парсеры (IfcOpenShell) для извлечения структуры, а не надейтесь на универсальные инструменты.</p></div>
  </div>

  <div class="insight-box" style="margin-top: 2rem;">
    <h3>Финал</h3>
    <p class="text-main" style="margin: 0;">Теперь у вас есть собственный, неприступный цифровой мозг. Пользуйтесь с умом.</p>
  </div>
</section>

<div class="outro-block">
  <h2>От локального стека к инженерной системе</h2>
  <p>RAGFlow — мощный фундамент, но для корпоративного контура нужна архитектура. Изучите CRAG для самопроверки ответов и Safe-RAG для защиты периметра.</p>
  <p>Если готовы к полному отказу от тяжёлых зависимостей — переходите на Л.Е.С. v2.0: FastAPI + Qdrant + Ollama. Lightweight, zero-cloud, production-ready.</p>
  <div class="outro-links">
    <a href="/crag" class="btn btn-punch" style="padding: 1.2rem 3rem;">CRAG →</a>
    <a href="/safe-rag" class="btn btn-outline" style="padding: 1.2rem 3rem;">Safe-RAG</a>
    <a href="/les" class="btn btn-outline" style="padding: 1.2rem 3rem;">Л.Е.С. v2.0</a>
    <a href="/" class="btn btn-outline" style="padding: 1.2rem 3rem;">На главную RAG</a>
  </div>
</div>
</article>
</main>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"TechArticle","headline":"<?= htmlspecialchars($pageTitle) ?>","description":"<?= htmlspecialchars($pageDescription) ?>","image":"<?= $pageImage ?>","author":{"@type":"Organization","name":"WeAreFired","url":"https://rag.ovc.me"},"publisher":{"@type":"Organization","name":"WeAreFired","logo":{"@type":"ImageObject","url":"https://rag.ovc.me/img/logo.svg"}},"datePublished":"<?= $pageDate ?>","dateModified":"<?= $pageModified ?>","url":"<?= $pageUrl ?>","keywords":"<?= htmlspecialchars($pageKeywords) ?>","inLanguage":"ru"}
</script>
<?php include 'footer.php'; ?>
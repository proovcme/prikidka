<?php
$siteId = 'rag';
$pageTitle = 'Домашний сервер: архитектура приватной инфраструктуры | WeAreFired';
$pageDescription = 'Полный обзор приватной инфраструктуры для удалённой работы, хранения данных, медиа и локального ИИ. Автономность, безопасность и полная независимость от облаков.';
$pageKeywords = 'домашний сервер, приватная инфраструктура, homelab, Docker, ZeroTier, локальный ИИ, Ollama, Jellyfin, TorrServer';
$pageAuthor = 'WeAreFired';
$pageDate = '2026-05-24';
$pageModified = '2026-05-24';
$pageUrl = 'https://rag.ovc.me/infra';
$pageImage = 'https://rag.ovc.me/img/og-infra.jpg';
include 'header.php';
?>
<style>
/* ─── СТИЛИ INFRA.PHP (OVC DS, акцент RAG) ─── */
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
  <h1 itemprop="headline">Домашний сервер:<br><span>архитектура приватной инфраструктуры</span></h1>
  <p class="hero-desc" itemprop="description">Приватная инфраструктура для удалённой работы, хранения данных, медиа и локального ИИ. Автономность, безопасность и полная независимость от облачных сервисов.</p>
</header>

<div class="nav-group">
  <span class="nav-label">Содержание руководства</span>
  <div class="nav-row">
    <a href="#hardware" class="btn btn-outline">01 / Железо</a>
    <a href="#security" class="btn btn-teal">02 / Безопасность</a>
    <a href="#network" class="btn btn-punch">03 / Сеть</a>
    <a href="#services" class="btn btn-outline">04 / Сервисы</a>
    <a href="#faq" class="btn btn-ghost">FAQ</a>
  </div>
</div>

<section id="hardware" class="content-block" itemprop="articleBody">
  <span class="section-num">01 / Hardware</span>
  <h2 class="section-title">Оборудование и роли</h2>
  <p class="text-lead">Инфраструктура строится на разделении ролей. Сервер вычисляет и хранит, клиенты управляют и потребляют контент.</p>
  
  <ul class="step-list">
    <li class="step-item"><div class="step-num">1</div><div><div class="step-title">Mac Mini — Основной сервер</div><div class="step-text">Хранит данные, запускает Docker-контейнеры, обрабатывает локальные ИИ-задачи. Работает 24/7.</div></div></li>
    <li class="step-item"><div class="step-num">2</div><div><div class="step-title">MacBook Air — Клиент / Терминал</div><div class="step-text">Управление сервером, разработка, мониторинг логов. Подключается по SSH.</div></div></li>
    <li class="step-item"><div class="step-num">3</div><div><div class="step-title">Lenovo Legion — Рабочая станция</div><div class="step-text">Доступ к проекту, синхронизация файлов, тяжёлые клиентские задачи.</div></div></li>
    <li class="step-item"><div class="step-num">4</div><div><div class="step-title">LG TV — Медиа-клиент</div><div class="step-text">Просмотр фильмов, сериалов и стриминг через Jellyfin / TorrServer.</div></div></li>
  </ul>
</section>

<section id="security" class="content-block">
  <span class="section-num">02 / Security</span>
  <h2 class="section-title">Безопасность и хранение данных</h2>
  <p class="text-main">Безопасность строится на принципе разделения системы и данных, а также на полном отказе от парольного доступа извне.</p>
  
  <div class="insight-box">
    <h3>Принцип разделения</h3>
    <p class="text-main" style="margin: 0;"><strong>Системный диск</strong> работает без шифрования. Сервер должен гарантированно включаться после отключения электричества без ручного ввода пароля.<br><br><strong>Рабочие данные</strong> хранятся в отдельном зашифрованном контейнере (AES-256). Том монтируется автоматически. Даже при физическом изъятии диска файлы останутся недоступны.</p>
  </div>

  <ul class="step-list" style="margin-top: 2rem;">
    <li class="step-item"><div class="step-num">🔑</div><div><div class="step-title">Доступ только по ключам</div><div class="step-text">Парольная авторизация по SSH полностью отключена. Вход только по криптографическим ключам.</div></div></li>
    <li class="step-item"><div class="step-num">🛡️</div><div><div class="step-title">Изолированная сеть</div><div class="step-text">Прямые порты в интернет не открыты. Вся инфраструктура находится за NAT без пробросов.</div></div></li>
  </ul>
</section>

<section id="network" class="content-block">
  <span class="section-num">03 / Network</span>
  <h2 class="section-title">Сетевая архитектура</h2>
  <p class="text-main">Устройства видят друг друга как в одной комнате, где бы они ни находились физически.</p>
  
  <div class="tools-grid">
    <div class="tool-card c-teal"><span class="tool-tag">Виртуальная сеть</span><h3 class="tool-name">ZeroTier</h3><p class="tool-desc">Создаёт защищённую виртуальную локальную сеть поверх интернета. Не требует настройки роутера, шифрует трафик.</p></div>
    <div class="tool-card"><span class="tool-tag">Терминал</span><h3 class="tool-name">SSH</h3><p class="tool-desc">Защищённый доступ к командной строке. Стандарт индустрии, лёгкий, безопасный, работает при слабом интернете.</p></div>
    <div class="tool-card c-punch"><span class="tool-tag">Графика</span><h3 class="tool-name">Parsec</h3><p class="tool-desc">Удалённый рабочий стол с низкой задержкой. Поддержка 60 FPS, оптимизирован для видео и интерфейсов.</p></div>
  </div>
</section>

<section id="services" class="content-block">
  <span class="section-num">04 / Services</span>
  <h2 class="section-title">Основные сервисы (контейнеры)</h2>
  <p class="text-main">Вся нагрузка изолирована в Docker-контейнерах. Это позволяет обновлять сервисы в один клик, не затрагивая хост-систему.</p>
  
  <div class="tools-grid">
    <div class="tool-card"><span class="tool-tag">Платформа</span><h3 class="tool-name">Docker</h3><p class="tool-desc">Запускает все сервисы в изолированных средах. Легко обновлять, не ломая систему.</p></div>
    <div class="tool-card c-teal"><span class="tool-tag">Медиа</span><h3 class="tool-name">Jellyfin</h3><p class="tool-desc">Личный Netflix. Организует коллекцию фильмов, подтягивает постеры. Открытый код, без подписок.</p></div>
    <div class="tool-card"><span class="tool-tag">Стриминг</span><h3 class="tool-name">TorrServer</h3><p class="tool-desc">Движок потокового просмотра торрентов. Экономит время и место, работает по принципу «нажал — играет».</p></div>
    <div class="tool-card c-punch"><span class="tool-tag">Локальный ИИ</span><h3 class="tool-name">Ollama</h3><p class="tool-desc">Запускает языковые модели на сервере. Данные не уходят в облако, не нужны платные API, работает оффлайн.</p></div>
  </div>
</section>

<section id="faq" class="content-block">
  <span class="section-num">05 / FAQ</span>
  <h2 class="section-title">Частые вопросы</h2>
  <details class="faq-item"><summary>Как подключиться к серверу?</summary><div class="faq-body">Управление через SSH-терминал. Полноценный рабочий стол через Parsec. Доступ к файлам через встроенные средства macOS. Все подключения строго через приватную виртуальную сеть.</div></details>
  <details class="faq-item"><summary>Как смотреть медиа и торренты?</summary><div class="faq-body">Приложение Jellyfin или браузер на ТВ/телефоне. Для быстрого стриминга торрентов без скачивания используется связка Lampa + TorrServer.</div></details>
  <details class="faq-item"><summary>Зачем нужен ZeroTier?</summary><div class="faq-body">Это виртуальный сетевой кабель через интернет. Он не требует настройки роутера, работает на любых провайдерах и шифрует весь трафик между устройствами.</div></details>
  <details class="faq-item"><summary>Почему выбран Docker?</summary><div class="faq-body">Система контейнеров даёт полную изоляцию сервисов, простоту переноса и быстрое обновление без перезагрузки сервера. Если один сервис упадёт, остальные продолжат работать.</div></details>
</section>

<div class="outro-block">
  <h2>Автономность и контроль</h2>
  <p>Инфраструктура модульная: новые сервисы добавляются как отдельные контейнеры, не влияя на работу остальных. Обновления через Docker, бэкапы через Time Machine.</p>
  <p>Технические детали (адреса, ключи, скрипты, конфигурации) хранятся во внутренней документации и не подлежат распространению.</p>
  <div class="outro-links">
    <a href="/rag-simple" class="btn btn-punch" style="padding: 1.2rem 3rem;">← Основы RAG</a>
    <a href="/rag-okn" class="btn btn-outline" style="padding: 1.2rem 3rem;">RAG для ОКН</a>
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
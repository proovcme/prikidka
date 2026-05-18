<?php
declare(strict_types=1);
/**
 * ШАБЛОН СТАТЬИ v4.0 — OVC Design System
 * PHP-логика без изменений, оформление из UI Kit
 */

// 1. Зависимости
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}
require_once __DIR__ . '/lib/frontmatter.php';
require_once __DIR__ . '/lib/views.php';

// 2. Slug
$rawSlug = trim($_GET['slug'] ?? '');
$slug    = preg_replace('/[^a-z0-9\-_]/i', '', $rawSlug);

// 3. Счётчик
if ($slug !== '') {
    incrementView($slug);
}

// 4. Файл
$filePath = ($slug !== '') ? __DIR__ . '/content/' . $slug . '.md' : '';
$article  = ($filePath !== '' && is_file($filePath)) ? parseArticle($filePath) : null;

// 5. Черновик
$isDraft = ($article !== null && !empty($article['meta']['draft']));
$is404   = ($article === null || $isDraft);

// 6. Переменные
$siteId          = 'main';
$pageTitle       = 'Статья не найдена';
$pageDescription = '';
$htmlContent     = '';
$meta            = [];

if (!$is404) {
    $meta            = $article['meta'];
    $siteId          = $meta['site'] ?? 'main';
    $pageTitle       = $meta['title'] ?? 'Без заголовка';
    $pageDescription = $meta['description'] ?? '';

    if (class_exists('\Parsedown')) {
        $parsedown   = new \Parsedown();
        $htmlContent = $parsedown->setSafeMode(true)->text($article['body']);
    } else {
        $htmlContent = '<p style="color:var(--c-ai)">Ошибка рендеринга Markdown.</p>';
    }
} else {
    http_response_code(404);
}

// 7. Хедер (подключает OVC CSS/JS)
include __DIR__ . '/header.php';

// Определяем цвет акцента секции для article-container
$sectionColors = [
    'rag' => 'theme-article-rag',
    'waf' => 'theme-article-rag', // WAF = AI = c-ai
    'ai'  => 'theme-article-rag',
    'toc' => 'theme-article-toc',
    'bim' => 'theme-article-bim',
    'fun' => 'theme-article-fun',
];
$sectionSlug   = strtolower($meta['section'] ?? '');
$articleTheme  = '';
foreach ($sectionColors as $key => $cls) {
    if (str_contains($sectionSlug, $key)) { $articleTheme = $cls; break; }
}
if (!$articleTheme) $articleTheme = 'theme-article-main';
?>

<!-- SEO -->
<?php if (!empty($pageDescription)): ?>
<meta name="description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') ?>">
<?php endif; ?>
<?php if (!empty($meta['tags'])): ?>
<meta name="keywords" content="<?= htmlspecialchars(implode(', ', $meta['tags']), ENT_QUOTES, 'UTF-8') ?>">
<?php endif; ?>
<meta property="og:title"       content="<?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:type"        content="article">

<style>
/* ── Обёртка страницы ────────────────────────────────────────── */
.article-page {
    max-width: 820px;
    margin: 0 auto;
    padding: 48px 48px 80px;
}
@media (max-width: 900px) { .article-page { padding: 32px 28px 64px; } }
@media (max-width: 600px) { .article-page { padding: 20px 16px 48px; } }

/* ── Тема-акценты для дополнительных секций ─────────────────── */
.theme-article-bim  { --article-accent: var(--c-bim); }
.theme-article-fun  { --article-accent: var(--c-fun); }
.theme-article-main { --article-accent: var(--c-main); }

/* ── Мета-строка статьи ──────────────────────────────────────── */
.art-meta-row {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 12px;
    margin-top: 16px;
}
.art-date {
    font-family: var(--font-code);
    font-size: .78rem;
    color: var(--text-dim);
}
.art-tags {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}
.art-tag {
    font-family: var(--font-code);
    font-size: .68rem;
    color: var(--article-accent, var(--c-main));
    border: 1px solid var(--article-accent, var(--c-main));
    padding: 2px 8px;
    border-radius: 4px;
    opacity: .8;
}
.art-views {
    margin-left: auto;
    font-family: var(--font-code);
    font-size: .78rem;
    color: var(--text-dim);
}

/* ── Контент из Markdown ──────────────────────────────────────── */
.article-content {
    font-size: 1rem;
    line-height: 1.8;
    color: var(--text-main);
    margin-top: 8px;
}
.article-content h2 {
    font-family: var(--font-code);
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--article-accent, var(--c-main));
    margin: 36px 0 12px;
    letter-spacing: .01em;
}
.article-content h3 {
    font-size: .97rem;
    font-weight: 700;
    color: var(--text-main);
    margin: 28px 0 10px;
}
.article-content h4 {
    font-size: .92rem;
    font-weight: 700;
    color: var(--text-muted);
    margin: 20px 0 8px;
}
.article-content p  { margin-bottom: 18px; }
.article-content ul,
.article-content ol { margin-bottom: 18px; padding-left: 22px; }
.article-content li { margin-bottom: 6px; color: var(--text-muted); font-size: .97rem; }
.article-content a  {
    color: var(--article-accent, var(--c-main));
    text-decoration: none;
    border-bottom: 1px dashed var(--border);
    transition: border-color var(--ease);
}
.article-content a:hover { border-color: var(--article-accent, var(--c-main)); }
.article-content blockquote {
    border-left: 3px solid var(--article-accent, var(--c-main));
    background: var(--bg-secondary);
    margin: 22px 0;
    padding: 16px 20px;
    border-radius: 0 var(--r-md) var(--r-md) 0;
    font-style: italic;
    color: var(--text-main);
    font-size: .97rem;
}
.article-content code {
    font-family: var(--font-code);
    background: var(--bg-tertiary);
    padding: 2px 6px;
    border-radius: 3px;
    color: var(--article-accent, var(--c-main));
    font-size: .86em;
    border: 1px solid var(--border-sub);
}
.article-content pre {
    background: var(--bg-tertiary);
    border: 1px solid var(--border);
    border-radius: var(--r-md);
    padding: 20px;
    overflow-x: auto;
    margin-bottom: 22px;
}
.article-content pre code {
    background: none;
    border: none;
    padding: 0;
    color: var(--text-muted);
    font-size: .84rem;
    line-height: 1.7;
}

/* ── Таблицы ─────────────────────────────────────────────────── */
.article-content table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 22px;
    font-family: var(--font-code);
    font-size: .84rem;
    overflow: hidden;
    border-radius: var(--r-md);
    border: 1px solid var(--border);
}
.article-content th,
.article-content td {
    padding: 11px 15px;
    border-bottom: 1px solid var(--border-sub);
    text-align: left;
    vertical-align: top;
}
.article-content th {
    background: var(--bg-tertiary);
    color: var(--text-dim);
    font-size: .7rem;
    text-transform: uppercase;
    letter-spacing: .06em;
    font-weight: 700;
    border-bottom-color: var(--border);
}
.article-content tr:last-child td { border-bottom: none; }
.article-content tbody tr:hover td { background: var(--bg-secondary); }

/* ── Навигация под статьёй ───────────────────────────────────── */
.article-foot-nav {
    margin-top: 48px;
    padding-top: 28px;
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

/* ── 404 ─────────────────────────────────────────────────────── */
.art-404 {
    text-align: center;
    padding: 60px 20px;
}
</style>

<div class="article-page">

  <?php if ($is404): ?>
  <!-- 404 ────────────────────────────────────────────────────── -->
  <div class="art-404 empty-state-404 c-ai">
    <div class="error-code">404</div>
    <div class="error-msg">
      <span class="cli-only">bash: cd: /article/<?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') ?>: </span>Статья не найдена
    </div>
    <p style="color:var(--text-dim);font-family:var(--font-code);font-size:.82rem;margin:0 0 22px">
      [ Возможно, статья скрыта или путь указан неверно ]
    </p>
    <div class="btn-group" style="justify-content:center">
      <a href="/" class="btn btn-action c-main">
        <span class="cli-only">~/ </span>На главную
      </a>
      <a href="javascript:history.back()" class="btn btn-nav c-main">
        <span class="cli-only">&lt;_ </span>Назад
      </a>
    </div>
  </div>

  <?php else: ?>
  <!-- СТАТЬЯ ─────────────────────────────────────────────────── -->
  <article class="article-container <?= htmlspecialchars($articleTheme, ENT_QUOTES, 'UTF-8') ?>">

    <header class="article-header">
      <div class="article-prompt">
        chernetchenko.pro<span class="cli-only">&gt;_</span>
        <span class="cli-only"> cd </span><?= htmlspecialchars($meta['section'] ?? 'article', ENT_QUOTES, 'UTF-8') ?>
      </div>
      <h1 class="article-title"><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></h1>

      <div class="art-meta-row">
        <?php if (!empty($meta['date'])): ?>
        <time class="art-date" datetime="<?= htmlspecialchars($meta['date'], ENT_QUOTES, 'UTF-8') ?>">
          <?= htmlspecialchars(date('d.m.Y', strtotime($meta['date'])), ENT_QUOTES, 'UTF-8') ?>
        </time>
        <?php endif; ?>

        <?php if (!empty($meta['tags'])): ?>
        <div class="art-tags">
          <?php foreach ($meta['tags'] as $tag): ?>
          <span class="art-tag">#<?= htmlspecialchars($tag, ENT_QUOTES, 'UTF-8') ?></span>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <span class="art-views">👁 <?= getViewCount($slug) ?></span>
      </div>
    </header>

    <section class="article-content">
      <?= $htmlContent ?>
    </section>

  </article>

  <!-- Навигация ──────────────────────────────────────────────── -->
  <nav class="article-foot-nav">
    <a href="javascript:history.back()" class="btn btn-nav c-main">
      <span class="cli-only">&lt;_ </span>Назад
    </a>
    <a href="/" class="btn btn-nav c-main">
      <span class="cli-only">~/ </span>На главную
    </a>
  </nav>
  <?php endif; ?>

</div><!-- /.article-page -->

<?php include __DIR__ . '/footer.php'; ?>

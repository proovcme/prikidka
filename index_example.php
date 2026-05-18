<?php
declare(strict_types=1);
/**
 * /index.php — Главная страница v9.1 OVC Design System
 * Контент: config/main_layout.json
 * Оформление: frontend/ui-kit (OVC DS v3.0)
 */

$siteId    = 'main';
$pageTitle = 'chernetchenko.pro';
include 'header.php';

// ── Загрузка конфигурации ──────────────────────────────────────
$layoutFile = __DIR__ . '/config/main_layout.json';
$layout = [];

if (file_exists($layoutFile)) {
    $content = file_get_contents($layoutFile);
    $decoded = json_decode($content, true);
    if (is_array($decoded) && !empty($decoded['meta'])) {
        $layout = $decoded;
    }
}

if (empty($layout)) {
    echo '<div style="text-align:center;padding:100px 20px;font-family:var(--font-code);color:var(--text-muted);">';
    echo '<div style="font-size:3rem;margin-bottom:16px;color:var(--c-ai)">⚙_</div>';
    echo '<p>Конфигурация главной страницы не найдена.</p>';
    echo '<a href="/admin/?tab=layout" class="btn btn-action c-main" style="margin-top:20px;display:inline-flex;">Перейти в админку →</a>';
    echo '</div>';
    include 'footer.php';
    exit;
}

// ── CMS библиотеки ─────────────────────────────────────────────
if (!function_exists('getArticles')) {
    require_once __DIR__ . '/lib/frontmatter.php';
    require_once __DIR__ . '/lib/views.php';
}

// ── Данные из JSON ─────────────────────────────────────────────
$meta     = $layout['meta']       ?? [];
$hero     = $layout['hero']       ?? [];
$about    = $layout['about']      ?? [];
$nav      = $layout['nav_chips']  ?? [];
$sections = $layout['sections']   ?? [];

if (!empty($meta['title'])) $pageTitle = $meta['title'];
?>

<!-- SEO -->
<?php if (!empty($meta['description'])): ?>
<meta name="description" content="<?= htmlspecialchars($meta['description'], ENT_QUOTES, 'UTF-8') ?>">
<?php endif; ?>
<?php if (!empty($meta['keywords'])): ?>
<meta name="keywords" content="<?= htmlspecialchars($meta['keywords'], ENT_QUOTES, 'UTF-8') ?>">
<?php endif; ?>
<?php if (!empty($meta['canonical'])): ?>
<link rel="canonical" href="<?= htmlspecialchars($meta['canonical'], ENT_QUOTES, 'UTF-8') ?>">
<?php endif; ?>
<meta property="og:title"       content="<?= htmlspecialchars($meta['title'] ?? 'chernetchenko.pro', ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:description" content="<?= htmlspecialchars($meta['description'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:type"        content="website">
<?php if (!empty($meta['canonical'])): ?>
<meta property="og:url" content="<?= htmlspecialchars($meta['canonical'], ENT_QUOTES, 'UTF-8') ?>">
<?php endif; ?>

<style>
html { scroll-behavior: smooth; }

/* ── HERO ────────────────────────────────────────────────────── */
.hero {
  padding: 56px 0 32px;
  border-bottom: 1px solid var(--border);
  margin-bottom: 0;
}
.hero__title {
  font-family: var(--font-code);
  font-size: clamp(1.8rem, 5vw, 3.2rem);
  font-weight: 700;
  line-height: 1.15;
  margin: 0 0 16px;
  color: var(--text-main);
  letter-spacing: -.02em;
}
.hero__title .accent { color: var(--c-main); }
.hero__sub {
  font-family: var(--font-code);
  font-size: 1rem;
  color: var(--c-main);
  text-transform: uppercase;
  letter-spacing: .06em;
  font-weight: 700;
  margin-bottom: 14px;
}
.hero__desc {
  font-size: 1rem;
  color: var(--text-muted);
  max-width: 680px;
  line-height: 1.75;
  margin-bottom: 0;
}

/* ── STICKY NAV CHIPS ────────────────────────────────────────── */
.nav-sticky {
  position: sticky;
  top: 52px;
  z-index: 90;
  background: var(--bg-main);
  background-image: none;
  padding: 12px 0;
  border-bottom: 1px solid var(--border);
  margin-bottom: 48px;
}
.anchor-chips { display: flex; flex-wrap: wrap; gap: 8px; }
.anchor-chips .chip {
  font-family: var(--font-code);
  font-size: .72rem;
  font-weight: 700;
  text-transform: uppercase;
  text-decoration: none;
  color: var(--text-muted);
  padding: 7px 14px;
  border: 1px solid var(--border);
  border-radius: var(--r-sm);
  transition: all var(--ease);
  letter-spacing: .04em;
  background: transparent;
}
.anchor-chips .chip::before { display: none; }
.anchor-chips .chip:hover   { border-color: var(--text-muted); color: var(--text-main); }
.anchor-chips .chip.active  { background: var(--c-main); color: #fff; border-color: var(--c-main); }

/* ── ABOUT GRID ──────────────────────────────────────────────── */
.about-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin-bottom: 56px;
}
@media (max-width: 900px) { .about-grid { grid-template-columns: 1fr; } }

.about-card {
  background: var(--bg-secondary);
  border: 1px solid var(--border);
  border-radius: var(--r-lg);
  padding: 24px;
  position: relative;
  overflow: hidden;
}
.about-card::before {
  content: "";
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 2px;
  background: var(--c-main);
}
.about-card h4 {
  font-family: var(--font-code);
  font-size: .72rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .07em;
  color: var(--c-main);
  margin: 0 0 16px;
}
.about-card ul  { list-style: none; padding: 0; margin: 0; }
.about-card li  { margin-bottom: 8px; font-size: .9rem; color: var(--text-muted); line-height: 1.5; padding-left: 14px; position: relative; }
.about-card li::before { content: ">"; position: absolute; left: 0; color: var(--c-main); font-family: var(--font-code); font-size: .78rem; }
.about-card .contact-link { color: var(--text-muted); text-decoration: none; transition: color var(--ease); }
.about-card .contact-link:hover { color: var(--c-main); }

/* ── SECTION WRAPPER ─────────────────────────────────────────── */
.section-wrap {
  margin-bottom: 64px;
  scroll-margin-top: 120px;
}
.section-head {
  font-family: var(--font-code);
  font-size: 1rem;
  font-weight: 700;
  margin: 0 0 28px;
  color: var(--text-main);
  text-transform: uppercase;
  letter-spacing: .06em;
  display: flex;
  align-items: center;
  gap: 12px;
}
.section-head::after {
  content: "";
  flex: 1;
  height: 1px;
  background: linear-gradient(to right, var(--border), transparent);
}

/* ── DEV CARDS ───────────────────────────────────────────────── */
.dev-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 16px;
}
@media (max-width: 1200px) { .dev-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 768px)  { .dev-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px)  { .dev-grid { grid-template-columns: 1fr; } }
.dev-grid .article-card { min-height: 160px; }
.dev-grid .article-card__tag {
  font-family: var(--font-code);
  font-size: .65rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .06em;
  color: rgba(255,255,255,.55);
  margin-bottom: 10px;
}

/* ── NET CARDS ───────────────────────────────────────────────── */
.net-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 16px;
}
.net-card {
  display: block;
  text-decoration: none;
  background: var(--bg-secondary);
  border: 1px solid var(--border);
  border-radius: var(--r-lg);
  padding: 24px;
  transition: transform var(--ease), border-color var(--ease), box-shadow var(--ease);
  position: relative;
  overflow: hidden;
}
.net-card::before {
  content: "";
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 2px;
  background: var(--c-main);
}
.net-card:hover { transform: translateY(-3px); border-color: var(--c-main); box-shadow: var(--sh-md); }
.net-card strong { display: block; font-family: var(--font-code); font-weight: 700; font-size: .95rem; margin-bottom: 8px; color: var(--text-main); }
.net-card span   { color: var(--text-muted); font-size: .88rem; line-height: 1.55; }

/* ── TOOL BOX ────────────────────────────────────────────────── */
.tool-box-wrap {
  background: color-mix(in srgb, var(--c-bim) 10%, var(--bg-secondary));
  border: 1px solid color-mix(in srgb, var(--c-bim) 50%, var(--border));
  border-radius: var(--r-lg);
  padding: 36px 40px;
  text-align: center;
  transition: box-shadow var(--ease);
  position: relative;
  overflow: hidden;
}
.tool-box-wrap::before {
  content: "";
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 3px;
  background: var(--c-bim);
}
.tool-box-wrap h3 { color: var(--c-bim) !important; }
.tool-box-wrap:hover { box-shadow: var(--sh-md); }
.tool-box-wrap h3    { font-family: var(--font-code); font-size: 1.3rem; font-weight: 700; margin: 0 0 8px; color: var(--text-main); }
.tool-box-wrap .version { font-family: var(--font-code); font-size: .78rem; color: var(--c-bim); font-weight: 700; display: block; margin-bottom: 16px; }
.tool-box-wrap p     { color: var(--text-muted); margin-bottom: 24px; font-size: .95rem; line-height: 1.65; }

/* ── ARTICLES GRID ───────────────────────────────────────────── */
.articles-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; }
.art-card {
  position: relative;
  display: block;
  padding: 24px;
  background: var(--bg-secondary);
  border: 1px solid var(--border);
  border-radius: var(--r-lg);
  text-decoration: none;
  color: var(--text-main);
  transition: transform var(--ease), border-color var(--ease), box-shadow var(--ease);
  overflow: hidden;
}
.art-card::before { content: ""; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: var(--c-main); }
.art-card:hover   { transform: translateY(-3px); border-color: var(--c-main); box-shadow: var(--sh-md); }
.art-meta  { font-family: var(--font-code); font-size: .7rem; color: var(--c-main); text-transform: uppercase; margin-bottom: 10px; font-weight: 700; letter-spacing: .05em; }
.art-title { font-family: var(--font-code); font-size: 1rem; font-weight: 700; margin: 0 0 12px; line-height: 1.3; color: var(--text-main); }
.art-desc  { color: var(--text-muted); font-size: .88rem; margin: 0 0 14px; line-height: 1.55; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.art-foot  { font-size: .72rem; color: var(--text-dim); display: flex; justify-content: space-between; align-items: center; font-family: var(--font-code); }
</style>

<!-- ============================================================
     MAIN CONTENT
     ============================================================ -->
<main>

  <!-- 1. HERO ─────────────────────────────────────────────────── -->
  <section class="hero">
    <?php if (!empty($hero['subtitle'])): ?>
    <div class="hero__sub"><span class="cli-only">&gt;_ </span><?= htmlspecialchars($hero['subtitle'], ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <h1 class="hero__title" itemprop="headline">
      <?= htmlspecialchars($hero['title_line1'] ?? '', ENT_QUOTES, 'UTF-8') ?><br>
      <span class="accent"><?= htmlspecialchars($hero['title_line2'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
    </h1>
    <?php if (!empty($hero['description'])): ?>
    <p class="hero__desc"><?= nl2br(htmlspecialchars($hero['description'], ENT_QUOTES, 'UTF-8')) ?></p>
    <?php endif; ?>
  </section>

  <!-- 2. STICKY NAV — сразу под hero ──────────────────────────── -->
  <?php if (!empty($nav)): ?>
  <div class="nav-sticky">
    <nav class="anchor-chips" id="page-nav">
      <?php foreach ($nav as $chip): ?>
      <a href="<?= htmlspecialchars($chip['link'] ?? '#', ENT_QUOTES, 'UTF-8') ?>" class="chip">
        <?= htmlspecialchars($chip['label'] ?? '', ENT_QUOTES, 'UTF-8') ?>
      </a>
      <?php endforeach; ?>
    </nav>
  </div>
  <?php endif; ?>

  <!-- 3. ABOUT ────────────────────────────────────────────────── -->
  <?php if (!empty($about['visible']) && !empty($about['columns'])): ?>
  <section class="about-grid" id="about">
    <?php foreach ($about['columns'] as $col): ?>
    <div class="about-card">
      <h4><?= htmlspecialchars(($col['icon'] ?? '') . ' ' . ($col['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h4>
      <ul>
        <?php if (!empty($col['items'])): ?>
          <?php foreach ($col['items'] as $item): ?>
          <li><?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?></li>
          <?php endforeach; ?>
        <?php elseif (!empty($col['items_links'])): ?>
          <?php foreach ($col['items_links'] as $link): ?>
          <li>
            <?php if (!empty($link['url'])): ?>
              <a href="<?= htmlspecialchars($link['url'], ENT_QUOTES, 'UTF-8') ?>"
                 class="contact-link"
                 <?= str_starts_with($link['url'], 'http') ? 'target="_blank" rel="noopener"' : '' ?>>
                <?= htmlspecialchars($link['text'] ?? '', ENT_QUOTES, 'UTF-8') ?>
              </a>
            <?php else: ?>
              <?= htmlspecialchars($link['text'] ?? '', ENT_QUOTES, 'UTF-8') ?>
            <?php endif; ?>
          </li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </div>
    <?php endforeach; ?>
  </section>
  <?php endif; ?>

  <!-- 4. ДИНАМИЧЕСКИЕ СЕКЦИИ ──────────────────────────────────── -->
  <?php foreach ($sections as $sec): ?>
    <?php if (empty($sec['visible'])) continue; ?>
    <?php
      $secId    = htmlspecialchars($sec['id']    ?? 'section', ENT_QUOTES, 'UTF-8');
      $secTitle = htmlspecialchars($sec['title'] ?? '',         ENT_QUOTES, 'UTF-8');
      $secType  = $sec['type'] ?? '';
    ?>
    <section class="section-wrap" id="<?= $secId ?>">
      <h2 class="section-head"><?= $secTitle ?></h2>

      <!-- dev_grid ─────────────────────────────────────────────── -->
      <?php if ($secType === 'dev_grid' && !empty($sec['cards'])): ?>
      <div class="dev-grid">
        <?php foreach ($sec['cards'] as $card):
          // Берём имя команды из поддомена URL: rag.chernetchenko.pro → rag
          $host = parse_url($card['url'] ?? '', PHP_URL_HOST) ?? '';
          $cmd  = explode('.', $host)[0] ?? 'go';
        ?>
        <a href="<?= htmlspecialchars($card['url'] ?? '#', ENT_QUOTES, 'UTF-8') ?>"
           class="article-card colored <?= htmlspecialchars($card['color_class'] ?? 'c-main', ENT_QUOTES, 'UTF-8') ?>"
           <?= (!empty($card['target']) && $card['target'] === '_blank') ? 'target="_blank" rel="noopener"' : '' ?>>
          <div class="article-card__prompt">chernetchenko.pro<span class="cli-only">&gt;_</span></div>
          <div class="article-card__tag"><?= htmlspecialchars($card['tag'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
          <div class="article-card__cmd"><span class="cli-only">cd </span><?= htmlspecialchars($cmd, ENT_QUOTES, 'UTF-8') ?></div>
          <div class="article-card__title"><?= htmlspecialchars($card['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
          <div class="article-card__desc"><?= htmlspecialchars($card['desc'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
        </a>
        <?php endforeach; ?>
      </div>

      <!-- net_grid ─────────────────────────────────────────────── -->
      <?php elseif ($secType === 'net_grid' && !empty($sec['cards'])): ?>
      <div class="net-grid">
        <?php foreach ($sec['cards'] as $card): ?>
        <a href="<?= htmlspecialchars($card['url'] ?? '#', ENT_QUOTES, 'UTF-8') ?>" class="net-card">
          <strong><?= htmlspecialchars($card['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong>
          <span><?= htmlspecialchars($card['desc'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
        </a>
        <?php endforeach; ?>
      </div>

      <!-- tool_box ─────────────────────────────────────────────── -->
      <?php elseif ($secType === 'tool_box' && !empty($sec['content'])): ?>
      <div class="tool-box-wrap">
        <h3><?= htmlspecialchars($sec['content']['headline'] ?? '', ENT_QUOTES, 'UTF-8') ?></h3>
        <?php if (!empty($sec['content']['version'])): ?>
        <span class="version"><?= htmlspecialchars($sec['content']['version'], ENT_QUOTES, 'UTF-8') ?></span>
        <?php endif; ?>
        <p><?= htmlspecialchars($sec['content']['desc'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
        <a href="<?= htmlspecialchars($sec['content']['url'] ?? '#', ENT_QUOTES, 'UTF-8') ?>"
           class="btn btn-action c-bim">
          <?= htmlspecialchars($sec['content']['btn_text'] ?? 'Открыть', ENT_QUOTES, 'UTF-8') ?>
        </a>
      </div>

      <!-- cms_loop ─────────────────────────────────────────────── -->
      <?php elseif ($secType === 'cms_loop'): ?>
        <?php
          $cmsParams     = $sec['cms_params'] ?? [];
          $siteFilter    = $cmsParams['site']          ?? 'main';
          $sectionFilter = $cmsParams['section']       ?? '';
          $includeDrafts = !empty($cmsParams['include_drafts']);
          $limit         = (int)($cmsParams['limit']   ?? 0);
          try {
              $viewCounts = function_exists('getAllViewCounts') ? getAllViewCounts() : [];
              $articles   = function_exists('getArticles')     ? getArticles($siteFilter, $sectionFilter, $includeDrafts) : [];
              if ($limit > 0) $articles = array_slice($articles, 0, $limit);
              if (!empty($articles)): ?>
              <div class="articles-grid">
                <?php foreach ($articles as $art):
                    $m       = $art['meta'];
                    $slug    = htmlspecialchars($m['slug'] ?? '', ENT_QUOTES, 'UTF-8');
                    $isStub  = !empty($m['stub']);
                    $attrs   = $isStub
                        ? (function_exists('render_stub_attrs') ? render_stub_attrs($m) : 'href="#"')
                        : 'href="/article.php?slug=' . $slug . '"';
                    $linkTag = $isStub ? 'div' : 'a';
                ?>
                <<?= $linkTag ?> class="art-card" <?= $attrs ?>>
                  <?php if (function_exists('render_badge')) echo render_badge($m); ?>
                  <div class="art-meta"><?= htmlspecialchars($m['section'] ?? 'Без раздела', ENT_QUOTES, 'UTF-8') ?></div>
                  <h3 class="art-title"><?= htmlspecialchars($m['title'] ?? 'Без заголовка', ENT_QUOTES, 'UTF-8') ?></h3>
                  <p class="art-desc"><?= htmlspecialchars($m['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                  <div class="art-foot">
                    <span>👁 <?= (int)($viewCounts[$m['slug']] ?? 0) ?></span>
                    <span><?= htmlspecialchars($m['date'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                  </div>
                </<?= $linkTag ?>>
                <?php endforeach; ?>
              </div>
              <?php else: ?>
              <div class="content-block block-info">
                <div class="content-block-header"><span class="cli-only">[INFO]</span> Статей пока нет</div>
                <p>Контент появится здесь после публикации первой статьи.</p>
              </div>
              <?php endif;
          } catch (Throwable $e) {
              echo '<div class="content-block block-error"><div class="content-block-header">[ERROR] Ошибка</div><p>'
                   . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p></div>';
          }
        ?>
      <?php endif; ?>

    </section>
  <?php endforeach; ?>

</main>

<?php include 'footer.php'; ?>

<script>
(function() {
  var nav      = document.getElementById('page-nav');
  var links    = nav ? Array.from(nav.querySelectorAll('a')) : [];
  var sections = links.map(function(l) {
    return document.querySelector(l.getAttribute('href'));
  }).filter(Boolean);
  function setActive() {
    if (!nav) return;
    var current = '';
    sections.forEach(function(sec) {
      var rect = sec.getBoundingClientRect();
      if (rect.top <= 140 && rect.bottom > 140) current = sec.id;
    });
    links.forEach(function(l) {
      l.classList.toggle('active', l.getAttribute('href') === '#' + current);
    });
  }
  window.addEventListener('scroll', setActive, { passive: true });
  setActive();
})();
</script>

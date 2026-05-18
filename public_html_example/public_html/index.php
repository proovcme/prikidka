<?php
declare(strict_types=1);
$siteId          = 'rag';
$pageTitle       = 'RAG & Базы знаний — rag.ovc.me';
$pageDescription = 'RAG-системы, работа с базами знаний, нормативная документация и ИИ для инженеров';

include __DIR__ . '/header.php';

require_once __DIR__ . '/../../public_html/lib/frontmatter.php';
require_once __DIR__ . '/../../public_html/lib/views.php';
require_once __DIR__ . '/../../public_html/lib/sites.php';

$layout   = json_decode(file_get_contents(__DIR__ . '/config/rag_layout.json'), true) ?: [];
$hero     = $layout['hero']     ?? [];
$sections = $layout['sections'] ?? [];
?>
<meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
<meta property="og:title"       content="<?= htmlspecialchars($pageTitle) ?>">
<meta property="og:url"         content="https://rag.ovc.me/">

<style>
:root { --site-accent: var(--c-rag); }

.rag-hero { padding: 4rem 0 2.5rem; border-bottom: 2px solid var(--c-rag); margin-bottom: 2.5rem; }
.rag-hero__sub { font-family: var(--font-code); font-size: .78rem; color: var(--c-rag); text-transform: uppercase; letter-spacing: .1em; font-weight: 700; margin-bottom: .8rem; }
.rag-hero h1 { font-family: var(--font-ui); font-size: clamp(1.8rem,5vw,3rem); font-weight: 900; line-height: 1.15; margin: 0 0 1rem; color: var(--text-main); letter-spacing: -.02em; }
.rag-hero h1 .accent { color: var(--c-rag); }
.rag-hero__desc { font-size: 1rem; color: var(--text-muted); max-width: 640px; line-height: 1.7; margin-bottom: 1.5rem; }
.rag-hero__chips { display: flex; flex-wrap: wrap; gap: 8px; }
.rag-chip { font-family: var(--font-code); font-size: .7rem; font-weight: 700; text-transform: uppercase; text-decoration: none; color: var(--text-muted); padding: 5px 12px; border: 1px solid var(--border); border-radius: 20px; transition: all .2s; }
.rag-chip:hover { border-color: var(--c-rag); color: var(--c-rag); }

.section-wrap { margin-bottom: 3.5rem; scroll-margin-top: 120px; }
.rag-section-head { font-family: var(--font-code); font-size: .9rem; font-weight: 700; margin: 0 0 1.2rem; color: var(--text-main); text-transform: uppercase; letter-spacing: .06em; display: flex; align-items: center; gap: 10px; }
.rag-section-head::after { content: ""; flex: 1; height: 1px; background: linear-gradient(to right, var(--c-rag), transparent); }

.rag-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 14px; }
.rag-card { position: relative; display: block; text-decoration: none; background: var(--bg-secondary); border: 1px solid var(--border); border-radius: var(--r-lg); padding: 20px; color: var(--text-main); transition: transform .2s, border-color .2s, box-shadow .2s; overflow: hidden; }
.rag-card::before { content: ""; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: var(--c-rag); }
.rag-card:hover { transform: translateY(-3px); border-color: var(--c-rag); box-shadow: 0 6px 20px rgba(194,153,34,.15); }
.rag-card__tag { font-family: var(--font-code); font-size: .65rem; font-weight: 700; text-transform: uppercase; color: var(--c-rag); margin-bottom: 8px; letter-spacing: .05em; }
.rag-card__title { font-family: var(--font-ui); font-size: 1rem; font-weight: 800; margin: 0 0 8px; line-height: 1.3; }
.rag-card__desc { font-size: .87rem; color: var(--text-muted); line-height: 1.5; margin: 0 0 10px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
.rag-card__foot { font-family: var(--font-code); font-size: .67rem; color: var(--text-dim); display: flex; justify-content: space-between; }

.rag-empty { border: 1px dashed var(--c-rag); border-radius: var(--r-lg); padding: 1.5rem; color: var(--text-muted); font-size: .88rem; }
.rag-empty strong { display: block; font-family: var(--font-code); font-size: .72rem; color: var(--c-rag); text-transform: uppercase; margin-bottom: .4rem; }

/* DEV GRID (карточки со ссылками на PHP-страницы) */
.rag-dev-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px; }
.rag-dev-card { text-decoration: none; background: var(--bg-secondary); border: 2px solid var(--text-main); border-radius: var(--r-lg); padding: 22px; display: flex; flex-direction: column; transition: transform .2s, box-shadow .2s; color: var(--text-main); }
.rag-dev-card:hover { transform: translate(-3px,-3px); box-shadow: 5px 5px 0 var(--text-main); }
.rag-dev-card.c-rag { border-color: var(--c-rag); }
.rag-dev-card.c-rag:hover { box-shadow: 5px 5px 0 var(--c-rag); }
.rag-dev-card.c-bim { border-color: var(--c-bim); }
.rag-dev-card.c-bim:hover { box-shadow: 5px 5px 0 var(--c-bim); }
.rag-dev-card.c-waf { border-color: var(--c-waf); }
.rag-dev-card.c-waf:hover { box-shadow: 5px 5px 0 var(--c-waf); }
.rag-dev-card .tag { font-family: var(--font-ui); font-size: .65rem; font-weight: 900; text-transform: uppercase; color: var(--c-rag); margin-bottom: 10px; letter-spacing: .05em; }
.rag-dev-card .tag.c-bim { color: var(--c-bim); }
.rag-dev-card .tag.c-waf { color: var(--c-waf); }
.rag-dev-card strong { display: block; font-family: var(--font-ui); font-size: 1rem; font-weight: 900; margin-bottom: 8px; line-height: 1.3; }
.rag-dev-card span   { font-size: .88rem; color: var(--text-muted); line-height: 1.5; }

@media (max-width: 640px) { .rag-grid, .rag-dev-grid { grid-template-columns: 1fr; } }
</style>

<main class="container">

  <section class="rag-hero">
    <?php if (!empty($hero['subtitle'])): ?>
    <div class="rag-hero__sub"><?= htmlspecialchars($hero['subtitle']) ?></div>
    <?php endif; ?>
    <h1><?= htmlspecialchars($hero['title_line1'] ?? 'RAG & Базы знаний:') ?><br>
        <span class="accent"><?= htmlspecialchars($hero['title_line2'] ?? 'ИИ который знает нормативы') ?></span>
    </h1>
    <?php if (!empty($hero['description'])): ?>
    <p class="rag-hero__desc"><?= htmlspecialchars($hero['description']) ?></p>
    <?php endif; ?>
    <nav class="rag-hero__chips">
      <?php foreach ($sections as $s): if (empty($s['visible'])) continue; ?>
      <a href="#<?= htmlspecialchars($s['id']) ?>" class="rag-chip"><?= htmlspecialchars($s['title']) ?></a>
      <?php endforeach; ?>
    </nav>
  </section>

  <?php foreach ($sections as $sec):
    if (empty($sec['visible'])) continue;
    $secId   = htmlspecialchars($sec['id']    ?? '');
    $secTitle= htmlspecialchars($sec['title'] ?? '');
    $secType = $sec['type'] ?? '';
  ?>
  <section class="section-wrap" id="<?= $secId ?>">
    <h2 class="rag-section-head"><?= $secTitle ?></h2>

    <?php if ($secType === 'dev_grid' || $secType === 'net_grid'):
      $cards = $sec['cards'] ?? [];
    ?>
      <?php if (!empty($cards)): ?>
      <div class="rag-dev-grid">
        <?php foreach ($cards as $card):
          $cc = $card['color_class'] ?? '';
        ?>
        <a href="<?= htmlspecialchars($card['url'] ?? '#') ?>" class="rag-dev-card <?= $cc ?>"
           <?= !empty($card['target']) && $card['target']==='_blank' ? 'target="_blank" rel="noopener"' : '' ?>>
          <div class="tag <?= $cc ?>"><?= htmlspecialchars($card['tag'] ?? '') ?></div>
          <strong><?= htmlspecialchars($card['title'] ?? '') ?></strong>
          <span><?= htmlspecialchars($card['desc'] ?? '') ?></span>
        </a>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div class="rag-empty"><strong>[<?= strtoupper($secId) ?>]</strong> Карточки не добавлены.</div>
      <?php endif;

    elseif ($secType === 'cms_loop'):
      $p     = $sec['cms_params'] ?? [];
      $arts  = getArticles($p['site'] ?? 'rag', $p['section'] ?? '', false);
      $limit = (int)($p['limit'] ?? 0);
      if ($limit > 0) $arts = array_slice($arts, 0, $limit);
      $views = function_exists('getAllViewCounts') ? getAllViewCounts() : [];
    ?>
      <?php if (!empty($arts)): ?>
      <div class="rag-grid">
        <?php foreach ($arts as $art):
          $m = $art['meta'];
          $slug = htmlspecialchars($m['slug'] ?? '');
        ?>
        <a href="/article.php?slug=<?= $slug ?>" class="rag-card">
          <div class="rag-card__tag"><?= htmlspecialchars($m['section'] ?? '') ?></div>
          <h3 class="rag-card__title"><?= htmlspecialchars($m['title'] ?? '') ?></h3>
          <p class="rag-card__desc"><?= htmlspecialchars($m['description'] ?? '') ?></p>
          <div class="rag-card__foot">
            <span>👁 <?= (int)($views[$m['slug']] ?? 0) ?></span>
            <span><?= htmlspecialchars($m['date'] ?? '') ?></span>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div class="rag-empty">
        <strong>[<?= strtoupper($secId) ?>]</strong>
        Статьи появятся здесь. Добавь через <a href="/admin/?tab=cms" style="color:var(--c-rag)">CMS</a> → сайт RAG → раздел «<?= $secTitle ?>».
      </div>
      <?php endif;
    endif; ?>

  </section>
  <?php endforeach; ?>

</main>

<?php include __DIR__ . '/footer.php'; ?>

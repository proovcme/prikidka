<?php
$siteId = 'rag';
include 'header.php';

require_once '../../public_html/lib/sites.php';
$root = get_site_root('rag');
$layoutFile = $root . '/config/rag_layout.json';
$layout = json_decode(file_get_contents($layoutFile), true) ?: [];
$hero     = $layout['hero']     ?? [];
$sections = $layout['sections']  ?? [];
?>
<style>
/* ─── RAG INDEX STYLES (OVC DS) ─── */
.rag-hero { padding: 5rem 0 2rem; border-bottom: 2px solid var(--text-main); margin-bottom: 2rem; }
.rag-hero h1 { font-family: var(--font-ui); font-size: clamp(2.2rem, 6vw, 4rem); font-weight: 900; line-height: 1.15; margin-bottom: 0.8rem; letter-spacing: -0.02em; color: var(--text-main); }
.rag-hero h1 span { color: var(--c-rag); display: block; font-size: 0.6em; margin-top: 0.3rem; font-weight: 800; letter-spacing: 0.02em; }
.rag-hero .subtitle { font-family: var(--font-ui); font-size: clamp(1.1rem, 2.5vw, 1.5rem); color: var(--text-main); margin-bottom: 1rem; font-weight: 700; }
.rag-hero .hero-desc { color: var(--text-muted); font-size: 1.1rem; max-width: 700px; line-height: 1.6; }

/* NAV CHIPS */
.rag-nav-chips { display: flex; flex-wrap: wrap; gap: 0.8rem; margin-bottom: 4rem; padding-top: 1rem; }
.rag-chip { padding: 0.6rem 1.2rem; background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 50px; font-family: var(--font-code); font-size: 0.85rem; font-weight: 700; color: var(--text-muted); text-decoration: none; transition: all 0.2s; }
.rag-chip:hover { border-color: var(--c-rag); color: var(--c-rag); transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
.rag-chip.accent { background: var(--c-rag); color: #111; border-color: var(--c-rag); }
.rag-chip.accent:hover { background: #fff; color: var(--c-rag); }

.section-title { font-family: var(--font-ui); font-size: 1.4rem; font-weight: 900; margin: 0 0 2rem; color: var(--text-main); padding-bottom: 0.8rem; border-bottom: 2px solid var(--text-main); }
.rag-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 4rem; }
.rag-card { background: var(--bg-secondary); border: 2px solid var(--border); padding: 2rem; border-radius: var(--r-lg); transition: all 0.2s; display: flex; flex-direction: column; text-decoration: none; }
.rag-card:hover { transform: translateY(-4px); box-shadow: 6px 6px 0 var(--text-main); border-color: var(--text-main); }
.rag-tag { font-family: var(--font-code); font-size: 0.7rem; font-weight: 900; margin-bottom: 0.8rem; display: inline-block; padding: 0.2rem 0.5rem; background: var(--bg-main); border-radius: var(--r-sm); border: 1px solid var(--border); }
.c-rag .rag-tag { color: var(--c-rag); border-color: var(--c-rag); }
.c-bim .rag-tag { color: var(--c-bim); border-color: var(--c-bim); }
.c-waf .rag-tag { color: var(--c-waf); border-color: var(--c-waf); }
.rag-card h3 { font-family: var(--font-ui); font-size: 1.25rem; font-weight: 900; margin-bottom: 0.6rem; color: var(--text-main); }
.rag-card p { font-size: 0.95rem; color: var(--text-muted); line-height: 1.5; margin: 0; }

/* LES BLOCK (VISUALLY SEPARATED) */
.rag-les-block { 
  background: var(--bg-main); 
  border: 2px solid var(--c-rag); 
  border-radius: var(--r-xl); 
  padding: 3.5rem; 
  margin: 4rem 0 5rem; 
  display: flex; 
  gap: 3rem; 
  align-items: center; 
  flex-wrap: wrap; 
  box-shadow: 0 15px 40px rgba(0,0,0,0.1); 
  position: relative; 
  overflow: hidden; 
}
.rag-les-block::before { content: ""; position: absolute; top: 0; left: 0; width: 8px; height: 100%; background: var(--c-rag); }
.rag-les-content { flex: 1; min-width: 300px; }
.rag-les-version { font-family: var(--font-code); font-size: 0.75rem; color: var(--c-rag); font-weight: 700; letter-spacing: 0.1em; margin-bottom: 0.5rem; }
.rag-les-headline { font-family: var(--font-ui); font-size: clamp(1.8rem, 3vw, 2.2rem); font-weight: 900; margin-bottom: 1rem; color: var(--text-main); line-height: 1.1; }
.rag-les-desc { font-size: 1.1rem; color: var(--text-muted); line-height: 1.6; margin-bottom: 2rem; max-width: 800px; }
.rag-les-btn { 
  background: var(--c-rag); 
  color: #111; 
  padding: 1.2rem 2.5rem; 
  border-radius: var(--r-md); 
  font-family: var(--font-ui); 
  font-weight: 900; 
  font-size: 1rem; 
  text-transform: uppercase; 
  text-decoration: none; 
  transition: all 0.2s; 
  display: inline-block; 
  letter-spacing: 0.05em; 
  border: 2px solid var(--c-rag);
}
.rag-les-btn:hover { background: transparent; color: var(--c-rag); transform: translateY(-3px); box-shadow: 0 8px 20px rgba(194, 153, 34, 0.2); }
.rag-les-icon { font-size: 5rem; color: var(--c-rag); opacity: 0.15; }

@media (max-width: 768px) {
  .rag-hero { padding: 3rem 0 2rem; }
  .rag-les-block { padding: 2rem; flex-direction: column; text-align: center; }
  .rag-les-block::before { width: 100%; height: 8px; }
  .rag-les-icon { display: none; }
  .rag-nav-chips { justify-content: center; }
}
</style>

<main class="container">
  <section class="rag-hero">
    <h1><?= htmlspecialchars($hero['title_line1'] ?? '') ?> <span><?= htmlspecialchars($hero['title_line2'] ?? '') ?></span></h1>
    <div class="subtitle"><?= htmlspecialchars($hero['subtitle'] ?? '') ?></div>
    <p class="hero-desc"><?= htmlspecialchars($hero['description'] ?? '') ?></p>
    
    <!-- Navigation Chips -->
    <div class="rag-nav-chips">
      <a href="#theory" class="rag-chip">Теория</a>
      <a href="#practice" class="rag-chip">Практика</a>
      <a href="#les_system" class="rag-chip accent">Система Л.Е.С.</a>
    </div>
  </section>

  <?php foreach ($sections as $sec): if (empty($sec['visible'])) continue; ?>
    
    <?php if ($sec['type'] === 'dev_grid' && !empty($sec['cards'])): ?>
      <section id="<?= $sec['id'] ?>" style="padding-bottom: 4rem; border-bottom: 1px dashed var(--border);">
        <h2 class="section-title"><?= htmlspecialchars($sec['title']) ?></h2>
        <div class="rag-grid">
          <?php foreach ($sec['cards'] as $card): ?>
            <a href="<?= htmlspecialchars($card['url']) ?>" class="rag-card <?= $card['color_class'] ?? 'c-rag' ?>">
              <span class="rag-tag"><?= htmlspecialchars($card['tag'] ?? 'INFO') ?></span>
              <h3><?= htmlspecialchars($card['title']) ?></h3>
              <p><?= htmlspecialchars($card['desc']) ?></p>
            </a>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif; ?>

    <?php if ($sec['type'] === 'tool_box' && !empty($sec['content'])): ?>
      <section id="<?= $sec['id'] ?>" class="rag-les-block">
        <div class="rag-les-content">
          <div class="rag-les-version"><?= htmlspecialchars($sec['content']['version'] ?? '') ?></div>
          <h2 class="rag-les-headline"><?= htmlspecialchars($sec['content']['headline'] ?? '') ?></h2>
          <p class="rag-les-desc"><?= htmlspecialchars($sec['content']['desc'] ?? '') ?></p>
          <a href="<?= htmlspecialchars($sec['content']['url'] ?? '#') ?>" class="rag-les-btn"><?= htmlspecialchars($sec['content']['btn_text'] ?? 'Открыть →') ?></a>
        </div>
        <div class="rag-les-icon">⚙️</div>
      </section>
    <?php endif; ?>

    <?php if ($sec['type'] === 'cms_loop'): 
      require_once '../../public_html/lib/frontmatter.php';
      $p = $sec['cms_params'] ?? [];
      $arts = getArticles($p['site'] ?? 'rag', $p['section'] ?? '', false);
      if (!empty($arts)): ?>
        <section id="<?= $sec['id'] ?>" style="padding-bottom: 4rem; border-bottom: 1px dashed var(--border);">
          <h2 class="section-title"><?= htmlspecialchars($sec['title']) ?></h2>
          <div class="rag-grid">
            <?php foreach ($arts as $art): ?>
              <a href="/article.php?slug=<?= $art['meta']['slug'] ?>" class="rag-card c-rag">
                <span class="rag-tag"><?= strtoupper($art['meta']['section'] ?? 'ART') ?></span>
                <h3><?= htmlspecialchars($art['meta']['title']) ?></h3>
                <p><?= htmlspecialchars(substr($art['meta']['description'] ?? '', 0, 90)) ?>...</p>
              </a>
            <?php endforeach; ?>
          </div>
        </section>
      <?php endif;
    endif; ?>

  <?php endforeach; ?>
</main>

<?php include 'footer.php'; ?>
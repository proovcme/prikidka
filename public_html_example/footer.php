<?php
/**
 * ФУТЕР chernetchenko.pro v7.0 — OVC Design System
 * Фаза 3 миграции: структура site-footer OVC
 */
$siteId = $siteId ?? 'main';
if (!isset($sites)) {
    $sites = [
        'main' => ['id'=>'main', 'page_title'=>'Олег Чернетченко',     'url'=>'https://ovc.me',         'color'=>'var(--c-main)', 'cmd'=>'~/'],
        'waf'  => ['id'=>'waf',  'page_title'=>'Прикладной ИИ',        'url'=>'https://ai.ovc.me',      'color'=>'var(--c-waf)',  'cmd'=>'cd ai'],
        'fun'  => ['id'=>'fun',  'page_title'=>'Лаборатория',          'url'=>'https://fun.ovc.me',     'color'=>'var(--c-fun)',  'cmd'=>'cd fun'],
        'toc'  => ['id'=>'toc',  'page_title'=>'Что мы сделали',       'url'=>'https://toc.ovc.me',     'color'=>'var(--c-toc)',  'cmd'=>'cd toc'],
        'rag'  => ['id'=>'rag',  'page_title'=>'RAG & Данные',         'url'=>'https://rag.ovc.me',     'color'=>'var(--c-rag)',  'cmd'=>'cd rag'],
    ];
}
$current = $sites[$siteId] ?? $sites['main'];
$host    = parse_url($current['url'], PHP_URL_HOST);
?>

<!-- ============================================================
     SITE FOOTER (OVC Design System)
     ============================================================ -->
<footer class="site-footer">
  <div class="site-footer__inner">

    <div class="site-footer__top">

      <!-- Логотип / бренд -->
      <div class="site-footer__logo">
        <span class="cli-only">root@</span><span><?= htmlspecialchars($host) ?></span><span class="cli-only"> ~ #</span>
      </div>

      <!-- Колонки ссылок -->
      <div class="site-footer__cols">

        <!-- Экосистема -->
        <div class="site-footer__col">
          <div class="site-footer__col-title">Подсайты</div>
          <ul>
            <li>
              <a href="<?= $sites['waf']['url'] ?>" style="color:var(--c-waf)">
                <span class="cli-only">cd </span>ai
              </a>
              <span class="link-sub">Прикладной ИИ — LLM, RAG, агенты</span>
            </li>
            <li>
              <a href="<?= $sites['rag']['url'] ?>" style="color:var(--c-rag)">
                <span class="cli-only">cd </span>rag
              </a>
              <span class="link-sub">RAG & Данные — векторные БД</span>
            </li>
            <li>
              <a href="<?= $sites['toc']['url'] ?>" style="color:var(--c-toc)">
                <span class="cli-only">cd </span>toc
              </a>
              <span class="link-sub">Что сделали — ПИР, ТОС, расчёты</span>
            </li>
            <li>
              <a href="<?= $sites['fun']['url'] ?>" style="color:var(--c-fun)">
                <span class="cli-only">cd </span>fun
              </a>
              <span class="link-sub">Лаборатория — инженерный нуар</span>
            </li>
            <li>
              <a href="<?= $sites['main']['url'] ?>" style="color:var(--c-main)">
                <span class="cli-only">~/</span>ovc.me
              </a>
              <span class="link-sub">Автор — цифровая мастерская</span>
            </li>
          </ul>
        </div>

        <!-- О проекте -->
        <div class="site-footer__col">
          <div class="site-footer__col-title">О проекте</div>
          <ul>
            <li>
              <a href="https://chernetchenko.pro/#about">
                <span class="cli-only">cd /</span>about
              </a>
              <span class="link-sub">Кто такой Олег Чернетченко</span>
            </li>
            <li>
              <a href="/networking.php">
                <span class="cli-only">cd /</span>networking
              </a>
              <span class="link-sub">Написать напрямую</span>
            </li>
            <li>
              <a href="/digest/">
                <span class="cli-only">cd /</span>digest
              </a>
              <span class="link-sub">BIM & AI дайджест</span>
            </li>
          </ul>
        </div>

        <!-- Соцсети -->
        <div class="site-footer__col">
          <div class="site-footer__col-title">Связь</div>
          <ul>
            <li>
              <a href="https://t.me/chernetchenko" style="color:var(--c-tg)"
                 target="_blank" rel="noopener">
                <span class="cli-only">ping @</span>chernetchenko
              </a>
              <span class="link-sub">Личный Telegram</span>
            </li>
            <li>
              <a href="https://t.me/wearefired" style="color:var(--c-tg)"
                 target="_blank" rel="noopener">
                <span class="cli-only">ping @</span>wearefired
              </a>
              <span class="link-sub">Канал — мы пилим сук</span>
            </li>
            <li>
              <a href="mailto:oleg@chernetchenko.ru">
                <span class="cli-only">mail </span>-s oleg@
              </a>
              <span class="link-sub">Написать на почту</span>
            </li>
          </ul>
        </div>

      </div><!-- /.site-footer__cols -->
    </div><!-- /.site-footer__top -->

    <!-- Нижняя строка -->
    <div class="site-footer__bottom">
      <div class="site-footer__copy">
        <?= date('Y') ?> © <?= htmlspecialchars($host) ?> — Все права защищены.
      </div>
      <div class="site-footer__status">
        [Process completed] All systems operational.
      </div>
    </div>

  </div><!-- /.site-footer__inner -->
</footer>
</body>
</html>

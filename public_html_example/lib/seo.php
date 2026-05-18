<?php
/**
 * SEO-хелпер для генерации мета-тегов
 */
function renderSeoMeta(array $p): void {
    $t = fn($k, $d = '') => htmlspecialchars($p[$k] ?? $d, ENT_QUOTES, 'UTF-8');
    echo "<meta name=\"description\" content=\"{$t('description')}\">\n";
    echo "<meta name=\"robots\" content=\"index,follow\">\n";
    if ($p['canonical'] ?? '') echo "<link rel=\"canonical\" href=\"{$t('canonical')}\">\n";
    foreach (['og:title' => 'title', 'og:description' => 'description', 'og:url' => 'canonical', 'og:type' => 'type', 'og:image' => 'image'] as $prop => $key) {
        echo "<meta property=\"$prop\" content=\"{$t($key, 'website')}\">\n";
    }
    echo "<meta name=\"twitter:card\" content=\"summary_large_image\">\n";
    echo "<meta name=\"twitter:title\" content=\"{$t('title')}\">\n";
    echo "<meta name=\"twitter:description\" content=\"{$t('description')}\">\n";
}
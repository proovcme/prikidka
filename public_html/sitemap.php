<?php
header('Content-Type: application/xml; charset=utf-8');
$pages = [
    ['loc' => 'https://toc.chernetchenko.pro/',        'priority' => '1.0',  'changefreq' => 'weekly'],
    ['loc' => 'https://toc.chernetchenko.pro/calc',    'priority' => '0.95', 'changefreq' => 'weekly'],
    ['loc' => 'https://toc.chernetchenko.pro/toc',     'priority' => '0.85', 'changefreq' => 'monthly'],
    ['loc' => 'https://toc.chernetchenko.pro/why',     'priority' => '0.80', 'changefreq' => 'monthly'],
    ['loc' => 'https://toc.chernetchenko.pro/iona',    'priority' => '0.75', 'changefreq' => 'monthly'],
    ['loc' => 'https://toc.chernetchenko.pro/dbr',     'priority' => '0.65', 'changefreq' => 'monthly'],
    ['loc' => 'https://toc.chernetchenko.pro/poreshal','priority' => '0.40', 'changefreq' => 'monthly'],
];
$lastmod = date('Y-m-d');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<?php foreach ($pages as $p): ?>
  <url>
    <loc><?= htmlspecialchars($p['loc']) ?></loc>
    <lastmod><?= $lastmod ?></lastmod>
    <changefreq><?= $p['changefreq'] ?></changefreq>
    <priority><?= $p['priority'] ?></priority>
  </url>
<?php endforeach; ?>
</urlset>

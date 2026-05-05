<?php
/**
 * ХЕДЕР toc.chernetchenko.pro v6.1
 * SEO: meta description, OG, canonical, JSON-LD, breadcrumbs
 */

// Значения по умолчанию — переопределяются в каждой странице
$pageTitle       = $pageTitle       ?? 'ПИР-калькулятор и ТОС | toc.chernetchenko.pro';
$pageDescription = $pageDescription ?? 'Бесплатный калькулятор стоимости ПИР на основе Теории Ограничений Голдратта. Ресурсный график, матрица рисков, ТОС-буфер, CCPM для проектных бюро.';
$pageKeywords    = $pageKeywords    ?? 'ПИР калькулятор, стоимость проектных работ, теория ограничений, ТОС, CCPM, управление проектами, проектное бюро, ресурсный график, буфер рисков';
$canonicalUrl    = $canonicalUrl    ?? 'https://toc.chernetchenko.pro/';
$ogImage         = $ogImage         ?? 'https://toc.chernetchenko.pro/og-image.png';
$ogType          = $ogType          ?? 'website';
$breadcrumbs     = $breadcrumbs     ?? [];  // массив [['name'=>'...','url'=>'...']]
$jsonLd          = $jsonLd          ?? [];  // массив JSON-LD объектов

// Базовый JSON-LD — WebSite всегда
$baseJsonLd = [
    '@context' => 'https://schema.org',
    '@type'    => 'WebSite',
    'name'     => 'toc.chernetchenko.pro',
    'url'      => 'https://toc.chernetchenko.pro',
    'description' => 'Инструменты планирования ПИР на базе Теории Ограничений Систем',
    'author'   => [
        '@type' => 'Person',
        'name'  => 'Олег Чернетченко',
        'url'   => 'https://chernetchenko.pro',
        'sameAs'=> ['https://t.me/chernetchenko']
    ]
];

// BreadcrumbList если переданы хлебные крошки
$breadcrumbJsonLd = null;
if (!empty($breadcrumbs)) {
    $items = [];
    foreach ($breadcrumbs as $i => $crumb) {
        $items[] = [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'name'     => $crumb['name'],
            'item'     => $crumb['url']
        ];
    }
    $breadcrumbJsonLd = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $items
    ];
}
?>
<!DOCTYPE html>
<html lang="ru" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Основные SEO теги -->
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta name="keywords"    content="<?= htmlspecialchars($pageKeywords) ?>">
    <meta name="author"      content="Олег Чернетченко">
    <meta name="robots"      content="index, follow, max-snippet:-1, max-image-preview:large">
    <link rel="canonical"    href="<?= htmlspecialchars($canonicalUrl) ?>">

    <!-- Open Graph -->
    <meta property="og:title"       content="<?= htmlspecialchars($pageTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta property="og:type"        content="<?= htmlspecialchars($ogType) ?>">
    <meta property="og:url"         content="<?= htmlspecialchars($canonicalUrl) ?>">
    <meta property="og:image"       content="<?= htmlspecialchars($ogImage) ?>">
    <meta property="og:image:width"  content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale"      content="ru_RU">
    <meta property="og:site_name"   content="toc.chernetchenko.pro">

    <!-- Twitter Card -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="<?= htmlspecialchars($pageTitle) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta name="twitter:image"       content="<?= htmlspecialchars($ogImage) ?>">

    <!-- Favicon -->
    <link rel="icon"             type="image/svg+xml" href="https://chernetchenko.pro/favicon.svg">
    <link rel="apple-touch-icon" href="https://chernetchenko.pro/favicon.svg">

    <!-- Preconnect для шрифтов -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Golos+Text:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700;800&display=swap" rel="stylesheet">

    <!-- Стили -->
    <link rel="stylesheet" href="/style.css">
    <?php if (!empty($needsGantt)): ?>
    <link rel="stylesheet" href="/frappe-gantt.css">
    <?php endif; ?>

    <!-- JSON-LD: базовый WebSite -->
    <script type="application/ld+json"><?= json_encode($baseJsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>

    <?php if ($breadcrumbJsonLd): ?>
    <!-- JSON-LD: BreadcrumbList -->
    <script type="application/ld+json"><?= json_encode($breadcrumbJsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
    <?php endif; ?>

    <?php foreach ($jsonLd as $ld): ?>
    <!-- JSON-LD: <?= htmlspecialchars($ld['@type'] ?? 'Schema') ?> -->
    <script type="application/ld+json"><?= json_encode($ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
    <?php endforeach; ?>

    <!-- Yandex.Metrika -->
    <script type="text/javascript">
        (function(m,e,t,r,i,k,a){
            m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();
            for(var j=0;j<document.scripts.length;j++){if(document.scripts[j].src===r){return;}}
            k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
        })(window,document,'script','https://mc.yandex.ru/metrika/tag.js?id=108508539','ym');
        ym(108508539,'init',{ssr:true,webvisor:true,clickmap:true,ecommerce:"dataLayer",referrer:document.referrer,url:location.href,accurateTrackBounce:true,trackLinks:true});
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/108508539" style="position:absolute;left:-9999px;" alt=""/></div></noscript>
    <!-- /Yandex.Metrika -->

    <style>
    .eco-bar {
        background: #1a1612;
        padding: 5px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }
    .eco-bar a { color: rgba(255,255,255,0.5); text-decoration: none; transition: color 0.2s; margin: 0 6px; }
    .eco-bar a:hover { color: #fff; }
    .eco-bar a.eco-active { color: #b8860b; }
    .eco-bar-left { display: flex; align-items: center; gap: 4px; }
    .eco-bar-left a { color: rgba(255,255,255,0.35); font-size: 9px; }
    .eco-bar-right { display: flex; align-items: center; gap: 4px; }
    .eco-tg { color: #229ED9 !important; border: 1px solid rgba(34,158,217,0.4); padding: 2px 8px; border-radius: 10px; }
    .eco-tg:hover { background: #229ED9; color: #fff !important; }
    </style>
</head>
<body>

    <!-- Экосистемная полоска -->
    <div class="eco-bar" aria-label="Навигация по сайтам экосистемы">
        <div class="eco-bar-left">
            <a href="https://chernetchenko.pro">chernetchenko.pro</a>
            <span style="color:rgba(255,255,255,0.2);" aria-hidden="true">/</span>
            <a href="https://waf.chernetchenko.pro">Прикладной ИИ</a>
            <span style="color:rgba(255,255,255,0.2);" aria-hidden="true">/</span>
            <a href="https://fun.chernetchenko.pro">Лаборатория</a>
            <span style="color:rgba(255,255,255,0.2);" aria-hidden="true">/</span>
            <a href="https://toc.chernetchenko.pro" class="eco-active" aria-current="page">Что сделали</a>
        </div>
        <div class="eco-bar-right">
            <a href="https://t.me/chernetchenko" class="eco-tg" target="_blank" rel="noopener" aria-label="Telegram личный">TG личный</a>
            <a href="https://t.me/waf_chernetchenko"    class="eco-tg" target="_blank" rel="noopener" aria-label="Telegram канал WAF">TG канал</a>
        </div>
    </div>

    <!-- Навигация -->
    <?php $cur = basename($_SERVER['PHP_SELF']); ?>
    <nav class="navbar" aria-label="Основная навигация">
        <a href="/" class="navbar-brand" aria-label="Главная toc.chernetchenko.pro">TOC.CHERNETCHENKO.PRO</a>
        <div style="display:flex;align-items:center;flex-wrap:wrap;gap:5px;">
            <a href="/"       class="<?= ($cur=='index.php'||$cur=='')?'active':'' ?>" <?= ($cur=='index.php'||$cur=='')?'aria-current="page"':'' ?>>Манифест</a>
            <a href="/toc"    class="<?= ($cur=='toc.php')?'active':'' ?>"    <?= ($cur=='toc.php')?'aria-current="page"':'' ?>>О ТОС</a>
            <a href="/iona"   class="<?= ($cur=='iona.php')?'active':'' ?>"   <?= ($cur=='iona.php')?'aria-current="page"':'' ?>>Голдратт</a>
            <a href="/why"    class="<?= ($cur=='why.php')?'active':'' ?>"    <?= ($cur=='why.php')?'aria-current="page"':'' ?>>Зачем это?</a>
            <a href="/dbr"    class="<?= ($cur=='dbr.php')?'active':'' ?>"    <?= ($cur=='dbr.php')?'aria-current="page"':'' ?>>Анимации</a>
            <a href="/calc"   class="nav-cta <?= ($cur=='calc.php')?'active':'' ?>" <?= ($cur=='calc.php')?'aria-current="page"':'' ?>>ПИР Калькулятор</a>
            <a href="/poreshal" style="color:var(--red);border:1px dashed var(--red);padding:4px 8px;border-radius:3px;font-size:11px;margin-left:10px;" <?= ($cur=='poreshal.php')?'aria-current="page"':'' ?>>☠️ Порешать</a>
        </div>
    </nav>

<?php
declare(strict_types=1);
/**
 * Парсер YAML front matter из Markdown файлов.
 * Кэширует результаты в static $cache.
 *
 * Поддерживаемые поля:
 * title, slug, site, section, date, tags, draft, description,
 * badge, stub, badge_color
 */

function parseArticle(string $filePath): ?array {
    static $cache = [];
    
    if (isset($cache[$filePath])) return $cache[$filePath];
    if (!is_file($filePath)) return null;
    
    $content = file_get_contents($filePath);
    if ($content === false) return null;
    
    // Регулярка для front matter: --- ... --- ...
    if (!preg_match('/^---\s*\n(.*?)\n---\s*\n(.*)$/s', $content, $m)) {
        return ['meta' => [], 'body' => $content];
    }
    
    $meta = [];
    foreach (explode("\n", $m[1]) as $line) {
        $line = trim($line);
        if (!$line || !str_contains($line, ':')) continue;
        [$key, $value] = explode(':', $line, 2);
        $key = trim(strtolower($key));
        $value = trim($value);
        
        // Массивы [tag1, tag2]
        if (preg_match('/^\[(.*)\]$/', $value, $arr)) {
            $meta[$key] = array_map(fn($i) => trim(trim($i), '"\''), explode(',', $arr[1]));
        } elseif (strtolower($value) === 'true') {
            $meta[$key] = true;
        } elseif (strtolower($value) === 'false') {
            $meta[$key] = false;
        } else {
            $meta[$key] = trim($value, '"\'');
        }
    }
    
    // Дефолтные значения для новых полей
    $meta['badge']       = $meta['badge'] ?? '';
    $meta['badge_color'] = $meta['badge_color'] ?? '';
    $meta['stub']        = $meta['stub'] ?? false;
    
    $result = ['meta' => $meta, 'body' => $m[2]];
    $cache[$filePath] = $result;
    
    return $result;
}

function getArticles(string $site = 'main', string $section = '', bool $includeDrafts = false): array {
    static $listCache = [];
    $cacheKey = $site . '|' . $section . '|' . ($includeDrafts ? '1' : '0');
    if (isset($listCache[$cacheKey])) return $listCache[$cacheKey];

    $paths = defined('CONTENT_PATHS') ? json_decode(CONTENT_PATHS, true) : [];
    $basePath = $paths[$site] ?? (__DIR__ . '/../content');
    if (!is_dir($basePath)) return [];
    
    $files = glob($basePath . '/*.md') ?: [];
    $articles = [];
    
    foreach ($files as $f) {
        $p = parseArticle($f);
        if (!$p || ($p['meta']['draft'] && !$includeDrafts)) continue;
        if ($site && ($p['meta']['site'] ?? '') !== $site) continue;
        if ($section && ($p['meta']['section'] ?? '') !== $section) continue;
        
        $articles[] = $p;
    }
    
    // Сортировка по дате (новые сверху)
    usort($articles, fn($a, $b) => strtotime($b['meta']['date'] ?? '1970-01-01') - strtotime($a['meta']['date'] ?? '1970-01-01'));
    
    $listCache[$cacheKey] = $articles;
    return $articles;
}
<?php
declare(strict_types=1);
/**
 * lib/sites.php
 * Динамическое получение списка сайтов (Engine Mode)
 * Сканирует директории для поиска сайтов экосистемы
 */

function get_dynamic_sites(): array {
    $sites = ['main']; // Основной сайт по умолчанию
    
    // Определяем базовую директорию для поиска других сайтов
    $currentDir = __DIR__; // /.../public_html/lib
    $publicHtmlDir = dirname($currentDir); // /.../public_html
    $projectDir = dirname($publicHtmlDir); // /.../SITE_F (или другой проект)
    $projectsBase = dirname($projectDir); // /.../Code (папка с проектами)
    
    if (is_dir($projectsBase)) {
        $dirs = scandir($projectsBase);
        foreach ($dirs as $dir) {
            if ($dir === '.' || $dir === '..') continue;
            $fullPath = $projectsBase . '/' . $dir;
            // Ищем директории, соответствующие паттерну *_chernetchenko_pro
            if (is_dir($fullPath) && preg_match('/^([a-z0-9_]+)_chernetchenko_pro$/', $dir, $matches)) {
                $siteId = $matches[1];
                if (!in_array($siteId, $sites)) {
                    $sites[] = $siteId;
                }
            }
        }
    }
    
    // Сортируем: main первый, остальные по алфавиту
    usort($sites, function($a, $b) {
        if ($a === 'main') return -1;
        if ($b === 'main') return 1;
        return strcmp($a, $b);
    });
    
    return $sites;
}
?>
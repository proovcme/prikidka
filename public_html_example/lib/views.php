<?php
declare(strict_types=1);
/**
 * /lib/views.php
 * 
 * 1. Счётчик просмотров (flat-file, JSON, flock-safe).
 * 2. Стили и логика для Бейджей (Badges) и Заглушек (Stubs).
 *    Исправлена ошибка кавычек в JS.
 */

// ==========================================
// 1. СЧЁТЧИК ПРОСМОТРОВ
// ==========================================

function getViewFile(): string {
    return __DIR__ . '/../content/_views.json';
}

function getAllViewCounts(): array {
    $file = getViewFile();
    if (!is_file($file)) return [];
    $json = file_get_contents($file);
    return $json ? json_decode($json, true) : [];
}

function getViewCount(string $slug): int {
    $counts = getAllViewCounts();
    return (int)($counts[$slug] ?? 0);
}

function incrementView(string $slug): void {
    $file = getViewFile();
    $counts = getAllViewCounts();
    $counts[$slug] = ($counts[$slug] ?? 0) + 1;

    $fp = @fopen($file, 'c');
    if ($fp) {
        if (flock($fp, LOCK_EX)) {
            ftruncate($fp, 0);
            fwrite($fp, json_encode($counts));
            fflush($fp);
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }
}

// ==========================================
// 2. СТИЛИ ДЛЯ БЕЙДЖЕЙ И СТАБОВ
// ==========================================

/**
 * Возвращает блок <style> и <script> для работы бейджей и стабов.
 * Вызывать в <head> шаблона: echo get_badge_styles();
 */
function get_badge_styles(): string {
    return '
<style>
    /* Бейджи (метки) */
    .card-badge {
        position: absolute; top: -8px; right: 12px;
        padding: 3px 9px; border-radius: 20px;
        font-size: .65rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: .06em;
        z-index: 10; white-space: nowrap;
        box-shadow: 0 2px 4px rgba(0,0,0,0.15);
        pointer-events: none;
    }
    .badge-new    { background: var(--accent, #1a4fa0); color: #fff; }
    .badge-beta   { background: #6a1b9a; color: #fff; }
    .badge-wip    { background: #f59e0b; color: #fff; }
    .badge-soon   { background: #64748b; color: #fff; }
    .badge-hot    { background: #ef4444; color: #fff; }

    /* Заглушки (Stubs) */
    .card-stub {
        opacity: .65; cursor: not-allowed; position: relative;
        filter: grayscale(0.3);
    }
    .card-stub:hover { transform: none !important; box-shadow: none !important; }

    /* Тост-уведомление */
    #go-toast {
        position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%);
        background: var(--ink, #1a1612); color: #fff;
        padding: 12px 24px; border-radius: 8px;
        font-size: .85rem; font-weight: 500;
        z-index: 9999; opacity: 0;
        transition: opacity .3s; pointer-events: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    #go-toast.show { opacity: 1; }
</style>

<script>
document.addEventListener("click", function(e) {
    const stub = e.target.closest(".card-stub");
    if (!stub) return;
    
    if (stub.tagName === "A" || stub.querySelector("a")) {
        e.preventDefault();
    }
    
    const msg = stub.dataset.msg || "Раздел в разработке. Заходи позже!";
    let toast = document.getElementById("go-toast");
    
    if (!toast) {
        toast = document.createElement("div");
        toast.id = "go-toast";
        document.body.appendChild(toast);
    }
    
    toast.textContent = msg;
    toast.classList.add("show");
    clearTimeout(window.toastTimer);
    window.toastTimer = setTimeout(() => toast.classList.remove("show"), 2500);
});
</script>';
}

// ==========================================
// 3. ХЕЛПЕРЫ ДЛЯ РЕНДЕРА
// ==========================================

function render_badge(array $meta): string {
    $badge = $meta['badge'] ?? '';
    if (!$badge) return '';

    $labels = [
        'new'  => '🆕 Новое',
        'beta' => '🧪 Beta',
        'wip'  => '🔧 В работе',
        'soon' => '⏳ Скоро',
        'hot'  => '🔥 Горячее',
    ];

    $label = $labels[$badge] ?? htmlspecialchars($badge, ENT_QUOTES, 'UTF-8');
    $class = 'card-badge badge-' . htmlspecialchars($badge, ENT_QUOTES, 'UTF-8');

    return '<span class="' . $class . '">' . $label . '</span>';
}

function render_stub_attrs(array $meta): string {
    if (empty($meta['stub'])) return '';
    
    $msg = $meta['stub_msg'] ?? 'Раздел в разработке';
    
    return 'class="card-stub" href="#" data-msg="' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '"';
}
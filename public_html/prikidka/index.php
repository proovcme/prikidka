<?php
/**
 * ПРИКИДКА — Модуль предварительных расчётов
 * Интегрирован в каркас toc.chernetchenko.pro
 */

$pageTitle       = 'Прикидка — Предварительные расчёты | toc.chernetchenko.pro';
$pageDescription = 'Модуль предварительных расчётов: нагрузки, стоимость, электричество, тепло, сроки. Быстрая оценка проектных параметров.';
$pageKeywords    = 'прикидка, предварительный расчёт, нагрузки, стоимость ПИР, электричество, теплотехника, сроки проектирования';
$canonicalUrl    = 'https://toc.chernetchenko.pro/prikidka/';
$ogImage         = 'https://toc.chernetchenko.pro/og-image.png';
$ogType          = 'website';
$breadcrumbs     = [
    ['name' => 'Главная', 'url' => 'https://toc.chernetchenko.pro/'],
    ['name' => 'Прикидка', 'url' => 'https://toc.chernetchenko.pro/prikidka/']
];
$jsonLd          = [];

include '../header.php';
?>

<!-- Стили модуля Прикидка -->
<link rel="stylesheet" href="assets/css/prikidka.css">

<div class="prikidka-layout" style="display:flex;max-width:1200px;margin:0 auto;padding:20px;gap:20px;">

    <!-- Боковая навигация -->
    <aside id="prikidka-sidebar" style="width:220px;flex-shrink:0;">
        <nav aria-label="Навигация по разделам Прикидки">
            <h3 style="font-size:14px;text-transform:uppercase;letter-spacing:0.05em;color:var(--muted);margin-bottom:15px;">Разделы</h3>
            <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:5px;">
                <li>
                    <a href="#loads" class="prikidka-nav-link" data-section="loads"
                       style="display:block;padding:8px 12px;border-radius:4px;text-decoration:none;color:var(--text);font-size:14px;transition:background 0.2s;">
                        ⚡ Нагрузки
                    </a>
                </li>
                <li>
                    <a href="#cost" class="prikidka-nav-link" data-section="cost"
                       style="display:block;padding:8px 12px;border-radius:4px;text-decoration:none;color:var(--text);font-size:14px;transition:background 0.2s;">
                        💰 Стоимость
                    </a>
                </li>
                <li>
                    <a href="#electric" class="prikidka-nav-link" data-section="electric"
                       style="display:block;padding:8px 12px;border-radius:4px;text-decoration:none;color:var(--text);font-size:14px;transition:background 0.2s;">
                        🔌 Электричество
                    </a>
                </li>
                <li>
                    <a href="#heat" class="prikidka-nav-link" data-section="heat"
                       style="display:block;padding:8px 12px;border-radius:4px;text-decoration:none;color:var(--text);font-size:14px;transition:background 0.2s;">
                        🔥 Тепло
                    </a>
                </li>
                <li>
                    <a href="#timeline" class="prikidka-nav-link" data-section="timeline"
                       style="display:block;padding:8px 12px;border-radius:4px;text-decoration:none;color:var(--text);font-size:14px;transition:background 0.2s;">
                        📅 Сроки
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Основной контент -->
    <main id="prikidka-content" style="flex:1;min-width:0;">
        <div style="margin-bottom:20px;">
            <h1 style="font-size:24px;margin:0 0 5px 0;">Прикидка</h1>
            <p style="color:var(--muted);font-size:14px;margin:0;">Предварительные расчёты проектных параметров</p>
        </div>

        <!-- Контейнер для калькуляторов -->
        <div id="prikidka-calculator-container">
            <div class="prikidka-placeholder" style="padding:40px;text-align:center;border:2px dashed var(--border);border-radius:8px;color:var(--muted);">
                <p style="font-size:16px;margin:0;">Выберите раздел в боковом меню</p>
                <p style="font-size:13px;margin:5px 0 0 0;">Калькуляторы загружаются динамически</p>
            </div>
        </div>
    </main>

</div>

<!-- Скрипт модуля Прикидка -->
<script src="assets/js/app.js"></script>

<?php include '../footer.php'; ?>
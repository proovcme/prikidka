<?php
$pageTitle       = 'Элияху Голдратт | Биография, книги и Иона';
$pageDescription = 'Элияху Голдратт (1947–2011) — физик, создатель ТОС. Биография, цитаты, полная библиография: «Цель», «Критическая цепь», «Цель-2», «Правила Голдратта». Персонаж Ионы и его метод.';
$pageKeywords    = 'голдратт книги, книга цель голдратт, критическая цепь книга, иона тос, физик в менеджменте, бизнес-романы управление';
$canonicalUrl    = 'https://toc.chernetchenko.pro/iona';
$breadcrumbs     = [
    ['name' => 'Главная',   'url' => 'https://toc.chernetchenko.pro/'],
    ['name' => 'Голдратт', 'url' => 'https://toc.chernetchenko.pro/iona'],
];
$jsonLd = [[
    '@context' => 'https://schema.org',
    '@type'    => 'Person',
    'name'     => 'Элияху Моше Голдратт',
    'birthDate'  => '1947-03-31',
    'deathDate'  => '2011-06-11',
    'nationality' => 'Israeli',
    'jobTitle'   => 'Физик, бизнес-консультант, автор',
    'description'=> 'Создатель Теории Ограничений Систем, автор книги "Цель"',
    'sameAs'     => ['https://en.wikipedia.org/wiki/Eliyahu_M._Goldratt'],
    'knowsAbout' => ['Теория ограничений', 'CCPM', 'Управление проектами']
]];
$siteId = 'toc';
include 'header.php';
?>




<style>
/* ─── СТИЛИ СТАТЬИ (OVC DS, акцент TOC) ─── */
.article-hero { padding: 4.5rem 0 3rem; border-bottom: 2px solid var(--text-main); margin-bottom: 2rem; }
.article-hero h1 { font-family: var(--font-ui); font-size: clamp(2rem, 6vw, 3.5rem); font-weight: 900; line-height: 1.1; margin-bottom: 1.2rem; letter-spacing: -0.02em; color: var(--text-main); }
.article-hero h1 span { color: var(--c-toc); }
.article-hero .hero-desc { font-size: 1.05rem; color: var(--text-muted); max-width: 680px; line-height: 1.7; margin-bottom: 0; }
.content-block { padding: 4rem 0; border-bottom: 1px dashed var(--border); }
.content-block:last-child { border-bottom: none; }
.section-num { font-family: var(--font-code); font-size: 0.78rem; color: var(--c-toc); font-weight: 900; margin-bottom: 1rem; display: block; letter-spacing: 0.15em; text-transform: uppercase; }
.section-title { font-family: var(--font-ui); font-size: clamp(1.5rem, 3vw, 2.1rem); font-weight: 900; margin-bottom: 1.5rem; color: var(--text-main); line-height: 1.2; }
.text-lead { font-family: var(--font-ui); font-size: 1.2rem; line-height: 1.65; color: var(--text-main); margin-bottom: 2rem; font-weight: 700; }
.text-main { font-size: 1.05rem; line-height: 1.8; color: var(--text-muted); margin-bottom: 1.5rem; }
.text-main:last-child { margin-bottom: 0; }
.text-main a { color: var(--c-toc); text-decoration: underline; font-weight: 700; }
.btn { padding: 0.85rem 1.4rem; border-radius: var(--r-md); font-family: var(--font-ui); font-size: 0.8rem; font-weight: 800; text-transform: uppercase; text-decoration: none; transition: all 0.2s; text-align: center; letter-spacing: 0.05em; display: inline-block; cursor: pointer; }
.btn-outline { background: var(--bg-secondary); color: var(--c-toc); border: 2px solid var(--c-toc); }
.btn-outline:hover { background: var(--c-toc); color: #fff; transform: translateY(-2px); }
.insight-box { background: var(--bg-secondary); border-left: 4px solid var(--c-toc); padding: 2rem; margin: 2rem 0; border-radius: 0 var(--r-lg) var(--r-lg) 0; }
.insight-box h3 { font-family: var(--font-ui); font-size: 1.05rem; margin-bottom: 0.7rem; color: var(--c-toc); }
</style>
<main class="container" style="max-width: 900px; margin: 0 auto; padding: 48px 48px 80px;">
<style>@media (max-width: 900px) { .container { padding: 32px 28px 64px !important; } }
@media (max-width: 600px) { .container { padding: 20px 16px 48px !important; } }</style>
  <article itemscope itemtype="https://schema.org/TechArticle">
    <header class="article-hero"><h1 itemprop="headline">Элияху Голдратт и его Иона</h1><p class="hero-desc" itemprop="description"></p></header>
    
    <div class="bio-section">
        <div class="bio-text">
            <h2>Физик, сломавший производственный менеджмент</h2>
            <p>Элияху М. Голдратт (1947–2011) — израильский физик. В мир бизнеса попал случайно: приятель попросил помочь разобраться с производством. Голдратт применил к заводу тот же научный подход, что и к физическим системам, и обнаружил очевидное: любое предприятие работает как единая система, а её скорость определяется самым слабым звеном — ограничением. Всё остальное — следствие.</p>
            <p>Писать академические трактаты он не стал. Вместо этого — бизнес-романы с живыми героями и реальными конфликтами. Главный из них, «Цель» (1984), рассказывает про директора убыточного завода Алекса Рого. Его наставником в книге стал загадочный физик по имени <b>Иона</b> — очевидный автопортрет Голдратта.</p>
            <p>Иона никогда не давал прямых ответов. Только вопросы — пока собеседник сам не приходил к выводу, который казался ему очевидным с самого начала, но которого он до этого упорно избегал. Наша платформа работает ровно так же.</p>
        </div>
    </div>

    <blockquote class="quote-block">
        «Скажи мне, как ты будешь меня оценивать, и я скажу тебе, как я буду себя вести. Если ты оцениваешь меня нелогично, не жалуйся на моё нелогичное поведение.»
        <div class="quote-author">— Элияху Голдратт</div>
    </blockquote>

    <h2 class="section-title">Базовая библиография</h2>

    <div class="grid-2">
        <div class="book-card">
            <h4>Цель. Процесс непрерывного совершенствования</h4>
            <span class="book-meta">1984 год &nbsp;|&nbsp; Основы ТОС</span>
            <p>Книга, перевернувшая производственный менеджмент. История о том, как спасти завод за 90 дней — не героизмом, а просто перестав пытаться загрузить работой все станки и сфокусировавшись на одном бутылочном горлышке. Разошлась тиражом более 10 миллионов экземпляров. До сих пор входит в обязательное чтение в бизнес-школах.</p>
        </div>

        <div class="book-card">
            <h4>Критическая цепь</h4>
            <span class="book-meta">1997 год &nbsp;|&nbsp; ТОС в управлении проектами</span>
            <p>Голдратт переносит логику узких мест на проектное управление. Вводит студенческий синдром, буферы на слияние путей и Проектный буфер. Объясняет, почему жёсткие дедлайны для отдельных задач убивают весь проект, и как из этого выйти без героизма и переработок.</p>
        </div>

        <div class="book-card">
            <h4>Цель-2. Дело не в везении</h4>
            <span class="book-meta">1994 год &nbsp;|&nbsp; Мыслительные процессы</span>
            <p>Продолжение истории Алекса Рого: фокус смещается на маркетинг, продажи и разрешение системных конфликтов. Здесь появляются инструменты логического анализа — Грозовая туча, Дерево текущей реальности, Дерево будущей реальности. Из производственного романа ТОС вырастает в полноценную управленческую методологию.</p>
        </div>

        <div class="book-card">
            <h4>Правила Голдратта (The Choice)</h4>
            <span class="book-meta">2008 год &nbsp;|&nbsp; Философия</span>
            <p>Последняя концептуальная книга. Диалог с дочерью об устройстве систем и людей: почему любая сложная реальность имеет простое объяснение, почему перемены вызывают сопротивление, и почему любой компромисс — это капитуляция перед неправильно поставленным вопросом.</p>
        </div>

        <div class="book-card">
            <h4>Необходимо, но недостаточно</h4>
            <span class="book-meta">2000 год &nbsp;|&nbsp; ТОС и ERP</span>
            <p>Роман о внедрении ERP-систем и о том, почему технологии сами по себе не решают проблем бизнеса. Актуален для всех, кто занимается внедрением ПО, автоматизацией и цифровизацией: технология — необходимое условие, но не достаточное. Нужно менять правила игры вместе с инструментом.</p>
        </div>

        <div class="book-card">
            <h4>Я так и знал! ТОС для розничной торговли</h4>
            <span class="book-meta">2008 год &nbsp;|&nbsp; ТОС в логистике и цепочках поставок</span>
            <p>Применение ТОС к управлению запасами и цепочками поставок. Показывает, как традиционная система заказов создаёт дефицит и излишки одновременно, и предлагает контринтуитивное решение, которое работает на практике.</p>
        </div>
    </div>
</div>

</article>
</main>
<?php include 'footer.php'; ?>
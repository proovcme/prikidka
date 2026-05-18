<?php
declare(strict_types=1);
$siteId          = 'toc';
$pageTitle       = 'ПИР-калькулятор | Управление проектными работами по ТОС';
$pageDescription = 'Бесплатный онлайн-калькулятор стоимости ПИР для проектных бюро. Ресурсный график, матрица рисков, ТОС-буфер времени. Основан на Методе Критической Цепи (CCPM) Элияху Голдратта.';
$pageKeywords    = 'ПИР калькулятор, стоимость проектирования, управление ПИР, теория ограничений, CCPM, проектный буфер, ресурсный график проектировщика, РИМ проектирование';
$canonicalUrl    = 'https://toc.chernetchenko.pro/';

include 'header.php';
require_once '../../public_html/lib/frontmatter.php';
require_once '../../public_html/lib/views.php';
require_once '../../public_html/lib/sites.php';

$articles = getArticles('toc', '', false);
?>
<!-- SEO + JSON-LD -->
<meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
<meta name="keywords" content="<?= htmlspecialchars($pageKeywords) ?>">
<link rel="canonical" href="<?= $canonicalUrl ?>">
<meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
<meta property="og:description" content="<?= htmlspecialchars($pageDescription) ?>">
<meta property="og:type" content="website">
<meta property="og:url" content="<?= $canonicalUrl ?>">
<script type="application/ld+json">
<?= json_encode([
[
'@context'    => 'https://schema.org',
'@type'       => 'SoftwareApplication',
'name'        => 'ПИР-калькулятор',
'applicationCategory' => 'BusinessApplication',
'operatingSystem' => 'Web',
'url'         => 'https://toc.chernetchenko.pro/calc',
'description' => 'Бесплатный калькулятор стоимости проектных изысканий и работ (ПИР) для проектных бюро',
'offers'      => ['@type'=>'Offer','price'=>'0','priceCurrency'=>'RUB'],
'author'      => ['@type'=>'Person','name'=>'Олег Чернетченко','url'=>'https://chernetchenko.pro'],
]
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>

<style>
/* ─── TOC INDEX STYLES (OVC DS) ─── */
.toc-hero { padding: 5rem 0 2rem; border-bottom: 2px solid var(--text-main); margin-bottom: 2rem; }
.toc-hero h1 { font-family: var(--font-ui); font-size: clamp(2.2rem, 6vw, 4rem); font-weight: 900; line-height: 1.15; margin-bottom: 0.8rem; letter-spacing: -0.02em; color: var(--text-main); }
.toc-hero h1 span { color: var(--c-toc); display: block; font-size: 0.6em; margin-top: 0.3rem; font-weight: 800; letter-spacing: 0.02em; }
.toc-hero .hero-desc { color: var(--text-muted); font-size: 1.1rem; max-width: 700px; line-height: 1.6; }

/* NAV CHIPS */
.toc-nav-chips { display: flex; flex-wrap: wrap; gap: 0.8rem; margin-bottom: 4rem; padding-top: 1rem; }
.toc-chip { padding: 0.6rem 1.2rem; background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 50px; font-family: var(--font-code); font-size: 0.85rem; font-weight: 700; color: var(--text-muted); text-decoration: none; transition: all 0.2s; }
.toc-chip:hover { border-color: var(--c-toc); color: var(--c-toc); transform: translateY(-2px); }
.toc-chip.accent { background: var(--c-toc); color: #111; border-color: var(--c-toc); }

/* SECTIONS */
.section-title { font-family: var(--font-ui); font-size: 1.4rem; font-weight: 900; margin: 0 0 2rem; color: var(--text-main); padding-bottom: 0.8rem; border-bottom: 2px solid var(--text-main); }
.section-sub { display: block; font-size: 0.9rem; font-weight: 400; color: var(--text-muted); margin-top: 0.5rem; border-bottom: none; padding-bottom: 0; }

/* GRIDS */
.toc-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 4rem; }

/* CARDS */
.toc-card { background: var(--bg-secondary); border: 2px solid var(--border); padding: 2rem; border-radius: var(--r-lg); transition: all 0.2s; display: flex; flex-direction: column; text-decoration: none; color: inherit; }
.toc-card:hover { transform: translateY(-4px); box-shadow: 6px 6px 0 var(--text-main); border-color: var(--text-main); }
.toc-card h3 { font-family: var(--font-ui); font-size: 1.2rem; font-weight: 900; margin: 0 0 1rem; color: var(--c-toc); line-height: 1.3; }
.toc-card ul { padding-left: 1.2rem; color: var(--text-muted); margin: 0; font-size: 0.9rem; line-height: 1.6; }
.toc-card li { margin-bottom: 6px; }
.toc-card li b { color: var(--text-main); }

/* CTA BOX */
.cta-box { background: var(--bg-secondary); border: 2px solid var(--text-main); border-radius: var(--r-xl); padding: 3.5rem; text-align: center; transition: all 0.2s; margin-bottom: 4rem; position: relative; overflow: hidden; display: flex; flex-direction: column; align-items: center; }
.cta-box::before { content: ""; position: absolute; top: 0; left: 0; width: 8px; height: 100%; background: var(--c-toc); }
.cta-box h3 { font-family: var(--font-ui); font-size: clamp(1.8rem, 3vw, 2.2rem); font-weight: 900; margin-bottom: 1rem; color: var(--text-main); }
.cta-box p { font-size: 1.1rem; color: var(--text-muted); line-height: 1.6; margin-bottom: 2rem; max-width: 800px; }
.btn-primary { background: var(--c-toc); color: #111; padding: 1.2rem 2.5rem; border-radius: var(--r-md); font-family: var(--font-ui); font-weight: 900; font-size: 1rem; text-transform: uppercase; text-decoration: none; transition: all 0.2s; display: inline-block; border: 2px solid var(--c-toc); }
.btn-primary:hover { background: transparent; color: var(--c-toc); transform: translateY(-3px); }

/* ARTICLES */
.article-tags { display: flex; gap: 6px; flex-wrap: wrap; margin-top: auto; }
.article-tags .tag { font-size: 11px; padding: 2px 8px; background: var(--bg-tertiary); border: 1px solid var(--border); border-radius: var(--r-sm); color: var(--c-toc); font-family: var(--font-code); }

/* AI CHAT */
.ai-trigger { position: fixed; bottom: 20px; right: 20px; width: 60px; height: 60px; background: var(--c-toc); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.2); transition: transform 0.2s; z-index: 9998; color: #111; }
.ai-trigger:hover { transform: scale(1.1); }
.ai-trigger span { font-size: 1.8rem; }
.ai-modal { display: none; position: fixed; bottom: 90px; right: 20px; width: 380px; height: 500px; background: var(--bg-secondary); border: 2px solid var(--text-main); border-radius: var(--r-lg); box-shadow: 10px 10px 0 rgba(0,0,0,0.1); z-index: 9999; flex-direction: column; overflow: hidden; }
.ai-modal.open { display: flex; animation: slideUp 0.3s ease; }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
.ai-header { background: var(--text-main); color: var(--bg-secondary); padding: 15px; display: flex; justify-content: space-between; align-items: center; }
.ai-header h4 { margin: 0; font-family: var(--font-code); font-size: 0.9rem; }
.ai-close { background: none; border: none; color: var(--bg-secondary); cursor: pointer; font-size: 1.2rem; }
.ai-body { flex: 1; padding: 15px; overflow-y: auto; background: var(--bg-main); font-family: var(--font-code); font-size: 0.85rem; line-height: 1.5; }
.ai-msg { margin-bottom: 10px; padding: 10px; border-radius: 8px; max-width: 85%; }
.ai-msg.bot { background: var(--bg-secondary); border: 1px solid var(--border); align-self: flex-start; color: var(--text-main); }
.ai-msg.user { background: var(--c-toc); color: #111; margin-left: auto; text-align: right; }
.ai-input-wrap { padding: 10px; border-top: 1px solid var(--border); display: flex; gap: 8px; background: var(--bg-secondary); }
.ai-input-wrap input { flex: 1; padding: 8px; border: 1px solid var(--border); border-radius: var(--r-sm); font-family: var(--font-code); background: var(--bg-main); color: var(--text-main); }
.ai-input-wrap button { padding: 8px 16px; background: var(--c-toc); color: #111; border: none; border-radius: var(--r-sm); cursor: pointer; font-weight: bold; font-family: var(--font-ui); }

@media (max-width: 768px) {
.toc-hero { padding: 3rem 0 2rem; }
.cta-box { padding: 2rem; }
.cta-box::before { width: 100%; height: 8px; }
.toc-nav-chips { justify-content: center; }
}
@media (max-width: 600px) { .ai-modal { width: 90%; height: 60vh; bottom: 10px; right: 5%; } }
</style>

<main class="container" style="max-width:1100px; margin: 0 auto; padding: 48px 48px 80px;">
<style>@media (max-width: 900px) { .container { padding: 32px 28px 64px !important; } }
@media (max-width: 600px) { .container { padding: 20px 16px 48px !important; } }</style>

<!-- 1. HERO -->
<section class="toc-hero">
<h1><span class="cli-only">$ </span>Управление реальностью<br>в проектировании <span>по ТОС</span></h1>
<p class="hero-desc">Инструменты и правила планирования ПИР на базе Теории Ограничений. Никакой магии — только расчёт, контроль ограничений и честный ресурсный график.</p>
<div style="display:flex; gap:10px; flex-wrap:wrap;">
<a href="/calc" class="toc-chip" style="background:var(--c-toc);color:var(--bg-secondary);border-color:var(--c-toc)">🧮 Открыть калькулятор</a>
<a href="#manifesto" class="toc-chip">📜 Манифест</a>
</div>
</section>

<!-- 2. NAV -->
<div class="toc-nav-chips">
<a href="#calc" class="toc-chip accent">Калькулятор</a>
<a href="#manifesto" class="toc-chip">Манифест</a>
<a href="#articles" class="toc-chip">Статьи</a>
<a href="#toc-rules" class="toc-chip">Правила ТОС</a>
</div>

<!-- 3. CTA / CALCULATOR -->
<section class="section-wrap" id="calc">
<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
<div class="cta-box">
<h3>🧮 ПИР-калькулятор v2.0</h3>
<p>Рассчитай стоимость проекта, построй ресурсный график и найди точку безубыточности за 5 минут.</p>
<a href="/calc" class="btn-primary">Перейти к расчетам →</a>
</div>
<div class="cta-box">
<h3>🏗 Инженерная прикидка</h3>
<p>Быстрая предпроектная оценка нагрузок, стоимости, сроков и пусконаладочных работ.</p>
<a href="/prikidka/" class="btn-primary">Прикинуть проект →</a>
</div>
</div>
</section>

<!-- 4. MANIFESTO (ПОЛНЫЙ ТЕКСТ) -->
<section class="section-wrap" id="manifesto">
<h2 class="section-title">
Манифест проектировщика
<span class="section-sub">Свод правил для тех, кто не хочет жить в иллюзиях.</span>
</h2>
<div class="toc-grid">
<div class="toc-card c-toc">
<h3>🚩 Стратегия, планирование и риски</h3>
<ul>
<li><b>Принцип исходной лжи:</b> проект всегда начинается с недооценки сроков и сложности на стадии первичной оценки.</li>
<li><b>Географическая разница:</b> договорной график и проектный график имеют такое же отношение друг к другу, как Австрия и Австралия.</li>
<li><b>Неизбежность срывов:</b> сроки, указанные в договоре, будут сорваны без системной защиты.</li>
<li><b>Полезный пессимизм:</b> внутренний график должен быть максимально пессимистичным для адекватного отражения реальности.</li>
<li><b>Отсутствие магии:</b> в управлении проектами не бывает волшебных решений, есть только расчет и контроль ограничений.</li>
<li><b>Формализм рисков:</b> стадия оценки рисков в классическом управлении почти всегда игнорируется или проводится формально.</li>
<li><b>Обреченность:</b> если в проекте нет скрытых резервов на риски (буферов) — проект обречен на крах.</li>
<li><b>Контроль:</b> если вы не ведете график — вы не контролируете проект.</li>
<li><b>Жертвенность:</b> всегда есть чем и ради чего пожертвовать.</li>
<li>Любая идеальная схема может быть разрушена человеческим фактором.</li>
<li>Любая идеальная схема может быть разрушена чем угодно.</li>
<li>Все всегда идет не так.</li>
<li>Срочное не важно, а важное не срочно.</li>
</ul>
</div>

<div class="toc-card c-toc">
<h3>⏳ Ресурсы и загрузка</h3>
<ul>
<li><b>Главный инструмент:</b> в управлении проектом нет инструмента лучше, чем ресурсный график.</li>
<li><b>Реальные возможности:</b> нельзя использовать ресурсы без понимания их истинных возможностей и квалификации.</li>
<li><b>Вскрытие дефицита:</b> на стадии планирования всегда выясняется нехватка ресурсов, скрытая при первичной оценке.</li>
<li><b>Планирование отсутствия:</b> отпуск не наступает внезапно, на это тоже есть график, который должен быть частью проекта.</li>
<li><b>Физика загрузки:</b> фактическая загрузка не может быть выше 100%, в то время как плановая загрузка может превышать этот предел.</li>
<li><b>Золотой стандарт:</b> адекватная плановая загрузка составляет 80%, остальное — резерв на вариативность системы.</li>
<li><b>Миф эффективности:</b> стремление к 100% занятости каждого ресурса парализует движение всего проекта.</li>
</ul>
</div>

<div class="toc-card c-toc">
<h3>👥 Управление, процессы и доверие</h3>
<ul>
<li><b>Качество данных:</b> бардак на входе неизбежно ведет к бардаку на выходе.</li>
<li><b>Технологическая ловушка:</b> применение современных технологий на старых, неэффективных процессах управления ведет к краху.</li>
<li><b>Уровень автономности:</b> чем выше должность сотрудника, тем больше у него должно быть автономности в принятии решений.</li>
<li><b>Долгосрочные риски:</b> жесткая методика управления дает выгоду в краткосрочном периоде, но в долгосрочной перспективе ведет к краху.</li>
<li><b>Информация вместо данных:</b> менеджерам нужны не отчеты, а информация, доступная в нужное время и в форме, позволяющей принимать решения.</li>
<li><b>Локальная эффективность:</b> попытки оптимизировать работу каждого отдела в отдельности вредят общей цели проекта.</li>
<li><b>Устная фиксация:</b> устные договоренности не работают.</li>
<li><b>Вероятность обмана:</b> если вас могут обмануть — вас обманут.</li>
<li><b>Суть руководителя:</b> руководитель, который не умеет ставить задачи и доверять их исполнение — не руководитель.</li>
</ul>
</div>

<div class="toc-card c-toc">
<h3>🧠 Психология, этика и человеческий фактор</h3>
<ul>
<li><b>Системный сбой:</b> героизм и переработки — это не повод для гордости, а признак плохой организации процесса.</li>
<li><b>Синдром студента:</b> любая задача начинается в самый последний момент, что уничтожает все индивидуальные резервы времени.</li>
<li><b>Закон Паркинсона:</b> работа всегда заполняет всё время, отпущенное на её выполнение.</li>
<li><b>Вредная многозадачность:</b> переключение между задачами убивает производительность и увеличивает сроки исполнения.</li>
<li><b>Закон Брукса:</b> добавление людей в проект, который уже опаздывает, заставляет его опаздывать еще сильнее.</li>
<li><b>Эффективность уважения:</b> человеческое, уважительное отношение всегда эффективнее палки и угроз.</li>
<li><b>Грань:</b> нельзя путать хорошее отношение и слабость.</li>
<li><b>Кадры:</b> хороший специалист — это человек с характером.</li>
</ul>
</div>
</div>
</section>

<!-- 5. БАЗОВЫЕ МАТЕРИАЛЫ (ССЫЛКИ НА PHP) -->
<section class="section-wrap" id="base-articles">
<h2 class="section-title">Базовые материалы</h2>
<div class="toc-grid">
<a href="/why" class="toc-card">
<h3>Зачем это проектировщику?</h3>
<p>Как применить ТОС в проектном бюро. Интерактивная анимация DBR. Сравнение традиционного подхода и ТОС. Пошаговый сценарий внедрения.</p>
</a>
<a href="/toc" class="toc-card">
<h3>Теория Ограничений Систем (ТОС)</h3>
<p>Полное руководство по ТОС с примерами для проектных бюро. Пять фокусирующих шагов, DBR, CCPM, мыслительные процессы. T-I-OE, шесть слоёв сопротивления.</p>
</a>
<a href="/iona" class="toc-card">
<h3>Элияху Голдратт и его Иона</h3>
<p>Биография создателя ТОС. Цитаты, полная библиография: «Цель», «Критическая цепь», «Цель-2». Персонаж Ионы и его метод Сократа.</p>
</a>
<a href="/dbr" class="toc-card">
<h3>Механика DBR: Барабан, Буфер, Канат</h3>
<p>Анимация и разбор механизма управления потоком задач через самое узкое место. Защита дедлайнов буфером и синхронизация исполнителей.</p>
</a>
</div>
</section>

<!-- 6. СТАТЬИ (CMS LOOP) -->
<section class="section-wrap" id="articles">
<h2 class="section-title">Статьи и руководства</h2>
<div class="toc-grid">
<?php if (!empty($articles)): foreach ($articles as $art): ?>
<a href="/article.php?slug=<?= htmlspecialchars($art['meta']['slug']) ?>" class="toc-card">
<h3><?= htmlspecialchars($art['meta']['title']) ?></h3>
<?php if (!empty($art['meta']['description'])): ?>
<p><?= htmlspecialchars($art['meta']['description']) ?></p>
<?php endif; ?>
<?php if (!empty($art['meta']['tags'])): ?>
<div class="article-tags">
<?php foreach ($art['meta']['tags'] as $tag): ?>
<span class="tag">#<?= htmlspecialchars($tag) ?></span>
<?php endforeach; ?>
</div>
<?php endif; ?>
</a>
<?php endforeach; else: ?>
<div class="toc-card" style="border-style:dashed; justify-content:center; align-items:center; text-align:center;">
<h3>Статей пока нет</h3>
<p>Создайте статью через <a href="/admin/?tab=cms" style="color:var(--c-toc);">CMS</a> с полем <code>site: toc</code></p>
</div>
<?php endif; ?>
</div>
</section>

<!-- 7. TOC RULES (CCPM) -->
<section class="section-wrap" id="toc-rules">
<h2 class="section-title">Правила CCPM</h2>
<div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
<div style="background:var(--bg-secondary); padding:20px; border-radius:var(--r-lg); border:1px dashed var(--c-toc);">
<h3 style="margin-top:0; color:var(--c-toc); font-family:var(--font-code); font-size:0.9rem;">❌ КАК НЕЛЬЗЯ</h3>
<ul style="list-style:none; padding:0; color:var(--text-muted); margin:0;">
<li style="margin-bottom:8px;">• Давать задачи с запасом "на всякий случай"</li>
<li style="margin-bottom:8px;">• Спрашивать "почему не готово?" каждый день</li>
<li>• Бросать всё ради "срочной мелочи"</li>
</ul>
</div>
<div style="background:var(--bg-secondary); padding:20px; border-radius:var(--r-lg); border:1px dashed var(--c-toc);">
<h3 style="margin-top:0; color:var(--c-toc); font-family:var(--font-code); font-size:0.9rem;">✅ КАК ПО ТОС</h3>
<ul style="list-style:none; padding:0; color:var(--text-muted); margin:0;">
<li style="margin-bottom:8px;">• Убрать запасы из задач → собрать в Буфер Проекта</li>
<li style="margin-bottom:8px;">• Принцип эстафеты: готов → сразу передавай</li>
<li>• Следить только за потреблением буфера</li>
</ul>
</div>
</div>
</section>
</main>

<!-- AI TRIGGER -->
<div class="ai-trigger" onclick="toggleAI()"><span>🧠</span></div>
<!-- AI MODAL -->
<div class="ai-modal" id="aiModal">
<div class="ai-header"><h4>🤖 TOC AI Помощник</h4><button class="ai-close" onclick="toggleAI()">×</button></div>
<div class="ai-body" id="chatBody"><div class="ai-msg bot">Привет! Я знаю всё про расчеты ПИР и Теорию ограничений. Спрашивай.</div></div>
<div class="ai-input-wrap"><input type="text" id="chatInput" placeholder="Вопрос..."><button onclick="sendChat()">→</button></div>
</div>

<script>
function toggleAI() { document.getElementById('aiModal').classList.toggle('open'); }
function sendChat() {
const inp = document.getElementById('chatInput'), body = document.getElementById('chatBody'), q = inp.value.trim();
if (!q) return;
body.innerHTML += `<div class="ai-msg user">${q}</div>`; inp.value = ''; body.scrollTop = body.scrollHeight;
setTimeout(() => { body.innerHTML += `<div class="ai-msg bot">Для точного ответа нужны параметры проекта. Попробуй: "Как рассчитать буфер для 50 млн?"</div>`; body.scrollTop = body.scrollHeight; }, 800);
}
</script>
<?php include 'footer.php'; ?>
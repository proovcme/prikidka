<?php
declare(strict_types=1);
/**
 * /index.php — TOC: Теория Ограничений и расчеты ПИР (v2.0 Redesign)
 * Дизайн: WAF-эталон (index-31.php) + Интеграция ИИ-помощника.
 * Контент: Манифест проектировщика, ссылки на калькулятор.
 */

$siteId          = 'toc';
$pageTitle       = 'ПИР-калькулятор | Управление проектными работами по ТОС';
$pageDescription = 'Бесплатный онлайн-калькулятор стоимости ПИР для проектных бюро. Ресурсный график, матрица рисков, ТОС-буфер времени. Основан на Методе Критической Цепи (CCPM) Элияху Голдратта.';
$pageKeywords    = 'ПИР калькулятор, стоимость проектирования, управление ПИР, теория ограничений, CCPM, проектный буфер, ресурсный график проектировщика, РИМ проектирование';
$canonicalUrl    = 'https://toc.chernetchenko.pro/';

include 'header.php';
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
    ],
    [
        '@context' => 'https://schema.org',
        '@type'    => 'FAQPage',
        'mainEntity' => [
            ['@type'=>'Question','name'=>'Что такое ТОС в проектировании?','acceptedAnswer'=>['@type'=>'Answer','text'=>'ТОС (Теория Ограничений) — методология управления, основанная на философии Элияху Голдратта. В проектировании применяется через CCPM: единый проектный буфер, отказ от индивидуальных дедлайнов, принцип эстафеты.']],
            ['@type'=>'Question','name'=>'Как рассчитать стоимость ПИР?','acceptedAnswer'=>['@type'=>'Answer','text'=>'Стоимость ПИР рассчитывается по формуле: ФОТ (ставка х дни х загрузка) х ТОС-буфер (50%) х коэффициенты сложности + накладные расходы + фонд рисков (EMV) + маржа.']]
        ]
    ]
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>

<style>
/* ─── TOC SPECIFIC STYLES ─── */
:root {
    --toc-gold: #b8860b;
    --toc-gold-light: rgba(184, 134, 11, 0.08);
    --toc-dark: #4a3b00;
}

/* HERO SECTION */
.hero { padding: 4.5rem 0 3rem; border-bottom: 2px solid var(--ink); margin-bottom: 2rem; }
.hero h1 { font-family: var(--font-title); font-size: clamp(2rem, 6vw, 3.5rem); font-weight: 900; line-height: 1.1; margin-bottom: 1.2rem; color: var(--ink); }
.hero h1 .line-accent { color: var(--toc-gold); }
.hero-desc { font-size: 1.1rem; color: var(--ink2); max-width: 700px; line-height: 1.7; margin-bottom: 2rem; }

/* STICKY NAV */
.nav-sticky { position: sticky; top: 72px; z-index: 90; background: rgba(250, 247, 242, 0.95); backdrop-filter: blur(12px); padding: 12px 0; border-bottom: 1px solid var(--border); margin-bottom: 40px; }
.anchor-chips { display: flex; flex-wrap: wrap; gap: 10px; }
.chip { font-family: var(--font-mono); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; text-decoration: none; color: var(--ink2); padding: 8px 16px; border: 1px solid var(--border); border-radius: 20px; transition: all 0.2s; letter-spacing: 0.05em; background: #fff; }
.chip:hover { border-color: var(--toc-gold); color: var(--toc-gold); }
.chip.active { background: var(--toc-gold); color: #fff; border-color: var(--toc-gold); box-shadow: 2px 2px 0 rgba(184, 134, 11, 0.2); }

/* SECTIONS */
.section-wrap { margin-bottom: 5rem; scroll-margin-top: 140px; }
.section-head { font-family: var(--font-title); font-size: 1.4rem; font-weight: 900; margin: 0 0 2rem; color: var(--ink); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid var(--toc-gold); padding-bottom: 0.8rem; }
.section-sub { font-size: 0.8rem; color: var(--ink3); font-weight: 500; text-transform: none; letter-spacing: 0; display: block; margin-top: 4px; }

/* MANIFESTO CARDS */
.manifesto-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
.m-card { background: #fff; border: 2px solid var(--border); border-radius: 12px; padding: 25px; transition: transform 0.2s, box-shadow 0.2s; height: 100%; }
.m-card:hover { transform: translate(-4px, -4px); box-shadow: 6px 6px 0 var(--toc-gold); border-color: var(--ink); }
.m-card h3 { font-family: var(--font-title); font-size: 1.1rem; font-weight: 900; margin: 0 0 15px; color: var(--ink); border-bottom: 1px dashed var(--border); padding-bottom: 10px; }
.m-card ul { list-style: none; padding: 0; margin: 0; }
.m-card li { margin-bottom: 10px; font-size: 0.9rem; color: var(--ink2); line-height: 1.5; padding-left: 15px; position: relative; }
.m-card li::before { content: "•"; position: absolute; left: 0; color: var(--toc-gold); font-weight: 800; }
.m-card li b { color: var(--ink); font-weight: 700; }

/* CTA BOX */
.cta-box { background: #fff; border: 2px solid var(--ink); border-radius: 16px; padding: 2.5rem; text-align: center; transition: all 0.2s; margin-bottom: 4rem; }
.cta-box:hover { box-shadow: 8px 8px 0 var(--toc-gold); transform: translate(-4px, -4px); }
.cta-box h3 { font-family: var(--font-title); font-size: 1.4rem; font-weight: 900; margin-bottom: 10px; }
.cta-box p { color: var(--ink2); margin-bottom: 20px; font-size: 1rem; }
.btn-primary { display: inline-block; padding: 12px 24px; background: var(--toc-gold); color: #fff; border-radius: 8px; text-decoration: none; font-weight: 700; transition: 0.2s; }
.btn-primary:hover { background: var(--ink); color: #fff; }

/* AI CHAT MODAL */
.ai-trigger { position: fixed; bottom: 20px; right: 20px; width: 60px; height: 60px; background: var(--toc-gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.2); transition: transform 0.2s; z-index: 9998; }
.ai-trigger:hover { transform: scale(1.1); }
.ai-trigger span { font-size: 1.8rem; }

.ai-modal { display: none; position: fixed; bottom: 90px; right: 20px; width: 380px; height: 500px; background: #fff; border: 2px solid var(--ink); border-radius: 16px; box-shadow: 10px 10px 0 rgba(0,0,0,0.1); z-index: 9999; flex-direction: column; overflow: hidden; }
.ai-modal.open { display: flex; animation: slideUp 0.3s ease; }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

.ai-header { background: var(--ink); color: #fff; padding: 15px; display: flex; justify-content: space-between; align-items: center; }
.ai-header h4 { margin: 0; font-family: var(--font-mono); font-size: 0.9rem; }
.ai-close { background: none; border: none; color: #fff; cursor: pointer; font-size: 1.2rem; }
.ai-body { flex: 1; padding: 15px; overflow-y: auto; background: var(--bg); font-family: var(--font-mono); font-size: 0.85rem; line-height: 1.5; }
.ai-msg { margin-bottom: 10px; padding: 10px; border-radius: 8px; max-width: 85%; }
.ai-msg.bot { background: #fff; border: 1px solid var(--border); align-self: flex-start; color: var(--ink); }
.ai-msg.user { background: var(--toc-gold); color: #fff; margin-left: auto; text-align: right; }
.ai-input-wrap { padding: 10px; border-top: 1px solid var(--border); display: flex; gap: 8px; background: #fff; }
.ai-input-wrap input { flex: 1; padding: 8px; border: 1px solid var(--border); border-radius: 4px; font-family: var(--font-mono); }
.ai-input-wrap button { padding: 8px 16px; background: var(--toc-gold); color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }

@media (max-width: 600px) { .ai-modal { width: 90%; height: 60vh; bottom: 10px; right: 5%; } }
</style>

<main class="container" style="max-width:1100px;">

    <!-- 1. HERO -->
    <section class="hero">
        <h1 itemprop="headline">Управление реальностью<br>в проектировании <span class="line-accent">по ТОС</span></h1>
        <p class="hero-desc">Инструменты и правила планирования ПИР на базе Теории Ограничений. Никакой магии — только расчёт, контроль ограничений и честный ресурсный график.</p>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="/calc" class="chip" style="background:var(--toc-gold);color:#fff;border-color:var(--toc-gold)">🧮 Открыть калькулятор</a>
            <a href="#manifesto" class="chip">📜 Манифест</a>
        </div>
    </section>

    <!-- 2. STICKY NAV -->
    <div class="nav-sticky">
        <nav class="anchor-chips" id="page-nav">
            <a href="#calc" class="chip active">Калькулятор</a>
            <a href="#manifesto" class="chip">Манифест</a>
            <a href="#toc-rules" class="chip">Правила ТОС</a>
        </nav>
    </div>

    <!-- 3. CTA / CALCULATOR -->
    <section class="section-wrap" id="calc">
        <div class="cta-box">
            <h3>🧮 ПИР-калькулятор v2.0</h3>
            <p>Рассчитай стоимость проекта, построй ресурсный график и найди точку безубыточности за 5 минут.</p>
            <a href="/calc" class="btn-primary">Перейти к расчетам →</a>
        </div>
    </section>

    <!-- 4. MANIFESTO -->
    <section class="section-wrap" id="manifesto">
        <h2 class="section-head">
            Манифест проектировщика
            <span class="section-sub">Свод правил для тех, кто не хочет жить в иллюзиях.</span>
        </h2>
        <div class="manifesto-grid">
            <div class="m-card">
                <h3>🚩 Стратегия и риски</h3>
                <ul>
                    <li><b>Принцип исходной лжи:</b> проект всегда начинается с недооценки сроков.</li>
                    <li><b>Географическая разница:</b> договорной и проектный графики имеют такое же отношение друг к другу, как Австрия и Австралия.</li>
                    <li><b>Отсутствие магии:</b> в управлении не бывает волшебных решений — есть только расчёт.</li>
                </ul>
            </div>
            <div class="m-card">
                <h3>⏳ Ресурсы и загрузка</h3>
                <ul>
                    <li><b>Главный инструмент:</b> нет инструмента лучше, чем ресурсный график.</li>
                    <li><b>Золотой стандарт:</b> адекватная плановая загрузка — 80%. 100% — это ступор.</li>
                    <li><b>Физика загрузки:</b> фактическая загрузка не может быть выше 100%, плановая — может (и это ошибка).</li>
                </ul>
            </div>
            <div class="m-card">
                <h3>👥 Управление и доверие</h3>
                <ul>
                    <li><b>Устная фиксация:</b> устные договорённости не работают.</li>
                    <li><b>Информация вместо данных:</b> менеджерам нужны не отчёты, а решения.</li>
                    <li><b>Локальная эффективность:</b> оптимизация одного отдела вредит целому проекту.</li>
                </ul>
            </div>
            <div class="m-card">
                <h3>🧠 Психология и этика</h3>
                <ul>
                    <li><b>Системный сбой:</b> героизм и переработки — это признак плохой организации, а не повод для гордости.</li>
                    <li><b>Синдром студента:</b> любая задача начинается в самый последний момент.</li>
                    <li><b>Закон Брукса:</b> добавление людей в отстающий проект заставляет его опаздывать ещё сильнее.</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- 5. TOC RULES (CCPM) -->
    <section class="section-wrap" id="toc-rules">
        <h2 class="section-head">Правила CCPM (Метод критической цепи)</h2>
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
            <div style="background:var(--bg); padding:20px; border-radius:12px; border:1px dashed var(--toc-gold);">
                <h3 style="margin-top:0; color:var(--toc-gold); font-family:var(--font-mono); font-size:0.9rem;">❌ КАК ДЕЛАТЬ НЕЛЬЗЯ</h3>
                <ul style="list-style:none; padding:0; color:var(--ink2);">
                    <li style="margin-bottom:8px;">• Давать каждому задачу с запасом "на всякий случай"</li>
                    <li style="margin-bottom:8px;">• Спрашивать "почему не готово?" каждый день</li>
                    <li style="margin-bottom:8px;">• Бросать всё ради "срочной мелочи"</li>
                </ul>
            </div>
            <div style="background:var(--bg); padding:20px; border-radius:12px; border:1px dashed var(--toc-gold);">
                <h3 style="margin-top:0; color:var(--toc-dark); font-family:var(--font-mono); font-size:0.9rem;">✅ КАК ДЕЛАТЬ ПО ТОС</h3>
                <ul style="list-style:none; padding:0; color:var(--ink2);">
                    <li style="margin-bottom:8px;">• Убрать запасы из задач, собрать их в общий Буфер Проекта</li>
                    <li style="margin-bottom:8px;">• Работать по принципу эстафеты (готов передавай сразу)</li>
                    <li style="margin-bottom:8px;">• Следить только за потреблением буфера</li>
                </ul>
            </div>
        </div>
    </section>

</main>

<!-- AI TRIGGER -->
<div class="ai-trigger" onclick="toggleAI()">
    <span>🧠</span>
</div>

<!-- AI MODAL -->
<div class="ai-modal" id="aiModal">
    <div class="ai-header">
        <h4>🤖 TOC AI Помощник</h4>
        <button class="ai-close" onclick="toggleAI()">×</button>
    </div>
    <div class="ai-body" id="chatBody">
        <div class="ai-msg bot">Привет! Я знаю всё про расчеты ПИР и Теорию ограничений. Спрашивай.</div>
    </div>
    <div class="ai-input-wrap">
        <input type="text" id="chatInput" placeholder="Вопрос...">
        <button onclick="sendChat()">→</button>
    </div>
</div>

<script>
// JS для AI Чата (Пока заглушка, потом подключим API)
function toggleAI() { document.getElementById('aiModal').classList.toggle('open'); }
function sendChat() {
    const inp = document.getElementById('chatInput');
    const body = document.getElementById('chatBody');
    const q = inp.value.trim();
    if (!q) return;
    body.innerHTML += `<div class="ai-msg user">${q}</div>`;
    inp.value = '';
    body.scrollTop = body.scrollHeight;
    
    // Имитация ответа
    setTimeout(() => {
        body.innerHTML += `<div class="ai-msg bot">Чтобы ответить точно, мне нужно знать параметры проекта. Попробуй задать вопрос вроде: "Как рассчитать буфер для проекта на 50 млн?"</div>`;
        body.scrollTop = body.scrollHeight;
    }, 800);
}

// Навигация
(function(){
    const nav=document.getElementById('page-nav'), links=Array.from(nav.querySelectorAll('a'));
    const sections=links.map(l=>document.querySelector(l.getAttribute('href'))).filter(Boolean);
    function setActive(){
        let cur='';
        sections.forEach(s=>{ if(window.scrollY>=s.offsetTop-150) cur=s.id; });
        links.forEach(l=>l.classList.toggle('active', l.getAttribute('href')==='#'+cur));
    }
    window.addEventListener('scroll', setActive, {passive:true}); setActive();
})();
</script>

<?php include 'footer.php'; ?>
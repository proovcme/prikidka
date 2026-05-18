# Руководство для ИИ: перенос OVC Design System на страницы go-cms

> Версия: 2.0 | Дата: 2026-05  
> Проект: ovc.me | UI Kit: OVC Design System v3.0
>
> **Смотри также:**  
> `VSCODE_PROMPT.md` — промпт для VS Code при переносе подсайта  
> `SUBSITE_MIGRATION.md` — инструкция по процессу переноса для человека

---

## 0. Перед началом работы — обязательный чеклист

Перед тем как писать хоть одну строку кода, прочитай:

1. `/frontend/ui-kit/00_manifest.md` — токены, логика mode/theme, правила системы
2. `/frontend/ui-kit/css/01_tokens.css` — все CSS-переменные
3. `/frontend/ui-kit/css/03_components.css` — все компоненты
4. `ARCHITECTURE.md` — структура go-cms, маршрутизация, что где лежит
5. `MIGRATION.md` — что уже сделано, что ещё нет

**Никогда не редактируй файлы UI Kit (`01_tokens.css`, `02_base.css`, `03_components.css`, `04_components.js`, `05_interactions.js`) без явной причины.** Все page-specific стили пишутся внутри `<style>` тега на самой странице.

---

## 1. Архитектура CSS: три уровня

```
01_tokens.css      ← глобальные переменные (цвета, шрифты, тени, радиусы)
02_base.css        ← сброс, типографика body, медиазапросы
03_components.css  ← готовые компоненты: хедер, футер, карточки, кнопки, таблицы...
─────────────────────────────────────────────────────────────────
<style> на странице ← только page-specific стили, используют токены из уровней выше
```

**Правило**: page-specific стили не дублируют то, что уже есть в 03_components.css. Они только расширяют или переопределяют для конкретной страницы.

---

## 2. Структура любой PHP-страницы

Каждая публичная страница сайта строится по одному шаблону:

```php
<?php
// 1. Обязательные переменные ДО include header.php
$siteId    = 'main';          // или 'waf', 'toc', 'fun'
$pageTitle = 'Заголовок';     // используется в <title>
include 'header.php';          // подключает OVC CSS/JS + <html><head><body>
?>

<!-- 2. SEO мета-теги (ПОСЛЕ include header.php — PHP уже вывел <head>) -->
<meta name="description" content="...">
<link rel="canonical" href="https://chernetchenko.pro/page">
<meta property="og:title" content="...">
<meta property="og:type" content="website">

<!-- 3. Page-specific стили -->
<style>
/* Только то, чего нет в 03_components.css */
/* Всегда используй CSS-переменные из 01_tokens.css */
.my-page-wrapper {
  max-width: 900px;
  margin: 0 auto;
  padding: 48px 48px 80px;
}
</style>

<!-- 4. Контент страницы -->
<main>
  <!-- ... -->
</main>

<!-- 5. Подключение футера — закрывает </body></html> -->
<?php include 'footer.php'; ?>

<!-- 6. Page-specific JS (если нужен) -->
<script>
// Только логика этой страницы
</script>
```

### Критически важно:

- `$siteId` и `$pageTitle` **должны** быть объявлены до `include 'header.php'`
- `header.php` открывает `<!DOCTYPE html>` и `<body>` — не дублируй их
- `footer.php` закрывает `</body></html>` — не дублируй их
- Мета-теги `<meta name="description">` пишутся **после** include header.php — они попадут в `<head>` через буферизацию вывода PHP

---

## 3. Переменные $siteId и цвета подсайтов

`$siteId` определяет активный подсайт. Это влияет на:
- Подсветку активной ссылки в хедере
- Переменную `--accent` (CSS адаптер-слой в header.php)

| `$siteId` | Домен | CSS цвет | Hex |
|---|---|---|---|
| `main` | ovc.me | `--c-main` | `#2a629a` |
| `waf`  | ai.ovc.me | `--c-waf` = `--c-ai` | `#b02a2a` |
| `rag`  | rag.ovc.me | `--c-rag` | `#c29922` |
| `toc`  | toc.ovc.me | `--c-toc` | `#5a9c42` |
| `fun`  | fun.ovc.me | `--c-fun` | `#4a2180` |

Переменная `--accent` в адаптер-слое header.php автоматически равна цвету текущего подсайта. Старые стили страниц могут использовать `var(--accent)` и получат правильный цвет.

---

## 4. Компоненты UI Kit — справочник

### 4.1 Кнопки

```html
<!-- Основная кнопка с цветом темы -->
<a href="..." class="btn btn-action c-main">Текст</a>
<a href="..." class="btn btn-action c-bim">BIM цвет</a>
<a href="..." class="btn btn-action c-rag">RAG цвет</a>

<!-- Вторичная (outline) кнопка -->
<a href="..." class="btn btn-nav c-main">Назад</a>

<!-- Группа кнопок -->
<div class="btn-group">
  <a href="..." class="btn btn-action c-main">Основная</a>
  <a href="..." class="btn btn-nav c-main">Вторичная</a>
</div>
```

Класс `c-*` устанавливает `--theme-accent`. Все доступные: `c-main`, `c-rag`, `c-toc`, `c-ai`, `c-bim`, `c-fun`, `c-waf`.

### 4.2 Карточки статей (article-cards-grid)

```html
<!-- Светлые карточки (для статей/ссылок) -->
<div class="article-cards-grid">
  <a href="..." class="article-card c-main">
    <div class="article-card__prompt">chernetchenko.pro<span class="cli-only">&gt;_</span></div>
    <div class="article-card__cmd"><span class="cli-only">cd </span>rag</div>
    <div class="article-card__title">Заголовок</div>
    <div class="article-card__desc">Описание</div>
  </a>
</div>

<!-- Цветные карточки (для навигации по подсайтам) -->
<div class="article-cards-grid">
  <a href="..." class="article-card colored c-rag">
    <!-- те же внутренние элементы -->
  </a>
</div>
```

### 4.3 Контентные блоки (callouts)

```html
<div class="content-block block-info">
  <div class="content-block-header"><span class="cli-only">[INFO]</span> Заголовок</div>
  <p>Текст блока</p>
</div>

<!-- Варианты: block-info (синий), block-warn (жёлтый), 
              block-success (зелёный), block-error (красный) -->
```

### 4.4 Таблица

```html
<div class="terminal-table-wrapper">
  <table class="terminal-table">
    <thead>
      <tr>
        <th>Колонка 1</th>
        <th>Колонка 2</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Значение</td>
        <td>Значение</td>
      </tr>
    </tbody>
  </table>
</div>
```

### 4.5 Секция с заголовком

Стиль `section-head` (полоса + градиент справа) уже написан в index.php — его можно использовать на любой странице добавив в `<style>`:

```css
.section-head {
  font-family: var(--font-code);
  font-size: 1rem;
  font-weight: 700;
  margin: 0 0 28px;
  color: var(--text-main);
  text-transform: uppercase;
  letter-spacing: .06em;
  display: flex;
  align-items: center;
  gap: 12px;
}
.section-head::after {
  content: "";
  flex: 1;
  height: 1px;
  background: linear-gradient(to right, var(--border), transparent);
}
```

```html
<h2 class="section-head">Заголовок секции</h2>
```

### 4.6 404 / Пустые состояния

```html
<div class="empty-state-404 c-ai">
  <div class="error-code">404</div>
  <div class="error-msg">
    <span class="cli-only">bash: cd: /путь: </span>Не найдено
  </div>
  <div class="btn-group" style="justify-content:center">
    <a href="/" class="btn btn-action c-main">На главную</a>
  </div>
</div>
```

### 4.7 KPI-карточки (для аналитических страниц)

```html
<div class="kpi-grid">
  <div class="kpi-card">
    <div class="kpi-header">Метрика</div>
    <div class="kpi-value">42</div>
    <div class="kpi-label">Описание метрики</div>
  </div>
</div>
```

### 4.8 Хлебные крошки

```html
<nav class="breadcrumbs">
  <a href="/" class="cli-only">~/</a>
  <a href="/" class="mgmt-only">Главная</a>
  <span class="sep">/</span>
  <span class="current">Текущая страница</span>
</nav>
```

---

## 5. Дуальный режим: cli-only / mgmt-only

Это ключевая механика UI Kit. **Всегда используй её** при написании любого контента.

```html
<!-- Видно только в режиме Разработчик (по умолчанию) -->
<span class="cli-only">cd /section</span>

<!-- Видно только в режиме Менеджер -->
<span class="mgmt-only">Перейти в раздел</span>

<!-- Обычно используются вместе: -->
<span class="cli-only">&gt;_ </span><span class="mgmt-only">→ </span>Действие
```

**Правила применения:**

| Элемент | cli-only | mgmt-only |
|---|---|---|
| Команды навигации (`cd`, `~/`, `grep`) | ✅ | ❌ |
| Человекочитаемые метки разделов | ❌ | ✅ |
| Префиксы `>_`, `[INFO]`, `[ERROR]` | ✅ | ❌ |
| Иконки (эмодзи как навигация) | ❌ | ✅ |
| Технические термины в подписях | ✅ | ❌ |
| Бизнес-термины в подписях | ❌ | ✅ |

---

## 6. Типовой page wrapper

Все страницы используют единый паттерн обёртки. Размеры адаптивны:

```css
.page-wrapper {
  max-width: 900px;   /* или 1140px для широкого контента */
  margin: 0 auto;
  padding: 48px 48px 80px;
}
@media (max-width: 900px) { .page-wrapper { padding: 32px 28px 64px; } }
@media (max-width: 600px) { .page-wrapper { padding: 20px 16px 48px; } }
```

Для страниц с широким контентом (таблицы, гриды):
```css
.page-wrapper-wide {
  max-width: 1140px;
  margin: 0 auto;
  padding: 48px 48px 80px;
}
```

---

## 7. Типовая карточка с OVC-стилем

Паттерн карточки используется везде на сайте. Копируй и адаптируй:

```css
.my-card {
  background: var(--bg-secondary);
  border: 1px solid var(--border);
  border-radius: var(--r-lg);
  padding: 24px;
  position: relative;
  overflow: hidden;
  transition: transform var(--ease), border-color var(--ease), box-shadow var(--ease);
}
/* Цветная полоса сверху */
.my-card::before {
  content: "";
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 2px;
  background: var(--c-main); /* или var(--accent), или конкретный --c-* */
}
.my-card:hover {
  transform: translateY(-3px);
  border-color: var(--c-main);
  box-shadow: var(--sh-md);
}
```

---

## 8. Работа с go-cms: CMS Loop и статьи

Если страница выводит статьи из `content/*.md`, используй `getArticles()`:

```php
<?php
require_once __DIR__ . '/lib/frontmatter.php';
require_once __DIR__ . '/lib/views.php';

$articles = getArticles('main', 'section-name', false);
// Параметры: site ('main'|'waf'|'toc'|'fun'), section ('' = все), includeDrafts
?>

<?php foreach ($articles as $art): ?>
<?php $m = $art['meta']; ?>
<a href="/article.php?slug=<?= htmlspecialchars($m['slug'], ENT_QUOTES, 'UTF-8') ?>"
   class="art-card">
  <div class="art-meta"><?= htmlspecialchars($m['section'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
  <h3 class="art-title"><?= htmlspecialchars($m['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></h3>
  <p class="art-desc"><?= htmlspecialchars($m['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
</a>
<?php endforeach; ?>
```

**Поля frontmatter статьи:**

| Поле | Тип | Описание |
|---|---|---|
| `title` | string | Заголовок |
| `slug` | string | URL-идентификатор |
| `site` | string | `main`, `waf`, `toc`, `fun` |
| `section` | string | Раздел (для фильтрации) |
| `date` | string | Дата `YYYY-MM-DD` |
| `tags` | array | `[tag1, tag2]` |
| `draft` | bool | `true` = скрыта |
| `badge` | string | `new`, `beta`, `wip`, `soon`, `hot` |
| `stub` | bool | `true` = заглушка (не открывается) |
| `description` | string | SEO-описание |

---

## 9. Работа с JSON-контентом (index.php паттерн)

Главная страница управляется через `config/main_layout.json`. При создании новых JSON-driven страниц следуй тому же паттерну:

```php
<?php
$layoutFile = __DIR__ . '/config/my_page.json';
$data = [];
if (file_exists($layoutFile)) {
    $decoded = json_decode(file_get_contents($layoutFile), true);
    if (is_array($decoded)) $data = $decoded;
}

// Fallback если JSON не найден
if (empty($data)) {
    // показать заглушку или дефолтный контент
}
?>
```

Структура JSON файла — свободная, но рекомендуется:
```json
{
  "meta": { "title": "...", "description": "..." },
  "sections": [ { "id": "...", "type": "...", "visible": true } ]
}
```

---

## 10. Счётчик просмотров

На страницах статей подключай через `lib/views.php`:

```php
require_once __DIR__ . '/lib/views.php';

// Инкремент при загрузке страницы
incrementView($slug);

// Получить число просмотров
$count = getViewCount($slug);

// Получить все просмотры (для листингов)
$viewCounts = getAllViewCounts();
$count = (int)($viewCounts[$slug] ?? 0);
```

Счётчик хранится в `content/_views.json` (flat-file, `flock`-safe).

---

## 11. Безопасность вывода данных

**Всегда** экранируй данные перед выводом в HTML:

```php
// Для атрибутов и текста
echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

// Для URL
echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8');

// Для JSON в JS
echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);

// Для slugов
$slug = preg_replace('/[^a-z0-9\-_]/i', '', $rawSlug);
```

---

## 12. Чеклист переноса страницы на OVC DS

Для каждой новой/переносимой страницы:

- [ ] Объявлены `$siteId` и `$pageTitle` перед `include 'header.php'`
- [ ] Подключены `header.php` и `footer.php`
- [ ] SEO-мета после include header.php
- [ ] Inline-стили используют `var(--c-*)`, `var(--bg-*)`, `var(--text-*)`, `var(--border)`, `var(--r-*)`, `var(--sh-*)`
- [ ] Нет хардкодных цветов (`#1a4fa0`, `#fff`, `#333`) — только токены
- [ ] Нет инлайн-шрифтов (Google Fonts, Golos, JetBrains) — только `var(--font-code)` и `var(--font-ui)`
- [ ] Дуальный режим: в интерактивных элементах стоят `cli-only` / `mgmt-only`
- [ ] Кнопки используют классы `btn btn-action c-*` или `btn btn-nav c-*`
- [ ] Карточки используют токены и паттерн `::before` для цветной полосы
- [ ] 404 / пустые состояния используют `.empty-state-404`
- [ ] Обновлён `MIGRATION.md` — добавлена запись о переведённой странице

---

## 13. Что НЕ делать

- **Не подключать** сторонние шрифты (Google Fonts, Яндекс.Шрифты) — они уже не нужны
- **Не создавать** свои CSS-переменные с именами типа `--my-color` если уже есть подходящий токен
- **Не писать** `background: #181b1f` — только `var(--bg-main)`
- **Не трогать** `03_components.css` ради page-specific стилей — пиши в `<style>` на странице
- **Не дублировать** хедер/футер HTML — они в `header.php` / `footer.php`
- **Не объявлять** `<!DOCTYPE html>`, `<html>`, `<head>`, `<body>` — они в header.php
- **Не закрывать** `</body>`, `</html>` — это делает footer.php
- **Не использовать** `background: #fff` для карточек — только `var(--bg-secondary)`
- **Не ставить** `border-radius: 8px` хардкодом — только `var(--r-sm)` / `var(--r-md)` / `var(--r-lg)`

---

## 14. Быстрый старт: шаблон новой страницы

```php
<?php
/**
 * my-page.php — Моя страница v1.0 OVC Design System
 */
$siteId    = 'main';
$pageTitle = 'Название страницы — Олег Чернетченко';
include 'header.php';
?>
<meta name="description" content="SEO описание страницы.">
<link rel="canonical" href="https://chernetchenko.pro/my-page.php">
<meta property="og:title"       content="<?= htmlspecialchars($pageTitle) ?>">
<meta property="og:description" content="SEO описание страницы.">
<meta property="og:type"        content="website">

<style>
.my-page { max-width: 900px; margin: 0 auto; padding: 48px 48px 80px; }
@media (max-width: 900px) { .my-page { padding: 32px 28px 64px; } }
@media (max-width: 600px) { .my-page { padding: 20px 16px 48px; } }

.my-page__hero { padding: 0 0 32px; border-bottom: 1px solid var(--border); margin-bottom: 40px; }
.my-page__hero .sub { font-family: var(--font-code); font-size: .9rem; color: var(--c-main); text-transform: uppercase; letter-spacing: .06em; font-weight: 700; margin-bottom: 12px; }
.my-page__hero h1   { font-family: var(--font-code); font-size: clamp(1.8rem,4vw,2.8rem); font-weight: 700; color: var(--text-main); margin: 0 0 14px; line-height: 1.15; }
.my-page__hero .desc{ font-size: .97rem; color: var(--text-muted); max-width: 650px; line-height: 1.75; }

.section-head { font-family: var(--font-code); font-size: 1rem; font-weight: 700; margin: 0 0 28px; color: var(--text-main); text-transform: uppercase; letter-spacing: .06em; display: flex; align-items: center; gap: 12px; }
.section-head::after { content: ""; flex: 1; height: 1px; background: linear-gradient(to right, var(--border), transparent); }
</style>

<div class="my-page">

  <div class="my-page__hero">
    <div class="sub"><span class="cli-only">&gt;_ </span>Раздел</div>
    <h1>Название<br>страницы</h1>
    <p class="desc">Короткое описание для пользователя.</p>
  </div>

  <h2 class="section-head">Секция 1</h2>

  <div class="content-block block-info">
    <div class="content-block-header"><span class="cli-only">[INFO]</span> Информация</div>
    <p>Текст блока.</p>
  </div>

  <div class="btn-group">
    <a href="/" class="btn btn-action c-main">
      <span class="cli-only">~/ </span><span class="mgmt-only">→ </span>На главную
    </a>
  </div>

</div>

<?php include 'footer.php'; ?>
```

---

## 15. Справочник токенов для быстрого копирования

```css
/* Фоны */
var(--bg-main)        /* основной фон */
var(--bg-secondary)   /* фон карточек, хедера, футера */
var(--bg-tertiary)    /* самый тёмный фон, инпуты */

/* Текст */
var(--text-main)      /* основной текст */
var(--text-muted)     /* вторичный текст */
var(--text-dim)       /* мета-информация, подписи */

/* Границы */
var(--border)         /* основная граница */
var(--border-sub)     /* тонкая граница между элементами */

/* Цвета подсайтов */
var(--c-main)         /* синий #2a629a — основной */
var(--c-rag)          /* жёлтый #c29922 — RAG */
var(--c-toc)          /* зелёный #5a9c42 — ТОС */
var(--c-ai)           /* красный #b02a2a — ИИ/WAF */
var(--c-bim)          /* бирюзовый #178a99 — BIM */
var(--c-fun)          /* фиолетовый #4a2180 — FUN */
var(--c-tg)           /* голубой #24a1de — Telegram */
var(--c-waf)          /* = --c-ai */

/* Радиусы */
var(--r-sm)   /* 6px  — кнопки, бейджи */
var(--r-md)   /* 10px — callout-блоки, таблицы */
var(--r-lg)   /* 16px — карточки */
var(--r-xl)   /* 24px — крупные блоки */

/* Тени */
var(--sh-sm)  /* лёгкая */
var(--sh-md)  /* средняя — hover */
var(--sh-lg)  /* сильная — модалки */

/* Шрифты */
var(--font-code)  /* Courier New — терминальный */
var(--font-ui)    /* system-ui — читаемый */

/* Анимация */
var(--ease)   /* 200ms ease — все transitions */
```

---

*Документ обновлён: 2026-05 | Источник: исходные коды + DEV_LOG.md*  
*Для переноса подсайта — см. `VSCODE_PROMPT.md` и `SUBSITE_MIGRATION.md`*

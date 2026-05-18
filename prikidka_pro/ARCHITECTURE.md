# go-cms — Архитектура и логика движка

> Восстановлено по исходным кодам. Дев-лог утерян, этот документ — его замена.

---

## Структура проекта

```
public_html_01/
├── index.php               — Главная страница (JSON-driven)
├── article.php             — Шаблон статьи (Markdown → HTML)
├── networking.php          — Календарь мероприятий
├── speech.php              — Выступления
├── 404.php                 — Страница ошибки
├── header.php              — Единый хедер (OVC DS v3.0)
├── footer.php              — Единый футер
├── config.php              — Конфигурация (в .gitignore!)
│
├── lib/
│   ├── frontmatter.php     — Парсер YAML front matter + getArticles()
│   ├── views.php           — Счётчик просмотров + бейджи/стабы
│   ├── sites.php           — Динамический список подсайтов
│   ├── seo.php             — Хелпер renderSeoMeta()
│   ├── brute_force.php     — Защита от перебора (для админки)
│   └── openrouter.php      — Клиент OpenRouter API
│
├── config/
│   ├── main_layout.json    — Структура главной страницы (JSON)
│   ├── seo_overrides.json  — Переопределения SEO по файлам
│   ├── ai_prompts.json     — Промпты ИИ-ассистентов
│   └── sections.json       — Разделы (для CMS)
│
├── content/                — Markdown-статьи (main сайт)
│   └── _views.json         — Счётчик просмотров (flat-file)
│
├── admin/
│   ├── index.php           — Панель управления (v7.5 Engine Mode)
│   ├── config.php          — Конфиг админки (ADMIN_PASSWORD, ENCRYPTION_KEY)
│   ├── auth.php            — Авторизация
│   ├── article.php         — Редактор (устаревший, заменён CMS API)
│   ├── sections.json       — Разделы сайтов
│   ├── links.json          — Ссылки
│   └── api/
│       ├── save_article.php    — Сохранение MD-статьи
│       ├── get_article.php     — Чтение статьи/PHP-файла
│       ├── list_articles.php   — Дерево файлов (MD + PHP всех сайтов)
│       ├── save_php.php        — Сохранение PHP-файла шаблона
│       ├── layout.php          — CRUD для main_layout.json
│       ├── sections.php        — Разделы (динамические сайты)
│       ├── scan_php.php        — SEO-сканер PHP-файлов
│       ├── format.php          — AI-форматирование через OpenRouter
│       ├── settings.php        — Настройки + шифрование AES-256
│       ├── prompts.php         — CRUD для ai_prompts.json
│       ├── digest_action.php   — Запуск дайджеста + лог
│       └── links.php           — CRUD для links.json
│
├── digest/
│   ├── index.php           — Публичная страница дайджеста
│   ├── core/
│   │   ├── Config.php      — Загружает config.php + константы дайджеста
│   │   ├── Db.php          — Singleton PDO (сокет → TCP fallback)
│   │   ├── AiClient.php    — AI-клиент (OpenRouter, primary/fallback1/fallback2)
│   │   └── sources.json    — Источники для коллекторов
│   ├── collectors/         — Коллекторы новостей (RSS, Telegram, etc.)
│   ├── api/                — API дайджеста
│   ├── admin/              — Внутренняя админка дайджеста
│   ├── cache/rss/          — Кэш RSS-запросов
│   └── logs/               — Логи сборщика
│
└── frontend/
    └── ui-kit/             — OVC Design System v3.0
        ├── 00_manifest.md
        ├── css/
        │   ├── 01_tokens.css
        │   ├── 02_base.css
        │   └── 03_components.css
        └── js/
            ├── 04_components.js
            └── 05_interactions.js
```

---

## Ключевые потоки данных

### 1. Главная страница (index.php)
```
config/main_layout.json
    └─→ index.php читает JSON
        ├─→ hero: title_line1, title_line2, subtitle, description
        ├─→ about: columns[] с items / items_links
        ├─→ nav_chips: якорная навигация
        └─→ sections[]:
            ├─ dev_grid   → article-card colored (c-rag/c-waf/c-toc/c-fun)
            ├─ net_grid   → net-card
            ├─ tool_box   → tool-box-wrap + btn btn-action c-bim
            └─ cms_loop   → getArticles() → art-card
```

### 2. Статьи (article.php)
```
GET /article.php?slug=my-article
    └─→ parseArticle(content/my-article.md)
        ├─→ YAML front matter → $meta
        │   (title, slug, site, section, date, tags, draft, badge, stub)
        └─→ body → Parsedown → $htmlContent
```

### 3. CMS / Редактор
```
Админка /admin/?tab=cms (Глобальный Проводник)
    └─→ api/list_articles.php
        ├─→ сканирует content/*.md каждого сайта  → [MD]
        └─→ сканирует корень сайта *.php          → [PHP]
    
    Клик [MD]:  api/get_article.php → EasyMDE
    Клик [PHP]: api/get_article.php → textarea (зелёный текст)
    
    Сохранить [MD]:  api/save_article.php  → content/{slug}.md
    Сохранить [PHP]: api/save_php.php      → {site_root}/{file}.php
```

### 4. Динамические сайты (Engine Mode)
```
lib/sites.php → get_dynamic_sites()
    └─→ сканирует /Users/chernetchenko/Code/SITE_F/
        ищет паттерн: {id}_chernetchenko_pro/
        возвращает: ['main', 'fun', 'toc', 'waf'] (main первый)
```

### 5. Дайджест
```
digest/core/Config.php   — загружает config.php, определяет константы
digest/core/Db.php       — Singleton PDO
    └─→ MySQL olegcherne_dig @ 127.0.0.1:3306
        таблицы: digest_events, digest_ai_log,
                 digest_daily_summary, admin_settings

Сбор: admin/api/digest_action.php?action=run (POST)
Лог:  admin/api/digest_action.php?action=log (GET)
```

### 6. Настройки и шифрование
```
admin/api/settings.php
    GET  → читает admin_settings из MySQL, дешифрует AES-256-CBC
    POST → шифрует AES-256-CBC, сохраняет в MySQL

Ключ шифрования:
    1. ENCRYPTION_KEY из admin/config.php (>= 32 символа)
    2. Fallback: hash('sha256', ADMIN_PASSWORD)

Хранимые ключи: openrouter_key, openrouter_model,
                github_token, telegram_token, digest_admin_pass
```

---

## Схема БД (MySQL: olegcherne_dig)

| Таблица | Назначение |
|---|---|
| `digest_events` | Новости/события (title, url, source, category, ai_summary) |
| `digest_ai_log` | Лог вызовов ИИ (model, tokens, response_time, status) |
| `digest_daily_summary` | Дневные сводки (summary_text, items_count) |
| `admin_settings` | Зашифрованные настройки (openrouter_key и др.) |

Таблицы создаются автоматически через `Db::initTables()`.

---

## Мультисайтовость

| Сайт | Домен | Контент | Цвет |
|---|---|---|---|
| `main` | chernetchenko.pro | `content/*.md` | `--c-main` (#1a4fa0) |
| `waf`  | waf.chernetchenko.pro | PHP-шаблоны | `--c-waf` = `--c-ai` |
| `toc`  | toc.chernetchenko.pro | PHP-шаблоны | `--c-toc` (#b8860b) |
| `fun`  | fun.chernetchenko.pro | PHP-шаблоны | `--c-fun` (#4a2180) |

**Важно:** только `main` использует MD-статьи через `getArticles()`.
WAF/TOC/FUN — PHP-файлы с собственной структурой.

Пути подсайтов определены в `config.php`:
```php
define('SITE_PATHS', json_encode([
    'main' => $_base . '/public_html',
    'waf'  => $_base . '/waf_chernetchenko_pro/public_html',
    'toc'  => $_base . '/toc_chernetchenko_pro/public_html',
    'fun'  => $_base . '/fun_chernetchenko_pro/public_html',
]));
```

---

## Безопасность

- **Авторизация**: `$_SESSION['admin']` — проверяется во всех API
- **Брутфорс**: `lib/brute_force.php` — блокировка по IP
- **Шифрование**: AES-256-CBC для API-ключей в БД
- **Пути**: `realpath()` проверка в `save_php.php`
- **JSON-ответы**: `error_reporting(0)` во всех API (no PHP warnings в ответе)
- **Валидация slug**: `preg_replace('/[^a-z0-9\-_]/i', '', $slug)`

---

## Зависимости

| Пакет | Где используется |
|---|---|
| Parsedown (Composer) | article.php — Markdown → HTML |
| EasyMDE (CDN) | admin/index.php — MD-редактор |
| PDO MySQL | digest/ — работа с БД |
| OpenRouter API | format.php, AiClient.php — AI |

---

## Roadmap (из README + DEV_LOG)

- [ ] Редизайн админки: Terminal Dark (--accent: #00ff88)
- [ ] Rate-limiting в авторизацию (brute_force.php уже есть)
- [ ] Кеширование листинга статей (getArticles() сейчас без кеша)
- [ ] Docker-контейнеризация
- [ ] speech.php перевод на OVC DS (в процессе)
- [ ] 404.php перевод на OVC DS
- [ ] Перевод toc_chernetchenko_pro на OVC DS

---

## Локальная разработка

```bash
cd /Users/chernetchenko/Code/SITE_F/public_html_01
php -S 127.0.0.1:8000
# → http://127.0.0.1:8000

# MySQL (Homebrew):
# host: 127.0.0.1:3306, db: olegcherne_dig, user: root, pass: (пустой)
```

---

*Восстановлено: <?= date('Y-m-d') ?> | Источник: исходные коды + DEV_LOG.md*

# Инструкция по переносу подсайта на go-cms + OVC Design System

> Версия: 2.0 | Дата: 2026-05  
> Для кого: **Ты** — что делать руками  
> Рядом лежит: `VSCODE_PROMPT.md` — промпт для VS Code / Claude / Cursor

---

## Логика процесса

```
Скачать подсайт → Открыть в VS Code с промптом → ИИ делает перенос → Залить обратно
```

Твоя задача: скачать, запустить ИИ, проверить, залить.  
Задача ИИ: прочитать промпт и выполнить перенос по правилам.

---

## Шаг 1: Подготовка

### Что нужно иметь локально

```
/Code/SITE_F/
├── public_html_01/       ← main-сайт (go-cms ядро + UI Kit)
│   ├── header.php
│   ├── footer.php
│   ├── frontend/ui-kit/  ← OVC Design System v3.0
│   └── ...
└── [имя]_chernetchenko_pro/   ← подсайт для переноса
    └── public_html/
```

Если папки подсайта нет локально — скачай с сервера:

```bash
# Через FTP/SFTP или SSH
scp -r user@server:/home/o/olegcherne/waf_chernetchenko_pro ./
```

### Актуальные домены и siteId

| Подсайт | Домен | `$siteId` | Цвет OVC |
|---|---|---|---|
| main | ovc.me | `main` | `--c-main` (#2a629a) |
| waf | ai.ovc.me | `waf` | `--c-waf` = `--c-ai` (#b02a2a) |
| rag | rag.ovc.me | `rag` | `--c-rag` (#c29922) |
| toc | toc.ovc.me | `toc` | `--c-toc` (#5a9c42) |
| fun | fun.ovc.me | `fun` | `--c-fun` (#4a2180) |

---

## Шаг 2: Открыть в VS Code с промптом

1. Открой VS Code
2. Открой папку подсайта (`waf_chernetchenko_pro/public_html`)
3. Открой `VSCODE_PROMPT.md` из корня go-cms (рядом с этим файлом)
4. Скопируй содержимое `VSCODE_PROMPT.md` целиком
5. Открой Claude / Cursor / Copilot Chat в VS Code
6. Вставь промпт как первое сообщение
7. Допиши: `Подсайт: waf, домен: ai.ovc.me` (подставь свои значения)
8. Отправь и жди

ИИ должен сам:
- Прочитать все PHP-файлы подсайта
- Заменить старые стили и хедер/футер на OVC DS
- Не трогать логику и данные

---

## Шаг 3: Проверить локально

```bash
# Запустить main-сайт (нужен для header.php и UI Kit)
cd /Code/SITE_F/public_html_01
php -S 127.0.0.1:8000

# В браузере проверить подсайт через include пути
# Или запустить подсайт отдельно:
cd /Code/SITE_F/waf_chernetchenko_pro/public_html
php -S 127.0.0.1:8001
```

### Чек-лист проверки

- [ ] Хедер отображается (логотип, навигация, переключатели)
- [ ] Футер отображается
- [ ] Цвет акцента соответствует подсайту
- [ ] Светлая тема работает
- [ ] Режим dev/manager переключается
- [ ] Нет сломанных ссылок на CSS/JS
- [ ] Страницы открываются без PHP-ошибок
- [ ] Адаптив на мобильном (DevTools)

---

## Шаг 4: Залить обратно

```bash
# Через SCP
scp -r ./waf_chernetchenko_pro/public_html user@server:/home/o/olegcherne/waf_chernetchenko_pro/

# Или через FTP-клиент (FileZilla, Cyberduck)
# Или через git если настроен деплой
```

**Что НЕ заливать:**
- `config.php` (если есть локальный с тестовыми данными)
- `.DS_Store`
- `*.bak`

---

## Структура подсайта после переноса

Что должно быть в `public_html/` переведённого подсайта:

```
public_html/
├── header.php      ← включает main header.php через relative path
├── footer.php      ← включает main footer.php через relative path
├── index.php       ← $siteId = 'waf'; include '../../../public_html_01/header.php';
├── *.php           ← остальные страницы — аналогично
├── style.css       ← если есть старые стили, остаётся как fallback
└── ...             ← специфичные файлы подсайта (api, db, etc.)
```

Путь к main-сайту из подсайта:

```php
// Из waf_chernetchenko_pro/public_html/header.php
include __DIR__ . '/../../public_html_01/header.php';
```

---

## Частые ошибки

| Ошибка | Причина | Решение |
|---|---|---|
| Хедер не отображается | Неверный путь до `public_html_01` | Проверь относительный путь `../../public_html_01/` |
| Стили не применяются | UI Kit грузится из main, путь `/frontend/ui-kit/` | На локале нужен vhost или запуск main на 8000 |
| `$siteId` не определён | Забыл объявить до `include header.php` | Объявить `$siteId = 'waf'` первой строкой |
| Цвет акцента не тот | Неверный `$siteId` | Проверь таблицу доменов выше |
| PHP Warning о константах | Двойной `require_once config.php` | Убрать прямой `require_once`, оставить через Config.php |
| Светлая тема не работает | Старые хардкодные цвета в style.css | ИИ должен добавить адаптер-переменные в header.php подсайта |

---

## Подсайты — статус переноса

| Подсайт | Статус | Что сделано |
|---|---|---|
| main (ovc.me) | ✅ Готов | OVC DS v3.0, Layout Builder, CMS |
| rag (rag.ovc.me) | ✅ Готов | `rag_index.php`, `les.php`, JSON-driven |
| toc (toc.ovc.me) | ⚠️ Частично | header/footer OVC, calc.php ждёт |
| waf (ai.ovc.me) | ❌ Не начат | Нужна новая версия от тебя |
| fun (fun.ovc.me) | ❌ Не начат | — |

---

## Ссылки

- Репо: https://github.com/chernetchenko-dev/go-cms
- Промпт для ИИ: `VSCODE_PROMPT.md` (рядом с этим файлом)
- Токены OVC DS: `frontend/ui-kit/css/01_tokens.css`
- Пример переноса: `toc_chernetchenko_pro/public_html/header.php`

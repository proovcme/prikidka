# Проект: Сервис оценки стоимости и сроков проектирования (ТОС)

## Инструкция по запуску

### 1. Запуск локального PHP-сервера
Откройте первый терминал и выполните:
```bash
php -S localhost:8000
```

### 2. Запуск E2E-тестов с Playwright
Откройте второй терминал и выполните:
```bash
npm init -y
npm install -D @playwright/test
npx playwright install
npx playwright test tests/e2e/app.spec.js --headed
```

## Структура проекта
- `index.html` — главная страница с интерфейсом
- `style.css` — стили (дизайн-система: бежевый фон, темно-синие акценты)
- `app.js` — логика фронтенда (обработчики событий, AJAX-запросы)
- `db.php` — класс подключения к БД (PDO)
- `risk.php` — API для расчета экономики и ТОС-буфера
- `report.php` — генерация печатного отчета (PDF-style)
- `api_update_input.php` — API для обновления статуса исходных данных
- `api_update_task.php` — API для обновления сроков задач (график Ганта)
- `api_upload_csv.php` — API для загрузки ресурсов из CSV
- `src/TocCalculator.php` — чистый класс расчетов по ТОС
- `src/TaskValidator.php` — валидация дат задач
- `src/ResourceCsvParser.php` — парсер CSV с ресурсами
- `tests/` — PHPUnit-тесты и fixtures
- `tests/e2e/` — E2E-тесты Playwright
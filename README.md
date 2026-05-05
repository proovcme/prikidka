# Проект: Сервис оценки стоимости и сроков проектирования (ТОС)

## Описание

Веб-сервис для расчёта стоимости и сроков проектных работ на основе Теории Ограничений Голдратта (ТОС/CCPM).

**Основной сайт:** [toc.chernetchenko.pro](https://toc.chernetchenko.pro)

## Структура проекта

```
toc/
├── .gitignore              # Исключения для Git
├── README.md               # Этот файл
└── public_html/            # Корень веб-сервера
    ├── header.php          # Общий хедер (SEO, навигация, стили)
    ├── footer.php          # Общий футер
    ├── style.css           # Базовые стили дизайн-системы
    ├── index.php           # Главная страница (Манифест)
    ├── calc.php            # ПИР Калькулятор
    ├── toc.php             # О ТОС
    ├── iona.php            # Голдратт
    ├── why.php             # Зачем это?
    ├── dbr.php             # Анимации
    ├── poreshal.php        # Порешать
    ├── report.php          # Генерация отчёта
    ├── risk.php            # API расчёта рисков и ТОС-буфера
    ├── admin.php           # Админка
    ├── db.php              # Подключение к БД (не в Git)
    ├── db_config.php       # Конфиг БД (не в Git)
    ├── db.example.php      # Шаблон подключения к БД
    ├── db_config.example.php # Шаблон конфига БД
    ├── lib/
    │   └── ai.php          # AI-клиент (OpenRouter)
    ├── src/
    │   ├── TocCalculator.php      # Расчёты по ТОС
    │   ├── TaskValidator.php      # Валидация дат
    │   └── ResourceCsvParser.php  # Парсер CSV
    ├── api/                # API endpoints
    ├── projects/           # JSON проектов
    ├── prikidka/           # Модуль "Прикидка"
    │   ├── index.php       # Главная модуля
    │   ├── assets/
    │   │   ├── css/prikidka.css
    │   │   └── js/app.js
    │   └── backend/
    │       ├── api.php     # API контроллер
    │       └── init_db.sql # Скрипт создания БД
    └── ...
```

## Модуль "Прикидка"

Модуль предварительных расчётов проектных параметров.

### Разделы
- **Нагрузки** — расчёт нагрузок на конструкции
- **Стоимость** — предварительная оценка стоимости
- **Электричество** — расчёт электрических параметров
- **Тепло** — теплотехнические расчёты
- **Сроки** — оценка сроков проектирования

### База данных

Таблицы (см. `prikidka/backend/init_db.sql`):
- `directories` — справочники нормативных коэффициентов
- `analytics_log` — статистика расчётов инженеров

### API

Endpoint: `prikidka/backend/api.php`

Действия:
- `save_log` — сохранение лога расчёта
- `get_directory` — получение данных справочника

## Установка

### 1. Клонирование
```bash
git clone https://github.com/proovcme/prikidka.git
cd prikidka
```

### 2. Настройка конфигурации
```bash
cp public_html/db.example.php public_html/db.php
cp public_html/db_config.example.php public_html/db_config.php
```

Отредактируйте `db.php` и `db_config.php`, указав реальные данные подключения к БД.

### 3. Создание таблиц
```bash
mysql -u user -p database < public_html/prikidka/backend/init_db.sql
```

### 4. Запуск локального сервера
```bash
cd public_html
php -S localhost:8000
```

## Технологии

- **Backend:** PHP 7.4+ (без фреймворков)
- **Frontend:** Чистый HTML/CSS/JavaScript (без сборщиков)
- **База данных:** MySQL 8.0+ / MariaDB
- **AI:** OpenRouter API (опционально)

## Автор

**Олег Чернетченко**
- [chernetchenko.pro](https://chernetchenko.pro)
- [t.me/chernetchenko](https://t.me/chernetchenko)

## Лицензия

© 2026. Никакой магии. Только расчёт.
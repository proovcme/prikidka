<?php
// Массив с терминами для глоссария
// В будущем этот массив можно будет тянуть из базы через единую админку
$terms = [
    'RAG (Retrieval-Augmented Generation)' => 'Технология, которая дает нейросети доступ к внешним базам знаний. ИИ не просто выдумывает ответ, а ищет нужный документ и формулирует ответ на его основе.',
    'CRAG (Corrective RAG)' => 'Продвинутая версия RAG. Алгоритм не просто ищет информацию, но и оценивает ее релевантность. Если найденный документ не подходит, система корректирует поиск или обращается к внешним источникам.',
    'CSR (CRAG-Safe-RAG)' => 'Концепция безопасной работы с проектными данными. Главный принцип: информация без прохождения проверок C/S-RAG не считается точной и не выдается в итоговый ответ.',
    'AMSLRAG Machine' => 'Apple Mac mini Local RAG. Аппаратный комплекс для локального запуска ИИ-моделей. Обеспечивает полную изоляцию и безопасность проектных данных.',
    'Токен' => 'Минимальная смысловая единица, на которые нейросеть разбивает текст. Это может быть буква, слог или целое слово. Лимиты ИИ всегда измеряются в токенах.',
    'Векторная база данных' => 'Специальное хранилище, где текст переведен в математические координаты (векторы). Позволяет ИИ мгновенно находить смысловые совпадения в тысячах страниц ГОСТов и СП.',
    'Мультиагентность' => 'Система, где несколько разных ИИ работают вместе. Например, один агент ищет текст в чертеже, второй анализирует координаты, а третий проверяет итоговое качество ответа.',
    'LLM (Large Language Model)' => 'Большая языковая модель. Мозг системы, который понимает человеческую речь, анализирует найденные в базе нормы и пишет связный ответ.'
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAG и Технологии ИИ | База знаний</title>
    <style>
        :root {
            --bg-color: #f8f9fa;
            --text-color: #212529;
            --accent-color: #0d6efd;
            --card-bg: #ffffff;
            --border-color: #dee2e6;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
        }
        header {
            text-align: center;
            margin-bottom: 3rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
        }
        h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        .statement {
            font-size: 1.2rem;
            color: #6c757d;
        }
        .glossary-grid {
            display: grid;
            gap: 1.5rem;
        }
        .term-card {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .term-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--accent-color);
            margin-top: 0;
            margin-bottom: 0.5rem;
        }
        .term-desc {
            margin: 0;
        }
        .nav-links {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
        .btn {
            background-color: var(--accent-color);
            color: white;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: opacity 0.2s;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .btn-outline {
            background-color: transparent;
            color: var(--accent-color);
            border: 1px solid var(--accent-color);
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1>База знаний RAG</h1>
        <p class="statement">Условно Точная Надежная Изолированная Система Работы с Проектными Данными</p>
        <div class="nav-links">
            <a href="#glossary" class="btn">Глоссарий для нулевых</a>
            <a href="/textbook" class="btn btn-outline">Читать учебник</a>
        </div>
    </header>

    <main>
        <section id="glossary">
            <h2>Словарь терминов</h2>
            <p>Простые объяснения технологий, которые работают под капотом.</p>
            
            <div class="glossary-grid">
                <?php foreach ($terms as $term => $description): ?>
                    <div class="term-card">
                        <h3 class="term-title"><?= htmlspecialchars($term) ?></h3>
                        <p class="term-desc"><?= htmlspecialchars($description) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</div>

</body>
</html>
<?php
require_once 'db.php';
require_once 'src/TocCalculator.php';

$project_id = $_GET['project_id'] ?? 1;
$pdo = DB::getConnection();
$calc = new TocCalculator();

try {
    // Получаем данные проекта
    $stmt = $pdo->prepare("SELECT p.*, r.name as region_name, e.name as exp_name FROM projects p LEFT JOIN dict_regions r ON p.region_id = r.id LEFT JOIN dict_expertise e ON p.expertise_id = e.id WHERE p.id = ?");
    $stmt->execute([$project_id]);
    $project = $stmt->fetch();
    if (!$project) throw new Exception("Проект не найден");

    // Получаем риски (исходные данные)
    $stmt = $pdo->prepare("SELECT input_name, status FROM project_inputs WHERE project_id = ?");
    $stmt->execute([$project_id]);
    $inputs = $stmt->fetchAll();

    // Получаем ресурсы
    $stmt = $pdo->prepare("SELECT pr.qty, pr.daily_rate, dr.name as role_name FROM project_resources pr JOIN dict_roles dr ON pr.role_id = dr.id WHERE pr.project_id = ?");
    $stmt->execute([$project_id]);
    $resources = $stmt->fetchAll();

    // Стоимость задач
    $stmt = $pdo->prepare("SELECT SUM(pr.daily_rate * pr.qty * DATEDIFF(t.end_date, t.start_date)) as base_cost FROM tasks t JOIN project_resources pr ON t.resource_id = pr.id WHERE t.project_id = ?");
    $stmt->execute([$project_id]);
    $baseCost = $stmt->fetchColumn() ?: 0;

    // Расчет ТОС
    $riskResult = $calc->calculateRiskBuffer($inputs);
    $economics = $calc->calculateEconomics($baseCost, $riskResult['money'], $project['overhead_pct'], $project['inflation_pct'], $project['margin_pct']);

} catch (Exception $e) {
    die("Ошибка генерации отчета: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Отчет по проекту: <?= htmlspecialchars($project['name']) ?></title>
    <style>
        :root {
            --bg-color: #f5e6d3;
            --text-color: #1a365d;
            --accent-color: #d97706;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 20px;
        }
        .page {
            max-width: 210mm; /* A4 width */
            margin: 0 auto;
            background: #fff;
            padding: 20mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1, h2 { border-bottom: 2px solid var(--text-color); padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid var(--text-color); padding: 8px; text-align: left; }
        th { background-color: rgba(26, 54, 93, 0.1); }
        .total-row { font-weight: bold; font-size: 1.2em; background-color: rgba(217, 119, 6, 0.1); }
        .risk-item { color: var(--accent-color); font-weight: bold; }
        
        @media print {
            body { background: #fff; }
            .page { box-shadow: none; padding: 0; width: 100%; max-width: none; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
    </style>
</head>
<body>
    <div class="page">
        <h1>Паспорт проекта: <?= htmlspecialchars($project['name']) ?></h1>
        <p><strong>Заказчик:</strong> <?= htmlspecialchars($project['customer'] ?? 'Не указан') ?></p>
        <p><strong>Стадия:</strong> <?= htmlspecialchars($project['stage'] ?? 'Не указана') ?></p>
        <p><strong>Дата формирования:</strong> <?= date('d.m.Y H:i') ?></p>

        <h2>1. Финансовая сводка (Метод ТОС)</h2>
        <table>
            <tr><td>Базовая себестоимость ресурсов</td><td><?= number_format($economics['base_cost'], 2, ',', ' ') ?> руб.</td></tr>
            <tr><td>Накладные расходы (<?= $project['overhead_pct'] ?>%)</td><td><?= number_format($economics['base_cost'] * ($project['overhead_pct'] / 100), 2, ',', ' ') ?> руб.</td></tr>
            <tr><td>Инфляционные ожидания (<?= $project['inflation_pct'] ?>%)</td><td><?= number_format($economics['base_cost'] * ($project['inflation_pct'] / 100), 2, ',', ' ') ?> руб.</td></tr>
            <tr class="total-row"><td>Буфер рисков ТОС</td><td class="risk-item"><?= number_format($riskResult['money'], 2, ',', ' ') ?> руб.</td></tr>
            <tr class="total-row"><td>Итоговая стоимость (с маржой <?= $project['margin_pct'] ?>%)</td><td><?= number_format($economics['final_price'], 2, ',', ' ') ?> руб.</td></tr>
        </table>

        <h2>2. Реестр рисков (Обоснование буфера)</h2>
        <?php if (empty($riskResult['log'])): ?>
            <p>Рисков не выявлено. Исходные данные в наличии.</p>
        <?php else: ?>
            <table>
                <tr><th>Описание риска</th><th>Влияние (дни)</th><th>Влияние (руб)</th></tr>
                <?php foreach ($riskResult['log'] as $risk): ?>
                    <tr>
                        <td><?= htmlspecialchars($risk['description']) ?></td>
                        <td>+<?= $risk['impact_days'] ?></td>
                        <td><?= number_format($risk['impact_money'], 2, ',', ' ') ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td>ИТОГО БУФЕР:</td>
                    <td><?= $riskResult['days'] ?> дн.</td>
                    <td><?= number_format($riskResult['money'], 2, ',', ' ') ?> руб.</td>
                </tr>
            </table>
        <?php endif; ?>

        <h2>3. Задействованные ресурсы</h2>
        <table>
            <tr><th>Должность</th><th>Количество</th><th>Ставка в день</th></tr>
            <?php foreach ($resources as $res): ?>
                <tr>
                    <td><?= htmlspecialchars($res['role_name']) ?></td>
                    <td><?= $res['qty'] ?></td>
                    <td><?= number_format($res['daily_rate'], 2, ',', ' ') ?> руб.</td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (json_last_error() !== JSON_ERROR_NONE && !empty(file_get_contents('php://input'))) {
    echo json_encode(['error' => 'Invalid JSON input']);
    exit;
}

$action = $input['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'get_cost_directories':
        // TODO: Заменить на реальное подключение к БД
        // Пример запроса:
        // $pdo = new PDO('mysql:host=localhost;dbname=prikidka', 'user', 'pass');
        // $stmt = $pdo->prepare("SELECT category, name, value FROM directories WHERE category IN ('ncs_base', 'region_coef', 'pir_percent', 'fgis_index')");
        // $stmt->execute();
        // $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // $groupedData = [];
        // foreach ($data as $row) {
        //     $groupedData[$row['category']][] = ['name' => $row['name'], 'value' => (float)$row['value']];
        // }
        // echo json_encode($groupedData);

        // Заглушка (mock) для тестирования
        $mockData = [
            'ncs_base' => [
                ['name' => 'Жилое многоквартирное здание', 'value' => 85000],
                ['name' => 'Складской комплекс', 'value' => 45000],
                ['name' => 'Офисный центр', 'value' => 95000],
            ],
            'region_coef' => [
                ['name' => 'Москва', 'value' => 1.25],
                ['name' => 'Санкт-Петербург', 'value' => 1.15],
                ['name' => 'Казань', 'value' => 1.05],
            ],
            'pir_percent' => [
                ['name' => 'Жилое здание', 'value' => 4.5],
                ['name' => 'Складской комплекс', 'value' => 3.0],
                ['name' => 'Офисный центр', 'value' => 5.5],
            ],
            'fgis_index' => [
                ['name' => 'Индекс с 2022 на 2026', 'value' => 1.45],
                ['name' => 'Индекс с 2023 на 2026', 'value' => 1.25],
                ['name' => 'Индекс с 2024 на 2026', 'value' => 1.15],
            ],
            'electro_specific' => [
                ['name' => 'Жилое здание', 'value' => 25],
                ['name' => 'Офисный центр', 'value' => 40],
                ['name' => 'Склад', 'value' => 10],
            ],
            'electro_demand' => [
                ['name' => 'Жилое здание', 'value' => 0.8],
                ['name' => 'Офисный центр', 'value' => 0.7],
                ['name' => 'Склад', 'value' => 0.9],
            ],
            'heat_heating' => [
                ['name' => 'Жилое здание', 'value' => 60],
                ['name' => 'Офисный центр', 'value' => 70],
                ['name' => 'Склад', 'value' => 40],
            ],
            'heat_vent' => [
                ['name' => 'Жилое здание', 'value' => 10],
                ['name' => 'Офисный центр', 'value' => 45],
                ['name' => 'Склад', 'value' => 15],
            ],
            'water_daily_rate' => [
                ['name' => 'Жилое здание', 'value' => 250],
                ['name' => 'Офисный центр', 'value' => 15],
                ['name' => 'Склад', 'value' => 25],
            ],
            'timeline_design_base' => [
                ['name' => 'Жилое здание', 'value' => 3.0],
                ['name' => 'Офисный центр', 'value' => 4.0],
                ['name' => 'Склад', 'value' => 2.0],
            ],
            'timeline_design_mult' => [
                ['name' => 'Жилое здание', 'value' => 0.5],
                ['name' => 'Офисный центр', 'value' => 0.6],
                ['name' => 'Склад', 'value' => 0.2],
            ],
            'timeline_build_base' => [
                ['name' => 'Жилое здание', 'value' => 6.0],
                ['name' => 'Офисный центр', 'value' => 6.0],
                ['name' => 'Склад', 'value' => 4.0],
            ],
            'timeline_build_mult' => [
                ['name' => 'Жилое здание', 'value' => 1.0],
                ['name' => 'Офисный центр', 'value' => 1.2],
                ['name' => 'Склад', 'value' => 0.5],
            ],
            'pnr_cost_percent' => [
                ['name' => 'Электрика (ЭОМ)', 'value' => 0.8],
                ['name' => 'Вентиляция и кондиционирование (ОВ)', 'value' => 1.5],
                ['name' => 'Слаботочные системы и пожарная безопасность (СС и АПС)', 'value' => 1.0],
                ['name' => 'Диспетчеризация и автоматика (АК / BMS)', 'value' => 2.0],
            ],
            'pnr_time_base' => [
                ['name' => 'Электрика (ЭОМ)', 'value' => 0.5],
                ['name' => 'Вентиляция и кондиционирование (ОВ)', 'value' => 1.0],
                ['name' => 'Слаботочные системы и пожарная безопасность (СС и АПС)', 'value' => 0.5],
                ['name' => 'Диспетчеризация и автоматика (АК / BMS)', 'value' => 1.5],
            ],
        ];
        echo json_encode($mockData);
        break;

    default:
        echo json_encode(['error' => 'Unknown action']);
        break;
}
?>
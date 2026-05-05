<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Подключаем конфигурацию
require_once 'config.php';

// Проверка токена
$token = $_POST['token'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? '';
// Удаляем префикс "Bearer " если он есть
$token = str_replace('Bearer ', '', $token);

if (empty($token) || !hash_equals(SYNC_SECRET_KEY, $token)) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden: Invalid or missing token']);
    exit;
}

// Получаем данные из тела запроса
$input = json_decode(file_get_contents('php://input'), true);
if (json_last_error() !== JSON_ERROR_NONE && !empty(file_get_contents('php://input'))) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON input']);
    exit;
}

// Проверяем, что данные являются массивом
if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(['error' => 'Input must be a JSON array']);
    exit;
}

// TODO: Заменить на реальное подключение к БД
// Пример подключения:
// $pdo = new PDO('mysql:host=localhost;dbname=prikidka', 'user', 'pass');
// $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Подготовленный запрос для вставки или обновления
// Предполагается, что в таблице directories есть уникальный ключ по (category, name)
// или аналогичный механизм для ON DUPLICATE KEY UPDATE
// $stmt = $pdo->prepare("INSERT INTO directories (category, name, value, description) VALUES (?, ?, ?, ?)
//                         ON DUPLICATE KEY UPDATE value = VALUES(value), description = VALUES(description)");

$results = [];
$errors = [];

foreach ($input as $item) {
    // Проверяем наличие обязательных полей
    if (!isset($item['category']) || !isset($item['key_name']) || !isset($item['value'])) {
        $errors[] = "Missing required fields (category, key_name, value) in item: " . json_encode($item);
        continue;
    }

    $category = $item['category'];
    $key_name = $item['key_name'];
    $value = $item['value'];
    $description = $item['description'] ?? '';

    try {
        // TODO: Выполнить реальный запрос к БД
        // $stmt->execute([$category, $key_name, $value, $description]);
        
        // Заглушка для демонстрации
        $results[] = [
            'status' => 'success',
            'category' => $category,
            'key_name' => $key_name,
            'value' => $value,
            'message' => 'Record updated/inserted successfully (mock)'
        ];
    } catch (PDOException $e) {
        $errors[] = "Database error for item " . json_encode($item) . ": " . $e->getMessage();
    }
}

// Формируем ответ
$response = [
    'status' => empty($errors) ? 'success' : 'partial_success',
    'processed_count' => count($results),
    'error_count' => count($errors),
    'results' => $results,
    'errors' => $errors,
];

if (!empty($errors)) {
    $response['message'] = 'Some items failed to process';
}

echo json_encode($response);
?>
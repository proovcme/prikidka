<?php
/**
 * TOC.CHERNETCHENKO.PRO — api_projects.php
 * Серверное хранение слепков проектов (State)
 */

header('Content-Type: application/json; charset=utf-8');

$dir = __DIR__ . '/projects/';

// Создаем папку, если забыл создать вручную
if (!file_exists($dir)) {
    mkdir($dir, 0755, true);
}

// Получаем метод и действие
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// ──────────────────────────────────────────────────────────────
// 1. СОХРАНЕНИЕ ПРОЕКТА
// ──────────────────────────────────────────────────────────────
if ($method === 'POST' && $action === 'save') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!$data) {
        echo json_encode(['status' => 'error', 'message' => 'Пустые данные']);
        exit;
    }

    // Генерируем уникальный короткий ID (8 символов)
    $projectId = bin2hex(random_bytes(4)); 
    $filename = $dir . 'project_' . $projectId . '.json';

    // Сохраняем весь стейт
    if (file_put_contents($filename, json_encode($data, JSON_UNESCAPED_UNICODE))) {
        echo json_encode(['status' => 'success', 'id' => $projectId]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Не удалось записать файл']);
    }
    exit;
}

// ──────────────────────────────────────────────────────────────
// 2. ЗАГРУЗКА ПРОЕКТА
// ──────────────────────────────────────────────────────────────
if ($method === 'GET' && $action === 'load') {
    $id = preg_replace('/[^a-f0-9]/', '', $_GET['id'] ?? '');
    
    if (!$id) {
        echo json_encode(['status' => 'error', 'message' => 'ID не указан']);
        exit;
    }

    $filename = $dir . 'project_' . $id . '.json';

    if (file_exists($filename)) {
        $content = file_get_contents($filename);
        echo $content; // Отдаем JSON как есть
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Проект не найден']);
    }
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Неизвестное действие']);
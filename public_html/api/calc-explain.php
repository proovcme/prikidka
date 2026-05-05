<?php
/**
 * API endpoint for AI explanation of PIR calculation
 * Uses OpenRouter via TOC's AI client
 */

require_once __DIR__ . '/../lib/ai.php';
header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['result'])) {
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

$result = $data['result'] ?? '';
$params = $data['params'] ?? [];

$system = 'Ты — эксперт по ценообразованию проектных работ в России. ' .
          'Отвечай по-русски, кратко и профессионально. Без воды.';

$prompt = "Поясни результат расчёта ПИР:\n" .
          "Итоговая стоимость: {$result}\n" .
          "Параметры: " . json_encode($params, JSON_UNESCAPED_UNICODE) . "\n\n" .
          "Объясни в 2-3 предложениях что включает эта сумма и на что влияет.";

$explanation = tocAskAI($prompt, $system);

echo json_encode([
    'explanation' => $explanation ?: 'ИИ недоступен. Проверьте настройки API.',
]);
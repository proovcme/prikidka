<?php
// Отключаем лимит времени
set_time_limit(120);
ini_set('display_errors', 0);

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        echo json_encode(['error' => ['message' => 'PHP КРАШ: ' . $error['message']]]);
    }
});

header('Content-Type: application/json; charset=utf-8');

// ======================================================================================
// ⚙️ НАСТРОЙКИ ИИ (ВБИВАТЬ ТОЛЬКО СЮДА)
// ======================================================================================
$apiKey = getenv('OPENROUTER_API_KEY') ?: 'ТВОЙ_OPENROUTER_API_КЛЮЧ'; // Вставь свой ключ sk-or-v1-... или передай через env
$selectedModel = 'google/gemini-2.5-flash'; // Рекомендую flash для парсинга и чата
// ======================================================================================

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(['error' => ['message' => 'Пустой запрос от фронта']]);
    exit;
}

$messages = [];

// ➔ СЦЕНАРИЙ 3: Парсинг документа (ТЗ / Договор)
if (isset($data['action']) && $data['action'] === 'parse_doc') {
    $text = $data['text'] ?? '';
    
    $systemPrompt = "Ты строгий экстрактор данных. Твоя задача вытащить из текста документа названия компаний и объектов.
    Верни ТОЛЬКО валидный JSON без маркдауна, без тегов ```json и без других слов. 
    Формат: {\"client\": \"Название заказчика\", \"object\": \"Название объекта\"}.
    Если что-то не найдено, пиши \"Не найдено\".";
    
    $messages = [
        ['role' => 'system', 'content' => $systemPrompt],
        ['role' => 'user', 'content' => "Текст документа:\n" . $text]
    ];
}

// ➔ СЦЕНАРИЙ 2: Запрос из блока Экономики (Чат и Объяснение сметы)
else if (isset($data['params'])) {
    $userMessage = $data['message'] ?? 'Обоснуй эту смету перед заказчиком.';
    $params = $data['params'];
    $result = $data['result'] ?? '0';
    
    $contextData = "Смета ПИР: Итого = $result руб. ФОТ: {$params['fot']} руб, Коэффициенты: {$params['coeffs']}, Доп: {$params['extra']}, УП: {$params['mgmt']}, Риски: {$params['risk']}, Маржа: {$params['margin']}.";
    
    $systemPrompt = "Ты финансовый директор проектного бюро. Твоя задача - аргументированно объяснять смету, защищать стоимость перед заказчиком, опираясь на заложенные риски, управление и ТОС. Отвечай кратко, емко и убедительно на русском языке.";
    
    $messages = [
        ['role' => 'system', 'content' => $systemPrompt],
        ['role' => 'user', 'content' => "Данные сметы:\n" . $contextData . "\n\nВопрос заказчика / задача: " . $userMessage]
    ];
} else {
    echo json_encode(['error' => ['message' => 'Неизвестный формат запроса']]);
    exit;
}

// ➔ ОТПРАВКА В OPENROUTER
$payloadArray = [
    'model' => $selectedModel,
    'messages' => $messages,
    'temperature' => 0.1 // Низкая температура для более точного парсинга JSON
];

$payloadJson = json_encode($payloadArray, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

$ch = curl_init('https://openrouter.ai/api/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 60); // Даем больше времени на чтение документа
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . trim($apiKey),
    'Content-Type: application/json; charset=utf-8',
    'HTTP-Referer: https://toc.chernetchenko.pro', 
    'X-Title: PIR Service',
    'Content-Length: ' . strlen($payloadJson)
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadJson);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($response === false) {
    echo json_encode(['error' => ['message' => "cURL Error: $curlError"]], JSON_UNESCAPED_UNICODE);
    exit;
}

$responseData = json_decode($response, true);

if ($httpCode === 200 && isset($responseData['choices'][0]['message']['content'])) {
    $content = $responseData['choices'][0]['message']['content'];
    
    // Принудительно зачищаем маркдаун, если ИИ вернул код в блоке
    if (isset($data['action']) && $data['action'] === 'parse_doc') {
        $content = preg_replace('/```json\s*/', '', $content);
        $content = preg_replace('/```\s*/', '', $content);
        $content = trim($content);
    }

    // Унифицированный ключ для фронта
    $responseData['explanation'] = $content;
    echo json_encode($responseData, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode([
        'error' => [
            'message' => "HTTP $httpCode: " . ($responseData['error']['message'] ?? 'Неизвестная ошибка API')
        ]
    ], JSON_UNESCAPED_UNICODE);
}
?>
<?php
declare(strict_types=1);

/**
 * Централизованный вызов OpenRouter API.
 * Только бесплатные модели. Единая точка входа для всех подсайтов.
 *
 * @param string $prompt Текст запроса к LLM
 * @param int $timeout Таймаут в секундах (по умолчанию 20)
 * @return array Ответ API или массив с ошибкой ['error' => '...']
 */
function callOpenRouter(string $prompt, int $timeout = 20): array {
    // Подгружаем конфиг если константы ещё не определены
    if (!defined('OPENROUTER_KEY')) {
        $configPath = __DIR__ . '/../admin/config.php';
        if (is_file($configPath)) {
            require_once $configPath;
        }
    }

    // Проверка наличия ключа
    if (!defined('OPENROUTER_KEY') || !OPENROUTER_KEY) {
        return ['error' => 'OPENROUTER_KEY не задан в admin/config.php'];
    }

    // Защита: разрешены только бесплатные модели
    $model = defined('OPENROUTER_MODEL') ? OPENROUTER_MODEL : 'openrouter/free';
    if ($model !== 'openrouter/free' && !str_ends_with($model, ':free')) {
        return ['error' => 'Запрещено: используй только бесплатные модели (openrouter/free или model:free)'];
    }

    $payload = json_encode([
        'model'    => $model,
        'messages' => [['role' => 'user', 'content' => $prompt]]
    ], JSON_UNESCAPED_UNICODE);

    if ($payload === false) {
        return ['error' => 'Ошибка кодирования JSON'];
    }

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . OPENROUTER_KEY,
        'HTTP-Referer: https://chernetchenko.pro',
        'X-Title: Gospodin-Oformitel',
    ];

    $ctx = stream_context_create([
        'http' => [
            'method'        => 'POST',
            'header'        => implode("\r\n", $headers),
            'content'       => $payload,
            'timeout'       => $timeout,
            'ignore_errors' => true,
        ]
    ]);

    $url = 'https://openrouter.ai/api/v1/chat/completions';
    $raw = file_get_contents($url, false, $ctx);

    if ($raw === false) {
        return ['error' => 'Нет связи с OpenRouter'];
    }

    $data = json_decode($raw, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['error' => 'Ошибка разбора ответа API: ' . json_last_error_msg()];
    }

    if (isset($data['error'])) {
        $errMsg = $data['error']['message'] ?? 'Ошибка OpenRouter';
        return ['error' => $errMsg];
    }

    return $data;
}

/**
 * Извлекает текстовый ответ из массива response.
 *
 * @param array $response Ответ от callOpenRouter()
 * @return string Текст ответа или пустая строка при ошибке
 */
function openRouterText(array $response): string {
    if (isset($response['error'])) {
        return '';
    }
    return $response['choices'][0]['message']['content'] ?? '';
}
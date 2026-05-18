<?php
/**
 * AI client for TOC calculator
 * Uses same OpenRouter key from main config
 */

// Подключаем конфиг из корня проекта
$configPath = __DIR__ . '/../../../../config.php';
if (file_exists($configPath)) {
    require_once $configPath;
} else {
    // Пробуем другой путь (если структура отличается)
    $configPath = __DIR__ . '/../../../config.php';
    if (file_exists($configPath)) require_once $configPath;
}

if (!defined('OPENROUTER_KEY')) {
    define('OPENROUTER_KEY', '');
}
if (!defined('OPENROUTER_MODEL')) {
    define('OPENROUTER_MODEL', 'openrouter/free');
}

function tocAskAI(string $prompt, string $system = ''): string {
    if (!OPENROUTER_KEY) return '';
    
    $messages = [];
    if ($system) {
        $messages[] = ['role' => 'system', 'content' => $system];
    }
    $messages[] = ['role' => 'user', 'content' => $prompt];
    
    $payload = json_encode([
        'model'       => OPENROUTER_MODEL,
        'messages'    => $messages,
        'max_tokens'  => 500,
        'temperature' => 0.3,
    ], JSON_UNESCAPED_UNICODE);
    
    $ch = curl_init('https://openrouter.ai/api/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . OPENROUTER_KEY,
            'Content-Type: application/json',
            'HTTP-Referer: https://toc.chernetchenko.pro',
        ],
        CURLOPT_TIMEOUT        => 20,
    ]);
    
    $response = curl_exec($ch);
    $errno    = curl_errno($ch);
    $error    = curl_error($ch);
    curl_close($ch);
    
    if ($errno) return '';
    
    $data = json_decode($response, true);
    return $data['choices'][0]['message']['content'] ?? '';
}
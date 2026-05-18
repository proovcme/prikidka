<?php
declare(strict_types=1);
/**
 * Простая защита от брутфорса через файловый кеш.
 * Не требует БД, Redis или shell-доступа. Работает на shared-хостинге.
 * Хранит счётчики в sys_get_temp_dir() с атомарной записью.
 *
 * Содержит алиасы для обратной совместимости с admin/index.php:
 * - checkBruteForce()
 * - recordAttempt()
 * - clearBruteForce()
 * - BRUTE_MAX_ATTEMPTS
 */

define('BF_MAX_ATTEMPTS', 5);
define('BF_WINDOW',       300);
define('BF_LOCKOUT',      900);

// Алиас для константы из старого кода
define('BRUTE_MAX_ATTEMPTS', BF_MAX_ATTEMPTS);

/**
 * Получает реальный IP клиента (учитывает прокси)
 */
function bf_get_ip(): string {
    $raw = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    return preg_replace('/[^0-9a-f:.]/', '', explode(',', $raw)[0]);
}

/**
 * Возвращает путь к файлу кеша для IP
 */
function bf_key(string $ip): string {
    return sys_get_temp_dir() . '/bf_' . md5($ip) . '.json';
}

/**
 * Читает данные из файла кеша
 */
function bf_read_data(string $file): array {
    if (!file_exists($file)) return [];
    $content = @file_get_contents($file);
    return $content ? json_decode($content, true) : [];
}

/**
 * Проверяет, заблокирован ли текущий IP.
 * Возвращает массив: ['blocked' => bool, 'wait' => int, 'attempts' => int, 'remaining' => int, 'allowed' => bool]
 */
function bf_is_blocked(): array {
    $ip   = bf_get_ip();
    $file = bf_key($ip);
    $data = bf_read_data($file);
    $now  = time();

    // Активная блокировка
    if (!empty($data['blocked_until']) && $data['blocked_until'] > $now) {
        return [
            'blocked'   => true,
            'allowed'   => false,
            'wait'      => $data['blocked_until'] - $now,
            'attempts'  => 0,
            'remaining' => 0,
        ];
    }

    // Очистка просроченных попыток
    if (!empty($data['attempts'])) {
        $data['attempts'] = array_values(array_filter(
            $data['attempts'],
            fn($t) => ($now - $t) < BF_WINDOW
        ));
        @file_put_contents($file, json_encode($data), LOCK_EX);
    }

    $attempts = count($data['attempts'] ?? []);

    return [
        'blocked'   => false,
        'allowed'   => true,
        'wait'      => 0,
        'attempts'  => $attempts,
        'remaining' => max(0, BF_MAX_ATTEMPTS - $attempts),
    ];
}

/**
 * Регистрирует неудачную попытку входа
 */
function bf_record_failure(): void {
    $ip   = bf_get_ip();
    $file = bf_key($ip);
    $now  = time();
    $data = bf_read_data($file);

    $attempts = array_filter($data['attempts'] ?? [], fn($t) => ($now - $t) < BF_WINDOW);
    $attempts[] = $now;
    $data['attempts'] = array_values($attempts);

    if (count($data['attempts']) >= BF_MAX_ATTEMPTS) {
        $data['blocked_until'] = $now + BF_LOCKOUT;
        $data['attempts']      = [];
    }

    @file_put_contents($file, json_encode($data), LOCK_EX);
}

/**
 * Регистрирует успешный вход (очищает кеш для IP)
 */
function bf_record_success(): void {
    $file = bf_key(bf_get_ip());
    if (file_exists($file)) @unlink($file);
}

// === АЛИАСЫ ДЛЯ СОВМЕСТИМОСТИ С ADMIN/INDEX.PHP ===
// Не удалять! Используются в admin/index.php и toc/fun/admin.php

function checkBruteForce(string $ip): array {
    return bf_is_blocked();
}

function recordAttempt(string $ip): void {
    bf_record_failure();
}

function clearBruteForce(string $ip): void {
    bf_record_success();
}
<?php
session_start();

function checkAuth() {
    if (empty($_SESSION['toc_admin'])) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Доступ запрещен. Необходима авторизация (admin.php).']);
        exit;
    }
}

<?php
/**
 * API Controller
 */

header('Content-Type: application/json; charset=utf-8');

// Read POST input
$input = json_decode(file_get_contents('php://input'), true);

// Simple router
$action = $input['action'] ?? '';

switch ($action) {

    case 'save_log':
        // Stub: log saved
        echo json_encode([
            'status' => 'success',
            'message' => 'log saved'
        ]);
        break;

    case 'get_directory':
        // Stub: return empty data
        echo json_encode([
            'status' => 'success',
            'data' => []
        ]);
        break;

    default:
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Unknown action'
        ]);
        break;
}
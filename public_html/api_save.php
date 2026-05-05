<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !is_array($data)) {
    echo json_encode(["status" => "error", "message" => "Данные не получены"]);
    exit;
}

try {
    $db = DB::getConnection();

    // Добавляем колонку is_buffer если её нет (миграция на лету)
    try {
        $db->exec("ALTER TABLE tasks ADD COLUMN is_buffer TINYINT(1) DEFAULT 0");
    } catch (Exception $e) {
        // Колонка уже есть — игнорируем
    }

    $db->beginTransaction();
    $db->exec("DELETE FROM tasks");

    $stmt = $db->prepare(
        "INSERT INTO tasks (id, name, start_date, end_date, dependencies, resource_id, load_pct,
                            is_milestone, is_management, is_buffer, sort_order)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    foreach ($data as $index => $task) {
        $stmt->execute([
            $task['id'],
            $task['name'],
            $task['start'],
            $task['end'],
            isset($task['dependencies']) ? implode(',', (array)$task['dependencies']) : '',
            $task['resource']    ?? '',
            $task['utilization'] ?? 100,
            !empty($task['isMilestone'])  ? 1 : 0,
            !empty($task['isManagement']) ? 1 : 0,
            !empty($task['isBuffer'])     ? 1 : 0,
            $index
        ]);
    }

    $db->commit();
    echo json_encode(["status" => "success"]);

} catch (Exception $e) {
    if (isset($db)) $db->rollBack();
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>

<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

try {
    $db = DB::getConnection();
    $stmt = $db->query("SELECT * FROM tasks ORDER BY sort_order ASC");
    $rows = $stmt->fetchAll();

    $tasks = [];
    foreach ($rows as $row) {
        $tasks[] = [
            'id'           => $row['id'],
            'name'         => $row['name'],
            'start'        => $row['start_date'],
            'end'          => $row['end_date'],
            'dependencies' => $row['dependencies'] ? explode(',', $row['dependencies']) : [],
            'resource'     => $row['resource_id'],
            'utilization'  => (int)$row['load_pct'],
            'isMilestone'  => (bool)$row['is_milestone'],
            'isManagement' => (bool)$row['is_management'],
            'isBuffer'     => isset($row['is_buffer']) ? (bool)$row['is_buffer'] : false,
        ];
    }
    echo json_encode($tasks);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>

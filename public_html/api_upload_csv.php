<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';
require_once 'src/ResourceCsvParser.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['csv_file'])) {
    die(json_encode(['status' => 'error', 'message' => 'Файл не загружен']));
}

$file = $_FILES['csv_file'];
if ($file['error'] !== UPLOAD_ERR_OK) {
    die(json_encode(['status' => 'error', 'message' => 'Ошибка загрузки файла']));
}

try {
    $parser = new ResourceCsvParser();
    $resources = $parser->parse($file['tmp_name']);

    if (empty($resources)) {
        throw new Exception("Файл пуст или имеет неверный формат. Ожидается: Должность, Количество, Ставка");
    }

    $pdo = DB::getConnection();
    $projectId = $_POST['project_id'] ?? 1;

    // В транзакции удаляем старые ресурсы проекта и заливаем новые
    $pdo->beginTransaction();
    
    $stmt = $pdo->prepare("DELETE FROM project_resources WHERE project_id = ?");
    $stmt->execute([$projectId]);

    $stmtInsert = $pdo->prepare("INSERT INTO project_resources (project_id, role_id, qty, daily_rate) VALUES (?, ?, ?, ?)");
    
    $stmtCheckRole = $pdo->prepare("SELECT id FROM dict_roles WHERE name = ?");
    $stmtInsertRole = $pdo->prepare("INSERT INTO dict_roles (name, default_rate) VALUES (?, ?)");

    foreach ($resources as $res) {
        // Ищем роль в справочнике. Если нет - добавляем.
        $stmtCheckRole->execute([$res['role']]);
        $roleId = $stmtCheckRole->fetchColumn();
        
        if (!$roleId) {
            $stmtInsertRole->execute([$res['role'], $res['rate']]);
            $roleId = $pdo->lastInsertId();
        }
        
        $stmtInsert->execute([$projectId, $roleId, $res['qty'], $res['rate']]);
    }

    $pdo->commit();
    echo json_encode(['status' => 'success', 'message' => 'Ресурсы успешно обновлены']);

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
<?php
require_once 'db.php';
$db = DB::getConnection();
try {
    $db->exec("ALTER TABLE tasks ADD COLUMN is_buffer TINYINT(1) DEFAULT 0");
    echo "Migration is_buffer successful.\n";
} catch (Exception $e) {
    echo "Migration is_buffer failed or already applied: " . $e->getMessage() . "\n";
}

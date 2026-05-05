<?php
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    ['id' => 'Task_1', 'name' => 'Сбор исходных данных', 'start' => date('Y-m-d'), 'end' => date('Y-m-d', strtotime('+5 days')), 'progress' => 10, 'dependencies' => ''],
    ['id' => 'Task_2', 'name' => 'Разработка АР', 'start' => date('Y-m-d', strtotime('+6 days')), 'end' => date('Y-m-d', strtotime('+20 days')), 'progress' => 0, 'dependencies' => 'Task_1'],
    ['id' => 'Buffer_1', 'name' => '⚠️ Буфер проекта (ТОС)', 'start' => date('Y-m-d', strtotime('+21 days')), 'end' => date('Y-m-d', strtotime('+40 days')), 'progress' => 0, 'dependencies' => 'Task_2', 'custom_class' => 'bar-buffer']
]);
?>

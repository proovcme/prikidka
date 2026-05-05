<?php
header('Content-Type: application/json; charset=utf-8');
$rand = rand(10, 99) * 1000; 
echo json_encode(['status' => 'success', 'data' => ['financials' => ['base_cost' => 150000, 'risk_buffer_money' => 105000 + $rand, 'total_cost' => 255000 + $rand, 'final_price' => 306000 + $rand], 'timeline' => ['base_end_date' => date('Y-m-d', strtotime('+20 days')), 'risk_buffer_days' => 19], 'risks' => [['description' => 'Отсутствует/Неизвестно: ТУ', 'impact_days' => 5, 'impact_money' => 25000], ['description' => 'Отсутствует/Неизвестно: Спецусловия', 'impact_days' => 14, 'impact_money' => 80000]]]]);
?>

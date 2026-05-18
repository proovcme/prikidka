<?php
class TocCalculator {
    private array $riskWeights = [
        'нет_нужно' => ['days' => 5, 'money' => 25000],
        'неизвестно' => ['days' => 14, 'money' => 80000]
    ];

    public function calculateRiskBuffer(array $inputs): array {
        $bufferDays = 0;
        $bufferMoney = 0;
        $riskLog = [];

        foreach ($inputs as $input) {
            $status = $input['status'];
            if (isset($this->riskWeights[$status])) {
                $days = $this->riskWeights[$status]['days'];
                $money = $this->riskWeights[$status]['money'];
                $bufferDays += $days;
                $bufferMoney += $money;
                $riskLog[] = [
                    'type' => 'input_missing',
                    'description' => "Отсутствует/Неизвестно: {$input['input_name']}",
                    'impact_days' => $days,
                    'impact_money' => $money
                ];
            }
        }
        return ['days' => $bufferDays, 'money' => $bufferMoney, 'log' => $riskLog];
    }

    public function calculateEconomics(float $baseCost, float $bufferMoney, float $overheadPct, float $inflationPct, float $marginPct): array {
        $overhead = $baseCost * ($overheadPct / 100);
        $inflation = $baseCost * ($inflationPct / 100);
        $totalCost = $baseCost + $overhead + $inflation + $bufferMoney;
        $finalPrice = $totalCost * (1 + ($marginPct / 100));

        return [
            'base_cost' => round($baseCost, 2),
            'risk_buffer_money' => round($bufferMoney, 2),
            'total_cost' => round($totalCost, 2),
            'final_price' => round($finalPrice, 2)
        ];
    }
}
?>
<?php
class TaskValidator {
    public static function validateDates(string $start, string $end): bool {
        $startDate = strtotime($start);
        $endDate = strtotime($end);
        
        if (!$startDate || !$endDate) return false;
        if ($startDate > $endDate) return false;
        
        return true;
    }
}
?>
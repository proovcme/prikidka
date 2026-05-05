<?php
class ResourceCsvParser {
    public function parse(string $filePath): array {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new Exception("Файл не найден или недоступен для чтения");
        }

        $resources = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            // Пропускаем заголовки
            $firstRow = fgetcsv($handle, 1000, ',');
            
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                // Ожидаем формат: Должность, Кол-во, Ставка
                if (count($data) >= 3) {
                    $role = trim($data[0]);
                    $qty = (int)trim($data[1]);
                    $rate = (float)trim($data[2]);

                    // Простая валидация строки
                    if (!empty($role) && $qty > 0 && $rate >= 0) {
                        $resources[] = [
                            'role' => $role,
                            'qty' => $qty,
                            'rate' => $rate
                        ];
                    }
                }
            }
            fclose($handle);
        }
        return $resources;
    }
}
?>
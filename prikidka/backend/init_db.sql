-- ============================================================
-- Database Initialization Script
-- ============================================================

-- Create directories table (справочники)
CREATE TABLE IF NOT EXISTS directories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) NOT NULL,
    key_name VARCHAR(100) NOT NULL,
    value DECIMAL(10,4) NOT NULL,
    description TEXT,
    INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create analytics_log table (статистика)
CREATE TABLE IF NOT EXISTS analytics_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    module_name VARCHAR(50) NOT NULL,
    input_data JSON,
    result_data JSON
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
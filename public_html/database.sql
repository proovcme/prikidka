-- Таблица проектов
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    customer VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Таблица задач (синхронизирована с app.js)
CREATE TABLE IF NOT EXISTS tasks (
    id VARCHAR(50) PRIMARY KEY,
    project_id INT DEFAULT 1,
    name VARCHAR(255) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    dependencies VARCHAR(255) DEFAULT '',
    resource_id VARCHAR(255) DEFAULT '',
    load_pct INT DEFAULT 100,
    is_milestone TINYINT(1) DEFAULT 0,
    is_management TINYINT(1) DEFAULT 0,
    sort_order INT DEFAULT 0
);

-- Таблица рисков
CREATE TABLE IF NOT EXISTS risks (
    id VARCHAR(50) PRIMARY KEY,
    project_id INT DEFAULT 1,
    description TEXT,
    probability INT,
    impact INT,
    cost_impact DECIMAL(12,2)
);

-- Таблица исходных данных (для сохранения состояний чек-листа)
CREATE TABLE IF NOT EXISTS initial_data (
    id VARCHAR(50) PRIMARY KEY,
    project_id INT DEFAULT 1,
    name VARCHAR(255),
    status VARCHAR(50)
);
INSERT INTO dict_regions (name) VALUES ('Москва'), ('Казань'), ('Санкт-Петербург');
INSERT INTO dict_roles (name, default_rate) VALUES ('ГИП', 8000), ('Ведущий архитектор', 6000), ('BIM-координатор', 5500);

INSERT INTO projects (id, name, customer, region_id, stage, is_tim, margin_pct, overhead_pct, inflation_pct) 
VALUES (1, 'ТЦ Галактика', 'ООО ИнвестСтрой', 1, 'Проектная документация', 1, 15, 10, 5);

-- Добавляем исходные данные для срабатывания рисков ТОС
INSERT INTO project_inputs (project_id, input_name, status) VALUES 
(1, 'ГПЗУ', 'есть'),
(1, 'Технические условия на воду', 'нет_нужно'), -- Даст +5 дней и 25 000
(1, 'Спецусловия по пожарной безопасности', 'неизвестно'); -- Даст +14 дней и 80 000

INSERT INTO project_resources (id, project_id, role_id, qty, daily_rate) VALUES 
(1, 1, 1, 1, 8000),
(2, 1, 2, 2, 6000);

-- Задачи для диаграммы Ганта
INSERT INTO tasks (id, project_id, name, start_date, end_date, dependencies, resource_id) VALUES 
('Task_1', 1, 'Сбор исходных данных', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 5 DAY), '', 1),
('Task_2', 1, 'Разработка АР', DATE_ADD(CURDATE(), INTERVAL 6 DAY), DATE_ADD(CURDATE(), INTERVAL 20 DAY), 'Task_1', 2),
('Task_3', 1, 'BIM-моделирование', DATE_ADD(CURDATE(), INTERVAL 10 DAY), DATE_ADD(CURDATE(), INTERVAL 25 DAY), 'Task_1', 2);
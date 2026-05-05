-- Наполнение таблицы directories данными для калькулятора ПНР

-- Категория 'pnr_cost_percent' (процент от общей стоимости СМР для оценки ПНР системы)
INSERT INTO directories (category, name, value) VALUES
('pnr_cost_percent', 'Электрика (ЭОМ)', 0.8),
('pnr_cost_percent', 'Вентиляция и кондиционирование (ОВ)', 1.5),
('pnr_cost_percent', 'Слаботочные системы и пожарная безопасность (СС и АПС)', 1.0),
('pnr_cost_percent', 'Диспетчеризация и автоматика (АК / BMS)', 2.0);

-- Категория 'pnr_time_base' (базовый срок наладки системы в месяцах)
INSERT INTO directories (category, name, value) VALUES
('pnr_time_base', 'Электрика (ЭОМ)', 0.5),
('pnr_time_base', 'Вентиляция и кондиционирование (ОВ)', 1.0),
('pnr_time_base', 'Слаботочные системы и пожарная безопасность (СС и АПС)', 0.5),
('pnr_time_base', 'Диспетчеризация и автоматика (АК / BMS)', 1.5);
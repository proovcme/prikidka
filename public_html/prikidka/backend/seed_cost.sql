-- Наполнение таблицы directories данными для калькуляторов стоимости

-- Категория 'ncs_base' (базовая цена за м2 по НЦС)
INSERT INTO directories (category, name, value) VALUES
('ncs_base', 'Жилое многоквартирное здание', 85000),
('ncs_base', 'Складской комплекс', 45000),
('ncs_base', 'Офисный центр', 95000);

-- Категория 'region_coef' (региональные коэффициенты)
INSERT INTO directories (category, name, value) VALUES
('region_coef', 'Москва', 1.25),
('region_coef', 'Санкт-Петербург', 1.15),
('region_coef', 'Казань', 1.05);

-- Категория 'pir_percent' (процент на ПИР от СМР)
INSERT INTO directories (category, name, value) VALUES
('pir_percent', 'Жилое здание', 4.5),
('pir_percent', 'Складской комплекс', 3.0),
('pir_percent', 'Офисный центр', 5.5);

-- Категория 'fgis_index' (индексы перехода от исторических цен к текущим)
INSERT INTO directories (category, name, value) VALUES
('fgis_index', 'Индекс с 2022 на 2026', 1.45),
('fgis_index', 'Индекс с 2023 на 2026', 1.25),
('fgis_index', 'Индекс с 2024 на 2026', 1.15);
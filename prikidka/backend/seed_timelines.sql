-- Наполнение таблицы directories данными для калькулятора сроков

-- Категория 'timeline_design_base' (Базовый срок ПИР в месяцах для объекта до 1000 м2)
INSERT INTO directories (category, name, value) VALUES
('timeline_design_base', 'Жилое здание', 3.0),
('timeline_design_base', 'Офисный центр', 4.0),
('timeline_design_base', 'Склад', 2.0);

-- Категория 'timeline_design_mult' (Добавочный срок ПИР за каждые 1000 м2 свыше базы)
INSERT INTO directories (category, name, value) VALUES
('timeline_design_mult', 'Жилое здание', 0.5),
('timeline_design_mult', 'Офисный центр', 0.6),
('timeline_design_mult', 'Склад', 0.2);

-- Категория 'timeline_build_base' (Базовый срок СМР в месяцах для объекта до 1000 м2)
INSERT INTO directories (category, name, value) VALUES
('timeline_build_base', 'Жилое здание', 6.0),
('timeline_build_base', 'Офисный центр', 6.0),
('timeline_build_base', 'Склад', 4.0);

-- Категория 'timeline_build_mult' (Добавочный срок СМР за каждые 1000 м2 свыше базы)
INSERT INTO directories (category, name, value) VALUES
('timeline_build_mult', 'Жилое здание', 1.0),
('timeline_build_mult', 'Офисный центр', 1.2),
('timeline_build_mult', 'Склад', 0.5);
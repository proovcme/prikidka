-- Наполнение таблицы directories данными для калькулятора электричества

-- Категория 'electro_specific' (удельная нагрузка Вт/м2)
INSERT INTO directories (category, name, value) VALUES
('electro_specific', 'Жилое здание', 25),
('electro_specific', 'Офисный центр', 40),
('electro_specific', 'Склад', 10);

-- Категория 'electro_demand' (коэффициент спроса)
INSERT INTO directories (category, name, value) VALUES
('electro_demand', 'Жилое здание', 0.8),
('electro_demand', 'Офисный центр', 0.7),
('electro_demand', 'Склад', 0.9);
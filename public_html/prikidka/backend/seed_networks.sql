-- Наполнение таблицы directories данными для калькуляторов тепла и воды

-- Категория 'heat_heating' (удельный расход тепла на отопление, Вт/м2)
INSERT INTO directories (category, name, value) VALUES
('heat_heating', 'Жилое здание', 60),
('heat_heating', 'Офисный центр', 70),
('heat_heating', 'Склад', 40);

-- Категория 'heat_vent' (удельный расход тепла на вентиляцию, Вт/м2)
INSERT INTO directories (category, name, value) VALUES
('heat_vent', 'Жилое здание', 10),
('heat_vent', 'Офисный центр', 45),
('heat_vent', 'Склад', 15);

-- Категория 'water_daily_rate' (норма расхода воды в сутки на человека, литров)
INSERT INTO directories (category, name, value) VALUES
('water_daily_rate', 'Жилое здание', 250),
('water_daily_rate', 'Офисный центр', 15),
('water_daily_rate', 'Склад', 25);
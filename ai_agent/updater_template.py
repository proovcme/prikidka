#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Шаблон скрипта для локального ИИ-агента по обновлению справочников.
Этот скрипт собирает данные (имитация) и отправляет их на сервер через API.
"""

import requests
import json
import time

# --- Конфигурация ---
# URL к вашему sync.php на сервере
API_URL = "http://localhost/prikidka/backend/sync.php"  # Замените на реальный URL

# Секретный токен для авторизации (должен совпадать с SYNC_SECRET_KEY в config.php)
SECRET_KEY = "твой_надежный_токен_здесь"  # Замените на ваш реальный токен

# --- Функции ---

def fetch_data_like_human():
    """
    Имитация сбора данных.
    В реальном сценарии здесь может быть логика:
    - Парсинг веб-страниц (с использованием Selenium, Playwright, BeautifulSoup)
    - Чтение из файлов (CSV, Excel)
    - Получение данных из других API
    - Использование ИИ для анализа и извлечения данных
    
    Возвращает список словарей с данными для обновления.
    """
    print("Имитация сбора данных...")
    time.sleep(1)  # Имитация задержки
    
    # Пример данных, которые могли быть собраны
    data_to_update = [
        {
            "category": "fgis_index",
            "key_name": "2024_to_2026",
            "value": 1.18,
            "description": "Обновлено ИИ на основе данных ФГИС"
        },
        {
            "category": "ncs_base",
            "key_name": "warehouse",
            "value": 48000,
            "description": "Обновлено ИИ на основе новых НЦС"
        },
        {
            "category": "electro_specific",
            "key_name": "office",
            "value": 42,
            "description": "Обновлено ИИ: актуальная удельная нагрузка для офисов"
        }
    ]
    
    print(f"Собрано {len(data_to_update)} записей для обновления.")
    return data_to_update

def push_to_server(data):
    """
    Отправляет собранные данные на сервер через sync.php.
    
    :param data: Список словарей с данными для обновления.
    :return: True если успешно, False в случае ошибки.
    """
    if not data:
        print("Нет данных для отправки.")
        return True

    headers = {
        'Content-Type': 'application/json',
        'Authorization': f'Bearer {SECRET_KEY}'
    }
    
    # Также можно передать токен в POST параметрах, если сервер настроен на это
    # В данном случае sync.php проверяет и заголовок Authorization, и POST параметр 'token'
    # Для надежности можно использовать оба варианта или выбрать один.
    # В текущей реализации sync.php проверяет сначала POST, потом заголовок.
    # Добавим токен в тело запроса для совместимости.
    payload = {
        'token': SECRET_KEY,
        'data': data
    }

    try:
        print(f"Отправка {len(data)} записей на {API_URL}...")
        response = requests.post(API_URL, headers=headers, json=payload, timeout=30)
        
        if response.status_code == 200:
            result = response.json()
            print(f"Сервер ответил: {json.dumps(result, indent=2, ensure_ascii=False)}")
            if result.get('status') == 'success':
                print("Данные успешно обновлены на сервере.")
                return True
            else:
                print(f"Сервер сообщил об ошибке: {result.get('message', 'Неизвестная ошибка')}")
                return False
        elif response.status_code == 403:
            print("Ошибка 403: Доступ запрещен. Проверьте SECRET_KEY.")
            return False
        else:
            print(f"Ошибка сервера: {response.status_code} - {response.text}")
            return False
            
    except requests.exceptions.RequestException as e:
        print(f"Ошибка сети при отправке данных: {e}")
        return False

# --- Основной цикл ---

if __name__ == "__main__":
    print("Запуск ИИ-агента для обновления справочников...")
    
    # 1. Сбор данных
    new_data = fetch_data_like_human()
    
    # 2. Отправка данных
    if new_data:
        success = push_to_server(new_data)
        if success:
            print("Процесс обновления завершен успешно.")
        else:
            print("Процесс обновления завершен с ошибками.")
    else:
        print("Новых данных для обновления не найдено.")
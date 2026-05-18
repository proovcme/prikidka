<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Предпроектная оценка объекта</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><span class="cli-only">>_</span><span class="mgmt-only">→</span> Дашборд предпроектной оценки</h1>
            <button id="toggle-mode" class="system-btn" style="position:absolute; right:20px; top:20px;">Переключить режим</button>
        </header>

        <!-- Каскадный выбор -->
        <section id="cascade-select">
            <div class="select-group">
                <label>Вид объекта:</label>
                <select id="object-type">
                    <option value="">Выберите...</option>
                    <option value="building">Здание</option>
                    <option value="structure">Сооружение</option>
                </select>
            </div>
            <div class="select-group">
                <label>Задача:</label>
                <select id="task-type">
                    <option value="">Выберите...</option>
                    <option value="new">Новое строительство</option>
                    <option value="reconstruction">Реконструкция</option>
                </select>
            </div>
            <div class="select-group">
                <label>Тип:</label>
                <select id="building-type">
                    <option value="">Выберите...</option>
                    <option value="residential">Жилье</option>
                    <option value="office">Офис</option>
                    <option value="mall">ТЦ</option>
                </select>
            </div>
        </section>

        <!-- Карточка объекта -->
        <section id="object-card" style="display:none;">
            <div class="card">
                <h2>Модель объекта</h2>
                <div class="input-group">
                    <label>Общая площадь (м²):</label>
                    <input type="number" id="total-area" placeholder="Введите площадь">
                </div>
                <div class="input-group">
                    <label>Количество людей (N):</label>
                    <input type="number" id="people-count" placeholder="Введите количество">
                </div>
                <div class="input-group">
                    <label>Этажность:</label>
                    <input type="number" id="floors-count" placeholder="Введите количество этажей">
                </div>
                <div class="input-group">
                    <label>Объем здания (м³):</label>
                    <input type="number" id="building-volume" placeholder="Рассчитается автоматически" disabled>
                </div>
            </div>
        </section>

        <!-- Орбита систем -->
        <section id="systems-orbit" style="display:none;">
            <h2>Инженерные системы</h2>
            <div class="orbit-container">
                <button class="system-btn" data-system="vk">
                    <span class="cli-only">>_</span><span class="mgmt-only">→</span> 💧 ВК (Водоснабжение)
                </button>
                <button class="system-btn" data-system="ov">
                    <span class="cli-only">>_</span><span class="mgmt-only">→</span> 🌬️ ОВ (Отопление и вентиляция)
                </button>
                <button class="system-btn" data-system="eom">
                    <span class="cli-only">>_</span><span class="mgmt-only">→</span> ⚡ ЭОМ (Электрика)
                </button>
            </div>
        </section>

        <!-- Инженерная лаборатория (модальное окно) -->
        <div id="lab-modal" class="modal" style="display:none;">
            <div class="modal-content">
                <span class="close-btn">&times;</span>
                <h2 id="lab-title"></h2>
                <div id="lab-formulas"></div>
                <div id="lab-inputs"></div>
                <div id="lab-result"></div>
            </div>
        </div>

        <script src="assets/js/engineering_norms.js"></script>
        <script src="assets/js/calculator.js"></script>
        <script src="assets/js/lab.js"></script>
        <script src="assets/js/main.js"></script>
    </div>
</body>
</html>
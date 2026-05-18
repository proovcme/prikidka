<?php
$siteId = 'toc';
$pageTitle = 'Прикидка — Калькулятор стоимости и сроков';
$pageDescription = 'Калькулятор стоимости СМР по НЦС, ПИР, ПНР, сроков реализации и инженерных систем. Быстрая оценка для проектных бюро.';
$canonicalUrl = 'https://toc.chernetchenko.pro/prikidka/';
include '../header.php';
?>

<div class="page-wrapper-wide">

    <!-- Боковая навигация модулей -->
    <nav class="sidebar-nav" aria-label="Навигация по модулям">
        <div class="sidebar-nav__title">
            <span class="cli-only">>_ </span>
            <span class="mgmt-only">→ </span>
            Прикидка
        </div>
        <ul class="sidebar-nav__list">
            <li><a href="#" class="active" data-module="cost">
                <span class="cli-only">>_ </span>
                <span class="mgmt-only">→ </span>
                Стоимость
            </a></li>
            <li><a href="#" data-module="electricity">
                <span class="cli-only">>_ </span>
                <span class="mgmt-only">→ </span>
                Электричество
            </a></li>
            <li><a href="#" data-module="heat">
                <span class="cli-only">>_ </span>
                <span class="mgmt-only">→ </span>
                Тепло
            </a></li>
            <li><a href="#" data-module="water">
                <span class="cli-only">>_ </span>
                <span class="mgmt-only">→ </span>
                Вода
            </a></li>
            <li><a href="#" data-module="timeline">
                <span class="cli-only">>_ </span>
                <span class="mgmt-only">→ </span>
                Сроки
            </a></li>
            <li><a href="#" data-module="pnr">
                <span class="cli-only">>_ </span>
                <span class="mgmt-only">→ </span>
                ПНР
            </a></li>
        </ul>
    </nav>

    <!-- Основной контент -->
    <main class="main-content">

        <!-- Модуль "Стоимость" -->
        <div id="cost" class="module-container active">
            <div class="section-head">
                <span class="cli-only">>_ </span>
                <span class="mgmt-only">→ </span>
                Калькулятор стоимости
            </div>

            <div class="tabs">
                <button class="btn btn-nav c-toc active" data-tab="ncs">
                    <span class="cli-only">>_ </span>По нормам (НЦС)
                </button>
                <button class="btn btn-nav c-toc" data-tab="pir">
                    <span class="cli-only">>_ </span>Проектные работы (ПИР)
                </button>
                <button class="btn btn-nav c-toc" data-tab="index">
                    <span class="cli-only">>_ </span>Пересчет аналога (Индексы)
                </button>
            </div>

            <!-- Блок НЦС -->
            <div id="ncs" class="tab-content active">
                <div class="card calc-card">
                    <div class="form-group">
                        <label for="ncs-building-type">Тип здания:</label>
                        <select id="ncs-building-type">
                            <!-- Опции будут заполнены из JS -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ncs-area">Площадь (м²):</label>
                        <input type="number" id="ncs-area" placeholder="Например: 1000" min="0">
                    </div>
                    <div class="form-group">
                        <label for="ncs-region">Регион:</label>
                        <select id="ncs-region">
                            <!-- Опции будут заполнены из JS -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ncs-underground">Подземные этажи:</label>
                        <select id="ncs-underground">
                            <option value="0">Нет</option>
                            <option value="1">1 этаж</option>
                            <option value="2">2+ этажа</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ncs-class">Класс объекта:</label>
                        <select id="ncs-class">
                            <option value="econom">Эконом</option>
                            <option value="comfort">Комфорт</option>
                            <option value="business">Бизнес</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ncs-soil">Грунтовые условия:</label>
                        <select id="ncs-soil">
                            <option value="normal">Нормальные</option>
                            <option value="difficult">Сложные свайные</option>
                        </select>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" id="ncs-nds">
                        <label for="ncs-nds">Включая НДС 22%</label>
                    </div>
                    <div class="result-block">
                        <div class="result-label">Итоговая стоимость СМР:</div>
                        <div class="result-value" id="ncs-result">0 ₽</div>
                    </div>
                    <div class="result-block">
                        <div class="result-label">Продаваемая/Арендуемая площадь (GLA):</div>
                        <div class="result-value" id="cost-sellable-area">0 м²</div>
                    </div>
                    <div class="content-block block-warn">
                        <div class="content-block-header"><span class="cli-only">[WARN]</span> ВАЖНО</div>
                        <p>В стоимость СМР не включена плата за технологическое присоединение (ТП) к городским сетям.</p>
                    </div>
                </div>
            </div>

            <!-- Блок ПИР -->
            <div id="pir" class="tab-content">
                <div class="card calc-card">
                    <div class="form-group">
                        <label for="pir-smr-cost">Стоимость СМР (₽):</label>
                        <input type="number" id="pir-smr-cost" placeholder="Например: 10000000" min="0">
                    </div>
                    <div class="form-group">
                        <label for="pir-building-type">Тип здания (рекомендуемый %):</label>
                        <select id="pir-building-type">
                            <!-- Опции будут заполнены из JS -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pir-percent">Процент на ПИР (%):</label>
                        <input type="number" id="pir-percent" placeholder="Например: 4.5" step="0.1" min="0">
                    </div>
                    <div class="result-block">
                        <div class="result-label">Стоимость ПИР:</div>
                        <div class="result-value" id="pir-result">0 ₽</div>
                    </div>
                </div>
            </div>

            <!-- Блок Индексы -->
            <div id="index" class="tab-content">
                <div class="card calc-card">
                    <div class="form-group">
                        <label for="index-historical-cost">Историческая стоимость (₽):</label>
                        <input type="number" id="index-historical-cost" placeholder="Например: 5000000" min="0">
                    </div>
                    <div class="form-group">
                        <label for="index-period">Период постройки аналога:</label>
                        <select id="index-period">
                            <!-- Опции будут заполнены из JS -->
                        </select>
                    </div>
                    <div class="result-block">
                        <div class="result-label">Обновленная стоимость:</div>
                        <div class="result-value" id="index-result">0 ₽</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Модуль "Электричество" -->
        <div id="electricity" class="module-container">
            <div class="section-head">
                <span class="cli-only">>_ </span>
                <span class="mgmt-only">→ </span>
                Калькулятор электричества
            </div>
            <div class="card calc-card">
                <div class="form-group">
                    <label for="electro-object-type">Тип объекта:</label>
                    <select id="electro-object-type">
                        <!-- Опции будут заполнены из JS -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="electro-area">Общая площадь, м²:</label>
                    <input type="number" id="electro-area" placeholder="Например: 100" min="0">
                </div>
                <div class="form-group">
                    <label for="electro-additional-eq">Дополнительное оборудование, кВт:</label>
                    <input type="number" id="electro-additional-eq" placeholder="Например: 5" min="0" step="0.1" value="0">
                </div>
                <div class="form-group">
                    <label for="electro-cooking-type">Пищеприготовление:</label>
                    <select id="electro-cooking-type">
                        <option value="gas">Газ</option>
                        <option value="electric">Электричество</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="electro-em-charging">Машиномест с зарядкой ЭМ (шт):</label>
                    <input type="number" id="electro-em-charging" placeholder="Например: 10" min="0" value="0">
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="electro-smoke">
                    <label for="electro-smoke">Системы противодымной защиты (ДУ)</label>
                </div>
                <div class="form-group">
                    <label for="electro-reliability">Категория надежности:</label>
                    <select id="electro-reliability">
                        <option value="3">III категория</option>
                        <option value="2">II категория</option>
                        <option value="1">I категория + ДГУ</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="electro-vent-type">Тип вентиляции:</label>
                    <select id="electro-vent-type">
                        <option value="cav">Стандартная CAV</option>
                        <option value="vav">Энергоэффективная VAV</option>
                    </select>
                </div>
                <div class="result-block">
                    <div class="result-label">Расчетная мощность (Рр), кВт:</div>
                    <div class="result-value" id="electro-calculated-power">0.00 кВт</div>
                </div>
                <div class="result-block">
                    <div class="result-label">Установленная мощность (Ру), кВт:</div>
                    <div class="result-value" id="electro-installed-power">0.00 кВт</div>
                </div>
                <div class="result-block">
                    <div class="result-label">Мощность трансформатора (S):</div>
                    <div class="result-value" id="electro-total-kva">0.00 кВА</div>
                </div>
                <div id="electro-dgu-block" style="display: none; margin-top: 16px;" class="content-block block-info">
                    <div class="content-block-header"><span class="cli-only">[INFO]</span> Дизель-генераторная установка</div>
                    <p>Требуемая мощность ДГУ: <span id="electro-dgu-power">0.00</span> кВА (20% от полной мощности здания)</p>
                </div>
            </div>
        </div>

        <!-- Модуль "Тепло" -->
        <div id="heat" class="module-container">
            <div class="section-head">
                <span class="cli-only">>_ </span>
                <span class="mgmt-only">→ </span>
                Калькулятор тепловых нагрузок
            </div>
            <div class="card calc-card">
                <div class="form-group">
                    <label for="heat-object-type">Тип объекта:</label>
                    <select id="heat-object-type">
                        <!-- Опции будут заполнены из JS -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="heat-area">Общая площадь, м²:</label>
                    <input type="number" id="heat-area" placeholder="Например: 500" min="0">
                </div>
                <div class="form-group">
                    <label for="heat-ceiling-height">Средняя высота потолков, м:</label>
                    <input type="number" id="heat-ceiling-height" placeholder="Например: 3" min="0" step="0.1" value="3">
                </div>
                <div class="form-group">
                    <label for="heat-glazing">Остекление фасада:</label>
                    <select id="heat-glazing">
                        <option value="20">Стандарт 20%</option>
                        <option value="50">Панорама 50%</option>
                        <option value="80">Сплошное 80%</option>
                    </select>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="heat-beams">
                    <label for="heat-beams">Климатические балки (активные)</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="heat-recovery">
                    <label for="heat-recovery">Рекуперация (КПД 65%)</label>
                </div>
                <div class="form-group">
                    <label for="heat-floors">Количество этажей:</label>
                    <input type="number" id="heat-floors" placeholder="Например: 10" min="1" value="10">
                </div>
                <div class="result-block">
                    <div class="result-label">Нагрузка на отопление, кВт:</div>
                    <div class="result-value" id="heat-heating-result">0.00 кВт</div>
                </div>
                <div class="result-block">
                    <div class="result-label">Нагрузка на вентиляцию, кВт:</div>
                    <div class="result-value" id="heat-vent-result">0.00 кВт</div>
                </div>
                <div class="result-block">
                    <div class="result-label">Итоговая тепловая нагрузка, кВт:</div>
                    <div class="result-value" id="heat-total-result">0.00 кВт</div>
                </div>
                <div class="result-block">
                    <div class="result-label">Нагрузка для получения ТУ:</div>
                    <div class="result-value" id="heat-total-gcal">0.0000 Гкал/ч</div>
                </div>
            </div>
        </div>

        <!-- Модуль "Вода" -->
        <div id="water" class="module-container">
            <div class="section-head">
                <span class="cli-only">>_ </span>
                <span class="mgmt-only">→ </span>
                Калькулятор водопотребления
            </div>
            <div class="card calc-card">
                <div class="form-group">
                    <label for="water-object-type">Тип объекта:</label>
                    <select id="water-object-type">
                        <!-- Опции будут заполнены из JS -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="water-users">Количество пользователей:</label>
                    <input type="number" id="water-users" placeholder="Например: 100" min="0">
                </div>
                <div class="form-group">
                    <label for="water-inequality-coef">Коэффициент часовой неравномерности:</label>
                    <input type="number" id="water-inequality-coef" placeholder="Например: 1.5" min="0" step="0.1" value="1.5">
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="water-fire-pipe">
                    <label for="water-fire-pipe">Противопожарный водопровод (ВПВ)</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="water-sprinkler">
                    <label for="water-sprinkler">Спринклерное тушение (АУПТ)</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="water-reserve">
                    <label for="water-reserve">Резерв питьевой воды</label>
                </div>
                <div class="result-block">
                    <div class="result-label">Суточный расход, м³/сут:</div>
                    <div class="result-value" id="water-daily-result">0.00 м³/сут</div>
                </div>
                <div class="result-block">
                    <div class="result-label">Максимальный часовой расход, м³/час:</div>
                    <div class="result-value" id="water-hourly-result">0.00 м³/час</div>
                </div>
                <div id="water-fire-tanks" style="display: none; margin-top: 16px;" class="content-block block-warn">
                    <div class="content-block-header"><span class="cli-only">[WARN]</span> Пожарные резервуары</div>
                    <p>Ориентировочный объем баков: <span id="water-tanks-vol">0</span> м³ (запас на 3 часа работы).</p>
                </div>
            </div>
        </div>

        <!-- Модуль "Сроки" -->
        <div id="timeline" class="module-container">
            <div class="section-head">
                <span class="cli-only">>_ </span>
                <span class="mgmt-only">→ </span>
                Калькулятор сроков реализации
            </div>
            <div class="card calc-card">
                <div class="form-group">
                    <label for="timeline-object-type">Тип объекта:</label>
                    <select id="timeline-object-type">
                        <!-- Опции будут заполнены из JS -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="timeline-area">Общая площадь, м²:</label>
                    <input type="number" id="timeline-area" placeholder="Например: 1500" min="0">
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="timeline-expertise" checked>
                    <label for="timeline-expertise">Прохождение государственной/негосударственной экспертизы</label>
                </div>
                <div class="form-group">
                    <label for="timeline-pir-stage">Стадийность ПИР:</label>
                    <select id="timeline-pir-stage">
                        <option value="sequential">Последовательно П→Р</option>
                        <option value="parallel">Параллельно П+Р</option>
                    </select>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="timeline-underground">
                    <label for="timeline-underground">Наличие подземной части</label>
                </div>
                <div class="result-block">
                    <div class="result-label">Проектирование и изыскания (ПИР):</div>
                    <div class="result-value" id="timeline-design-result">0.0 мес</div>
                </div>
                <div class="result-block">
                    <div class="result-label">Экспертиза и утверждение:</div>
                    <div class="result-value" id="timeline-expertise-result">0.0 мес</div>
                </div>
                <div class="result-block">
                    <div class="result-label">Строительно-монтажные работы (СМР):</div>
                    <div class="result-value" id="timeline-build-result">0.0 мес</div>
                </div>
                <div class="result-block">
                    <div class="result-label">Итоговый срок реализации:</div>
                    <div class="result-value" id="timeline-total-result">0.0 мес</div>
                </div>
            </div>
        </div>

        <!-- Модуль "ПНР" -->
        <div id="pnr" class="module-container">
            <div class="section-head">
                <span class="cli-only">>_ </span>
                <span class="mgmt-only">→ </span>
                Калькулятор пусконаладочных работ (ПНР)
            </div>
            <div class="card calc-card">
                <div class="form-group">
                    <label for="pnr-smr-cost">Ориентировочная стоимость СМР объекта, ₽:</label>
                    <input type="number" id="pnr-smr-cost" placeholder="Например: 50000000" min="0">
                </div>
                <div class="form-group">
                    <label for="pnr-automation">Сложность автоматизации:</label>
                    <select id="pnr-automation">
                        <option value="basic">Базовая</option>
                        <option value="ems">Продвинутая (EMS)</option>
                        <option value="bms">Полная (BMS)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Системы, требующие ПНР:</label>
                    <div class="checkbox-group">
                        <input type="checkbox" id="pnr-electro" value="Электрика (ЭОМ)">
                        <label for="pnr-electro">Электроснабжение (ЭОМ)</label>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" id="pnr-vent" value="Вентиляция и кондиционирование (ОВ)">
                        <label for="pnr-vent">Вентиляция и кондиционирование (ОВ)</label>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" id="pnr-weak" value="Слаботочные системы и пожарная безопасность (СС и АПС)">
                        <label for="pnr-weak">Слаботочные системы и АПС</label>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" id="pnr-bms" value="Диспетчеризация и автоматика (АК / BMS)">
                        <label for="pnr-bms">Автоматизация и диспетчеризация (BMS)</label>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" id="pnr-smoke" value="Противодымная вентиляция и клапаны (ДУ)">
                        <label for="pnr-smoke">Противодымная вентиляция и клапаны (ДУ)</label>
                    </div>
                </div>
                <div class="result-block">
                    <div class="result-label">Бюджет на ПНР, ₽:</div>
                    <div class="result-value" id="pnr-cost-result">0 ₽</div>
                </div>
                <div class="result-block">
                    <div class="result-label">Ориентировочный срок ПНР, мес.:</div>
                    <div class="result-value" id="pnr-time-result">0.0 мес</div>
                </div>
            </div>
        </div>

    </main>
</div>

<style>
/* ============================================================
   PAGE LAYOUT
   ============================================================ */
.page-wrapper-wide {
    display: flex;
    min-height: calc(100vh - 52px);
    max-width: 100%;
    margin: 0;
    padding: 0;
}

/* ============================================================
   SIDEBAR NAVIGATION
   ============================================================ */
.sidebar-nav {
    width: 220px;
    min-width: 220px;
    background: var(--bg-secondary);
    border-right: 1px solid var(--border);
    padding: 24px 0;
    position: sticky;
    top: 52px;
    height: calc(100vh - 52px);
    overflow-y: auto;
    flex-shrink: 0;
}

.sidebar-nav__title {
    font-family: var(--font-code);
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--c-toc);
    padding: 0 20px 16px;
    border-bottom: 1px solid var(--border);
    margin-bottom: 12px;
    letter-spacing: 0.02em;
}

.sidebar-nav__list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar-nav__list li {
    margin: 0;
}

.sidebar-nav__list li a {
    display: block;
    padding: 10px 20px;
    color: var(--text-muted);
    text-decoration: none;
    font-family: var(--font-code);
    font-size: 0.82rem;
    transition: all var(--ease);
    border-left: 2px solid transparent;
}

.sidebar-nav__list li a:hover {
    color: var(--text-main);
    background: rgba(255,255,255,0.03);
    border-left-color: var(--border);
}

.sidebar-nav__list li a.active {
    color: var(--c-toc);
    background: rgba(90,156,66,0.08);
    border-left-color: var(--c-toc);
    font-weight: 600;
}

/* ============================================================
   MAIN CONTENT
   ============================================================ */
.main-content {
    flex: 1;
    padding: 32px 40px;
    background: var(--bg-main);
    min-width: 0;
}

/* ============================================================
   SECTION HEAD (gradient line)
   ============================================================ */
.section-head {
    font-family: var(--font-code);
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-main);
    margin: 0 0 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--border);
    position: relative;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-head::after {
    content: "";
    flex: 1;
    height: 1px;
    background: linear-gradient(to right, var(--c-toc), transparent);
    opacity: 0.5;
}

/* ============================================================
   MODULE CONTAINERS
   ============================================================ */
.module-container {
    display: none;
}

.module-container.active {
    display: block;
}

/* ============================================================
   CALC CARDS
   ============================================================ */
.calc-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: var(--r-lg);
    padding: 24px;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
}

.calc-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--c-toc);
}

/* ============================================================
   TABS
   ============================================================ */
.tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

/* ============================================================
   TAB CONTENT
   ============================================================ */
.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* ============================================================
   FORM ELEMENTS
   ============================================================ */
.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-family: var(--font-code);
    font-size: 0.78rem;
    color: var(--text-dim);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.form-group select,
.form-group input[type="number"] {
    width: 100%;
    padding: 10px 14px;
    background: var(--bg-tertiary);
    border: 1px solid var(--border);
    border-radius: var(--r-sm);
    color: var(--text-main);
    font-family: var(--font-code);
    font-size: 0.92rem;
    box-sizing: border-box;
    transition: border-color var(--ease), box-shadow var(--ease);
    outline: none;
}

.form-group select:focus,
.form-group input[type="number"]:focus {
    border-color: var(--c-toc);
    box-shadow: 0 0 0 3px rgba(90,156,66,0.14);
}

/* ============================================================
   CHECKBOX GROUPS
   ============================================================ */
.checkbox-group {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
}

.checkbox-group input[type="checkbox"] {
    width: 16px;
    height: 16px;
    accent-color: var(--c-toc);
    cursor: pointer;
}

.checkbox-group label {
    font-family: var(--font-ui);
    font-size: 0.9rem;
    color: var(--text-muted);
    text-transform: none;
    letter-spacing: 0;
    cursor: pointer;
    margin-bottom: 0;
}

/* ============================================================
   RESULT BLOCKS
   ============================================================ */
.result-block {
    margin-top: 20px;
    padding: 16px;
    background: var(--bg-tertiary);
    border-radius: var(--r-md);
    border: 1px solid var(--border-sub);
}

.result-label {
    font-family: var(--font-code);
    font-size: 0.72rem;
    color: var(--text-dim);
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 6px;
}

.result-value {
    font-family: var(--font-code);
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--c-toc);
}

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 900px) {
    .sidebar-nav {
        width: 180px;
        min-width: 180px;
    }
    .main-content {
        padding: 24px 28px;
    }
}

@media (max-width: 600px) {
    .page-wrapper-wide {
        flex-direction: column;
    }
    .sidebar-nav {
        width: 100%;
        min-width: 100%;
        height: auto;
        position: static;
        border-right: none;
        border-bottom: 1px solid var(--border);
        padding: 16px 0;
    }
    .sidebar-nav__list {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        padding: 0 16px;
    }
    .sidebar-nav__list li a {
        padding: 6px 12px;
        border-left: none;
        border-radius: var(--r-sm);
        border: 1px solid transparent;
    }
    .sidebar-nav__list li a.active {
        border-color: var(--c-toc);
    }
    .main-content {
        padding: 20px 16px;
    }
}
</style>

<script src="assets/js/cost.js"></script>
<script src="assets/js/electricity.js"></script>
<script src="assets/js/heat.js"></script>
<script src="assets/js/water.js"></script>
<script src="assets/js/timeline.js"></script>
<script src="assets/js/pnr.js"></script>
<script>
    // Переключение модулей
    document.addEventListener('DOMContentLoaded', () => {
        const menuLinks = document.querySelectorAll('.sidebar-nav__list li a');
        const moduleContainers = document.querySelectorAll('.module-container');

        menuLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();

                menuLinks.forEach(l => l.classList.remove('active'));
                moduleContainers.forEach(c => c.classList.remove('active'));

                link.classList.add('active');
                const targetModule = link.getAttribute('data-module');
                document.getElementById(targetModule).classList.add('active');
            });
        });
    });
</script>

<?php include '../footer.php'; ?>
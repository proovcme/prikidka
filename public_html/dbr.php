<?php 
  $pageTitle = "Анимации ТОС | toc.chernetchenko.pro"; 
  include 'header.php'; 
?>
<style>
    <style>
        /* ── Анимационная страница ── */

        .anim-stage {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 28px;
            margin-bottom: 36px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            position: relative;
            overflow: hidden;
        }

        .anim-stage h2 {
            margin: 0 0 6px;
            font-size: 19px;
            color: var(--navy);
        }

        .anim-stage .anim-subtitle {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 22px;
        }

        /* ── DBR-анимация ── */
        .dbr-canvas {
            position: relative;
            height: 200px;
            background: #f5f3f0;
            border-radius: 4px;
            border: 1px solid var(--border);
            overflow: hidden;
            margin-bottom: 16px;
        }

        /* Конвейер */
        .conveyor-track {
            position: absolute;
            bottom: 48px;
            left: 0;
            right: 0;
            height: 14px;
            background: repeating-linear-gradient(
                90deg,
                #c8c0b4 0px, #c8c0b4 18px,
                #b8b0a4 18px, #b8b0a4 20px
            );
            border-top: 2px solid #9a9088;
            border-bottom: 2px solid #9a9088;
        }

        /* Станции (ресурсы) */
        .station {
            position: absolute;
            bottom: 60px;
            width: 64px;
            text-align: center;
        }

        .station-box {
            width: 64px;
            height: 52px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 700;
            color: #fff;
            flex-direction: column;
            gap: 2px;
            position: relative;
        }

        .station-label {
            font-size: 9px;
            color: var(--muted);
            margin-top: 4px;
            white-space: nowrap;
        }

        .station-normal .station-box   { background: #4a6888; }
        .station-drum .station-box     { background: var(--navy); border: 2px solid var(--orange); }
        .station-overload .station-box { background: var(--red); animation: pulse-red 1s infinite; }

        @keyframes pulse-red {
            0%, 100% { box-shadow: 0 0 0 0 rgba(176,48,48,0.4); }
            50%       { box-shadow: 0 0 0 6px rgba(176,48,48,0); }
        }

        /* Задания (детали на конвейере) */
        .work-item {
            position: absolute;
            bottom: 55px;
            width: 26px;
            height: 20px;
            border-radius: 3px;
            background: #7a9ab8;
            border: 1px solid #5a7a98;
            transition: left 0.05s linear;
        }

        .work-item.blocked { background: var(--red); border-color: #901818; }
        .work-item.processed { background: var(--green); border-color: #1d5a28; }

        /* Буфер */
        .buffer-bar-wrap {
            position: absolute;
            right: 16px;
            bottom: 62px;
            width: 16px;
            height: 80px;
            background: #e8e4dc;
            border-radius: 3px;
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .buffer-fill {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--green);
            transition: height 0.4s ease, background 0.4s ease;
        }

        .buffer-label {
            position: absolute;
            right: 36px;
            bottom: 62px;
            font-size: 9px;
            font-weight: 700;
            color: var(--muted);
            text-align: right;
            white-space: nowrap;
        }

        /* Канат */
        .rope {
            position: absolute;
            bottom: 68px;
            height: 2px;
            background: var(--orange);
            transform-origin: left center;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .rope.visible { opacity: 1; }

        /* Drum-индикатор */
        .drum-indicator {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--navy);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 2px;
            letter-spacing: 0.3px;
        }

        /* Тикер скорости */
        .speed-ticker {
            position: absolute;
            top: 10px;
            right: 50px;
            font-size: 10px;
            color: var(--muted);
        }

        /* ── Кнопки управления ── */
        .anim-controls {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .anim-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-weight: 700;
            font-size: 12px;
            font-family: inherit;
            transition: opacity 0.15s;
        }

        .anim-btn:hover { opacity: 0.85; }
        .anim-btn-play  { background: var(--navy);   color: #fff; }
        .anim-btn-drum  { background: var(--orange);  color: #fff; }
        .anim-btn-rope  { background: var(--green);   color: #fff; }
        .anim-btn-reset { background: #e8e4dc; color: var(--navy); border: 1px solid var(--border); }

        .anim-status {
            font-size: 12px;
            color: var(--muted);
            font-style: italic;
            margin-left: 6px;
        }

        .legend-row {
            display: flex;
            gap: 20px;
            margin-top: 12px;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            color: var(--muted);
        }

        .legend-dot {
            width: 12px;
            height: 12px;
            border-radius: 2px;
            flex-shrink: 0;
        }

        /* ── CCPM-анимация ── */
        .ccpm-canvas {
            position: relative;
            height: 280px;
            background: #f5f3f0;
            border-radius: 4px;
            border: 1px solid var(--border);
            overflow: hidden;
            margin-bottom: 16px;
        }

        .gantt-row-label {
            position: absolute;
            left: 10px;
            font-size: 10px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .gantt-bar {
            position: absolute;
            height: 26px;
            border-radius: 3px;
            display: flex;
            align-items: center;
            padding: 0 8px;
            font-size: 10px;
            font-weight: 700;
            color: #fff;
            white-space: nowrap;
            transition: width 0.6s ease, left 0.6s ease, opacity 0.6s ease;
        }

        .bar-task    { background: #4a6888; }
        .bar-sleep   { background: #8a9aaa; }
        .bar-panic   { background: var(--red); }
        .bar-buffer  { background: var(--orange); }
        .bar-relay   { background: var(--navy); }
        .bar-done    { background: var(--green); }

        .gantt-deadline {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--red);
            opacity: 0.7;
        }

        .gantt-deadline-label {
            position: absolute;
            top: 4px;
            font-size: 9px;
            font-weight: 700;
            color: var(--red);
            transform: translateX(-50%);
        }

        .gantt-timeline {
            position: absolute;
            bottom: 0;
            left: 80px;
            right: 10px;
            height: 20px;
            border-top: 1px solid var(--border);
        }

        .gantt-tick {
            position: absolute;
            bottom: 2px;
            font-size: 9px;
            color: #aaa;
            transform: translateX(-50%);
        }

        .ccpm-phase-label {
            position: absolute;
            top: 8px;
            font-size: 11px;
            font-weight: 700;
            color: var(--navy);
            background: rgba(240,236,228,0.9);
            padding: 2px 8px;
            border-radius: 2px;
        }

        /* ── Метрики ── */
        .metrics-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 18px;
        }

        .metric-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 12px 14px;
            text-align: center;
        }

        .metric-val {
            font-size: 22px;
            font-weight: 800;
            color: var(--navy);
            line-height: 1.1;
        }

        .metric-val.bad  { color: var(--red); }
        .metric-val.good { color: var(--green); }
        .metric-val.warn { color: var(--orange); }

        .metric-label {
            font-size: 10px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-top: 3px;
        }
    </style>
</head>
<body>

 

    <header class="hero">
        <h1>Барабан — Буфер — Канат</h1>
        <p>Интерактивные модели: смотри что происходит без ТОС и с ним.</p>
    </header>

    <div class="container">

        <!-- ── АНИМАЦИЯ 1: DBR ── -->
        <div class="anim-stage">
            <h2>Барабан, Буфер, Канат (DBR)</h2>
            <div class="anim-subtitle">Производственная система из 5 станций. Третья — узкое место (барабан). Запусти и посмотри, что происходит.</div>

            <div class="metrics-row">
                <div class="metric-card">
                    <div class="metric-val" id="dbr-throughput">0</div>
                    <div class="metric-label">Пропускная способность</div>
                </div>
                <div class="metric-card">
                    <div class="metric-val warn" id="dbr-wip">0</div>
                    <div class="metric-label">НЗП (очередь перед узким местом)</div>
                </div>
                <div class="metric-card">
                    <div class="metric-val" id="dbr-buffer">100%</div>
                    <div class="metric-label">Буфер перед барабаном</div>
                </div>
                <div class="metric-card">
                    <div class="metric-val" id="dbr-mode">—</div>
                    <div class="metric-label">Режим</div>
                </div>
            </div>

            <div class="dbr-canvas" id="dbr-canvas">
                <div class="drum-indicator" id="dbr-drum-label">БЕЗ УПРАВЛЕНИЯ</div>
                <div class="speed-ticker" id="dbr-speed-label"></div>
                <div class="conveyor-track"></div>
            </div>

            <div class="anim-controls">
                <button class="anim-btn anim-btn-play" id="btn-dbr-play" onclick="dbrPlay()">▶ Запустить без управления</button>
                <button class="anim-btn anim-btn-drum" onclick="dbrEnableDrum()">🥁 Включить Барабан</button>
                <button class="anim-btn anim-btn-rope" onclick="dbrEnableRope()">🪢 Добавить Канат</button>
                <button class="anim-btn anim-btn-reset" onclick="dbrReset()">↺ Сброс</button>
                <span class="anim-status" id="dbr-status">Нажми «Запустить» чтобы начать</span>
            </div>

            <div class="legend-row">
                <div class="legend-item"><div class="legend-dot" style="background:#4a6888"></div>Обычная станция</div>
                <div class="legend-item"><div class="legend-dot" style="background:var(--navy);outline:2px solid var(--orange)"></div>Барабан (узкое место)</div>
                <div class="legend-item"><div class="legend-dot" style="background:var(--red)"></div>Перегружена</div>
                <div class="legend-item"><div class="legend-dot" style="background:#7a9ab8"></div>Задание в системе</div>
                <div class="legend-item"><div class="legend-dot" style="background:var(--green)"></div>Задание выполнено</div>
                <div class="legend-item"><div class="legend-dot" style="background:var(--orange)"></div>Канат (сигнал запуска)</div>
            </div>
        </div>

        <!-- Пояснение DBR -->
        <div class="intro-block card card-left-navy" style="margin-bottom: 36px;">
            <h2 style="color:var(--navy); margin-top:0; font-size:18px;">Что ты видишь</h2>
            <p><b>Без управления:</b> каждая станция работает на максимальной скорости. Перед узким местом (барабаном) накапливается очередь — незавершённое производство (НЗП). Быстрые станции после барабана простаивают в ожидании. Буфер расходуется неуправляемо.</p>
            <p><b>С барабаном:</b> узкое место задаёт ритм всей системы. Станции после него работают с его скоростью. Входной поток ограничивается барабаном, но очередь больше не растёт бесконечно.</p>
            <p style="margin:0"><b>С канатом:</b> запуск новых заданий в систему привязан к скорости барабана — только когда барабан готов принять следующее. НЗП минимально. Буфер перед барабаном защищает его от простоя. Система работает предсказуемо.</p>
        </div>

        <!-- ── АНИМАЦИЯ 2: CCPM ── -->
        <div class="anim-stage">
            <h2>Традиционный план vs Критическая цепь (CCPM)</h2>
            <div class="anim-subtitle">Три параллельные задачи. Один дедлайн. Два подхода. Смотри, что происходит с буфером проекта.</div>

            <div class="metrics-row">
                <div class="metric-card">
                    <div class="metric-val bad" id="ccpm-trad-end">—</div>
                    <div class="metric-label">Традиционный: итог</div>
                </div>
                <div class="metric-card">
                    <div class="metric-val good" id="ccpm-cc-end">—</div>
                    <div class="metric-label">CCPM: итог</div>
                </div>
                <div class="metric-card">
                    <div class="metric-val warn" id="ccpm-buffer-pct">100%</div>
                    <div class="metric-label">Буфер проекта (CCPM)</div>
                </div>
                <div class="metric-card">
                    <div class="metric-val" id="ccpm-day">0</div>
                    <div class="metric-label">Текущий день проекта</div>
                </div>
            </div>

            <div class="ccpm-canvas" id="ccpm-canvas"></div>

            <div class="anim-controls">
                <button class="anim-btn anim-btn-play" onclick="ccpmPlay()">▶ Запустить симуляцию</button>
                <button class="anim-btn anim-btn-reset" onclick="ccpmReset()">↺ Сброс</button>
                <span class="anim-status" id="ccpm-status">Нажми «Запустить» — увидишь оба подхода одновременно</span>
            </div>

            <div class="legend-row">
                <div class="legend-item"><div class="legend-dot" style="background:#8a9aaa"></div>Студенческий синдром (ожидание)</div>
                <div class="legend-item"><div class="legend-dot" style="background:#4a6888"></div>Работа по задаче</div>
                <div class="legend-item"><div class="legend-dot" style="background:var(--red)"></div>Аврал (выход за дедлайн)</div>
                <div class="legend-item"><div class="legend-dot" style="background:var(--navy)"></div>Работа по CCPM (эстафета)</div>
                <div class="legend-item"><div class="legend-dot" style="background:var(--orange)"></div>Общий буфер проекта</div>
                <div class="legend-item"><div class="legend-dot" style="background:var(--green)"></div>Завершено в срок</div>
            </div>
        </div>

        <div class="orange-banner">
            Буфер — это не жир, который нужно срезать. Это страховочный трос между реальностью и планом.
        </div>

        <pre class="footer-cat">
  /\_____/\
 (  ~   ~  )
 =( Y = Y )=
  ) DBR!  (
 (_)-(_)-(_)
        </pre>

    </div>

    <script>
    // ═══════════════════════════════════════════════════════
    // DBR ANIMATION
    // ═══════════════════════════════════════════════════════

    const DBR = {
        running: false,
        drumMode: false,
        ropeMode: false,
        timer: null,
        tick: 0,
        items: [],
        throughput: 0,
        wip: 0,
        bufferPct: 100,
        nextId: 0,
        ropeSignal: false,

        // 5 станций: скорости (тиков на обработку)
        // Станция 3 — узкое место (медленнее всех)
        stations: [
            { id: 0, name: 'С1',   x: 80,  speed: 8,  queue: [], busy: 0 },
            { id: 1, name: 'С2',   x: 185, speed: 10, queue: [], busy: 0 },
            { id: 2, name: 'БАРАБАН', x: 290, speed: 22, queue: [], busy: 0 },
            { id: 3, name: 'С4',   x: 395, speed: 9,  queue: [], busy: 0 },
            { id: 4, name: 'С5',   x: 500, speed: 7,  queue: [], busy: 0 },
        ],

        launchInterval: 15, // тиков между запусками
        lastLaunch: 0,
    };

    function dbrRender() {
        const canvas = document.getElementById('dbr-canvas');
        // Убираем старые элементы кроме трека и индикаторов
        Array.from(canvas.querySelectorAll('.station,.work-item,.rope,.buffer-bar-wrap,.buffer-label')).forEach(e => e.remove());

        // Станции
        DBR.stations.forEach((st, i) => {
            const isDrum = i === 2;
            const isOverload = !DBR.drumMode && st.queue.length > 3;
            const div = document.createElement('div');
            div.className = 'station' + (isDrum ? ' station-drum' : isOverload ? ' station-overload' : ' station-normal');
            div.style.left = st.x + 'px';
            div.innerHTML = `<div class="station-box">
                <span style="font-size:18px">${isDrum ? '🥁' : '⚙️'}</span>
                <span>${st.name}</span>
            </div>
            <div class="station-label">${isDrum ? 'Узкое место' : 'Ск:' + st.speed}</div>`;
            canvas.appendChild(div);
        });

        // Предмет труда (items) как точки на конвейере
        DBR.items.forEach(item => {
            const div = document.createElement('div');
            div.className = 'work-item' + (item.state === 'blocked' ? ' blocked' : item.state === 'done' ? ' processed' : '');
            div.style.left = item.x + 'px';
            canvas.appendChild(div);
        });

        // Буфер перед барабаном
        const bufWrap = document.createElement('div');
        bufWrap.className = 'buffer-bar-wrap';
        bufWrap.style.right = (canvas.offsetWidth - DBR.stations[2].x - 8) + 'px';
        const bufFill = document.createElement('div');
        bufFill.className = 'buffer-fill';
        const pct = Math.max(0, Math.min(100, DBR.bufferPct));
        bufFill.style.height = pct + '%';
        bufFill.style.background = pct > 60 ? 'var(--green)' : pct > 30 ? 'var(--orange)' : 'var(--red)';
        bufWrap.appendChild(bufFill);
        canvas.appendChild(bufWrap);

        const bufLabel = document.createElement('div');
        bufLabel.className = 'buffer-label';
        bufLabel.style.right = (canvas.offsetWidth - DBR.stations[2].x + 4) + 'px';
        bufLabel.innerHTML = 'БУФ<br>' + Math.round(pct) + '%';
        canvas.appendChild(bufLabel);

        // Канат (линия от барабана к входу)
        if (DBR.ropeMode) {
            const rope = document.createElement('div');
            rope.className = 'rope visible';
            rope.style.left = '10px';
            rope.style.width = (DBR.stations[2].x - 10) + 'px';
            rope.style.bottom = '68px';
            canvas.appendChild(rope);
        }

        // Метрики
        const queue2 = DBR.stations[2].queue.length;
        document.getElementById('dbr-throughput').textContent = DBR.throughput;
        document.getElementById('dbr-wip').textContent = queue2;
        document.getElementById('dbr-wip').className = 'metric-val' + (queue2 > 4 ? ' bad' : queue2 > 2 ? ' warn' : ' good');
        document.getElementById('dbr-buffer').textContent = Math.round(DBR.bufferPct) + '%';
        document.getElementById('dbr-mode').textContent = DBR.ropeMode ? 'DBR' : DBR.drumMode ? 'Барабан' : 'Нет';
        document.getElementById('dbr-drum-label').textContent = DBR.ropeMode ? 'БАРАБАН + БУФЕР + КАНАТ' : DBR.drumMode ? 'БАРАБАН (без каната)' : 'БЕЗ УПРАВЛЕНИЯ';
    }

    function dbrStep() {
        DBR.tick++;

        // Запуск нового задания
        let canLaunch = false;
        if (!DBR.ropeMode) {
            canLaunch = (DBR.tick - DBR.lastLaunch) >= DBR.launchInterval;
        } else {
            // Канат: запуск только когда барабан почти готов принять
            const drumQ = DBR.stations[2].queue.length;
            canLaunch = (DBR.tick - DBR.lastLaunch) >= DBR.launchInterval && drumQ < 2;
        }

        if (canLaunch) {
            DBR.items.push({ id: DBR.nextId++, x: 20, stationIdx: -1, progress: 0, state: 'moving' });
            DBR.lastLaunch = DBR.tick;
        }

        // Двигаем элементы
        const stationXs = DBR.stations.map(s => s.x + 19); // центр станции
        DBR.items = DBR.items.filter(item => item.x < 620);

        DBR.items.forEach(item => {
            if (item.state === 'moving') {
                // Двигаемся к следующей станции
                const nextSt = item.stationIdx + 1;
                if (nextSt >= DBR.stations.length) {
                    item.state = 'done';
                    item.x += 3;
                    return;
                }
                const targetX = stationXs[nextSt] - 13;
                if (item.x < targetX - 2) {
                    item.x += DBR.drumMode ? 2 : 3;
                } else {
                    // Встали у станции
                    item.x = targetX;
                    const st = DBR.stations[nextSt];
                    st.queue.push(item);
                    item.state = 'queued';
                    item.stationIdx = nextSt;
                }
            } else if (item.state === 'done') {
                item.x += 2;
            }
        });

        // Обрабатываем станции
        DBR.stations.forEach((st, i) => {
            if (st.busy > 0) {
                st.busy--;
                if (st.busy === 0 && st.queue.length > 0) {
                    const done = st.queue.shift();
                    done.state = 'moving';
                    done.stationIdx = i;
                    if (i === DBR.stations.length - 1) {
                        DBR.throughput++;
                        done.state = 'done';
                    }
                }
            } else if (st.queue.length > 0) {
                const item = st.queue[0];
                st.busy = st.speed;
                item.state = 'processing';
            }
        });

        // Буфер перед барабаном
        const drumQ = DBR.stations[2].queue.length;
        if (DBR.ropeMode) {
            // С канатом буфер защищён
            DBR.bufferPct = Math.max(0, 100 - drumQ * 8);
        } else {
            // Без управления — буфер тает при очереди
            DBR.bufferPct = Math.max(0, 100 - drumQ * 15);
        }

        // Статус
        const statusEl = document.getElementById('dbr-status');
        if (DBR.ropeMode && drumQ <= 1) statusEl.textContent = 'DBR работает: барабан не простаивает, НЗП минимально';
        else if (DBR.drumMode && drumQ > 3) statusEl.textContent = 'Барабан задаёт ритм, но канат ещё не ограничил входной поток';
        else if (!DBR.drumMode && drumQ > 5) statusEl.textContent = '⚠️ Очередь перед узким местом растёт — система перегружена';
    }

    function dbrLoop() {
        if (!DBR.running) return;
        dbrStep();
        dbrRender();
        DBR.timer = requestAnimationFrame(() => setTimeout(dbrLoop, 80));
    }

    function dbrPlay() {
        if (DBR.running) return;
        DBR.running = true;
        dbrLoop();
    }

    function dbrEnableDrum() {
        DBR.drumMode = true;
        DBR.launchInterval = 22;
        document.getElementById('dbr-status').textContent = 'Барабан активирован — ритм задаётся узким местом';
        if (!DBR.running) dbrPlay();
    }

    function dbrEnableRope() {
        DBR.drumMode = true;
        DBR.ropeMode = true;
        DBR.launchInterval = 24;
        document.getElementById('dbr-status').textContent = 'Канат привязан — запуск синхронизирован с барабаном';
        if (!DBR.running) dbrPlay();
    }

    function dbrReset() {
        DBR.running = false;
        cancelAnimationFrame(DBR.timer);
        clearTimeout(DBR.timer);
        Object.assign(DBR, { tick: 0, items: [], throughput: 0, wip: 0, bufferPct: 100,
            nextId: 0, lastLaunch: 0, drumMode: false, ropeMode: false, launchInterval: 15 });
        DBR.stations.forEach(s => { s.queue = []; s.busy = 0; });
        document.getElementById('dbr-status').textContent = 'Нажми «Запустить» чтобы начать';
        dbrRender();
    }

    // ═══════════════════════════════════════════════════════
    // CCPM ANIMATION
    // ═══════════════════════════════════════════════════════

    const CCPM = {
        running: false,
        timer: null,
        day: 0,
        maxDay: 120,
        speed: 80, // мс на тик
    };

    // Параметры отображения
    const CANVAS_LEFT = 90;   // px от левого края до начала временной шкалы
    const DAY_W = 3.8;        // px на день

    function dayToX(day) { return CANVAS_LEFT + day * DAY_W; }

    // Данные сценариев (в днях)
    // ТРАДИЦИОННЫЙ: каждая задача имеет полный срок + студенческий синдром
    const TRAD = [
        // [rowY, sleepStart, sleepEnd, workStart, workEnd, panicStart, panicEnd]
        // Задача 1: полный срок 30 дн, спит 20, работает 8, паникует 2 (итого 30, но задача зависит от 2й — выйдет на 34)
        { label: 'Задача 1', y: 25,  sleep: [0, 20],  work: [20, 28], panic: [28, 34], deadlineLocal: 30 },
        { label: 'Задача 2', y: 65,  sleep: [0, 22],  work: [22, 32], panic: null,     deadlineLocal: 30 },
        { label: 'Задача 3', y: 105, sleep: [0, 18],  work: [18, 25], panic: [25, 34], deadlineLocal: 30 },
    ];
    const TRAD_DEADLINE = 30;
    const TRAD_ACTUAL   = 34; // реальная сдача

    // CCPM: сжатые задачи (вдвое), эстафета, общий буфер
    const CC = [
        { label: 'Задача 1', y: 165, relay: [0, 12],  done: 12 },
        { label: 'Задача 2', y: 205, relay: [12, 24], done: 24 },
        { label: 'Задача 3', y: 245, relay: [24, 34], done: 34 },
    ];
    const CC_BUFFER_START = 34;
    const CC_BUFFER_END   = 50; // договорной срок с буфером
    const CC_ACTUAL       = 34; // реальная сдача (в буфер не зашли)

    function ccpmRender() {
        const canvas = document.getElementById('ccpm-canvas');
        canvas.innerHTML = '';
        const d = CCPM.day;
        const cw = canvas.offsetWidth || 700;

        // --- Дедлайн ---
        const deadX = dayToX(TRAD_DEADLINE);
        const dlLine = document.createElement('div');
        dlLine.className = 'gantt-deadline';
        dlLine.style.left = deadX + 'px';
        canvas.appendChild(dlLine);
        const dlLbl = document.createElement('div');
        dlLbl.className = 'gantt-deadline-label';
        dlLbl.style.left = deadX + 'px';
        dlLbl.textContent = 'Дедлайн (д.30)';
        canvas.appendChild(dlLbl);

        // --- Метки секций ---
        const lbl1 = document.createElement('div');
        lbl1.className = 'ccpm-phase-label';
        lbl1.style.top = '18px'; lbl1.style.left = '4px';
        lbl1.textContent = 'Традиционный подход';
        canvas.appendChild(lbl1);

        const lbl2 = document.createElement('div');
        lbl2.className = 'ccpm-phase-label';
        lbl2.style.top = '158px'; lbl2.style.left = '4px';
        lbl2.textContent = 'Критическая цепь';
        canvas.appendChild(lbl2);

        // Разделитель
        const sep = document.createElement('div');
        sep.style.cssText = `position:absolute;top:148px;left:0;right:0;height:1px;background:var(--border)`;
        canvas.appendChild(sep);

        // === ТРАДИЦИОННЫЙ ===
        TRAD.forEach(row => {
            // Ярлык
            const lbl = document.createElement('div');
            lbl.className = 'gantt-row-label';
            lbl.style.top = (row.y + 5) + 'px';
            lbl.textContent = row.label;
            canvas.appendChild(lbl);

            // Студенческий синдром (сон)
            if (d > row.sleep[0]) {
                const sleepEnd = Math.min(d, row.sleep[1]);
                const bar = document.createElement('div');
                bar.className = 'gantt-bar bar-sleep';
                bar.style.left  = dayToX(row.sleep[0]) + 'px';
                bar.style.width = Math.max(0, dayToX(sleepEnd) - dayToX(row.sleep[0])) + 'px';
                bar.style.top   = row.y + 'px';
                if (sleepEnd > row.sleep[0] + 3) bar.textContent = 'zzz';
                canvas.appendChild(bar);
            }

            // Работа
            if (d > row.work[0]) {
                const workEnd = Math.min(d, row.work[1]);
                const bar = document.createElement('div');
                bar.className = 'gantt-bar bar-task';
                bar.style.left  = dayToX(row.work[0]) + 'px';
                bar.style.width = Math.max(0, dayToX(workEnd) - dayToX(row.work[0])) + 'px';
                bar.style.top   = row.y + 'px';
                if (workEnd - row.work[0] > 4) bar.textContent = 'Работа';
                canvas.appendChild(bar);
            }

            // Паника
            if (row.panic && d > row.panic[0]) {
                const panicEnd = Math.min(d, row.panic[1]);
                const bar = document.createElement('div');
                bar.className = 'gantt-bar bar-panic';
                bar.style.left  = dayToX(row.panic[0]) + 'px';
                bar.style.width = Math.max(0, dayToX(panicEnd) - dayToX(row.panic[0])) + 'px';
                bar.style.top   = row.y + 'px';
                if (panicEnd - row.panic[0] > 2) bar.textContent = '🔥';
                canvas.appendChild(bar);
            }
        });

        // === CCPM ===
        CC.forEach(row => {
            // Ярлык
            const lbl = document.createElement('div');
            lbl.className = 'gantt-row-label';
            lbl.style.top = (row.y + 5) + 'px';
            lbl.textContent = row.label;
            canvas.appendChild(lbl);

            // Эстафета
            if (d > row.relay[0]) {
                const relayEnd = Math.min(d, row.relay[1]);
                const bar = document.createElement('div');
                bar.className = 'gantt-bar bar-relay';
                bar.style.left  = dayToX(row.relay[0]) + 'px';
                bar.style.width = Math.max(0, dayToX(relayEnd) - dayToX(row.relay[0])) + 'px';
                bar.style.top   = row.y + 'px';
                if (relayEnd - row.relay[0] > 4) bar.textContent = '🏃 Эстафета';
                canvas.appendChild(bar);
            }
        });

        // Буфер
        if (d > CC_BUFFER_START) {
            const bufEnd = Math.min(d, CC_BUFFER_END);
            const consumed = Math.max(0, CC_ACTUAL - CC_BUFFER_START); // 0 — не зашли в буфер
            const bufW = dayToX(CC_BUFFER_END) - dayToX(CC_BUFFER_START);
            const usedW = (consumed / (CC_BUFFER_END - CC_BUFFER_START)) * bufW;

            const bufBg = document.createElement('div');
            bufBg.className = 'gantt-bar bar-buffer';
            bufBg.style.left  = dayToX(CC_BUFFER_START) + 'px';
            bufBg.style.width = Math.max(0, dayToX(bufEnd) - dayToX(CC_BUFFER_START)) + 'px';
            bufBg.style.top   = '155px';
            bufBg.style.height = '80px';
            bufBg.style.opacity = '0.25';
            bufBg.style.zIndex = '0';
            canvas.appendChild(bufBg);

            const bufLbl = document.createElement('div');
            bufLbl.style.cssText = `position:absolute;left:${dayToX(CC_BUFFER_START) + 4}px;top:176px;font-size:10px;font-weight:700;color:var(--orange);z-index:2`;
            bufLbl.textContent = 'БУФЕР ПРОЕКТА (' + (CC_BUFFER_END - CC_BUFFER_START) + ' дн.)';
            canvas.appendChild(bufLbl);
        }

        // Линия "сдано раньше срока"
        if (d >= CC_ACTUAL) {
            const dline = document.createElement('div');
            dline.style.cssText = `position:absolute;left:${dayToX(CC_ACTUAL)}px;top:155px;bottom:20px;width:2px;background:var(--green);z-index:3`;
            canvas.appendChild(dline);
            const dlbl = document.createElement('div');
            dlbl.style.cssText = `position:absolute;left:${dayToX(CC_ACTUAL) + 4}px;top:158px;font-size:9px;font-weight:700;color:var(--green)`;
            dlbl.textContent = '✓ Сдано (д.' + CC_ACTUAL + ')';
            canvas.appendChild(dlbl);
        }

        // Временная шкала
        const timeline = document.createElement('div');
        timeline.className = 'gantt-timeline';
        canvas.appendChild(timeline);
        [0, 10, 20, 30, 40, 50].forEach(day => {
            const tick = document.createElement('div');
            tick.className = 'gantt-tick';
            tick.style.left = dayToX(day) - CANVAS_LEFT + 'px';
            tick.textContent = 'д.' + day;
            timeline.appendChild(tick);
        });

        // Метрики
        document.getElementById('ccpm-day').textContent = d;
        if (d >= TRAD_ACTUAL) {
            document.getElementById('ccpm-trad-end').textContent = 'Сдача: д.' + TRAD_ACTUAL;
            document.getElementById('ccpm-trad-end').className = 'metric-val bad';
        } else if (d >= TRAD_DEADLINE) {
            document.getElementById('ccpm-trad-end').textContent = '🔥 Просрочено';
            document.getElementById('ccpm-trad-end').className = 'metric-val bad';
        }

        if (d >= CC_ACTUAL) {
            document.getElementById('ccpm-cc-end').textContent = 'Сдача: д.' + CC_ACTUAL;
            document.getElementById('ccpm-cc-end').className = 'metric-val good';
        }

        // Буфер %
        const bufUsed = Math.max(0, Math.min(d, CC_ACTUAL) - CC_BUFFER_START);
        const bufTotal = CC_BUFFER_END - CC_BUFFER_START;
        const bufLeft = Math.max(0, 100 - Math.round(bufUsed / bufTotal * 100));
        const bufEl = document.getElementById('ccpm-buffer-pct');
        bufEl.textContent = bufLeft + '%';
        bufEl.className = 'metric-val' + (bufLeft > 60 ? ' good' : bufLeft > 30 ? ' warn' : ' bad');
    }

    function ccpmStep() {
        CCPM.day = Math.min(CCPM.day + 1, CCPM.maxDay);
        ccpmRender();

        if (CCPM.day >= 55) {
            CCPM.running = false;
            document.getElementById('ccpm-status').textContent =
                'Итог: Традиционный — просрочка на ' + (TRAD_ACTUAL - TRAD_DEADLINE) + ' дня. CCPM — сдача в день ' + CC_ACTUAL + ', буфер почти не тронут.';
        }
    }

    function ccpmPlay() {
        if (CCPM.running) return;
        CCPM.running = true;
        document.getElementById('ccpm-status').textContent = 'Симуляция запущена...';
        const loop = () => {
            if (!CCPM.running) return;
            ccpmStep();
            if (CCPM.running) CCPM.timer = setTimeout(loop, CCPM.speed);
        };
        loop();
    }

    function ccpmReset() {
        CCPM.running = false;
        clearTimeout(CCPM.timer);
        CCPM.day = 0;
        document.getElementById('ccpm-status').textContent = 'Нажми «Запустить» — увидишь оба подхода одновременно';
        document.getElementById('ccpm-trad-end').textContent = '—';
        document.getElementById('ccpm-trad-end').className = 'metric-val bad';
        document.getElementById('ccpm-cc-end').textContent = '—';
        document.getElementById('ccpm-cc-end').className = 'metric-val good';
        document.getElementById('ccpm-buffer-pct').textContent = '100%';
        document.getElementById('ccpm-day').textContent = '0';
        ccpmRender();
    }

    // Инит
    dbrRender();
    ccpmReset();
    </script>
<?php include 'footer.php'; ?>
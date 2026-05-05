/**
 * CsvEngine — импорт, экспорт, шаблоны CSV (BOM для Excel/кириллица)
 */
const CsvEngine = {

    download(filename, headers, rows) {
        let content = '\uFEFF' + headers.join(';') + '\n';
        rows.forEach(row => {
            content += row.map(v => {
                const s = String(v ?? '').replace(/"/g, '""');
                return s.includes(';') ? `"${s}"` : s;
            }).join(';') + '\n';
        });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(new Blob([content], { type: 'text/csv;charset=utf-8;' }));
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    },

    downloadTemplate(filename, headers, sampleRow) {
        this.download(filename, headers, [sampleRow]);
    },

    parse(file, callback) {
        const reader = new FileReader();
        reader.onload = e => {
            const lines = e.target.result.split(/\r?\n/);
            const data = [];
            lines.forEach(line => {
                if (!line.trim()) return;
                data.push(line.split(';').map(p =>
                    p.replace(/(^"|"$)/g, '').replace(/""/g, '"').trim()
                ));
            });
            callback(data);
        };
        reader.readAsText(file, 'utf-8');
    }
};

/**
 * setupCsvManagers — привязывает все CSV-кнопки к данным приложения.
 * Вызывается из app.js после DOMContentLoaded.
 */
function setupCsvManagers() {

    // ── РАЗДЕЛЫ ──────────────────────────────────────────────
    const SEC_HEADERS  = ['Шифр', 'Наименование'];
    const SEC_SAMPLE   = ['АР', 'Архитектурные решения'];

    document.getElementById('btn-tpl-sec')?.addEventListener('click', () => {
        CsvEngine.downloadTemplate('шаблон_разделы.csv', SEC_HEADERS, SEC_SAMPLE);
    });

    document.getElementById('btn-export-sec')?.addEventListener('click', () => {
        const rows = [];
        document.querySelectorAll('.sec-cb:checked').forEach(cb => {
            rows.push([cb.dataset.code, cb.dataset.name]);
        });
        if (rows.length === 0) {
            // Экспортируем весь текущий состав таблицы задач
            (window.currentTasks || []).forEach(t => {
                if (!t.isBuffer && !t.isManagement) {
                    const m = t.name.match(/^\[(.+?)\]\s*(.+)/);
                    rows.push(m ? [m[1], m[2]] : ['', t.name]);
                }
            });
        }
        if (rows.length === 0) return alert('Нет отмеченных разделов для экспорта.');
        CsvEngine.download('разделы_' + _today() + '.csv', SEC_HEADERS, rows);
    });

    document.getElementById('csv-import-sec')?.addEventListener('change', e => {
        const file = e.target.files[0];
        if (!file) return;
        CsvEngine.parse(file, data => {
            const today = _dateStr();
            let added = 0;
            data.forEach((row, i) => {
                if (i === 0 && row[0].toLowerCase() === 'шифр') return; // пропускаем заголовок
                const code = (row[0] || '').trim();
                const name = (row[1] || '').trim();
                if (!code || !name) return;
                if (!(window.currentTasks || []).find(t => t.name.startsWith(`[${code}]`))) {
                    (window.currentTasks = window.currentTasks || []).push({
                        id: 'Task_' + Date.now() + Math.floor(Math.random() * 1000),
                        name: `[${code}] ${name}`,
                        start: today, end: today, progress: 0, dependencies: [], resource: '', utilization: 100
                    });
                    added++;
                }
            });
            e.target.value = '';
            if (added > 0 && typeof cascadeDates === 'function') {
                cascadeDates();
                if (typeof syncBufferTask === 'function') syncBufferTask();
                if (typeof saveToServer === 'function') saveToServer(window.currentTasks);
            } else {
                alert(added === 0 ? 'Все разделы уже есть в графике или файл пуст.' : '');
            }
        });
    });

    // ── ЗАДАЧИ / ГРАФИК ──────────────────────────────────────
    const TASK_HEADERS = ['ID', 'Наименование', 'Начало (ГГГГ-ММ-ДД)', 'Конец (ГГГГ-ММ-ДД)', 'Длительность (дн.)', 'Исполнитель', 'Загрузка (%)', 'Предшественник ID'];
    const TASK_SAMPLE  = ['Task_001', 'Раздел АР', _dateStr(), _dateStr(14), '14', 'Архитектор', '100', ''];

    document.getElementById('btn-tpl-tasks')?.addEventListener('click', () => {
        CsvEngine.downloadTemplate('шаблон_график.csv', TASK_HEADERS, TASK_SAMPLE);
    });

    document.getElementById('btn-export-tasks')?.addEventListener('click', () => {
        const tasks = (window.currentTasks || []).filter(t => !t.isBuffer);
        if (tasks.length === 0) return alert('График пуст.');
        const rows = tasks.map(t => {
            const dur = Math.max(1, Math.ceil((new Date(t.end) - new Date(t.start)) / 86400000) + 1);
            return [t.id, t.name, t.start, t.end, dur, t.resource || '', t.utilization ?? 100, (t.dependencies || []).join(',')];
        });
        CsvEngine.download('график_' + _today() + '.csv', TASK_HEADERS, rows);
    });

    document.getElementById('csv-import-tasks')?.addEventListener('change', e => {
        const file = e.target.files[0];
        if (!file) return;
        CsvEngine.parse(file, data => {
            let added = 0;
            data.forEach((row, i) => {
                if (i === 0) return; // пропускаем заголовок
                const name  = (row[1] || '').trim();
                const start = (row[2] || _dateStr()).trim();
                const end   = (row[3] || _dateStr()).trim();
                if (!name) return;
                const dur = parseInt(row[4]) || 1;
                const endDate = end !== start ? end : _dateStr(dur - 1, new Date(start));
                window.currentTasks = window.currentTasks || [];
                window.currentTasks.push({
                    id:           row[0]?.trim() || 'Task_' + Date.now() + Math.floor(Math.random() * 1000),
                    name, start,
                    end:          endDate,
                    progress:     0,
                    dependencies: row[7] ? row[7].split(',').map(s => s.trim()).filter(Boolean) : [],
                    resource:     (row[5] || '').trim(),
                    utilization:  parseInt(row[6]) || 100
                });
                added++;
            });
            e.target.value = '';
            if (added > 0 && typeof cascadeDates === 'function') {
                cascadeDates();
                if (typeof syncBufferTask === 'function') syncBufferTask();
                if (typeof saveToServer   === 'function') saveToServer(window.currentTasks);
            }
        });
    });

    // ── СТАВКИ / РЕСУРСЫ ─────────────────────────────────────
    const RATE_HEADERS = ['Роль', 'Ставка (₽/чел-день)'];
    const RATE_SAMPLE  = ['ГИП', '8000'];

    document.getElementById('btn-tpl-rates')?.addEventListener('click', () => {
        // Шаблон со всеми текущими ролями
        const rows = (window.availableResources || ['ГИП','ГАП','Архитектор','Конструктор','Специалист ТИМ'])
            .map(r => [r, window.globalRate || 5000]);
        CsvEngine.download('шаблон_ставки.csv', RATE_HEADERS, rows);
    });

    document.getElementById('btn-export-rates')?.addEventListener('click', () => {
        const rows = (window.availableResources || []).map(r => [r, window.globalRate || 5000]);
        if (rows.length === 0) return alert('Нет данных о ставках.');
        CsvEngine.download('ставки_' + _today() + '.csv', RATE_HEADERS, rows);
    });

    document.getElementById('csv-import-rates')?.addEventListener('change', e => {
        const file = e.target.files[0];
        if (!file) return;
        CsvEngine.parse(file, data => {
            data.forEach((row, i) => {
                if (i === 0) return;
                const role = (row[0] || '').trim();
                const rate = parseFloat(row[1]);
                if (!role) return;
                if (!window.availableResources) window.availableResources = [];
                if (!window.availableResources.includes(role)) window.availableResources.push(role);
                if (!isNaN(rate) && rate > 0) window.globalRate = rate; // упрощённо — одна глобальная ставка
            });
            e.target.value = '';
            if (typeof renderUI === 'function') renderUI();
            if (typeof renderEconomics === 'function') renderEconomics();
        });
    });
}

// ── Helpers ──────────────────────────────────────────────────
function _today() {
    const d = new Date();
    return `${d.getFullYear()}${String(d.getMonth()+1).padStart(2,'0')}${String(d.getDate()).padStart(2,'0')}`;
}

function _dateStr(offsetDays = 0, base = new Date()) {
    const d = new Date(base);
    d.setDate(d.getDate() + offsetDays);
    return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
}

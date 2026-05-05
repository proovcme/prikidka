<footer style="background:var(--card-bg);border-top:1px solid var(--border);padding:30px 20px;margin-top:auto;text-align:center;font-size:13px;color:var(--muted);">
        <div style="max-width:1000px;margin:0 auto;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:20px;">
            <div style="text-align:left;">
                <b style="color:var(--navy);display:block;margin-bottom:5px;">Чернетченко Олег</b>
                Проектирование, ИИ и Теория Ограничений<br>
                <a href="https://chernetchenko.pro" target="_blank" style="color:var(--orange);text-decoration:none;font-weight:bold;">chernetchenko.pro</a>
            </div>
            <div style="text-align:center;">
                <pre style="font-family:monospace;font-size:10px;margin:0;line-height:1.1;opacity:0.5;">
 /\_/\
( o.o )
 > ^ <</pre>
            </div>
            <div style="text-align:right;display:flex;flex-direction:column;gap:6px;align-items:flex-end;">
                <a href="https://waf.chernetchenko.pro" style="color:#d32f2f;font-size:11px;text-decoration:none;border:1px dashed #d32f2f;padding:3px 8px;border-radius:3px;">Прикладной ИИ →</a>
                <a href="https://fun.chernetchenko.pro" style="color:#6a1b9a;font-size:11px;text-decoration:none;border:1px dashed #6a1b9a;padding:3px 8px;border-radius:3px;">Лаборатория →</a>
                <a href="https://t.me/WeAreFired" target="_blank" class="btn btn-outline" style="border-color:#38bdf8;color:#38bdf8;background:transparent;margin-top:2px;">Читать канал WeAreFired</a>
                <div style="font-size:11px;margin-top:4px;">© <?= date('Y') ?>. Никакой магии. Только расчёт.</div>
            </div>
        </div>
    </footer>
    <?php if (!empty($needsGantt)): ?>
    <script src="/frappe-gantt.min.js"></script>
    <?php endif; ?>
    <script src="/csv-manager.js"></script>
    <script src="/app.js"></script>
</body>
</html>

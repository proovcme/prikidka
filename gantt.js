function syncBufferTask() {
    if (appMode === 'simple') {
        currentTasks = currentTasks.filter(t => !t.isBuffer);
        return;
    }
    
    const { timeDays } = calcRiskReserves();
    
    // Удаляем старый буфер
    currentTasks = currentTasks.filter(t => !t.isBuffer);
    
    if (timeDays <= 0) { 
        const badge = document.getElementById('badge-toc-buffer');
        if (badge) {
            const thresh = window.RISK_THRESHOLD || 4;
            badge.textContent = `ТОС-буфер: 0 дн. (нет рисков ≥${thresh})`;
            badge.style.color = 'var(--c-toc)';
            badge.title = `Все риски ниже порога ${thresh}. Добавьте риски или снизьте порог.`;
        }
        renderUI(); 
        return; 
    }
    
    // Ищем конец графика
    const workTasks = currentTasks.filter(t => !t.isBuffer && !t.isMilestone && t.end);
    if (workTasks.length === 0) return;
    
    const lastEnd = new Date(Math.max(...workTasks.map(t => new Date(t.end).getTime())));
    const bufStart = new Date(lastEnd); bufStart.setDate(bufStart.getDate() + 1);
    const bufEnd = new Date(bufStart); bufEnd.setDate(bufEnd.getDate() + timeDays - 1);
    
    currentTasks.push({
        id: 'Buffer_TOC', name: `[ТОС] Буфер (${timeDays} дн.)`,
        start: getLocalDateString(bufStart), end: getLocalDateString(bufEnd),
        progress: 0, dependencies: [], resource: 'ТОС', utilization: 0, isBuffer: true
    });
    
    saveToServer(currentTasks);
    renderUI();
}

window.deleteTask = function(id) {
    const t = currentTasks.find(x => x.id === id);
    if (t && (t.isManagement || t.isBuffer)) return; // Защита системных задач
    
    if (!confirm('Удалить задачу "' + t.name + '" из графика?')) return;
    
    currentTasks = currentTasks.filter(x => x.id !== id);
    cascadeDates();
    syncBufferTask();
    saveToServer(currentTasks);
};

function initSections() { const grid=document.getElementById('sections-grid'); if(!grid) return; grid.innerHTML=''; const s=SECTIONS_DATA[currentStage]; for(const c in s) grid.innerHTML+=`<label style="display:flex;align-items:center;gap:8px;font-size:12px;background:#f9f9f9;padding:8px 10px;border:1px solid var(--border);border-radius:4px;cursor:pointer;"><input type="checkbox" class="sec-cb" data-code="${c}" data-name="${s[c]}" style="accent-color:var(--c-toc);width:16px;height:16px;"><b style="color:var(--text-main);min-width:45px;">${c}</b><span>${s[c]}</span></label>`; }

function refreshApp() { fetch('api_gantt.php?nocache='+Date.now()).then(r=>r.json()).then(d=>{currentTasks=(Array.isArray(d)?d:(d.tasks||[])).map(t=>({...t,dependencies:Array.isArray(t.dependencies)?t.dependencies:(t.dependencies?t.dependencies.split(',').map(x=>x.trim()):[]),resource:t.resource||'',utilization:t.utilization!==undefined?parseInt(t.utilization):100})); syncBufferTask(); renderUI(); renderRisks(); }).catch(e=>console.error(e)); }

function saveToServer(d) { fetch('api_save.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(d)}).then(r=>r.json()).catch(e=>console.error('Save failed:', e)); renderUI(); renderRisks(); }

function renderUI() {
const isToc = (appMode === 'toc');
const container = document.getElementById('gantt-container');
renderTaskTable();
if (!container || (!isToc && !isGipRole())) return; 

if (currentTasks.length===0) { container.innerHTML="<p style='padding:20px;text-align:center;'>График пуст.</p>"; renderEconomics(); return; }
if (typeof Gantt==='undefined') { setTimeout(renderUI,100); return; }

const validIds = new Set(currentTasks.map(t=>t.id));
const gt = currentTasks.map((t,i) => ({ id:t.id, name:`${i+1}. ${t.name}`, start:isNaN(new Date(t.start).getTime())?getLocalDateString():t.start, end:isNaN(new Date(t.end).getTime())?getLocalDateString():t.end, progress:0, dependencies:(t.dependencies||[]).filter(d=>validIds.has(d)).join(', '), custom_class:t.isMilestone?'bar-milestone':t.isManagement?'bar-management':t.isBuffer?'bar-buffer':'' }));
container.innerHTML='';
try {
    ganttInstance = new Gantt('#gantt-container', gt, {
        header_height:50, view_mode: currentGanttView, language:'ru', on_click: task => openModal(task),
        on_date_change: (task,s,e) => { const t=currentTasks.find(x=>x.id===task.id); if(t&&!t.isBuffer){t.start=getLocalDateString(s);t.end=getLocalDateString(new Date(e.getTime()-43200000));if(t.isMilestone)t.end=t.start;cascadeDates();syncBufferTask();saveToServer(currentTasks);} }
    });
} catch(e) {
    console.error('[Gantt Error]', e);
    container.innerHTML = `<p style="color:var(--c-ai);padding:20px;text-align:center;">Ошибка отображения диаграммы Ганта: ${e.message}</p>`;
}
renderEconomics();
}

function renderTaskTable() {
const tbody = document.getElementById('task-table-body'); if (!tbody) return;
const isToc = (appMode === 'toc');
const isGip = isGipRole();
const displayFot = isGip ? 'none' : 'table-cell';
let fot = 0; let html = '';

currentTasks.forEach((t,i) => {
    const tf = calcTaskFot(t); fot += tf;
    const dur = t.isMilestone||t.isBuffer ? 0 : Math.max(1, Math.ceil((new Date(t.end)-new Date(t.start))/86400000)+1);
    let depOpt = '<option value="">Нет</option>';
    currentTasks.forEach((ct,ci)=>{if(ct.id!==t.id&&!ct.isBuffer) depOpt+=`<option value="${ct.id}" ${(t.dependencies?.includes(ct.id))?'selected':''}>${ci+1}. ${esc(ct.name)}</option>`;});
    const resArr=t.resource?t.resource.split(',').map(r=>r.trim()).filter(Boolean):[];
    const rCb = availableResources.map(r=>`<label style="display:block;font-size:11px;margin-bottom:4px;cursor:pointer;"><input type="checkbox" class="cb-res-${t.id}" value="${r}" ${resArr.includes(r)?'checked':''}> ${r}</label>`).join('');
    const isL = t.isManagement||t.isBuffer;
    const trBg = t.isBuffer?'#fff4e5':t.isManagement?'#f0fbf4':t.isMilestone?'#fff5f5':'transparent';
    
    html += `<tr style="background:${trBg};" data-index="${i}">
         <td style="text-align:center;"><input type="number" class="inline-input inline-order" data-id="${t.id}" value="${i+1}" min="1" style="width:38px;" ${isL?'disabled':''}></td>
         <td>
             <input type="text" class="inline-input inline-name-input text-clamp" data-id="${t.id}" value="${esc(t.name)}" title="${esc(t.name)}" 
                   style="width:100%;font-weight:${isL?'700':'400'};color:${t.isBuffer?'var(--c-fun)':t.isMilestone?'var(--c-ai)':'inherit'};font-size:12px;background:transparent;border:none;" ${isL?'readonly':''}>
         </td>
         <td style="display:${(isToc||isGip)?'table-cell':'none'};"><select class="inline-input inline-dep-select" data-id="${t.id}" style="font-size:11px;width:130px;">${depOpt}</select></td>
         <td style="position:relative;min-width:110px;">
            ${!t.isBuffer ? `<button class="btn btn-outline btn-res-toggle" data-id="${t.id}" style="width:100%;font-size:10px;padding:3px 6px;margin-bottom:4px;">Назначить ▾</button>
             <div id="res-drop-${t.id}" style="display:none;position:absolute;top:30px;left:0;z-index:200;background:#fff;border:1px solid var(--text-main);padding:8px;border-radius:4px;width:200px;">${rCb}<button class="btn btn-dark btn-res-apply" data-id="${t.id}" style="margin-top:6px;width:100%;padding:4px;">Ок</button></div>` : ''}
             <div class="text-clamp" style="font-size:10px;color:var(--muted);max-width:130px;" title="${t.isBuffer?'—':resArr.join(', ')}">${t.isBuffer?'—':resArr.join(', ')||'—'}</div>
         </td>
         <td><input type="number" class="inline-input inline-util-input" data-id="${t.id}" value="${t.isBuffer?0:Math.round(t.utilization||100)}" min="0" style="width:42px;text-align:center;" ${isL?'readonly':''}>%</td>
         <td><input type="date" class="inline-input inline-start-input" data-id="${t.id}" value="${t.start}" style="width:115px;" ${isL?'readonly':''}></td>
         <td><input type="date" class="inline-input inline-end-input" data-id="${t.id}" value="${t.end}" style="width:115px;" ${t.isBuffer||t.isMilestone?'readonly':''}></td>
         <td><input type="number" class="inline-input inline-dur-input" data-id="${t.id}" value="${t.isBuffer?0:dur}" min="1" style="width:45px;text-align:center;" ${isL?'readonly':''}>дн.</td>
         <td style="text-align:right;font-weight:600;font-size:12px;display:${displayFot};">${t.isBuffer?'—':fmt(tf)+' ₽'}</td>
         <td style="text-align:center; vertical-align:middle;">
             ${(t.isManagement || t.isBuffer) ? '<span style="color:#ccc;">—</span>' : `<button onclick="deleteTask('${t.id}')" class="btn btn-outline" style="padding:2px 8px;color:var(--c-ai);border-color:var(--c-ai);font-size:12px;">✕</button>`}
         </td>
     </tr>`;
});

// ⬇️ Обновлено: 9 и 8 вместо 8 и 7
const colSpan = (isToc || isGip) ? 9 : 8;
html += `<tr style="background:var(--text-main);font-weight:700;"><td colspan="${colSpan}" style="padding:8px 12px;color:rgba(255,255,255,0.7);font-size:12px;">ИТОГО ФОТ по графику:</td><td style="padding:8px 12px;text-align:right;color:#fff;font-size:15px;white-space:nowrap;display:${displayFot};">${fmt(fot)} ₽</td></tr>`;
tbody.innerHTML = html;

tbody.querySelectorAll('.inline-name-input').forEach(el=>el.addEventListener('change', e=>{
    const t=currentTasks.find(x=>x.id===e.target.dataset.id);
    if(t){ t.name=e.target.value; saveToServer(currentTasks); }
}));

tbody.querySelectorAll('.inline-dep-select').forEach(el=>el.addEventListener('change', e=>{const t=currentTasks.find(x=>x.id===e.target.dataset.id);if(t){t.dependencies=e.target.value?[e.target.value]:[];cascadeDates();syncBufferTask();saveToServer(currentTasks);}}));
tbody.querySelectorAll('.btn-res-toggle').forEach(btn => {
    btn.addEventListener('click', e => { 
        e.preventDefault(); e.stopPropagation(); 
        const id=e.currentTarget.dataset.id, drop=document.getElementById('res-drop-'+id), isH=drop?drop.style.display==='none':false;
        document.querySelectorAll('[id^="res-drop-"]').forEach(d=>d.style.display='none');
        if(drop) drop.style.display=isH?'block':'none'; 
    });
});
tbody.querySelectorAll('.btn-res-apply').forEach(btn => {
    btn.addEventListener('click', e => {
        e.preventDefault(); e.stopPropagation();
        const id=e.currentTarget.dataset.id, t=currentTasks.find(x=>x.id===id);
        if(t){ t.resource=Array.from(document.querySelectorAll(`.cb-res-${id}:checked`)).map(cb=>cb.value).join(', '); document.getElementById('res-drop-'+id).style.display='none'; saveToServer(currentTasks); }
    });
});
tbody.querySelectorAll('[id^="res-drop-"]').forEach(d=>d.addEventListener('click', e=>e.stopPropagation()));
tbody.querySelectorAll('.inline-util-input').forEach(el=>el.addEventListener('change', e=>{const t=currentTasks.find(x=>x.id===e.target.dataset.id);if(t){t.utilization=Math.min(100,Math.max(0,parseInt(e.target.value)||0));saveToServer(currentTasks);}}));

tbody.querySelectorAll('.inline-start-input,.inline-end-input').forEach(el=>el.addEventListener('change', e=>{
    const t=currentTasks.find(x=>x.id===e.target.dataset.id);
    if(t&&!t.isManagement&&!t.isBuffer){
        if(e.target.classList.contains('inline-start-input')){
            t.start=e.target.value;
            if(t.isMilestone) t.end=t.start; 
        } else {
             if(!t.isMilestone) t.end=e.target.value;
            else t.end=t.start;
        }
        if(new Date(t.start)>new Date(t.end)) t.end=t.start;
        cascadeDates();syncBufferTask();saveToServer(currentTasks);
    }
}));

tbody.querySelectorAll('.inline-dur-input').forEach(el=>el.addEventListener('change', e=>{const t=currentTasks.find(x=>x.id===e.target.dataset.id);if(t&&!t.isManagement&&!t.isMilestone&&!t.isBuffer){const s=new Date(t.start);const end=new Date(s);end.setDate(s.getDate()+(parseInt(e.target.value)||1)-1);t.end=getLocalDateString(end);cascadeDates();syncBufferTask();saveToServer(currentTasks);}}));
tbody.querySelectorAll('.inline-order').forEach(el=>el.addEventListener('change', e=>{const oi=currentTasks.findIndex(x=>x.id===e.target.dataset.id);if(currentTasks[oi].isManagement||currentTasks[oi].isBuffer)return;let ni=Math.min(Math.max(0,parseInt(e.target.value)-1),currentTasks.length-1);if(currentTasks[ni]?.isManagement||currentTasks[ni]?.isBuffer)ni=1;const item=currentTasks.splice(oi,1)[0];currentTasks.splice(ni,0,item);cascadeDates();syncBufferTask();saveToServer(currentTasks);}));
}

function cascadeDates() {
let iterations=0,changed=true;
while (changed&&iterations<100) {
changed=false; iterations++;
for (const t of currentTasks) {
if (t.isBuffer||!t.dependencies?.length) continue;
let maxEnd=null;
for (const depId of t.dependencies) { const dep=currentTasks.find(x=>x.id===depId); if(dep){const d=new Date(dep.end);if(!maxEnd||d>maxEnd)maxEnd=d;} }
if (maxEnd) {
const reqStart=new Date(maxEnd); reqStart.setDate(reqStart.getDate()+1);
if (new Date(t.start).getTime()!==reqStart.getTime()) {
const diff=Math.round((new Date(t.end)-new Date(t.start))/86400000);
t.start=getLocalDateString(reqStart); const newEnd=new Date(reqStart); newEnd.setDate(newEnd.getDate()+diff);
t.end=getLocalDateString(newEnd); if (t.isMilestone) t.end=t.start; changed=true;
}
}
}
}
const mgmt=currentTasks.find(t=>t.isManagement);
if (mgmt) { const n=currentTasks.filter(t=>!t.isManagement&&!t.isBuffer); if(n.length){mgmt.start=n.map(t=>t.start).sort()[0]; mgmt.end=n.map(t=>t.end).sort().reverse()[0];} }
}


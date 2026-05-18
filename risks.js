function calcRiskReserves() {
    let timeBuf = 0, moneyBuf = 0;
    const PROB_WEIGHT = { 1: 0.2, 2: 0.5, 3: 0.8 };
    
    projectRisks.forEach(r => {
        const rating = (r.prob || 1) * (r.impact || 1);
        // Учитываем только если рейтинг выше порога
        if (rating >= window.RISK_THRESHOLD) {
            const w = PROB_WEIGHT[r.prob] || 0.5;
            timeBuf += (r.delay || 0) * w;
            moneyBuf += (r.cost || 0) * w;
        }
    });
    
    return { timeDays: Math.ceil(timeBuf), moneyRub: Math.round(moneyBuf) };
}

window.setRiskThreshold = function(val) {
    window.RISK_THRESHOLD = parseInt(val) || 4;
    try { localStorage.setItem(LS_RISK_THRESH, window.RISK_THRESHOLD); } catch(e){}
    syncBufferTask(); 
    renderUI(); 
    renderRisks(); 
    renderEconomics();
    const sel = document.getElementById('risk-threshold-select'); 
    if(sel) sel.value = window.RISK_THRESHOLD;
};

function renderRisks() {
const tbody=document.getElementById('risks-tbody'); if(!tbody) return;
for(let p=1;p<=3;p++) for(let i=1;i<=3;i++){const c=document.getElementById(`rm-${p}-${i}`);if(c){c.textContent='0';c.style.fontWeight='';c.style.fontSize='';c.style.transform='';c.style.boxShadow='';c.style.zIndex='1';}}
let td=0, tc=0;
const rows=projectRisks.map(r=>{
const c=document.getElementById(`rm-${r.prob}-${r.impact}`); if(c) { c.textContent=parseInt(c.textContent||0)+1; c.style.fontWeight='900'; c.style.fontSize='18px'; c.style.transform='scale(1.15)'; c.style.boxShadow='inset 0 0 0 3px rgba(0,0,0,0.4)'; c.style.zIndex='2'; }
const s=r.prob*r.impact; const z=s>=6?'<span style="color:var(--c-ai);">● ВЫСОКИЙ</span>':s>=3?'<span style="color:var(--c-fun);">● СРЕДНИЙ</span>':'<span style="color:var(--c-toc);">● НИЗКИЙ</span>';
td+=r.delay||0; tc+=r.cost||0;
return `<tr style="background:${r.isAuto?'#fff8f8':'transparent'}"><td style="padding:4px 6px;"><input type="text" value="${esc(r.desc)}" class="inline-input" style="width:100%;" onchange="updateRisk('${r.id}','desc',this.value)" ${r.isAuto?'readonly':''}></td><td style="text-align:center;"><input type="number" value="${r.prob}" min="1" max="3" class="inline-input" style="width:40px;text-align:center;" onchange="updateRisk('${r.id}','prob',this.value)"></td><td style="text-align:center;"><input type="number" value="${r.impact}" min="1" max="3" class="inline-input" style="width:40px;text-align:center;" onchange="updateRisk('${r.id}','impact',this.value)"></td><td style="text-align:center;font-weight:bold;">${z}</td><td style="text-align:right;"><input type="number" value="${r.cost}" step="10000" class="inline-input" style="width:90px;text-align:right;" onchange="updateRisk('${r.id}','cost',this.value)"></td><td style="text-align:center;"><input type="number" value="${r.delay||0}" class="inline-input" style="width:50px;text-align:center;" onchange="updateRisk('${r.id}','delay',this.value)"></td><td style="text-align:center;"><button onclick="delRisk('${r.id}')" class="btn btn-outline" style="color:var(--c-ai);padding:2px 8px;" title="${r.isAuto?'Удалить авто-риск':'Удалить риск'}">✕</button></td></tr>`;
});
tbody.innerHTML=rows.join('') + (projectRisks.length?`<tr style="background:#f5f3f0;font-weight:700;"><td colspan="4" style="padding:6px 8px;">ИТОГО:</td><td style="text-align:right;color:var(--c-ai);">${fmt(tc)} ₽</td><td style="text-align:center;color:var(--c-ai);">${Math.ceil(td)} дн.</td><td></td></tr>`:'<tr><td colspan="7" style="text-align:center;color:var(--muted);">Риски не заданы</td></tr>');
renderEconomics();
}

window.updateRisk = function(id, f, v) { const r=projectRisks.find(x=>x.id===id); if(!r) return; r[f]=f==='desc'?v:(parseFloat(v)||0); if(f==='prob'||f==='impact')r[f]=Math.min(3,Math.max(1,r[f])); syncBufferTask(); renderUI(); renderRisks(); };

window.delRisk = function(id) { projectRisks=projectRisks.filter(x=>x.id!==id); syncBufferTask(); renderUI(); renderRisks(); };


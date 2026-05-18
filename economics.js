
function esc(str) {
    if (!str) return '';
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
}

function saveEconomics() {
const data = {};
ECON_FIELDS.forEach(id => {
const el = document.getElementById(id);
if (!el) return;
data[id] = el.type === 'checkbox' ? el.checked : el.value;
});
lsSave(LS_ECON, data);
}

function loadEconomics() {
const data = lsLoad(LS_ECON, null);
if (!data) return;
ECON_FIELDS.forEach(id => {
const el = document.getElementById(id);
if (!el || data[id] === undefined) return;
if (el.type === 'checkbox') { el.checked = data[id]; }
else { el.value = data[id]; }
});
const expCb = document.getElementById('exp-checkbox');
if (expCb?.checked) {
['exp-type','exp-calc-type'].forEach(id => { const el=document.getElementById(id); if(el) el.style.display='inline-block'; });
const w = document.getElementById('exp-val-wrapper'); if(w) w.style.display='flex';
}
}

function calcTaskFot(task) {
if (task.isMilestone || task.isBuffer) return 0;
const dur = Math.max(1, Math.ceil((new Date(task.end) - new Date(task.start)) / 86400000) + 1);
const roles = task.resource ? task.resource.split(',').map(r => r.trim()).filter(Boolean) : [];
const util = (task.utilization !== undefined ? task.utilization : 100) / 100;
if (roles.length === 0) {
const avgRate = Object.values(window.resourceRates).reduce((a, b) => a + b, 0) / Math.max(1, Object.keys(window.resourceRates).length);
return dur * avgRate * util;
}
return roles.reduce((sum, role) => sum + dur * (window.resourceRates[role] || 5000) * util, 0);
}

function renderRates() {
const tbody = document.getElementById('rates-tbody');
if (!tbody) return;
let html = '';
window.availableResources.forEach(role => {
html += `<tr><td class="text-clamp" style="padding:6px 10px;font-size:13px;max-width:180px;" title="${esc(role)}">${esc(role)}</td><td style="padding:4px 8px;"><div style="display:flex;align-items:center;gap:4px;"><input type="number" value="${window.resourceRates[role] || 5000}" min="0" step="500" class="inline-input" style="width:90px;text-align:right;" onchange="updateRate('${esc(role)}', this.value)"><span style="font-size:11px;color:var(--muted);">₽/чел-день</span></div></td><td style="padding:4px 8px;text-align:right;font-size:12px;color:var(--muted);"> ~${fmt((window.resourceRates[role]||5000)*22)} ₽/мес </td><td style="padding:4px 8px;text-align:center;"><button onclick="deleteRole('${esc(role)}')" class="btn btn-outline" style="padding:2px 8px;color:var(--c-ai);border-color:var(--c-ai);">✕</button></td></tr>`;
});
tbody.innerHTML = html;
}

window.updateRate = function(role, val) {
window.resourceRates[role] = parseFloat(val) || 0;
lsSave(LS_RATES, window.resourceRates);
renderRates();
renderUI();
};

window.deleteRole = function(role) {
delete window.resourceRates[role];
window.availableResources = window.availableResources.filter(r => r !== role);
lsSave(LS_RATES, window.resourceRates);
renderRates();
renderUI();
};

window.addRole = function() {
const nameEl = document.getElementById('new-role-name');
const rateEl = document.getElementById('new-role-rate');
const name = nameEl?.value.trim();
const rate = parseFloat(rateEl?.value) || 5000;
if (!name) return;
if (!window.availableResources.includes(name)) {
    window.availableResources.push(name);
}
window.resourceRates[name] = rate;
lsSave(LS_RATES, window.resourceRates);
if (nameEl) nameEl.value = '';
renderRates();
renderUI();
};

function renderCapex() {
const tbody = document.getElementById('capex-tbody');
if (!tbody) return;
if (capexItems.length === 0) {
tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;color:var(--muted);padding:14px;font-size:13px;"> Добавьте позиции через «+ Из списка» или «+ Своя позиция» </td></tr>`;
renderEconomics();
return;
}
let html = '';
let totalAmort = 0;
capexItems.forEach((item, idx) => {
const projectMonths = getProjectMonths();
const amortMonthly = item.cost / Math.max(1, item.life) / 12;
const amortProject = amortMonthly * projectMonths;
totalAmort += amortProject;
html += `<tr><td style="padding:6px 10px;font-size:12px;" class="text-clamp" title="${esc(item.name)}">${esc(item.name)}</td><td style="padding:4px 8px;text-align:right;font-size:12px;">${fmt(item.cost)} ₽</td><td style="padding:4px 8px;text-align:center;font-size:12px;"><input type="number" value="${item.life}" min="1" max="20" class="inline-input" style="width:45px;text-align:center;" onchange="updateCapexLife(${idx}, this.value)"> лет </td><td style="padding:4px 8px;text-align:right;font-size:12px;color:var(--muted);">${fmt(amortMonthly)} ₽/мес</td><td style="padding:4px 8px;text-align:right;font-size:12px;font-weight:600;color:var(--text-main);">${fmt(amortProject)} ₽</td><td style="padding:4px 8px;text-align:center;"><button onclick="removeCapex(${idx})" class="btn btn-outline" style="padding:2px 8px;color:var(--c-ai);border-color:var(--c-ai);">✕</button></td></tr>`;
});
html += `<tr style="background:#f5f3f0;font-weight:700;border-top:2px solid var(--border);"><td colspan="4" style="padding:8px 10px;font-size:12px;">Итого амортизация за проект:</td><td style="padding:8px 10px;text-align:right;color:var(--text-main);">${fmt(totalAmort)} ₽</td><td></td></tr>`;
tbody.innerHTML = html;
const amortInput = document.getElementById('oh_amort_val');
if (amortInput) { amortInput.value = Math.round(totalAmort); renderEconomics(); }
else renderEconomics();
}

window.addCapexFromPreset = function(idx) {
const p = CAPEX_PRESETS[idx];
capexItems.push({ ...p });
lsSave(LS_CAPEX, capexItems);
renderCapex();
};

window.removeCapex = function(idx) {
capexItems.splice(idx, 1);
lsSave(LS_CAPEX, capexItems);
renderCapex();
};

window.updateCapexLife = function(idx, val) {
capexItems[idx].life = parseFloat(val) || 1;
lsSave(LS_CAPEX, capexItems);
renderCapex();
};

function renderCapexPresets() {
const el = document.getElementById('capex-presets-list');
if (!el) return;
el.innerHTML = CAPEX_PRESETS.map((p, i) =>
`<div style="display:flex;justify-content:space-between;align-items:center;padding:6px 10px;border-bottom:1px solid #eee;font-size:12px;"><span>${p.name} <span style="color:var(--muted);">${fmt(p.cost)} ₽ · ${p.life} лет</span></span><button onclick="addCapexFromPreset(${i})" class="btn btn-outline" style="padding:2px 10px;font-size:11px;">+ Добавить</button></div>`
).join('');
}

function renderEconomics() {
const isToc = (appMode === 'toc');
let fot=0, minStart=null, maxEnd=null;
currentTasks.forEach(t => {
    if (!t.isMilestone&&!t.isBuffer) fot += calcTaskFot(t);
    if (!t.isBuffer) {
        if (!minStart||new Date(t.start)<new Date(minStart)) minStart=t.start;
        if (!maxEnd||new Date(t.end)>new Date(maxEnd)) maxEnd=t.end;
    }
});

const bufTask=currentTasks.find(t=>t.isBuffer);
const timeBufferDays=(isToc&&bufTask?.start&&bufTask?.end) ? Math.max(0,Math.ceil((new Date(bufTask.end)-new Date(bufTask.start))/86400000)+1) : 0;
const projectEnd=timeBufferDays>0&&maxEnd?(()=>{const d=new Date(maxEnd);d.setDate(d.getDate()+timeBufferDays);return getLocalDateString(d);})():maxEnd;
const totalDays=(minStart&&projectEnd)?Math.ceil((new Date(projectEnd)-new Date(minStart))/86400000)+1:0;

const cUrg=parseFloat(document.getElementById('coef_urgency')?.value)||1;
const cCli=parseFloat(document.getElementById('coef_client')?.value)||1;
const cReg=parseFloat(document.getElementById('coef_region')?.value)||1;
const cExt=parseFloat(document.getElementById('coef_extra')?.value)||1;
const totalCoef=cUrg*cCli*cReg*cExt;

if(document.getElementById('coef_total_display')) document.getElementById('coef_total_display').textContent=`×${totalCoef.toFixed(2)}`;
if(document.getElementById('coef_breakdown')) document.getElementById('coef_breakdown').innerHTML=`${cUrg}×${cCli}×${cReg}×${cExt} = <b style="color:var(--text-main);">${totalCoef.toFixed(2)}</b>`;

const baseCost = isToc ? (fot * 1.5) : fot; 
const costWithCoef = baseCost * totalCoef;

const calcOH = pfx => { const v=parseFloat(document.getElementById(pfx+'_val')?.value)||0; return document.getElementById(pfx+'_type')?.value==='pct' ? fot*(v/100) : v; };
const totalOH=calcOH('oh_print')+calcOH('oh_trip')+calcOH('oh_siz')+calcOH('oh_amort')+calcOH('oh_soft')+calcOH('oh_tech');

let expCost=0;
if (document.getElementById('exp-checkbox')?.checked) {
    const v=parseFloat(document.getElementById('exp-value')?.value)||0;
    expCost=document.getElementById('exp-calc-type')?.value==='pct'?costWithCoef*(v/100):v;
}
const totalExtra=totalOH+expCost;

const mgmtPct=parseFloat(document.getElementById('calc-mgmt')?.value)||0;
const mgmtMoney=fot*(mgmtPct/100); 

// ⬇️ Использование EMV денег
const { moneyRub } = calcRiskReserves();
const riskFund = (appMode === 'toc') ? moneyRub : 0;

const profitPct=parseFloat(document.getElementById('calc-profit')?.value)||0;
const costBeforeMargin=costWithCoef+totalExtra+mgmtMoney+riskFund;
const marginMoney=costBeforeMargin*(profitPct/100);
const totalNoVat=costBeforeMargin+marginMoney;

const taxPct=parseFloat(document.getElementById('calc-tax')?.value)||0;
if(document.getElementById('tax-rate-display')) document.getElementById('tax-rate-display').textContent = taxPct>0?taxPct+'%':'0%';
const taxMoney=totalNoVat*(taxPct/100);
const finalTotal=totalNoVat+taxMoney;
const advancePct=parseFloat(document.getElementById('calc-advance')?.value)||0;
const advanceMoney=finalTotal*(advancePct/100);

setTxt('res_fot',fot);
setTxt('res_coeffs',costWithCoef);
setTxt('res_extra',totalExtra);
setTxt('res_extra_total', costBeforeMargin - fot);
setTxt('res_mgmt',mgmtMoney);
setTxt('res_risk_fund',riskFund);
setTxt('res_margin',marginMoney);
setTxt('res_total_no_vat',totalNoVat);
setTxt('res_vat',taxMoney);
setTxt('res_total',finalTotal);
setTxt('res_advance',advanceMoney);
setTxt('res_remainder',finalTotal-advanceMoney);
setTxt('res_time_buffer',timeBufferDays);

const elDur=document.getElementById('kp_duration_txt');
if (elDur) elDur.value = totalDays > 0 ? (isToc ? `${totalDays} кал.дн. (с буфером ${timeBufferDays} дн.)` : `${totalDays} кал.дн.`) : '0 дн.';

const elPay=document.getElementById('kp_payment_terms');
if (elPay && advancePct>0) elPay.value=`Аванс ${advancePct}% (${fmt(advanceMoney)} ₽) при подписании, остаток по факту`;

const sbcTotal = (parseFloat(document.getElementById('sbc_base')?.value)||0) * (parseFloat(document.getElementById('sbc_index')?.value)||1) * ((parseFloat(document.getElementById('sbc_stage_pct')?.value)||100)/100);
if(document.getElementById('sbc_total_calc')) document.getElementById('sbc_total_calc').textContent=fmt(sbcTotal)+' ₽';
const elAlert=document.getElementById('sbc_diff_alert');
if (elAlert&&sbcTotal>0) {
    const dp=((totalNoVat-sbcTotal)/sbcTotal)*100; const s=dp>=0?'+':'';
    elAlert.innerHTML=Math.abs(dp)<=10?`<div style="background:#e8f4ec;border:1px solid var(--c-toc);color:var(--c-toc);padding:8px;border-radius:3px;">✓ Норма (${s}${dp.toFixed(1)}%)</div>`:dp>10?`<div style="background:#fffbe6;border:1px solid var(--c-fun);color:var(--c-fun);padding:8px;border-radius:3px;">⚠ Выше норматива на ${dp.toFixed(1)}%</div>`:`<div style="background:#fff5f5;border:1px solid var(--c-ai);color:var(--c-ai);padding:8px;border-radius:3px;">↓ Ниже норматива на ${Math.abs(dp).toFixed(1)}%</div>`;
}

const elBuf=document.getElementById('toc-buffer-badge');
if (elBuf) { elBuf.textContent=timeBufferDays>0?`ТОС-буфер: ${timeBufferDays} раб.дн.`:'ТОС-буфер: нет'; elBuf.style.color=timeBufferDays>0?'var(--c-fun)':'var(--c-toc)'; }
}


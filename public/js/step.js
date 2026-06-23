// HQ/High Torque 規格上限固定為 55 N.m。
// Target Torque 仍使用工具實際最小/最大扭力範圍。
const HARD_MAX_TORQUE_NM = 55;

function normalizeTorqueUnitName(unit) {
    const raw = String(unit || '').trim();
    const compact = raw
        .replace(/\s+/g, '')
        .replace(/[·・]/g, '.')
        .replace(/牛頓米|牛顿米/gi, 'N.m')
        .replace(/公斤力公分|千克力厘米/gi, 'kgf.cm')
        .replace(/公斤力米|千克力米/gi, 'kgf.m')
        .replace(/磅力英吋|磅力英寸/gi, 'lbf.in')
        .replace(/厘牛頓米|厘牛顿米/gi, 'cN.m');

    const lower = compact.toLowerCase();

    if (lower.includes('kgf.cm') || lower.includes('kgf-cm') || lower.includes('kgfcm')) return 'kgf.cm';
    if (lower.includes('kgf.m')  || lower.includes('kgf-m')  || lower.includes('kgfm'))  return 'kgf.m';
    if (lower.includes('lbf.in') || lower.includes('lbf-in') || lower.includes('lbfin')) return 'lbf.in';
    if (lower.includes('cn.m')   || lower.includes('cn-m')   || lower.includes('cnm'))   return 'cN.m';
    if (lower.includes('n.m')    || lower.includes('n-m')    || lower.includes('nm'))    return 'N.m';

    // 依後端 device_torque_unit 常見 index 做 fallback；若專案 index 不同，可在這裡調整。
    const unitIndex = String(document.getElementById('step_torque_unit')?.value ?? '').trim();
    const indexMap = {
        // 與後端 Step.php / search_stepinfo 的 torque unit index 保持一致
        '0': 'kgf.m',
        '1': 'N.m',
        '2': 'kgf.cm',
        '3': 'lbf.in',
        '4': 'cN.m'
    };

    return indexMap[unitIndex] || 'N.m';
}

function convertNmToTorqueUnit(nm, unit) {
    const normalizedUnit = normalizeTorqueUnitName(unit);

    switch (normalizedUnit) {
        case 'N.m':
            return nm;
        case 'kgf.cm':
            return nm * 10.19716213;
        case 'kgf.m':
            return nm * 0.1019716213;
        case 'lbf.in':
            return nm * 8.85074579;
        case 'cN.m':
            return nm * 100;
        default:
            return nm;
    }
}

function readFiniteNumberFromValue(raw) {
    const s = String(raw ?? '').trim().replace(',', '.');
    if (s === '') return NaN;
    const n = Number(s);
    return Number.isFinite(n) ? n : NaN;
}

// 取得工具實際最大扭力；優先讀 tool_max_tor，再讀 tool_maxtorque_unified。
// 兩者都是後端已依目前單位轉好的值。
function getHardMaxTorque() {
    const fromToolMax = readFiniteNumberFromValue(document.getElementById('tool_max_tor')?.value);
    if (Number.isFinite(fromToolMax)) return fromToolMax;

    const fromUnified = readFiniteNumberFromValue(document.getElementById('tool_maxtorque_unified')?.value);
    if (Number.isFinite(fromUnified)) return fromUnified;

    const fromGlobal = readFiniteNumberFromValue(window.__TOOL_MAX_TOR__);
    if (Number.isFinite(fromGlobal)) return fromGlobal;

    return NaN;
}

function getHardMaxTorqueText() {
    const { precision } = getTorqueRule();
    const max = getHardMaxTorque();
    return Number.isFinite(max) ? max.toFixed(precision) : '';
}

// HQ/High Torque 允許到 55 N.m，依目前扭力單位換算。
function getHqMaxTorque() {
    const fromHidden = readFiniteNumberFromValue(document.getElementById('hq_torque_limit')?.value);
    if (Number.isFinite(fromHidden)) return fromHidden;

    return convertNmToTorqueUnit(HARD_MAX_TORQUE_NM, getTorqueUnit());
}

function getHqMaxTorqueText() {
    const { precision } = getTorqueRule();
    const max = getHqMaxTorque();
    return Number.isFinite(max) ? max.toFixed(precision) : '';
}

// 只同步全域變數，不再覆寫 hidden input，避免把真正工具上限洗成固定 55。
function syncHardMaxTorqueToHiddenInput() {
    const hardMaxText = getHardMaxTorqueText();
    if (hardMaxText !== '') window.__TOOL_MAX_TOR__ = hardMaxText;
    return hardMaxText;
}

function setToolSpecToUIAndGlobal(tool_maxtorque, tool_mintorque) {
    const maxV = (tool_maxtorque == null) ? '' : String(tool_maxtorque).trim();
    const minV = (tool_mintorque == null) ? '' : String(tool_mintorque).trim();

    // 全域保底（你切換目標模式會用到）
    window.__TOOL_MIN_TOR__ = minV;
    window.__TOOL_MAX_TOR__ = maxV;

    // DOM（有就寫，沒有就跳過）
    const elMax = document.getElementById("tool_max_tor");
    const elMin = document.getElementById("tool_min_tor");
    if (elMax) elMax.value = maxV;
    if (elMin) elMin.value = minV;
}


/* =====================================================
 * Torque unit → precision / EPS
 * ===================================================== */
const TORQUE_UNIT_RULES = {
    'N.m':    { precision: 3, eps: 0.0005 },
    'kgf.cm': { precision: 2, eps: 0.005  },
    'kgf.m':  { precision: 4, eps: 0.00005 },
    'lbf.in': { precision: 2, eps: 0.005  },
    'cN.m':   { precision: 1, eps: 0.05   }
};

// 取得目前扭力單位（依你系統實際顯示來源）
function getTorqueUnit() {
    const rawUnit = document.getElementById('torque_unit_text')?.value
        || document.getElementById('tor_unit_label')?.innerText
        || document.querySelector('[id$="unit_name"]')?.value
        || document.querySelector('#target_tor_item .t1, #edit_target_tor_item .t1, #tor_hi_item .t1, #edit_tor_hi_item .t1')?.innerText
        || 'N.m';

    return normalizeTorqueUnitName(rawUnit);
}

function getTorqueRule() {
    const unit = getTorqueUnit();
    return TORQUE_UNIT_RULES[unit] || { precision: 3, eps: 0.0005 };
}

/* =====================================================
 * i18n（可擴充）
 * ===================================================== */
const I18N = {
    'format_hint': {
        'zh-tw': '已依扭力單位自動限制小數位',
        'zh-cn': '已根据扭力单位自动限制小数位',
        'en-us': 'Decimal places are limited by torque unit'
    }
};

function getLang() {
    return (window.getLangAndUnit?.().lang
        || window.getCookieSafe?.('language')
        || 'zh-tw').toLowerCase();
}

function t(key) {
    const lang = getLang();
    return I18N[key]?.[lang] || I18N[key]?.['en-us'] || key;
}


/* =====================================================
 * Format helpers
 * ===================================================== */
function formatByPrecision(value, precision) {
    if (value === '' || value === null) return '';

    let v = String(value).replace(/[^\d.]/g, '');

    // 只允許一個小數點
    const parts = v.split('.');
    if (parts.length > 2) {
        v = parts[0] + '.' + parts.slice(1).join('');
    }

    // ".5" → "0.5"
    if (v.startsWith('.')) {
        v = '0' + v;
    }

    // 裁切小數位（不 round，避免誤差）
    if (v.includes('.')) {
        const [i, d] = v.split('.');
        v = i + '.' + d.slice(0, precision);
    }

    return v;
}

/* =====================================================
 * Bind auto-format to torque input
 * ===================================================== */
function bindTorqueAutoFormat(input) {
    if (!input) return;

    input.addEventListener('input', function () {
        const { precision } = getTorqueRule();
        const oldVal = this.value;
        const newVal = formatByPrecision(oldVal, precision);

        if (oldVal !== newVal) {
            this.value = newVal;
        }
    });

    // blur 時補齊格式（1. → 1.000）
    input.addEventListener('blur', function () {
        const { precision } = getTorqueRule();
        if (this.value === '') return;

        let num = Number(this.value);
        if (!Number.isFinite(num)) return;

        this.value = num.toFixed(precision);
    });
}

/* =====================================================
 * Init torque auto format
 * ===================================================== */
function initTorqueAutoFormat(prefix = '') {

    const ids = [
        prefix + 'target_tor',
        prefix + 'tor_hi',
        prefix + 'tor_lo',
        prefix + 'th_tor',
        prefix + 'ds_tor'
    ];

    ids.forEach(id => {
        const el = document.getElementById(id);
        if (el) bindTorqueAutoFormat(el);
    });
}

/* =====================================================
 * Reformat when unit changes
 * ===================================================== */
function reformatAllTorqueInputs(prefix = '') {
    const { precision } = getTorqueRule();

    const ids = [
        prefix + 'target_tor',
        prefix + 'tor_hi',
        prefix + 'tor_lo',
        prefix + 'th_tor',
        prefix + 'ds_tor'
    ];

    ids.forEach(id => {
        const el = document.getElementById(id);
        if (el && el.value !== '') {
            el.value = formatByPrecision(el.value, precision);
        }
    });
}





/* =====================================================
 * New Step Downshift ON values
 * Rule:
 *   Threshold Torque = 0 formatted by current torque unit
 *   Downshift Torque = 0 formatted by current torque unit
 *   Downshift Speed  = 100
 * Only for New Step. Edit Step must preserve DB values.
 * ===================================================== */
function getTorqueZeroText() {
    const { precision } = getTorqueRule();
    return (0).toFixed(precision);
}

function setNewDownshiftDefaultValues(force = false) {
    const thTor = document.getElementById('th_tor');
    const dsTor = document.getElementById('ds_tor');
    const dsSpeed = document.getElementById('ds_speed');
    const zeroText = getTorqueZeroText();

    // New Step：切成 Downshift ON 時，兩個扭力欄位固定帶 0，
    // 並依目前扭力單位顯示對應小數位：N.m 3、kgf.cm 2、kgf.m 4、lbf.in 2、cN.m 1。
    if (thTor && (force || String(thTor.value ?? '').trim() === '')) {
        thTor.value = zeroText;
    }
    if (dsTor && (force || String(dsTor.value ?? '').trim() === '')) {
        dsTor.value = zeroText;
    }
    if (dsSpeed && (force || String(dsSpeed.value ?? '').trim() === '')) {
        dsSpeed.value = '100';
    }
}

function clearNewDownshiftTorqueValues() {
    // New Step + Downshift OFF：欄位 disabled，但仍顯示 0，並依目前扭力單位補對應小數位。
    // N.m 3、kgf.cm 2、kgf.m 4、lbf.in 2、cN.m 1。
    const thTor = document.getElementById('th_tor');
    const dsTor = document.getElementById('ds_tor');
    const dsSpeed = document.getElementById('ds_speed');
    const zeroText = getTorqueZeroText();

    if (thTor) thTor.value = zeroText;
    if (dsTor) dsTor.value = zeroText;
    if (dsSpeed && String(dsSpeed.value ?? '').trim() === '') dsSpeed.value = '100';
}

function create_step() {
    document.getElementById('newstep').style.display = 'block';

 

    // 目標扭力預設值：使用起子最大扭力（Step.php 已換算成目前控制器單位後放在 target_tor_value）。
    // 若 hidden 值不存在，fallback 讀 tool_max_tor。
    const target_tor_value_raw = document.getElementById('target_tor_value')?.value
        || document.getElementById('tool_max_tor')?.value
        || "0";
    const target_tor_value = target_tor_value_raw;

    // 扭力上限仍依規格固定使用 HQ 55 N.m 換算值。
    const tor_hi_unified_raw = getHqMaxTorqueText() || syncHardMaxTorqueToHiddenInput() || "0";
    const tor_hi_unified = tor_hi_unified_raw;

        
    // 預設值
    document.getElementById('rpm').value = 200;
    clearNewDownshiftTorqueValues();
    document.getElementById('ds_speed').value = 100;
    document.getElementById("direction_CW").checked = true;
    document.getElementById('ang_hi').value = 30600;
    document.getElementById('ang_lo').value = 0;
    document.getElementById('tor_hi').value =  tor_hi_unified;
    document.getElementById('tor_lo').value = 0;
    document.getElementById('target_tor').value = target_tor_value;
    // New Step 規則：Downshift ON / OFF 的 Threshold Torque 與 Downshift Torque 都顯示 0，
    // 並依扭力單位補小數位；OFF 狀態只 disabled 欄位。
    document.getElementById('target_tor').addEventListener('input', function () {
        if (document.getElementById("downshift_ON")?.checked) {
            setNewDownshiftDefaultValues(true);
        } else {
            // OFF 時仍顯示 0，只是欄位 disabled。
            clearNewDownshiftTorqueValues();
        }
    });
    document.getElementById('target_ang').value = 1800;
    document.getElementById("pnf_set_OFF").checked = true;

    // 預設 downshift_OFF 被選中
    document.getElementById("downshift_OFF").checked = true;
    toggleDisabledFields(); // 控制 th_tor, ds_tor, ds_speed 狀態

    // 監聽 downshift 切換
    document.getElementById("downshift_ON").addEventListener('change', toggleDisabledFields);
    document.getElementById("downshift_OFF").addEventListener('change', toggleDisabledFields);

    // 處理 target_opt 預設選項
    var targetoptionselect = document.getElementById('target_opt');
    var firstOptionValue = targetoptionselect.options[0].value;

    if (firstOptionValue == 1) {
        document.getElementById("downshift_OFF").checked = true;
        document.getElementById("downshift_OFF").disabled = true;
        document.getElementById("downshift_ON").disabled = true;
        document.getElementById('th_tor').disabled = true;
        document.getElementById('ds_tor').disabled = true;
        document.getElementById('ds_speed').disabled = true;

        document.getElementById('target_tor_item').style.display = 'none';
        document.getElementById('target_ang_item').style.display = 'block';
        document.getElementById('target_delay_item').style.display = 'none';
    }

    if (firstOptionValue == 2) {
        document.getElementById("downshift_OFF").checked = true;
        document.getElementById("downshift_OFF").disabled = true;
        document.getElementById("downshift_ON").disabled = true;
        document.getElementById('th_tor').disabled = true;
        document.getElementById('ds_tor').disabled = true;
        document.getElementById('ds_speed').disabled = true;
        document.getElementById('rpm').disabled = true;
        document.getElementById('tor_hi').disabled = true;
        document.getElementById('tor_lo').disabled = true;
        document.getElementById('ang_hi').disabled = true;
        document.getElementById('ang_lo').disabled = true;

        document.getElementById('target_tor_item').style.display = 'none';
        document.getElementById('target_ang_item').style.display = 'none';
        document.getElementById('target_delay_item').style.display = 'block';
        document.getElementById('target_delay').value = 1.0;
    }

    targetoptionselect.addEventListener('change', function () {
        var target_opt_Value = targetoptionselect.value;
        localStorage.setItem('target_option', target_opt_Value);
        toggleVisibility(target_opt_Value);
    });

    // ✅ 加入檢查：如果目前是第 4 個 step，禁用 downshift radio
    $.post("?url=Step/check_step_limit", {
        jobid: jobid,
        seqid: seqid
    }, function (res) {
        let result;
        try {
            result = JSON.parse(res);
        } catch (e) {
            alertify.alert('錯誤', '回傳格式錯誤');
            return;
        }

        if (result.count === 3) {
            const downshiftOff = document.getElementById('downshift_OFF');
            const downshiftOn = document.getElementById('downshift_ON');
            if (downshiftOff) downshiftOff.disabled = true;
            if (downshiftOn) downshiftOn.disabled = true;
        }
    });
}


// 用來根據 downshift 的選項來控制其他欄位的 disabled 狀態
function toggleDisabledFields() {
    if (document.getElementById("downshift_ON").checked) {
        // 當 downshift_ON 被選中時，解除 disabled，並補上新建 Step 的預設值
        document.getElementById('th_tor').disabled = false;
        document.getElementById('ds_tor').disabled = false;
        document.getElementById('ds_speed').disabled = false;
        setNewDownshiftDefaultValues(true);
    } else {
        // 當 downshift_OFF 被選中時，欄位 disabled，但仍顯示 0 與對應小數位。
        clearNewDownshiftTorqueValues();
        document.getElementById('th_tor').disabled = true;
        document.getElementById('ds_tor').disabled = true;
        document.getElementById('ds_speed').disabled = true;
    }
}


/* TARGET ANGLE KEEP VALUE FIX: angle edit keeps DB/user value instead of forcing 2000 */
function edit_step(stepid) {
    if (!jobid) return;

    $.ajax({
        url: "?url=Step/search_stepinfo",
        method: "POST",
        data: {
            job_id: jobid,
            seq_id: seqid,
            step_id: stepid
        },
        success: function (response) {

            const responseJSON = JSON.stringify(response);
            let cleanString = responseJSON.replace(/Array|\\n/g, '');
            cleanString = cleanString.substring(2, cleanString.length - 2);

            // 解析 step 資料
            const [, target_opt]   = cleanString.match(/\[target_opt\]\s*=>\s*([^ ]+)/) || [, '0'];
            const [, target_tor]   = cleanString.match(/\[target_tor\]\s*=>\s*([^ ]+)/) || [, ''];
            const [, target_ang]   = cleanString.match(/\[target_ang\]\s*=>\s*([^ ]+)/) || [, ''];
            const [, target_delay] = cleanString.match(/\[target_delay\]\s*=>\s*([^ ]+)/) || [, ''];

            const [, tor_hi] = cleanString.match(/\[tor_hi\]\s*=>\s*([^ ]+)/) || [, ''];
            const [, tor_lo] = cleanString.match(/\[tor_lo\]\s*=>\s*([^ ]+)/) || [, ''];
            const [, ang_hi] = cleanString.match(/\[ang_hi\]\s*=>\s*([^ ]+)/) || [, ''];
            const [, ang_lo] = cleanString.match(/\[ang_lo\]\s*=>\s*([^ ]+)/) || [, ''];

            const [, rpm]       = cleanString.match(/\[rpm\]\s*=>\s*([^ ]+)/) || [, ''];
            const [, direction] = cleanString.match(/\[direction\]\s*=>\s*([^ ]+)/) || [, ''];
            const [, th_mode]   = cleanString.match(/\[th_mode\]\s*=>\s*([^ ]+)/) || [, '0'];
            const [, pnf_set]   = cleanString.match(/\[pnf_set\]\s*=>\s*([^ ]+)/) || [, '0'];

            const [, ds_tor]   = cleanString.match(/\[ds_tor\]\s*=>\s*([^ ]+)/) || [, ''];
            const [, ds_speed] = cleanString.match(/\[ds_speed\]\s*=>\s*([^ ]+)/) || [, ''];
            const [, th_tor]   = cleanString.match(/\[th_tor\]\s*=>\s*([^ ]+)/) || [, ''];

            const [, step_id]  = cleanString.match(/\[step_id\]\s*=>\s*([^ ]+)/) || [, ''];

            // ⚠️ 工具值（後端 raw）— 只取出來，不要用它覆蓋 UI 的 unified
            const [, toolMaxRawFromDb] = cleanString.match(/\[tool_maxtorque\]\s*=>\s*([^ ]+)/) || [, null];
            const [, toolMinRawFromDb] = cleanString.match(/\[tool_mintorque\]\s*=>\s*([^ ]+)/) || [, null];

            // ✅ 用「目前頁面已轉換好的工具上下限」當唯一可信來源（避免 55 -> 1）
            // 這裡會讓 handleTargetOptChange / input_check_core 讀到的範圍保持一致
            const pageToolMax = syncHardMaxTorqueToHiddenInput();
            const pageToolMin = (document.getElementById("tool_min_tor")?.value ?? '').toString().trim();

            window.__TOOL_MAX_TOR__ = pageToolMax;
            window.__TOOL_MIN_TOR__ = pageToolMin;

            // ❌ 不要做：unified.value = toolMaxRawFromDb;
            // ❌ 不要做：setToolSpecToUIAndGlobal(toolMaxRawFromDb, toolMinRawFromDb);

            // 開啟 modal
            document.getElementById('editstep').style.display = 'block';

            // 設定 target_opt（先設值，後面我們手動呼叫 handleTargetOptChange）
            const sel = document.querySelector("select[name='edit_target_opt']");
            if (sel) sel.value = String(target_opt);

            // 先把欄位值填回來（避免被切換 handler 先吃到空值）
            document.getElementById("edit_rpm").value      = rpm;
            document.getElementById("edit_ds_speed").value = ds_speed;
            document.getElementById("edit_ds_tor").value   = ds_tor;
            document.getElementById("edit_th_tor").value   = th_tor;

            document.getElementById("edit_tor_hi").value = tor_hi;  // ✅ 保留 DB 實際儲存的扭力上限
            document.getElementById("edit_tor_lo").value = tor_lo;

            document.getElementById("edit_ang_hi").value = ang_hi;
            document.getElementById("edit_ang_lo").value = ang_lo;

            document.getElementById('edit_step_id').value = step_id;

            // target values（兩個 id 都兼容：edit_target_ang / edit_target_angle）
            const elTargetTor   = document.getElementById("edit_target_tor");
            const elTargetAngA  = document.getElementById("edit_target_ang");
            const elTargetAngB  = document.getElementById("edit_target_angle");
            const elTargetDelay = document.getElementById("edit_target_delay");

            if (elTargetTor)   elTargetTor.value   = target_tor;
            if (elTargetAngA)  elTargetAngA.value  = target_ang;
            if (elTargetAngB)  elTargetAngB.value  = target_ang;
            if (elTargetDelay) elTargetDelay.value = target_delay;

            // radio
            setRadioButton_value(document.getElementsByName("edit_th_mode"), th_mode);
            setRadioButton_value(document.getElementsByName("edit_direction"), direction);
            setRadioButton_value(document.getElementsByName("edit_pnf_set"), pnf_set);

            // ✅ 最後：根據 target_opt 切換顯示/disabled（用你既有的 handler）
            if (typeof handleTargetOptChange === 'function') {
                handleTargetOptChange(String(target_opt));
            }

            // Downshift OFF 只能鎖住欄位，不能把原本的門檻扭力 / 降速點扭力清成 0.0。
            // 因為 handleTargetOptChange 會切換 enable/disable，這裡再把 DB 回來的值補回去一次。
            document.getElementById("edit_ds_speed").value = ds_speed;
            document.getElementById("edit_ds_tor").value   = ds_tor;
            document.getElementById("edit_th_tor").value   = th_tor;

            // ✅ 你要的：切換到「角度」時，若空值就補 2000
            if (String(target_opt) === '1') {
                const angEl = document.getElementById("edit_target_angle") || document.getElementById("edit_target_ang");
                if (angEl && String(angEl.value || '').trim() === '') {
                    angEl.value = '1800';
                }
            }

            // 你原本的 downshift 限制 / th_tor disable
            bindEditDownshiftModeEvents();
            toggleThTorDisabled();

            // 第 4 個 step 禁用 downshift（保留你原本邏輯）
            $.post("?url=Step/check_step_limit", { jobid: jobid, seqid: seqid }, function (res) {
                let result;
                try { result = JSON.parse(res); } catch (e) { return; }
                if (result.count === 4) {
                    const off = document.getElementById('edit_downshift_OFF');
                    const on  = document.getElementById('edit_downshift_ON');
                    if (off) off.disabled = true;
                    if (on)  on.disabled  = true;
                }
            });
        },
        error: function () {
            alertify.alert('錯誤', '取得步驟資料失敗');
        }
    });
}


  
function countrows() {
    var tbody = document.querySelector('#step_table tbody');
    var rows = tbody.querySelectorAll('tr');
    var rowCount = rows.length;

    return rowCount;
}

function cound_step(argument){

    var table = document.getElementById('step_table');
    var selectedRow = table.querySelector('.selected');
    var selectedRowData = selectedRow ? selectedRow.cells[0].innerText : null;
    stepid = selectedRowData;
    if(argument == 'del'){
        document.querySelector(".main-content").classList.add("overlay-active");
        del_stepid(stepid);
    }

    if(argument =="copy" && stepid != null){
        document.querySelector(".main-content").classList.add("overlay-active");
        copy_step(stepid);
    }

    if(argument =="new"){
        document.querySelector(".main-content").classList.add("overlay-active");
        var step_count = countrows();
        if(step_count  < 4){
            create_step();
            prepareAddStepId(); 
        }
    }

    if(argument =="edit" && stepid != null){
        document.querySelector(".main-content").classList.add("overlay-active");
        edit_step(stepid);
    }

}

function copy_step(stepid){
    document.getElementById('copystep').style.display = 'block';   
    copy_step_by_id(stepid);

}

function isRadioLikeElement(element) {
    return element && (element.type === 'radio' || element.type === 'checkbox');
}

function setValueIfAllowed(element, value) {
    // Radio / checkbox 的 value 是固定語意值，不能因 enable/disable 被清掉，否則送出會變空值。
    if (!element || value === undefined || isRadioLikeElement(element)) return;
    element.value = value;
}

function disableElements(elements, value) {
    Array.from(elements || []).forEach(function(element) {
        element.disabled = true;
        setValueIfAllowed(element, value === true ? 0 : value);
    });
}

function disableElementById(id, value = undefined) {
    var element = document.getElementById(id);
    if (element) {
        element.disabled = true;
        setValueIfAllowed(element, value);
    } else {
        console.log("元素未找到: " + id);
    }
}

function disableElementsByName(elementName) {
    const elements = document.getElementsByName(elementName);
    Array.from(elements).forEach(function(element) {
        element.disabled = true;
    });
}

function enableElementById(id, value = undefined) {
    var element = document.getElementById(id);
    if (element) {
        element.disabled = false;
        setValueIfAllowed(element, value);
    } else {
        console.log("元素未找到: " + id);
    }
}
function enableElementByName(name, value = undefined) {
    var elements = document.getElementsByName(name);
    if (elements.length > 0) {
        Array.from(elements).forEach(function(element) {
            element.disabled = false;
            setValueIfAllowed(element, value);
        });
    } else {
        console.log("未找到具有 name '" + name + "' 的元素");
    }
}

function setRadioChecked(id, checked) {
    const el = document.getElementById(id);
    if (el) el.checked = !!checked;
}

function forceEditDownshiftOff() {
    setRadioChecked('edit_downshift_OFF', true);
    setRadioChecked('edit_downshift_ON', false);
}

function forceNewDownshiftOff() {
    setRadioChecked('downshift_OFF', true);
    setRadioChecked('downshift_ON', false);
}

function ensurePositiveDefaultValue(el, fallbackValue) {
    if (!el) return;
    const raw = String(el.value ?? '').trim();
    const n = Number(raw);
    if (raw === '' || !Number.isFinite(n) || n <= 0) {
        el.value = fallbackValue;
    }
}


function detectDownshiftSelection() {
    const radios = document.getElementsByName("th_mode");
    radios.forEach((radio) => {
        radio.addEventListener("change", function() {
            if (document.getElementById("downshift_ON").checked) {
                updateTargetOption();
            }else{
                restoreBackupOptions();
            }
        });
    });
}


//依照targer_opt的 val 控制 欄位的是否需要 disabled
function toggleVisibility(targetValue) {
    const targetTorItem = document.getElementById('target_tor_item');
    const targetAngItem = document.getElementById('target_ang_item');
    const targetDelayItem = document.getElementById('target_delay_item');
    // 扭力上限仍固定使用 HQ 55 N.m 依目前單位換算後的值。
    const tor_hi_unified = getHqMaxTorqueText() || syncHardMaxTorqueToHiddenInput();
    
    targetTorItem.style.display = 'none';
    targetAngItem.style.display = 'none';
    targetDelayItem.style.display = 'none';

        
    document.getElementById('ang_hi').value = 30600;
    document.getElementById('ang_lo').value = 0;
    document.getElementById('tor_hi').value = tor_hi_unified;
    document.getElementById('tor_lo').value = 0;


    if (targetValue == 0) {
        targetTorItem.style.display = "block";
        enableElementById('tor_hi','0');
        enableElementById('tor_lo','0');
        enableElementById('ang_hi','0');
        enableElementById('ang_lo','0');
        enableElementById('rpm','200');
        enableElementById('th_tor','');
        enableElementById('ds_tor','');
        enableElementById('ds_speed','100');
        enableElementById('direction_CW','');
        enableElementById('direction_CCW','');
        enableElementById('downshift_ON','');
        enableElementById('downshift_OFF','');

        document.getElementById("downshift_OFF").checked = true;
        document.getElementById("downshift_ON").checked = false;
        clearNewDownshiftTorqueValues();

    } else if (targetValue == 1) {
        targetAngItem.style.display = "block";

        enableElementById('tor_hi', ''); 
        enableElementById('tor_lo', ''); 
        enableElementById('ang_hi', ''); 
        enableElementById('ang_lo', ''); 
        enableElementById('direction_CW','');
        enableElementById('direction_CCW','');
        disableElementById('th_tor','');
        disableElementById('ds_tor','');
        disableElementById('ds_speed','100');
        disableElementById('downshift_ON','');
        disableElementById('downshift_OFF','');
        forceNewDownshiftOff();
        document.getElementById('tor_hi').value = getHqMaxTorqueText() || getHardMaxTorque();
        document.getElementById('tor_lo').value = 0;
        document.getElementById('ang_hi').value = 30600;
        document.getElementById('ang_lo').value = 0;


         
    } else if (targetValue == 2) {
        targetDelayItem.style.display = "block";
        disableElementById('tor_hi','0');
        disableElementById('tor_lo','0');
        disableElementById('ang_hi','0');
        disableElementById('ang_lo','0');
        disableElementById('rpm','200');
        disableElementById('th_tor','');
        disableElementById('ds_tor','');
        disableElementById('ds_speed','100');
        disableElementById('direction_CW','');
        disableElementById('direction_CCW','');
        disableElementById('downshift_ON','');
        disableElementById('downshift_OFF','');
        document.getElementById('tor_hi').value = getHqMaxTorqueText() || getHardMaxTorque();
        document.getElementById('tor_lo').value = 0;
        document.getElementById('ang_hi').value = 30600;
        document.getElementById('ang_lo').value = 0;
        document.getElementById('target_delay').value = (1.0).toFixed(1);


        forceNewDownshiftOff();

    }
}

function targetOptChangeHandler() {
    const sel = document.querySelector("select[name='edit_target_opt']");
    if (!sel) return;

    bindEditDownshiftModeEvents();
    handleTargetOptChange(sel.value);

    // Edit Step 從 Angle / Delay 切回 Torque 時，handleTargetOptChange 會先把欄位 enable。
    // 這裡再依目前 Downshift OFF / ON 狀態同步一次 disabled，避免 OFF 時欄位仍可輸入。
    toggleThTorDisabled();
}

function handleTargetOptChange(target_opt) {

    const rpm      = document.getElementById("edit_rpm")?.value ?? '';
    const ds_tor   = document.getElementById("edit_ds_tor")?.value ?? '';
    const ds_speed = document.getElementById("edit_ds_speed")?.value ?? '';
    const th_tor   = document.getElementById("edit_th_tor")?.value ?? '';

    // ✅ 依扭力單位保留小數位（例如 N.m = 3 → 55.000）
    const { precision } = getTorqueRule();
    const keepTorqueFormat = (v) => {
        const raw = String(v ?? '').trim();
        if (raw === '') return '';
        const n = Number(raw);
        if (!Number.isFinite(n)) return raw;
        return n.toFixed(precision);
    };

    // ✅ 不要 cleanNumber()，否則 55.000 會變 55
    const tor_hi = keepTorqueFormat(document.getElementById("edit_tor_hi")?.value ?? '');
    const tor_lo = keepTorqueFormat(document.getElementById("edit_tor_lo")?.value ?? '');

    const ang_hi = document.getElementById("edit_ang_hi")?.value ?? '';
    const ang_lo = document.getElementById("edit_ang_lo")?.value ?? '';

    const targetTorEl   = document.getElementById("edit_target_tor");
    const targetAngEl   = document.getElementById("edit_target_ang") || document.getElementById("edit_target_angle"); // ✅ 兩種 id 都支援
    const targetDelayEl = document.getElementById("edit_target_delay");

    // ⭐⭐⭐ 關鍵：全域變數才是唯一可信來源
    let toolMinTor = (window.__TOOL_MIN_TOR__ ?? '').toString().trim();
    if (!toolMinTor) {
        toolMinTor = (document.getElementById("tool_min_tor")?.value ?? '').trim();
    }

    const pickPositiveTorqueDefault = (...values) => {
        for (const v of values) {
            const raw = String(v ?? '').trim();
            const n = Number(raw);
            if (raw !== '' && Number.isFinite(n) && n > 0) {
                return keepTorqueFormat(raw);
            }
        }
        return keepTorqueFormat('0');
    };

    // Angle / Delay step 會把 target_tor 存成 0；
    // Edit 時若從 Angle 切回 Torque，預設值應與 New Step 一致，
    // 優先使用 target_tor_value（目前控制器單位的建議目標扭力），
    // 不要優先取 tool_min_tor，否則 kgf.m 會顯示 0.0102 這類工具下限值。
    const fallbackTor = pickPositiveTorqueDefault(
        document.getElementById('target_tor_value')?.value,
        document.getElementById('ds_tor')?.value,
        document.getElementById('edit_ds_tor')?.value,
        document.getElementById('tool_max_tor')?.value,
        window.__TOOL_MAX_TOR__,
        toolMinTor,
        tor_lo
    );
    const fallbackAng = '1800';

    /* =====================================================
     * 目標扭力
     * ===================================================== */
    if (String(target_opt) === '0') {

        document.getElementById("edit_target_tor_item").style.display   = 'block';
        document.getElementById("edit_target_ang_item").style.display   = 'none';
        document.getElementById("edit_target_delay_item").style.display = 'none';

        ensurePositiveDefaultValue(targetTorEl, fallbackTor);
        setTimeout(() => {
            const el = document.getElementById("edit_target_tor");
            ensurePositiveDefaultValue(el, fallbackTor);
        }, 0);

        enableElementById('edit_tor_hi', tor_hi);
        enableElementById('edit_tor_lo', tor_lo);
        enableElementById('edit_ang_hi', ang_hi);
        enableElementById('edit_ang_lo', ang_lo);
        enableElementById('edit_rpm', rpm);
        enableElementById('edit_ds_tor', ds_tor);
        enableElementById('edit_ds_speed', ds_speed);
        enableElementById('edit_th_tor', th_tor);

        enableElementByName("edit_direction");
        enableElementByName("edit_th_mode");

        // Edit 模式：不要強制把 Downshift 改成 ON。
        // 原本資料若是 OFF，就只要 disabled 欄位，th_tor / ds_tor / ds_speed 的值必須保留。
        const editDownshiftOn  = document.getElementById("edit_downshift_ON");
        const editDownshiftOff = document.getElementById("edit_downshift_OFF");
        if (editDownshiftOn && editDownshiftOff && !editDownshiftOn.checked && !editDownshiftOff.checked) {
            editDownshiftOff.checked = true;
        }
    }

    /* =====================================================
     * 目標角度
     * ===================================================== */
    if (String(target_opt) === '1') {

        document.getElementById("edit_target_ang_item").style.display   = 'block';
        document.getElementById("edit_target_tor_item").style.display   = 'none';
        document.getElementById("edit_target_delay_item").style.display = 'none';

        // ✅ 切到角度：不要覆蓋已存在/DB 回來的 target_ang
        // 原本這裡每次都強制寫入 1800，導致已改成 2000 後再次開啟 Edit 仍顯示 1800。
        ensurePositiveDefaultValue(targetAngEl, fallbackAng);
        setTimeout(() => {
            const el = document.getElementById("edit_target_ang") || document.getElementById("edit_target_angle");
            ensurePositiveDefaultValue(el, fallbackAng);
        }, 0);

        // ✅ 重點：不要覆寫 edit_tor_hi/lo 的值，只 disable 即可
        enableElementById('edit_tor_hi', tor_hi);
        enableElementById('edit_tor_lo', tor_lo);
        enableElementById('edit_ang_hi', ang_hi);
        enableElementById('edit_ang_lo', ang_lo);

        disableElementById('edit_ds_tor', ds_tor);
        disableElementById('edit_ds_speed', ds_speed);
        disableElementById('edit_th_tor', th_tor);
        forceEditDownshiftOff();
        disableElementsByName("edit_th_mode");

        enableElementByName("edit_direction");
        enableElementById('edit_rpm', rpm);
    }

    /* =====================================================
     * 目標延遲
     * ===================================================== */
    if (String(target_opt) === '2') {

        document.getElementById("edit_target_delay_item").style.display = 'block';
        document.getElementById("edit_target_tor_item").style.display   = 'none';
        document.getElementById("edit_target_ang_item").style.display   = 'none';

        const fallbackDelay = '1.0';
        if (targetDelayEl && !String(targetDelayEl.value ?? '').trim()) {
            targetDelayEl.value = fallbackDelay;
        }
        setTimeout(() => {
            const el = document.getElementById("edit_target_delay");
            if (el && !String(el.value ?? '').trim()) el.value = fallbackDelay;
        }, 0);

        // ✅ 延遲模式：一樣不要洗掉 tor_hi/lo 的值
        disableElementById('edit_th_tor', th_tor);
        disableElementById('edit_tor_hi', tor_hi);
        disableElementById('edit_tor_lo', tor_lo);
        disableElementById('edit_ang_hi', ang_hi);
        disableElementById('edit_ang_lo', ang_lo);

        disableElementById('edit_rpm', rpm);
        disableElementById('edit_ds_tor', ds_tor);
        disableElementById('edit_ds_speed', ds_speed);
        forceEditDownshiftOff();
        disableElementsByName("edit_th_mode");
        disableElementsByName("edit_direction");
    }
}


function setRadioButton_value(radioButtons, value) {
    radioButtons.forEach(function(button) {
        if (button.value === value.toString()) {
            button.checked = true;
        } else {
            button.checked = false;
        }
    });
}


function del_stepid(step_id) {
    if (!step_id) {
        isProcessing = false;
        return;
    }

    var language = getCookie('language');
    var text_info, title;

    if (language === "zh-cn") {
        text_info = '你确定吗？';
        title = '删除步骤';
    } else if (language === "zh-tw") {
        text_info = '你確定嗎 ?';
        title = '刪除步驟';
    } else {
        text_info = 'Are you sure ?';
        title = 'Delete Step';
    }

    alertify.confirm(title, text_info, function (confirmed) {
        if (confirmed) {
            document.getElementById('spinner').style.display = 'block';

            $.ajax({
                url: "?url=Step/delete_step",
                method: "POST",
                data: {
                    stepid: step_id,
                    jobid: jobid,
                    seqid: seqid
                },
                success: function (response) {
                    var responseData = JSON.parse(response);

                    setTimeout(function () {
                        document.getElementById('spinner').style.display = 'none';

                        alertify.alert(responseData.res_type, responseData.res_msg, function () {
                            history.go(0);
                        });

                        setTimeout(function () {
                            alertify.closeAll();
                            history.go(0);
                        }, 3000);
                    }, 1000);
                },
                error: function (xhr, status, error) {
                    document.getElementById('spinner').style.display = 'none';
                    alertify.alert("Error", "Delete failed.");
                }
            });
        }
    }, function () {
        // 取消 callback 可選寫在這裡（目前略過）
        document.querySelector(".main-content").classList.remove("overlay-active");

    });
}




function bindEditDownshiftModeEvents() {
    const radios = document.getElementsByName('edit_th_mode');
    Array.from(radios || []).forEach(function (radio) {
        if (radio.dataset.editDownshiftBound === '1') return;
        radio.dataset.editDownshiftBound = '1';
        radio.addEventListener('change', toggleThTorDisabled);
    });
}

function toggleThTorDisabled() {

    const radios = document.getElementsByName('edit_th_mode');
    const edit_th_tor = document.getElementById('edit_th_tor');
    const edit_ds_tor = document.getElementById('edit_ds_tor');
    const edit_ds_speed = document.getElementById('edit_ds_speed');

    if (!edit_th_tor || !edit_ds_tor || !edit_ds_speed) return;

    // 只有 Target Type = Torque 時，Downshift 欄位才可能開放。
    // Angle / Delay 一律 disabled。
    const editTargetOpt = String(document.getElementById('edit_target_opt')?.value ?? '');
    const isTorqueTarget = editTargetOpt === '0';

    // Downshift OFF：只 disabled，不改 value。
    // 這樣 Edit 既有資料的 Threshold Torque / Downshift Torque 會保留顯示與儲存。
    const isDownshiftOffChecked = Array.from(radios).some(radio => radio.checked && radio.value === '0');
    const shouldDisable = !isTorqueTarget || isDownshiftOffChecked;

    edit_th_tor.disabled = shouldDisable;
    edit_ds_tor.disabled = shouldDisable;
    edit_ds_speed.disabled = shouldDisable;
}



function cleanNumber(value) {
    if (value === "" || value === null || value === undefined) {
        return value;
    }
    let num = parseFloat(value);
    if (isNaN(num)) {
        return value;  // 不是數字的話，直接回傳原本的
    }
    if (Number.isInteger(num)) {
        return num.toString();
    }
    // 如果是小數，檢查是不是 .0 結尾
    if (num % 1 === 0) {
        return parseInt(num).toString(); 
    }
    return value; // 其他正常小數（例如 12.3）直接回傳
}


function prepareAddStepId() {
    const stepRows = document.querySelectorAll('#step_table tbody tr');
    const nextStepId = stepRows.length + 1;

    const addStepInput = document.getElementById("add_step_id");
    if (addStepInput) {
        addStepInput.value = nextStepId;
    } else {
        console.warn("[prepareAddStepId] ⚠️ 無法找到 #add_step_id 元素，請確認該欄位已正確插入頁面。");
    }
}



function prepareAddStep(jobid, seqid) {
    $.post("?url=Step/check_step_limit", {
        jobid: jobid,
        seqid: seqid
    }, function (res) {
        let result;
        try {
            result = JSON.parse(res);
        } catch (e) {
            alertify.alert('錯誤', '回傳格式錯誤');
            return;
        }

        // ✅ 如果是準備建立第 4 個 step，禁用 downshift radio
        if (result.count === 3) {
            const downshiftOff = document.getElementById('downshift_OFF');
            const downshiftOn = document.getElementById('downshift_ON');

            if (downshiftOff) downshiftOff.disabled = true;
            if (downshiftOn) downshiftOn.disabled = true;
        } else {
            // 不是第 4 個 step，就啟用選項
            const downshiftOff = document.getElementById('downshift_OFF');
            const downshiftOn = document.getElementById('downshift_ON');

            if (downshiftOff) downshiftOff.disabled = false;
            if (downshiftOn) downshiftOn.disabled = false;
        }

        // ❌ 後端回傳禁止新增
        if (!result.allow) {
            alertify.alert('警告', result.msg);
            return;
        }

    });
}

function checkStepAndHandleDownshift(jobid, seqid, onSuccessCallback) {
    $.ajax({
        url: "?url=Step/check_step_limit",
        method: "POST",
        data: {
            jobid: jobid,
            seqid: seqid
        },
        dataType: "json",
        success: function(result) {
            // ✅ 第4個 step，禁用 downshift radio
            const downshiftOff = document.getElementById('downshift_OFF');
            const downshiftOn = document.getElementById('downshift_ON');

            if (result.count === 3) {
                if (downshiftOff) downshiftOff.disabled = true;
                if (downshiftOn) downshiftOn.disabled = true;
            } else {
                if (downshiftOff) downshiftOff.disabled = false;
                if (downshiftOn) downshiftOn.disabled = false;
            }

            // ❌ 不允許新增 Step
            if (!result.allow) {
                //alertify.alert('警告', result.msg);
                isProcessing = false;
                return;

            }

            // ✅ 通過限制，執行後續動作（例如新增 Step）
            if (typeof onSuccessCallback === 'function') {
                onSuccessCallback();
            }
        },
        error: function(xhr, status, error) {
            alertify.alert("錯誤", "無法取得步驟資料，請稍後再試！");
            isProcessing = false;
        }
    });
}


function input_check_core(prefix, step_id = '') {

    /* =====================================================
    * 語系
    * ===================================================== */
    function normalizeLang(raw){
        const v = String(raw || '').toLowerCase().replace('_','-');
        if (v.startsWith('zh-tw') || v.startsWith('zh-hant') || v === 'tw' || v === 'zh') return 'zh-tw';
        if (v.startsWith('zh-cn') || v.startsWith('zh-hans') || v === 'cn') return 'zh-cn';
        return 'en-us';
    }
    const LANG = normalizeLang(getCookie('language') || 'zh-tw');

    const MSG = {
        rpm_range:{'zh-tw':'轉速必須介於 ({min} ~ {max})','zh-cn':'转速必须介于 ({min} ~ {max})','en-us':'RPM must be between ({min} ~ {max})'},
        delay_range:{'zh-tw':'延遲時間必須介於 0.1 ~ 9.9 秒','zh-cn':'延迟时间必须介于 0.1 ~ 9.9 秒','en-us':'Delay time must be 0.1 ~ 9.9 sec'},

        angle_lo_range:{'zh-tw':'角度下限必須介於 0 ~ 30600','zh-cn':'角度下限必须介于 0 ~ 30600','en-us':'Angle low must be 0 ~ 30600'},
        angle_hi_range:{'zh-tw':'角度上限必須介於 1 ~ 30600','zh-cn':'角度上限必须介于 1 ~ 30600','en-us':'Angle high must be 1 ~ 30600'},
        angle_lo_hi:{'zh-tw':'角度下限必須小於角度上限','zh-cn':'角度下限必须小于角度上限','en-us':'Angle low must be < Angle high'},
        angle_lo_target:{'zh-tw':'角度下限必須小於目標角度','zh-cn':'角度下限必须小于目标扭力','en-us':'Angle low must be < Target angle'},
        angle_target_range:{'zh-tw':'目標角度必須介於角度上下限之間','zh-cn':'目标角度必须介于角度上下限之间','en-us':'Target angle must be inside range'},

        torque_target_range:{'zh-tw':'目標扭力必須介於 ({min} ~ {max})','zh-cn':'目标扭力必须介于 ({min} ~ {max})','en-us':'Target torque must be ({min} ~ {max})'},
        torque_lo_hi:{'zh-tw':'扭力下限必須小於扭力上限','zh-cn':'扭力下限必须小于扭力上限','en-us':'Torque low must be < Torque high'},
        torque_lo_target:{'zh-tw':'扭力下限必須小於目標扭力','zh-cn':'扭力下限必须小于目标扭力','en-us':'Torque low must be < target torque'},
        torque_hi_target:{'zh-tw':'扭力上限必須大於目標扭力','zh-cn':'扭力上限必须大于目标扭力','en-us':'Torque high must be > target torque'},

        torque_hi_out_range:{
            'zh-tw':'扭力上限超出範圍（{min} ~ {max}）',
            'zh-cn':'扭力上限超出范围（{min} ~ {max}）',
            'en-us':'Torque high is out of range ({min} ~ {max})'
        },
        torque_lo_out_range:{
            'zh-tw':'扭力下限超出範圍（{min} ~ {max}）',
            'zh-cn':'扭力下限超出范围（{min} ~ {max}）',
            'en-us':'Torque low is out of range ({min} ~ {max})'
        },
        torque_hi_gt_lo_required:{
            'zh-tw':'扭力上限需大於扭力下限',
            'zh-cn':'扭力上限需大于扭力下限',
            'en-us':'Torque high must be greater than torque low'
        },
        torque_hi_hard_max: {
            'zh-tw': '扭力上限超出範圍（最大 {max}）',
            'zh-cn': '扭力上限超出范围（最大 {max}）',
            'en-us': 'Torque high is out of range (max {max})'
        },
    };

    const t  = k => MSG[k]?.[LANG] || MSG[k]?.['zh-tw'] || '';
    const tf = (k, v = {}) => { let s = t(k); for (const x in v) s = s.replaceAll(`{${x}}`, v[x]); return s; };
    const alertMsg = (title, msg, el) => {
        alertify?.alert
            ? alertify.alert(title, msg, () => { el?.focus?.(); el?.select?.(); })
            : alert(msg);
    };

    /* =====================================================
    * ✅ 統一取得「同單位」的 tool torque range（關鍵修正）
    * ===================================================== */
    function getToolTorqueRangeUnified(){
        /*
         * 修正重點：
         * 1. 不判斷起子型號，只判斷目前工具範圍 tool_min_tor ~ tool_max_tor。
         * 2. tool_min_tor = 0 也必須是有效範圍，不能被當 false。
         * 3. tool_max_tor / tool_min_tor 是頁面 hidden input 已轉換後的範圍，優先使用。
         * 4. window.__TOOL_MAX_TOR__ 可能被 edit_step() 以 tool_maxtorque_unified 覆蓋成 55，
         *    所以只能當 DOM 不存在時的最後 fallback。
         * 5. tool_maxtorque_unified 只當 fallback，不再拿來壓縮 maxAllowed。
         */
        const readNumber = (id) => {
            const raw = document.getElementById(id)?.value;
            const s = String(raw ?? '').trim().replace(',', '.');
            if (s === '') return NaN;

            const n = Number(s);
            return Number.isFinite(n) ? n : NaN;
        };

        const readGlobalNumber = (key) => {
            const raw = window[key];
            const s = String(raw ?? '').trim().replace(',', '.');
            if (s === '') return NaN;

            const n = Number(s);
            return Number.isFinite(n) ? n : NaN;
        };

        // Target Torque 範圍仍讀工具實際最大扭力。
        // HQ/High Torque 另外使用 getHqMaxTorque() 的 55 N.m 換算值。
        syncHardMaxTorqueToHiddenInput();

        let minV = readNumber('tool_min_tor');
        if (!Number.isFinite(minV)) minV = readGlobalNumber('__TOOL_MIN_TOR__');
        if (!Number.isFinite(minV)) minV = 0;

        let maxV = readNumber('tool_max_tor');
        if (!Number.isFinite(maxV)) maxV = readNumber('tool_maxtorque_unified');
        if (!Number.isFinite(maxV)) maxV = readGlobalNumber('__TOOL_MAX_TOR__');

        // 0 是有效下限，所以只能用 Number.isFinite 判斷
        const has = Number.isFinite(minV) && Number.isFinite(maxV);

        // 防呆：若資料來源反了，直接交換，避免驗證失效
        if (has && minV > maxV) {
            const tmp = minV;
            minV = maxV;
            maxV = tmp;
        }

        return {
            hardMax: has ? maxV : NaN,
            hasToolRange: has,
            toolMin: has ? minV : NaN,
            toolMax: has ? maxV : NaN,
            maxAllowed: has ? maxV : NaN
        };
    }

    /* ===================================================== */
    const target_opt = document.getElementById(prefix+'target_opt')?.value;
    const stepNum = parseInt(step_id || '1', 10);

    /* =====================================================
    * RPM（所有模式都檢查）
    * ===================================================== */
    const elRpm = document.getElementById(prefix+'rpm');
    const rpm = parseInt(elRpm?.value, 10);
    const toolMaxRpm = parseInt(document.getElementById('tool_max_rpm')?.value, 10);
    const rpmMin = (stepNum === 1) ? 50 : 100;

    if (Number.isFinite(rpm) && Number.isFinite(toolMaxRpm) && (rpm < rpmMin || rpm > toolMaxRpm)) {
        alertMsg('RPM', tf('rpm_range', { min: rpmMin, max: toolMaxRpm }), elRpm);
        return false;
    }

    /* =====================================================
    * Delay 模式（只讀 delay）
    * ===================================================== */
    if (target_opt === '2') {
        const el = document.getElementById(prefix+'target_delay');
        const v = parseFloat(el?.value);
        if (!Number.isFinite(v) || v < 0.1 || v > 9.9) {
            alertMsg('Delay', t('delay_range'), el);
            return false;
        }
        return true;
    }

    /* =====================================================
    * Angle 模式（主驗證 Angle + 被動驗證 Torque）
    * ===================================================== */
    if (target_opt === '1') {

        const R = getToolTorqueRangeUnified();
        const HARD_MAX_TORQUE = getHqMaxTorque();
        const toolMin = R.toolMin;
        const hasToolRange = R.hasToolRange;
        const maxAllowed = R.maxAllowed;

        const torHiEl = document.getElementById(prefix+'tor_hi');
        const torLoEl = document.getElementById(prefix+'tor_lo');

        const torHiRaw = String(torHiEl?.value ?? '').trim();
        const torLoRaw = String(torLoEl?.value ?? '').trim();

        const hasTorHi = torHiRaw !== '';
        const hasTorLo = torLoRaw !== '';

        const torHi = hasTorHi ? parseFloat(torHiRaw) : NaN;
        const torLo = hasTorLo ? parseFloat(torLoRaw) : NaN;

        const torHiEnabled = hasTorHi && Number.isFinite(torHi) && torHi > 0;
        const torLoEnabled = hasTorLo && Number.isFinite(torLo) && torLo > 0;

        // LQ/Low Torque 允許小於工具下限，但不可為負數或非數字。
        if (hasTorLo && (!Number.isFinite(torLo) || torLo < 0)) {
            alertMsg('Torque', tf('torque_lo_out_range', { min: 0, max: torHiEnabled ? torHi : (getHqMaxTorqueText() || HARD_MAX_TORQUE) }), torLoEl);
            return false;
        }

        // ✅ tor_hi：只要有輸入 >0 就永遠檢查範圍
        if (torHiEnabled) {

            if (torHi > HARD_MAX_TORQUE) {
                alertMsg('Torque', tf('torque_hi_hard_max', { max: HARD_MAX_TORQUE }), torHiEl);
                return false;
            }

            // HQ/High Torque 可大於起子工具上限，但不可小於工具下限。上限改用 55 N.m 換算值。
            if (hasToolRange && torHi < toolMin) {
                alertMsg('Torque', tf('torque_hi_out_range', { min: toolMin, max: getHqMaxTorqueText() || HARD_MAX_TORQUE }), torHiEl);
                return false;
            }
        }

        // ✅ Torque window 開關（只看 tor_lo）
        const torqueWindowEnabledInAngle = torLoEnabled;

        if (hasToolRange && torqueWindowEnabledInAngle) {

            if (torLoEnabled && torLo < 0) {
                alertMsg('Torque', tf('torque_lo_out_range', { min: 0, max: torHiEnabled ? torHi : (getHqMaxTorqueText() || HARD_MAX_TORQUE) }), torLoEl);
                return false;
            }

            // window ON 才檢查 lo < hi
            if (torLoEnabled && torHiEnabled && torLo >= torHi) {
                alertMsg('Torque', t('torque_lo_hi'), torLoEl);
                return false;
            }
        }

        /* ================= 主驗證 Angle ================= */
        const elTar = document.getElementById(prefix+'target_ang');
        const elLo  = document.getElementById(prefix+'ang_lo');
        const elHi  = document.getElementById(prefix+'ang_hi');

        const tar = parseInt(elTar?.value, 10);
        const lo  = parseInt(elLo?.value, 10);
        const hi  = parseInt(elHi?.value, 10);

        if (!Number.isFinite(lo) || lo < 0 || lo > 30600) {
            alertMsg('Angle', t('angle_lo_range'), elLo);
            return false;
        }

        if (!Number.isFinite(hi) || hi < 1 || hi > 30600) {
            alertMsg('Angle', t('angle_hi_range'), elHi);
            return false;
        }

        if (lo >= hi) {
            alertMsg('Angle', t('angle_lo_hi'), elLo);
            return false;
        }

        if (!Number.isFinite(tar)) {
            alertMsg('Angle', t('angle_target_range'), elTar);
            return false;
        }

        if (lo >= tar) {
            alertMsg('Angle', t('angle_lo_target'), elLo);
            return false;
        }

        if (tar <= lo || tar >= hi) {
            alertMsg('Angle', t('angle_target_range'), elTar);
            return false;
        }

        return true;
    }

    /* =====================================================
    * Torque 模式（主驗證 Torque + 被動驗證 Angle）
    * ===================================================== */
    if (target_opt === '0') {

        const R = getToolTorqueRangeUnified();
        const HARD_MAX_TORQUE = getHqMaxTorque();
        const toolMin = R.toolMin;
        const hasToolRange = R.hasToolRange;
        const maxAllowed = R.maxAllowed;

        const elTar = document.getElementById(prefix+'target_tor');
        const elLo  = document.getElementById(prefix+'tor_lo');
        const elHi  = document.getElementById(prefix+'tor_hi');

        const tarRaw = String(elTar?.value ?? '').trim();
        const loRaw  = String(elLo?.value  ?? '').trim();
        const hiRaw  = String(elHi?.value  ?? '').trim();

        const hasTar = tarRaw !== '';
        const hasLo  = loRaw  !== '';
        const hasHi  = hiRaw  !== '';

        const tar = hasTar ? parseFloat(tarRaw) : NaN;
        const lo  = hasLo  ? parseFloat(loRaw)  : NaN;
        const hi  = hasHi  ? parseFloat(hiRaw)  : NaN;

        const loEnabled = hasLo && Number.isFinite(lo) && lo > 0;
        const hiEnabled = hasHi && Number.isFinite(hi) && hi > 0;

        /* ================= 被動檢查 Angle ================= */
        const angHiEl = document.getElementById(prefix+'ang_hi');
        const angLoEl = document.getElementById(prefix+'ang_lo');

        const angHi = parseInt(angHiEl?.value, 10);
        const angLo = parseInt(angLoEl?.value, 10);

        if (Number.isFinite(angHi) && (angHi < 1 || angHi > 30600)) {
            alertMsg('Angle', t('angle_hi_range'), angHiEl);
            return false;
        }

        if (Number.isFinite(angLo) && (angLo < 0 || angLo > 30600)) {
            alertMsg('Angle', t('angle_lo_range'), angLoEl);
            return false;
        }

        if (Number.isFinite(angLo) && Number.isFinite(angHi) && angLo >= angHi) {
            alertMsg('Angle', t('angle_lo_hi'), angLoEl);
            return false;
        }

        /* ================= Target Torque ================= */
        if (!hasTar || !Number.isFinite(tar)) {
            alertMsg('Torque', t('torque_target_range'), elTar);
            return false;
        }

        if (hasToolRange && (tar < toolMin || tar > maxAllowed)) {
            alertMsg('Torque', tf('torque_target_range',{min:toolMin,max:maxAllowed}), elTar);
            return false;
        }

        /* ================= Low Torque =================
         * LQ/Low Torque 只需 >= 0，且後面會檢查必須小於 Target Torque。
         * 不再用工具下限 tool_min_tor 限制，避免 0.001 < 0.2 被擋。
         */
        if (hasLo && (!Number.isFinite(lo) || lo < 0)) {
            alertMsg('Torque', tf('torque_lo_out_range',{min:0,max:Number.isFinite(tar) ? tar : ''}), elLo);
            return false;
        }

        /* ================= tor_hi 永遠檢查硬上限 ================= */
        if (hiEnabled && hi > HARD_MAX_TORQUE) {
            alertMsg('Torque', tf('torque_hi_hard_max', { max: HARD_MAX_TORQUE }), elHi);
            return false;
        }

        /* =====================================================
        * lo=0 且 hi=0 不允許
        * ===================================================== */
        if (!loEnabled && !hiEnabled) {
            alertMsg('Torque', t('torque_hi_gt_lo_required'), elHi);
            return false;
        }

        /* ================= HQ 必須大於 Target Torque =================
         * LQ = 0 時 torqueWindowEnabled 會是 false；
         * 但 HQ 仍然必須大於目標扭力，不能直接通過。
         */
        if (hiEnabled && hi <= tar) {
            alertMsg('Torque', t('torque_hi_target'), elHi);
            return false;
        }

        /* ================= Window 開關 ================= */
        const torqueWindowEnabled = loEnabled;
        if (!torqueWindowEnabled) return true;

        /* ================= Window ON ================= */
        // HQ/High Torque 可大於起子工具上限，但不可小於工具下限。上限改用 55 N.m 換算值。
        if (hiEnabled && hasToolRange && hi < toolMin) {
            alertMsg('Torque', tf('torque_hi_out_range',{min:toolMin,max:getHqMaxTorqueText() || HARD_MAX_TORQUE}), elHi);
            return false;
        }

        if (lo >= tar) {
            alertMsg('Torque', t('torque_lo_target'), elLo);
            return false;
        }

        if (hiEnabled && lo >= hi) {
            alertMsg('Torque', t('torque_lo_hi'), elLo);
            return false;
        }

        return true;
    }

    return true;
}




function getLabelText(element) {
    let id = element.id || "";

    // 如果 id 是 edit_xxx 開頭，就去掉 edit_
    if (id.startsWith("edit_")) {
        id = id.replace("edit_", "");
    }

    // 優先 label/data-label/相鄰元素，最後 fallback 回 id
    return (
        document.querySelector(`label[for="${element.id}"]`)?.innerText?.replace(':', '') ||
        element.getAttribute("data-label") ||
        element.previousElementSibling?.innerText?.replace(':', '') ||
        id
    );
}



function validateInput(element, pattern, min, max, compareGreaterThanId, compareLessThanId) {
    const value = element.value.trim();
    let isValid = true;
    let customMessage = "";

    // 語系處理
    let language = getCookie('language') || 'default';
    const errorText = {
        "zh-cn": {
            empty: "不可为空。",
            pattern: "格式错误。",
            range: (min, max) => `输入值必须在 ${min} ~ ${max} 之间`,
            gt: (label, val) => `必须大于 ${label}（目前值: ${val}）`,
            lt: (label, val) => `必须小于 ${label}（目前值: ${val}）`
        },
        "zh-tw": {
            empty: "不可空白。",
            pattern: "格式錯誤。",
            range: (min, max) => `輸入值必須在 ${min} ~ ${max} 之間`,
            gt: (label, val) => `必須大於 ${label}（目前值: ${val}）`,
            lt: (label, val) => `必須小於 ${label}（目前值: ${val}）`
        },
        "default": {
            empty: "This field is required.",
            pattern: "Invalid format.",
            range: (min, max) => `Value must be between ${min} and ${max}`,
            gt: (label, val) => `Must be greater than ${label} (current: ${val})`,
            lt: (label, val) => `Must be less than ${label} (current: ${val})`
        }
    };

    const msg = errorText[language] || errorText["default"];

    // 驗證空值
    if (value === "") {
        isValid = false;
        customMessage = msg.empty;
    }
    // 驗證格式
    else if (!pattern.test(value)) {
        isValid = false;
        customMessage = msg.pattern;
    }
    // 驗證範圍（min ~ max）
    else if ((min !== null && min !== undefined && parseFloat(value) < min) ||
            (max !== null && max !== undefined && parseFloat(value) > max)) {
        const minDisplay = (typeof min === 'number') ? String(min) : min;
        const maxDisplay = (typeof max === 'number') ? String(max) : max;
        isValid = false;
        customMessage = msg.range(minDisplay, maxDisplay);
    }

    // 驗證必須大於某欄位
    if (compareGreaterThanId) {
        const compareElement = document.getElementById(compareGreaterThanId);
        if (compareElement && parseFloat(value) <= parseFloat(compareElement.value)) {
            const labelText = getLabelText(compareElement) || "此欄位";
            customMessage = msg.gt(labelText, compareElement.value);
            isValid = false;
        }
    }

    // 驗證必須小於某欄位
    if (compareLessThanId) {
        const compareElement = document.getElementById(compareLessThanId);
        const compareValue = parseFloat(compareElement?.value);
        const thisValue = parseFloat(value);

        if (
            compareElement &&
            !compareElement.classList.contains("is-invalid") &&
            !isNaN(thisValue) &&
            !isNaN(compareValue) &&
            thisValue >= compareValue
        ) {
            const labelText = getLabelText(compareElement) || "此欄位";
            customMessage = msg.lt(labelText, compareElement.value);
            isValid = false;
        }
    }

    // 顯示或移除錯誤訊息
    if (!isValid) {
        element.classList.add("is-invalid");
        if (element.nextElementSibling) {
            element.nextElementSibling.innerHTML = customMessage;
        }
    } else {
        element.classList.remove("is-invalid");
        if (element.nextElementSibling) {
            element.nextElementSibling.innerHTML = "";
        }
    }

    return isValid;
}




let backupOptions = [];  // 用來存儲備份的選項

// 根據 target_option_only_tor 更新 select options
function updateTargetOption() {
    try {
        // 假設 target_option_only_tor 是從 PHP 傳過來的 JSON 資料
        let target_option_only_tor = JSON.parse('<?php echo $data["target_option_only_tor_json"]; ?>');

        // 檢查 target_option_only_tor 是否是有效的陣列
        if (!Array.isArray(target_option_only_tor)) {
            return; // 如果不是陣列，則終止函數
        }

        // 取得 select 元素
        const selectElement = document.getElementById("target_opt");

        // 檢查 select 元素是否存在
        if (!selectElement) {
            //console.error('未能找到 id="target_opt" 的元素');
            return; 
        }

        // 備份目前的選項
        backupOptions = Array.from(selectElement.options).map(option => ({
            value: option.value,
            text: option.text
        }));

        // 清空現有的選項
        while (selectElement.options.length > 0) {
            selectElement.remove(0);  // 移除第一個選項
        }

        console.log('target_option_only_tor:', target_option_only_tor);

        // 遍歷 target_option_only_tor 並創建新的 option 元素
        target_option_only_tor.forEach((option) => {
            // 確保每個選項有有效的 value 和 text
            if (option.value !== undefined && option.text !== undefined) {
                const optionElement = document.createElement("option");
                optionElement.value = option.value;  // 設定選項的 value 屬性
                optionElement.textContent = option.text;  // 設定選項的顯示文字
                selectElement.appendChild(optionElement);  // 將選項加入到 select 中
            } else {
                //console.warn('無效的選項:', option);  // 如果選項格式不正確，輸出警告
            }
        });

    } catch (error) {
        //console.error('解析 JSON 發生錯誤:', error);
    }
}

// 恢復原本的選項
function restoreBackupOptions() {
    const selectElement = document.getElementById("target_opt");

    // 清空現有的選項
    while (selectElement.options.length > 0) {
        selectElement.remove(0);
    }

    // 恢復備份的選項
    backupOptions.forEach((option) => {
        const optionElement = document.createElement("option");
        optionElement.value = option.value;
        optionElement.textContent = option.text;
        selectElement.appendChild(optionElement);
    });

}

function cleanNumber(value) {
    if (value === "" || value === null || value === undefined) {
        return value;
    }
    let num = parseFloat(value);
    if (isNaN(num)) {
        return value;  // 不是數字的話，直接回傳原本的
    }
    if (Number.isInteger(num)) {
        return num.toString();
    }
    // 如果是小數，檢查是不是 .0 結尾
    if (num % 1 === 0) {
        return parseInt(num).toString(); 
    }
    return value; // 其他正常小數（例如 12.3）直接回傳
}


function parseTorqueValue(id) {
    const str = document.getElementById(id)?.value || "0";
    const num = +str;
    const display = (num % 1 === 0) ? num.toFixed(1) : str;
    return { str, num, display };
}
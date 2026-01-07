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
    'kgf.m':  { precision: 3, eps: 0.0005 },
    'lbf.in': { precision: 2, eps: 0.005  },
    'cN.m':   { precision: 1, eps: 0.05   }
};

// 取得目前扭力單位（依你系統實際顯示來源）
function getTorqueUnit() {
    return document.getElementById('torque_unit_text')?.value
        || document.getElementById('tor_unit_label')?.innerText
        || 'N.m';
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



function create_step() {
    document.getElementById('newstep').style.display = 'block';

 

    const tool_min_tor_raw = document.getElementById('tool_min_tor').value || "0";
    //const tool_min_tor = parseFloat(tool_min_tor_raw); // ✅ 自動去掉多餘的 0
    const tool_min_tor  = tool_min_tor_raw;
    const tor_hi_unified_raw = document.getElementById('tool_maxtorque_unified').value || "0";
    //const tor_hi_unified = parseFloat(tor_hi_unified_raw); // ✅ 自動去尾 0
    const tor_hi_unified =tor_hi_unified_raw;

        
    // 預設值
    document.getElementById('rpm').value = 100;
    document.getElementById('th_tor').value = (0.0).toFixed(1);
    document.getElementById('ds_tor').value = (0.0).toFixed(1);
    document.getElementById('ds_speed').value = 100;
    document.getElementById("direction_CW").checked = true;
    document.getElementById('ang_hi').value = 30600;
    document.getElementById('ang_lo').value = 0;
    document.getElementById('tor_hi').value =  tor_hi_unified;
    document.getElementById('tor_lo').value = 0;
    document.getElementById('target_tor').value = tool_min_tor;
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
        // 當 downshift_ON 被選中時，解除 disabled
        document.getElementById('th_tor').disabled = false;
        document.getElementById('ds_tor').disabled = false;
        document.getElementById('ds_speed').disabled = false;
    } else {
        // 當 downshift_OFF 被選中時，設置 disabled
        document.getElementById('th_tor').disabled = true;
        document.getElementById('ds_tor').disabled = true;
        document.getElementById('ds_speed').disabled = true;
    }
}


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

            const [, target_opt] = cleanString.match(/\[target_opt\]\s*=>\s*([^ ]+)/) || [, null];
            const [, target_tor] = cleanString.match(/\[target_tor\]\s*=>\s*([^ ]+)/) || [, null];
            const [, target_ang] = cleanString.match(/\[target_ang\]\s*=>\s*([^ ]+)/) || [, null];
            const [, target_delay] = cleanString.match(/\[target_delay\]\s*=>\s*([^ ]+)/) || [, null];
            const [, tor_hi] = cleanString.match(/\[tor_hi\]\s*=>\s*([^ ]+)/) || [, null];
            const [, tor_lo] = cleanString.match(/\[tor_lo\]\s*=>\s*([^ ]+)/) || [, null];
            const [, ang_hi] = cleanString.match(/\[ang_hi\]\s*=>\s*([^ ]+)/) || [, null];
            const [, ang_lo] = cleanString.match(/\[ang_lo\]\s*=>\s*([^ ]+)/) || [, null];
            const [, rpm] = cleanString.match(/\[rpm\]\s*=>\s*([^ ]+)/) || [, null];
            const [, direction] = cleanString.match(/\[direction\]\s*=>\s*([^ ]+)/) || [, null];
            const [, th_mode] = cleanString.match(/\[th_mode\]\s*=>\s*([^ ]+)/) || [, null];
            const [, pnf_set] = cleanString.match(/\[pnf_set\]\s*=>\s*([^ ]+)/) || [, null];
            const [, ds_tor] = cleanString.match(/\[ds_tor\]\s*=>\s*([^ ]+)/) || [, null];
            const [, ds_speed] = cleanString.match(/\[ds_speed\]\s*=>\s*([^ ]+)/) || [, null];
            const [, th_tor] = cleanString.match(/\[th_tor\]\s*=>\s*([^ ]+)/) || [, null];
            const [, step_id] = cleanString.match(/\[step_id\]\s*=>\s*([^ ]+)/) || [, null];
            const [, tool_maxtorque] = cleanString.match(/\[tool_maxtorque\]\s*=>\s*([^ ]+)/) || [, null];
            const [, tool_mintorque] = cleanString.match(/\[tool_mintorque\]\s*=>\s*([^ ]+)/) || [, null];

            document.getElementById('editstep').style.display = 'block';
            document.querySelector("select[name='edit_target_opt']").value = target_opt;
            document.getElementById("tool_max_tor").value = tool_maxtorque;
            document.getElementById("tool_min_tor").value = tool_mintorque;



            if (target_opt == 0) {
                const inputs = document.querySelectorAll("input[type='text'], input[type='radio'], select");
                inputs.forEach(input => input.disabled = false);

                document.getElementById("edit_target_tor").value = target_tor;
                document.getElementById("edit_target_ang_item").style.display = 'none';
                document.getElementById("edit_target_delay_item").style.display = 'none';
                document.getElementById("edit_target_tor_item").style.display = 'block';

                // ✅ 檢查是否為第 4 個 step，禁用 downshift
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

                    if (result.count === 4) {
                        const downshiftOff = document.getElementById('edit_downshift_OFF');
                        const downshiftOn = document.getElementById('edit_downshift_ON');
                        if (downshiftOff) downshiftOff.disabled = true;
                        if (downshiftOn) downshiftOn.disabled = true;
                    }
                });
            }

            if (target_opt == 1) {
                const inputs = document.querySelectorAll("input[type='text'], input[type='radio'], select");
                inputs.forEach(function (input) {
                    if (input.type === 'radio' && input.name === 'edit_direction') {
                        input.disabled = false;
                    } else if (input.type !== 'radio') {
                        input.disabled = false;
                    }
                });

                document.getElementById("edit_target_ang").value = target_ang;
                document.getElementById("edit_target_tor_item").style.display = 'none';
                document.getElementById("edit_target_delay_item").style.display = 'none';
                document.getElementById("edit_target_ang_item").style.display = 'block';

                disableElementsByName("edit_th_mode");
                disableElementById('edit_ds_tor');
                disableElementById('edit_ds_speed');
                disableElementById('edit_th_tor');
                enableElementById('edit_rpm');
                enableElementById('edit_tor_hi');
                enableElementById('edit_tor_lo');
                enableElementById('edit_ang_hi');
                enableElementById('edit_ang_lo');
            }

            if (target_opt == 2) {
                document.getElementById("edit_target_delay").value = target_delay;
                document.getElementById("edit_target_tor_item").style.display = 'none';
                document.getElementById("edit_target_ang_item").style.display = 'none';
                document.getElementById("edit_target_delay_item").style.display = 'block';

                disableElementById('edit_rpm');
                disableElementById('edit_ds_tor');
                disableElementById('edit_ds_speed');
                disableElementById('edit_th_tor');
                disableElementById('edit_tor_hi');
                disableElementById('edit_tor_lo');
                disableElementById('edit_ang_hi');
                disableElementById('edit_ang_lo');
                disableElementsByName("edit_th_mode");
                disableElementsByName("edit_direction");
            }

            document.getElementById("edit_rpm").value = rpm;
            document.getElementById("edit_ds_speed").value = ds_speed;
            document.getElementById("edit_ds_tor").value = ds_tor;
            document.getElementById("edit_th_tor").value = th_tor;
            document.getElementById("edit_tor_hi").value = tor_hi;
            document.getElementById("edit_tor_lo").value = tor_lo;
            document.getElementById("edit_ang_hi").value = ang_hi;
            document.getElementById("edit_ang_lo").value = ang_lo;
            document.getElementById('edit_step_id').value = step_id;

            const radioButtons_th_mode = document.getElementsByName("edit_th_mode");
            setRadioButton_value(radioButtons_th_mode, th_mode);

            const radioButtons_direction = document.getElementsByName("edit_direction");
            setRadioButton_value(radioButtons_direction, direction);

        
            const radioButtons_pnf_set = document.getElementsByName("edit_pnf_set");
            setRadioButton_value(radioButtons_pnf_set, pnf_set);

            toggleThTorDisabled();
        },
        error: function (xhr, status, error) {
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

function disableElements(elements, value) {
    elements.forEach(function(element) {
        element.disabled = value;
        element.value = value === true ? 0 : ''; 
    });
}

function disableElementById(id, value = '') {
    var element = document.getElementById(id); 
    if (element) {
        element.disabled = true;  
        element.value = value;    
    } else {
        console.log("元素未找到: " + id);  
    }
}

function disableElementsByName(elementName) {
    const elements = document.getElementsByName(elementName);
    for (let i = 0; i < elements.length; i++) {
        elements[i].disabled = true;
    }
}

function enableElementById(id, value = '') {
    var element = document.getElementById(id); 
    if (element && element.disabled) {
        element.disabled = false;  
        element.value = value;     
        //console.log('元素已启用:', id, '并设置值为:', value);
    } else if (element) {
        //console.log('元素已是启用状态:', id);
    } else {
        console.log("元素未找到: " + id);  
    }
}
function enableElementByName(name, value = '') {
    var elements = document.getElementsByName(name); 
    if (elements.length > 0) {
        // 遍歷所有具有該 name 的元素
        elements.forEach(function(element) {
            if (element.disabled) {
                element.disabled = false;  
                element.value = value;     
                //console.log('元素已启用:', name, '并设置值为:', value);
            } else {
                //console.log('元素已是启用状态:', name);
            }
        });
    } else {
        console.log("未找到具有 name '" + name + "' 的元素");
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
    const tor_hi_unified = document.getElementById('tool_maxtorque_unified').value;
    
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
        enableElementById('th_tor','0.0');
        enableElementById('ds_tor','0.0');
        enableElementById('ds_speed','100');
        enableElementById('direction_CW','');
        enableElementById('direction_CCW','');
        enableElementById('downshift_ON','');
        enableElementById('downshift_OFF','');

        document.getElementById("downshift_ON").checked = true;

    } else if (targetValue == 1) {
        targetAngItem.style.display = "block";

        enableElementById('tor_hi', ''); 
        enableElementById('tor_lo', ''); 
        enableElementById('ang_hi', ''); 
        enableElementById('ang_lo', ''); 
        enableElementById('direction_CW','');
        enableElementById('direction_CCW','');
        disableElementById('th_tor','0.0');
        disableElementById('ds_tor','0.0');
        disableElementById('ds_speed','100');
        disableElementById('downshift_ON','');
        disableElementById('downshift_OFF','');
        document.getElementById("downshift_OFF").checked = true;
        document.getElementById('tor_hi').value = 55;
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
        disableElementById('th_tor','0.0');
        disableElementById('ds_tor','0.0');
        disableElementById('ds_speed','100');
        disableElementById('direction_CW','');
        disableElementById('direction_CCW','');
        disableElementById('downshift_ON','');
        disableElementById('downshift_OFF','');
        document.getElementById('tor_hi').value = 55;
        document.getElementById('tor_lo').value = 0;
        document.getElementById('ang_hi').value = 30600;
        document.getElementById('ang_lo').value = 0;
        document.getElementById('target_delay').value = (1.0).toFixed(1);


        document.getElementById("downshift_OFF").checked = true;

    }
}

function targetOptChangeHandler() {
    const sel = document.querySelector("select[name='edit_target_opt']");
    if (!sel) return;
    handleTargetOptChange(sel.value);
}

function handleTargetOptChange(target_opt) {

    const rpm       = document.getElementById("edit_rpm")?.value ?? '';
    const ds_tor    = document.getElementById("edit_ds_tor")?.value ?? '';
    const ds_speed  = document.getElementById("edit_ds_speed")?.value ?? '';
    const th_tor    = document.getElementById("edit_th_tor")?.value ?? '';

    const tor_hi    = cleanNumber(document.getElementById("edit_tor_hi")?.value ?? '');
    const tor_lo    = cleanNumber(document.getElementById("edit_tor_lo")?.value ?? '');
    const ang_hi    = document.getElementById("edit_ang_hi")?.value ?? '';
    const ang_lo    = document.getElementById("edit_ang_lo")?.value ?? '';

    const targetTorEl   = document.getElementById("edit_target_tor");
    const targetAngEl   = document.getElementById("edit_target_ang");
    const targetDelayEl = document.getElementById("edit_target_delay");

    // ⭐⭐⭐ 關鍵：全域變數才是唯一可信來源
    let toolMinTor = (window.__TOOL_MIN_TOR__ ?? '').toString().trim();

    // DOM 只當備援（不可信）
    if (!toolMinTor) {
        toolMinTor = (document.getElementById("tool_min_tor")?.value ?? '').trim();
    }

    // 最終 fallback（避免空白）
    const fallbackTor = toolMinTor || tor_lo || '0';

    // ✅ 目標角度預設值
    const fallbackAng = '1800';


    /* =====================================================
     * 目標扭力
     * ===================================================== */
    if (String(target_opt) === '0') {

        document.getElementById("edit_target_tor_item").style.display   = 'block';
        document.getElementById("edit_target_ang_item").style.display   = 'none';
        document.getElementById("edit_target_delay_item").style.display = 'none';

        // ⭐ 第一次補值
        if (targetTorEl && !targetTorEl.value) {
            targetTorEl.value = fallbackTor;
        }

        // ⭐ 第二次補值（防 UI 重畫 / re-render）
        setTimeout(function () {
            const el = document.getElementById("edit_target_tor");
            if (el && !el.value) {
                el.value = fallbackTor;
            }
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

        document.getElementById("downshift_ON").checked = true;
    }

    /* =====================================================
     * 目標角度
     * ===================================================== */
    if (String(target_opt) === '1') {

        document.getElementById("edit_target_ang_item").style.display   = 'block';
        document.getElementById("edit_target_tor_item").style.display   = 'none';
        document.getElementById("edit_target_delay_item").style.display = 'none';

        // ✅ 第一次補值：如果原本是目標扭力存的，target_ang 常常會是空白
        if (targetAngEl && (targetAngEl.value == null || String(targetAngEl.value).trim() === '')) {
            targetAngEl.value = fallbackAng;
        }

        // ✅ 第二次補值：防止某些情況切換 UI 後又被洗掉
        setTimeout(function () {
            const el = document.getElementById("edit_target_ang");
            if (el && (el.value == null || String(el.value).trim() === '')) {
                el.value = fallbackAng;
            }
        }, 0);


        enableElementById('edit_tor_hi', tor_hi);
        enableElementById('edit_tor_lo', tor_lo);
        enableElementById('edit_ang_hi', ang_hi);
        enableElementById('edit_ang_lo', ang_lo);
        disableElementById('edit_ds_tor', ds_tor);
        disableElementById('edit_ds_speed', ds_speed);
        disableElementById('edit_th_tor', th_tor);
        disableElementsByName("edit_th_mode");
        enableElementByName("edit_direction");
        enableElementById('edit_rpm', rpm);

        document.getElementById("downshift_OFF").checked = true;
    }

    /* =====================================================
     * 目標延遲
     * ===================================================== */
    if (String(target_opt) === '2') {

        document.getElementById("edit_target_delay_item").style.display = 'block';
        document.getElementById("edit_target_tor_item").style.display   = 'none';
        document.getElementById("edit_target_ang_item").style.display   = 'none';

        disableElementById('edit_th_tor', th_tor);
        disableElementById('edit_tor_hi', tor_hi);
        disableElementById('edit_tor_lo', tor_lo);
        disableElementById('edit_ang_hi', ang_hi);
        disableElementById('edit_ang_lo', ang_lo);

        disableElementById('edit_rpm', rpm);
        disableElementById('edit_ds_tor', ds_tor);
        disableElementById('edit_ds_speed', ds_speed);
        disableElementsByName("edit_th_mode");
        disableElementsByName("edit_direction");

        document.getElementById("downshift_OFF").checked = true;
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




function toggleThTorDisabled() {

    const radios = document.getElementsByName('edit_th_mode');
    const edit_th_tor = document.getElementById('edit_th_tor');
    const edit_ds_tor = document.getElementById('edit_ds_tor');
    const edit_ds_speed = document.getElementById('edit_ds_speed');

    // 檢查是否有 "downshift_OFF" 單選框被選中
    const isDownshiftOffChecked = Array.from(radios).some(radio => radio.checked && radio.value === '0');
    edit_th_tor.disabled = isDownshiftOffChecked;
    edit_ds_tor.disabled = isDownshiftOffChecked;
    edit_ds_speed.disabled = isDownshiftOffChecked;


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


// =====================================================
// 核心表單檢查邏輯（FINAL CLEAN VERSION）
// =====================================================
function input_check_core(prefix, step_id = '') {

    /* =====================================================
     * 語系
     * ===================================================== */
    const getLang = () =>
        (window.getLangAndUnit?.().lang
            || window.getCookieSafe?.('language')
            || 'zh-tw').toLowerCase();

    const LANG = getLang();

    const MSG = {

        /* ========== Torque ========== */
        torque_cross: {
            'zh-tw': '目標扭力必須大於最小扭力、小於最大扭力',
            'zh-cn': '目标扭力必须大于最小扭力、小于最大扭力',
            'en-us': 'Target torque must be greater than the minimum torque and less than the maximum torque'
        },
        torque_lo_hi: {
            'zh-tw': '扭力下限必須小於扭力上限',
            'zh-cn': '扭力下限必须小于扭力上限',
            'en-us': 'Torque low limit must be less than torque high limit'
        },

        /* ========== Angle ========== */
        angle_cross: {
            'zh-tw': '目標角度必須大於角度下限、小於角度上限',
            'zh-cn': '目标角度必须大于角度下限、小于角度上限',
            'en-us': 'Target angle must be greater than the minimum angle and less than the maximum angle'
        },
        angle_lo_range: {
            'zh-tw': '角度下限必須介於 0 ～ 30600',
            'zh-cn': '角度下限必须介于 0 ～ 30600',
            'en-us': 'Minimum angle must be between 0 and 30600'
        },
        angle_hi_range: {
            'zh-tw': '角度上限必須介於 0 ～ 30600',
            'zh-cn': '角度上限必须介于 0 ～ 30600',
            'en-us': 'Maximum angle must be between 0 and 30600'
        },
        angle_lo_hi: {
            'zh-tw': '角度下限必須小於角度上限',
            'zh-cn': '角度下限必须小于角度上限',
            'en-us': 'Minimum angle must be less than the maximum angle'
        },

        /* ========== Downshift / Threshold ========== */
        th_tor_range: {
            'zh-tw': '門檻扭力必須介於 ({min} ~ {max})',
            'zh-cn': '门槛扭力必须介于 ({min} ~ {max})',
            'en-us': 'Threshold torque must be between ({min} ~ {max})'
        },
        ds_tor_range: {
            'zh-tw': '降速點扭力必須介於 ({min} ~ {max})',
            'zh-cn': '降速点扭力必须介于 ({min} ~ {max})',
            'en-us': 'Downshift torque must be between ({min} ~ {max})'
        }
    };

    const t = (k) => MSG[k]?.[LANG] || MSG[k]?.['en-us'] || k;
    const tf = (k, v = {}) => {
        let s = t(k);
        for (const key in v) s = s.replaceAll(`{${key}}`, v[key]);
        return s;
    };

    const alertMsg = (title, msg, el) => {
        if (window.alertify?.alert) {
            alertify.alert(title, msg, () => {
                try { el?.focus(); el?.select?.(); } catch {}
            });
        } else {
            alert(msg);
        }
    };

    /* =====================================================
     * Torque 精度
     * ===================================================== */
    const TORQUE_EPS = {
        'N.m': 0.0005,
        'kgf.cm': 0.005,
        'kgf.m': 0.0005,
        'lbf.in': 0.005,
        'cN.m': 0.05
    };

    const unit =
        document.getElementById('torque_unit_text')?.value
        || document.getElementById('tor_unit_label')?.innerText
        || 'N.m';

    const eps = TORQUE_EPS[unit] ?? 0.0005;

    /* =====================================================
     * 模式判斷
     * ===================================================== */
    const target_opt = document.getElementById(prefix + 'target_opt')?.value;

    /* =====================================================
     * Angle 模式（target_opt === '1'）
     * ===================================================== */
    if (target_opt === '1') {

        const elTar = document.getElementById(prefix + 'target_ang');
        const elLo  = document.getElementById(prefix + 'ang_lo');
        const elHi  = document.getElementById(prefix + 'ang_hi');

        const tar = parseInt(elTar?.value, 10);
        const lo  = parseInt(elLo?.value, 10);
        const hi  = parseInt(elHi?.value, 10);

        if (Number.isFinite(lo) && (lo < 0 || lo > 30600)) {
            alertMsg(t('angle_lo_range'), t('angle_lo_range'), elLo);
            return false;
        }

        if (Number.isFinite(hi) && (hi < 0 || hi > 30600)) {
            alertMsg(t('angle_hi_range'), t('angle_hi_range'), elHi);
            return false;
        }

        if (Number.isFinite(lo) && Number.isFinite(hi) && lo >= hi) {
            alertMsg(t('angle_lo_hi'), t('angle_lo_hi'), elLo);
            return false;
        }

        if (Number.isFinite(tar) && Number.isFinite(hi) && tar >= hi) {
            alertMsg(t('angle_cross'), t('angle_cross'), elTar);
            return false;
        }

        if (Number.isFinite(tar) && Number.isFinite(lo) && tar <= lo) {
            alertMsg(t('angle_cross'), t('angle_cross'), elTar);
            return false;
        }

        return true;
    }

    /* =====================================================
     * Torque + Downshift（target_opt === '0'）
     * ===================================================== */
    if (target_opt === '0') {

        const elTar = document.getElementById(prefix + 'target_tor');
        const tar   = parseFloat(elTar?.value);

        const toolMin = parseFloat(document.getElementById('tool_min_tor')?.value);
        const toolMax = parseFloat(document.getElementById('tool_max_tor')?.value);

        if (
            Number.isFinite(tar) &&
            Number.isFinite(toolMin) &&
            Number.isFinite(toolMax) &&
            (tar < toolMin - eps || tar > toolMax + eps)
        ) {
            alertMsg(t('torque_cross'), t('torque_cross'), elTar);
            return false;
        }

        if (document.getElementById('downshift_ON')?.checked) {

            const elTh = document.getElementById(prefix + 'th_tor');
            const elDs = document.getElementById(prefix + 'ds_tor');

            const th = parseFloat(elTh?.value);
            const ds = parseFloat(elDs?.value);

            const minStr = (0).toFixed(3);
            const maxStr = Number.isFinite(toolMax) ? toolMax.toFixed(3) : '';

            if (Number.isFinite(th) && (th < 0 - eps || th > toolMax + eps)) {
                alertMsg('Torque', tf('th_tor_range', { min: minStr, max: maxStr }), elTh);
                return false;
            }

            if (Number.isFinite(ds) && (ds < 0 - eps || ds > toolMax + eps)) {
                alertMsg('Torque', tf('ds_tor_range', { min: minStr, max: maxStr }), elDs);
                return false;
            }
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
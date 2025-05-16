

 // 核心表單檢查邏輯
function input_check_core(prefix) {
    // 取得選擇目標類型 (Torque / Angle / Delay)
    const target_opt = document.getElementById(prefix + "target_opt")?.value;
    let step_id = "";
    if (prefix === "edit_") {
        step_id = document.getElementById("new_edit_step_id")?.value?.trim() || "";
    } else {
        step_id = document.getElementById("add_step_id")?.value?.trim() || "";
    }

    // 工具參數設定
    let Tool_Max_Torque = parseFloat(document.getElementById('tool_max_tor')?.value || 0);
    let Tool_Min_Torque = parseFloat(document.getElementById('tool_min_tor')?.value || 0);
    let Tool_Max_RPM = parseFloat(document.getElementById('tool_max_rpm')?.value || 0);
    let Tool_Min_RPM = parseFloat(document.getElementById('tool_min_rpm')?.value || 0);

    // 特殊邏輯：Step 1 限制轉速下限為 50
    if (step_id === "1") {
        Tool_Min_RPM = 50;
        console.log(`[Step ID ${step_id}] 強制 Tool_Min_RPM = 50`);
    }

    const isDownshiftOff = document.getElementById(prefix + "downshift_OFF")?.checked || false;
    let conditions = [];

    // 根據目標類型設定驗證規則
    if (target_opt === "0") { // Torque 控制
        const target_torque = parseFloat(document.getElementById(prefix + 'target_tor')?.value || 0);

        conditions.push(
            { id: prefix + 'target_tor', pattern: /^\d{1,5}(\.\d{1})?$/, min: Tool_Min_Torque, max: Tool_Max_Torque },
            { id: prefix + 'tor_hi', pattern: /^\d{1,5}(\.\d{1})?$/, min: 1, max: 55, compareGreaterThanId: prefix + 'target_tor' },
            { id: prefix + 'tor_lo', pattern: /^\d{1,5}(\.\d{1})?$/, min: 0, max: 3, compareLessThanId: prefix + 'target_tor' },
            { id: prefix + 'ang_hi', pattern: /^\d{0,5}$/, min: 1, max: 9999, compareGreaterThanId: prefix + 'target_ang' },
            { id: prefix + 'ang_lo', pattern: /^\d{0,5}$/, min: 0, max: 9999, compareLessThanId: prefix + 'target_ang' },
            { id: prefix + 'rpm', pattern: /^\d{0,4}$/, min: Tool_Min_RPM, max: Tool_Max_RPM }
        );

        if (!isDownshiftOff) {
            conditions.push(
                { id: prefix + 'th_tor', pattern: /^\d{1,5}(\.\d{1})?$/, min: 0, max: target_torque },
                { id: prefix + 'ds_tor', pattern: /^\d{1,5}(\.\d{1})?$/, min: 0, max: target_torque },
                { id: prefix + 'ds_speed', pattern: /^\d{0,4}$/, min: Tool_Min_RPM, max: Tool_Max_RPM }
            );
        }
    }

    if (target_opt === "1") { // Angle 控制
        conditions.push(
            { id: prefix + 'target_ang', pattern: /^\d{0,5}$/, min: 1, max: 9999 },
            { id: prefix + 'tor_hi', pattern: /^\d{1,5}(\.\d{1})?$/, min: 1, max: 55, compareGreaterThanId: prefix + 'target_tor' },
            { id: prefix + 'tor_lo', pattern: /^\d{1,5}(\.\d{1})?$/, min: 0, max: low_torque, compareLessThanId: prefix + 'target_tor' },
            { id: prefix + 'ang_hi', pattern: /^\d{0,5}$/, min: 1, max: 9999, compareGreaterThanId: prefix + 'target_ang' },
            { id: prefix + 'ang_lo', pattern: /^\d{0,5}$/, min: 0, max: 9999, compareLessThanId: prefix + 'target_ang' },
            { id: prefix + 'rpm', pattern: /^\d{0,4}$/, min: Tool_Min_RPM, max: Tool_Max_RPM }
        );
    }

    if (target_opt === "2") { // Delay 控制
        conditions.push(
            { id: prefix + 'target_delay', pattern: /^\d{1,5}(\.\d{1})?$/, min: 0.1, max: 9.9 }
        );
    }

    // 驗證欄位
    let isFormValid = true;
    conditions.forEach(input => {
        const element = document.getElementById(input.id);
        if (element && !validateInput(element, input.pattern, input.min, input.max, input.compareGreaterThanId, input.compareLessThanId)) {
            isFormValid = false;
        }
    });

    return isFormValid;
}


function create_step() {

    document.getElementById('newstep').style.display = 'block';
    document.getElementById('rpm').value = 100;
    document.getElementById('th_tor').value = (0.0).toFixed(1);
    document.getElementById('ds_tor').value = (0.0).toFixed(1);
    document.getElementById('ds_speed').value = 100;
    
    document.getElementById('ang_hi').value= 9999;
    document.getElementById('ang_lo').value= 0;
    document.getElementById('tor_hi').value= 55;
    document.getElementById('tor_lo').value= 0;
    



    var targetoptionselect = document.getElementById('target_opt');
    targetoptionselect.addEventListener('change', function() {

    var target_opt_Value = targetoptionselect.value;
        localStorage.setItem('target_option', target_opt_Value);
        toggleVisibility(target_opt_Value);
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
    
    targetTorItem.style.display = 'none';
    targetAngItem.style.display = 'none';
    targetDelayItem.style.display = 'none';

        
    document.getElementById('ang_hi').value = 9999;
    document.getElementById('ang_lo').value = 0;
    document.getElementById('tor_hi').value = 55;
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
        document.getElementById('ang_hi').value = 9999;
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
        document.getElementById('ang_hi').value = 9999;
        document.getElementById('ang_lo').value = 0;
        document.getElementById('target_delay').value = (1.0).toFixed(1);


        document.getElementById("downshift_OFF").checked = true;

    }
}

function targetOptChangeHandler() {
    var target_opt = document.querySelector("select[name='edit_target_opt']").value;
    handleTargetOptChange(target_opt); 
}

function handleTargetOptChange(target_opt) {

    var rpm = document.getElementById("edit_rpm").value;  
    var ds_tor = document.getElementById("edit_ds_tor").value;  
    var ds_speed = document.getElementById("edit_ds_speed").value;  
    var th_tor = document.getElementById("edit_th_tor").value; 
    var tor_hi =  cleanNumber(document.getElementById("edit_tor_hi").value);
    var tor_lo =  cleanNumber(document.getElementById("edit_tor_lo").value);
    var ang_hi = document.getElementById("edit_ang_hi").value; 
    var ang_lo = document.getElementById("edit_ang_lo").value;

    var target_tor = document.getElementById("edit_target_tor").value;
    var target_ang = document.getElementById("edit_target_ang").value;
    var target_delay = document.getElementById("edit_target_delay").value;


    if (target_opt == 0) {
        document.getElementById("edit_target_tor_item").style.display = 'block';
        document.getElementById("edit_target_ang_item").style.display = 'none';
        document.getElementById("edit_target_delay_item").style.display = 'none';


        enableElementById('edit_tor_hi',tor_hi);
        enableElementById('edit_tor_lo',tor_lo);
        enableElementById('edit_ang_hi',ang_hi);
        enableElementById('edit_ang_lo',ang_lo);
        enableElementById('edit_rpm',rpm);
        enableElementById('edit_ds_tor',ds_tor);
        enableElementById('edit_ds_speed',ds_speed);
        enableElementById('edit_th_tor',th_tor);
        enableElementByName("edit_direction");
        enableElementByName("edit_th_mode");

        document.getElementById("downshift_ON").checked = true;
  

    }

    if (target_opt == 1) {
        document.getElementById("edit_target_ang_item").style.display = 'block';
        document.getElementById("edit_target_tor_item").style.display = 'none';
        document.getElementById("edit_target_delay_item").style.display = 'none';

        enableElementById('edit_tor_hi',tor_hi);
        enableElementById('edit_tor_lo',tor_lo);
        enableElementById('edit_ang_hi',ang_hi);
        enableElementById('edit_ang_lo',ang_lo);
        disableElementById('edit_ds_tor',ds_tor);
        disableElementById('edit_ds_speed',ds_speed);
        disableElementById('edit_th_tor',th_tor);
        disableElementsByName("edit_th_mode");
        enableElementByName("edit_direction");
        enableElementById('edit_rpm',rpm);

        document.getElementById("downshift_OFF").checked = true;
  
    }

    if (target_opt == 2) {
        document.getElementById("edit_target_delay_item").style.display = 'block';
        document.getElementById("edit_target_tor_item").style.display = 'none';
        document.getElementById("edit_target_ang_item").style.display = 'none';

        disableElementById('edit_th_tor',th_tor);
        disableElementById('edit_tor_hi',tor_hi);
        disableElementById('edit_tor_lo',tor_lo);
        disableElementById('edit_ang_hi',ang_hi);
        disableElementById('edit_ang_lo',ang_lo);

        disableElementById('edit_rpm',rpm);
        disableElementById('edit_ds_tor',ds_tor);
        disableElementById('edit_ds_speed',ds_speed);
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


function del_stepid(step_id){

    if(step_id) {

        var language = getCookie('language');
        if(language == "zh-cn"){
            var text_info ='你确定吗？';
            var title = 'Copy Job';
        }else if(language == "zh-tw"){
            var text_info ='你確定嗎 ?';
            var title = 'Copy Job';
        }else{
            var text_info ='Are you sure ?';
            var title = 'Copy Job';
        }

 
        $.ajax({
            url: "?url=Step/delete_step",
            method: "POST",
            data:{ 
                stepid:step_id,
                jobid:jobid,
                seqid:seqid
            },
            success: function(response) {
                alertify.confirm(text_info, function (result) {

                    document.getElementById('spinner').style.display = 'block';
                    
                    var responseData = JSON.parse(response);
                    // 延遲 1000 毫秒後隱藏加載動畫，並在隱藏後顯示 alertify 彈跳視窗
                    setTimeout(function() {
                        // 隱藏加載動畫
                        document.getElementById('spinner').style.display = 'none';

                        // 顯示 alertify 彈跳視窗
                        alertify.alert(responseData.res_type, responseData.res_msg, function() {
                            // 刷新頁面
                            history.go(0);  
                        });

                        // 在 3 秒後自動關閉 alertify 彈跳視窗
                        setTimeout(function() {
                            alertify.closeAll();  // 關閉所有開啟的 alertify 彈跳視窗
                            history.go(0); 
                        }, 3000); 
                    }, 1000); // 延遲 1000 毫秒

                });
            },
            error: function(xhr, status, error) {
                
            }
        });

    }

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
    document.getElementById("add_step_id").value = nextStepId;
}
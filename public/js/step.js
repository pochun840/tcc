function create_step() {
    document.getElementById('newstep').style.display = 'block';

    // 預設值
    document.getElementById('rpm').value = 100;
    document.getElementById('th_tor').value = (0.0).toFixed(1);
    document.getElementById('ds_tor').value = (0.0).toFixed(1);
    document.getElementById('ds_speed').value = 100;
    document.getElementById("direction_CW").checked = true;
    document.getElementById('ang_hi').value = 9999;
    document.getElementById('ang_lo').value = 0;
    document.getElementById('tor_hi').value = 55;
    document.getElementById('tor_lo').value = 0;
    document.getElementById('target_tor').value = parseFloat(document.getElementById('tool_min_tor').value);
    document.getElementById('target_ang').value = 1800;

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
            const [, ds_tor] = cleanString.match(/\[ds_tor\]\s*=>\s*([^ ]+)/) || [, null];
            const [, ds_speed] = cleanString.match(/\[ds_speed\]\s*=>\s*([^ ]+)/) || [, null];
            const [, th_tor] = cleanString.match(/\[th_tor\]\s*=>\s*([^ ]+)/) || [, null];
            const [, step_id] = cleanString.match(/\[step_id\]\s*=>\s*([^ ]+)/) || [, null];

            document.getElementById('editstep').style.display = 'block';
            document.querySelector("select[name='edit_target_opt']").value = target_opt;

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
            document.getElementById("edit_tor_hi").value = cleanNumber(tor_hi);
            document.getElementById("edit_tor_lo").value = cleanNumber(tor_lo);
            document.getElementById("edit_ang_hi").value = ang_hi;
            document.getElementById("edit_ang_lo").value = ang_lo;
            document.getElementById('edit_step_id').value = step_id;

            const radioButtons_th_mode = document.getElementsByName("edit_th_mode");
            setRadioButton_value(radioButtons_th_mode, th_mode);

            const radioButtons_direction = document.getElementsByName("edit_direction");
            setRadioButton_value(radioButtons_direction, direction);

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
        //document.querySelector(".main-content").classList.remove("overlay-active");

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
    document.getElementById("add_step_id").value = nextStepId;
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
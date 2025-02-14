

function create_step() {

    document.getElementById('newstep').style.display = 'block';

    document.getElementById('rpm').value = 200;
    document.getElementById('th_tor').value = 0;
    document.getElementById('ds_tor').value = 0.3;
    document.getElementById('ds_speed').value =100;


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
    var elements = document.getElementsByName(name);  // 根據 name 屬性選擇元素
    if (elements.length > 0) {
        // 遍歷所有具有該 name 的元素
        elements.forEach(function(element) {
            if (element.disabled) {
                element.disabled = false;  // 啟用元素
                element.value = value;     // 設置元素的值
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
    const radios = document.getElementsByName("ds_mode");
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

    if (targetValue == 0) {
        targetTorItem.style.display = "block";
        enableElementById('tor_hi','0');
        enableElementById('tor_lo','0');
        enableElementById('ang_hi','0');
        enableElementById('ang_lo','0');
        enableElementById('rpm','200');
        enableElementById('th_tor','0');
        enableElementById('ds_tor','0.3');
        enableElementById('ds_speed','100');
        enableElementById('direction_CW','');
        enableElementById('direction_CCW','');
        enableElementById('downshift_ON','');
        enableElementById('downshift_OFF','');

        //控制欄位 顯示 / 隱藏
        document.getElementById("tor_hi_item").style.display= 'block';
        document.getElementById("tor_lo_item").style.display= 'block';
        document.getElementById("ang_hi_item").style.display= 'block';
        document.getElementById("ang_lo_item").style.display= 'block';
        document.getElementById("rpm_item").style.display= 'block';
        document.getElementById("direction_item").style.display= 'block';
        document.getElementById("ds_mode_item").style.display = 'block';
        document.getElementById("th_tor_item").style.display = 'block';
        document.getElementById("ds_tor_item").style.display = 'block';
        document.getElementById("ds_speed_item").style.display = 'block';
        document.getElementById("ds_mode_item").style.display = 'block';

    } else if (targetValue == 1) {
        targetAngItem.style.display = "block";

        enableElementById('tor_hi', ''); 
        enableElementById('tor_lo', ''); 
        enableElementById('ang_hi', ''); 
        enableElementById('ang_lo', ''); 
        enableElementById('direction_CW','');
        enableElementById('direction_CCW','');
        disableElementById('rpm','200');
        disableElementById('downshift_ON','');
        disableElementById('downshift_OFF','');
        disableElementById('th_tor','0');
        disableElementById('ds_tor','0.3');
        disableElementById('ds_speed','100');

        //控制欄位 顯示 / 隱藏
        document.getElementById("tor_hi_item").style.display= 'block';
        document.getElementById("tor_lo_item").style.display= 'block';
        document.getElementById("ang_hi_item").style.display= 'block';
        document.getElementById("ang_lo_item").style.display= 'block';
        document.getElementById("rpm_item").style.display= 'none';
        document.getElementById("direction_item").style.display= 'block';
        document.getElementById("ds_mode_item").style.display = 'none';
        document.getElementById("th_tor_item").style.display = 'none';
        document.getElementById("ds_tor_item").style.display = 'none';
        document.getElementById("ds_speed_item").style.display = 'none';
        document.getElementById("ds_mode_item").style.display = 'none';

         

    } else if (targetValue == 2) {
        targetDelayItem.style.display = "block";
        disableElementById('tor_hi','0');
        disableElementById('tor_lo','0');
        disableElementById('ang_hi','0');
        disableElementById('ang_lo','0');
        disableElementById('rpm','200');
        disableElementById('th_tor','0');
        disableElementById('ds_tor','0.3');
        disableElementById('ds_speed','100');
        disableElementById('direction_CW','');
        disableElementById('direction_CCW','');
        disableElementById('downshift_ON','');
        disableElementById('downshift_OFF','');


        //控制欄位 顯示 / 隱藏
        document.getElementById("tor_hi_item").style.display= 'none';
        document.getElementById("tor_lo_item").style.display= 'none';
        document.getElementById("ang_hi_item").style.display= 'none';
        document.getElementById("ang_lo_item").style.display= 'none';
        document.getElementById("rpm_item").style.display= 'none';
        document.getElementById("direction_item").style.display= 'none';
        document.getElementById("ds_mode_item").style.display = 'none';
        document.getElementById("th_tor_item").style.display = 'none';
        document.getElementById("ds_tor_item").style.display = 'none';
        document.getElementById("ds_speed_item").style.display = 'none';
        document.getElementById("ds_mode_item").style.display = 'none';


     

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
    var tor_hi = document.getElementById("edit_tor_hi").value; 
    var tor_lo = document.getElementById("edit_tor_lo").value;
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
        enableElementByName("edit_ds_mode");


        //欄位 控制
        document.getElementById("edit_tor_hi_item").style.display= 'block';
        document.getElementById("edit_tor_lo_item").style.display= 'block';
        document.getElementById("edit_ang_hi_item").style.display= 'block';
        document.getElementById("edit_ang_lo_item").style.display= 'block';
        document.getElementById("edit_rpm_item").style.display= 'block';
        document.getElementById("edit_direction_item").style.display= 'block';
        document.getElementById("edit_ds_mode_item").style.display = 'block';
        document.getElementById("edit_th_tor_item").style.display = 'block';
        document.getElementById("edit_ds_tor_item").style.display = 'block';
        document.getElementById("edit_ds_speed_item").style.display = 'block';
        document.getElementById("edit_ds_mode_item").style.display = 'block';

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
        disableElementById('edit_rpm',rpm);
        disableElementsByName("edit_ds_mode");
        enableElementByName("edit_direction");


        //欄位 控制
        document.getElementById("edit_tor_hi_item").style.display= 'block';
        document.getElementById("edit_tor_lo_item").style.display= 'block';
        document.getElementById("edit_ang_hi_item").style.display= 'block';
        document.getElementById("edit_ang_lo_item").style.display= 'block';
        document.getElementById("edit_rpm_item").style.display= 'none';
        document.getElementById("edit_direction_item").style.display= 'block';
        document.getElementById("edit_ds_mode_item").style.display = 'none';
        document.getElementById("edit_th_tor_item").style.display = 'none';
        document.getElementById("edit_ds_tor_item").style.display = 'none';
        document.getElementById("edit_ds_speed_item").style.display = 'none';
        document.getElementById("edit_ds_mode_item").style.display = 'none';


        
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
        disableElementsByName("edit_ds_mode");
        disableElementsByName("edit_direction");

        //欄位 控制
        document.getElementById("edit_tor_hi_item").style.display= 'none';
        document.getElementById("edit_tor_lo_item").style.display= 'none';
        document.getElementById("edit_ang_hi_item").style.display= 'none';
        document.getElementById("edit_ang_lo_item").style.display= 'none';
        document.getElementById("edit_rpm_item").style.display= 'none';
        document.getElementById("edit_direction_item").style.display= 'none';
        document.getElementById("edit_ds_mode_item").style.display = 'none';
        document.getElementById("edit_th_tor_item").style.display = 'none';
        document.getElementById("edit_ds_tor_item").style.display = 'none';
        document.getElementById("edit_ds_speed_item").style.display = 'none';
        document.getElementById("edit_ds_mode_item").style.display = 'none';




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

        document.getElementById('spinner').style.display = 'block';
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






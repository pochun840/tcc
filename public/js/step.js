

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






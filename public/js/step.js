
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(event) {
        if (event.target && event.target.id === 'copyButton') {
            copy_step_by_id();
        }
    });
});

function copy_step(stepid){
    document.getElementById('copystep').style.display = 'block';   
    copy_step_by_id();

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






function delete_jobid(jobid) {
    if (jobid) {

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
            url: "?url=Jobs/delete_jobid",
            method: "POST",
            data: { jobid: jobid },
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
                // 這裡可以處理 AJAX 請求失敗的情況
            }
        });
    }
}


var oldjobname ='';
var old_jobid  = '';
function cound_job(argument){

    var table = document.getElementById('job_table');
    var selectedRow = table.querySelector('.selected');
    var jobid  = selectedRow ? selectedRow.cells[0].innerText : null;
    oldjobname = selectedRow ? selectedRow.cells[1].innerText : null;
    old_jobid  = selectedRow ? selectedRow.cells[0].innerText : null;
    if(argument == 'del' && jobid != null){
        document.querySelector(".main-content").classList.add("overlay-active");
        delete_jobid(jobid);
    }

    if(argument =="edit" && jobid != null){
        document.querySelector(".main-content").classList.add("overlay-active");
        edit_job(jobid);
    }

    if(argument =="new"){
        //alert('111111111');
        document.querySelector(".main-content").classList.add("overlay-active");
        create_job();
    }

    if(argument =="copy" && jobid != null){
        document.querySelector(".main-content").classList.add("overlay-active");
        copy_job(jobid);
    }

}


function readFromLocalStorage(key) {
    return localStorage.getItem(key);
}

function create_job() {
    
  

    //帶入預設值
    document.getElementById('newjob').style.display = 'block';
    document.getElementById('rev_speed').value = 550;
    document.getElementById('rev_force').value = 50;
    document.getElementById('rev_direction_CCW').checked = true;
    document.getElementById('job_ok').checked = true;
    document.getElementById('job_ok_stop_off').checked = true;
}

function copy_job(jobid){
    document.getElementById('copyjob').style.display = 'block';
    copy_data(jobid);
}

function updatejob(){

    var jobid      = document.getElementById("edit_jobid").value;
    var jobname    = document.getElementById("edit_jobname").value;
    var speedvalue   = document.getElementById("edit_rev_speed").value;
    var forcevalue = document.getElementById("edit_rev_force").value;
    var directionValue = document.querySelector('input[name="edit_direction"]:checked').value;
    var jobokValue = document.querySelector('input[name="edit_job_ok"]:checked').value;
    var stopjobValue = document.querySelector('input[name="edit_job_ok_stop"]:checked').value;

    //驗證
    let check = input_check_editjob();
    
    if(check) {

        document.getElementById('spinner').style.display = 'block';


        $.ajax({
            url: "?url=Jobs/update_job",
            method: "POST",
            data: { 
                jobid: jobid,
                jobname: jobname,
                speedvalue: speedvalue,
                forcevalue: forcevalue,
                directionValue: directionValue,
                jobokValue:jobokValue,
                stopjobValue:stopjobValue

            },
            success: function(response) {   
                var responseData = JSON.parse(response);
                
                // 延遲 1000 毫秒後隱藏加載動畫，並在隱藏後顯示 alertify 彈跳視窗
                setTimeout(function() {
                    // 隱藏加載動畫
                    document.getElementById('spinner').style.display = 'none';

                    // 顯示 alertify 彈跳視窗
                    alertify.alert(responseData.res_type, responseData.res_msg, function() {
                        // 在這裡可以進行其他回調操作，如果需要的話
                    });

                    // 儲存數據到 localStorage
                    localStorage.setItem('jobid', jobid);
                    localStorage.setItem('jobname', jobname);
                    localStorage.setItem('rev_speed', speedvalue);
                    localStorage.setItem('rev_force', forcevalue);
                    localStorage.setItem('direction', directionValue);

                    // 在 3 秒後自動關閉 alertify 彈跳視窗並刷新頁面
                    setTimeout(function() {
                        alertify.closeAll();  // 關閉所有開啟的 alertify 彈跳視窗
                        // 刷新頁面
                        history.go(0);
                    }, 3000); // 3000 毫秒 = 3 秒
                }, 1000); // 延遲 1000 毫秒 
            },
            error: function(xhr, status, error) {
                
            }
        });

    }
   
}

function edit_job(jobid) {
    if(jobid){
        $.ajax({
            url: "?url=Jobs/search_job",
            method: "POST",
            data:{ 
                jobid: jobid
            },
            success: function(response) {
                var responseJSON = JSON.stringify(response);
                var cleanString = responseJSON.replace(/Array|\\n/g, '');
                var cleanString = cleanString.substring(2, cleanString.length - 2);
                var [, jobid] = cleanString.match(/\[job_id]\s*=>\s*([^ ]+)/) || [, null];
                var [, jobname] = cleanString.match(/\[job_name]\s*=>\s*([^ ]+)/) || [, null];
                var [, rev_direction] = cleanString.match(/\[rev_direction]\s*=>\s*([^ ]+)/) || [, null];
                var [, rev_force] = cleanString.match(/\[rev_force]\s*=>\s*([^ ]+)/) || [, null];
                var [, rev_speed] = cleanString.match(/\[rev_speed]\s*=>\s*([^ ]+)/) || [, null];
                var [, job_ok] = cleanString.match(/\[job_ok]\s*=>\s*([^ ]+)/) || [, null];
                var [, job_ok_stop] = cleanString.match(/\[job_ok_stop]\s*=>\s*([^ ]+)/) || [, null];
          
                document.getElementById('editjob').style.display = 'block';


                document.getElementById("edit_jobid").value = jobid;
                document.getElementById("edit_jobname").value = jobname;

                document.getElementById("edit_rev_speed").value = rev_speed;
                document.getElementById("edit_rev_force").value = rev_force;

                var radioButtons = document.getElementsByName("edit_direction");
                setRadioButtonValue(radioButtons, rev_direction);

                var radioButtons_job = document.getElementsByName("edit_job_ok");
                setRadioButtonValue(radioButtons_job, job_ok);

                var radioButtons_stop_job = document.getElementsByName("edit_job_ok_stop");
                setRadioButtonValue(radioButtons_stop_job, job_ok_stop);
              
              
            },
            error: function(xhr, status, error) {
                
            }
        });
    }   
}


function validateInput(element, pattern, min, max) {
    const value = element.value.trim();
    const numericValue = parseFloat(value);
    let isValid = true;

    // 清除先前錯誤樣式
    element.classList.remove("is-invalid");

    // 空值檢查
    if (!value) {
        isValid = false;
    }
    // 格式檢查
    else if (pattern && !pattern.test(value)) {
        isValid = false;
    }
    // 範圍檢查（只檢查數值欄位）
    else if (!isNaN(numericValue)) {
        if (min !== null && !isNaN(min) && numericValue < parseFloat(min)) {
            isValid = false;
        }
        if (max !== null && !isNaN(max) && numericValue > parseFloat(max)) {
            isValid = false;
        }
    }

    // 套用錯誤樣式
    if (!isValid) {
        element.classList.add("is-invalid");
    }

    return isValid;
}


function copy_data(jobid){
    var new_jobid = document.getElementById("to_job_id").value;
    var new_jobname = document.getElementById("to_job_name").value;

    document.getElementById("from_job_id").value = old_jobid;
    document.getElementById("from_job_name").value = oldjobname;
    document.getElementById("to_job_id").value = new_jobid;

}


function copy_job_by_id(jobid){

    var new_jobid = document.getElementById("to_job_id").value;
    var new_jobname = document.getElementById("to_job_name").value;

    document.getElementById("from_job_id").value = old_jobid;
    document.getElementById("from_job_name").value = oldjobname;
    document.getElementById("to_job_id").value = new_jobid;

    var language = getCookie('language');
    if(language == "zh-cn"){
        var text_info ='你确定吗？';
    }else if(language == "zh-tw"){
        var text_info ='你確定嗎 ?';
    }else{
        var text_info ='Are you sure ?';
    }


    if(new_jobid){


        $.ajax({
            url: "?url=Jobs/check_job_type",
            method: "POST",
            data:{ 
                new_jobid: new_jobid,

            },
            success: function(response) {
                alertify.confirm(text_info , function (result) {
                if (result) {
                    document.getElementById('spinner').style.display = 'block';
                    $.ajax({
                        url: "?url=Jobs/copy_job_data",
                        method: "POST",
                        data:{ 
                            old_jobid: old_jobid,
                            old_jobname: oldjobname,
                            new_jobid: new_jobid,
                            new_jobname: new_jobname

                        },
                        success: function(response) {
                            var responseData = JSON.parse(response);
                            // 延遲 1000 毫秒後隱藏加載動畫，並在隱藏後顯示 alertify 彈跳視窗
                            setTimeout(function() {
                                // 隱藏 'copyjob' 和 'spinner' 加載動畫
                                document.getElementById('copyjob').style.display = 'none';
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
                        },
                        error: function(xhr, status, error) {
                            
                        }
                    });
                } else {
                    alertify.error('Cancelled');
                }
                });
            },
            error: function(xhr, status, error) {
                
            }
        });
        
    }
}

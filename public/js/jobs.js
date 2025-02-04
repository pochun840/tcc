
function delete_jobid(jobid) {
    if (jobid) {
        document.getElementById('spinner').style.display = 'block';
        $.ajax({
            url: "?url=Jobs/delete_jobid",
            method: "POST",
            data: { jobid: jobid },
            success: function(response) {
                var responseData = JSON.parse(response);
                //延遲 1000 毫秒後隱藏加載動畫，並在隐藏後顯示 alertify 彈跳視窗
                setTimeout(function() {
                    //隱藏加載動畫
                    document.getElementById('spinner').style.display = 'none';

                    //顯示 alertify 彈跳視窗
                    alertify.alert(responseData.res_type, responseData.res_msg, function() {
                        history.go(0);  //刷新頁面
                    });
                }, 1000); //延遲 1000 毫秒  
            },
            error: function(xhr, status, error) {
                
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
    
    document.getElementById('rev_speed').value = 200;
    document.getElementById('rev_force').value = 50;
    document.getElementById('rev_direction_CCW').checked = true;
    document.getElementById('job_ok').checked = true;
    document.getElementById('stop_job_ok_off').checked = true;
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
    var stopjobValue = document.querySelector('input[name="edit_stop_job_ok"]:checked').value;

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
                
                //延遲 1000 毫秒後隱藏加載動畫，並在隐藏後顯示 alertify 彈跳視窗
                setTimeout(function() {
                    //隱藏加載動畫
                    document.getElementById('spinner').style.display = 'none';

                    //顯示 alertify 彈跳視窗
                    alertify.alert(responseData.res_type, responseData.res_msg, function() {
                        // 將數據存储到 localStorage
                        localStorage.setItem('jobid', jobid);
                        localStorage.setItem('jobname', jobname);
                        localStorage.setItem('rev_speed', speedvalue);
                        localStorage.setItem('rev_force', forcevalue);
                        localStorage.setItem('direction', directionValue);

                        //刷新頁面
                        history.go(0);
                    });
                }, 1000); //延遲 1000 毫秒 
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
                var [, stop_job_ok] = cleanString.match(/\[stop_job_ok]\s*=>\s*([^ ]+)/) || [, null];
          
                document.getElementById('editjob').style.display = 'block';


                document.getElementById("edit_jobid").value = jobid;
                document.getElementById("edit_jobname").value = jobname;

                document.getElementById("edit_rev_speed").value = rev_speed;
                document.getElementById("edit_rev_force").value = rev_force;

                var radioButtons = document.getElementsByName("edit_direction");
                setRadioButtonValue(radioButtons, rev_direction);

                var radioButtons_job = document.getElementsByName("edit_job_ok");
                setRadioButtonValue(radioButtons_job, job_ok);

                var radioButtons_stop_job = document.getElementsByName("edit_stop_job_ok");
                setRadioButtonValue(radioButtons_stop_job, stop_job_ok);
              
              
            },
            error: function(xhr, status, error) {
                
            }
        });
    }   
}

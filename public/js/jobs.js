
function delete_jobid(jobid) {
    if (jobid) {
        $.ajax({
            url: "?url=Jobs/delete_jobid",
            method: "POST",
            data: { jobid: jobid },
            success: function(response) {
                console.log(response);
                var responseData = JSON.parse(response);
                alertify.alert(responseData.res_type, responseData.res_msg, function() {
                    history.go(0);
                }); 
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
        delete_jobid(jobid);
    }

    if(argument =="edit" && jobid != null){
        edit_job(jobid);
    }

    if(argument =="new"){
        create_job();
    }

    if(argument =="copy" && jobid != null){
        copy_job(jobid);
    }

}

function readFromLocalStorage(key) {
    return localStorage.getItem(key);
}

function create_job() {
    document.getElementById('newjob').style.display = 'block';
    document.getElementById('unscrew_RPM').value = 1;
    document.getElementById('unscre_power').value = 1;
    document.getElementById('unfasten_direction_CCW').checked = true;
    savejob();
}

function copy_job(jobid){
    document.getElementById('copyjob').style.display = 'block';
    copy_job_by_id(jobid);
}

function updatejob(){

    var jobid      = document.getElementById("edit_jobid").value;
    var jobname    = document.getElementById("edit_jobname").value;
    var rpmvalue   = document.getElementById("edit_unscrew_rpm").value;
    var powervalue = document.getElementById("edit_unscrew_power").value;
    var directionValue = document.querySelector('input[name="edit_direction"]:checked').value;

    if(jobid) {
        $.ajax({
            url: "?url=Jobs/update_job",
            method: "POST",
            data: { 
                jobid: jobid,
                jobname: jobname,
                rpmvalue: rpmvalue,
                powervalue: powervalue,
                directionValue: directionValue
            },
            success: function(response) {   
                var responseData = JSON.parse(response);
                alertify.alert(responseData.res_type, responseData.res_msg, function() {
                    localStorage.setItem('jobid', jobid);
                    localStorage.setItem('jobname', jobname);
                    localStorage.setItem('unscrew_rpm', rpmvalue);
                    localStorage.setItem('unscre_power', powervalue);
                    localStorage.setItem('direction', directionValue);
                    history.go(0);
                });

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
                var [, unscrew_direction] = cleanString.match(/\[unscrew_direction]\s*=>\s*([^ ]+)/) || [, null];
                var [, unscrew_power] = cleanString.match(/\[unscrew_power]\s*=>\s*([^ ]+)/) || [, null];
                var [, unscrew_rpm] = cleanString.match(/\[unscrew_rpm]\s*=>\s*([^ ]+)/) || [, null];
          
                document.getElementById('editjob').style.display = 'block';


                document.getElementById("edit_jobid").value = jobid;
                document.getElementById("edit_jobname").value = jobname;

                document.getElementById("edit_unscrew_rpm").value = unscrew_rpm;
                document.getElementById("edit_unscrew_power").value = unscrew_power;

                var radioButtons = document.getElementsByName("edit_direction");
                setRadioButtonValue(radioButtons, unscrew_direction);
              
            },
            error: function(xhr, status, error) {
                
            }
        });
    }   
}

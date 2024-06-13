<?php require APPROOT . 'views/inc/header.php'; ?>
<link rel="stylesheet" type="text/css" href="../public/css/tcc_jobs.css">
<body>

<div class="container-ms">
    <div class="w3-text-white w3-center">
        <table class="no-border">
            <tr id="header">
                <td width="100%">
                    <h3>Job Management</h3>
                </td>
                <td>
                    <img src="./img/btn_home.png" style="margin-right: 10px" onclick="window.location.href = '?url=In';">
                </td>
            </tr>
        </table>
    </div>

    <div class="main-content">
        <div class="center-content">
            <div class="table-container">
                <div class="scrollbar" id="style-jobtable">
                    <div class="scrollbar-force-overflow">
                        <table id="job_table"  class="table w3-table-all w3-hoverable">
                            <thead id="header-table">
                                <tr class="w3-dark-grey">
                                    <th>Job ID</th>
                                    <th>Job Name</th>
                                    <th>Unscrew Direction</th>
                                    <th>Unscrew RPM</th>
                                    <th>Unscrew Power</th>
                                    <th>Total Seq</th>
                                    <th>Add Seq</th>
                                </tr>
                            </thead>

                            <tbody style="font-size: 1.8vmin;text-align: center;">
									<?php foreach($data['jobs'] as $key =>$val){?>
										<tr >
											<td id='job_id' ><?php echo $val['job_id'];?></td>
											<td><?php echo $val['job_name'];?></td>
											<td><?php echo $data['direction'][$val['unscrew_direction']];?></td>
											<td><?php echo $val['unscrew_rpm'];?></td>
											<td><?php echo $val['unscrew_power'];?></td>
											<td><?php echo $val['total_seq'];?></td>
                                            <?php $url ='?url=Sequences/index/'.$val['job_id'];?>
                                            <td><img id="Add_Seq" src="./img/btn_plus.png" onclick="location.href='<?php echo $url;?>'">

                                		</tr>

									<?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div id="TotalPage">
            <div id="TotalJobTable">
                <div style="color:black; float: right; margin: 2px">Total Jobs :
                    <label id="RecordCnt" name="RecordCnt" type="text" style="margin-right: 20px"><?php echo count($data['jobs']);?></label>
                </div>
            </div>
        </div>

        <div class="buttonbox">
            <input id="S3" name="Job_Manager_Submit" type="button" value="New" tabindex="1"   onclick="cound_job('new')">
            <input id="S6" name="Job_Manager_Submit" type="button" value="Edit" tabindex="1"  onclick="cound_job('edit')">
            <input id="S5" name="Job_Manager_Submit" type="button" value="Copy" tabindex="1"  onclick="cound_job('copy')">
            <input id="S4" name="Job_Manager_Submit" type="button" value="Delete" tabindex="1" onclick="cound_job('del')">
        </div>
    </div>

    <!-- Add New Job -->
    <div id="newjob" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w3-animate-zoom" style="width: 70%">
                <header class="w3-container modal-header">
                    <span onclick="hideElementById('newjob');"
                        class="w3-button w3-red w3-display-topright" style="width: 50px; margin: 3px;">&times;</span>
                    <h3 id='modal_title'>New Job</h3>
                </header>

                <div class="modal-body">
                    <form id="new_job_form" style="padding-left: 5%">
                        <div class="row">
                            <div for="job-id" class="col-6 t1">Job ID :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="job_id" maxlength=""  value='<?php echo $data['jobint'];?>'>
                            </div>
                        </div>
                        <div class="row">
                            <div for="job-name" class="col-6 t1">Job Name :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="job_name" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="Unscrew-Direction" class="col-6 t1">Unscrew Direction :</div>
                            <div class="col t2" >
            			      	<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="direction" id="unfasten_direction_CW" value="0">
            					  <label class="form-check-label" for="unfasten_direction_CW">CW</label>
            					</div>
            					<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="direction" id="unfasten_direction_CCW" value="1">
            					  <label class="form-check-label" for="unfasten_direction_CCW">CCW</label>
            					</div>
                            </div>
                        </div>
                        <div class="row">
                            <div for="unscrew-RPM" class="col-6 t1">Unscrew RPM(1=10%) :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="unscrew_RPM" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="unscrew-power" class="col-6 t1">Unscrew Power(1=10%):</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="unscre_power" maxlength="">
                            </div>
                        </div>

                    </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <button id="" class="button-modal" onclick="savejob()">Save</button>
                    <button id="" class="button-modal" onclick="hideElementById('newjob');" class="closebtn">Close</button>
                </div>
            </div>
        </div>
    </div>


    <!-- edit Job -->
    <div id="editjob" class="modal" >
    <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w3-animate-zoom" style="width: 70%">
                <header class="w3-container modal-header">
                    <span onclick="hideElementById('editjob');"
                        class="w3-button w3-red w3-display-topright" style="width: 50px; margin: 3px;">&times;</span>
                    <h3 id='modal_title'>Edit Job</h3>
                </header>

                <div class="modal-body">
                    <form id="new_job_form" style="padding-left: 5%">
                        <div class="row">
                            <div for="job-id" class="col-6 t1">Job ID :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="edit_jobid" maxlength=""  value=''>
                            </div>
                        </div>
                        <div class="row">
                            <div for="job-name" class="col-6 t1">Job Name :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="edit_jobname" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="Unscrew-Direction" class="col-6 t1">Unscrew Direction :</div>
                            <div class="col t2" >
            			      	<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_direction" id="direction_CW" value="0">
            					  <label class="form-check-label" for="unfasten_direction_CW">CW</label>
            					</div>
            					<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio"  name="edit_direction" id="direction_CCW" value="1">
            					  <label class="form-check-label" for="unfasten_direction_CCW">CCW</label>
            					</div>
                            </div>
                        </div>
                        <div class="row">
                            <div for="unscrew-RPM" class="col-6 t1">Unscrew RPM(1=10%) :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="edit_unscrew_rpm" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="unscrew-power" class="col-6 t1">Unscrew Power(1=10%):</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="edit_unscrew_power" maxlength="">
                            </div>
                        </div>

                    </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <button id="" class="button-modal" onclick="updatejob();">Save</button>
                    <button id="" class="button-modal" onclick="hideElementById('editjob');" class="closebtn">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Copy Job -->
    <div id="copyjob" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w3-animate-zoom" style="width: 60%">
                <header class="w3-container modal-header">
                    <span onclick="hideElementById('copyjob');"
                        class="w3-button w3-red w3-display-topright" style="width: 50px; margin: 3px;">&times;</span>
                    <h3 id='modal_title'>Copy Job</h3>
                </header>

                <div class="modal-body">
                    <form id="new_job_form">
        	            <label for="from_job_id" class="col col-form-label" style="font-weight: bold">Copy From</label>
        	            <div style="padding-left: 10%;">
        		            <div class="row">
        				        <label for="from_job_id" class="t1 col-4 col-form-label">Job ID :</label>
        				        <div class="col-5 t2 ">
        				            <input type="number" class="form-control" id="from_job_id" disabled>
        				        </div>
        				    </div>
        				    <div class="row">
        				        <label for="from_job_name" class="t1 col-4 col-form-label">Job Name :</label>
        				        <div class="t2 col-5">
        				            <input type="text" class="form-control" id="from_job_name" disabled>
        				        </div>
        				    </div>
        			    </div>

        			    <label for="from_job_id" class="col col-form-label" style="font-weight: bold">Copy To</label>
        			    <div style="padding-left: 10%;">
        				    <div class="row">
        				        <label for="to_job_id" class="t1 col-4 col-form-label">Job ID</label>
        				        <div class="t2 col-5">
        				            <input type="number" class="form-control" id="to_job_id">
        				        </div>
        				    </div>
        				    <div class="row">
        				        <label for="to_job_name" class="t1 col-4 col-form-label">Job Name</label>
        				        <div class="t2 col-5">
        				            <input type="text" class="form-control" id="to_job_name">
        				        </div>
        				    </div>
        			    </div>
        			  </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <button id="" class="button-modal"  onclick="copy_job_by_id();">Save</button>
                    <button id="" class="button-modal" onclick="hideElementById('copyjob');"  class="closebtn">Close</button>
                </div>
            </div>
        </div>
    </div>


</div>

<script>
$(document).ready(function () {
    highlight_row('job_table');
});


// Get the modal
var modal = document.getElementById('newjob');

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}



</script>


</body>

</html>
<script>
    
function cound_job(argument){

 
    var table = $('#job_table').DataTable();
    var selectedRowData = table.row('.selected').data();
    var jobid = selectedRowData ? selectedRowData[0] : null;

    //移除
    var elementsToRemove = ["job_table_filter", "job_table_length", "job_table_info", "job_table_paginate"];
    removeElements(elementsToRemove);

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

var rows = document.getElementsByTagName("tr");
for (var i = 0; i < rows.length; i++) {
    (function(row) {
        var cells = row.getElementsByTagName("td");
        if (cells.length > 0) {
            cells[0].addEventListener("click", function() {
           
                var jobid = cells[0] ? (cells[0].textContent || cells[0].innerText) : null;
                var secondCellValue = cells[1] ? (cells[1].textContent || cells[1].innerText) : null;
                var thirdCellValue = cells[2] ? (cells[2].textContent || cells[2].innerText) : null;
                var rpmvalue = cells[3] ? (cells[3].textContent || cells[3].innerText) : null;
                var powervalue = cells[4] ? (cells[4].textContent || cells[4].innerText) : null;
        
                localStorage.setItem("jobid", jobid );
                localStorage.setItem("jobname", secondCellValue);
                localStorage.setItem("direction", thirdCellValue);
                localStorage.setItem("powervalue", powervalue);
                localStorage.setItem("rpmvalue", rpmvalue);

            });
        }
    })(rows[i]);
}


//讀取localStorage
function readFromLocalStorage(key) {
    return localStorage.getItem(key);
}

function create_job() {
    document.getElementById('newjob').style.display = 'block';
    savejob();
}

function savejob() {

    var jobidnew = '<?php echo $data['jobint']?>';

    var jobname_val      = document.getElementById("job_name").value;
    var unscrew_rpm_val  = document.getElementById("unscrew_RPM").value;
    var unscre_power_val = document.getElementById("unscre_power").value;
    
    var directionElement = document.querySelector('input[name="direction"]:checked');
    var direction_val = directionElement ? directionElement.value : null;

    if (jobname_val && unscrew_rpm_val && unscre_power_val && direction_val) {
        $.ajax({
            url: "?url=Jobs/create_job",
            method: "POST",
            data: { 
                jobidnew: jobidnew,
                jobname_val: jobname_val,
                unscrew_rpm_val: unscrew_rpm_val,
                unscre_power_val: unscre_power_val,
                direction_val: direction_val
            },
            success: function(response) {
                console.log(response);
                alert(response);
                history.go(0);
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed:", status, error);
            }
        });
    }
}

function delete_jobid(jobid) {
    if (jobid) {
        $.ajax({
            url: "?url=Jobs/delete_jobid",
            method: "POST",
            data: { jobid: jobid },
            success: function(response) {
                console.log( response);
                alert(response);
                history.go(0);
            },
            error: function(xhr, status, error) {
                
            }
        });
    }
}

function edit_job(jobid) {
    var jobid = readFromLocalStorage("jobid");
    console.log(jobid);
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
                //console.log('eew');
            }
        });
    }
   
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

                localStorage.setItem('jobid', jobid);
                localStorage.setItem('jobname', jobname);
                localStorage.setItem('unscrew_rpm', rpmvalue);
                localStorage.setItem('unscre_power', powervalue);
                localStorage.setItem('direction', directionValue);

                console.log( response);
                history.go(0);
            },
            error: function(xhr, status, error) {
                
            }
        });

    }
   
}


function copy_job(jobid){
    
    document.getElementById('copyjob').style.display = 'block';
    copy_job_by_id(jobid);
}

function copy_job_by_id(jobid){

    var old_jobid = readFromLocalStorage("jobid");
    var old_jobname = readFromLocalStorage("jobname");
    var new_jobid = '<?php echo $data['jobint']?>';

    var new_jobname = document.getElementById("to_job_name").value;

    document.getElementById("from_job_id").value = old_jobid;
    document.getElementById("from_job_name").value = old_jobname;
    document.getElementById("to_job_id").value = new_jobid;


    if(new_jobname){
        $.ajax({
            url: "?url=Jobs/copy_job",
            method: "POST",
            data: { 
                old_jobid: old_jobid,
                old_jobname: old_jobname,
                new_jobid: new_jobid,
                new_jobname: new_jobname
            },
            success: function(response) {
                console.log(response);
                alert(response);
                history.go(0);
            },
            error: function(xhr, status, error) {
                
            }
        });

    }
}

</script>

<link rel="stylesheet" href="<?php echo URLROOT; ?>css/tcc_jobs_m.css" type="text/css">
<style>
.form-control{
    width: 100px;
    display: initial!important;
}

.form-control.is-invalid{
    padding-right:inherit!important;
}
.is-invalid~.invalid-feedback{
    display: inline!important;
}

.main-content.overlay-active {
  filter: grayscale(100%); /* 完全灰化 */
  pointer-events: none; /* 禁止點擊 */
  opacity: 0.3; /* 降低不透明度 */
}

</style>
<div class="container-ms">
    <div class="w3-text-white w3-center">
        <table class="no-border">
            <tr id="header">
                <td width="100%"><h3><?php echo $text['job_management'];?></h3></td>
                <td><img src="./img/btn_home.png" style="margin-right: 10px" onclick="back()"></td>
            </tr>
        </table>
    </div>

    <div class="main-content">
        <div class="center-content">
            <div class="table-container">
                <div class="scrollbar" id="style-jobtable">
                    <div class="force-overflow">
                        <table id="job_table" class="table w3-table-all w3-hoverable">
                            <thead id="header-table">
                                <tr class="w3-dark-grey" style="font-size: 2.5vmin">
                                    <th><?php echo $text['job_id'];?></th>
                                    <th><?php echo $text['job_name'];?></th>
                                    <th><?php echo $text['rev_direction'];?></th>
                                    <th><?php echo $text['rev_speed'];?></th>
                                    <th><?php echo $text['rev_force'];?></th>
                                    <th><?php echo $text['total_seq'];?></th>
                                    <th><?php echo $text['add_seq'];?></th>
                                </tr>
                            </thead>

                            <tbody style="font-size: 2vmin;text-align: center;">
                                <?php foreach($data['jobs'] as $key =>$val){?>
										<tr >
											<td id='job_id' ><?php echo $val['job_id'];?></td>
											<td><?php echo $val['job_name'];?></td>
											<td><?php echo $text[$data['direction'][$val['rev_direction']]];?></td>
											<td><?php echo $val['rev_speed'];?></td>
											<td><?php echo $val['rev_force'];?></td>
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
                <div style="color:black; float: right; margin: 2px"><?php echo $text['total_job'];?> :
                    <label id="RecordCnt" name="RecordCnt" type="text" style="margin-right: 20px"><?php echo count($data['jobs']);?></label>
                </div>
            </div>
        </div>

        <div class="buttonbox">
        <?php $status = count($data['jobs']) >=  50 ? 'disabled' : ''; ?>
            <input id="S3" name="Job_Manager_Submit" type="button" value="<?php echo $text['New'];?>" tabindex="1"   onclick="cound_job('new')" <?php echo $status;?> >
            <input id="S6" name="Job_Manager_Submit" type="button" value="<?php echo $text['Edit'];?>" tabindex="1"  onclick="cound_job('edit')">
            <input id="S5" name="Job_Manager_Submit" type="button" value="<?php echo $text['Copy'];?>" tabindex="1"  onclick="cound_job('copy')" <?php echo $status;?> >
            <input id="S4" name="Job_Manager_Submit" type="button" value="<?php echo $text['Delete'];?>" tabindex="1" onclick="cound_job('del')">
        </div>
    </div>

    <!-- Add New Job -->
    <div id="newjob" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w3-animate-zoom" style="width: auto">
                <header class="w3-container modal-header">
                    <span onclick="closejob('newjob')"
                        class="w3-button w3-red w3-display-topright" style="width: 50px; height: 43px; margin: 3px; font-size: 4.5vmin">&times;</span>
                    <h3 id='modal_title'><?php echo $text['new_job'];?></h3>
                </header>

                <div class="modal-body">
                    <form id="new_job_form" style="padding-left: 5%">
                        <div class="row">
                            <div for="job-id" class="col-6 t1"><?php echo $text['job_id'];?>:</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="job_id" maxlength="" value='<?php echo $data['next_job_id'];?>' >
                            </div>
                        </div>
                        <div class="row">
                            <div for="job-name" class="col-6 t1"><?php echo $text['job_name'];?>:</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="job_name" maxlength="" value ='<?php echo "JOB"."-".$data['next_job_id'];?>'>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div for="Unscrew-Direction" class="col-6 t1"><?php echo $text['job_ok'];?> :</div>
                            <div class="col t2" >

                                <div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="job_ok" id="job_off" value="0" >
            					  <label class="form-check-label" for="job_off"> <?php  echo $text['OFF_text']; ?></label>
            					</div>

            			      	<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="job_ok" id="job_ok" value="1">
            					  <label class="form-check-label" for="job_ok"><?php  echo $text['ON_text']; ?></label>  
            					</div>
                            </div>
                        </div>

                        <div class="row">
                            <div for="Unscrew-Direction" class="col-6 t1"><?php echo $text['JOB_COMPLETED'];?> :</div>
                            <div class="col t2" >

                                <div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="stop_job_ok" id="stop_job_ok_off" value="0" >
            					  <label class="form-check-label" for="job_off"> <?php  echo $text['OFF_text']; ?></label>
            					</div>

            			      	<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="stop_job_ok" id="stop_job_ok_ok" value="1">
            					  <label class="form-check-label" for="job_ok"><?php  echo $text['ON_text']; ?></label>
            					</div>
                            </div>
                        </div>

                        <div class="row">
                            <div for="reverse-Direction" class="col-6 t1"><?php echo $text['rev_direction'];?>:</div>
                            <div class="col t2" >
            			      	<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="direction" id="rev_direction_CW" value="0">
            					  <label class="form-check-label" for="rev_direction_CW"><?php echo $text['CW']; ?></label>
            					</div>

            					<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="direction" id="rev_direction_CCW" value="1">
            					  <label class="form-check-label" for="rev_direction_CCW"><?php echo $text['CCW']; ?></label>
            					</div>

                                <div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="direction" id="rev_direction_disable" value="2">
            					  <label class="form-check-label" for="rev_direction_disable"> <?php  echo $text['Disable']; ?></label>
            					</div>

                            </div>
                        </div>
                        <div class="row">
                            <div for="reverse-RPM" class="col-6 t1"><?php echo $text['rev_speed'];?>(Max:1100):</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="rev_speed" maxlength=""  >
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div for="reverse-power" class="col-6 t1"><?php echo $text['rev_force'];?>:</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="rev_force" maxlength="">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <button id="" class="button-modal" onclick="savejob()"><?php echo $text['save'];?></button>
                    <button id="" class="button-modal" onclick="closejob('newjob')" class="closebtn"><?php echo $text['close'];?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- edit Job -->
    <div id="editjob" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w3-animate-zoom" style="width: auto">
                <header class="w3-container modal-header">
                    <span onclick="closejob('editjob')"
                        class="w3-button w3-red w3-display-topright" style="width: 50px; height: 43px; margin: 3px; font-size: 4.5vmin">&times;</span>
                    <h3 id='modal_title'><?php echo $text['edit_job'];?></h3>
                </header>

                <div class="modal-body">
                    <form id="new_job_form" style="padding-left: 5%">
                        <div class="row">
                            <div for="job-id" class="col-6 t1"><?php echo $text['job_id'];?>:</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="edit_jobid" maxlength="" value='<?php echo $data['jobint'];?>' style="max-width: 100px;" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="job-name" class="col-6 t1"><?php echo $text['job_name'];?>:</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="edit_jobname" maxlength="">
                            </div>
                        </div>

                        <div class="row">
                            <div for="Unscrew-Direction" class="col-6 t1"><?php echo $text['job_ok'];?> :</div>
                            <div class="col t2" >

                                <div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_job_ok" id="job_off" value="0" >
            					  <label class="form-check-label" for="job_off"> <?php  echo $text['OFF_text']; ?></label>
            					</div>

            			      	<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_job_ok" id="job_ok" value="1">
            					  <label class="form-check-label" for="job_ok"><?php  echo $text['ON_text']; ?></label>
            					</div>
                            </div>
                        </div>

                        <div class="row">
                            <div for="Unscrew-Direction" class="col-6 t1"><?php echo $text['JOB_COMPLETED'];?> :</div>
                            <div class="col t2" >

                                <div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_stop_job_ok" id="stop_job_ok_off" value="0" >
            					  <label class="form-check-label" for="job_off"> <?php  echo $text['OFF_text']; ?></label>
            					</div>

            			      	<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_stop_job_ok" id="stop_job_ok_ok" value="1">
            					  <label class="form-check-label" for="job_ok"><?php  echo $text['ON_text']; ?></label>
            					</div>
                            </div>
                        </div>

                        <div class="row">
                            <div for="Unscrew-Direction" class="col-6 t1"><?php echo $text['rev_direction'];?>:</div>
                            <div class="col t2" >
            			      	<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_direction" id="rev_direction_CW" value="0">
            					  <label class="form-check-label" for="rev_direction_CW"><?php echo $text['CW'];?></label>
            					</div>

            					<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_direction" id="rev_direction__CCW" value="1">
            					  <label class="form-check-label" for="rev_direction_CCW"><?php echo $text['CCW'];?></label>
            					</div>

                                <div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_direction" id="rev_direction_disable" value="2">
            					  <label class="form-check-label" for="rev_direction_disable"> <?php  echo $text['Disable']; ?></label>
            					</div>

                            </div>
                        </div>
                        <div class="row">
                            <div for="reverse-RPM" class="col-6 t1"><?php echo $text['rev_speed'];?>(Max:1100):</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="edit_rev_speed" maxlength="" required >
                            </div>
                        </div>
                        <div class="row">
                            <div for="reverse-power" class="col-6 t1"><?php echo $text['rev_force'];?>:</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="edit_rev_force" maxlength=""  >
                            </div>
                        </div>

                    </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <button id="" class="button-modal" onclick="updatejob();"><?php echo $text['save'];?></button>
                    <button id="" class="button-modal" onclick="closejob('editjob')" class="closebtn"><?php echo $text['close'];?></button>

                </div>
            </div>
        </div>
    </div>

    <!-- Copy Job -->
    <div id="copyjob" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w3-animate-zoom" style="width: auto">
                <header class="w3-container modal-header">
                    <span onclick="closejob('copyjob')"
                        class="w3-button w3-red w3-display-topright" style="width: 50px; height: 43px;font-size: 4.5vmin; margin: 3px">&times;</span>
                    <h3 id='modal_title'><?php echo $text['copy_job'];?></h3>
                </header>

                <div class="modal-body">
                    <form id="new_job_form">
        	            <label for="from_job_id" class="col col-form-label" style="font-weight: bold"><?php echo $text['copy_from'];?></label>
        	            <div style="padding-left: 10%;">
        		            <div class="row">
        				        <label for="from_job_id" class="t1 col-4 col-form-label"><?php echo $text['job_id'];?> :</label>
        				        <div class="col-5 t2 ">
        				            <input type="number" class="form-control" id="from_job_id" disabled>
        				        </div>
        				    </div>
        				    <div class="row">
        				        <label for="from_job_name" class="t1 col-4 col-form-label"><?php echo $text['job_name'];?> :</label>
        				        <div class="t2 col-5">
        				            <input type="text" class="form-control" id="from_job_name" disabled>
        				        </div>
        				    </div>
        			    </div>

        			    <label for="from_job_id" class="col col-form-label" style="font-weight: bold"><?php echo $text['copy_to'];?></label>
        			    <div style="padding-left: 10%;">
        				    <div class="row">
        				        <label for="to_job_id" class="t1 col-4 col-form-label"><?php echo $text['job_id'];?> :</label>
        				        <div class="t2 col-5" >
        				            <input type="number" class="form-control" id="to_job_id" value ='<?php echo $data['next_job_id'];?>'>
        				        </div>
        				    </div>
        				    <div class="row">
        				        <label for="to_job_name" class="t1 col-4 col-form-label"><?php echo $text['job_name'];?> :</label>
        				        <div class="t2 col-5">
        				            <input type="text" class="form-control" id="to_job_name" value ='<?php echo "JOB-".$data['next_job_id'];?>'>
        				        </div>
        				    </div>
        			    </div>
        			  </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <button id="" class="button-modal" onclick="copy_job_by_id();"><?php echo $text['save'];?></button>
                   <button id="" class="button-modal"  onclick="closejob('copyjob')" class="closebtn"><?php echo $text['close'];?></button>
                </div> 
            </div>
        </div>
    </div>


</div>

<script>



$(document).ready(function () {
    highlight_row('job_table');
});

document.addEventListener('DOMContentLoaded', function() {
  var observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      var headerElements = document.querySelectorAll('.ajs-header');
      headerElements.forEach(function(headerElement) {
        headerElement.parentNode.removeChild(headerElement);
      });
    });
  });

  observer.observe(document.body, { childList: true, subtree: true });
});

var modal = document.getElementById('newjob');
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

</script>
<script>

    
var jobid ='';
var old_jobname = '';
var rows = document.getElementsByTagName("tr");
for (var i = 0; i < rows.length; i++) {
    (function(row) {
        var cells = row.getElementsByTagName("td");
        if (cells.length > 0) {
            cells[0].addEventListener("click", function() {
           
                var jobid = cells[0] ? (cells[0].textContent || cells[0].innerText) : null;
                var secondCellValue = cells[1] ? (cells[1].textContent || cells[1].innerText) : null;
                var thirdCellValue = cells[2] ? (cells[2].textContent || cells[2].innerText) : null;
                var speedvalue = cells[3] ? (cells[3].textContent || cells[3].innerText) : null;
                var powervalue = cells[4] ? (cells[4].textContent || cells[4].innerText) : null;
                jobid = jobid;
                old_jobname = secondCellValue;
        
                localStorage.setItem("jobid", jobid );
                localStorage.setItem("jobname", secondCellValue);
                localStorage.setItem("direction", thirdCellValue);
                localStorage.setItem("powervalue", powervalue);
                localStorage.setItem("speedvalue", speedvalue);

            });
        }
    })(rows[i]);
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
                            alertify.alert(responseData.res_type, responseData.res_msg, function() {
                                document.getElementById('copyjob').style.display = 'none';
                                history.go(0);
                            });
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
        
        //document.getElementById('copyjob').style.display = 'none';
    }
}

function savejob() {

    var jobidnew = '<?php echo $data['jobint']?>';
    var jobname_val      = document.getElementById("job_name").value;
    var rev_speed_val  = document.getElementById("rev_speed").value;
    var rev_force_val = document.getElementById("rev_force").value;

    var directionElement = document.querySelector('input[name="direction"]:checked');
    var direction_val = directionElement ? directionElement.value : null;

    var jobElement = document.querySelector('input[name="job_ok"]:checked');
    var job_ok_val = jobElement ? jobElement.value : null;

    var stopjobokElement = document.querySelector('input[name="stop_job_ok"]:checked');
    var stop_job_ok_val = stopjobokElement ? stopjobokElement .value : null;

     //驗證
    let check = input_check_savejob();

    if (check  ) {
        $.ajax({
            url: "?url=Jobs/create_job",
            method: "POST",
            data: { 
                jobidnew: jobidnew,
                jobname_val: jobname_val,
                rev_speed_val: rev_speed_val,
                rev_force_val: rev_force_val,
                direction_val: direction_val, //起子方向
                job_ok_val: job_ok_val,
                stop_job_ok_val:stop_job_ok_val
            },
            success: function(response) {
    
                var responseData = JSON.parse(response);
                alertify.alert(responseData.res_type, responseData.res_msg, function() {
                    history.go(0);
                });         
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed:", status, error);
            }
        });
    }
}

function validateInput(element, pattern, min, max) {
    let value = element.value.trim();
    let isValid = true;

    // 验证空值
    if (value === "") {
        element.classList.add("is-invalid");
        isValid = false;
    }
    // 验证正则
    else if (!pattern.test(value)) {
        element.classList.add("is-invalid");
        isValid = false;
    }
    // 验证最小值
    else if (min !== null && parseFloat(value) < min) {
        element.classList.add("is-invalid");
        isValid = false;
    }
    // 验证最大值
    else if (max !== null && parseFloat(value) > max) {
        element.classList.add("is-invalid");
        isValid = false;
    }
    // 通过验证
    else {
        element.classList.remove("is-invalid");
    }

    return isValid;
}

function input_check_savejob() {
    let conditions = [
        { id: 'job_name', pattern: /^[a-zA-Z0-9\u4E00-\u9FA5\-]+$/, min: null, max: null },
        { id: 'rev_speed', pattern: /^[0-9]+$/, min: 1, max: 1100 },
        { id: 'rev_force', pattern: /^[0-9]+$/, min: 10, max: 110 },
    ];

    let isFormValid = true;

    conditions.forEach(function(input) {
        var element = document.getElementById(input.id);
        if (input.id !== 'job_name') {
            element.nextElementSibling.innerHTML = `${input.min} ~ ${input.max}`;
        }

        if (!validateInput(element, input.pattern, input.min, input.max)) {
            isFormValid = false;
        }
    });

    return isFormValid;
}

function input_check_editjob() {
    let conditions = [
        { id: 'edit_jobname', pattern: /^[a-zA-Z0-9\u4E00-\u9FA5\-]+$/, min: null, max: null },
        { id: 'edit_rev_speed', pattern: /^[0-9]+$/, min: 1, max: 1100 },
        { id: 'edit_rev_force', pattern: /^[0-9]+$/, min: 10, max: 110 },
    ];

    let isFormValid = true;
    conditions.forEach(function(input) {
        var element = document.getElementById(input.id);
        /*if (input.id !== 'edit_jobname') {
            element.nextElementSibling.innerHTML = `${input.min} ~ ${input.max}`;
        }*/

        if (input.id !== 'edit_jobname') {
            const nextSibling = element.nextElementSibling; // 先存起來，避免重複呼叫
            if (nextSibling) { // 檢查 nextSibling 是否不為 null
                nextSibling.innerHTML = `${input.min} ~ ${input.max}`;
            } else {
                console.error(`Element with id '${input.id}' does not have a next sibling.`); // 錯誤處理，例如輸出到控制台
                // 或者你可以選擇創建一個新的元素，並將它插入到 element 之後
                // const newSibling = document.createElement('span');
                // element.parentNode.insertBefore(newSibling, element.nextSibling);
                // newSibling.innerHTML = `${input.min} ~ ${input.max}`;
            }
        }


        if (!validateInput(element, input.pattern, input.min, input.max)) {
            isFormValid = false;
        }
    });

    return isFormValid;
}

function closejob(elementId) {
    // 確保傳入的 elementId 有效，並且元素存在
    document.getElementById(elementId).style.display = 'none';
   
    // 確保 .main-content 元素存在
    var mainContent = document.querySelector(".main-content");
    if (mainContent) {
        mainContent.classList.remove("overlay-active");
    }
}
</script>

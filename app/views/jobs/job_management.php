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
                        <table id="job_table"  class="table w3-table">
                            <thead id="header-table">
                                <tr class="w3-dark-grey">
                                    <th><?php echo $text['job_id'];?></th>
                                    <th><?php echo $text['job_name'];?></th>
                                    <th><?php echo $text['rev_direction'];?></th>
                                    <th><?php echo $text['rev_speed'];?></th>
                                    <th><?php echo $text['rev_force'];?></th>
                                    <th><?php echo $text['total_seq'];?></th>
                                    <th><?php echo $text['add_seq'];?></th>
                                </tr>
                            </thead>

                            <tbody style="font-size: 1.8vmin;text-align: center;">
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

    <style>
        .job-name {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%; /* hoặc chiều rộng cụ thể như 300px */
        }
    </style>

 
    <div style="display:block;">
        <input id="tool_max_tor" value="<?php echo $data['tools']['tool_maxtorque']; ?>">
        <input id="tool_min_tor" value="<?php echo $data['tools']['tool_mintorque']; ?>">
        <input id="tool_max_rpm" value="<?php echo $data['tools']['tool_maxrpm']; ?>">
        <input id="tool_min_rpm" value="<?php echo $data['tools']['tool_minrpm']; ?>">
        <input id="rev_tor_unit" value="<?php echo  $data['rev_tor_unit']?>">
    </div>


    <!-- Add New Job -->
    <div id="newjob" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w3-animate-zoom" style="width: 75%">
                <header class="w3-container modal-header">
                    <span onclick="closebutton('newjob');"
                        class="w3-button w3-red w3-display-topright" style="width: 50px; height: 42px; margin: 3px; font-size: 18px;">&times;</span>
                    <h3 id='modal_title'><?php echo $text['new_job'];?></h3>
                </header>

                <div class="modal-body">
                    <form id="new_job_form" style="padding-left: 5%">
                        <div class="row">
                            <div for="job-id" class="col-6 t1"><?php echo $text['job_id'];?> :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="job_id"  value='<?php echo $data['next_job_id'];?>' disabled >
                            </div>
                        </div>
                        <div class="row">
                            <div for="job-name" class="col-6 t1"><?php echo $text['job_name'];?> :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="job_name" value ='<?php echo "JOB"."-".$data['next_job_id'];?>' >
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div for="Unscrew-Direction" class="col-6 t1"><?php echo $text['job_ok'];?> :</div>
                            <div class="col t2">

                                <div class="form-check form-check-inline col-md-3">
            					  <input class="form-check-input" type="radio" name="job_ok" id="job_off" value="0" >
            					  <label class="form-check-label" for="job_off"> <?php  echo $text['OFF_text']; ?></label>
            					</div>

            			      	<div class="form-check form-check-inline col-md-3">
            					  <input class="form-check-input" type="radio" name="job_ok" id="job_ok" value="1">
            					  <label class="form-check-label" for="job_ok"><?php  echo $text['ON_text']; ?></label>
            					</div>
                            </div>
                        </div>

                        <div class="row">
                            <div for="Unscrew-Direction" class="col-6 t1"><?php echo $text['job_ok_stop'];?> :</div>
                            <div class="col t2">

                                <div class="form-check form-check-inline col-md-3">
            					  <input class="form-check-input" type="radio" name="job_ok_stop" id="job_ok_stop_off" value="0" >
            					  <label class="form-check-label" for="job_ok_stop_off"> <?php  echo $text['OFF_text']; ?></label>
            					</div>

            			      	<div class="form-check form-check-inline col-md-3">
            					  <input class="form-check-input" type="radio" name="job_ok_stop" id="job_ok_stop_ok" value="1">
            					  <label class="form-check-label" for="job_ok_stop_ok"><?php  echo $text['ON_text']; ?></label>
            					</div>
                            </div>
                        </div>


                        <div class="row">
                            <div for="Unscrew-Direction" class="col-6 t1" ><?php echo $text['rev_direction'];?> :</div>
                            <div class="col t2" >
            			      	<div class="form-check form-check-inline col-md-3">
            					  <input class="form-check-input" type="radio" name="direction" id="rev_direction_CW" value="0">
            					  <label class="form-check-label" for="rev_direction_CW" style="white-space: nowrap;"><?php  echo $text['CW']; ?></label>
            					</div>
            					<div class="form-check form-check-inline col-md-3">
            					  <input class="form-check-input" type="radio" name="direction" id="rev_direction_CCW" value="1">
            					  <label class="form-check-label" for="rev_direction_CCW" style="white-space: nowrap;"> <?php  echo $text['CCW']; ?></label>
            					</div>

                                <div class="form-check form-check-inline col-md-3">
            					  <input class="form-check-input" type="radio" name="direction" id="rev_direction_disable" value="2">
            					  <label class="form-check-label" for="rev_direction_disable" style="white-space: nowrap;"> <?php  echo $text['Disable']; ?></label>
            					</div>

                            </div>
                        </div>

                        
                        <div class="row">
                            <div for="reverse-RPM" class="col-6 t1"><?php echo $text['rev_speed'];?>(Max:1100) :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="rev_speed" maxlength="" >
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div for="reverse-power" class="col-6 t1"><?php echo $text['rev_force'];?>(%) :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="rev_force" maxlength="">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 t1"><?php echo $text['rev_option']; ?> :</div>
                            <div class="col-4 t2">
                                <select id="rev_cnt_mode" name="rev_cnt_mode" class="custom-file" style="width:225px">
                                    <option value="0"><?php echo $text['OFF_text']; ?></option>
                                    <option value="1"><?php echo $text['threshold_tor']; ?></option>
                                    <option value="2"><?php echo $text['threshold_ang']; ?></option>
                                    <option value="3"><?php echo $text['all_torque_angle']; ?></option>
                                    <option value="4"><?php echo $text['all_torque_first']; ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 t1"><?php echo $text['threshold_tor']; ?>(<?php echo $text[$data['unit_name']] ?? $data['unit_name']; ?>) :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="rev_th_tor" name="rev_th_tor">
                                <input type="hidden" id="rev_tor_unit" value="<?php echo  $data['rev_tor_unit']?>">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 t1"><?php echo $text['threshold_ang']; ?> :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="rev_th_ang" name="rev_th_ang">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <button id="" class="button-modal" onclick="savejob()"><?php echo $text['save'];?></button>
                    <button id="" class="button-modal" onclick="closebutton('newjob');" class="closebtn"><?php echo $text['close'];?></button>
                </div>
            </div>
        </div>
    </div>


    <!-- edit Job -->
    <div id="editjob" class="modal" >
    <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w3-animate-zoom" style="width: 75%">
                <header class="w3-container modal-header">
                    <span onclick="closebutton('editjob');"
                        class="w3-button w3-red w3-display-topright" style="width: 50px; margin: 3px;">&times;</span>
                    <h3 id='modal_title'><?php echo $text['edit_job'];?></h3>
                </header>

                <div class="modal-body">
                    <form id="new_job_form" style="padding-left: 5%">
                        <div class="row">
                            <div for="job-id" class="col-6 t1"><?php echo $text['job_id'];?> :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="edit_jobid" maxlength=""  value='' disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div for="job-name" class="col-6 t1"><?php echo $text['job_name'];?> :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="edit_jobname" maxlength="" >
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div for="Unscrew-Direction" class="col-6 t1"><?php echo $text['job_ok'];?> :</div>
                            <div class="col t2" >

                                <div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_job_ok" id="edit_job_off" value="0" >
            					  <label class="form-check-label" for="edit_job_off"> <?php  echo $text['OFF_text']; ?></label>
            					</div>

            			      	<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_job_ok" id="edit_job_ok" value="1">
            					  <label class="form-check-label" for="edit_job_ok"><?php  echo $text['ON_text']; ?></label>
            					</div>
                            </div>
                        </div>

                        <div class="row">
                            <div for="Unscrew-Direction" class="col-6 t1"><?php echo $text['job_ok_stop'];?> :</div>
                            <div class="col t2" >

                                <div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_job_ok_stop" id="edit_job_ok_stop_off" value="0" >
            					  <label class="form-check-label" for="edit_job_ok_stop_off"> <?php  echo $text['OFF_text']; ?></label>
            					</div>

            			      	<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_job_ok_stop" id="edit_job_ok_stop_ok" value="1">
            					  <label class="form-check-label" for="edit_job_ok_stop_ok"><?php  echo $text['ON_text']; ?></label>
            					</div>
                            </div>
                        </div>


                        <div class="row">
                            <div for="Unscrew-Direction" class="col-6 t1"><?php echo $text['rev_direction'];?> :</div>
                            <div class="col t2" >
            			      	<div class="form-check form-check-inline col-md-3">
            					  <input class="form-check-input" type="radio" name="edit_direction" id="edit_direction_CW" value="0">
            					  <label class="form-check-label" for="edit_direction_CW" style="white-space: nowrap;"><?php  echo $text['CW'];?></label>
            					</div>

            					<div class="form-check form-check-inline col-md-3">
            					  <input class="form-check-input" type="radio"  name="edit_direction" id="edit_direction_CCW" value="1">
            					  <label class="form-check-label" for="edit_direction_CCW" style="white-space: nowrap;"><?php  echo $text['CCW'];?></label>
            					</div>

                                <div class="form-check form-check-inline col-md-3">
            					  <input class="form-check-input" type="radio" name="edit_direction" id="edit_direction_disable" value="2">
            					  <label class="form-check-label" for="edit_direction_disable" style="white-space: nowrap;"> <?php  echo $text['Disable']; ?></label>
            					</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 t1"><?php echo $text['rev_speed'];?>(Max:1100):</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="edit_rev_speed" maxlength="" >
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 t1"><?php echo $text['rev_force'];?>(%):</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="edit_rev_force" maxlength="">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 t1"><?php echo $text['rev_option']; ?> :</div>
                            <div class="col-4 t2">
                                <select id="edit_rev_cnt_mode" name="edit_rev_cnt_mode" class="custom-file" style="width:225px">
                                    <option value="0"><?php echo $text['OFF_text']; ?></option>
                                    <option value="1"><?php echo $text['threshold_tor']; ?></option>
                                    <option value="2"><?php echo $text['threshold_ang']; ?></option>
                                    <option value="3"><?php echo $text['all_torque_angle']; ?></option>
                                    <option value="4"><?php echo $text['all_torque_first']; ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 t1"><?php echo $text['threshold_tor']; ?>(<span id='threshold_tor_label'></span>) :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="edit_rev_th_tor" name="edit_rev_th_tor">
                                <input type="hidden" id="rev_tor_unit" value="<?php echo  $data['rev_tor_unit']?>">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 t1"><?php echo $text['threshold_ang']; ?> :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="edit_rev_th_ang" name="edit_rev_th_ang">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        

                    </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <button id="" class="button-modal" onclick="updatejob();"><?php echo $text['save'];?></button>
                    <button id="" class="button-modal" onclick="closebutton('editjob');" class="closebtn"><?php echo $text['close'];?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Copy Job -->
    <div id="copyjob" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w3-animate-zoom" style="width: 60%">
                <header class="w3-container modal-header">
                    <span onclick="closebutton('copyjob');"
                        class="w3-button w3-red w3-display-topright" style="width: 50px; margin: 3px;">&times;</span>
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
        				        <div class="t2 col-5">
        				            <input type="number" class="form-control" id="to_job_id" value= '<?php echo $data['next_job_id'];?>' disabled style="background-color: #fff; color: #000; border: 1px solid #ccc;">
        				        </div>
        				    </div>
        				    <div class="row">
        				        <label for="to_job_name" class="t1 col-4 col-form-label"><?php echo $text['job_name'];?> :</label>
        				        <div class="t2 col-5">
        				            <input type="text" class="form-control" id="to_job_name" value ='<?php echo "JOB"."-".$data['next_job_id'];?>'>
        				        </div>
        				    </div>
        			    </div>
        			  </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <button id="" class="button-modal" onclick="copy_job_by_id();"><?php echo $text['save'];?></button>
                    <button id="" class="button-modal" onclick="closebutton('copyjob');" class="closebtn"><?php echo $text['close'];?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- 加载動畫 OP -->
        <?php require_once '../app/views/inc/include_spinner.php';?>
    <!-- 加载動畫 ED -->

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

// Get the modal
var modal = document.getElementById('newjob');
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

<script>    

window.max_rpm_diff = "<?php echo $data['tools']['tool_maxrpm']?>";
window.max_rpm_diff = "<?php echo $data['tools']['tool_minrpm']?>";

let rev_tor_unit = '<?php echo $data['rev_tor_unit']?>';

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

function savejob() {

    var jobidnew = '<?php echo $data['next_job_id']?>';

    var jobname_val       = document.getElementById("job_name").value;
    var rev_speed_val     = document.getElementById("rev_speed").value;
    var rev_force_val     = document.getElementById("rev_force").value;

    // 05/29 Lana add new function
    var rev_cnt_mode     = document.getElementById("rev_cnt_mode").value;
    var rev_th_tor     = document.getElementById("rev_th_tor").value;
    var rev_th_ang     = document.getElementById("rev_th_ang").value;

    var rev_tor_unit = <?php echo  $data['rev_tor_unit'] ?>

    var directionElement = document.querySelector('input[name="direction"]:checked');
    var direction_val = directionElement ? directionElement.value : null;

    var jobElement = document.querySelector('input[name="job_ok"]:checked');
    var job_ok_val = jobElement ? jobElement.value : null;

    var stopjobokElement = document.querySelector('input[name="job_ok_stop"]:checked');
    var job_ok_stop_val = stopjobokElement ? stopjobokElement .value : null;

    //驗證
    let check = input_check_savejob();

    if (check) {

        document.getElementById('spinner').style.display = 'block';

        $.ajax({
            url: "?url=Jobs/create_job",
            method: "POST",
            data: { 
                jobidnew: jobidnew,
                jobname_val: jobname_val,
                rev_speed_val: rev_speed_val,
                rev_force_val: rev_force_val,
                rev_cnt_mode: rev_cnt_mode,
                rev_th_tor: rev_th_tor,
                rev_th_ang: rev_th_ang,
                rev_tor_unit: rev_tor_unit,
                direction_val: direction_val, //起子方向
                job_ok_val: job_ok_val,
                job_ok_stop_val:job_ok_stop_val
            },
            success: function(response) {
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
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed:", status, error);
            }
            
        });
    }
}


function copy_job_by_id(jobid){

    var new_jobid = document.getElementById("to_job_id").value;
    var new_jobname = document.getElementById("to_job_name").value;

    document.getElementById("from_job_id").value = old_jobid;
    document.getElementById("from_job_name").value = oldjobname;
    document.getElementById("to_job_id").value = new_jobid;

    if(new_jobid){


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
            url: "?url=Jobs/check_job_type",
            method: "POST",
            data:{ 
                new_jobid: new_jobid,

            },
            success: function(response) {
                alertify.confirm(text_info, function (result) {

                
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
                        //document.getElementById('spinner').style.display = 'none';  // 隱藏 spinner
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


function copy_data(jobid){
    var new_jobid = document.getElementById("to_job_id").value;
    var new_jobname = document.getElementById("to_job_name").value;

    document.getElementById("from_job_id").value = old_jobid;
    document.getElementById("from_job_name").value = oldjobname;
    document.getElementById("to_job_id").value = new_jobid;

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

    let rangeLabel = "<?php echo $error_message['OOR']; ?>"; 
    let max_rpm = "<?php echo $data['tools']['tool_maxrpm']?>";
    let min_rpm = "<?php echo $data['tools']['tool_minrpm']?>";
    
    let rev_cnt_mode = document.getElementById('edit_rev_cnt_mode').value;



    let conditions = [
        { id: 'job_name', pattern: /^[a-zA-Z0-9\u4E00-\u9FA5\-]+$/, min: null, max: null },
        { id: 'rev_speed', pattern: /^[0-9]+$/, min: min_rpm, max: 1100 },
        { id: 'rev_force', pattern: /^[0-9]+$/, min: 10, max: 110 },

    ];

    // ✅ Kiểm tra thêm theo chế độ rev_count
    if (rev_cnt_mode === "1") { // Threshold Tor.
        conditions.push({
            id: prefix + 'rev_th_tor',
            pattern: /^\d{1,4}(\.\d{1,2})?$/ // chỉ kiểm tra định dạng số, không giới hạn min/max
        });
    }
    if (rev_cnt_mode === "2") { // Threshold Ang.
        conditions.push({
            id: prefix + 'rev_th_ang',
            pattern: /^\d{1,4}$/ // chỉ kiểm tra là số có tối đa 4 chữ số
        });
    }
    else if (rev_cnt_mode === "3" || rev_cnt_mode === "4") { // All
        conditions.push(
            {
                id: prefix + 'rev_th_tor',
                pattern: /^\d{1,4}(\.\d{1,2})?$/ // chỉ kiểm tra định dạng số, không giới hạn min/max
            },
            {
                id: prefix + 'rev_th_ang',
                pattern: /^\d{1,4}$/ // chỉ kiểm tra là số có tối đa 4 chữ số
            }
        );
    }


    let isFormValid = true;

    conditions.forEach(function(input) {
        var element = document.getElementById(input.id);
        if (input.id !== 'job_name') {
            element.nextElementSibling.innerHTML = `${rangeLabel} ${input.min} ~ ${input.max}`;
        }

        if (!validateInput(element, input.pattern, input.min, input.max)) {
            isFormValid = false;
        }
    });

    return isFormValid;
}

function input_check_editjob() {
    let rangeLabel = "<?php echo $error_message['OOR']; ?>"; 

    let max_rpm = parseInt("<?php echo $data['tools']['tool_maxrpm']?>");
    let min_rpm = parseInt("<?php echo $data['tools']['tool_minrpm']?>");

    // Lấy giá trị rev_count từ select
    let rev_cnt_mode = document.getElementById('edit_rev_cnt_mode').value;

    // Nếu dùng prefix cho id (nếu không có thì bỏ dòng này và xài id trực tiếp)
    let prefix = 'edit_';

    let conditions = [
        { id: 'edit_jobname', pattern: /^[a-zA-Z0-9\u4E00-\u9FA5\-]+$/, min: null, max: null },
        { id: 'edit_rev_speed', pattern: /^[0-9]+$/, min: min_rpm, max: max_rpm },
        { id: 'edit_rev_force', pattern: /^[0-9]+$/, min: 10, max: 110 },
    ];

    // Thêm điều kiện theo rev_count
    if (rev_cnt_mode === "1") { // Threshold Tor.
        conditions.push({
            id: prefix + 'rev_th_tor',
            pattern: /^\d{1,4}(\.\d{1,2})?$/ // chỉ kiểm tra định dạng số, không giới hạn min/max
        });
    } else if (rev_cnt_mode === "2") { // Threshold Ang.
        conditions.push({
            id: prefix + 'rev_th_ang',
            pattern: /^\d{1,4}$/ // chỉ kiểm tra là số có tối đa 4 chữ số
        });
    } else if (rev_cnt_mode === "3" || rev_cnt_mode === "4") { // Both
        conditions.push(
            {
                id: prefix + 'rev_th_tor',
                pattern: /^\d{1,4}(\.\d{1,2})?$/ // chỉ kiểm tra định dạng số, không giới hạn min/max
            },
            {
                id: prefix + 'rev_th_ang',
               pattern: /^\d{1,4}$/ // chỉ kiểm tra là số có tối đa 4 chữ số
            }
        );
    }

    let isFormValid = true;

    conditions.forEach(function(input) {
        var element = document.getElementById(input.id);
        if (!element) {
            console.warn('Element not found:', input.id);
            return;
        }

        if (input.id !== 'edit_jobname') {
            // Hiển thị thông báo phạm vi sai số kế bên input
            if (element.nextElementSibling) {
                element.nextElementSibling.innerHTML = `${rangeLabel} ${input.min} ~ ${input.max}`;
            }
        }

        if (!validateInput(element, input.pattern, input.min, input.max)) {
            isFormValid = false;
        }
    });

    return isFormValid;
}


</script>

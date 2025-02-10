
<link rel="stylesheet" href="<?php echo URLROOT; ?>css/tcc_step.css" type="text/css">
<style>
.form-control{
    width: auto!important;
    display: initial!important;
}

.form-control.is-invalid{
    padding-right:inherit!important;
}
.is-invalid~.invalid-feedback{
    display: inline!important;
}
</style>

<div class="container-ms">
    <div class="w3-text-white w3-center">
        <table class="no-border">
            <tr id="header">
                <td width="100%"><h3><?php echo $text['step_management']; ?></h3></td>
                <!--<td>
                    <img src="./img/btn_home.png" style="margin-right: 10px">
                </td>-->
            </tr>
        </table>
    </div>

    <div class="main-content">
        <div class="center-content">
            <div class="topnav">
                <label style="font-size:20px;color: #000; padding-left: 2%" for="job_id"><?php echo $text['job_id'];?> :</label>&nbsp;
                <input type="text" id="job_id" name="job_id" size="8" maxlength="20" value="<?php echo $data['job_id'];?>" disabled
                style="height:28px; font-size:20px;text-align: center; background-color: #DDDDDD; border:0; margin: 3px;">

                <label style="font-size:20px;color: #000; padding-left: 2%" for="seq_id"><?php echo $text['seq_id'];?> :</label>&nbsp;
                <input type="text" id="seq_id" name="seq_id" size="8" maxlength="20" value="<?php echo $data['seq_id'];?>" disabled
                style="height:28px; font-size:20px;text-align: center; background-color: #DDDDDD; border:0; margin: 3px;">

                <button id="back_btn" type="button" onclick="window.history.back()"><?php echo $text['return'];?></button>
            </div>

            <div class="table-container">
                <table id="step_table" class="table w3-table-all w3-hoverable">
                    <thead id="header-table">
                        <tr class="w3-dark-grey">
                            <th><?php echo $text['step_id'];?></th>
                            <th><?php echo $text['step_target_type'];?></th>
                            <th><?php echo $text['direction'];?></th>
                            <th><?php echo $text['up'];?></th>
                            <th><?php echo $text['down'];?></th>
                        </tr>
                    </thead>

                    <tbody style="font-size: 1.8vmin;text-align: center;">
                       <?php foreach($data['step'] as $key =>$val){?>
                        <tr>
                            <td><?php echo $val['step_id'];?></td>
                            <td><?php echo $text[$data['target_option'][$val['target_option']]];?></td>
                            <td><?php echo $text[$data['direction'][$val['direction']]];?></td>
                            <td><img src="./img/btn_up.png" onclick="MoveUp(this);"></td>
                            <td><img src="./img/btn_down.png"onclick="MoveDown(this);"></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="footer">
        <div id="TotalPage">
            <div id="TotalStepTable">
                <div style="color:black; float: right; margin: 2px"><?php echo $text['total_step'];?> :
                    <label id="RecordCnt" name="RecordCnt" type="text" style="margin-right: 20px"><?php echo count($data['step']);?></label>
                </div>
            </div>
        </div>

        <div class="buttonbox">
            <?php $status = count($data['step']) == 4 ? 'disabled' : ''; ?>
            <input id="S3" name="Step_Manager_Submit" type="button" value="<?php echo $text['New'];?>" tabindex="1"  onclick="cound_step('new');" <?php echo $status;?>>
            <input id="S6" name="Step_Manager_Submit" type="button" value="<?php echo $text['Edit'];?>" tabindex="1" onclick="cound_step('edit')">
            <input id="S5" name="Step_Manager_Submit" type="button" value="<?php echo $text['Copy'];?>" tabindex="1"  onclick="cound_step('copy');" <?php echo $status; ?>>
            <input id="S4" name="Step_Manager_Submit" type="button" value="<?php echo $text['Delete'];?>" tabindex="1" onclick="cound_step('del');" >
        </div>
    </div>

    <!-- Add New Step -->
    <div id="newstep" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w3-animate-zoom" style="width: 80%">
                <header class="w3-container modal-header">
                    <span onclick="hideElementById('newstep');"
                        class="w3-button w3-red w3-display-topright" style="width: 50px; margin: 3px;">&times;</span>
                    <h3 id='modal_title'><?php echo $text['new_step'];?></h3>
                </header>

                <div style="display:none;">
                    <input id="tool_max_tor" value="<?php echo $data['tools']['tool_maxtorque']; ?>">
                    <input id="tool_min_tor" value="<?php echo $data['tools']['tool_mintorque']; ?>">
                    <input id="tool_max_rpm" value="<?php echo $data['tools']['tool_maxrpm']; ?>">
                    <input id="tool_min_rpm" value="<?php echo $data['tools']['tool_minrpm']; ?>">
                </div>


                <div class="modal-body">
                    <form id="new_step_form" style="padding-left: 5%">
                        <div class="row">
                            <div for="target-option" class="col-6 t1"><?php echo $text['step_target_type'];?> :</div>
                            <div class="col-3 t2">
                                <select id="target_opt" name="target_opt" class="col custom-file">
                                    <?php if($data['check'][0]['count_records'] == "1"){?>
                                        <?php foreach($data['target_option_change'] as $key => $val){?>
                                             <option value="<?php echo $key;?>"><?php echo $text[$val];?></option>
                                        <?php }?>   

                                    <?php } else {?>
                                        <?php foreach($data['target_option'] as $key => $val){?>
                                             <option value="<?php echo $key;?>"><?php echo $text[$val];?></option>
                                        <?php }?>     
                                    <?php } ?>
                                   
                                    
                                </select>
                            </div>
                        </div>

                        <div id='target_tor_item' style="display: block;"  >
                            <div class="row">
                                <div  class="col-6 t1"><?php echo $text['Target_Torque'];?> (<?php echo $text[$data['unit_name']];?>):</div>
                                <div class="col-3 t2">
                                    <input type="text" class="form-control input-ms" id="target_tor" maxlength="" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div id='target_ang_item' style="display: none;"  >                     
                            <div class="row">
                                <div class="col-6 t1"><?php echo $text['Target_Angle'];?> :</div>
                                <div class="col-3 t2">
                                    <input type="text" class="form-control input-ms" id="target_ang" maxlength="" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div id='target_delay_item' style="display: none;"  >       
                            <div class="row">
                                    <div for="target-torque" class="col-6 t1"><?php echo $text['Target Delay Time'];?> :</div>
                                    <div class="col-3 t2">
                                        <input type="text" class="form-control input-ms" id="target_delay" maxlength="" >
                                        <div class="invalid-feedback"></div>
                                    </div>
                            </div>
                        </div>


                        <div class="row">
                            <div for="hi-torque" class="col-6 t1"><?php echo $text['High_Torque'];?> (<?php echo $text[$data['unit_name']];?>):</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="tor_hi" maxlength="" >
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div for="lo-torque" class="col-6 t1"><?php echo $text['Low_Torque'];?> (<?php echo $text[$data['unit_name']];?>):</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="tor_lo" maxlength="" >
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div for="hi-angle" class="col-6 t1"><?php echo $text['High_Angle'];?> :</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="ang_hi" maxlength="" >
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div for="lo-angle" class="col-6 t1"><?php echo $text['Low_Angle'];?>:</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="ang_lo" maxlength="" >
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div for="RPM" class="col-6 t1"><?php echo $text['rpm'];?>:</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="rpm" maxlength="" >
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div for="direction" class="col-6 t1"><?php echo $text['direction'];?>:</div>
                            <div class="col t2" >
            			      	<div class="col-4 form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="direction_option" id="direction_CW" value="0">
            					  <label class="form-check-label" for="direction_CW"><?php echo $text['CW'];?></label>
            					</div>
            					<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="direction_option" id="direction_CCW" value="1" checked="checked">
            					  <label class="form-check-label" for="direction_CCW"><?php echo $text['CCW'];?></label>
            					</div>
                            </div>
                        </div>
                        <div class="row">
                            <div for="downshift" class="col-6 t1"><?php echo $text['Downshift'];?>:</div>
                            <div class="col t2" >
            			      	<div class="col-4 form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="ds_mode" id="downshift_ON" value="0" checked="checked">
            					  <label class="form-check-label" for="downshift_ON"><?php echo $text['switch_on'];?></label>
            					</div>
            					<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="ds_mode" id="downshift_OFF" value="1" >
            					  <label class="form-check-label" for="downshift_OFF"><?php echo $text['switch_off'];?></label>
            					</div>
                            </div>
                        </div>
                        <div class="row" >
                            <div id="downshift_threshold_title" for="th_tor" class="col-6 t1"><?php echo $text['Threshold_Torque'];?>(<?php echo $text[$data['unit_name']];?>):</div>
                            <div class="col-3 t2" id="downshift_threshold_item"> 
                                <input type="text" class="form-control input-ms" id="th_tor" >
                            </div>
                        </div>
                        <div class="row" >
                            <div id="downshift_torque_title" for="ds_tor" class="col-6 t1"><?php echo $text['Downshift_Torque'];?>(<?php echo $text[$data['unit_name']];?>):</div>
                            <div class="col-3 t2" id="downshift_torque_item">
                                <input type="text" class="form-control input-ms" id="ds_tor" maxlength="" >
                            </div>
                        </div>
                        <div class="row" >
                            <div id="downshift_speed_title" for="downshift-speed" class="col-6 t1"><?php echo $text['Downshift_Speed'];?>:</div>
                            <div class="col-3 t2" id="downshift_speed_item">
                                <input type="text" class="form-control input-ms" id="ds_speed" maxlength="" >
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <input type='hidden' id='step_torque_unit' name='step_torque_unit' value='<?php echo $data['step_torque_unit'];?>'>
                    <button id="" class="button-modal" onclick="add_step()" ><?php echo $text['save'];?></button>
                    <button id="" class="button-modal" onclick="hideElementById('newstep');"  class="closebtn"><?php echo $text['close'];?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- edit Step -->
    <div id="editstep" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w3-animate-zoom" style="width: 80%">
                <header class="w3-container modal-header">
                    <span onclick="hideElementById('editstep');"
                        class="w3-button w3-red w3-display-topright" style="width: 50px; margin: 3px;">&times;</span>
                    <h3 id='modal_title'><?php echo $text['edit_step'];?></h3>
                </header>

                <div class="modal-body">
                    <form id="new_step_form" style="padding-left: 5%">
                        <div class="row">
                            <div for="target-option" class="col-6 t1"><?php echo $text['step_target_type'];?> :</div>
                            <div class="col-3 t2">
                                <select id="edit_target_option" name="edit_target_option" class="col custom-file">
                                    <?php foreach($data['target_option'] as $key => $val){?>
                                        <option value="<?php echo $key;?>"><?php echo $text[$val];?></option>
                                    <?php }?>
                                    
                                </select>
                            </div>
                        </div>

                        
                        <div class="row">
                            <div for="edit_target-torque" id="edit_target-torque_title"  class="col-6 t1" style="display: none;" ><?php echo $text['Target_Angle'];?>(<?php echo $text[$data['unit']];?>):</div>
                            <div class="col-3 t2" id="edit_target-torque_val" style="display:none;" >
                                <input type="text" class="form-control input-ms" id="edit_target_torque" maxlength="" >
                            </div>
                        </div>
                     
                   

                        <div class="row">
                            <div for="edit_target-angle" id="edit_target-angle_title"  class="col-6 t1" style="display: none;" ><?php echo $text['Target_Angle'];?>:</div>
                            <div class="col-3 t2" id="edit_target-angle_val" style="display:none;" >
                                <input type="text" class="form-control input-ms" id="edit_target_angle" maxlength="" >
                            </div>
                        </div>


                        <div class="row">
                            <div for="edit_target-delaytime" id="edit_target-delaytime_title"  class="col-6 t1" style="display: none;" ><?php  echo  $text['Target Delay Time'] ; ?>:</div>
                            <div class="col-3 t2" id="edit_target-delaytime_val" style="display:none;" >
                                <input type="text" class="form-control input-ms" id="edit_target_delaytime" maxlength="" >
                            </div>
                        </div>

                        <div class="row">
                            <div for="hi-torque" class="col-6 t1"><?php echo $text['High_Torque'];?> (<?php echo $text[$data['unit_name']];?>):</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="edit_hi_torque" maxlength="" >
                            </div>
                        </div>
                        
                        <div class="row">
                            <div for="lo-torque" class="col-6 t1"><?php echo $text['Low_Torque'];?> (<?php echo $text[$data['unit_name']];?>):</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="edit_lo_torque" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="hi-angle" class="col-6 t1"><?php echo $text['High_Angle'];?>:</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="edit_hi_angle" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="lo-angle" class="col-6 t1"><?php echo $text['Low_Angle'];?>:</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="edit_lo_angle" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="RPM" class="col-6 t1"><?php echo $text['rpm'];?>:</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="edit_rpm" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="direction" class="col-6 t1"><?php echo $text['direction'];?>:</div>
                            <div class="col t2" >
            			      	<div class="col-4 form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_direction_option" id="direction_CW" value="0">
            					  <label class="form-check-label" for="direction_CW"><?php echo $text['CW'];?></label>
            					</div>
            					<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_direction_option" id="direction_CCW" value="1">
            					  <label class="form-check-label" for="direction_CCW"><?php echo $text['CCW'];?></label>
            					</div>
                            </div>
                        </div>
                        <div class="row">
                            <div for="downshift" class="col-6 t1"><?php echo $text['Downshift'];?>:</div>
                            <div class="col t2" >
            			      	<div class="col-4 form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_downshift_option" id="downshift_ON" value="1">
            					  <label class="form-check-label" for="downshift_ON"><?php echo $text['switch_on'];?></label>
            					</div>
            					<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_downshift_option" id="downshift_OFF" value="0" >
            					  <label class="form-check-label" for="downshift_OFF"><?php echo $text['switch_off'];?></label>
            					</div>
                            </div>
                        </div>
                        <div class="row" id="edit_downshift_threshold_title">
                            <div class="col-6 t1">Downshift Threshold(<?php echo $text[$data['unit_name']];?>):</div>
                            <div class="col-3 t2" id="edit_downshift_threshold_item">
                                <input type="text" class="form-control input-ms" id="edit_downshift_threshold" maxlength="" >
                            </div>
                        </div>
                        <div class="row" id="edit_downshift_torque_title">
                            <div class="col-6 t1"><?php echo $text['Downshift_Torque'];?>(<?php echo $text[$data['unit_name']];?>):</div>
                            <div class="col-3 t2" id="edit_downshift_torque_item">
                                <input type="text" class="form-control input-ms" id="edit_downshift_torque" maxlength="" >
                            </div>
                        </div>
                        <div class="row" id="edit_downshift_speed_title">
                            <div class="col-6 t1"><?php echo $text['Downshift_Speed'];?>:</div>
                            <div class="col-3 t2" id="edit_downshift_speed_item">
                                <input type="text" class="form-control input-ms" id="edit_downshift_speed" maxlength="" >
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <button id="" class="button-modal" onclick="edit_step_save()" ><?php echo $text['save'];?></button>
                    <button id="" class="button-modal" onclick="hideElementById('edittep');"  class="closebtn"><?php echo $text['close'];?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Copy Step -->
    <div id="copystep" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w3-animate-zoom" style="width: 60%">
                <header class="w3-container modal-header">
                    <span onclick="document.getElementById('copystep').style.display='none'"
                        class="w3-button w3-red w3-display-topright" style="width: 50px; margin: 3px;">&times;</span>
                    <h3 id='modal_title'><?php echo $text['copy_step'];?></h3>
                </header>

                <div class="modal-body">
                    <form id="new_step_form">
        	            <label for="from_step_id" class="col col-form-label" style="font-weight: bold"><?php echo $text['copy_from'];?></label>
        	            <div style="padding-left: 10%">
        		            <div class="row">
        				        <label for="from_step_id" class="t1 col-4 col-form-label"><?php echo $text['step_id'];?> :</label>
        				        <div class="col-5 t2 ">
        				            <input type="number" class="form-control" id="from_step_id" disabled>
        				        </div>
        				    </div>
        			    </div>

        			    <label for="from_step_id" class="col col-form-label" style="font-weight: bold"><?php echo $text['copy_to'];?></label>
        			    <div style="padding-left: 10%">
        				    <div class="row">
        				        <label for="to_step_id" class="t1 col-4 col-form-label"><?php echo $text['step_id'];?> :</label>
        				        <div class="t2 col-5">
        				            <input type="number" class="form-control" id="to_step_id">
        				        </div>
        				    </div>
        			    </div>
        			  </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <button id="copyButton" class="button-modal" onclick="copy_step_by_id_ajax()" ><?php echo $text['save'];?></button>
                    <button id="" class="button-modal" onclick="document.getElementById('copystep').style.display='none'" class="closebtn"><?php echo $text['close'];?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

let selected_target_opt_val = '';

let jobid = '<?php echo $data['job_id']?>';
let seqid = '<?php echo $data['seq_id']?>';
let add_stepid = '<?php echo $data['step_count']?>';
let step_torque_unit = '<?php echo $data['step_torque_unit']?>';


$(document).ready(function () {
    highlight_row('step_table');
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
var modal = document.getElementById('newstep');

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
//var stepid = '';
var check_step_torque = '<?php echo $data['check_step_torque']?>';

var rows = document.getElementsByTagName("tr");
for (var i = 0; i < rows.length; i++) {
    (function(row) {
        var cells = row.getElementsByTagName("td");
        if (cells.length > 0) {
            cells[0].addEventListener("click", function() {
           
                stepid   = cells[0] ? (cells[0].textContent || cells[0].innerText) : null;
                localStorage.setItem("stepid", stepid);
            });
        }
    })(rows[i]);
}


function cound_step(argument){

    var table = document.getElementById('step_table');
    var selectedRow = table.querySelector('.selected');
    var selectedRowData = selectedRow ? selectedRow.cells[0].innerText : null;
    stepid = selectedRowData;
    if(argument == 'del'){
        del_stepid(stepid);
    }

    if(argument =="copy" && stepid != null){
        copy_step(stepid);
    }


    if(argument =="new"){
        var step_count = countrows();
        if(step_count  < 4){
            create_step();
        }

        /*if(step_count > 1){
            //如果不是STEP1 的話 name = downshift_option 需要disabled 
            document.querySelector('[name="downshift_option"]')?.disabled = true;
        }*/
    }

    if(argument =="edit" && stepid != null){
        edit_step(stepid);
    }

}


function edit_step(){

    var jobid = '<?php echo $data['job_id']?>';
    var seqid = '<?php echo $data['seq_id']?>';

    var unit = '<?php echo $data['unit_name']?>';
    var language = getCookie('language');


    if(language == "zh-cn"){
        var torque_title = '目标扭力';
    }else if(language == "zh-tw"){
        var torque_title = '目標扭力';
    }else{
        var torque_title ='Target Torque';
    }


    if(jobid){
        $.ajax({
            url: "?url=Step/search_stepinfo",
            method: "POST",
            data:{ 
                jobid: jobid,
                seqid: seqid,
                stepid:stepid,
            },
            success: function(response) {

                var responseJSON = JSON.stringify(response);
                var cleanString = responseJSON.replace(/Array|\\n/g, '');
                var cleanString = cleanString.substring(2, cleanString.length - 2);



                var [, jobid] = cleanString.match(/\[job_id]\s*=>\s*([^ ]+)/) || [, null];
                var [, seqid] = cleanString.match(/\[seq_id]\s*=>\s*([^ ]+)/) || [, null];
                var [, step_id] = cleanString.match(/\[step_id]\s*=>\s*([^ ]+)/) || [, null];
                var [, hi_torque] = cleanString.match(/\[hi_torque]\s*=>\s*([^ ]+)/) || [, null];
                var [, lo_torque] = cleanString.match(/\[lo_torque]\s*=>\s*([^ ]+)/) || [, null];
                var [, hi_angle] = cleanString.match(/\[hi_angle]\s*=>\s*([^ ]+)/) || [, null];
                var [, lo_angle] = cleanString.match(/\[lo_angle]\s*=>\s*([^ ]+)/) || [, null];
                var [, rpm] = cleanString.match(/\[rpm]\s*=>\s*([^ ]+)/) || [, null];
                var [, downshift_speed] = cleanString.match(/\[downshift_speed]\s*=>\s*([^ ]+)/) || [, null];
                var [, downshift_torque] = cleanString.match(/\[downshift_torque]\s*=>\s*([^ ]+)/) || [, null];
                var [, threshold_torque] = cleanString.match(/\[threshold_torque]\s*=>\s*([^ ]+)/) || [, null];
                var [, target_option] = cleanString.match(/\[target_option]\s*=>\s*([^ ]+)/) || [, null];
                var [, target_torque] = cleanString.match(/\[target_torque]\s*=>\s*([^ ]+)/) || [, null];
                var [, target_angle] = cleanString.match(/\[target_angle]\s*=>\s*([^ ]+)/) || [, null];
                var [, target_delaytime] = cleanString.match(/\[target_delaytime]\s*=>\s*([^ ]+)/) || [, null];
                var [, direction] = cleanString.match(/\[direction]\s*=>\s*([^ ]+)/) || [, null];
                var [, downshift] = cleanString.match(/\[downshift]\s*=>\s*([^ ]+)/) || [, null];
                var [, check_count] = cleanString.match(/\[check_count]\s*=>\s*([^ ]+)/) || [, null];
                var [, downshift_speed] = cleanString.match(/\[downshift_speed]\s*=>\s*([^ ]+)/) || [, null];
               

                document.getElementById('editstep').style.display = 'block';
                document.getElementById("edit_hi_torque").value = hi_torque;
                document.getElementById("edit_lo_torque").value = lo_torque;
                document.getElementById("edit_hi_angle").value = hi_angle;
                document.getElementById("edit_lo_angle").value = lo_angle;
                document.getElementById("edit_rpm").value = rpm;
                document.getElementById("edit_downshift_speed").value = downshift_speed;
                document.getElementById("edit_downshift_torque").value = downshift_torque;
                document.getElementById("edit_downshift_threshold").value = threshold_torque;
                document.querySelector("select[name='edit_target_option']").value = target_option;

                document.getElementById("edit_target_delaytime").value = target_delaytime;
                document.getElementById("edit_target_torque").value = target_torque;
                document.getElementById("edit_target_angle").value = target_angle;


                var radioButtons1 = document.getElementsByName("edit_direction_option");
                var radioButtons2 = document.getElementsByName("edit_downshift_option");

                setRadioButtonValue(radioButtons1, direction);
                setRadioButtonValue(radioButtons2, downshift);

                //判斷有其他的step 選了 扭力 
                //target_option的下拉式選單 就會把 扭力 移除
                if(target_option != 0 && check_count != 0){
                    
                    var selectElement = document.getElementById('mySelect');
                    var selectElement = document.querySelector('select[name="edit_target_option"]');

                    for(var i = selectElement.options.length - 1; i >= 0; i--) {
                        var option = selectElement.options[i];
                        if (option.value === '0') { 
                            selectElement.remove(i);
                        }
                    }
                }else{
                    var selectElement = document.getElementById('mySelect');
                    var selectElement = document.querySelector('select[name="edit_target_option"]');
                }

                if(target_option == 2){
                    document.querySelector('div[for="edit_target-torque"]').textContent = '<?php echo $text['Target Delay Time']?>';
                    document.getElementById('edit_hi_torque').disabled = true; 
                    document.getElementById('edit_lo_torque').disabled = true; 
                    document.getElementById('edit_hi_angle').disabled = true; 
                    document.getElementById('edit_lo_angle').disabled = true; 
                    document.getElementById('edit_rpm').disabled = true; 
                    document.getElementById('edit_downshift_threshold').disabled = true; 
                    document.getElementById('edit_downshift_torque').disabled = true; 
                    document.getElementById('edit_downshift_speed').disabled = true; 

                    document.querySelectorAll('input[name="edit_direction_option"]').forEach(function(radioButton) {
                                radioButton.disabled = true;
                    });

                    document.querySelectorAll('input[name="edit_downshift_option"]').forEach(function(radioButton) {
                                radioButton.disabled = true;
                    });
                }

                //扭力
                if(target_option == 0){
                    document.getElementById('edit_target-torque_title').style.display = 'block';
                    document.getElementById('edit_target-torque_val').style.display = 'block';
                    document.getElementById('edit_target_torque').value = target_torque;  
                }

                //角度
                if(target_option == 1){
                    document.getElementById('edit_target-angle_title').style.display = 'block';
                    document.getElementById('edit_target-angle_val').style.display = 'block';
                    document.getElementById('edit_target_angle').value = target_angle; 
                }

                //delay time 
                if(target_option == 2){
                    document.getElementById('edit_target-delaytime_title').style.display = 'block';
                    document.getElementById('edit_target-delaytime_val').style.display = 'block';
                    document.getElementById("edit_target_delaytime").value = target_delaytime;
                }


                if(target_option == 0){
                    var name = '<?php echo $text['Target_Torque']?>';
                    const unitTranslations = {
                        "zh-cn": {
                            "kgf.cm": "公斤公分",
                            "kgf.m": "公斤米",
                            "N.m": "牛頓米",
                            "default": "英磅英吋"
                        },
                        "zh-tw": {
                            "kgf.cm": "公斤公分",
                            "kgf.m": "公斤米",
                            "N.m": "牛頓米",
                            "default": "英磅英吋"
                        }
                        
                    };

                    if (language === "zh-cn" || language === "zh-tw") {
                        unit = unitTranslations[language][unit] || unitTranslations[language]["default"];
                    } 

                    document.getElementById('edit_target-torque_title').style.display = 'block';
                    document.querySelector('div[for="edit_target-torque"]').textContent = torque_title + "(" + unit + ")" ;
                }

                if(downshift != 0 ){
                    document.getElementById('edit_downshift_threshold_title').style.display = "none";
                    document.getElementById('edit_downshift_threshold_item').style.display = "none";
                    document.getElementById('edit_downshift_torque_title').style.display = "none";
                    document.getElementById('edit_downshift_torque_item').style.display = "none";
                    document.getElementById('edit_downshift_speed_title').style.display = "none";
                    document.getElementById('edit_downshift_speed_item').style.display = "none";

                }

                var target_option = document.getElementById("edit_target_option");
                target_option.addEventListener('change', function() {
                var selectedValue = this.value;
                
                    if (selectedValue == 2) {
                        var elementsToDisable = [
                            document.getElementById('edit_hi_torque'),
                            document.getElementById('edit_lo_torque'),
                            document.getElementById('edit_hi_angle'),
                            document.getElementById('edit_lo_angle'),
                            document.getElementById('edit_rpm'),
                            document.getElementById('edit_downshift_speed'),
                            document.getElementById('edit_downshift_threshold'),
                            document.getElementById('edit_downshift_speed'),
                            document.getElementById('edit_downshift_torque')
                        ];

                        disableElements(elementsToDisable, true);

                        document.querySelectorAll('input[name="edit_direction_option"]').forEach(function(radioButton) {
                            radioButton.disabled = true;
                        });

                        document.querySelectorAll('input[name="edit_downshift_option"]').forEach(function(radioButton) {
                            radioButton.disabled = true;
                        });
                

                        document.querySelector('div[for="edit_target-torque"]').textContent = '<?php echo $text['Target Delay Time']?>';
                    } 
                    
                    if (selectedValue == 1 || selectedValue == 0) {

                        document.getElementById('edit_hi_torque').disabled = false;
                        document.getElementById('edit_hi_torque').value = hi_torque;

                        document.getElementById('edit_lo_torque').disabled = false;
                        document.getElementById('edit_lo_torque').value = lo_torque;

                        document.getElementById('edit_hi_angle').disabled = false;
                        document.getElementById('edit_hi_angle').value = hi_angle;

                        document.getElementById('edit_lo_angle').disabled = false;
                        document.getElementById('edit_lo_angle').value = lo_angle;

                        document.getElementById('edit_rpm').disabled = false;  
                        document.getElementById('edit_rpm').value = rpm;

                        document.getElementById('edit_downshift_threshold').disabled = false; 
                        document.getElementById('edit_downshift_threshold').value = threshold_torque;

                        document.getElementById('edit_downshift_speed').disabled = false; 
                        document.getElementById('edit_downshift_speed').value = downshift_speed;


                        document.getElementById('edit_downshift_torque').disabled = false; 
                        document.getElementById('edit_downshift_torque').value = downshift_torque;


                        document.querySelectorAll('input[name="edit_direction_option"]').forEach(function(radioButton) {
                            radioButton.disabled = false;
                        });

                        document.querySelectorAll('input[name="edit_downshift_option"]').forEach(function(radioButton) {
                            radioButton.disabled = false;
                        });

                    }


                    if(selectedValue == 0){

                        document.getElementById('edit_target_torque_title').style.display = 'block';
                        document.getElementById('edit_target_torque_val').style.display = 'block';

                        document.getElementById('edit_target-angle_title').style.display = 'none';
                        document.getElementById('edit_target-angle_val').style.display = 'none';

                        document.getElementById('edit_target-delaytime_title').style.display = 'none';
                        document.getElementById('edit_target-delaytime_val').style.display = 'none';
                        document.querySelector('div[for="edit_target-torque"]').textContent = '<?php echo $text['Target_Torque'] ?>' + "(" + unit + ")" ;

                    }


                    if(selectedValue == 1){

                        document.getElementById('edit_target_torque_title').style.display = 'none';
                        document.getElementById('edit_target_torque_val').style.display = 'none';
                        document.getElementById('edit_target_angle_title').style.display = 'block';
                        document.getElementById('edit_target_angle_val').style.display = 'block';
                        document.getElementById('edit_target_delaytime_title').style.display = 'none';
                        document.getElementById('edit_target_delaytime_val').style.display = 'none';

                    }


                    if(selectedValue == 2){

                        document.getElementById('edit_target-torque_title').style.display = 'none';
                        document.getElementById('edit_target-torque_val').style.display = 'none';

                        document.getElementById('edit_target-angle_title').style.display = 'none';
                        document.getElementById('edit_target-angle_val').style.display = 'none';

                        document.getElementById('edit_target-delaytime_title').style.display = 'block';
                        document.getElementById('edit_target-delaytime_val').style.display = 'block';

                    }


                
                });


                var downshiftOptionRadios = document.getElementsByName("edit_downshift_option");
                for (var i = 0; i < downshiftOptionRadios.length; i++) {
                    downshiftOptionRadios[i].addEventListener("change", function() {
                        var selectval = this.value;
                        localStorage.setItem('downshift_option',selectval);
                        if(selectval == 1){
                            document.querySelector('div[for="edit_downshift-torque"]').style.display = "block";
                            document.getElementById('edit_downshift_torque').style.display = "block";

                            document.querySelector('div[for="edit_downshift-threshold"]').style.display = "block";
                            document.getElementById('edit_downshift_threshold').style.display = "block";

                            document.querySelector('div[for="edit_downshift-speed"]').style.display = "block";
                            document.getElementById('edit_downshift_speed').style.display = "block";
                        }else{
                            document.querySelector('div[for="edit_downshift-torque"]').style.display = "none";
                            document.getElementById('edit_downshift_torque').style.display = "none";

                            document.querySelector('div[for="edit_downshift-threshold"]').style.display = "none";
                            document.getElementById('edit_downshift_threshold').style.display = "none";

                            document.querySelector('div[for="edit_downshift-speed"]').style.display = "none";
                            document.getElementById('edit_downshift_speed').style.display = "none";
                        }
                    
                    });
                }

            },
            error: function(xhr, status, error) {
                
            }
        });

    }

}

function edit_step_save() {

    var jobid = '<?php echo $data['job_id']?>';
    var seqid = '<?php echo $data['seq_id']?>';
   

    var target_option = document.getElementById("edit_target_option").value;

    var target_torque = 0;
    var target_angle = 0;
    var target_delaytime = 0;
    var hi_torque = 0;
    var lo_torque = 0;
    var hi_angle = 0;
    var lo_angle = 0;
    var rpm = 0;
    var direction = 0;
    var downshift = 0;
    var threshold_torque = 0;
    var downshift_torque = 0;
    var downshift_speed = 0;

    if(target_option == 2) {
        target_delaytime = document.getElementById("edit_target_delaytime").value;
    }else if(target_option == 1) {
        target_angle = document.getElementById("edit_target_angle").value;
    }else{
        target_torque = document.getElementById("edit_target_torque").value;
    }      


    hi_torque = document.getElementById("edit_hi_torque").value;
    lo_torque = document.getElementById("edit_lo_torque").value;
    hi_angle = document.getElementById("edit_hi_angle").value;
    lo_angle = document.getElementById("edit_lo_angle").value;
    rpm = document.getElementById("edit_rpm").value;
    direction = document.querySelector('input[name="edit_direction_option"]:checked').value;
    downshift = document.querySelector('input[name="edit_downshift_option"]:checked').value;
    threshold_torque = document.getElementById("edit_downshift_threshold").value;
    downshift_torque = document.getElementById("edit_downshift_torque").value;
    downshift_speed = document.getElementById("edit_downshift_speed").value;


    var requestData = {
        jobid: jobid,
        seqid: seqid,
        stepid: stepid,
        target_option: target_option,
        target_torque: target_torque,
        target_angle: target_angle,
        target_delaytime: target_delaytime,
        hi_torque: hi_torque,
        lo_torque: lo_torque,
        hi_angle: hi_angle,
        lo_angle: lo_angle,
        rpm: rpm,
        direction: direction,
        downshift: downshift,
        threshold_torque: threshold_torque,
        downshift_torque: downshift_torque,
        downshift_speed: downshift_speed
    };

    if (target_option) {
        $.ajax({
            url: "?url=Step/edit_step",
            method: "POST",
            data: requestData,
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



function create_step() {

    document.getElementById('newstep').style.display = 'block';

    document.getElementById('rpm').value = 200;
    document.getElementById('th_tor').value = 0;
    document.getElementById('ds_tor').value = 0;
    document.getElementById('ds_speed').value =100;

    var targetoptionselect = document.getElementById('target_opt');
    targetoptionselect.addEventListener('change', function() {

    var target_opt_Value = targetoptionselect.value;
        localStorage.setItem('target_option', target_opt_Value);
        toggleVisibility(target_opt_Value);
    });

   

}
   
function add_step(){

    var target_option = document.getElementById('target_opt').value;
    var target_tor    = document.getElementById('target_tor').value;
    var target_ang    = document.getElementById('target_ang').value;
    var target_delay  = document.getElementById('target_delay').value;
    var tor_hi        = document.getElementById('tor_hi').value;
    var tor_lo        = document.getElementById('tor_lo').value;
    var ang_hi        = document.getElementById('ang_hi').value;
    var ang_lo        = document.getElementById('ang_lo').value;
    var rpm           = document.getElementById('rpm').value;
    var direction     = document.querySelector('input[name="direction_option"]:checked').value;
    var ds_mode       = document.querySelector('input[name="ds_mode"]:checked').value;
    var th_tor        = document.getElementById('th_tor').value;
    var ds_tor        = document.getElementById('ds_tor').value;
    var ds_speed      = document.getElementById('ds_speed').value;
    var record_ang    = 0; //紀錄 累計角度(DB有這個欄位,但是SA並沒有)

    //驗證 
    let check = input_check_savestep();
    if(check){

    }
    //alert(direction_opt);

    /*var target_option = document.getElementById('target_opt').value;

    
    var target_torque = document.getElementById('target_torque').value;

    var hi_torque = document.getElementById('hi_torque').value;
    var lo_torque = document.getElementById('lo_torque').value;

    var hi_angle = document.getElementById('hi_angle').value;
    var lo_angle = document.getElementById('lo_angle').value;
    var rpm = document.getElementById('rpm').value;

    var threshold_torque = document.getElementById('downshift_threshold').value;
    var downshift_torque = document.getElementById('downshift_torque').value;
    var downshift_speed  = document.getElementById('downshift_speed').value;

    var direction = document.querySelector('input[name="direction_option"]:checked').value;
    var downshift = document.querySelector('input[name="downshift_option"]:checked').value;


    //驗證
    let check = input_check_savestep();

    if(check){

        $.ajax({
            url: "?url=Step/create_step",
            method: "POST",
            data:{ 
                jobid: jobid,
                seqid: seqid,
                stepid: stepid,
                target_option: target_option,
                target_torque: target_torque,
                hi_torque: hi_torque,
                lo_torque: lo_torque,
                hi_angle: hi_angle,
                lo_angle: lo_angle,
                rpm: rpm,
                direction: direction,
                downshift: downshift,
                threshold_torque: threshold_torque,
                downshift_torque: downshift_torque,
                downshift_speed: downshift_speed

            },
            success: function(response) {
                //console.log(response);
                var responseData = JSON.parse(response);
                alertify.alert(responseData.res_type, responseData.res_msg, function() {
                    history.go(0);
                });
            },
            error: function(xhr, status, error) {
                
            }
        });
    }*/
}



function copy_step_by_id(){
    var jobid = '<?php echo $data['job_id']?>';
    var seqidnew = '<?php echo $data['stepid_new']?>';
    
    document.getElementById('from_step_id').value = stepid;    
    document.getElementById("to_step_id").value = seqidnew;


}

function copy_step_by_id_ajax(){
    //var stepid = readFromLocalStorage("stepid");
    var jobid = '<?php echo $data['job_id']?>';
    var seqid = '<?php echo $data['seq_id']?>';
    var stepid_new  = '<?php echo $data['stepid_new']?>';
    
    if(stepid_new){
        $.ajax({
            url: "?url=Step/copy_step",
            method: "POST",
            data:{ 
                jobid: jobid,
                seqid: seqid,
                stepid:stepid,
                stepid_new: stepid_new
            },
            success: function(response) {
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


function del_stepid(step_id){
    var jobid = '<?php echo $data['job_id']?>';
    var seqid = '<?php echo $data['seq_id']?>';
    if(stepid) {
        $.ajax({
            url: "?url=Step/delete_step",
            method: "POST",
            data:{ 
                stepid:stepid,
                jobid:jobid,
                seqid:seqid
            },
            success: function(response) {
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

var rowInfoArray = [];
<?php foreach($data['step'] as $key =>$val) {?>
        var jobid = "<?php echo $val['job_id'];?>";
        var sequenceId = "<?php echo $val['seq_id'];?>";
        var stepid = "<?php echo $val['step_id'];?>";
      
        
        var rowInfo = {
            job_id: jobid,
            sequence_id: sequenceId,
            step_id: stepid,
        };
        
        rowInfoArray.push(rowInfo);
<?php } ?>

function sendRowInfoArray() {
    var jobid = '<?php echo $data['job_id']?>';
    var dataToSend = {
        jobid: jobid,
        rowInfoArray: rowInfoArray
    };
 
    if(rowInfoArray){

        $.ajax({
            url: "?url=Step/adjustment_order", 
            method: "POST",
            data: dataToSend,
            success: function(response) {
                history.go(0); 
            },
            error: function(xhr, status, error) {
                console.error('Error sending data:', error);
            }
        });
    }
}

function countrows() {
    var tbody = document.querySelector('#step_table tbody');
    var rows = tbody.querySelectorAll('tr');
    var rowCount = rows.length;

    return rowCount;
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

function input_check_savestep(){

    let target_opt = document.getElementById("target_opt").value;
    let Tool_Max_Torque = parseFloat(document.getElementById('tool_max_torque').value);
    let Tool_Min_Torque = parseFloat(document.getElementById('tool_min_torque').value);
    let Tool_Max_RPM = document.getElementById('tool_max_rpm').value;
    let Tool_Min_RPM = document.getElementById('tool_min_rpm').value;
    let hi_angle_max = 9999;
    let hi_angle_min = 1;

    let conditions  = [];
    if(target_opt == 0){
        //需要驗證 target_tor
        conditions = [
            { id: 'target_tor', pattern: /^\d{1,5}(\.\d{1})?$/, min: Tool_Min_Torque, max: Tool_Max_Torque }
        ];

    }


    if(target_opt == 1){
        //需要驗證 target_ang
        conditions = [
            { id: 'target_ang', pattern: /^\d{0,5}?$/, min: 1, max: 9999 }
        ];

    }


    if(target_opt == 2){
        //需要驗證 target_delay
        conditions = [
            { id: 'target_delay', pattern: /^\d{0,5}?$/, min: 0.1, max: 9.9 }
        ];

    }
    //unscrew_torque_threshold
    conditions = [
        { id: 'rpm', pattern: /^\d{0,4}$/, min: Tool_Min_RPM, max: Tool_Max_RPM },
    ];

    let isFormValid = true;
    conditions.forEach(function(input) {
        var element = document.getElementById(input.id);
        if (input.id !== 'target_opt') {
            let nextSibling = element.nextElementSibling;
            if (nextSibling) {
                nextSibling.innerHTML = `${rangeLabel} ${input.min} ~ ${input.max}`;
            } else {
                console.warn(`No next sibling found for element with id ${input.id}`);
            }
        }
        if (!validateInput(element, input.pattern, input.min, input.max)) {
            isFormValid = false;
        }

    });


return true;




return isFormValid;

} 

function input_check_savestep11() {

    let target_opt = document.getElementById("target_opt").value;
    let Tool_Max_Torque = parseFloat(document.getElementById('tool_max_torque').value);
    let Tool_Min_Torque = parseFloat(document.getElementById('tool_min_torque').value);
    let Tool_Max_RPM = document.getElementById('tool_max_rpm').value;
    let Tool_Min_RPM = document.getElementById('tool_min_rpm').value;
    let hi_angle_max = 9999;
    let hi_angle_min = 1;

    //let StepOption = document.getElementById("StepOption").value;
    //let Target_Torque_value = document.getElementById('StepTorque').value;
    //let delta = Number.parseFloat(Tool_Min_Torque * 0.05).toFixed(4);

    //let  StepEnableThreshold = document.querySelector('input[name="StepEnableThreshold"]:checked');

        /*if(StepEnableThreshold.value == 0){
            Tool_Max_Torque = 0;
            Tool_Min_Torque = 0;
        }*/
        //alert(Tool_Min_Torque);
 
        if(StepOption ==0){

            //torque
            /*et offset_max = 0;
            if( parseFloat(Tool_Max_Torque*1.08 - Target_Torque_value).toFixed(2) >= parseFloat(Target_Torque_value*0.3).toFixed(4) ){
                offset_max = parseFloat(Target_Torque_value*0.3).toFixed(4);
            }else{
                offset_max = parseFloat(Tool_Max_Torque*1.08 - Target_Torque_value).toFixed(2);
            }
            let offset_min = 0;
            let aa = Number.parseFloat(Tool_Min_Torque*0.7 - Target_Torque_value ).toFixed(4);
            let bb = Number.parseFloat(Target_Torque_value*0.3).toFixed(4);
            if( aa >= -bb ){
                offset_min = aa;
            }else{
                offset_min = -bb;
            }


            hi_angle_max = 30600
            hi_angle_min = 0;
            //lo_angle_max =  document.getElementById('StepHiAngle').value;
            lo_angle_max = 0;
            lo_angle_min = 0;
            hi_torque_max = Number.parseFloat(Tool_Max_Torque*1.1).toFixed(4);
            hi_torque_min = Number.parseFloat( parseFloat(Target_Torque_value) + parseFloat(delta) ).toFixed(4);
            lo_torque_max = Number.parseFloat( parseFloat(Target_Torque_value) - parseFloat(delta) ).toFixed(4);
            lo_torque_min = 0;
            join_offset_max = offset_max;
            join_offset_min = offset_min;
            torque_threshold_max = document.getElementById('StepTorque').value;
            torque_threshold_min = 0;
            downshift_torque_max = document.getElementById('StepTorque').value;
            downshift_torque_min = 0;
            downshift_speed_max = document.getElementById('StepRPMDownShift').value;
            downshift_speed_min = Tool_Min_RPM;

            angle_threshold_max = 30600;
            angle_threshold_min = 0;
            downshift_angle_max = 30600;
            downshift_angle_min = 0;*/

        }else if(StepOption ==1){

            //Angle 
            let offset_max = Number.parseFloat(Tool_Max_Torque*0.3).toFixed(4);
            let offset_min = parseFloat(Tool_Min_Torque*0.7 - document.getElementById('StepHiTorque').value ).toFixed(4);
            if(Math.abs(offset_min) > Math.abs(offset_max)){
                offset_min = offset_max * -1;
            }

 

           

        }else{

            //time
            let offset_max = 99999;
            let offset_min = 0;
  

         

        }

        let conditions  = [];
        if (StepOption == 0) {
        // StepOption == 0: 需要驗證 StepTorque，不需要驗證 StepAngle 和 StepTime
            conditions = [
                { id: 'StepTorque', pattern: /^\d{1,5}(\.\d{1})?$/, min: Tool_Min_Torque, max: Tool_Max_Torque }
            ];
        } else if (StepOption == 1) {
            // StepOption == 1: 需要驗證 StepAngle，不需要驗證 StepTorque 和 StepTime
            conditions = [
                { id: 'StepAngle', pattern: /^\d{0,5}?$/, min: 1, max: 30600 }
            ];
        } else if (StepOption == 2) {
            // StepOption == 2: 需要驗證 StepTime，不需要驗證 StepTorque 和 StepAngle
            conditions = [
                { id: 'StepTime', pattern: /^\d{0,5}?$/, min: 0, max: 20 }
            ];
        }

        //unscrew_torque_threshold
         conditions = [
            { id: 'STEPname', pattern: /^[a-zA-Z0-9\u4E00-\u9FA5\-]+$/, min: null, max: null },
            { id: 'StepDelay', pattern: /^\d{0,4}$/, min: 0, max: 2000 }, 
            { id: 'StepRPM', pattern: /^\d{0,4}$/, min: Tool_Min_RPM, max: Tool_Max_RPM },
            { id: 'k_value', pattern: /^(0(\.\d{1,2})?|1(\.\d{2})?|2(\.([0-4]{1}[0-9]{1}|50)))$/, min: 0, max: 2.50 },  
            { id: 'StepRPMDownShift',pattern: /^\d{0,4}$/, min: Tool_Min_RPM, max: Tool_Max_RPM},
            //{ id: 'StepTorqueTS', pattern: /^\d{0,4}(\.\d{1})?$/, min: Tool_Min_Torque, max: Tool_Max_Torque },20250113 暫時disabled
            //{ id: 'StepTorqueDownShift', pattern: /^\d{0,4}(\.\d{1})?$/, min: Tool_Min_Torque, max: Tool_Max_Torque },20250113暫時disabled
            { id: 'StepHiTorque',pattern: /^\d{0,6}(\.\d{0,4})?$/, min: lo_torque_min, max: hi_torque_max },
            { id: 'StepLoTorque',pattern: /^\d{0,6}(\.\d{0,4})?$/, min: lo_torque_min, max: hi_torque_max },
            { id: 'StepHiAngle', pattern: /^\d{0,5}?$/, min: hi_angle_min, max: 30600 },
            { id: 'StepLoAngle', pattern: /^\d{1,6}$/, min: lo_angle_min, max: lo_angle_max },
            { id: 'StepLimiHi',pattern: /^\d{0,3}$/, min: 0, max:100 },
            { id: 'StepLimiLo',pattern: /^\d{0,3}$/, min: 0, max:100 },
            //{ id: 'StepTorque', pattern: /^\d{1,5}(\.\d{1})?$/, min: Tool_Min_Torque, max: Tool_Max_Torque },
            //{ id: 'StepAngle', pattern: /^\d{0,5}?$/, min: 1, max: 30600 },
            //{ id: 'StepTime', pattern: /^\d{0,5}?$/, min: 0, max: 20 },
   
        ];

        if (StepOption == 0) {
            // 當 StepOption == 0 時，不需要驗證 StepAngle 和 StepTime
            conditions.push(
                { id: 'StepTorque', pattern: /^\d{1,5}(\.\d{1})?$/, min: Tool_Min_Torque, max: Tool_Max_Torque },
              
            );
        } else if (StepOption == 1) {
            // 保留所有欄位驗證
            conditions.push(
                { id: 'StepAngle', pattern: /^\d{0,5}?$/, min: 1, max: 30600 },
            );
        } else if (StepOption == 2) {
            // 保留所有欄位驗證
            conditions.push(
                { id: 'StepTime', pattern: /^\d{0,5}?$/, min: 0, max: 20 },
            );
        }


        let isFormValid = true;
        let errorMessages = []; 
        conditions.forEach(function(input) {
            var element = document.getElementById(input.id);
            var value = element.value.trim();

 
            if(input.id != 'STEPname'){
                var nextSibling = element.nextElementSibling;
                if (nextSibling) {
                    nextSibling.innerHTML = input.min + ' ~ ' + input.max;
                }
            }

            if (value === "") {
                element.classList.add("is-invalid");
                errorMessages.push(input.message); // 儲存錯誤訊息
                isFormValid = false;
            } else if (!input.pattern.test(value)) {
                element.classList.add("is-invalid");
                errorMessages.push(input.message); // 儲存錯誤訊息
                isFormValid = false;
            } else if (input.min !== null && parseFloat(value) < input.min) {
                element.classList.add("is-invalid");
                errorMessages.push(input.message); // 儲存錯誤訊息
                isFormValid = false;
            } else if (input.max !== null && parseFloat(value) > input.max) {
                element.classList.add("is-invalid");
                errorMessages.push(input.message); // 儲存錯誤訊息
                isFormValid = false;
            } else {
                element.classList.remove("is-invalid");
            }

        });

        
        console.log(isFormValid);
        if (!isFormValid) {
            //alert("以下欄位有錯誤：\n" + errorMessages.join("\n"));
            return false;  // 只要有錯誤，回傳 false
        }

        return true;



    return isFormValid;
}


</script>
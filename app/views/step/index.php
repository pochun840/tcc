
<div class="container-ms">
    <div class="w3-text-white w3-center">
        <table class="no-border">
            <tr id="header">
                <td width="100%"><h3><?php echo $text['step_management']; ?></h3></td>
             
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
                <table id="step_table" class="table w3-table">
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
                            <td><?php echo $text[$data['target_option'][$val['target_opt']]];?></td>
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

    <div style="display:none;">
        <input id="tool_max_tor" value="<?php echo $data['tools']['tool_maxtorque']; ?>">
        <input id="tool_min_tor" value="<?php echo $data['tools']['tool_mintorque']; ?>">
        <input id="tool_max_rpm" value="<?php echo $data['tools']['tool_maxrpm']; ?>">
        <input id="tool_min_rpm" value="<?php echo $data['tools']['tool_minrpm']; ?>">
        <input id="tool_max_tor_diff" value="<?php echo $data['tools']['tool_maxtorque_diff']; ?>">
        <input id="tool_min_tor_diff" value="<?php echo $data['tools']['tool_mintorque_diff']; ?>">
        <input id="step_torque_unit" value="<?php echo  $data['step_torque_unit']?>">
    </div>

    <!-- Add New Step -->
    <div id="newstep" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w3-animate-zoom" style="width: 90%">
                <header class="w3-container modal-header">
                    <span onclick="closebutton('newstep');"
                        class="w3-button w3-red w3-display-topright" style="width: 50px; margin: 3px;">&times;</span>
                    <h3 id='modal_title'><?php echo $text['new_step'];?></h3>
                </header>

              

                <div class="modal-body">
                    <form id="new_step_form" style="padding-left: 5%">
                        <div class="row">
                            <div for="target-option" class="col-6 t1"><?php echo $text['step_target_type'];?> :</div>
                            <div class="col-4 t2">
                                <input type="text" id='step_id' name='step_id' value='<?php echo $data['count_records'];?>' style="display: none;">
                                <select id="target_opt" name="target_opt" class="custom-file" style="width:225px">
                                    <?php if($data['check'][0]['count_records'] == 1 || $data['counts_torque'] == 1){?>
                                        <?php foreach($data['target_option_change'] as $key => $val){?>
                                             <option value="<?php echo $key;?>"><?php echo $text[$val];?></option>
                                        <?php }?>   

                                    <?php } else {?>
                                        <?php foreach($data['target_option'] as $key => $val){?>
                                             <option value="<?php echo $key;?>"><?php echo $text[$val];?></option>
                                        <?php }?>     
                                    <?php } ?>

                                    <?php 
                                    
                                    ?>
                                   
                                    
                                </select>
                            </div>
                        </div>

                        <div id='target_tor_item' style="display:block;">
                            <div class="row">
                                <div  class="col-6 t1"><?php echo $text['Target_Torque'];?> (<?php echo $text[$data['unit_name']];?>):</div>
                                <div class="col-4 t2">
                                    <input type="text" class="form-control input-ms" id="target_tor" maxlength="">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div id='target_ang_item' style="display:none;">                     
                            <div class="row">
                                <div class="col-6 t1"><?php echo $text['Target_Angle'];?> :</div>
                                <div class="col-4 t2">
                                    <input type="text" class="form-control input-ms" id="target_ang" maxlength="" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div id='target_delay_item' style="display:none;">       
                            <div class="row">
                                    <div for="target-torque" class="col-6 t1"><?php echo $text['Target Delay Time'];?> :</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="target_delay" maxlength="" >
                                        <div class="invalid-feedback"></div>
                                    </div>
                            </div>
                        </div>

                        <div id="tor_hi_item">                   
                            <div class="row">
                                <div for="hi-torque" class="col-6 t1"><?php echo $text['High_Torque'];?> (<?php echo $text[$data['unit_name']];?>):</div>
                                <div class="col-4 t2">
                                    <input type="text" class="form-control input-ms" id="tor_hi" maxlength="" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div id="tor_lo_item">
                            <div class="row">
                                <div for="lo-torque" class="col-6 t1"><?php echo $text['Low_Torque'];?> (<?php echo $text[$data['unit_name']];?>):</div>
                                <div class="col-4 t2">
                                    <input type="text" class="form-control input-ms" id="tor_lo" maxlength="" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div id="ang_hi_item">
                            <div class="row">
                                <div for="hi-angle" class="col-6 t1"><?php echo $text['High_Angle'];?> :</div>
                                <div class="col-4 t2">
                                    <input type="text" class="form-control input-ms" id="ang_hi" maxlength="" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div id="ang_lo_item">
                            <div class="row">
                                <div for="lo-angle" class="col-6 t1"><?php echo $text['Low_Angle'];?>:</div>
                                <div class="col-4 t2">
                                    <input type="text" class="form-control input-ms" id="ang_lo" maxlength="" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div id="rpm_item">
                            <div class="row">
                                <div for="RPM" class="col-6 t1"><?php echo $text['rpm'];?>:</div>
                                <div class="col-4 t2">
                                    <input type="text" class="form-control input-ms" id="rpm" maxlength="" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div id="direction_item">
                            <div class="row">
                                <div for="direction" class="col-6 t1"><?php echo $text['direction'];?>:</div>
                                <div class="col t2" >
                                    <div class="col-4 form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="direction_option" id="direction_CW" value="0">
                                    <label class="form-check-label" for="direction_CW"><?php echo $text['CW'];?></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="direction_option" id="direction_CCW" value="1">
                                    <label class="form-check-label" for="direction_CCW"><?php echo $text['CCW'];?></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="th_mode_item">
                            <div class="row">
                                <div for="downshift" class="col-6 t1"><?php echo $text['Downshift'];?>:</div>
                                <div class="col t2" >
                                    <div class="col-4 form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="th_mode" id="downshift_OFF" value="0">
                                    <label class="form-check-label" for="downshift_OFF"><?php echo $text['switch_off'];?></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="th_mode" id="downshift_ON" value="1" >
                                    <label class="form-check-label" for="downshift_ON"><?php echo $text['switch_on'];?></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id='th_tor_item'>
                            <div class="row">
                                <div id="downshift_threshold_title" for="th_tor" class="col-6 t1"><?php echo $text['Threshold_Torque'];?>(<?php echo $text[$data['unit_name']];?>):</div>
                                <div class="col-4 t2" id="downshift_threshold_item"> 
                                    <input type="text" class="form-control input-ms" id="th_tor" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div id='ds_tor_item'>
                            <div class="row" >
                                <div id="downshift_torque_title" for="ds_tor" class="col-6 t1"><?php echo $text['Downshift_Torque'];?>(<?php echo $text[$data['unit_name']];?>):</div>
                                <div class="col-4 t2" id="downshift_torque_item">
                                    <input type="text" class="form-control input-ms" id="ds_tor" maxlength="" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div id='ds_speed_item'>
                            <div class="row" >
                                <div id="downshift_speed_title" for="downshift-speed" class="col-6 t1"><?php echo $text['Downshift_Speed'];?>:</div>
                                <div class="col-4 t2" id="downshift_speed_item">
                                    <input type="text" class="form-control input-ms" id="ds_speed" maxlength="" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <input type='hidden' id='step_torque_unit' name='step_torque_unit' value='<?php echo $data['step_torque_unit'];?>'>
                    <button id="" class="button-modal" onclick="add_step()" ><?php echo $text['save'];?></button>
                    <button id="" class="button-modal" onclick="closebutton('newstep');"  class="closebtn"><?php echo $text['close'];?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- edit Step -->
    <div id="editstep" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w3-animate-zoom" style="width: 80%">
                <header class="w3-container modal-header">
                    <span onclick="closebutton('editstep');"
                        class="w3-button w3-red w3-display-topright" style="width: 50px; margin: 3px;">&times;</span>
                    <h3 id='modal_title'><?php echo $text['edit_step'];?></h3>
                </header>

                <div class="modal-body">
                    <form id="new_step_form" style="padding-left: 5%">
                        <div class="row">
                            <div for="target-option" class="col-6 t1"><?php echo $text['step_target_type'];?> :</div>
                            <input type="text" id='edit_step_id' name='edit_step_id' style="display: none;">
                            <div class="col-4 t2">
                                <select id="edit_target_opt" name="edit_target_opt" class="col custom-file" onchange="targetOptChangeHandler()" style="width:225px">
                                    <?php foreach($data['target_option'] as $key => $val){?>
                                        <option value="<?php echo $key;?>"><?php echo $text[$val];?></option>
                                    <?php }?>
                                    
                                </select>
                            </div>
                        </div>

                        
                        <div id='edit_target_tor_item' style="display:block;">
                            <div class="row">
                                <div  class="col-6 t1"><?php echo $text['Target_Torque'];?> (<?php echo $text[$data['unit_name']];?>):</div>
                                <div class="col-4 t2">
                                    <input type="text" class="form-control input-ms" id="edit_target_tor" maxlength="" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div id='edit_target_ang_item' style="display:none;">                     
                            <div class="row">
                                <div class="col-6 t1"><?php echo $text['Target_Angle'];?> :</div>
                                <div class="col-4 t2">
                                    <input type="text" class="form-control input-ms" id="edit_target_ang" maxlength="" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div id='edit_target_delay_item' style="display:none;">       
                            <div class="row">
                                    <div for="target-torque" class="col-6 t1"><?php echo $text['Target Delay Time'];?> :</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="edit_target_delay" maxlength="" >
                                        <div class="invalid-feedback"></div>
                                    </div>
                            </div>
                        </div>

                        <div id="edit_tor_hi_item">                
                            <div class="row">
                                <div for="hi-torque" class="col-6 t1"><?php echo $text['High_Torque'];?> (<?php echo $text[$data['unit_name']];?>):</div>
                                <div class="col-4 t2">
                                    <input type="text" class="form-control input-ms" id="edit_tor_hi" maxlength="" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="edit_tor_lo_item"> 
                            <div class="row">
                                <div for="lo-torque" class="col-6 t1"><?php echo $text['Low_Torque'];?> (<?php echo $text[$data['unit_name']];?>):</div>
                                <div class="col-4 t2">
                                    <input type="text" class="form-control input-ms" id="edit_tor_lo" maxlength="" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div id="edit_ang_hi_item"> 
                            <div class="row">
                                <div for="hi-angle" class="col-6 t1"><?php echo $text['High_Angle'];?>:</div>
                                <div class="col-4 t2">
                                    <input type="text" class="form-control input-ms" id="edit_ang_hi" maxlength="" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div id="edit_ang_lo_item"> 
                            <div class="row">
                                <div for="lo-angle" class="col-6 t1"><?php echo $text['Low_Angle'];?>:</div>
                                <div class="col-4 t2">
                                    <input type="text" class="form-control input-ms" id="edit_ang_lo" maxlength="" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div id="edit_rpm_item">
                            <div class="row">
                                <div for="RPM" class="col-6 t1"><?php echo $text['rpm'];?>:</div>
                                <div class="col-4 t2">
                                    <input type="text" class="form-control input-ms" id="edit_rpm" maxlength="" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div id="edit_direction_item">
                            <div class="row">
                                <div for="direction" class="col-6 t1"><?php echo $text['direction'];?>:</div>
                                <div class="col t2" >
                                    <div class="col-4 form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="edit_direction" id="edit_direction_CW" value="0">
                                    <label class="form-check-label" for="edit_direction_CW"><?php echo $text['CW'];?></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="edit_direction" id="edit_direction_CCW" value="1">
                                    <label class="form-check-label" for="edit_direction_CCW"><?php echo $text['CCW'];?></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="edit_th_mode_item">
                            <div class="row">
                                <div for="edit_th_mode" class="col-6 t1"><?php echo $text['Downshift'];?>:</div>
                                <div class="col t2" >
                                    <div class="col-4 form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="edit_th_mode" id="edit_downshift_OFF" value="0" onchange="toggleThTorDisabled()" >
                                    <label class="form-check-label" for="edit_downshift_OFF"><?php echo $text['switch_off'];?></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="edit_th_mode" id="edit_downshift_ON" value="1" onchange="toggleThTorDisabled()">
                                    <label class="form-check-label" for="edit_downshift_ON"><?php echo $text['switch_on'];?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="edit_th_tor_item">
                            <div class="row">
                                <div class="col-6 t1"><?php echo $text['Threshold_Torque'];?>(<?php echo $text[$data['unit_name']];?>):</div>
                                <div class="col-4 t2">
                                    <input type="text" class="form-control input-ms" id="edit_th_tor" maxlength="" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div id="edit_ds_tor_item">
                            <div class="row">
                                <div class="col-6 t1"><?php echo $text['Downshift_Torque'];?>(<?php echo $text[$data['unit_name']];?>):</div>
                                <div class="col-4 t2" id="edit_downshift_torque_item">
                                    <input type="text" class="form-control input-ms" id="edit_ds_tor" maxlength="" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div id="edit_ds_speed_item">
                            <div class="row">
                                <div class="col-6 t1"><?php echo $text['Downshift_Speed'];?>:</div>
                                <div class="col-4 t2">
                                    <input type="text" class="form-control input-ms" id="edit_ds_speed" maxlength="" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <input type='hidden' id='step_torque_unit' name='step_torque_unit' value='<?php echo $data['step_torque_unit'];?>'>
                    <button id="" class="button-modal" onclick="edit_step_save()" ><?php echo $text['save'];?></button>
                    <button id="" class="button-modal" onclick="closebutton('editstep');"  class="closebtn"><?php echo $text['close'];?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Copy Step -->
    <div id="copystep" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w3-animate-zoom" style="width: 60%">
                <header class="w3-container modal-header">
                    <span onclick="closebutton('copystep');"
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
        				            <input type="number" class="form-control" id="to_step_id" value='<?php echo $data['step_id'];?>'>
        				        </div>
        				    </div>
        			    </div>
        			  </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <button id="copyButton" class="button-modal" onclick="copy_step_by_id_ajax()" ><?php echo $text['save'];?></button>
                    <button id="" class="button-modal" onclick="closebutton('copystep');" class="closebtn"><?php echo $text['close'];?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- 加载動畫 OP -->
        <?php require_once '../app/views/inc/include_spinner.php';?>
    <!-- 加载動畫 ED -->
</div>

<script>

let selected_target_opt_val = '';

let jobid = '<?php echo $data['job_id']?>';
let seqid = '<?php echo $data['seq_id']?>';
let add_stepid = '<?php echo $data['step_id']?>';

let step_torque_unit = '<?php echo $data['step_torque_unit']?>';
let rangeLabel = "<?php echo $error_message['OOR']; ?>"; 
let isProcessing = false; 
let stepid_new  = '<?php echo $data['step_id']?>';
 
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
var check_step_torque = '<?php echo $data['check_step_torque']?>';

var rows = document.getElementsByTagName("tr");
for (var i = 0; i < rows.length; i++) {
    (function(row) {
        var cells = row.getElementsByTagName("td");
        if (cells.length > 0) {
            cells[0].addEventListener("click", function() {
           
                checkstep_id   = cells[0] ? (cells[0].textContent || cells[0].innerText) : null;
                localStorage.setItem("stepid", checkstep_id);
            });
        }
    })(rows[i]);
}



function edit_step(stepid){

    if(jobid){
        $.ajax({
            url: "?url=Step/search_stepinfo",
            method: "POST",
            data:{ 
                job_id: jobid,
                seq_id: seqid,
                step_id:stepid

            },
            success: function(response) {

                var responseJSON = JSON.stringify(response);
                var cleanString = responseJSON.replace(/Array|\\n/g, '');
                var cleanString = cleanString.substring(2, cleanString.length - 2);

                var [, job_id] = cleanString.match(/\[job_id]\s*=>\s*([^ ]+)/) || [, null];
                var [, seq_id] = cleanString.match(/\[seq_id]\s*=>\s*([^ ]+)/) || [, null];
                var [, step_id] = cleanString.match(/\[step_id]\s*=>\s*([^ ]+)/) || [, null];
                var [, target_opt] = cleanString.match(/\[target_opt]\s*=>\s*([^ ]+)/) || [, null];
                var [, target_tor] = cleanString.match(/\[target_tor]\s*=>\s*([^ ]+)/) || [, null];
                var [, target_ang] = cleanString.match(/\[target_ang]\s*=>\s*([^ ]+)/) || [, null];
                var [, target_delay] = cleanString.match(/\[target_delay]\s*=>\s*([^ ]+)/) || [, null];
                var [, tor_hi] = cleanString.match(/\[tor_hi]\s*=>\s*([^ ]+)/) || [, null];
                var [, tor_lo] = cleanString.match(/\[tor_lo]\s*=>\s*([^ ]+)/) || [, null];
                var [, ang_hi] = cleanString.match(/\[ang_hi]\s*=>\s*([^ ]+)/) || [, null];
                var [, ang_lo] = cleanString.match(/\[ang_lo]\s*=>\s*([^ ]+)/) || [, null];
                var [, rpm] = cleanString.match(/\[rpm]\s*=>\s*([^ ]+)/) || [, null];
                var [, direction] = cleanString.match(/\[direction]\s*=>\s*([^ ]+)/) || [, null];
                var [, th_mode] = cleanString.match(/\[th_mode]\s*=>\s*([^ ]+)/) || [, null];
                var [, ds_tor] = cleanString.match(/\[ds_tor]\s*=>\s*([^ ]+)/) || [, null];
                var [, ds_speed] = cleanString.match(/\[ds_speed]\s*=>\s*([^ ]+)/) || [, null];
                var [, th_tor] = cleanString.match(/\[th_tor]\s*=>\s*([^ ]+)/) || [, null];
                var [, record_ang] = cleanString.match(/\[record_ang]\s*=>\s*([^ ]+)/) || [, null];
                var [, tor_unit] = cleanString.match(/\[tor_unit]\s*=>\s*([^ ]+)/) || [, null];

                document.getElementById('editstep').style.display = 'block';


                document.querySelector("select[name='edit_target_opt']").value = target_opt;


                if(target_opt == 0){
                    

                    var inputs = document.querySelectorAll("input[type='text'], input[type='radio'], select");
                    inputs.forEach(function(input) {
                        input.disabled = false; 
                    });

                    document.getElementById("edit_target_tor").value = target_tor;
                    document.getElementById("edit_target_ang_item").style.display='none';
                    document.getElementById("edit_target_delay_item").style.display='none';
                    document.getElementById("edit_target_tor_item").style.display='block';

                   

                }

                if(target_opt == 1){

                    var inputs = document.querySelectorAll("input[type='text'], input[type='radio'], select");
                    inputs.forEach(function(input) {
                     
                        if (input.type === 'radio' && input.name === 'edit_direction') {
                            input.disabled = false;  
                        } else if (input.type !== 'radio') {
                            input.disabled = false;  
                        }
                    });


                    document.getElementById("edit_target_ang").value = target_ang;
                    document.getElementById("edit_target_tor_item").style.display='none';
                    document.getElementById("edit_target_delay_item").style.display='none';
                    document.getElementById("edit_target_ang_item").style.display='block';
                    disableElementsByName("edit_th_mode");
                    

                    disableElementById('edit_ds_tor');
                    disableElementById('edit_ds_speed');
                    disableElementById('edit_th_tor');
                    enableElementById('edit_rpm');
                    enableElementById('edit_tor_hi');
                    enableElementById('edit_tor_lo');
                    enableElementById('edit_ang_hi');
                    enableElementById('edit_ang_lo');

                  
                    
                }

                if(target_opt == 2){
                    document.getElementById("edit_target_delay").value = target_delay;
                    document.getElementById("edit_target_tor_item").style.display='none';
                    document.getElementById("edit_target_ang_item").style.display='none';
                    document.getElementById("edit_target_delay_item").style.display='block';
                    disableElementById('edit_rpm');
                    disableElementById('edit_ds_tor');
                    disableElementById('edit_ds_speed');
                    disableElementById('edit_th_tor');
                    disableElementById('edit_tor_hi');
                    disableElementById('edit_tor_lo');
                    disableElementById('edit_ang_hi');
                    disableElementById('edit_ang_lo');
                    disableElementsByName("edit_th_mode");
                    disableElementsByName("edit_direction");



                    
                }


                document.getElementById("edit_rpm").value = rpm;
                document.getElementById("edit_ds_speed").value = ds_speed;
                document.getElementById("edit_ds_tor").value = ds_tor;
                document.getElementById("edit_th_tor").value = th_tor;
                document.getElementById("edit_tor_hi").value = cleanNumber(tor_hi);
                document.getElementById("edit_tor_lo").value = cleanNumber(tor_lo);
                document.getElementById("edit_ang_hi").value = ang_hi;
                document.getElementById("edit_ang_lo").value = ang_lo;

                document.getElementById('edit_step_id').value =step_id;


                var radioButtons_th_mode = document.getElementsByName("edit_th_mode");
                setRadioButton_value(radioButtons_th_mode, th_mode);

                var radioButtons_direction = document.getElementsByName("edit_direction");
                setRadioButton_value(radioButtons_direction, direction);


                toggleThTorDisabled();


  
            },
            error: function(xhr, status, error) {
             
            }
        });
    }
}

function create_step() {

    document.getElementById('newstep').style.display = 'block';

    // 設定預設值
    document.getElementById('rpm').value = 100;
    document.getElementById('th_tor').value = (0.0).toFixed(1);  
    document.getElementById('ds_tor').value = (0.0).toFixed(1);
    document.getElementById('ds_speed').value = 100;
    document.getElementById("direction_CW").checked = true;
    document.getElementById('ang_hi').value= 9999;
    document.getElementById('ang_lo').value= 0;
    document.getElementById('tor_hi').value= 55;
    document.getElementById('tor_lo').value= 0;
    document.getElementById('target_tor').value = parseFloat(document.getElementById('tool_min_tor').value);
    document.getElementById('target_ang').value= 1800;


    // 預設 downshift_ON 需要被選中
    document.getElementById("downshift_OFF").checked = true;
    toggleDisabledFields();  // 根據 downshift 的選項來控制其他欄位的狀態

    // downshift 變更
    document.getElementById("downshift_ON").addEventListener('change', toggleDisabledFields);
    document.getElementById("downshift_OFF").addEventListener('change', toggleDisabledFields);

    // target_opt 變更
    var targetoptionselect = document.getElementById('target_opt');
    var firstOptionValue = targetoptionselect.options[0].value;
    
    if(firstOptionValue == 1){

        document.getElementById("downshift_OFF").checked = true;
        document.getElementById("downshift_OFF").disabled = true;
        document.getElementById("downshift_ON").disabled = true;
        document.getElementById('th_tor').disabled = true;
        document.getElementById('ds_tor').disabled = true;
        document.getElementById('ds_speed').disabled = true;

        document.getElementById('target_tor_item').style.display='none';
        document.getElementById('target_ang_item').style.display='block';
        document.getElementById('target_delay_item').style.display='none';
        document.getElementById('ang_hi').value= 9999;
        document.getElementById('ang_lo').value= 0;
        document.getElementById('tor_hi').value= 55;
        document.getElementById('target_ang').value = 1800;
        
  

    }

    if(firstOptionValue == 2){

        document.getElementById("downshift_OFF").checked = true;
        document.getElementById("downshift_OFF").disabled = true;
        document.getElementById("downshift_ON").disabled = true;
        document.getElementById('th_tor').disabled = true;
        document.getElementById('ds_tor').disabled = true;
        document.getElementById('ds_speed').disabled = true;
        document.getElementById('rpm').disabled = true;
        document.getElementById('tor_hi').disabled = true;
        document.getElementById('tor_lo').disabled = true;
        document.getElementById('ang_hi').disabled = true;
        document.getElementById('ang_lo').disabled = true;

        document.getElementById('target_tor_item').style.display='none';
        document.getElementById('target_ang_item').style.display='none';
        document.getElementById('target_delay_item').style.display='block';
        document.getElementById('ang_hi').value= 9999;
        document.getElementById('ang_lo').value= 0;
        document.getElementById('target_delay').value = 1.0;
    }



    targetoptionselect.addEventListener('change', function() {
        var target_opt_Value = targetoptionselect.value;
        localStorage.setItem('target_option', target_opt_Value);
        toggleVisibility(target_opt_Value);
    });

    
    

}

// 用來根據 downshift 的選項來控制其他欄位的 disabled 狀態
function toggleDisabledFields() {
    if (document.getElementById("downshift_ON").checked) {
        // 當 downshift_ON 被選中時，解除 disabled
        document.getElementById('th_tor').disabled = false;
        document.getElementById('ds_tor').disabled = false;
        document.getElementById('ds_speed').disabled = false;
    } else {
        // 當 downshift_OFF 被選中時，設置 disabled
        document.getElementById('th_tor').disabled = true;
        document.getElementById('ds_tor').disabled = true;
        document.getElementById('ds_speed').disabled = true;
    }
}



  
function add_step() {
    
    // 防止重複處理的標誌
    if (isProcessing) return;
    isProcessing = true; // 設置為處理中

    var target_opt = document.getElementById('target_opt').value;
    var target_tor = document.getElementById('target_tor').value;
    var target_ang = document.getElementById('target_ang').value;
    var target_delay = document.getElementById('target_delay').value;
    var tor_hi = document.getElementById('tor_hi').value;
    var tor_lo = document.getElementById('tor_lo').value;
    var ang_hi = document.getElementById('ang_hi').value;
    var ang_lo = document.getElementById('ang_lo').value;
    var rpm = document.getElementById('rpm').value;
    var direction = document.querySelector('input[name="direction_option"]:checked')?.value || 0;
    var th_mode = document.querySelector('input[name="th_mode"]:checked').value;
    var th_tor = document.getElementById('th_tor').value;
    var ds_tor = document.getElementById('ds_tor').value;
    var ds_speed = document.getElementById('ds_speed').value;
    var record_ang = 0; //紀錄 累計角度
    var tor_unit = <?php echo  $data['step_torque_unit'] ?>

    // 驗證
    let check = input_check_savestep();
    if (check) {
        // 顯示加載動畫
        document.getElementById('spinner').style.display = 'block';

        $.ajax({
            url: "?url=Step/create_step",
            method: "POST",
            data: {
                jobid: jobid,
                seqid: seqid,
                stepid: add_stepid,
                target_opt: target_opt,
                target_tor:target_tor,
                target_ang: target_ang,
                target_delay: target_delay,
                tor_hi: tor_hi,
                tor_lo: tor_lo,
                ang_hi: ang_hi,
                ang_lo: ang_lo,
                rpm: rpm,
                direction: direction,
                th_mode: th_mode,
                th_tor: th_tor,
                ds_tor: ds_tor,
                ds_speed: ds_speed,
                record_ang: record_ang,
                tor_unit: tor_unit
            },
            success: function(response) {
                var responseData = JSON.parse(response);
                // 延遲 1000 毫秒後隱藏加載動畫，並顯示 alertify 彈跳視窗
                setTimeout(function() {
                    // 隱藏加載動畫
                    document.getElementById('spinner').style.display = 'none';

                    // 顯示 alertify 彈跳視窗
                    alertify.alert(responseData.res_type, responseData.res_msg, function() {
                        // 只刷新一次頁面
                        if (!window.pageRefreshed) {
                            window.pageRefreshed = true; // 防止無限重複刷新
                            history.go(0);
                        }
                    });

                    // 在 3 秒後自動關閉 alertify 彈跳視窗
                    setTimeout(function() {
                        alertify.closeAll();  // 關閉所有開啟的 alertify 彈跳視窗
                        if (!window.pageRefreshed) {
                            window.pageRefreshed = true;
                            history.go(0);
                        }
                    }, 3000); 
                }, 1000); // 延遲 1000 毫秒
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed:", status, error);
                // 顯示錯誤信息
                document.getElementById('spinner').style.display = 'none';
                alertify.alert('Error', 'There was an issue with the request. Please try again later.');
            },
            complete: function() {
                isProcessing = false; // 處理結束
            }
        });
    } else {
        isProcessing = false; // 如果驗證不通過，重置處理狀態
    }
}

function edit_step_save() {

    // 防止重複處理的標誌
    if (isProcessing) return;
    isProcessing = true; // 設置為處理中

    var target_opt = document.getElementById('edit_target_opt').value;
    var target_tor = document.getElementById('edit_target_tor').value;
    var target_ang = document.getElementById('edit_target_ang').value;
    var target_delay = document.getElementById('edit_target_delay').value;
    var tor_hi = document.getElementById('edit_tor_hi').value;
    var tor_lo = document.getElementById('edit_tor_lo').value;
    var ang_hi = document.getElementById('edit_ang_hi').value;
    var ang_lo = document.getElementById('edit_ang_lo').value;
    var rpm = document.getElementById('edit_rpm').value;
    var direction = document.querySelector('input[name="direction_option"]:checked')?.value || 0;
    var th_mode = document.querySelector('input[name="edit_th_mode"]:checked').value;
    var th_tor = document.getElementById('edit_th_tor').value;
    var ds_tor = document.getElementById('edit_ds_tor').value;
    var ds_speed = document.getElementById('edit_ds_speed').value;
    var record_ang = 0; //紀錄 累計角度
    var tor_unit = 3; //預設


    //驗證
    let check = input_check_editstep();
    if (check) {
        // 顯示加載動畫
        document.getElementById('spinner').style.display = 'block';

        $.ajax({
            url: "?url=Step/edit_step",
            method: "POST",
            data: {
                jobid: jobid,
                seqid: seqid,
                stepid: stepid,
                target_opt: target_opt,
                target_ang: target_ang,
                target_delay: target_delay,
                tor_hi: tor_hi,
                tor_lo: tor_lo,
                ang_hi: ang_hi,
                ang_lo: ang_lo,
                rpm: rpm,
                direction: direction,
                th_mode: th_mode,
                th_tor: th_tor,
                ds_tor: ds_tor,
                ds_speed: ds_speed,
                record_ang: record_ang,
                tor_unit: tor_unit
            },
            success: function(response) {
                var responseData = JSON.parse(response);
                // 延遲 1000 毫秒後隱藏加載動畫，並顯示 alertify 彈跳視窗
                setTimeout(function() {
                    // 隱藏加載動畫
                    document.getElementById('spinner').style.display = 'none';

                    // 顯示 alertify 彈跳視窗
                    alertify.alert(responseData.res_type, responseData.res_msg, function() {
                        // 只刷新一次頁面
                        if (!window.pageRefreshed) {
                            window.pageRefreshed = true; // 防止無限重複刷新
                            history.go(0);
                        }
                    });

                    // 在 3 秒後自動關閉 alertify 彈跳視窗
                    setTimeout(function() {
                        alertify.closeAll();  // 關閉所有開啟的 alertify 彈跳視窗
                        if (!window.pageRefreshed) {
                            window.pageRefreshed = true;
                            history.go(0);
                        }
                    }, 3000); 
                }, 1000); // 延遲 1000 毫秒
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed:", status, error);
                // 顯示錯誤信息
                document.getElementById('spinner').style.display = 'none';
                alertify.alert('Error', 'There was an issue with the request. Please try again later.');
            },
            complete: function() {
                isProcessing = false; // 處理結束
            }
        });
    } else {
        isProcessing = false; // 如果驗證不通過，重置處理狀態
    }


}

function copy_step_by_id(stepid){
    var stepid_new  = '<?php echo $data['step_id']?>';
    
    document.getElementById('from_step_id').value = stepid;    
    document.getElementById("to_step_id").value = stepid_new;


}

function copy_step_by_id_ajax() {
    
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

    if (isProcessing) {
        return; // 如果正在處理中，直接返回
    }

    isProcessing = true; // 設置為處理

    if (stepid_new) {
        // 顯示加載動畫
      

        $.ajax({
            url: "?url=Step/copy_tcc_step",
            method: "POST",
            data: { 
                job_id: jobid,
                seq_id: seqid,
                old_step_id: stepid,
                new_step_id: stepid_new
            },
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
                console.error("AJAX request failed:", status, error);
                // 顯示錯誤信息
                document.getElementById('spinner').style.display = 'none';
                alertify.alert('Error', 'There was an issue with the request. Please try again later.');
            },
            complete: function() {
                isProcessing = false; 
            }
        });
    } else {
        isProcessing = false; 
    }
}





var rowInfoArray = [];
<?php foreach($data['step'] as $key =>$val) {?>
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

function input_check_editstep() {
    return input_check_core("edit_"); // 編輯時 prefix 是 edit_
}



function input_check_savestep() {
    return input_check_core(""); // 新增時 prefix 是 ""
}


function input_check_core(prefix) {
    // prefix 是 ""（新增時）或 "edit_"（編輯時）
    let target_opt = document.getElementById(prefix + "target_opt").value;
    let Tool_Max_Torque = parseFloat(document.getElementById('tool_max_tor').value);
    let Tool_Min_Torque = parseFloat(document.getElementById('tool_min_tor').value);
    let Tool_Max_Torque_diff = parseFloat(document.getElementById('tool_max_tor_diff').value);
    let Tool_Min_Torque_diff = parseFloat(document.getElementById('tool_min_tor_diff').value);
    let Tool_Max_RPM = parseFloat(document.getElementById('tool_max_rpm').value);
    let Tool_Min_RPM = parseFloat(document.getElementById('tool_min_rpm').value);

    // Step ID
    let present_step_id = document.getElementById(prefix + "step_id")?.value || "";

    //TCC M7設定step有一個規則, 在第一個step轉速最低都是可以設定到50
    if (add_stepid === "1") {
        Tool_Min_RPM = 50;
    }

    let isDownshiftOff = document.getElementById(prefix + "downshift_OFF")?.checked || false;

    let conditions = [];

    if (target_opt == 0) { // Target Torque
        conditions = [
            { id: prefix + 'target_tor', pattern: /^\d{1,5}(\.\d{1})?$/, min: Tool_Min_Torque, max: Tool_Max_Torque },
            { id: prefix + 'tor_hi', pattern: /^\d{1,5}(\.\d{1})?$/, min: 1, max: 55, compareGreaterThanId: prefix + 'target_tor' },
            { id: prefix + 'tor_lo', pattern: /^\d{1,5}(\.\d{1})?$/, min: 0, max: 3, compareLessThanId: prefix + 'target_tor' },
            { id: prefix + 'ang_hi', pattern: /^\d{0,5}$/, min: 1, max: 9999, compareGreaterThanId: prefix + 'target_ang' },
            { id: prefix + 'ang_lo', pattern: /^\d{0,5}$/, min: 0, max: 9999, compareLessThanId: prefix + 'target_ang' },
            { id: prefix + 'rpm', pattern: /^\d{0,4}$/, min: Tool_Min_RPM, max: Tool_Max_RPM }
        ];

        if (!isDownshiftOff) {
            let target_tor_value = parseFloat(document.getElementById(prefix + 'target_tor')?.value || 0);
            conditions.push(
                { id: prefix + 'th_tor', pattern: /^\d{1,5}(\.\d{1})?$/, min: 0, max: target_tor_value },
                { id: prefix + 'ds_tor', pattern: /^\d{1,5}(\.\d{1})?$/, min: 0, max: target_tor_value },
                { id: prefix + 'ds_speed', pattern: /^\d{0,4}$/, min: Tool_Min_RPM, max: Tool_Max_RPM }
            );
        }
    }

    if (target_opt == 1) { // Target Angle
        conditions = [
            { id: prefix + 'target_ang', pattern: /^\d{0,5}$/, min: 1, max: 9999 },
            { id: prefix + 'tor_hi', pattern: /^\d{1,5}(\.\d{1})?$/, min: 1, max: 55, compareGreaterThanId: prefix + 'target_tor' },
            { id: prefix + 'tor_lo', pattern: /^\d{1,5}(\.\d{1})?$/, min: 0, max: 3, compareLessThanId: prefix + 'target_tor' },
            { id: prefix + 'ang_hi', pattern: /^\d{0,5}$/, min: 1, max: 9999, compareGreaterThanId: prefix + 'target_ang' },
            { id: prefix + 'ang_lo', pattern: /^\d{0,5}$/, min: 0, max: 9999, compareLessThanId: prefix + 'target_ang' },
            { id: prefix + 'rpm', pattern: /^\d{0,4}$/, min: Tool_Min_RPM, max: Tool_Max_RPM }
        ];
    }

    if (target_opt == 2) { // Target Delay
        conditions = [
            { id: prefix + 'target_delay', pattern: /^\d{1,5}(\.\d{1})?$/, min: 0.1, max: 9.9 }
        ];
    }

    let isFormValid = true;
    conditions.forEach(function (input) {
        var element = document.getElementById(input.id);
        if (element) {
            if (!validateInput(element, input.pattern, input.min, input.max, input.compareGreaterThanId, input.compareLessThanId)) {
                isFormValid = false;
            }
        }
    });

    return isFormValid;
}



function validateInput(element, pattern, min, max, compareGreaterThanId = null, compareLessThanId = null) {
    let value = element.value.trim();
    let isValid = true;
    let customMessage = "";

    // 語系處理
    let language = getCookie('language') || 'default';
    const errorText = {
        "zh-cn": {
            empty: "不可为空。",
            pattern: "格式错误。",
            range: (min, max) => `输入值必须在 ${min} ~ ${max} 之间。`,
            gt: (label, val) => `必须大于 ${label}（目前值: ${val}）`,
            lt: (label, val) => `必须小于 ${label}（目前值: ${val}）`
        },
        "zh-tw": {
            empty: "不可空白。",
            pattern: "格式錯誤。",
            range: (min, max) => `輸入值必須在 ${min} ~ ${max} 之間。`,
            gt: (label, val) => `必須大於 ${label}（目前值: ${val}）`,
            lt: (label, val) => `必須小於 ${label}（目前值: ${val}）`
        },
        "default": {
            empty: "This field is required.",
            pattern: "Invalid format.",
            range: (min, max) => `Value must be between ${min} and ${max}.`,
            gt: (label, val) => `Must be greater than ${label} (current: ${val})`,
            lt: (label, val) => `Must be less than ${label} (current: ${val})`
        }
    };
    const msg = errorText[language] || errorText["default"];

    // 驗證空值
    if (value === "") {
        isValid = false;
        customMessage = msg.empty;
    }
    // 驗證格式
    else if (!pattern.test(value)) {
        isValid = false;
        customMessage = msg.pattern;
    }
    // 驗證範圍（min ~ max）
    else if ((min !== null && min !== undefined && parseFloat(value) < min) ||
             (max !== null && max !== undefined && parseFloat(value) > max)) {
        isValid = false;
        customMessage = msg.range(min, max);

    }
    // 驗證必須大於指定欄位
    else if (compareGreaterThanId) {
        const compareElement = document.getElementById(compareGreaterThanId);
        if (compareElement && parseFloat(value) <= parseFloat(compareElement.value)) {
            let labelText = compareElement.previousElementSibling ? compareElement.previousElementSibling.innerText.replace(':', '') : "";
            customMessage = msg.gt(labelText, compareElement.value);
            isValid = false;
        }
    }
    // 驗證必須小於指定欄位
    else if (compareLessThanId) {
        const compareElement = document.getElementById(compareLessThanId);
        if (compareElement && parseFloat(value) >= parseFloat(compareElement.value)) {
            let labelText = compareElement.previousElementSibling ? compareElement.previousElementSibling.innerText.replace(':', '') : "";
            customMessage = msg.lt(labelText, compareElement.value);
            isValid = false;
        }
    }

    // 顯示或移除錯誤訊息
    if (!isValid) {
        element.classList.add("is-invalid");
        if (element.nextElementSibling) {
            element.nextElementSibling.innerHTML = customMessage;
        }
    } else {
        element.classList.remove("is-invalid");
        if (element.nextElementSibling) {
            element.nextElementSibling.innerHTML = "";
        }
    }

    return isValid;
}






let backupOptions = [];  // 用來存儲備份的選項

// 根據 target_option_only_tor 更新 select options
function updateTargetOption() {
    try {
        // 假設 target_option_only_tor 是從 PHP 傳過來的 JSON 資料
        let target_option_only_tor = JSON.parse('<?php echo $data["target_option_only_tor_json"]; ?>');

        // 檢查 target_option_only_tor 是否是有效的陣列
        if (!Array.isArray(target_option_only_tor)) {
            return; // 如果不是陣列，則終止函數
        }

        // 取得 select 元素
        const selectElement = document.getElementById("target_opt");

        // 檢查 select 元素是否存在
        if (!selectElement) {
            //console.error('未能找到 id="target_opt" 的元素');
            return; 
        }

        // 備份目前的選項
        backupOptions = Array.from(selectElement.options).map(option => ({
            value: option.value,
            text: option.text
        }));

        // 清空現有的選項
        while (selectElement.options.length > 0) {
            selectElement.remove(0);  // 移除第一個選項
        }

        console.log('target_option_only_tor:', target_option_only_tor);

        // 遍歷 target_option_only_tor 並創建新的 option 元素
        target_option_only_tor.forEach((option) => {
            // 確保每個選項有有效的 value 和 text
            if (option.value !== undefined && option.text !== undefined) {
                const optionElement = document.createElement("option");
                optionElement.value = option.value;  // 設定選項的 value 屬性
                optionElement.textContent = option.text;  // 設定選項的顯示文字
                selectElement.appendChild(optionElement);  // 將選項加入到 select 中
            } else {
                //console.warn('無效的選項:', option);  // 如果選項格式不正確，輸出警告
            }
        });

    } catch (error) {
        //console.error('解析 JSON 發生錯誤:', error);
    }
}

// 恢復原本的選項
function restoreBackupOptions() {
    const selectElement = document.getElementById("target_opt");

    // 清空現有的選項
    while (selectElement.options.length > 0) {
        selectElement.remove(0);
    }

    // 恢復備份的選項
    backupOptions.forEach((option) => {
        const optionElement = document.createElement("option");
        optionElement.value = option.value;
        optionElement.textContent = option.text;
        selectElement.appendChild(optionElement);
    });

}

function cleanNumber(value) {
    if (value === "" || value === null || value === undefined) {
        return value;
    }
    let num = parseFloat(value);
    if (isNaN(num)) {
        return value;  // 不是數字的話，直接回傳原本的
    }
    if (Number.isInteger(num)) {
        return num.toString();
    }
    // 如果是小數，檢查是不是 .0 結尾
    if (num % 1 === 0) {
        return parseInt(num).toString(); 
    }
    return value; // 其他正常小數（例如 12.3）直接回傳
}

</script>   
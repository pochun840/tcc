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
                                <input type="hidden" id="add_step_id" name="add_step_id">
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
                                <div  class="col-6 t1"><?php echo $text['Target_Torque'];?> (<?php echo $text[$data['unit_name']] ?? $data['unit_name']; ?>):</div>
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
                                <div for="hi-torque" class="col-6 t1"><?php echo $text['High_Torque'];?> (<?php echo $text[$data['unit_name']] ?? $data['unit_name']; ?>):</div>
                                <div class="col-4 t2">
                                    <input type="text" class="form-control input-ms" id="tor_hi" maxlength="" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div id="tor_lo_item">
                            <div class="row">
                                <div for="lo-torque" class="col-6 t1"><?php echo $text['Low_Torque'];?> (<?php echo $text[$data['unit_name']] ?? $data['unit_name']; ?>):</div>
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
                                <div id="downshift_threshold_title" for="th_tor" class="col-6 t1"><?php echo $text['Threshold_Torque'];?>(<?php echo $text[$data['unit_name']] ?? $data['unit_name']; ?>):</div>
                                <div class="col-4 t2" id="downshift_threshold_item"> 
                                    <input type="text" class="form-control input-ms" id="th_tor" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div id='ds_tor_item'>
                            <div class="row" >
                                <div id="downshift_torque_title" for="ds_tor" class="col-6 t1"><?php echo $text['Downshift_Torque'];?>(<?php echo $text[$data['unit_name']] ?? $data['unit_name']; ?>):</div>
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
                            <input type="hidden" id='edit_step_id' name='edit_step_id'>
                            <input type="hidden" id="new_edit_step_id" name="new_edit_step_id">
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
                                <div for="hi-torque" class="col-6 t1"><?php echo $text['High_Torque'];?> (<?php echo $text[$data['unit_name']] ?? $data['unit_name']; ?>):</div>
                                <div class="col-4 t2">
                                    <input type="text" class="form-control input-ms" id="edit_tor_hi" maxlength="" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="edit_tor_lo_item"> 
                            <div class="row">
                                <div for="lo-torque" class="col-6 t1"><?php echo $text['Low_Torque'];?> (<?php echo $text[$data['unit_name']] ?? $data['unit_name']; ?>):</div>
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
                                <div class="col-6 t1"><?php echo $text['Threshold_Torque'];?>(<?php echo $text[$data['unit_name']] ?? $data['unit_name']; ?>):</div>
                                <div class="col-4 t2">
                                    <input type="text" class="form-control input-ms" id="edit_th_tor" maxlength="" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div id="edit_ds_tor_item">
                            <div class="row">
                                <div class="col-6 t1"><?php echo $text['Downshift_Torque'];?>(<?php echo $text[$data['unit_name']] ?? $data['unit_name']; ?>):</div>
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








  
function add_step() {
    
    // 防止重複處理的標誌
    if (isProcessing) return;
    isProcessing = true; // 設置為處理中

    // 第一步：先檢查 step 數量

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
    var direction = document.querySelector('input[name="edit_direction"]:checked')?.value || 0;
    var th_mode = document.querySelector('input[name="edit_th_mode"]:checked').value;
    var th_tor = document.getElementById('edit_th_tor').value;
    var ds_tor = document.getElementById('edit_ds_tor').value;
    var ds_speed = document.getElementById('edit_ds_speed').value;
    var record_ang = 0; //紀錄 累計角度
    var tor_unit = '<?php echo $data['step_torque_unit'];?>'; 

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
                target_tor: target_tor,
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





</script>   
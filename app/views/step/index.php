<?php require APPROOT . 'views/inc/header.php'; ?>
<link rel="stylesheet" type="text/css" href="./css/tcc_step.css">

<body>
<div class="container-ms">
    <div class="w3-text-white w3-center">
        <table class="no-border">
            <tr id="header">
                <td width="100%">
                    <h3>Step Management</h3>
                </td>
                <!--<td>
                    <img src="./img/btn_home.png" style="margin-right: 10px">
                </td>-->
            </tr>
        </table>
    </div>

    <div class="main-content">
        <div class="center-content">
            <div class="topnav">
                <label style="font-size:20px;color: #000; padding-left: 2%" for="job_id">Job ID :</label>&nbsp;
                <input type="text" id="job_id" name="job_id" size="8" maxlength="20" value="<?php echo $data['job_id'];?>" disabled
                style="height:28px; font-size:20px;text-align: center; background-color: #DDDDDD; border:0; margin: 3px;">

                <label style="font-size:20px;color: #000; padding-left: 2%" for="seq_id">Seq ID :</label>&nbsp;
                <input type="text" id="seq_id" name="seq_id" size="8" maxlength="20" value="<?php echo $data['seq_id'];?>" disabled
                style="height:28px; font-size:20px;text-align: center; background-color: #DDDDDD; border:0; margin: 3px;">

                <button id="back_btn" type="button" onclick="cancelSetting()">Return</button>
            </div>

            <div class="table-container">
                <table id="step_table" class="table w3-table-all w3-hoverable">
                    <thead id="header-table">
                        <tr class="w3-dark-grey">
                            <th>Step ID</th>
                            <th>Target Option</th>
                            <th>Direction</th>
                            <th>Up</th>
                            <th>Down</th>
                        </tr>
                    </thead>

                    <tbody style="font-size: 1.8vmin;text-align: center;">
                       <?php foreach($data['step'] as $key =>$val){?>
                        <tr>
                            <td><?php echo $val['step_id'];?></td>
                            <td><?php echo $data['target_option'][$val['target_option']];?></td>
                            <td><?php echo $data['direction'][$val['direction']];?></td>
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
                <div style="color:black; float: right; margin: 2px">Total Step :
                    <label id="RecordCnt" name="RecordCnt" type="text" style="margin-right: 20px"><?php echo count($data['step']);?></label>
                </div>
            </div>
        </div>

        <div class="buttonbox">
            <input id="S3" name="Step_Manager_Submit" type="button" value="New" tabindex="1"  onclick="cound_step('new');" >
            <input id="S6" name="Step_Manager_Submit" type="button" value="Edit" tabindex="1" onclick="cound_step('edit')">
            <input id="S5" name="Step_Manager_Submit" type="button" value="Copy" tabindex="1"  onclick="cound_step('copy');">
            <input id="S4" name="Step_Manager_Submit" type="button" value="Delete" tabindex="1" onclick="cound_step('del');" >
        </div>
    </div>

    <!-- Add New Step -->
    <div id="newstep" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w3-animate-zoom" style="width: 80%">
                <header class="w3-container modal-header">
                    <span onclick="hideElementById('newstep');"
                        class="w3-button w3-red w3-display-topright" style="width: 50px; margin: 3px;">&times;</span>
                    <h3 id='modal_title'>New Step</h3>
                </header>

                <div class="modal-body">
                    <form id="new_step_form" style="padding-left: 5%">
                        <div class="row">
                            <div for="target-option" class="col-6 t1">Target Option :</div>
                            <div class="col-3 t2">
                                <select id="target_option" name="target_option" class="col custom-file">
                                    <?php foreach($data['target_option'] as $key => $val){?>
                                        <option value="<?php echo $key;?>"><?php echo $val;?></option>
                                    <?php }?>
                                    
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div for="target-torque" class="col-6 t1">Target Torque (<?php echo $data['unit'];?>):</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="target_torque" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="hi-torque" class="col-6 t1">Hi Torque (<?php echo $data['unit'];?>):</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="hi_torque" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="lo-torque" class="col-6 t1">Lo Torque (<?php echo $data['unit'];?>):</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="lo_torque" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="hi-angle" class="col-6 t1">Hi Angle (degree):</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="hi_angle" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="lo-angle" class="col-6 t1">Lo Angle (degree):</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="lo_angle" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="RPM" class="col-6 t1">RPM:</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="rpm" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="direction" class="col-6 t1">Direction:</div>
                            <div class="col t2" >
            			      	<div class="col-4 form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="direction_option" id="direction_CW" value="0">
            					  <label class="form-check-label" for="direction_CW">CW</label>
            					</div>
            					<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="direction_option" id="direction_CCW" value="1" checked="checked">
            					  <label class="form-check-label" for="direction_CCW">CCW</label>
            					</div>
                            </div>
                        </div>
                        <div class="row">
                            <div for="downshift" class="col-6 t1">Downshift:</div>
                            <div class="col t2" >
            			      	<div class="col-4 form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="downshift_option" id="downshift_ON" value="0" checked="checked">
            					  <label class="form-check-label" for="downshift_ON">ON</label>
            					</div>
            					<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="downshift_option" id="downshift_OFF" value="1" >
            					  <label class="form-check-label" for="downshift_OFF">OFF</label>
            					</div>
                            </div>
                        </div>
                        <div class="row">
                            <div for="downshift-threshold" class="col-6 t1">Downshift Threshold(<?php echo $data['unit'];?>):</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="downshift_threshold" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="downshift-torque" class="col-6 t1">Downshift Torque(<?php echo $data['unit'];?>):</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="downshift_torque" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="downshift-rpm" class="col-6 t1">Downshift RPM:</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="downshift_rpm" maxlength="" >
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <button id="" class="button-modal" onclick="add_step()" >Save</button>
                    <button id="" class="button-modal" onclick="hideElementById('newstep');"  class="closebtn">Close</button>
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
                    <h3 id='modal_title'>Edit Step</h3>
                </header>

                <div class="modal-body">
                    <form id="new_step_form" style="padding-left: 5%">
                        <div class="row">
                            <div for="target-option" class="col-6 t1">Target Option :</div>
                            <div class="col-3 t2">
                                <select id="edit_target_option" name="edit_target_option" class="col custom-file">
                                    <?php foreach($data['target_option'] as $key => $val){?>
                                        <option value="<?php echo $key;?>"><?php echo $val;?></option>
                                    <?php }?>
                                    
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div for="edit_target-torque" class="col-6 t1">Target Torque (<?php echo $data['unit'];?>):</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="edit_target_torque" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="hi-torque" class="col-6 t1">Hi Torque (<?php echo $data['unit'];?>):</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="edit_hi_torque" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="lo-torque" class="col-6 t1">Lo Torque (<?php echo $data['unit'];?>):</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="edit_lo_torque" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="hi-angle" class="col-6 t1">Hi Angle (degree):</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="edit_hi_angle" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="lo-angle" class="col-6 t1">Lo Angle (degree):</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="edit_lo_angle" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="RPM" class="col-6 t1">RPM:</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="edit_rpm" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="direction" class="col-6 t1">Direction:</div>
                            <div class="col t2" >
            			      	<div class="col-4 form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_direction_option" id="direction_CW" value="0">
            					  <label class="form-check-label" for="direction_CW">CW</label>
            					</div>
            					<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_direction_option" id="direction_CCW" value="1">
            					  <label class="form-check-label" for="direction_CCW">CCW</label>
            					</div>
                            </div>
                        </div>
                        <div class="row">
                            <div for="downshift" class="col-6 t1">Downshift:</div>
                            <div class="col t2" >
            			      	<div class="col-4 form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_downshift_option" id="downshift_ON" value="1">
            					  <label class="form-check-label" for="downshift_ON">ON</label>
            					</div>
            					<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_downshift_option" id="downshift_OFF" value="0" >
            					  <label class="form-check-label" for="downshift_OFF">OFF</label>
            					</div>
                            </div>
                        </div>
                        <div class="row">
                            <div for="edit_downshift-threshold" class="col-6 t1">Downshift Threshold(<?php echo $data['unit'];?>):</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="edit_downshift_threshold" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="edit_downshift-torque" class="col-6 t1">Downshift Torque(<?php echo $data['unit'];?>):</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="edit_downshift_torque" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="edit_downshift-rpm" class="col-6 t1">Downshift RPM:</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="edit_downshift_rpm" maxlength="" >
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <button id="" class="button-modal" onclick="edit_step_save()" >Save</button>
                    <button id="" class="button-modal" onclick="hideElementById('edittep');"  class="closebtn">Close</button>
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
                    <h3 id='modal_title'>Copy Step</h3>
                </header>

                <div class="modal-body">
                    <form id="new_step_form">
        	            <label for="from_step_id" class="col col-form-label" style="font-weight: bold">Copy From</label>
        	            <div style="padding-left: 10%">
        		            <div class="row">
        				        <label for="from_step_id" class="t1 col-4 col-form-label">Step ID :</label>
        				        <div class="col-5 t2 ">
        				            <input type="number" class="form-control" id="from_step_id" disabled>
        				        </div>
        				    </div>
        			    </div>

        			    <label for="from_step_id" class="col col-form-label" style="font-weight: bold">Copy To</label>
        			    <div style="padding-left: 10%">
        				    <div class="row">
        				        <label for="to_step_id" class="t1 col-4 col-form-label">Step ID</label>
        				        <div class="t2 col-5">
        				            <input type="number" class="form-control" id="to_step_id">
        				        </div>
        				    </div>
        			    </div>
        			  </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <button id="copyButton" class="button-modal" onclick="copy_step_by_id_ajax()" >Save</button>
                    <button id="" class="button-modal" onclick="document.getElementById('copystep').style.display='none'" class="closebtn">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    highlight_row('step_table');
});


// Get the modal
var modal = document.getElementById('newstep');

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

var rows = document.getElementsByTagName("tr");
for (var i = 0; i < rows.length; i++) {
    (function(row) {
        var cells = row.getElementsByTagName("td");
        if (cells.length > 0) {
            cells[0].addEventListener("click", function() {
           
                var stepid   = cells[0] ? (cells[0].textContent || cells[0].innerText) : null;
                localStorage.setItem("stepid", stepid);
            });
        }
    })(rows[i]);
}


function cound_step(argument){

    var table = $('#step_table').DataTable();
    var selectedRowData = table.row('.selected').data();
    var stepid = selectedRowData ? selectedRowData[0] : null;

    //移除
    var elementsToRemove = ["step_table_filter", "step_table_length", "step_table_info", "step_table_paginate"];
    removeElements(elementsToRemove);

    if(argument == 'del'){
        del_stepid(stepid);
    }

    if(argument =="copy" && stepid != null){
        copy_step(stepid);
    }

    if(argument =="new"){
        create_step();
    }

    if(argument =="edit" && stepid != null){
        edit_step(stepid);
    }

}

function edit_step(stepid) {
    var jobid = '<?php echo $data['job_id']?>';
    var seqid = '<?php echo $data['seq_id']?>';
   
    var unit = '<?php echo $data['unit']?>';
    console.log(stepid);

    if (jobid) {
        $.ajax({
            url: "?url=Step/search_stepinfo",
            method: "POST",
            data: {
                jobid: jobid,
                seqid: seqid,
                stepid: stepid,
            },
            success: function (response) {
                var stepInfo = parseStepInfo(response);
                displayEditStepForm(stepInfo, unit);
            },
            error: function (xhr, status, error) {

            }
        });
    }
}

function parseStepInfo(response) {
    var responseJSON = JSON.stringify(response);
    var cleanString = responseJSON.replace(/Array|\\n/g, '');
    var cleanString = cleanString.substring(2, cleanString.length - 2);

    var stepInfo = {};

    var matchArray = [
        'job_id', 'sequence_id', 'step_id', 'hi_torque', 'lo_torque',
        'hi_angle', 'lo_angle', 'rpm', 'downshift_rpm', 'downshift_torque',
        'threshold_torque', 'target_option', 'target_torque', 'target_angle',
        'target_delaytime', 'direction', 'downshift'
    ];

    matchArray.forEach(key => {
        var [, value] = cleanString.match(new RegExp(`\\[${key}]\\s*=>\\s*([^ ]+)`)) || [, null];
        stepInfo[key] = value;
    });

    return stepInfo;
}

function displayEditStepForm(stepInfo, unit) {
    var elementsToDisable = [
        'edit_hi_torque', 'edit_lo_torque', 'edit_hi_angle', 'edit_lo_angle',
        'edit_rpm', 'edit_downshift_rpm', 'edit_downshift_threshold',
        'edit_downshift_rpm', 'edit_downshift_torque', 'edit_target_torque'
    ];

    var targetOption = document.getElementById("edit_target_option");
    targetOption.addEventListener('change', function () {
        var selectedValue = this.value;
        handleEditTargetOptionChange(selectedValue, stepInfo, unit);
    });

    var downshiftOptionRadios = document.getElementsByName("edit_downshift_option");
    for (var i = 0; i < downshiftOptionRadios.length; i++) {
        downshiftOptionRadios[i].addEventListener("change", function () {
            var selectval = this.value;
            localStorage.setItem('downshift_option', selectval);
            handleEditDownshiftOptionChange(selectval);
        });
    }

    // Display step info in edit form
    document.getElementById('editstep').style.display = 'block';
    document.getElementById("edit_hi_torque").value = stepInfo.hi_torque;
    document.getElementById("edit_lo_torque").value = stepInfo.lo_torque;
    document.getElementById("edit_hi_angle").value = stepInfo.hi_angle;
    document.getElementById("edit_lo_angle").value = stepInfo.lo_angle;
    document.getElementById("edit_rpm").value = stepInfo.rpm;
    document.getElementById("edit_downshift_rpm").value = stepInfo.downshift_rpm;
    document.getElementById("edit_downshift_torque").value = stepInfo.downshift_torque;
    document.getElementById("edit_downshift_threshold").value = stepInfo.threshold_torque;
    document.querySelector("select[name='edit_target_option']").value = stepInfo.target_option;
    document.getElementById("edit_target_torque").value = stepInfo.target_torque;

    setRadioButtonValue(document.getElementsByName("edit_direction_option"), stepInfo.direction);
    setRadioButtonValue(document.getElementsByName("edit_downshift_option"), stepInfo.downshift);
}

function handleEditTargetOptionChange(selectedValue, stepInfo, unit) {
    var elementsToDisable = [
        'edit_hi_torque', 'edit_lo_torque', 'edit_hi_angle', 'edit_lo_angle',
        'edit_rpm', 'edit_downshift_rpm', 'edit_downshift_threshold',
        'edit_downshift_rpm', 'edit_downshift_torque', 'edit_target_torque'
    ];

    if (selectedValue == 2) {
        elementsToDisable.forEach(element => {
            document.getElementById(element).disabled = true;
        });

        document.querySelectorAll('input[name="edit_direction_option"]').forEach(function (radioButton) {
            radioButton.disabled = true;
        });

        document.querySelectorAll('input[name="edit_downshift_option"]').forEach(function (radioButton) {
            radioButton.disabled = true;
        });

        document.querySelector('div[for="edit_target-torque"]').textContent = "Target Delay Time";
    } else {
        elementsToDisable.forEach(element => {
            document.getElementById(element).disabled = false;
        });

        document.getElementById('edit_target_torque').disabled = false;

        if (selectedValue == 1) {
            document.querySelector('div[for="edit_target-torque"]').textContent = "Target Angle (degree)";
            document.getElementById('edit_target_torque').value = stepInfo.target_angle;
        } else if (selectedValue == 0) {
            document.querySelector('div[for="edit_target-torque"]').textContent = "Target Torque (" + unit + ")";
            document.getElementById('edit_target_torque').value = stepInfo.target_torque;
        }
    }
}

function edit_step_save() {
    var jobid = '<?php echo $data['job_id']?>';
    var seqid = '<?php echo $data['seq_id']?>';
    var stepid = readFromLocalStorage("stepid");

    var target_option = document.getElementById("edit_target_option").value;

    var requestData = {};

    if (target_option === '2') {
        requestData = {
            jobid: jobid,
            seqid: seqid,
            stepid: stepid,
            target_option: target_option,
            target_delaytime: document.getElementById("edit_target_torque").value
        };
    } else {
        requestData = {
            jobid: jobid,
            seqid: seqid,
            stepid: stepid,
            target_option: target_option,
            target_torque: (target_option === '0') ? document.getElementById("edit_target_torque").value : 0,
            target_angle: (target_option === '1') ? document.getElementById("edit_target_torque").value : 0,
            hi_torque: document.getElementById("edit_hi_torque").value,
            lo_torque: document.getElementById("edit_lo_torque").value,
            hi_angle: document.getElementById("edit_hi_angle").value,
            lo_angle: document.getElementById("edit_lo_angle").value,
            rpm: document.getElementById("edit_rpm").value,
            direction: document.querySelector('input[name="edit_direction_option"]:checked').value,
            downshift: document.querySelector('input[name="edit_downshift_option"]:checked').value,
            threshold_torque: document.getElementById("edit_downshift_threshold").value,
            downshift_torque: document.getElementById("edit_downshift_torque").value,
            downshift_rpm: document.getElementById("edit_downshift_rpm").value
        };
    }

    if (target_option) {
        $.ajax({
            url: "?url=Step/edit_step",
            method: "POST",
            data: requestData,
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



function create_step() {
    document.getElementById('newstep').style.display = 'block';

    var unit = '<?php echo $data['unit']?>';

    var targetoptionselect = document.getElementById('target_option');
    targetoptionselect.addEventListener('change', handleTargetOptionChange);

    var downshiftOptionRadios = document.getElementsByName("downshift_option");
    downshiftOptionRadios.forEach(radio => {
        radio.addEventListener("change", handleDownshiftOptionChange);
    });
}

function handleTargetOptionChange() {
    var targetOptionValue = this.value;
    localStorage.setItem('target_option', targetOptionValue);

    var targetTorqueElement = document.getElementById('target_torque');
    var targetTorqueLabel = document.querySelector('div[for="target-torque"]');
    var disableElements = [
        targetTorqueElement,
        document.getElementById('hi_torque'),
        document.getElementById('lo_torque'),
        document.getElementById('hi_angle'),
        document.getElementById('lo_angle'),
        document.getElementById('rpm'),
        document.getElementById('downshift_threshold'),
        document.getElementById('downshift_torque'),
        document.getElementById('downshift_rpm')
    ];
    var directionOptions = document.querySelectorAll('input[name="direction_option"]');
    var downshiftOptions = document.querySelectorAll('input[name="downshift_option"]');

    disableElements.forEach(element => element.disabled = false);
    directionOptions.forEach(radioButton => radioButton.disabled = false);
    downshiftOptions.forEach(radioButton => radioButton.disabled = false);

    switch (targetOptionValue) {
        case '2':
            targetTorqueLabel.textContent = "Target Delay Time";
            disableElements.forEach(element => element.disabled = true);
            directionOptions.forEach(radioButton => radioButton.disabled = true);
            downshiftOptions.forEach(radioButton => radioButton.disabled = true);
            break;
        case '1':
            targetTorqueLabel.textContent = "Target Angle (degree)";
            break;
        default:
            targetTorqueLabel.textContent = "Target Torque (" + unit + ")";
            break;
    }
}

function handleDownshiftOptionChange() {
    var selectedValue = this.value;
    localStorage.setItem('downshift_option', selectedValue);
    var display = selectedValue == 1 ? "none" : "block";

    var elementsToHide = document.querySelectorAll('div[for^="downshift"], #downshift_threshold, #downshift_torque, #downshift_rpm');
    elementsToHide.forEach(element => {
        element.style.display = display;
    });
}

function add_step(){

    var jobid = '<?php echo $data['job_id']?>';
    var seqid = '<?php echo $data['seq_id']?>';
    var stepid = '<?php echo $data['stepid_new']?>';

    var target_option = document.getElementById('target_option').value;
    var target_torque = document.getElementById('target_torque').value;

    var hi_torque = document.getElementById('hi_torque').value;
    var lo_torque = document.getElementById('lo_torque').value;

    var hi_angle = document.getElementById('hi_angle').value;
    var lo_angle = document.getElementById('lo_angle').value;
    var rpm = document.getElementById('rpm').value;

    var threshold_torque = document.getElementById('downshift_threshold').value;
    var downshift_torque = document.getElementById('downshift_torque').value;
    var downshift_rpm  = document.getElementById('downshift_rpm').value;

    var direction = document.querySelector('input[name="direction_option"]:checked').value;
    var downshift = document.querySelector('input[name="downshift_option"]:checked').value;


    if(target_torque){
       
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
                downshift_rpm: downshift_rpm

            },
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

function copy_step(stepid){
    document.getElementById('copystep').style.display = 'block';   
    copy_step_by_id();

}

document.getElementById("copyButton").addEventListener("click", function() {
    copy_step_by_id();
});

function copy_step_by_id(){
    var stepid = readFromLocalStorage("stepid");
    var jobid = '<?php echo $data['job_id']?>';
    var seqid = '<?php echo $data['seq_id']?>';
    var seqidnew = '<?php echo $data['stepid_new']?>';
    
    document.getElementById('from_step_id').value = stepid;    
    document.getElementById("to_step_id").value = seqidnew;


}

function copy_step_by_id_ajax(){
    var stepid = readFromLocalStorage("stepid");
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
                console.log( response);
                alert(response);
                history.go(0);
            },
            error: function(xhr, status, error) {
                
            }
        });
    }
}


function del_stepid(step_id){
    var stepid = readFromLocalStorage("stepid");
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
                console.log( response);
                alert(response);
                history.go(0);
            },
            error: function(xhr, status, error) {
                
            }
        });

    }

}

function disableElements(elements, value) {
    elements.forEach(function(element) {
        element.disabled = value;
        element.value = value === true ? 0 : ''; 
    });
}

var rowInfoArray = [];
<?php foreach($data['step'] as $key =>$val) {?>
        var jobid = "<?php echo $val['job_id'];?>";
        var sequenceId = "<?php echo $val['sequence_id'];?>";
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
                console.log(response);
                history.go(0); 
            },
            error: function(xhr, status, error) {
                console.error('Error sending data:', error);
            }
        });
    }
}
</script>

</body>

</html>
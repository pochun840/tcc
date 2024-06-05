<?php require APPROOT . 'views/inc/header.php'; ?>
    <link rel="stylesheet" type="text/css" href="./css/tcc_step.css">
    <script src="<?php echo URLROOT; ?>js/all.js?v=202405291030"></script>

    <style>
        .t1{font-size: 17px; margin: 5px 0px; display: flex; align-items: center;}
        .t2{font-size: 17px; margin: 5px 0px;}
        .t3{font-size: 17px; margin: 3px 0px;}
    </style>

</head>

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

function highlight_row(tableId)
{
    var table = document.getElementById(tableId);
    var rows = table.getElementsByTagName('tr');

    for (var i = 1; i < rows.length; i++) {
        rows[i].onclick = function () {
            for (var j = 1; j < rows.length; j++) {
                rows[j].classList.remove('selected');
            }
            this.classList.add('selected');
        }
    }
}

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

    var stepid = readFromLocalStorage("stepid");
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


function edit_step(){

    var jobid = '<?php echo $data['job_id']?>';
    var seqid = '<?php echo $data['seq_id']?>';
    var stepid = readFromLocalStorage("stepid");

    var unit = '<?php echo $data['unit']?>';

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

                //console.log(cleanString);

                var [, jobid] = cleanString.match(/\[job_id]\s*=>\s*([^ ]+)/) || [, null];
                var [, seqid] = cleanString.match(/\[sequence_id]\s*=>\s*([^ ]+)/) || [, null];
                var [, step_id] = cleanString.match(/\[step_id]\s*=>\s*([^ ]+)/) || [, null];
                var [, hi_torque] = cleanString.match(/\[hi_torque]\s*=>\s*([^ ]+)/) || [, null];
                var [, lo_torque] = cleanString.match(/\[lo_torque]\s*=>\s*([^ ]+)/) || [, null];
                var [, hi_angle] = cleanString.match(/\[hi_angle]\s*=>\s*([^ ]+)/) || [, null];
                var [, lo_angle] = cleanString.match(/\[lo_angle]\s*=>\s*([^ ]+)/) || [, null];
                var [, rpm] = cleanString.match(/\[rpm]\s*=>\s*([^ ]+)/) || [, null];
                var [, downshift_rpm] = cleanString.match(/\[downshift_rpm]\s*=>\s*([^ ]+)/) || [, null];
                var [, downshift_torque] = cleanString.match(/\[downshift_torque]\s*=>\s*([^ ]+)/) || [, null];
                var [, threshold_torque] = cleanString.match(/\[threshold_torque]\s*=>\s*([^ ]+)/) || [, null];
                var [, target_option] = cleanString.match(/\[target_option]\s*=>\s*([^ ]+)/) || [, null];
                var [, target_torque] = cleanString.match(/\[target_torque]\s*=>\s*([^ ]+)/) || [, null];
                var [, target_angle] = cleanString.match(/\[target_angle]\s*=>\s*([^ ]+)/) || [, null];
                var [, target_delaytime] = cleanString.match(/\[target_delaytime]\s*=>\s*([^ ]+)/) || [, null];
                var [, direction] = cleanString.match(/\[direction]\s*=>\s*([^ ]+)/) || [, null];
                var [, downshift] = cleanString.match(/\[downshift]\s*=>\s*([^ ]+)/) || [, null];

                document.getElementById('editstep').style.display = 'block';
                document.getElementById("edit_hi_torque").value = hi_torque;
                document.getElementById("edit_lo_torque").value = lo_torque;
                document.getElementById("edit_hi_angle").value = hi_angle;
                document.getElementById("edit_lo_angle").value = lo_angle;
                document.getElementById("edit_rpm").value = rpm;
                document.getElementById("edit_downshift_rpm").value = downshift_rpm;
                document.getElementById("edit_downshift_torque").value = downshift_torque;
                document.getElementById("edit_downshift_threshold").value = threshold_torque;
                document.querySelector("select[name='edit_target_option']").value = target_option;

                var radioButtons1 = document.getElementsByName("edit_direction_option");
                var radioButtons2 = document.getElementsByName("edit_downshift_option");

                setRadioButtonValue(radioButtons1, direction);
                setRadioButtonValue(radioButtons2, downshift);

                if(target_option == 2){

                    document.querySelector('div[for="edit_target-torque"]').textContent = "Target Delay Time";
                    document.getElementById("edit_target_torque").value = target_delaytime;
                    document.getElementById('edit_hi_torque').disabled = true; 
                    document.getElementById('edit_lo_torque').disabled = true; 
                    document.getElementById('edit_hi_angle').disabled = true; 
                    document.getElementById('edit_lo_angle').disabled = true; 
                    document.getElementById('edit_rpm').disabled = true; 
                    document.getElementById('edit_downshift_threshold').disabled = true; 
                    document.getElementById('edit_downshift_torque').disabled = true; 
                    document.getElementById('edit_downshift_rpm').disabled = true; 

                    document.querySelectorAll('input[name="edit_direction_option"]').forEach(function(radioButton) {
                                radioButton.disabled = true;
                    });

                    document.querySelectorAll('input[name="edit_downshift_option"]').forEach(function(radioButton) {
                                radioButton.disabled = true;
                    });
                }

                if(target_option == 1){
                    document.querySelector('div[for="edit_target-torque"]').textContent = "Target Angle (degree)";
                    document.getElementById("edit_target_torque").value = target_angle;
                    
                }

                if(target_option == 0){
                    document.querySelector('div[for="edit_target-torque"]').textContent = "Target Torque (" + unit+")";
                    document.getElementById("edit_target_torque").value = target_torque;
                }

                if(downshift == 0){
                    document.querySelector('div[for="edit_downshift-torque"]').style.display = "none";
                    document.getElementById('edit_downshift_torque').style.display = "none";

                    document.querySelector('div[for="edit_downshift-threshold"]').style.display = "none";
                    document.getElementById('edit_downshift_threshold').style.display = "none";

                    document.querySelector('div[for="edit_downshift-rpm"]').style.display = "none";
                    document.getElementById('edit_downshift_rpm').style.display = "none";

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
                            document.getElementById('edit_downshift_rpm'),
                            document.getElementById('edit_downshift_threshold'),
                            document.getElementById('edit_downshift_rpm'),
                            document.getElementById('edit_downshift_torque')
                        ];

                        disableElements(elementsToDisable, true);

                        document.querySelectorAll('input[name="edit_direction_option"]').forEach(function(radioButton) {
                            radioButton.disabled = true;
                        });

                        document.querySelectorAll('input[name="edit_downshift_option"]').forEach(function(radioButton) {
                            radioButton.disabled = true;
                        });

                        document.querySelector('div[for="edit_target-torque"]').textContent = "Target Delay Time";
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

                        document.getElementById('edit_downshift_rpm').disabled = false; 
                        document.getElementById('edit_downshift_rpm').value = downshift_rpm;


                        document.getElementById('edit_downshift_torque').disabled = false; 
                        document.getElementById('edit_downshift_torque').value = downshift_torque;


                        document.querySelectorAll('input[name="edit_direction_option"]').forEach(function(radioButton) {
                            radioButton.disabled = false;
                        });

                        document.querySelectorAll('input[name="edit_downshift_option"]').forEach(function(radioButton) {
                            radioButton.disabled = false;
                        });

                    }

                    if(selectedValue == 1){
                        document.querySelector('div[for="edit_target-torque"]').textContent = "Target Angle (degree)";

                    }

                    if(selectedValue == 0){
                        document.querySelector('div[for="edit_target-torque"]').textContent = "Target Torque (" + unit + ")";

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

                            document.querySelector('div[for="edit_downshift-rpm"]').style.display = "block";
                            document.getElementById('edit_downshift_rpm').style.display = "block";
                        }else{
                            document.querySelector('div[for="edit_downshift-torque"]').style.display = "none";
                            document.getElementById('edit_downshift_torque').style.display = "none";

                            document.querySelector('div[for="edit_downshift-threshold"]').style.display = "none";
                            document.getElementById('edit_downshift_threshold').style.display = "none";

                            document.querySelector('div[for="edit_downshift-rpm"]').style.display = "none";
                            document.getElementById('edit_downshift_rpm').style.display = "none";
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
    var stepid = readFromLocalStorage("stepid");

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
    var downshift_rpm = 0;

    if (target_option == 2) {
        target_delaytime = document.getElementById("edit_target_torque").value;
    } else {
        if (target_option == 1) {
            target_angle = document.getElementById("edit_target_torque").value;
        }
        if (target_option == 0) {
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
        downshift_rpm = document.getElementById("edit_downshift_rpm").value;
    }

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
        downshift_rpm: downshift_rpm
    };

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
    targetoptionselect.addEventListener('change', function() {
        var targetOptionValue = targetoptionselect.value;
        localStorage.setItem('target_option', targetOptionValue);

        var targetTorqueElement = document.getElementById('target_torque');
        var hiTorqueElement = document.getElementById('hi_torque');
        var loTorqueElement = document.getElementById('lo_torque');
        var hiAngleElement = document.getElementById('hi_angle');
        var loAngleElement = document.getElementById('lo_angle');
        var rpmElement = document.getElementById('rpm');
        var downshiftThresholdElement = document.getElementById('downshift_threshold');
        var downshiftTorqueElement = document.getElementById('downshift_torque');
        var downshiftRpmElement = document.getElementById('downshift_rpm');
        var directionOptions = document.querySelectorAll('input[name="direction_option"]');
        var downshiftOptions = document.querySelectorAll('input[name="downshift_option"]');

        targetTorqueElement.disabled = false;
        hiTorqueElement.disabled = false;
        loTorqueElement.disabled = false;
        hiAngleElement.disabled = false;
        loAngleElement.disabled = false;
        rpmElement.disabled = false;
        downshiftThresholdElement.disabled = false;
        downshiftTorqueElement.disabled = false;
        downshiftRpmElement.disabled = false;
        directionOptions.forEach(radioButton => radioButton.disabled = false);
        downshiftOptions.forEach(radioButton => radioButton.disabled = false);

        if (targetOptionValue == 2) {
            document.querySelector('div[for="target-torque"]').textContent = "Target Delay Time";
            
            hiTorqueElement.disabled = true;
            loTorqueElement.disabled = true;
            hiAngleElement.disabled = true;
            loAngleElement.disabled = true;
            rpmElement.disabled = true;
            downshiftThresholdElement.disabled = true;
            downshiftTorqueElement.disabled = true;
            downshiftRpmElement.disabled = true;
            directionOptions.forEach(radioButton => radioButton.disabled = true);
            downshiftOptions.forEach(radioButton => radioButton.disabled = true);
            
        } else if (targetOptionValue == 1) {
            document.querySelector('div[for="target-torque"]').textContent = "Target Angle (degree)";
        }

        else if (targetOptionValue == 0) {
            document.querySelector('div[for="target-torque"]').textContent = "Target Torque (" + unit + ")";
        }
    });

    var downshiftOptionRadios = document.getElementsByName("downshift_option");
  

    for(var i = 0; i < downshiftOptionRadios.length; i++) {
        downshiftOptionRadios[i].addEventListener("change", function() {
        var selectedValue = this.value;
        localStorage.setItem('downshift_option',selectedValue);
        if(selectedValue  == 1){
            document.querySelector('div[for="downshift-threshold"]').style.display = "none";
            document.getElementById('downshift_threshold').style.display = "none";

            document.querySelector('div[for="downshift-torque"]').style.display = "none";
            document.getElementById('downshift_torque').style.display = "none";

            document.querySelector('div[for="downshift-rpm"]').style.display = "none";
            document.getElementById('downshift_rpm').style.display = "none";
        }else{
            document.querySelector('div[for="downshift-threshold"]').style.display = "block";
            document.getElementById('downshift_threshold').style.display = "block";

            document.querySelector('div[for="downshift-torque"]').style.display = "block";
            document.getElementById('downshift_torque').style.display = "block";

            document.querySelector('div[for="downshift-rpm"]').style.display = "block";
            document.getElementById('downshift_rpm').style.display = "block";

        }
          
        });
    }


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
        // 進行 新增的ajax 
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

function swap_row(row1, row2) {
    var parent = row1.parentNode;
    var nextSibling = row2.nextSibling;
    parent.insertBefore(row2, row1);
    parent.insertBefore(row1, nextSibling);
}

function MoveUp(button) {
    var row = button.parentNode.parentNode;
    var prevRow = row.previousElementSibling;
    if (prevRow) {
        swap_row(row, prevRow);
       
        var index1 = Array.from(row.parentNode.children).indexOf(row);
        var index2 = Array.from(row.parentNode.children).indexOf(prevRow);
        var temp = rowInfoArray[index1];
        rowInfoArray[index1] = rowInfoArray[index2];
        rowInfoArray[index2] = temp;
        sendRowInfoArray();
    }
}

function MoveDown(button) {
    var row = button.parentNode.parentNode;
    var nextRow = row.nextElementSibling;
    if (nextRow) {
        swap_row(nextRow, row);
     
        var index1 = Array.from(row.parentNode.children).indexOf(row);
        var index2 = Array.from(row.parentNode.children).indexOf(nextRow);
        var temp = rowInfoArray[index1];
        rowInfoArray[index1] = rowInfoArray[index2];
        rowInfoArray[index2] = temp;
        sendRowInfoArray();
    }
}

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

<style type="text/css">
    .selected {
        background-color: #9AC0CD !important;
    }
</style>
</body>

</html>
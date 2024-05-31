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
                            <td><img src="./img/btn_up.png"></td>
                            <td><img src="./img/btn_down.png"></td>
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
            <input id="S3" name="Step_Manager_Submit" type="button" value="New" tabindex="1" onclick="document.getElementById('newstep').style.display='block'">
            <input id="S6" name="Step_Manager_Submit" type="button" value="Edit" tabindex="1">
            <input id="S5" name="Step_Manager_Submit" type="button" value="Copy" tabindex="1" onclick="document.getElementById('copystep').style.display='block'">
            <input id="S4" name="Step_Manager_Submit" type="button" value="Delete" tabindex="1" >
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
                                <select id="Target_Option" class="col custom-file">
                                    <option value="1">Torque</option>
                                    <option value="2">Angle</option>
                                    <option value="3">Delay Time</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div for="target-torque" class="col-6 t1">Target Torque (kgf-cm):</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="target_torque" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="hi-torque" class="col-6 t1">Hi Torque (kgf-cm):</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="hi_torque" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="lo-torque" class="col-6 t1">Lo Torque (kgf-cm):</div>
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
            					  <label class="form-check-label" for="direction_CCW">CWW</label>
            					</div>
                            </div>
                        </div>
                        <div class="row">
                            <div for="downshift" class="col-6 t1">Downshift:</div>
                            <div class="col t2" >
            			      	<div class="col-4 form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="downshift_option" id="downshift_ON" value="1">
            					  <label class="form-check-label" for="downshift_ON">ON</label>
            					</div>
            					<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="downshift_option" id="downshift_OFF" value="2" checked="checked">
            					  <label class="form-check-label" for="downshift_OFF">OFF</label>
            					</div>
                            </div>
                        </div>
                        <div class="row">
                            <div for="downshift-threshold" class="col-6 t1">Downshift Threshold(kgf-cm):</div>
                            <div class="col-3 t2">
                                <input type="text" class="form-control input-ms" id="downshift_threshold" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="downshift-torque" class="col-6 t1">Downshift Torque(kgf-cm):</div>
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
                    <button id="" class="button-modal" onclick="add_member_user()" >Save</button>
                    <button id="" class="button-modal" onclick="hideElementById('newstep');"  class="closebtn">Close</button>
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
                    <button id="" class="button-modal" onclick="add_member_user()" >Save</button>
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

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}


// Button Return
/*document.getElementById('back_btn').onclick = function()
{
    window.location.href = './tcc_sequence.html';
    return;
};*/




var rows = document.getElementsByTagName("tr");
for (var i = 0; i < rows.length; i++) {
    (function(row) {
        var cells = row.getElementsByTagName("td");
        if (cells.length > 0) {
            cells[0].addEventListener("click", function() {
           
                var stepid   = cells[0] ? (cells[0].textContent || cells[0].innerText) : null;
                //var seqname = cells[1] ? (cells[1].textContent || cells[1].innerText) : null;
                localStorage.setItem("stepid", stepid);
            });
        }
    })(rows[i]);
}

function del_stepid(step_id){
    var jobid ='<?php echo $data['job_id']?>';
    var seqid ='<?php echo $data['seq_id']?>';

    if(jobid){
        //進行ajax del 
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
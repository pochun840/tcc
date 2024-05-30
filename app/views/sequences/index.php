<?php require APPROOT . 'views/inc/header.php'; ?>
<link rel="stylesheet" type="text/css" href="./css/tcc_seq.css">
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
                    <h3>Sequence Management</h3>
                </td>
                <td>
                    <!--<img src="./img/btn_home.png" style="margin-right: 10px">-->
                </td>
            </tr>
        </table>
    </div>

    <div class="main-content">
        <div class="center-content">
            <div class="topnav">
                <label style="font-size:20px;color: #000; padding-left: 2%" for="job_id">Job ID :</label>&nbsp;&nbsp;
                <input type="text" id="job_id" name="job_id" size="10" maxlength="20" value="<?php echo $data['job_id'];?>" disabled
                style="height:28px; font-size:20px;text-align: center; background-color: #DDDDDD; border:0; margin: 3px;">

                <button id="back_btn" type="button" onclick="cancelSetting()">Return</button>
            </div>

            <div class="table-container">
                <div class="scrollbar" id="style-jobtable">
                    <div class="scrollbar-force-overflow">
                        <table id="seq_table" class="table w3-table-all w3-hoverable">
                            <thead id="header-table">
                                <tr class="w3-dark-grey">
                                    <th>Seq ID</th>
                                    <th>Seq Name</th>
                                    <th>Unit</th>
                                    <th>TR</th>
                                    <th>Enable</th>
                                    <th>Up</th>
                                    <th>Down</th>
                                    <th>Total Step</th>
                                    <th>Add Step</th>
                                </tr>
                            </thead>

                            <tbody style="font-size: 1.8vmin;text-align: center;">
                                <?php foreach($data['sequences'] as $key =>$val) {?>
                                <tr >
                                    <td><?php echo $val['sequence_id'];?></td>
                                    <td><?php echo $val['sequence_name'];?></td>
                                    <td><?php echo $data['unit_arr'][$val['torque_unit']];?></td>
                                    <td><?php echo $val['tightening_repeat'];?></td>
                                    <td>
                                        <input class="seq_enable" style="zoom:1.5; vertical-align: middle" id="" name="" value="" type="checkbox" >
                                    </td>
                                    <td><img src="./img/btn_up.png"   onclick="MoveUp.call(this);"></td>
                                    <td><img src="./img/btn_down.png" onclick="MoveDown.call(this);"></td>
                                    <td><?php echo $data['total_step'];?></td>
                                    <td><img id="Add_Step" src="./img/btn_plus.png"></td>
                                </tr>
                                <?php  } ?>
                               
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div id="TotalPage">
            <div id="TotalSeqTable">
                <div style="color:black; float: right; margin: 2px">Total Sequence :
                    <label id="RecordCnt" name="RecordCnt" type="text" style="margin-right: 20px"><?php echo count($data['sequences']); ?></label>
                </div>
            </div>
        </div>

        <div class="buttonbox">
            <!--onclick="document.getElementById('newseq').style.display='block'"-->
            <input id="S3" name="Seq_Manager_Submit" type="button" value="new" tabindex="1"  onclick="cound_job('new');">
            <input id="S6" name="Seq_Manager_Submit" type="button" value="Edit" tabindex="1" onclick="cound_job('edit');">
            <input id="S5" name="Seq_Manager_Submit" type="button" value="Copy" tabindex="1" onclick="document.getElementById('copyseq').style.display='block'">
            <input id="S4" name="Seq_Manager_Submit" type="button" value="Delete" tabindex="1" onclick="cound_job('del');">
        </div>
    </div>

    <!-- Add New Sequence -->
    <div id="newseq" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w3-animate-zoom" style="width: 70%">
                <header class="w3-container modal-header">
                    <span onclick="hideElementById('newseq');"
                        class="w3-button w3-red w3-display-topright" style="width: 50px; margin: 3px;">&times;</span>
                    <h3 id='modal_title'>New Seq</h3>
                </header>

                <div class="modal-body">
                    <form id="new_seq_form" style="padding-left: 5%">
                        <div class="row">
                            <div for="job-id" class="col-6 t1">Job ID :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="job_id" maxlength="" value="<?php echo $data['job_id'];?>" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="seq-id" class="col-6 t1">Seq ID :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="seq_id" maxlength="" value="<?php echo $data['seq_id'];?>">
                            </div>
                        </div>
                        <div class="row">
                            <div for="seq-name" class="col-6 t1">Seq Name :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="seq_name" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="Tighten-Repeat" class="col-6 t1">Tighten Repeat :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="tighten_repeat" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="ok-time" class="col-6 t1">OK Time (sec) :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="ok_time" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="okall-alarm" class="col-6 t1">OK All Alarm Time (sec) :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="okall-alarm" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="k(30%-300%)" class="col-6 t1">K (30%-300%) :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="K" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="offset" class="col-6 t1">Offset :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="offset" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="torque-unit" class="col-6 t1">Torque Unit :</div>
                            <div class="col-4 t2">
                                <select id="torque_unit" class="col custom-file">
                                   <?php foreach($data['unit_arr'] as $k_unit => $v_unit){?>
                                      <option value="<?php echo $k_unit;?>"><?php echo $v_unit;?></option>
                                   <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div for="NG-stop" class="col-6 t1">NG Stop :</div>
                            <div class="col-4 t2">
                                <select id="ng_stop" class="col custom-file">
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div for="join" class="col-6 t1">Join :</div>
                            <div class="col t2" >
            			      	<div class="col-4 form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="join_option" id="soft" value="0">
            					  <label class="form-check-label" for="soft">Soft</label>
            					</div>
            					<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="join_option" id="hard" value="1" checked="checked">
            					  <label class="form-check-label" for="hard">Hard</label>
            					</div>
                            </div>
                        </div>
                        <div class="row">
                            <div for="okall-stop" class="col-6 t1">OK All Stop :</div>
                            <div class="col t2" >
            			      	<div class="col-4 form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="okall_stop_option" id="Okall_FF" value="0">
            					  <label class="form-check-label" for="Okall_OFF">OFF</label>
            					</div>
            					<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="okall_stop_option" id="Okall_ON" value="1" checked="checked">
            					  <label class="form-check-label" for="Okall_ON">ON</label>
            					</div>
                            </div>
                        </div>
                        <div class="row">
                            <div for="OPT" class="col-6 t1">OPT :</div>
                            <div class="col t2" >
            			      	<div class=" col-4 form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="opt_option" id="OPT_OFF" value="0">
            					  <label class="form-check-label" for="OPT_OFF">OFF</label>
            					</div>
            					<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="opt_option" id="OPT_ON" value="1" checked="checked">
            					  <label class="form-check-label" for="OPT_ON">ON</label>
            					</div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <button id="" class="button-modal" onclick="saveseq();">Save</button>
                    <button id="" class="button-modal" onclick="hideElementById('newseq');"  class="closebtn">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- edit Sequence -->
    <div id="editseq" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w3-animate-zoom" style="width: 70%">
                <header class="w3-container modal-header">
                    <span onclick="hideElementById('editseq');"
                        class="w3-button w3-red w3-display-topright" style="width: 50px; margin: 3px;">&times;</span>
                    <h3 id='modal_title'>New Seq</h3>
                </header>

                <div class="modal-body">
                    <form id="new_seq_form" style="padding-left: 5%">
                        <div class="row">
                            <div for="job-id" class="col-6 t1">Job ID :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="job_id" maxlength="" value="<?php echo $data['job_id'];?>" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="seq-id" class="col-6 t1">Seq ID :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="old_seqid" maxlength="" value="">
                            </div>
                        </div>
                        <div class="row">
                            <div for="seq-name" class="col-6 t1">Seq Name :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="edit_seq_name" maxlength="" value=''>
                            </div>
                        </div>
                        <div class="row">
                            <div for="Tighten-Repeat" class="col-6 t1">Tighten Repeat :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="edit_tighten_repeat" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="ok-time" class="col-6 t1">OK Time (sec) :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="edit_ok_time" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="okall-alarm" class="col-6 t1">OK All Alarm Time (sec) :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="edit_okall_alarm" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="k(30%-300%)" class="col-6 t1">K (30%-300%) :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="edit_K" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="offset" class="col-6 t1">Offset :</div>
                            <div class="col-4 t2">
                                <input type="text" class="form-control input-ms" id="edit_offset" maxlength="" >
                            </div>
                        </div>
                        <div class="row">
                            <div for="torque-unit" class="col-6 t1">Torque Unit :</div>
                            <div class="col-4 t2">
                                <select id="edit_torque_unit" class="col custom-file">
                                   <?php foreach($data['unit_arr'] as $k_unit => $v_unit){?>
                                      <option value="<?php echo $k_unit;?>"><?php echo $v_unit;?></option>
                                   <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div for="NG-stop" class="col-6 t1">NG Stop :</div>
                            <div class="col-4 t2">
                                <select id="edit_ng_stop" class="col custom-file">
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div for="join" class="col-6 t1">Join :</div>
                            <div class="col t2" >
            			      	<div class="col-4 form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_join_option" id="soft" value="0">
            					  <label class="form-check-label" for="soft">Soft</label>
            					</div>
            					<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_join_option" id="hard" value="1" >
            					  <label class="form-check-label" for="hard">Hard</label>
            					</div>
                            </div>
                        </div>
                        <div class="row">
                            <div for="okall-stop" class="col-6 t1">OK All Stop :</div>
                            <div class="col t2" >
            			      	<div class="col-4 form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_okall_stop_option" id="Okall_FF" value="0">
            					  <label class="form-check-label" for="Okall_OFF">OFF</label>
            					</div>
            					<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_okall_stop_option" id="Okall_ON" value="1" >
            					  <label class="form-check-label" for="Okall_ON">ON</label>
            					</div>
                            </div>
                        </div>
                        <div class="row">
                            <div for="OPT" class="col-6 t1">OPT :</div>
                            <div class="col t2" >
            			      	<div class=" col-4 form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_opt_option" id="OPT_OFF" value="0">
            					  <label class="form-check-label" for="OPT_OFF">OFF</label>
            					</div>
            					<div class="form-check form-check-inline">
            					  <input class="form-check-input" type="radio" name="edit_opt_option" id="OPT_ON" value="1">
            					  <label class="form-check-label" for="OPT_ON">ON</label>
            					</div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <button id="" class="button-modal" onclick="edit_seq_save();">Save</button>
                    <button id="" class="button-modal" onclick="hideElementById('editseq');"  class="closebtn">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Copy Sequence -->
    <div id="copyseq" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w3-animate-zoom" style="width: 60%">
                <header class="w3-container modal-header">
                    <span onclick="document.getElementById('copyseq').style.display='none'"
                        class="w3-button w3-red w3-display-topright" style="width: 50px; margin: 3px;">&times;</span>
                    <h3 id='modal_title'>Copy Seq</h3>
                </header>

                <div class="modal-body">
                    <form id="new_seq_form">
        	            <label for="from_seq_id" class="col col-form-label" style="font-weight: bold">Copy From</label>
        	            <div style="padding-left: 10%">
        		            <div class="row">
        				        <label for="from_seq_id" class="t1 col-4 col-form-label">Seq ID :</label>
        				        <div class="col-5 t2 ">
        				            <input type="number" class="form-control" id="from_seq_id" disabled>
        				        </div>
        				    </div>
        				    <div class="row">
        				        <label for="from_seq_name" class="t1 col-4 col-form-label">Seq Name :</label>
        				        <div class="t2 col-5">
        				            <input type="text" class="form-control" id="from_seq_name" disabled>
        				        </div>
        				    </div>
        			    </div>

        			    <label for="from_seq_id" class="col col-form-label" style="font-weight: bold">Copy To</label>
        			    <div style="padding-left: 10%">
        				    <div class="row">
        				        <label for="to_seq_id" class="t1 col-4 col-form-label">Seq ID</label>
        				        <div class="t2 col-5">
        				            <input type="number" class="form-control" id="to_seq_id">
        				        </div>
        				    </div>
        				    <div class="row">
        				        <label for="to_seq_name" class="t1 col-4 col-form-label">Seq Name</label>
        				        <div class="t2 col-5">
        				            <input type="text" class="form-control" id="to_seq_name">
        				        </div>
        				    </div>
        			    </div>
        			  </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <button id="" class="button-modal">Save</button>
                    <button id="" class="button-modal" onclick="document.getElementById('copyseq').style.display='none'" class="closebtn">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
// Change the color of a row in a table
$(document).ready(function () {
    highlight_row('seq_table');
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
var modal = document.getElementById('newseq');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Add Step
/*document.getElementById('Add_Step').onclick = function() {
    window.location.href = './tcc_step.html';
};*/

// Button Return
document.getElementById('back_btn').onclick = function()
{
    window.location.href = '?url=Jobs/index';
};

var rows = document.getElementsByTagName("tr");
for (var i = 0; i < rows.length; i++) {
    (function(row) {
        var cells = row.getElementsByTagName("td");
        if (cells.length > 0) {
            cells[0].addEventListener("click", function() {
           
                var seqid   = cells[0] ? (cells[0].textContent || cells[0].innerText) : null;
                var seqname = cells[1] ? (cells[1].textContent || cells[1].innerText) : null;
        
                localStorage.setItem("seqid", seqid);
                localStorage.setItem("seqname", seqname);

            });
        }
    })(rows[i]);
}

function MoveUp() {
    var table,
        row = this.parentNode;
    var index = this.parentNode.parentNode.rowIndex;

    while (row != null) {
        if (row.nodeName == 'TR') {
            break;
        }
        row = row.parentNode;
    }
    
    table = row.parentNode;
    $(this.parentNode.parentNode).removeClass('selected');

    if (index > 1) {
        swap_row(row, 'up');
    } else {
        alert('已經到達頂部！');
    }
}

function MoveDown() {
    var table,
        row = this.parentNode;
    var index = this.parentNode.parentNode.rowIndex;

    while (row != null) {
        if (row.nodeName == 'TR') {
            break;
        }
        row = row.parentNode;
    }

    table = row.parentNode;
    $(this.parentNode.parentNode).removeClass('selected'); // 保持up down selected

    if (index < table.rows.length) {
        swap_row(row, 'down');
    } else {
        alert('已經到達底部！');
    }
}

function swap_row(row, direction) {
    if (direction === 'up') {
        var prevRow = row.previousElementSibling;
        if (prevRow.nodeName === 'TR') {
            row.parentNode.insertBefore(row, prevRow);
        } else {
            alert("已經到達頂部！");
        }
    } else if (direction === 'down') {
        var nextRow = row.nextElementSibling;
        if (nextRow) {
            row.parentNode.insertBefore(nextRow, row);
        } else {
            alert("已經到達底部！");
        }
    }
}



function cound_job(argument){

    var seqid = readFromLocalStorage("seqid");
    if(argument == 'del' && seqid != null){
        delete_seqid(seqid);
    }

    if(argument =="edit" && seqid != null){
        edit_seq(seqid);
    }

    if(argument =="new"){
        create_seq();
    }

    /*if(argument =="copy" && jobid != null){
        copy_job(jobid);
    }*/


}


function  delete_seqid(seqid){
    var jobid = '<?php echo $data['job_id']?>';
    if (jobid) {
        $.ajax({
            url: "?url=Sequences/delete_seq",
            method: "POST",
            data:{ 
                jobid: jobid,
                seqid: seqid
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

function create_seq() {
    document.getElementById('newseq').style.display = 'block';
    saveseq();

}

function edit_seq() {
    var seqid = readFromLocalStorage("seqid");
    var jobid = '<?php echo $data['job_id']?>';

    if(jobid){
        $.ajax({
            url: "?url=Sequences/search_seqinfo",
            method: "POST",
            data:{ 
                jobid: jobid,
                seqid: seqid
            },
            success: function(response) {
                var responseJSON = JSON.stringify(response);
                var cleanString = responseJSON.replace(/Array|\\n/g, '');
                var cleanString = cleanString.substring(2, cleanString.length - 2);

                var [, jobid] = cleanString.match(/\[job_id]\s*=>\s*([^ ]+)/) || [, null];
                var [, seqid] = cleanString.match(/\[sequence_id]\s*=>\s*([^ ]+)/) || [, null];
                var [, seqname] = cleanString.match(/\[sequence_name]\s*=>\s*([^ ]+)/) || [, null];
                var [, tightening_repeat] = cleanString.match(/\[tightening_repeat]\s*=>\s*([^ ]+)/) || [, null];
                var [, ok_time] = cleanString.match(/\[ok_time]\s*=>\s*([^ ]+)/) || [, null];
                var [, okall_alarm_time] = cleanString.match(/\[okall_alarm_time]\s*=>\s*([^ ]+)/) || [, null];
                var [, k_value] = cleanString.match(/\[k_value]\s*=>\s*([^ ]+)/) || [, null];
                var [, offset] = cleanString.match(/\[offset]\s*=>\s*([^ ]+)/) || [, null];
                var [, ng_stop] = cleanString.match(/\[ng_stop]\s*=>\s*([^ ]+)/) || [, null];
                var [, torque_unit] = cleanString.match(/\[torque_unit]\s*=>\s*([^ ]+)/) || [, null];
                var [, screw_join] = cleanString.match(/\[screw_join]\s*=>\s*([^ ]+)/) || [, null];
                var [, okall_stop] = cleanString.match(/\[okall_stop]\s*=>\s*([^ ]+)/) || [, null];
                var [, opt] = cleanString.match(/\[opt]\s*=>\s*([^ ]+)/) || [, null];
     
                document.getElementById('editseq').style.display = 'block';
                document.getElementById("old_seqid").value = seqid;
                document.getElementById("edit_seq_name").value = seqname;
                document.getElementById("edit_tighten_repeat").value = tightening_repeat;
                document.getElementById("edit_ok_time").value = ok_time;
                document.getElementById("edit_okall_alarm").value = okall_alarm_time;
                document.getElementById("edit_K").value = k_value;
                document.getElementById("edit_offset").value = offset;
                document.getElementById("edit_ng_stop").value = ng_stop;
                document.getElementsByName("edit_torque_unit").value = torque_unit;
                 
                var radioButtons = document.getElementsByName("edit_join_option");
                var radioButtons_1 = document.getElementsByName("edit_okall_stop_option");
                var radioButtons_2 = document.getElementsByName("edit_opt_option");

                //redio 取值
                setRadioButtonValue(radioButtons, screw_join);
                setRadioButtonValue(radioButtons_1, okall_stop);
                setRadioButtonValue(radioButtons_2, opt);

                //var oldSeqid = document.getElementById("old_seqid").value;
                var oldSeqname = document.getElementById("edit_seq_name").value;
                var oldTighteningRepeat = document.getElementById("edit_tighten_repeat").value;
                
                if (seqname !== oldSeqname || tightening_repeat !== oldTighteningRepeat ) {
                    edit_seq_save();

                }



                
                //edit_seq_save();
            },
            error: function(xhr, status, error) {
             
            }
        });
    }
}


function edit_seq_save(){

    var jobid = '<?php echo $data['job_id']?>';
    var seqid = readFromLocalStorage("seqid");

    var seq_name = document.getElementById("edit_seq_name").value;
    var tightening_repeat = document.getElementById("edit_tighten_repeat").value;
    var ok_time = document.getElementById("edit_ok_time").value;
    var okall_alarm_time = document.getElementById("edit_okall_alarm").value;
    var k_value = document.getElementById("edit_K").value;
    var offset = document.getElementById("edit_offset").value;
    var torque_unit = document.getElementById('edit_torque_unit').value;
    var ng_stop = document.getElementById('edit_ng_stop').value;
    var join_val = document.querySelector('input[name="edit_join_option"]:checked').value;
    var okall_stop_val = document.querySelector('input[name="edit_okall_stop_option"]:checked').value;
    var opt_val = document.querySelector('input[name="edit_opt_option"]:checked').value;
    
    console.log(seq_name);
    if(seq_name){
        $.ajax({
            url: "?url=Sequences/edit_seq",
            method: "POST",
            data:{ 
                jobid: jobid,
                seqid: seqid,
                seq_name: seq_name,
                tightening_repeat: tightening_repeat,
                ok_time: ok_time,
                okall_alarm_time: okall_alarm_time,
                k_value: k_value,
                offset: offset,
                torque_unit: torque_unit,
                ng_stop: ng_stop,
                join_val:join_val,
                okall_stop_val: okall_stop_val,
                opt_val: opt_val



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

function saveseq(){

    var jobid = '<?php echo $data['job_id']?>';
    var seqid = '<?php echo $data['seq_id']?>';
    var seq_name = document.getElementById("seq_name").value;
    var tighten_repeat = document.getElementById("tighten_repeat").value;
    var ok_time = document.getElementById("ok_time").value;
    var okall_alarm = document.getElementById("okall-alarm").value;
    var k_value = document.getElementById("K").value;
    var torque_unit_val = document.getElementById('torque_unit').value;
    var ng_stop = document.getElementById('ng_stop').value;
    var join_val = document.querySelector('input[name="join_option"]:checked').value;
    var okall_stop_val = document.querySelector('input[name="okall_stop_option"]:checked').value;
    var opt_val = document.querySelector('input[name="opt_option"]:checked').value;
    var offset = document.getElementById("offset").value;

    if(seq_name){
        $.ajax({
            url: "?url=Sequences/create_seq",
            method: "POST",
            data: { 
                jobid: jobid,
                seqid: seqid,
                seq_name: seq_name,
                tighten_repeat: tighten_repeat,
                ok_time: ok_time,
                okall_alarm: okall_alarm,
                k_value: k_value,
                torque_unit_val: torque_unit_val,
                join_val: join_val,
                okall_stop_val: okall_stop_val,
                opt_val: opt_val,
                ng_stop: ng_stop,
                offset: offset

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


function setRadioButtonValue(radioButtons, value) {
    for (var i = 0; i < radioButtons.length; i++) {
        if (radioButtons[i].value === value) {
            radioButtons[i].checked = true;
            break;
        }
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
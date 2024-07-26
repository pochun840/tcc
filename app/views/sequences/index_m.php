<div class="container-ms">
    <div class="w3-text-white w3-center">
        <div class="w3-text-white w3-center">
            <header id="header">
 	            <h3><?php echo $text['seq_management'];?></h3>
            </header>
        </div>
    </div>

    <div class="main-content">
        <div class="center-content">
            <div class="topnav">
                <label style="font-size:3vmin;color: #000; padding-left: 2%" for="job_id"><?php echo $text['job_id'];?> :</label>&nbsp;&nbsp;
                <input type="text" id="job_id" name="job_id" size="10" maxlength="20" value="<?php echo $data['job_id'];?>" disabled
                style="height:28px; font-size:3vmin;text-align: center; background-color: #DDDDDD; border:0; margin: 3px;">

                <button id="back_btn" type="button" onclick="window.history.back()" ><?php echo $text['return'];?></button>
            </div>

            <div class="table-container">
                <div class="scrollbar" id="style-seqtable">
                    <div class="force-overflow">
                        <table id="seq_table" class="table w3-table-all w3-hoverable">
                            <thead id="header-table">
                                <tr class="w3-dark-grey" style="font-size: 2.4vmin">
                                    <th><?php echo $text['seq_id'];?></th>
                                    <th><?php echo $text['seq_name'];?></th>
                                    <th><?php echo $text['column_unit'];?></th>
                                    <th><?php echo $text['tightening_repeat'];?></th>
                                    <th><?php echo $text['enable'];?></th>
                                    <th><?php echo $text['up'];?></th>
                                    <th><?php echo $text['down'];?></th>
                                    <th><?php echo $text['total_step'];?></th>
                                    <th><?php echo $text['add_step'];?></th>
                                </tr>
                            </thead>

                            <tbody style="font-size: 2vmin;text-align: center;">
                                <?php foreach($data['sequences'] as $key =>$val) {?>
                                    <tr>
                                        <td class="seq-id"> <?php echo $val['sequence_id'];?></td>
                                        <td class="seq-name"><?php echo $val['sequence_name'];?></td>
                                        <td><?php echo $text[$data['unit_arr'][$val['torque_unit']]];?></td>
                                        <td><?php echo $val['tightening_repeat'];?></td>
                                        <td>
                                            <?php if($val['sequence_enable']== 1){?>
                                                <input class="seq_enable" style="zoom:1.5; vertical-align: middle" data-sequence-id="<?php echo $val['sequence_id'];?>" id="sequence_enable"   value="1"  type="checkbox" onclick="updateValue(this)"  checked>
                                            <?php }else{?>
                                                <input class="seq_enable" style="zoom:1.5; vertical-align: middle" data-sequence-id="<?php echo $val['sequence_id'];?>" id="sequence_enable"   value="0"  type="checkbox" onclick="updateValue(this)">
                                            <?php }?>
                                        </td>
                                        <td><img src="./img/btn_up.png"   onclick="MoveUp(this);"></td>
                                        <td><img src="./img/btn_down.png" onclick="MoveDown(this);"></td>
                                        <td><?php echo $val['total_step'];?></td>
                                        <?php $url ='?url=Step/index/'.$data['job_id']."/".$val['sequence_id'];?>
                                        <td><img id="Add_Step" src="./img/btn_plus.png" onclick="location.href='<?php echo $url;?>'"></td>
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
                <div style="color:black; float: right; margin: 2px"><?php echo $text['total_seq'];?> :
                    <label id="RecordCnt" name="RecordCnt" type="text" style="margin-right: 20px"><?php echo count($data['sequences']); ?></label>
                </div>
            </div>
        </div>

        <div class="buttonbox">
            <input id="S3" name="Seq_Manager_Submit" type="button" value="<?php echo $text['New'];?>" tabindex="1"  onclick="cound_job('new');">
            <input id="S6" name="Seq_Manager_Submit" type="button" value="<?php echo $text['Edit'];?>" tabindex="1" onclick="cound_job('edit');">
            <input id="S5" name="Seq_Manager_Submit" type="button" value="<?php echo $text['Copy'];?>" tabindex="1" onclick="cound_job('copy');">
            <input id="S4" name="Seq_Manager_Submit" type="button" value="<?php echo $text['Delete'];?>" tabindex="1" onclick="cound_job('del');">
        </div>
    </div>

    <!-- Add New Sequence -->
    <div id="newseq" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w3-animate-zoom" style="width: auto">
                <header class="w3-container modal-header">
                    <span onclick="hideElementById('newseq');"
                        class="w3-button w3-red w3-display-topright" style="width: 50px; height: 43px;font-size: 4.5vmin; margin: 3px">&times;</span>
                    <h3 id='modal_title'><?php echo $text['new_seq'];?></h3>
                </header>
                <div class="scrollbar-newseq" id="style-newseq">
                    <div class="newseq-force-overflow">
                        <div class="modal-body" style="font-size: 14px">
                            <form id="new_seq_form" style="padding-left: 5%">
                                <div class="row">
                                    <div for="job-id" class="col-6 t1"><?php echo $text['job_id'];?>:</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="job_id" maxlength="" value ="<?php echo $data['job_id'];?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="seq-id" class="col-6 t1"><?php echo $text['seq_id'];?>:</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="seq_id" maxlength="" value="<?php echo $data['seq_id'];?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="seq-name" class="col-6 t1"><?php echo $text['seq_name'];?>:</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="seq_name" maxlength="" >
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="Tighten-Repeat" class="col-6 t1"><?php echo $text['tighten_repeat'];?>:</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="tighten_repeat" maxlength="" >
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="ok-time" class="col-6 t1"><?php echo $text['ok_time'];?> (sec):</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="ok_time" maxlength="" >
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="okall-alarm" class="col-6 t1"><?php echo $text['ok_all_alarm_time'];?> (sec):</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="okall-alarm" maxlength="" >
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="k(30%-300%)" class="col-6 t1">K (30%-300%):</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="K" maxlength="" >
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="offset" class="col-6 t1"><?php echo $text['Joint_Offset'];?>:</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="offset" maxlength="" >
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="torque-unit" class="col-6 t1"><?php echo $text['torque_unit'];?>:</div>
                                    <div class="col-4 t2">
                                    <select id="torque_unit" class="col custom-file">
                                        <?php foreach($data['unit_arr'] as $k_unit => $v_unit){?>
                                            <option value="<?php echo $k_unit;?>"><?php echo $text[$v_unit];?></option>
                                        <?php }?>
                                    </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="NG-stop" class="col-6 t1"><?php echo $text['NG_Stop'];?>:</div>
                                    <div class="col-4 t2">
                                        <select id="NG_stop" class="col custom-file">
                                            <?php for($i=0;$i<=9;$i++) {?>
                                                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                            <?php } ?> 
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="join" class="col-6 t1"><?php echo $text['join'];?>:</div>
                                    <div class="col t2" >
                    			      	<div class="col-4 form-check form-check-inline">
                    					  <input class="form-check-input" type="radio" name="join_option" id="soft" value="1">
                    					  <label class="form-check-label" for="soft"><?php echo $text['soft'];?></label>
                    					</div>
                    					<div class="form-check form-check-inline">
                    					  <input class="form-check-input" type="radio" name="join_option" id="hard" value="2" checked="checked">
                    					  <label class="form-check-label" for="hard"><?php echo $text['hard'];?></label>
                    					</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="okall-stop" class="col-6 t1"><?php echo $text['OK_All_Stop'];?>:</div>
                                    <div class="col t2" >
                    			      	<div class="col-4 form-check form-check-inline">
                    					  <input class="form-check-input" type="radio" name="okall_stop_option" id="Okall_OFF" value="0">
                    					  <label class="form-check-label" for="Okall_OFF"><?php echo $text['switch_off'];?></label>
                    					</div>
                    					<div class="form-check form-check-inline">
                    					  <input class="form-check-input" type="radio" name="okall_stop_option" id="Okall_ON" value="1" checked="checked">
                    					  <label class="form-check-label" for="Okall_ON"><?php echo $text['switch_on'];?></label>
                    					</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="OPT" class="col-6 t1"><?php echo $text['opt'];?> :</div>
                                    <div class="col t2" >
                    			      	<div class=" col-4 form-check form-check-inline">
                    					  <input class="form-check-input" type="radio" name="opt_option" id="OPT_OFF" value="0">
                    					  <label class="form-check-label" for="OPT_OFF"><?php echo $text['switch_off'];?></label>
                    					</div>
                    					<div class="form-check form-check-inline">
                    					  <input class="form-check-input" type="radio" name="opt_option" id="OPT_ON" value="1" checked="checked">
                    					  <label class="form-check-label" for="OPT_ON"><?php echo $text['switch_on'];?></label>
                    					</div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>    
                

                <div class="modal-footer justify-content-center">
                <button id="" class="button-modal" onclick="saveseq();"><?php echo $text['save'];?></button>
                <button id="" class="button-modal" onclick="hideElementById('newseq');"  class="closebtn"><?php echo $text['close'];?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- edit Sequence -->
    <div id="editseq" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w3-animate-zoom" style="width: auto">
                <header class="w3-container modal-header">
                    <span onclick="hideElementById('editseq');"
                        class="w3-button w3-red w3-display-topright" style="width: 50px; height: 43px;font-size: 4.5vmin; margin: 3px">&times;</span>
                    <h3 id='modal_title'>Edit Seq</h3>
                </header>
                <div class="scrollbar-newseq" id="style-newseq">
                    <div class="newseq-force-overflow">
                        <div class="modal-body" style="font-size: 14px">
                            <form id="new_seq_form" style="padding-left: 5%">
                                <div class="row">
                                    <div for="job-id" class="col-6 t1"><?php echo $text['job_id'];?></div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="job_id" maxlength="" value ="<?php echo $data['job_id'];?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="seq-id" class="col-6 t1"><?php echo $text['seq_id'];?>:</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="old_seqid" maxlength="" value="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="seq-name" class="col-6 t1"><?php echo $text['seq_name'];?>:</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="edit_seq_name" maxlength="" >
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="Tighten-Repeat" class="col-6 t1"><?php echo $text['tightening_repeat'];?>:</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="edit_tighten_repeat" maxlength="" >
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="ok-time" class="col-6 t1"><?php echo $text['ok_time'];?> (sec):</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="edit_ok_time" maxlength="" >
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="okall-alarm" class="col-6 t1"><?php echo $text['ok_all_alarm_time'];?> (sec):</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="edit_okall_alarm" maxlength="" >
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="k(30%-300%)" class="col-6 t1">K (30%-300%):</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="edit_K" maxlength="" >
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="offset" class="col-6 t1"><?php echo $text['Joint_Offset'];?>:</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="edit_offset" maxlength="" >
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="edit_torque_unit" class="col-6 t1"><?php echo $text['torque_unit'];?>:</div>
                                    <div class="col-4 t2">
                                    <select id="edit_torque_unit" class="col custom-file">
                                        <?php foreach($data['unit_arr'] as $k_unit => $v_unit){?>
                                            <option value="<?php echo $k_unit;?>"><?php echo $text[$v_unit];?></option>
                                        <?php }?>
                                    </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="NG-stop" class="col-6 t1"><?php echo $text['NG_Stop'];?>:</div>
                                    <div class="col-4 t2">
                                        <select id="edit_ng_stop" class="col custom-file">
                                            <?php for($i=0;$i<=9;$i++) {?>
                                            <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                            <?php } ?>    
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="join" class="col-6 t1"><?php echo $text['join'];?>:</div>
                                    <div class="col t2" >
                    			      	<div class="col-4 form-check form-check-inline">
                    					  <input class="form-check-input" type="radio" name="edit_join_option" id="soft" value="0">
                    					  <label class="form-check-label" for="soft"><?php echo $text['soft'];?></label>
                    					</div>
                    					<div class="form-check form-check-inline">
                                          <input class="form-check-input" type="radio" name="edit_join_option" id="hard" value="1" >
                    					  <label class="form-check-label" for="hard"><?php echo $text['hard'];?></label>
                    					</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="okall-stop" class="col-6 t1"><?php echo $text['OK_All_Stop'];?>:</div>
                                    <div class="col t2" >
                    			      	<div class="col-4 form-check form-check-inline">
                                          <input class="form-check-input" type="radio" name="edit_okall_stop_option" id="Okall_OFF" value="0">
                    					  <label class="form-check-label" for="Okall_OFF"><?php echo $text['switch_off'];?></label>
                    					</div>
                    					<div class="form-check form-check-inline">
                                          <input class="form-check-input" type="radio" name="edit_okall_stop_option" id="Okall_ON" value="1" >
                    					  <label class="form-check-label" for="Okall_ON"><?php echo $text['switch_on'];?></label>
                    					</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="OPT" class="col-6 t1"><?php echo $text['opt'];?>:</div>
                                    <div class="col t2" >
                    			      	<div class=" col-4 form-check form-check-inline">
                    					  <input class="form-check-input" type="radio" name="edit_opt_option" id="OPT_ON" value="0">
                    					  <label class="form-check-label" for="OPT_ON"><?php echo $text['switch_on'];?></label>
                    					</div>
                    					<div class="form-check form-check-inline">
                    					  <input class="form-check-input" type="radio" name="edit_opt_option" id="OPT_OFF" value="1">
                    					  <label class="form-check-label" for="OPT_OFF"><?php echo $text['switch_off'];?></label>
                    					</div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>    
                <div class="modal-footer justify-content-center">
                    <button id="" class="button-modal" onclick="edit_seq_save();"><?php echo $text['save'];?></button>
                    <button id="" class="button-modal" onclick="hideElementById('editseq');"  class="closebtn"><?php echo $text['close'];?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Copy Sequence -->
    <div id="copyseq" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w3-animate-zoom" style="width: auto">
                <header class="w3-container modal-header">
                    <span onclick="document.getElementById('copyseq').style.display='none'"
                        class="w3-button w3-red w3-display-topright" style="width: 50px; height: 43px;font-size: 4.5vmin; margin: 3px">&times;</span>
                    <h3 id='modal_title'><?php echo $text['Copy_Sequence'];?></h3>
                </header>

                <div class="modal-body">
                    <form id="new_seq_form">
        	            <label for="from_seq_id" class="col col-form-label" style="font-weight: bold"><?php echo $text['copy_from'];?></label>
        	            <div style="padding-left: 10%">
        		            <div class="row">
        				        <label for="from_seq_id" class="t1 col-4 col-form-label"><?php echo $text['seq_id'];?> :</label>
        				        <div class="col-5 t2 ">
        				            <input type="text" class="form-control" id="from_seq_id" disabled>
        				        </div>
        				    </div>
        				    <div class="row">
        				        <label for="from_seq_name" class="t1 col-4 col-form-label"><?php echo $text['seq_name'];?> :</label>
        				        <div class="t2 col-5">
        				            <input type="text" class="form-control" id="from_seq_name" disabled>
        				        </div>
        				    </div>
        			    </div>

        			    <label for="from_seq_id" class="col col-form-label" style="font-weight: bold"><?php echo $text['copy_to'];?></label>
        			    <div style="padding-left: 10%">
        				    <div class="row">
        				        <label for="to_seq_id" class="t1 col-4 col-form-label"><?php echo $text['seq_id'];?> :</label>
        				        <div class="t2 col-5">
        				            <input type="number" class="form-control" id="to_seq_id">
        				        </div>
        				    </div>
        				    <div class="row">
        				        <label for="to_seq_name" class="t1 col-4 col-form-label"><?php echo $text['seq_name'];?> :</label>
        				        <div class="t2 col-5">
        				            <input type="text" class="form-control" id="to_seq_name">
        				        </div>
        				    </div>
        			    </div>
        			  </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <button id="" class="button-modal" onclick="copy_seq_by_id()"><?php echo $text['save'];?></button>
                    <button id="" class="button-modal" onclick="hideElementById('copyseq');" class="closebtn"><?php echo $text['close'];?></button>
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



// Get the modal
var modal = document.getElementById('newseq');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}


var rowInfoArray = [];
<?php foreach($data['sequences'] as $key =>$val) {?>
        var sequenceId = "<?php echo $val['sequence_id'];?>";
        var sequenceName = "<?php echo $val['sequence_name'];?>";
        
        var rowInfo = {
            sequence_id: sequenceId,
            sequence_name: sequenceName
        };
        
        rowInfoArray.push(rowInfo);
<?php } ?>

var seqid = ''; 
var seqname = '';
var rows = document.getElementsByTagName("tr");
for (var i = 0; i < rows.length; i++) {
    (function(row) {
        var cells = row.getElementsByTagName("td");
        if (cells.length > 0) {
            cells[0].addEventListener("click", function() {
                seqid = cells[0] ? (cells[0].textContent || cells[0].innerText) : null;
                seqname = cells[1] ? (cells[1].textContent || cells[1].innerText) : null;
            
                localStorage.setItem("seqid", seqid);
                localStorage.setItem("seqname", seqname);
            });
        }
    })(rows[i]);
}

function copy_seq_by_id(){

    var jobid = '<?php echo $data['job_id'];?>';
    var oldseqname = seqname;
    var seqid = readFromLocalStorage('seqid');
    document.getElementById("from_seq_name").value = oldseqname;
    var newseqid = document.getElementById('to_seq_id').value;
    document.getElementById('from_seq_id').value=  seqid;

    var newseqname = document.getElementById("to_seq_name").value;    

    if(newseqname){
        $.ajax({
            url: "?url=Sequences/check_seq_type",
            method: "POST",
            data:{ 
                jobid:jobid,
                newseqid: newseqid

            },
            success: function(response) {
                alertify.confirm("Are you sure?", function (result) {
                if(result){
                    $.ajax({
                        url: "?url=Sequences/copy_seq_data",
                        method: "POST",
                        data:{ 
                            jobid: jobid,
                            seqid: seqid,
                            oldseqname: oldseqname,
                            newseqid: newseqid,
                            newseqname: newseqname
                        },
                        success: function(response) {

                            
                            var responseData = JSON.parse(response);
                            alertify.alert(responseData.res_type, responseData.res_msg, function() {
                                history.go(0);
                            });
                            //console.log(response);
                        },
                        error: function(xhr, status, error) {
                            
                        }
                    });

                    //alert('eeew');
                }else {
                    alertify.error('Cancelled');
                    // 用户点击取消按钮的处理逻辑
                }
                });
                        },
            error: function(xhr, status, error) {
                
            }
        });
        
    }

}

function copy_seq(seqid){
    
    document.getElementById('copyseq').style.display = 'block';   
    copy_seq_by_id(seqid);
}


function delete_seqid(jobid,seqid){
    var jobid = '<?php echo $data['job_id']?>';
    var seqid = readFromLocalStorage('seqid');
    if (jobid) {
        $.ajax({
            url: "?url=Sequences/delete_seq",
            method: "POST",
            data:{ 
                jobid: jobid,
                seqid: seqid
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

function create_seq() {
    document.getElementById('newseq').style.display = 'block';
    saveseq();

}

function edit_seq(seqid) {
    var jobid = '<?php echo $data['job_id']?>';
    var seqid = readFromLocalStorage('seqid');
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

                var oldSeqname = document.getElementById("edit_seq_name").value;
                var oldTighteningRepeat = document.getElementById("edit_tighten_repeat").value;
                var oldtorque_unit = document.getElementsByName("edit_torque_unit").value;

               
            },
            error: function(xhr, status, error) {
             
            }
        });
    }
}

function edit_seq_save(){

    var jobid = '<?php echo $data['job_id']?>';

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

function updateValue(checkbox){
    var jobid = '<?php echo $data['job_id']?>';
    var check_seqid = checkbox.getAttribute('data-sequence-id');
    var type_value = checkbox.checked ? 1 : 0;
    console.log(check_seqid);
    console.log(type_value);
    if(jobid){
            $.ajax({
            url: "?url=Sequences/check_seq_type",
            method: "POST",
            data:{ 
                jobid: jobid,
                seqid: check_seqid,
                type_value:type_value

            },
            success: function(response) {
                history.go(0);
            },
            error: function(xhr, status, error) {
                
            }
        });
    } 
}

</script>
<script>
    
<?php foreach($data['sequences'] as $key =>$val) {?>
    var sequenceId = "<?php echo $val['sequence_id'];?>";
    var sequenceName = "<?php echo $val['sequence_name'];?>";

    var exists = rowInfoArray.some(function(item) {
        return item.sequence_id === sequenceId || item.sequence_name === sequenceName;
    });

    if (!exists) {
        var rowInfo = {
            sequence_id: sequenceId,
            sequence_name: sequenceName
        };
        rowInfoArray.push(rowInfo);
    }
<?php } ?>


function sendRowInfoArray() {
    var jobid = '<?php echo $data['job_id']?>';
    var dataToSend = {
        jobid: jobid,
        rowInfoArray: rowInfoArray
    };

    $.ajax({
        url: "?url=Sequences/adjustment_order", 
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
</script>
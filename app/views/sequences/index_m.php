
<link rel="stylesheet" href="<?php echo URLROOT; ?>css/tcc_seq_m.css" type="text/css">
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
</style>

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

                <button id="back_btn" type="button" onclick="window.location.href='?url=Jobs/index'"><?php echo $text['return'];?></button>
            </div>

            <div class="table-container">
                <div class="scrollbar" id="style-seqtable">
                    <div class="force-overflow">
                        <table id="seq_table" class="table w3-table-all w3-hoverable">
                            <thead id="header-table">
                                <tr class="w3-dark-grey" style="font-size: 2.4vmin">
                                    <th><?php echo $text['seq_id'];?></th>
                                    <th><?php echo $text['seq_name'];?></th>
                                    <th><?php echo $text['tr'];?></th>
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
                                        <td class="seq-id"> <?php echo $val['seq_id'];?></td>
                                        <td class="seq-name"><?php echo $val['seq_name'];?></td>
                                        <td><?php echo $val['tr'];?></td>
                                        <td>
                                            <?php if($val['seq_en']== 1){?>
                                                <input class="seq_enable" style="zoom:1.5; vertical-align: middle" data-sequence-id="<?php echo $val['seq_id'];?>" id="seq_en"   value="1"  type="checkbox" onclick="updateValue(this)"  checked>
                                            <?php }else{?>
                                                <input class="seq_enable" style="zoom:1.5; vertical-align: middle" data-sequence-id="<?php echo $val['seq_id'];?>" id="seq_en"   value="0"  type="checkbox" onclick="updateValue(this)">
                                            <?php }?>
                                        </td>
                                        <td><img src="./img/btn_up.png"   onclick="MoveUp(this);"></td>
                                        <td><img src="./img/btn_down.png" onclick="MoveDown(this);"></td>
                                        <td><?php echo $val['total_step'];?></td>
                                        <?php $url ='?url=Step/index/'.$data['job_id']."/".$val['seq_id'];?>
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
        <?php $status = count($data['sequences']) >=  50 ? 'disabled' : ''; ?>

            <input id="S3" name="Seq_Manager_Submit" type="button" value="<?php echo $text['New'];?>" tabindex="1"  onclick="cound_job('new');" <?php echo $status;?> >
            <input id="S6" name="Seq_Manager_Submit" type="button" value="<?php echo $text['Edit'];?>" tabindex="1" onclick="cound_job('edit');">
            <input id="S5" name="Seq_Manager_Submit" type="button" value="<?php echo $text['Copy'];?>" tabindex="1" onclick="cound_job('copy');" <?php echo $status;?> >
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
                                        <input type="text" class="form-control input-ms" id="job_id" maxlength="" value ="<?php echo $data['job_id'];?>" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="seq-id" class="col-6 t1"><?php echo $text['seq_id'];?>:</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="seq_id" maxlength="" value="<?php echo $data['seq_id'];?>" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="seq-name" class="col-6 t1"><?php echo $text['seq_name'];?>:</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="seq_name" maxlength="" value ='<?php echo "SEQ"."-".$data['seq_id'];?>'>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="Tighten-Repeat" class="col-6 t1"><?php echo $text['tr'];?>:</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="tr" maxlength="" >
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="OK_Sequence" class="col-6 t1"><?php echo $text['OK_Sequence'];?> :</div>
                                    <div class="col t2" >

                                        <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="seq_ok" id="seq_off" value="0" >
                                        <label class="form-check-label" for="seq_off"> <?php  echo $text['OFF_text']; ?></label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="seq_ok" id="seq_ok" value="1">
                                        <label class="form-check-label" for="seq_ok"><?php  echo $text['ON_text']; ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div for="OK_Sequence_Stop" class="col-6 t1"><?php echo $text['OK_Sequence_Stop'];?> :</div>
                                    <div class="col t2" >

                                        <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="stop_seq_ok" id="stop_seq_ok_off" value="0" >
                                        <label class="form-check-label" for="stop_seq_ok_off"> <?php  echo $text['OFF_text']; ?></label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="stop_seq_ok" id="stop_seq_ok_ok" value="1">
                                        <label class="form-check-label" for="stop_seq_ok_ok"><?php  echo $text['ON_text']; ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div for="k(30%-300%)" class="col-6 t1">K (30%-300%):</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="K" maxlength="" >
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="offset" class="col-6 t1"><?php echo $text['Joint_Offset'];?>:</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="ofs" maxlength="" >
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                              
                                <div class="row">
                                    <div for="NG-stop" class="col-6 t1"><?php echo $text['ns'];?>:</div>
                                    <div class="col-4 t2">
                                        <select id="ns" class="col custom-file">
                                            <?php for($i=0;$i<=9;$i++) {?>
                                                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                            <?php } ?> 
                                        </select>
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
                    					  <input class="form-check-input" type="radio" name="opt_option" id="OPT_ON" value="1" >
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
                    <h3 id='modal_title'><?php echo $text['edit_seq'];?></h3>
                </header>
                <div class="scrollbar-newseq" id="style-newseq">
                    <div class="newseq-force-overflow">
                        <div class="modal-body" style="font-size: 14px">
                            <form id="new_seq_form" style="padding-left: 5%">
                                <div class="row">
                                    <div for="job-id" class="col-6 t1"><?php echo $text['job_id'];?></div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="job_id" maxlength="" value ="<?php echo $data['job_id'];?>" >
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="seq-id" class="col-6 t1"><?php echo $text['seq_id'];?>:</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="old_seqid" maxlength="" value=""  disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="seq-name" class="col-6 t1"><?php echo $text['seq_name'];?>:</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="edit_seq_name" maxlength="" >
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="Tighten-Repeat" class="col-6 t1"><?php echo $text['tr'];?>:</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="edit_tr" maxlength="" >
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div for="OK_Sequence" class="col-6 t1"><?php echo $text['OK_Sequence'];?> :</div>
                                    <div class="col t2" >

                                        <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="edit_seq_ok" id="seq_off" value="0" >
                                        <label class="form-check-label" for="seq_off"> <?php  echo $text['OFF_text']; ?></label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="edit_seq_ok" id="seq_ok" value="1">
                                        <label class="form-check-label" for="seq_ok"><?php  echo $text['ON_text']; ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div for="OK_Sequence_Stop" class="col-6 t1"><?php echo $text['OK_Sequence_Stop'];?> :</div>
                                    <div class="col t2" >

                                        <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="edit_stop_seq_ok" id="stop_seq_ok_off" value="0" >
                                        <label class="form-check-label" for="stop_seq_ok_off"> <?php  echo $text['OFF_text']; ?></label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="edit_stop_seq_ok" id="stop_seq_ok_ok" value="1">
                                        <label class="form-check-label" for="stop_seq_ok_ok"><?php  echo $text['ON_text']; ?></label>
                                        </div>
                                    </div>
                                </div>


                                
                                <div class="row">
                                    <div for="k(30%-300%)" class="col-6 t1">K (30%-300%):</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="edit_K" maxlength="" >
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div for="offset" class="col-6 t1"><?php echo $text['Joint_Offset'];?>:</div>
                                    <div class="col-4 t2">
                                        <input type="text" class="form-control input-ms" id="edit_ofs" maxlength="" >
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                               
                                <div class="row">
                                    <div for="NG-stop" class="col-6 t1"><?php echo $text['ns'];?>:</div>
                                    <div class="col-4 t2">
                                        <select id="edit_ns" class="col custom-file">
                                            <?php for($i=0;$i<=9;$i++) {?>
                                            <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                            <?php } ?>    
                                        </select>
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
var modal = document.getElementById('newseq');
var seqid = '';

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
function cound_job(argument){
    var table = document.getElementById('seq_table');
    var selectedRow = table.querySelector('.selected');  
    var selectedRowData = selectedRow ? selectedRow.cells[0].innerText : null;
    var selectedRowData_name = selectedRow ? selectedRow.cells[1].innerText : null;
    seqid = selectedRowData;
    seqname = selectedRowData_name;
    
    
    if(argument == 'del' && seqid != null){
        delete_seqid(seqid);
    }

    if(argument =="edit" && seqid != null){
        
        edit_seq(seqid);
    }

    if(argument =="new"){
        create_seq();
    }

    if(argument =="copy" && seqid != null){
        copy_seq(seqid);
    }


}

var rowInfoArray = [];
<?php foreach($data['sequences'] as $key =>$val) {?>
        var sequenceId = "<?php echo $val['seq_id'];?>";
        var sequenceName = "<?php echo $val['seq_name'];?>";
        
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
    var newseqid = document.getElementById('to_seq_id').value;
    var newseqname = document.getElementById("to_seq_name").value;    

    var language = getCookie('language');
    if(language == "zh-cn"){
        var text_info ='你确定吗？';
    }else if(language == "zh-tw"){
        var text_info ='你確定嗎 ?';
    }else{
        var text_info ='Are you sure ?';
    }

    if(newseqname){
        $.ajax({
            url: "?url=Sequences/check_seq_type",
            method: "POST",
            data:{ 
                jobid:jobid,
                newseqid: newseqid

            },
            success: function(response) {
                alertify.confirm(text_info, function (result) {
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
                            console.log(response);
                            
                            var responseData = JSON.parse(response);
                            alertify.alert(responseData.res_type, responseData.res_msg, function() {
                                history.go(0);
                            });
                            
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
    document.getElementById('from_seq_id').value =seqid;
    document.getElementById('from_seq_name').value =seqname;
    copy_seq_by_id(seqid);
}



function delete_seqid(seqid){
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
    // 帶入預設值
    document.getElementById('newseq').style.display = 'block';
    document.getElementById('tr').value = 1;
    document.getElementById('seq_ok').checked = true;
    document.getElementById('K').value = 100;
    document.getElementById('stop_seq_ok_off').checked = true;
    document.getElementById('seq_off').checked = true;
    document.getElementById('OPT_OFF').checked = true;
    document.getElementById('ofs').value = 0;
    document.getElementById('ns').selectedIndex = 1;
    
}

function saveseq(){

    var jobid = '<?php echo $data['job_id']?>';
    var seqid = '<?php echo $data['seq_id']?>';
    var seq_name = document.getElementById("seq_name").value;
    var tr = document.getElementById("tr").value;
 
    var ns = document.getElementById('ns').value;

    var seqElement = document.querySelector('input[name="seq_ok"]:checked');
    var seq_ok = seqElement ? seqElement.value : null;

    var seq_stop_Element = document.querySelector('input[name="stop_seq_ok"]:checked');
    var stop_seq_ok = seq_stop_Element ? seq_stop_Element.value : null;

    var opt_val = getSelectedValue('opt_option', null);
    var k_value = document.getElementById("K").value;
    var ofs = document.getElementById("ofs").value;

    //驗證
    let check = input_check_saveseq();


    if(check){
        $.ajax({
            url: "?url=Sequences/create_seq",
            method: "POST",
            data: { 
                jobid: jobid,
                seqid: seqid,
                seq_name: seq_name,
                tr: tr,
                ns: ns,
                seq_ok:seq_ok,
                stop_seq_ok:stop_seq_ok,
                opt_val: opt_val,
                k_value: k_value,
                ofs: ofs

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



function edit_seq(seqid) {
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
                var [, seqid] = cleanString.match(/\[seq_id]\s*=>\s*([^ ]+)/) || [, null];
                var [, seqname] = cleanString.match(/\[seq_name]\s*=>\s*([^ ]+)/) || [, null];
                var [, tr] = cleanString.match(/\[tr]\s*=>\s*([^ ]+)/) || [, null];
                
                var [, k_value] = cleanString.match(/\[k_value]\s*=>\s*([^ ]+)/) || [, null];
                var [, ofs] = cleanString.match(/\[ofs]\s*=>\s*([^ ]+)/) || [, null];
                var [, ns] = cleanString.match(/\[ns]\s*=>\s*([^ ]+)/) || [, null];
                
                var [, opt] = cleanString.match(/\[opt]\s*=>\s*([^ ]+)/) || [, null];
                var [, seq_ok] = cleanString.match(/\[seq_ok]\s*=>\s*([^ ]+)/) || [, null];
                var [, stop_seq_ok] = cleanString.match(/\[stop_seq_ok]\s*=>\s*([^ ]+)/) || [, null];
                var [, opt_val] = cleanString.match(/\[opt]\s*=>\s*([^ ]+)/) || [, null];
               
   
        
                document.getElementById('editseq').style.display = 'block';
                document.getElementById("old_seqid").value = seqid;
                document.getElementById("edit_seq_name").value = seqname;
                document.getElementById("edit_tr").value = tr;

                document.getElementById("edit_K").value = k_value;
                document.getElementById("edit_ofs").value = ofs;
                document.getElementById("edit_ns").value = ns;
        
                var radioButtons_seq = document.getElementsByName("edit_seq_ok");
                setRadioButton_value(radioButtons_seq, seq_ok);

                var radioButtons_stop_seq = document.getElementsByName("edit_stop_seq_ok");
                setRadioButton_value(radioButtons_stop_seq, stop_seq_ok);


                var radioButtons_2 = document.getElementsByName("edit_opt_option");
                setRadioButton_value(radioButtons_2, opt_val);
  
            },
            error: function(xhr, status, error) {
             
            }
        });
    }
}

function edit_seq_save(){

    var jobid = '<?php echo $data['job_id']?>';

    var seq_name = document.getElementById("edit_seq_name").value;
    var tr = document.getElementById("edit_tr").value;
    var seq_ok = document.querySelector('input[name="edit_seq_ok"]:checked').value;
    var stop_seq_ok = document.querySelector('input[name="edit_stop_seq_ok"]:checked').value;
    var k_value = document.getElementById("edit_K").value;
    var ofs = document.getElementById("edit_ofs").value;
    var ns = document.getElementById('edit_ns').value;
    var opt_val = document.querySelector('input[name="edit_opt_option"]:checked').value;

    //驗證
    let check = input_check_editseq();
    
    if(check){
        $.ajax({
            url: "?url=Sequences/edit_seq",
            method: "POST",
            data:{ 
                jobid: jobid,
                seqid: seqid,
                seq_name: seq_name,
                tr: tr,
                seq_ok:seq_ok,
                stop_seq_ok:stop_seq_ok,
                k_value: k_value,
                ofs: ofs,
                ns: ns,
                opt_val: opt_val

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



function getSelectedValue(name, defaultValue = null) {
    var selectedOption = document.querySelector(`input[name="${name}"]:checked`);
    return selectedOption ? selectedOption.value : defaultValue;
}


function updateValue(element){
    var jobid = '<?php echo $data['job_id']?>';
    var seq_en = element.checked ? 1 : 0;
    var seqid = element.getAttribute('data-sequence-id');

    if(seqid){
        $.ajax({
            url: "?url=Sequences/check_seq_enable", 
            method: "POST",
            data: { 
                jobid: jobid,
                seqid: seqid,
                seq_en: seq_en
            },
            success: function(response) {
                console.log(response);
                history.go(0);
            },
            error: function(xhr, status, error) {
                console.error('AJAX 错误:', status, error); 
            }
        });    
    }


}
</script>
<script>
    
<?php foreach($data['sequences'] as $key =>$val) {?>
    var sequenceId = "<?php echo $val['seq_id'];?>";
    var sequenceName = "<?php echo $val['seq_name'];?>";

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
            console.log(response);
            history.go(0); 
        },
        error: function(xhr, status, error) {
            console.error('Error sending data:', error);
        }
    });
}

function goBackAndReload() {
    // 记录当前页面的 URL
    const currentUrl = window.location.href;
    // 用 replaceState 记录当前页面的状态
    window.history.replaceState({}, '', currentUrl);
    // 返回上一页
    window.history.back();
    // 设置标志来强制上一页刷新
    setTimeout(() => {
        // 刷新当前页，也就是上一页
        window.location.href = document.referrer + (document.referrer.includes('?') ? '&' : '?') + 'refresh=' + new Date().getTime();
    }, 100);
}


function setRadioButton_value(radioButtons, value) {
    radioButtons.forEach(function(button) {
        if (button.value === value.toString()) {
            button.checked = true;
        } else {
            button.checked = false;
        }
    });
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

function input_check_saveseq() {
    let conditions = [
        { id: 'seq_name', pattern: /^[a-zA-Z0-9\u4E00-\u9FA5\-]+$/, min: null, max: null },
        { id: 'tr', pattern: /^[0-9]+$/, min: 1, max: 99 },
        { id: 'K', pattern: /^(1\.[5-9]|([1-9][0-9]{0,2}|[1-9][0-9]{3,})(\.[0-9])?|999(\.[0-7])?|999\.8)$/, min: 1.5, max: 999.8 },
        { id: 'ofs', pattern: /^-?(25[0-4]|2[0-4][0-9]|[01]?[0-9]{1,2})$/, min: -254, max: 254 },

    ];

    let isFormValid = true;

    conditions.forEach(function(input) {
        var element = document.getElementById(input.id);
        if (input.id !== 'seq_name') {
            let nextSibling = element.nextElementSibling;
            if (nextSibling) {
                nextSibling.innerHTML = `${input.min} ~ ${input.max}`;
            } else {
                console.warn(`No next sibling found for element with id ${input.id}`);
            }
        }

        if (!validateInput(element, input.pattern, input.min, input.max)) {
            isFormValid = false;
        }
    });

    return isFormValid;
}

function input_check_editseq() {
    let conditions = [
        { id: 'edit_seq_name', pattern: /^[a-zA-Z0-9\u4E00-\u9FA5\-]+$/, min: null, max: null },
        { id: 'edit_tr', pattern: /^[0-9]+$/, min: 1, max: 99 },
        { id: 'edit_K', pattern: /^(1\.[5-9]|([1-9][0-9]{0,2}|[1-9][0-9]{3,})(\.[0-9])?|999(\.[0-7])?|999\.8)$/, min: 1.5, max: 999.8 },
        { id: 'edit_ofs', pattern: /^-?(25[0-4]|2[0-4][0-9]|[01]?[0-9]{1,2})$/, min: -254, max: 254 },
    ];

    let isFormValid = true;

    conditions.forEach(function(input) {
        var element = document.getElementById(input.id);
        if (input.id !== 'edit_seq_name') {
            let nextSibling = element.nextElementSibling;
            if (nextSibling) {
                nextSibling.innerHTML = `${input.min} ~ ${input.max}`;
            } else {
                console.warn(`No next sibling found for element with id ${input.id}`);
            }
        }

        if (!validateInput(element, input.pattern, input.min, input.max)) {
            isFormValid = false;
        }
    });

    return isFormValid;
}

</script>
<?php require APPROOT . 'views/inc/header.php'; ?>

<body>
<div class="container-ms">
    <div class="w3-text-white w3-center">
        <table class="no-border">
            <tr id="header">
                <td width="100%">
                    <h3>I/O Input</h3>
                </td>
                <td>
                    <img src="./img/btn_home.png" style="margin-right: 10px"  onclick="window.location.href = '?url=In';">
                </td>
            </tr>
        </table>
     </div>

    <div class="main-content">
        <div class="center-content">
            <div class="topnav">
                <label style="font-size:3vmin;color: #000; padding-left: 2%" for="job_id">Job ID :</label>&nbsp;
                <input type="text" id="job_id" name="job_id" size="8" maxlength="20" value="1" disabled style="height:30px; font-size:3vmin;text-align: center; background-color: #DDDDDD; border:0;">&nbsp;&nbsp;
                <button id="Button_Select" type="button" onclick="document.getElementById('JobSelect').style.display='block'">Select</button>
            </div>

            <!-- Job Select Modal -->
            <div id="JobSelect" class="modal" style="width: 325px;">
                <form class="w3-modal-content w3-animate-zoom" style="top: 13%;" action="">
                    <div class="w3-light-grey">
                        <header class="w3-container w3-dark-grey" style="height: 48px">
                            <span onclick="document.getElementById('JobSelect').style.display='none'" class="w3-button w3-red w3-large w3-display-topright" style="margin: 2px">&times;</span>
                            <h3 style="margin: 5px" onclick="get_job_list()">Job Select</h3>
                        </header>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-2 t2" style="margin-left: 3px">
                                    <select style="margin: center" id="JobNameSelect" name="JobNameSelect" size="200">
                                        <?php foreach($data['job_list'] as $key =>$val){?>
                                            <option value="<?php echo $val['job_id'];?>"><?php echo $val['job_name'];?></option>
                                        <?php }?>                                                                                                                             
                                    </select>
                                </div>
                            </div>
                        </div>    
                    </div>
                    <div class="modal-footer justify-content-center w3-dark-grey" style="height: 48px">
                        <button id="select_confirm" type="button" class="btn btn-primary" onclick="job_confirm()">Confirm</button>
                        <button id="select_close" type="button" class="btn btn-secondary" onclick="document.getElementById('JobSelect').style.display='none'" >Close</button>
                    </div>
                </form>
            </div>
            <div id="DivMode">
                <!-- Table Input -->
                <div id="TableInputSetting" class="table-container">
                    <div class="scrollbar-inputtable" id="style-inputtable">
                        <div class="force-overflow-inputtable">
                            <table id="input_table" class="table w3-table-all w3-hoverable">
                                <thead id="header-table">
                                    <tr class="w3-dark-grey" style="font-size: 2.6vmin">
                                        <th width="60%">Event</th>
                                        <th style="display: none;">2</th>
                                        <th style="display: none;">3</th>
                                        <th style="display: none;">4</th>
                                        <th style="display: none;">5</th>
                                        <th style="display: none;">6</th>
                                        <th style="display: none;">7</th>
                                        <th style="display: none;">8</th>
                                        <th style="display: none;">9</th>
                                        <th style="display: none;">10</th>
                                        <th width="20%">Pin</th>
                                        <th width="20%"></th>
                                    </tr>
                                </thead>

                                <tbody id="input_jobid_select"  style="font-size: 2.5vmin;text-align: center;">
                                   
                                   
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="footer" id='input_menu'>
                        <div class="buttonbox">
                            <input id="S1" name="New_Submit" type="button" value="<?php echo $text['New'];?>" tabindex="1" onclick="crud_job_event('new')">
                            <input id="S2" name="Edit_Submit" type="button" value="<?php echo $text['Edit'];?>" tabindex="1" onclick="crud_job_event('edit')">
                            <input id="S3" name="Copy_Submit" type="button" value="<?php echo $text['Copy'];?>" tabindex="1" onclick="crud_job_event('copy')">
                            <input id="S4" name="Delete_Submit" type="button" value="<?php echo $text['Delete'];?>" tabindex="1" onclick="crud_job_event('del')">
                            <input id="S5" name="Table_Submit" type="button" value="<?php echo $text['Table'];?>" tabindex="1" onclick="tablesubmit('show')">
                            <input id="S6" name="Align_Submit" type="button" value="<?php echo $text['Align'];?>" tabindex="1" onclick="crud_job_event('unified')" >
                        </div>
                    </div>
                </div>

                <!-- Table Data Information -->
                <div id="TableDataInput" style="display: none" class="table-container">
                    <div id="Event_List" style="margin-top: 10px;background-color: #F2F2D9;">
                        <div class="w3-border-bottom" style="font-size: 20px;">Event List</div>
                        <table class="table w3-table-all w3-hoverable" style="font-size: 2vmin">
                            <tr>
                                <td class="w3-left-align">1-50 SW Job ID</td>
                                <td class="w3-left-align">101 Disable</td>
                                <td class="w3-left-align">102 Enable</td>
                            </tr>
                            <tr>
                                <td class="w3-left-align">103 Clear</td>
                                <td class="w3-left-align">104 Confirm</td>                             
                                <td class="w3-left-align">105 Start-IN(Remote)</td>
                            </tr>
                            <tr>
                                <td class="w3-left-align">106 Unscrew(Remote)</td>
                                <td class="w3-left-align">107 Sequence Clear</td>
                                <td class="w3-left-align">108 Reboot</td>
                            </tr>
                            <tr>
                                <td class="w3-left-align">109 Gate Once</td>
                                <td class="w3-left-align">110 UsreDefine1</td>
                                <td class="w3-left-align">111 UsreDefine2</td>
                            </tr>
                            <tr>
                                <td class="w3-left-align">112 UsreDefine3</td>
                                <td class="w3-left-align">113 UsreDefine4</td>
                                <td class="w3-left-align">114 UsreDefine5</td>
                            </tr>
                        </table>                            
                    </div>
                    <div class="center">
                        <button id="button_Close" class="button" onclick="showTableInputSetting()">Close</button>
                    </div>
                </div>
            </div>

            <!-- Add New Input -->
            <div id="newinput" class="modal">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content w3-animate-zoom" style="width: 90%">
                        <header class="w3-container modal-header">
                            <span onclick="document.getElementById('newinput').style.display='none'"
                                class="w3-button w3-red w3-display-topright" style="width: 50px; margin: 3px;">&times;</span>
                            <h3 id='modal_title'>Create Event</h3>
                        </header>

                        <div class="modal-body">
                            <form id="new_input_form" style="padding-left: 5%">
                                <div class="row">
                                    <div for="event" class="col-3 t1">Event :</div>
                                    <div class="col-2 t2">
                                        <select id="Event_Option" name ="Event_Option" class="col custom-file">
                                                <?php foreach($data['event'] as $key =>$val){?>
                                                    <option value ='<?php echo $key;?>'><?php echo $val;?></option>
                                                <?php } ?>
                                        
                                        </select>
                                    </div>
                                </div>

                                <?php for($i = 2; $i <= 10; $i++){?>     
                                        <div class="row input-pin">
                                            <div class="col-2 t1" style="margin-left: 5%"><?php echo $i; ?>:</div>
                                            <div class="col t2">
                                                <div class="col-4 form-check form-check-inline">
                                                    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin<?php echo $i; ?>_high" value="1">
                                                    <label class="form-check-label" for="pin<?php echo $i; ?>_high"><img src="./img/high.png"></label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin<?php echo $i; ?>_low" value="2">
                                                    <label class="form-check-label" for="pin<?php echo $i; ?>_low"><img src="./img/low.png"></label>
                                                </div>
                                            </div>
                                        </div>
                                <?php } ?>
                                
                                <div class="row input-pin"  id='work_goc'style="display: none;">
                                    <div class="col-2 t1" class="col-3 t1">WRC:</div>
                                    <div class="col t2">
                    			      	<div class="col-4 form-check form-check-inline">
                                          <input class="form-check-input" type="radio" name="gateconfirm" id="gateconfirm_0" value="0" checked>
                                          <label class="form-check-label">NO</label>
                    					  
                    					</div>
                    					<div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gateconfirm" id="gateconfirm_1" value="1" >
                                            <label class="form-check-label">YES</label>
                    					    
                    					</div>
                                    </div>
                                </div>


                                

                            </form>
                        </div>

                        <div class="modal-footer justify-content-center">
                            <button id="" class="button-modal" onclick="create_input_id()">Save</button>
                            <button id="" class="button-modal" onclick="document.getElementById('newinput').style.display='none'" class="closebtn">Close</button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Edit Input -->
            <div id="edit_input" class="modal">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content w3-animate-zoom" style="width: 90%">
                        <header class="w3-container modal-header">
                            <span onclick="document.getElementById('edit_input').style.display='none'"
                                class="w3-button w3-red w3-display-topright" style="width: 50px; margin: 3px;">&times;</span>
                            <h3 id='modal_title'>Edit Event</h3>
                        </header>

                        <div class="modal-body">
                            <form id="new_input_form" style="padding-left: 5%">
                                <div class="row">
                                    <div for="event" class="col-3 t1">Event :</div>
                                    <div class="col-2 t2">
                                        <select id="edit_Event_Option" name ="edit_Event_Option" class="col custom-file">
                                                <?php foreach($data['event'] as $key =>$val){?>
                                                    <option value ='<?php echo $key;?>'><?php echo $val;?></option>
                                                <?php } ?>
                                        
                                        </select>
                                    </div>
                                </div>

                                <?php for ($i = 2; $i <= 10; $i++){?>
                                    <div class="row input-pin">
                                        <div class="col-2 t1" style="margin-left: 5%"><?php echo $i; ?>:</div>
                                        <div class="col t2">
                                            <div class="col-4 form-check form-check-inline">
                                                <input class="zoom form-check-input" type="radio" name="edit_pin_option" id="edit_pin<?php echo $i; ?>_high" value="1">
                                                <label class="form-check-label" for="edit_pin<?php echo $i; ?>_high"><img src="./img/high.png"></label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="zoom form-check-input" type="radio" name="edit_pin_option" id="edit_pin<?php echo $i; ?>_low" value="2">
                                                <label class="form-check-label" for="edit_pin<?php echo $i; ?>_low"><img src="./img/low.png"></label>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                
                                <div class="row input-pin"  id='work_goc'style="display: none;">
                                    <div class="col-2 t1" class="col-3 t1">WRC:</div>
                                    <div class="col t2">
                    			      	<div class="col-4 form-check form-check-inline">
                                          <input class="form-check-input" type="radio" name="edit_gateconfirm" id="edit_gateconfirm_0" value="0" checked>
                                          <label class="form-check-label">NO</label>
                    					  
                    					</div>
                    					<div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="edit_gateconfirm" id="edit_gateconfirm_1" value="1" >
                                            <label class="form-check-label">YES</label>
                    					    
                    					</div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer justify-content-center">
                            <button id="" class="button-modal" onclick="edit_input_id()">Save</button>
                            <button id="" class="button-modal" onclick="document.getElementById('edit_input').style.display='none'" class="closebtn">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Copy Input -->
            <div id="copyinput" class="modal">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content w3-animate-zoom" style="width: auto">
                        <header class="w3-container modal-header">
                            <span onclick="document.getElementById('copyinput').style.display='none'"
                                class="w3-button w3-red w3-display-topright" style="width: 50px; margin: 3px;">&times;</span>
                            <h3 id='modal_title'>Copy Input</h3>
                        </header>

                        <div class="modal-body">
                            <form id="new_seq_form">
                	            <label for="from_job_id" class="col col-form-label" style="font-weight: bold;padding-left: 5%;">Copy From</label>
                	            <div style="padding-left: 10%;">
                		            <div class="row">
                				        <label for="from_job_id" class="t1 col-4 col-form-label">Job ID :</label>
                				        <div class="col-5 t2 ">
                				            <input type="number" class="form-control" id="from_job_id" disabled>
                				        </div>

                				        <label for="from_job_name" class="t1 col-4 col-form-label">Job Name :</label>
                				        <div class="col-5 t2 ">
                				            <input type="text" class="form-control" id="from_job_name"  disabled>
                				        </div>
                				    </div>
                			    </div>

                			    <label for="from_job_id" class="col col-form-label" style="font-weight: bold;padding-left: 5%;">Copy To</label>
                			    <div style="padding-left: 10%">
                				    <div class="row">
                				        <label for="to_step_id" class="t1 col-4 col-form-label">Job :</label>
                				        <div class="t2 col-6">
                                            <select id="JobSelect1" class="col custom-file" style="margin: center; width: 153px">
                                                <?php foreach($data['job_list'] as $kk => $vv){?>
                                                    <option id ='job_list_option' value="<?php echo $vv['job_id']; ?>">
                                                        <?php echo $vv['job_id'] . " - " . $vv['job_name']; ?>
                                                    </option>
                                                <?php } ?>
                                             </select>
                				        </div>
                				    </div>
                			    </div>
                			  </form>
                        </div>

                        <div class="modal-footer justify-content-center">
                            <button id="" class="button-modal" onclick="copy_input_id()">Save</button>
                            <button id="" class="button-modal" onclick="document.getElementById('copyinput').style.display='none'" class="closebtn">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

var job_id; 
var input_event;
var temp;
var tempA;
var selectedValue;
var old_input_event;
var all_job;
var buttonDisabled = false;
var backgroundColorYellow = false;
var input_job;


$(document).ready(function () {
    highlight_row('input_table');

    var all_input_job = '<?php echo $data['device_data']['device_input_all_job']?>';
    job_id = all_input_job;
    input_job = all_input_job;
    if(job_id){
        get_input_by_job_id(job_id);
        document.getElementById('Button_Select').disabled = true;
        document.getElementById('job_id').style.backgroundColor = 'yellow';
    }

    
});

// Div Mode
function toggleDivs() {
    var tableInputSetting = document.getElementById('TableInputSetting');
    var tableDataInput = document.getElementById('TableDataInput');

    if (tableInputSetting.style.display === 'none') {
        tableInputSetting.style.display = 'block';
        tableDataInput.style.display = 'none';
    } else {
        tableInputSetting.style.display = 'none';
        tableDataInput.style.display = 'block';
    }
}

function showTableInputSetting() {
    document.getElementById('TableInputSetting').style.display = 'block';
    document.getElementById('TableDataInput').style.display = 'none';
    document.getElementById('input_menu').style.display = 'block';
}

// Get the modal
var modal = document.getElementById('newinput');
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}


document.getElementById("Event_Option").onchange = function() {
    var selectedValue = this.value; 
    handleEventChange(selectedValue); 
};



function crud_job_event(argument){
    if(argument == 'new' && job_id != '' && input_event != ''){
        //針對已設定的pin角位disable
        if (Array.isArray(temp)){ 
            temp.forEach(function(element) {
                var radio = document.getElementById(element);
                if (radio && radio.type === 'radio') { 
                    radio.disabled = true; 
                }
            });
        } 
        
        //針對已設定的事件option做反灰+disable
        if (Array.isArray(tempA)){
            tempA.forEach(function(element){
                var option = document.querySelector('#Event_Option option[value="' + element + '"]');
                if(option){
                    if (option.selected){
                        selectedValue = element;
                    }

                    option.disabled = true;
                    option.classList.add('disabled_input');
                }
            });
        }



        document.getElementById('newinput').style.display='block';
    } 
    
    if(argument == 'del'){
        delete_input_id(job_id,input_event);
    }



    if(argument == 'edit' && job_id != '' && input_event != ''){

        var selectElement = document.getElementById('edit_Event_Option');
        if(selectElement){
            selectElement.disabled = true;
            var options = selectElement.options;
            for (var i = 0; i < options.length; i++) {
                options[i].disabled = true;
                options[i].classList.add('disabled_input');
            }
        }
    
        if (Array.isArray(temp)){
            temp.forEach(function(element){
                var radio = document.getElementById(element);
                if (radio && radio.type === 'radio'){
                    radio.disabled = true;
                }
            });
        }
        get_input_info(job_id,input_event);
        handleEventChange(input_event); 
        document.getElementById('edit_input').style.display='block';
       
    }

    if(argument == 'copy' && job_id != ''){
        var jobinfo = <?php echo json_encode($data['job_list_new']); ?>;
        var from_job_name_bk = jobinfo[job_id]['job_name'];

        document.getElementById("from_job_id").value = job_id;
        document.getElementById("from_job_name").value = from_job_name_bk;
        var selectElement = document.getElementById('JobSelect1');
        var options = selectElement.getElementsByTagName('option');

        for (var i = 0; i < options.length; i++) {
            var optionId = options[i].getAttribute('id');
            var optionValue = options[i].value;
            if(optionValue == job_id){
                options[i].disabled = true; 
                options[i].classList.add('disabled_input'); 
             
            }
        }

        document.getElementById('copyinput').style.display='block';
    }

    if(argument == 'unified' && job_id != ''){
        enableButton();
        resetBackgroundColor();
        //console.log('input_job 有值:', input_job);
        //console.log('job_id 有值:', job_id);

        if(input_job != job_id){
            alignsubmit(job_id);  
        }else{
            resetalignsubmit(job_id);
        }
    }
}
</script>


</body>

</html>
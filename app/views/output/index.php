<div class="container-ms">
    <div class="w3-text-white w3-center">
        <table class="no-border">
            <tr id="header">
                <td width="100%">
                    <h3><?php echo $text['output'];?></h3>
                </td>
                <td>
                    <img src="./img/btn_home.png" style="margin-right: 10px" onclick="window.location.href = '?url=In';">
                </td>
            </tr>
        </table>
    </div>

    <div class="main-content">
        <div class="center-content">
            <div class="topnav">
                <label style="font-size:2.5vmin;color: #000; padding-left: 2%" for="job_id"><?php echo $text['job_id'];?> :</label>&nbsp;
                <input type="text" id="job_id" name="job_id" size="8" maxlength="20" value="" disabled style="height:30px; font-size:2.5vmin;text-align: center; background-color: #DDDDDD; border:0;">&nbsp;&nbsp;
                <button id="Button_Select" type="button" onclick="document.getElementById('JobSelect').style.display='block'"><?php echo $text['select'];?></button>
            </div>

            <!-- Job Select Modal -->
            <div id="JobSelect" class="modal">
                <form class="w3-modal-content w3-card-4 w3-animate-zoom" style="width: 400px; top: 12%; left: -20%" action="">
                    <div class="w3-light-grey">
                        <header class="w3-container w3-dark-grey" style="height: 48px">
                            <span onclick="document.getElementById('JobSelect').style.display='none'" class="w3-button w3-red w3-large w3-display-topright" style="margin: 2px">&times;</span>
                            <h3 style="margin: 5px"><?php echo $text['job_select'];?></h3>
                        </header>
                        <table id="Job_Select">
                            <tr>
                                <td>
                                    <select style="margin: center" id="JobNameSelect" name="JobNameSelect" size="200">
                                        <?php foreach($data['job_list'] as $key =>$val){?>
                                            <option value="<?php echo $val['job_id'];?>"><?php echo $val['job_name'];?></option>
                                        <?php }?>                                                                                                                                
                                     </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer justify-content-center w3-dark-grey" style="height: 48px">
                        <button id="select_confirm" type="button" class="btn btn-primary" onclick='job_confirm()'><?php echo $text['confirm'];?></button>
                        <button id="select_close" type="button" class="btn btn-secondary" onclick="document.getElementById('JobSelect').style.display='none'"><?php echo $text['close'];?></button>
                    </div>
                </form>
            </div>

            <!-- Table Input -->
            <!---./img/signal01.png持續 -->
            <!---./img/signal02.png單一週期 -->
            <!---./img/trigger.png起子trigger觸發-->
            <div id="TableOutputSetting">
                <div class="table-output">
                    <div class="scrollbar" id="style-outputtable">
                        <div class="scrollbar-force-overflow">
                            <table id="output_table" class="table w3-table-all w3-hoverable">
                                <thead class="header-table">
                                    <tr class="w3-dark-grey">
                                        <th><?php echo $text['event'];?></th>
                                        <?php $io = 1; for($io = 1; $io <= 11;$io++){?>
                                            <th><?php echo $io;?></th>
                                        <?php } ?>
                                        
                                        <th><?php echo $text['time'];?></th>
                                    </tr>
                                </thead>

                                <tbody id="output_jobid_select" style="font-size: 1.8vmin;text-align: center;"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="footer">
                    <div class="buttonbox">
                        <input id="S1" name="New_Submit" type="button" value="<?php echo $text['New'];?>" tabindex="1"       onclick="crud_job_event('new')">
                        <input id="S2" name="Edit_Submit" type="button" value="<?php echo $text['Edit'];?>" tabindex="1"     onclick="crud_job_event('edit')">
                        <input id="S3" name="Copy_Submit" type="button" value="<?php echo $text['Copy'];?>" tabindex="1"     onclick="crud_job_event('copy')">
                        <input id="S4" name="Delete_Submit" type="button" value="<?php echo $text['Delete'];?>" tabindex="1" onclick="crud_job_event('del')">
                        <input id="S6" name="Align_Submit" type="button" value="<?php echo $text['Align'];?>" tabindex="1" onclick="crud_job_event('unified')">
                    </div>
                </div>
            </div>

            <!-- Add New Output -->
            <div id="new_output" class="modal">
                <div class="modal-dialog modal-lg" style="top: 3%;">
                    <div class="modal-content w3-animate-zoom" style="width:65%">
                        <header class="w3-container modal-header">
                            <span onclick="document.getElementById('new_output').style.display='none'"
                                class="w3-button w3-red w3-display-topright" style="width: 50px; margin: 3px;">&times;</span>
                            <h3 id='modal_title'><?php echo $text['new_event'];?></h3>
                        </header>

                        <div class="modal-body">
                            <form id="new_output_form" style="padding-left: 5%">
                                <div class="row">
                                    <div for="event" class="col-3 t1"><?php echo $text['event'];?> :</div>
                                    <div class="col-2 t2">
                                        <select id="Event_Option" class="col custom-file">
                                           <?php foreach($data['event_output'] as $key =>$val){?>
                                                <option value ='<?php echo $key;?>'><?php echo $text[$val];?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

								<?php for ($i = 1; $i <= 11; $i++) {?>
									<div class="row output-pin">
										<div class="col-sm-2 t1"><?php echo $i; ?>:</div>
										<div class="col-sm-2 t2 form-check form-check-inline">
											<input class="form-check-input" type="radio" name="pin_option" id="pin<?php echo $i; ?>_1" value="1">
											<label class="form-check-label" for="pin<?php echo $i; ?>_signal01"><img src="./img/signal01.png"></label>
										</div>
										<div class="col-sm-2 t2 form-check form-check-inline">
											<input class="form-check-input" type="radio" name="pin_option" id="pin<?php echo $i; ?>_2" value="2">
											<label class="form-check-label" for="pin<?php echo $i; ?>_signal02"><img src="./img/signal02.png"></label>
										</div>
										<div class="col-sm-2 t2 form-check form-check-inline">
											<input class="form-check-input" type="radio" name="pin_option" id="pin<?php echo $i; ?>_3" value="3">
											<label class="form-check-label" for="pin<?php echo $i; ?>_trigger"><img src="./img/trigger.png"></label>
										</div>
										<div class="col-sm-2 t2">
											<input type="text" class="form-control" id="time<?php echo $i; ?>" placeholder="ms" style="height: 28px; text-align: center;">
										</div>
									</div>
								<?php } ?>

                            </form>
                        </div>

                        <div class="modal-footer justify-content-center">
                            <button id="" class="button-modal" onclick="create_output_id()"><?php echo $text['save'];?></button>
                            <button id="" class="button-modal" onclick="document.getElementById('new_output').style.display='none'" class="closebtn"><?php echo $text['close'];?></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Output -->
            <div id="edit_output" class="modal">
                <div class="modal-dialog modal-lg" style="top: 3%;">
                    <div class="modal-content w3-animate-zoom" style="width:65%">
                        <header class="w3-container modal-header">
                            <span onclick="document.getElementById('edit_output').style.display='none'"
                                class="w3-button w3-red w3-display-topright" style="width: 50px; margin: 3px;">&times;</span>
                            <h3 id='modal_title'><?php echo $text['edit_event'];?></h3>
                        </header>

                        <div class="modal-body">
                            <form id="new_output_form" style="padding-left: 5%">
                                <div class="row">
                                    <div for="event" class="col-3 t1"><?php echo $text['event'];?> :</div>
                                    <div class="col-2 t2">
                                        <select id="edit_event_option" name='edit_event_option' class="col custom-file">
                                           <?php foreach($data['event_output'] as $key =>$val){?>
                                                <option value ='<?php echo $key;?>'><?php echo $text[$val];?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

								<?php for ($i = 1; $i <= 11; $i++) {?>
										<div class="row output-pin">
											<div class="col-sm-2 t1"><?php echo $i; ?>:</div>
											<div class="col-sm-2 t2 form-check form-check-inline">
												<input class="form-check-input" type="radio" name="edit_pin_option" id="edit_pin<?php echo $i; ?>_1" value="1">
												<label class="form-check-label" for="pin<?php echo $i; ?>_signal01"><img src="./img/signal01.png"></label>
											</div>
											<div class="col-sm-2 t2 form-check form-check-inline">
												<input class="form-check-input" type="radio" name="edit_pin_option" id="edit_pin<?php echo $i; ?>_2" value="2">
												<label class="form-check-label" for="pin<?php echo $i; ?>_signal02"><img src="./img/signal02.png"></label>
											</div>
											<div class="col-sm-2 t2 form-check form-check-inline">
												<input class="form-check-input" type="radio" name="edit_pin_option" id="edit_pin<?php echo $i; ?>_3" value="3">
												<label class="form-check-label" for="pin<?php echo $i; ?>_trigger"><img src="./img/trigger.png"></label>
											</div>
											<div class="col-sm-2 t2">
												<input type="text" class="form-control" id="edit_time<?php echo $i; ?>" placeholder="ms" style="height: 28px; text-align: center;">
											</div>
										</div>
						        <?php } ?>


                            </form>
                        </div>

                        <div class="modal-footer justify-content-center">
                            <button id="" class="button-modal" onclick="edit_output_id()"><?php echo $text['save'];?></button>
                            <button id="" class="button-modal" onclick="document.getElementById('edit_output').style.display='none'" class="closebtn"><?php echo $text['close'];?></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Copy Output -->
            <div id="copy_output" class="modal">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content w3-animate-zoom" style="width: 60%;">
                        <header class="w3-container modal-header">
                            <span onclick="document.getElementById('copy_output').style.display='none'"
                                class="w3-button w3-red w3-display-topright" style="width: 50px; margin: 3px;">&times;</span>
                            <h3 id='modal_title'><?php echo $text['copy_input'];?></h3>
                        </header>

                        <div class="modal-body">
                            <form id="new_output_form">
                	            <label for="from_job_id" class="col col-form-label" style="font-weight: bold;padding-left: 5%;"><?php echo $text['copy_from'];?></label>
                	            <div style="padding-left: 10%;">
                		            <div class="row">
                				        <label for="from_job_id" class="t1 col-4 col-form-label"><?php echo $text['job_id'];?> :</label>
                				        <div class="col-5 t2 ">
                				            <input type="number" class="form-control" id="from_job_id" disabled>
                				        </div>

                				        <label for="from_job_name" class="t1 col-4 col-form-label"><?php echo $text['job_name'];?> :</label>
                				        <div class="col-5 t2 ">
                				            <input type="text" class="form-control" id="from_job_name" disabled>
                				        </div>
                				    </div>
                			    </div>

                			    <label for="from_job_id" class="col col-form-label" style="font-weight: bold;padding-left: 5%;"><?php echo $text['copy_to'];?></label>
                			    <div style="padding-left: 10%">
                				    <div class="row">
                				        <label for="to_step_id" class="t1 col-4 col-form-label"><?php echo $text['job'];?> :</label>
                				        <div class="t2 col-6">
                                            <select id="JobSelect1" class="col custom-file" style="margin: center; width: 160px">
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
                            <button id="" class="button-modal" onclick="copy_output_id()"><?php echo $text['save'];?></button>
                            <button id="" class="button-modal" onclick="document.getElementById('copy_output').style.display='none'" class="closebtn"><?php echo $text['close'];?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var job_id; 
var output_event;
var temp;
var tempA;
var buttonDisabled = false;
var backgroundColorYellow = false;
var output_job;
var all_job;
$(document).ready(function () {
    highlight_row('output_table');

    var all_output_job = '<?php echo $data['device_data']['device_output_all_job']?>';
    job_id = all_output_job ;
    output_job = all_output_job;
    if(job_id){
        get_output_by_job_id(job_id);
        document.getElementById('Button_Select').disabled = true;
        document.getElementById('job_id').style.backgroundColor = 'yellow';
    }
});


var modal = document.getElementById('newinput');

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}



function crud_job_event(argument){
    if(argument == 'del'){
        delete_output_id(job_id,output_event);
    }

    if(argument == 'new'){

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


        document.getElementById('new_output').style.display='block';
    }

    if(argument == 'edit'){

        var selectElement = document.getElementById('edit_event_option');
        if(selectElement){
            selectElement.disabled = true;
            var options = selectElement.options;
            for (var i = 0; i < options.length; i++) {
                options[i].disabled = true;
                options[i].classList.add('disabled_input');
            }
        }
      
        if (Array.isArray(temp)){ 
            temp.forEach(function(element) {
                var radio = document.getElementById(element);
                if (radio && radio.type === 'radio') { 
                    radio.disabled = true; 
                }
            });
        }
        
        get_output_info(job_id,output_event);
        document.getElementById('edit_output').style.display='block';
        
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
        document.getElementById('copy_output').style.display='block';
    }

    if(argument == 'unified' && job_id != ''){
        enableButton();
        resetBackgroundColor();
        if(output_job != job_id){
            alignsubmit(job_id);  
        }else{
            resetalignsubmit(job_id);
        }
        
    }
}

function collectPinValues(selector) {
    var pinOptions = document.querySelectorAll(selector);
    var selectedValues = [];

    pinOptions.forEach(function(option) {
        if (option.checked){ 
            var radioInfo = {
                id: option.id,
                value: option.value
            };
            selectedValues.push(radioInfo);
        }
    });

    return selectedValues;
}

</script>
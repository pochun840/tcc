
<link rel="stylesheet" href="<?php echo URLROOT; ?>css/tcc_output_m.css" type="text/css">

<div class="container-ms">
    <div class="w3-text-white w3-center">
        <table class="no-border">
            <tr id="header">
                <td width="100%"><h3><?php echo $text['output'];?></h3></td>
                <td><img src="./img/btn_home.png" style="margin-right: 10px" onclick="back()"></td>
            </tr>
        </table>
    </div>

    <div class="main-content">
        <div class="center-content">
            <div class="topnav">
                <label style="font-size:3vmin;color: #000; padding-left: 2%" for="job_id"><?php echo $text['job_id'];?> :</label>&nbsp;
                <input type="text" id="job_id" name="job_id" size="8" maxlength="20" value="1" disabled style="height:30px; font-size:3.5vmin;text-align: center; background-color: #DDDDDD; border:0;">&nbsp;&nbsp;
                <button id="Button_Select" type="button" onclick="document.getElementById('JobSelect').style.display='block'"><?php echo $text['select'];?></button>
            </div>

            <!-- Job Select Modal -->
            <div id="JobSelect" class="modal" style="width: 325px;">
                <form class="w3-modal-content w3-animate-zoom" style="top: 13%;" action="">
                    <div class="w3-light-grey">
                        <header class="w3-container w3-dark-grey" style="height: 48px">
                            <span onclick="document.getElementById('JobSelect').style.display='none'" class="w3-button w3-red w3-large w3-display-topright" style="margin: 2px">&times;</span>
                            <h3 style="margin: 5px"><?php echo $text['job_select'];?></h3>
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
                        <button id="select_confirm" type="button" class="btn btn-primary" onclick='job_confirm()'><?php echo $text['confirm'];?></button>
                        <button id="select_close" type="button" class="btn btn-secondary" onclick="document.getElementById('JobSelect').style.display='none'" ><?php echo $text['close'];?></button>
                    </div>
                </form>
            </div>

            <!-- Table Input -->
            <div id="TableOutputSetting">
                <div class="table-container">
                    <div class="scrollbar" id="style-outputtable">
                        <div class="force-overflow">
                            <table id="output_table" class="table w3-table-all w3-hoverable">
                                <thead id="header-table">
                                    <tr class="w3-dark-grey" style="font-size: 2.6vmin">
                                        <th class="w3-center"><?php echo $text['event'];?></th>
                                        <th class="w3-center">Pin</th>
                                        <th class="w3-center"></th>
                                        <th class="w3-center"><?php echo $text['time'];?></th>
                                    </tr>
                               </thead>

                                <tbody style="font-size: 2.5vmin;text-align: center;" id="output_jobid_select" >
                                </tbody>
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
                <div class="modal-dialog modal-lg" style="top: 6%;">
                    <div class="modal-content w3-animate-zoom" style="width: auto">
                        <header class="w3-container modal-header">
                            <span onclick="document.getElementById('new_output').style.display='none'"
                                class="w3-button w3-red w3-display-topright" style="width: 50px; margin: 3px;">&times;</span>
                            <h3 id='modal_title'><?php echo $text['new_event'];?></h3>
                        </header>

                        <div class="modal-body" id="new_output">
                            <form id="new_output_from" style="padding-left: 2%; padding-right: 1%">
                                <div class="row">
                                    <div for="event" class="col-3 t1"><?php echo $text['event'];?> :</div>
                                    <div class="col-2 t2">
                                        <select id="Event_Option" class="col custom-file">
                                        <option value="-1" disabled selected><?php echo $text['Choose_option']; ?></option>
                                           	<?php foreach($data['event_output'] as $key =>$val){?>
                                                <option value ='<?php echo $key;?>'><?php echo $text[$val];?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row output-pin">
                                    <div class="col t1">1:</div>
                    			    <div class="col t2 form-check form-check-inline">
                    				    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin1_1" value="1" onclick="toggleOnputTime('pin1_1', this.checked,'1')"  >
                    				    <label class="form-check-label" for="pin1_signal01"><img src="./img/signal01.png"></label>
                    				</div>
                    				<div class="col t2 form-check form-check-inline">
                    				    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin1_2" value="2" onclick="toggleOnputTime('pin1_2', this.checked,'2')" >
                    				    <label class="form-check-label" for="pin1_signal02"><img src="./img/signal02.png"></label>
                    				</div>
                    				<div class="col t2 form-check form-check-inline">
                    				    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin1_3" value="3" onclick="toggleOnputTime('pin1_3', this.checked,'3')" >
                    				    <label class="form-check-label" for="pin1_trigger"><img src="./img/trigger.png"></label>
                    				</div>
                  				    <div class="col-3 t2">
                				        <input type="text" class="t4 form-control" id="time1" placeholder="ms" value="" >
                   				    </div>
                                </div>
                                <div class="row output-pin">
                                    <div class="col t1">2:</div>
                  			      	<div class="col t2 form-check form-check-inline">
                   					    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin2_1" value="1"  onclick="toggleOnputTime('pin2_1', this.checked,'1')" >
                   					    <label class="form-check-label" for="pin2_signal01"><img src="./img/signal01.png"></label>
                   					</div>
                   					<div class="col t2 form-check form-check-inline">
                   					    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin2_2" value="2"  onclick="toggleOnputTime('pin2_2', this.checked,'2')">
                   					    <label class="form-check-label" for="pin2_signal02"><img src="./img/signal02.png"></label>
                   					</div>
                   					<div class="col t2 form-check form-check-inline">
                   					    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin2_3" value="3" onclick="toggleOnputTime('pin2_3', this.checked,'3')">
                   					    <label class="form-check-label" for="pin2_trigger"><img src="./img/trigger.png"></label>
                   					</div>
              				        <div class="col-3 t2">
            				            <input type="text" class="t4 form-control" id="time2" placeholder="ms" value="" >
               				        </div>
                                </div>
                                <div class="row output-pin">
                                    <div class="col t1">3:</div>
                   			      	<div class="col t2 form-check form-check-inline">
                   					    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin3_1" value="1"  onclick="toggleOnputTime('pin3_1', this.checked,'1')" >
                   					    <label class="form-check-label" for="pin3_signal01"><img src="./img/signal01.png"></label>
                   					</div>
                   					<div class="col t2 form-check form-check-inline">
                   					    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin3_2" value="2"  onclick="toggleOnputTime('pin3_2', this.checked,'2')">
                   					    <label class="form-check-label" for="pin3_signal02"><img src="./img/signal02.png"></label>
                   					</div>
                   					<div class="col t2 form-check form-check-inline">
                   					    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin3_3" value="3" onclick="toggleOnputTime('pin3_3', this.checked,'3')" >
                   					    <label class="form-check-label" for="pin3_trigger"><img src="./img/trigger.png"></label>
                   					</div>
              				        <div class="col-3 t2">
              				            <input type="text" class="t4 form-control" id="time3" placeholder="ms" value="" >
               				        </div>
                                </div>
                                <div class="row output-pin">
                                    <div class="col t1">4:</div>
                   			      	<div class="col t2 form-check form-check-inline">
                   					    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin4_1" value="1" onclick="toggleOnputTime('pin4_1', this.checked,'1')" >
                   					    <label class="form-check-label" for="pin4_signal01"><img src="./img/signal01.png"></label>
                   					</div>
                   					<div class="col t2 form-check form-check-inline">
                   					    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin4_2" value="2" onclick="toggleOnputTime('pin4_2', this.checked,'2')">
                   					    <label class="form-check-label" for="pin4_signal02"><img src="./img/signal02.png"></label>
                   					</div>
                   					<div class="col t2 form-check form-check-inline">
                   					    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin4_3" value="3" onclick="toggleOnputTime('pin4_3', this.checked,'3')">
                   					    <label class="form-check-label" for="pin4_trigger"><img src="./img/trigger.png"></label>
                   					</div>
              				        <div class="col-3 t2">
              				            <input type="text" class="t4 form-control" id="time4" value="" placeholder="ms" >
               				        </div>
                                </div>
                                <div class="row output-pin">
                                    <div class="col t1">5:</div>
                   			      	<div class="col t2 form-check form-check-inline">
                   					    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin5_1" value="1"  onclick="toggleOnputTime('pin5_1', this.checked,'1')">
                   					    <label class="form-check-label" for="pin5_signal01"><img src="./img/signal01.png"></label>
                   					</div>
                   					<div class="col t2 form-check form-check-inline">
                   					    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin5_2" value="2"  onclick="toggleOnputTime('pin5_2', this.checked,'2')">
                   					    <label class="form-check-label" for="pin5_signal02"><img src="./img/signal02.png"></label>
                   					</div>
                   					<div class="col t2 form-check form-check-inline">
                   					    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin5_3" value="3" onclick="toggleOnputTime('pin5_3', this.checked,'3')">
                   					    <label class="form-check-label" for="pin5_trigger"><img src="./img/trigger.png"></label>
                   					</div>
              				        <div class="col-3 t2">
              				            <input type="text" class="t4 form-control" id="time5" value="" placeholder="ms" >
               				        </div>
                                </div>
                                <div class="row output-pin">
                                    <div class="col t1">6:</div>
                   			      	<div class="col t2 form-check form-check-inline">
                   					    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin6_1" value="1" onclick="toggleOnputTime('pin6_1', this.checked,'1')">
                   					    <label class="form-check-label" for="pin6_signal01"><img src="./img/signal01.png"></label>
                   					</div>
                   					<div class="col t2 form-check form-check-inline">
                   					    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin6_2" value="2" onclick="toggleOnputTime('pin6_2', this.checked,'2')">
                   					    <label class="form-check-label" for="pin6_signal02"><img src="./img/signal02.png"></label>
                   					</div>
                   					<div class="col t2 form-check form-check-inline">
                   					    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin6_3" value="3" onclick="toggleOnputTime('pin6_3', this.checked,'3')">
                   					    <label class="form-check-label" for="pin6_trigger"><img src="./img/trigger.png"></label>
                   					</div>
              				        <div class="col-3 t2">
              				            <input type="text" class="t4 form-control" id="time6" placeholder="ms" >
               				        </div>
                                </div>
                                <div class="row output-pin">
                                    <div class="col t1">7:</div>
                   			      	<div class="col t2 form-check form-check-inline">
                   					    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin7_1" value="1"  onclick="toggleOnputTime('pin7_1', this.checked,'1')">
                   					    <label class="form-check-label" for="pin7_signal01"><img src="./img/signal01.png"></label>
                   					</div>
                   					<div class="col t2 form-check form-check-inline">
                   					    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin7_2" value="2"  onclick="toggleOnputTime('pin7_2', this.checked,'2')">
                   					    <label class="form-check-label" for="pin7_signal02"><img src="./img/signal02.png"></label>
                   					</div>
                   					<div class="col t2 form-check form-check-inline">
                   					    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin7_3" value="3" onclick="toggleOnputTime('pin7_3', this.checked,'3')">
                   					    <label class="form-check-label" for="pin7_trigger"><img src="./img/trigger.png"></label>
                   					</div>
              				        <div class="col-3 t2">
              				            <input type="text" class="t4 form-control" id="time7" placeholder="ms" >
               				        </div>
                                </div>
                                <div class="row output-pin">
                                    <div class="col t1">8:</div>
                   			      	<div class="col t2 form-check form-check-inline">
                   					    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin8_1" value="1" onclick="toggleOnputTime('pin8_1', this.checked,'1')">
                   					    <label class="form-check-label" for="pin8_signal01"><img src="./img/signal01.png"></label>
                   					</div>
                   					<div class="col t2 form-check form-check-inline">
                   					    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin8_2" value="2" onclick="toggleOnputTime('pin8_2', this.checked,'2')">
                   					    <label class="form-check-label" for="pin8_signal02"><img src="./img/signal02.png"></label>
                   					</div>
                   					<div class="col t2 form-check form-check-inline">
                   					    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin8_3" value="3" onclick="toggleOnputTime('pin8_3', this.checked,'3')">
                   					    <label class="form-check-label" for="pin8_trigger"><img src="./img/trigger.png"></label>
                   					</div>
              				        <div class="col-3 t2">
              				            <input type="text" class="t4 form-control" id="time8" placeholder="ms" value="" >
               				        </div>
                                </div>
                                <div class="row output-pin">
                                    <div class="col t1">9:</div>
                   			      	<div class="col t2 form-check form-check-inline">
                   					    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin9_1" value="1" onclick="toggleOnputTime('pin9_1', this.checked,'1')">
                   					    <label class="form-check-label" for="pin9_signal01"><img src="./img/signal01.png"></label>
                   					</div>
                   					<div class="col t2 form-check form-check-inline">
                   					    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin9_2" value="2" onclick="toggleOnputTime('pin9_2', this.checked,'2')">
                   					    <label class="form-check-label" for="pin9_signal02"><img src="./img/signal02.png"></label>
                   					</div>
                   					<div class="col t2 form-check form-check-inline">
                   					    <input class="zoom form-check-input" type="radio" name="pin_option" id="pin9_3" value="3" onclick="toggleOnputTime('pin9_3', this.checked,'3')">
                   					    <label class="form-check-label" for="pin9_trigger"><img src="./img/trigger.png"></label>
                   					</div>
              				        <div class="col-3 t2">
              				            <input type="text" class="t4 form-control" id="time9" placeholder="ms" >
               				        </div>
                                </div>
                               
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
                <div class="modal-dialog modal-lg" style="top: 6%;">
                    <div class="modal-content w3-animate-zoom" style="width: auto">
                        <header class="w3-container modal-header">
                            <span onclick="document.getElementById('edit_output').style.display='none'"
                                class="w3-button w3-red w3-display-topright" style="width: 50px; margin: 3px;">&times;</span>
                            <h3 id='modal_title'><?php echo $text['edit_event'];?></h3>
                        </header>

                        <div class="modal-body" id="new_output">
                            <form id="new_output_from" style="padding-left: 2%; padding-right: 1%">
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
								<?php for ($i = 1; $i <= 9; $i++) {?>

									<div class="row output-pin">
										<div class="col t1"><?php echo $i;?>:</div>
										<div class="col t2 form-check form-check-inline">
											<input class="zoom form-check-input" type="radio" name="edit_pin_option"  id="edit_pin<?php echo $i; ?>_1" value="1" onclick="toggleOnputTime_edit('edit_pin<?php echo $i; ?>_1', this.checked,'1')"  >
											<label class="form-check-label" for="pin1_signal01"><img src="./img/signal01.png"></label>
										</div>
										<div class="col t2 form-check form-check-inline">
											<input class="zoom form-check-input" type="radio" name="edit_pin_option" id="edit_pin<?php echo $i; ?>_2" value="2" onclick="toggleOnputTime_edit('edit_pin<?php echo $i; ?>_1', this.checked,'2')" >
											<label class="form-check-label" for="pin1_signal02"><img src="./img/signal02.png"></label>
										</div>
										<div class="col t2 form-check form-check-inline">
											<input class="zoom form-check-input" type="radio" name="edit_pin_option" id="edit_pin<?php echo $i; ?>_3" value="3" onclick="toggleOnputTime_edit('edit_pin<?php echo $i; ?>_1', this.checked,'3')" >
											<label class="form-check-label" for="pin1_trigger"><img src="./img/trigger.png"></label>
										</div>
										<div class="col-3 t2">
											<input type="text" class="t4 form-control" id="edit_time<?php echo $i; ?>" placeholder="ms" value="" >
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
                    <div class="modal-content w3-animate-zoom" style="width: auto">
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
											<option value="-1" disabled selected><?php echo $text['Choose_option']; ?></option>
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
var eventOption = document.getElementById('Event_Option'); 
var job_id; 
var output_event;
var temp;
var tempA;
var buttonDisabled = false;
var backgroundColorYellow = false;
var output_job;
var all_job;
var del_output_val;
var output_pinval;
var dataoutput_pin_val;
$(document).ready(function () {
    highlight_row_input('output_table');
    var all_output_job = '<?php echo $data['device_data']['device_output_all_job']?>';
    job_id = all_output_job ;
    output_job = all_output_job;
    if(job_id){
        get_output_by_job_id(job_id);
        document.getElementById('Button_Select').disabled = true;
        document.getElementById('job_id').style.backgroundColor = 'yellow';
    }

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


var modal = document.getElementById('newinput');
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

function crud_job_event(argument){

    var table = document.getElementById('output_table');
    var selectedRow = table.querySelector('tr.selected');
    if (selectedRow) {
        var dataEventValue = selectedRow.getAttribute('data-event');
        del_output_val = dataEventValue;
        output_event = del_output_val;

        var dataOutputPinElement = selectedRow.querySelector('[data-outputpin]');
        var dataOutputPinValue = dataOutputPinElement ? dataOutputPinElement.getAttribute('data-outputpin') : null;
        output_pinval = dataOutputPinValue;


    }

    if(argument == 'del' && job_id != '' &&  del_output_val){

        var selectedRows = document.querySelectorAll('#output_jobid_select tr.selected');
        if (!selectedRows.length > 0) {
            //getLanguageMessage('language'); 
            return;
        }
        
        delete_output_id(job_id,del_output_val);
    }

    if(argument == 'new' && job_id != ''){

            //處理下拉式選單
            var eventOption = document.getElementById('Event_Option');
            if (Array.isArray(tempA)) {
                tempA.forEach(function (optionValue) {
                    const option = eventOption.querySelector(`option[value="${optionValue}"]`);
                    if (option) {
                        option.disabled = true;
                    }
                });
            }

            //處理pin
            if (Array.isArray(temp)){ 
                temp.forEach(function(element) {
                    var radio = document.getElementById(element);
                    if (radio && radio.type === 'radio') { 
                        radio.disabled = true; 
                    }
                });
            } 

            var filtered_array = [];
            temp.forEach(function(element) {
                // 檢查是否是以 'pin' 開頭並且不包含 'edit_pin'
                if (element.includes('pin') && !element.includes('edit_pin')) {
                    filtered_array.push(element);
                }
            });


            disableElements(filtered_array);


            document.getElementById('new_output').style.display='block';
            var eventOption = document.getElementById('Event_Option');
            eventOption.addEventListener('change', function() {
                var selectedOptionId = parseInt(eventOption.options[eventOption.selectedIndex].value); // 轉換為整數
                const disableOptions = [7, 8, 9, 12, 13, 14, 15, 16]; // 需要停用的選項值陣列

                toggleElementsInRange(1, 10, 3); // 先重置所有元素的狀態

                if (!disableOptions.includes(selectedOptionId)) { // 如果選擇的選項不在停用列表中
                    disableElements(filtered_array); // 則執行停用特定元素的函式
                }
            });


    }

    if (argument === 'edit' && job_id != '' && output_event != '') {

        var selectedRows = document.querySelectorAll('#output_jobid_select tr.selected');
        if (!selectedRows.length > 0) {
            getLanguageMessage('language'); 
            return;
        }


        var selectElement = document.getElementById('edit_event_option');
        if (selectElement) {
            selectElement.disabled = true;
            Array.from(selectElement.options).forEach(option => {
                option.disabled = true;
                option.classList.add('disabled_input');
            });
        }
        if (Array.isArray(temp)) { 
            temp.forEach(id => {
                var radio = document.getElementById(id);
                if (radio && radio.type === 'radio') { 
                    radio.disabled = true; 
                }
            });

            let tempC = temp.slice(); 
            const filtered_C = tempC.filter(item => item.includes("edit_pin"));
            filtered_C.forEach(function(id) {
                var match = id.match(/(edit_pin\d+)_(\d+)/);
                if (match) {
                    var basePinId = match[1]; 
                    var pinNumber = match[2]; 

                    for (var i = 1; i <= 3; i++) {
                        var pinElementId = basePinId + "_" + i;
                        var pinElement = document.getElementById(pinElementId);
                        if (pinElement && pinElement.type === 'radio') {
                            pinElement.disabled = true;
                        }
                    }

                    // 禁用 edit_time 相關的元素
                    var timeElementId = 'edit_time' + basePinId.slice(3);
                    const toremove = "t_pin"; 
                    timeElementId = timeElementId.replace(toremove,'');
                    console.log(timeElementId);
                    
                    var timeElement = document.getElementById(timeElementId);
                    if (timeElement) {
                        timeElement.disabled = true;
                    }
                }
            });


            
        }


        //該事件的pin的 所有radio 及 input 全部都要可以填
        if(output_pinval != ''){

            const idsToDisable = [
                `edit_pin${output_pinval}_1`,
                `edit_pin${output_pinval}_2`,
                `edit_pin${output_pinval}_3`,
                `edit_time${output_pinval}`
            ];

            idsToDisable.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.disabled = false;
                }
            });

            var pin1Checkbox = document.getElementById(`edit_pin${output_event}_1`);
            var pin3Checkbox = document.getElementById(`edit_pin${output_event}_3`);
            var timeInput = document.getElementById(`edit_time${output_event}`);

        }

        

        get_output_info(job_id, output_event);
        document.getElementById('edit_output').style.display = 'block';
    }
    if(argument == 'copy' && job_id != '' && output_event != ''){

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

        var selectedRows = document.querySelectorAll('#output_jobid_select tr.selected');
        if (!selectedRows.length > 0) {
            getLanguageMessage('language'); 
            return;
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

function toggleElementsInRange(start, end, suffix) {
    let selectedOptionId = eventOption.options[eventOption.selectedIndex].value;
    const disableOptions = [7, 8, 9, 12, 13, 14, 15, 16]; 
    let disableAll = disableOptions.includes(parseInt(selectedOptionId)); 

    for (let i = start; i <= end; i++) {
        for (let j = 1; j <= suffix; j++) {
            let id = 'pin' + i + '_' + j;
            let element = document.getElementById(id);
            if (element) {
                element.disabled = disableAll && (j === 1 || j === 2);
            }
        }

        let timeId = 'time' + i;
        let timeElement = document.getElementById(timeId);
        if (timeElement) {
            timeElement.disabled = disableAll;
        }
    }
}


var old_output_event; 
var output_event;
function job_confirm(){
    var jobid = document.getElementById("JobNameSelect").value;
    localStorage.setItem("jobid", jobid);
    job_id = jobid;
    all_job = jobid;

    if(jobid){
        $.ajax({
            url: "?url=Outputs/get_output_by_job_id",
            method: "POST",
            data:{ 
                job_id: job_id,
            },
            success: function(response) {
                var data = JSON.parse(response);
                var job_outputlist = data.job_outputlist;
                temp = data.temp;
                tempA = data.tempA;


                document.getElementById("output_jobid_select").innerHTML = job_outputlist;
                document.getElementById("JobSelect").style.display = 'none';
                document.getElementById("job_id").value = job_id;
            
                var rows = document.querySelectorAll('#output_jobid_select tr');
                rows.forEach(function(row) {
                    row.addEventListener('click', function() { 

                        row.getAttribute('data-event');
                        output_event = row.getAttribute('data-event');
                   
 
                    });
                });

                var language = getCookie('language');
                if(language == "zh-cn"){
                    document.getElementById('1') && (document.getElementById('1').textContent = 'OK');
                    document.getElementById('2') && (document.getElementById('2').textContent = 'NG');
                    document.getElementById('3') && (document.getElementById('3').textContent = '超出上限');
                    document.getElementById('4') && (document.getElementById('4').textContent = '低于下限');
                    document.getElementById('5') && (document.getElementById('5').textContent = '工序完成信号');
                    document.getElementById('6') && (document.getElementById('6').textContent = '工作任务完成信号');
                    document.getElementById('7') && (document.getElementById('7').textContent = '马达信号');
                    document.getElementById('8') && (document.getElementById('8').textContent = '启动信号');
                    document.getElementById('9') && (document.getElementById('9').textContent = '拆螺丝');
                    document.getElementById('10') && (document.getElementById('10').textContent = 'BS');
                    document.getElementById('11') && (document.getElementById('11').textContent = '条码');
                    document.getElementById('12') && (document.getElementById('12').textContent = '自定义1');
                    document.getElementById('13') && (document.getElementById('13').textContent = '自定义2');
                    document.getElementById('14') && (document.getElementById('14').textContent = '自定义3');
                    document.getElementById('15') && (document.getElementById('15').textContent = '自定义4');
                    document.getElementById('16') && (document.getElementById('16').textContent = '自定义5');

                } 
                else if(language == "zh-tw"){
                    document.getElementById('1') && (document.getElementById('1').textContent = 'OK');
                    document.getElementById('2') && (document.getElementById('2').textContent = 'NG');
                    document.getElementById('3') && (document.getElementById('3').textContent = '超出上限');
                    document.getElementById('4') && (document.getElementById('4').textContent = '低於下限');
                    document.getElementById('5') && (document.getElementById('5').textContent = '工序完成信號');
                    document.getElementById('6') && (document.getElementById('6').textContent = '完工信號');
                    document.getElementById('7') && (document.getElementById('7').textContent = '馬達信號');
                    document.getElementById('8') && (document.getElementById('8').textContent = '啟動信號');
                    document.getElementById('9') && (document.getElementById('9').textContent = '拆螺絲');
                    document.getElementById('10') && (document.getElementById('10').textContent = 'BS');
                    document.getElementById('11') && (document.getElementById('11').textContent = '條碼');
                    document.getElementById('12') && (document.getElementById('12').textContent = '自定義1');
                    document.getElementById('13') && (document.getElementById('13').textContent = '自定義2');
                    document.getElementById('14') && (document.getElementById('14').textContent = '自定義3');
                    document.getElementById('15') && (document.getElementById('15').textContent = '自定義4');
                    document.getElementById('16') && (document.getElementById('16').textContent = '自定義5');
                }

            },
            error: function(xhr, status, error) {
            
            }
        });
    }
}

//delete
function delete_output_id(job_id,del_output_val){
    if(job_id){
        $.ajax({
            url: "?url=Outputs/delete_output",
            method: "POST",
            data: { 
                job_id: job_id,
                output_event: del_output_val,
             
            },
            success: function(response) {
       
                var responseData = JSON.parse(response);

                console.log(responseData);
                alertify.alert(responseData.res_type, responseData.res_msg, function() {
                    get_output_by_job_id(job_id);

                    updateEventSelectAndPins(responseData.old_output_pin);

                });
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed:", status, error);
            }
        });     
    }   
}

function get_output_by_job_id(job_id){
    $.ajax({
        url: "?url=Outputs/get_output_by_job_id",
        method: "POST",
        data: { 
            job_id: job_id,
        },
        success: function(response) {
            var data = JSON.parse(response);
            var job_outputlist = data.job_outputlist;
            temp = data.temp;
            tempA = data.tempA;

            document.getElementById("output_jobid_select").innerHTML = job_outputlist;
            document.getElementById("JobSelect").style.display = 'none';
            document.getElementById("job_id").value = job_id;
        
            var rows = document.querySelectorAll('#output_jobid_select tr');
            rows.forEach(function(row) {
                row.addEventListener('click', function() { 
                    output_event = this.className; 
                });
            });

            
            var language = getCookie('language');
            if(language == "zh-cn"){
                document.getElementById('1') && (document.getElementById('1').textContent = 'OK');
                document.getElementById('2') && (document.getElementById('2').textContent = 'NG');
                document.getElementById('3') && (document.getElementById('3').textContent = '超出上限');
                document.getElementById('4') && (document.getElementById('4').textContent = '低于下限');
                document.getElementById('5') && (document.getElementById('5').textContent = '工序完成信号');
                document.getElementById('6') && (document.getElementById('6').textContent = '工作任务完成信号');
                document.getElementById('7') && (document.getElementById('7').textContent = '马达信号');
                document.getElementById('8') && (document.getElementById('8').textContent = '启动信号');
                document.getElementById('9') && (document.getElementById('9').textContent = '拆螺丝');
                document.getElementById('10') && (document.getElementById('10').textContent = 'BS');
                document.getElementById('11') && (document.getElementById('11').textContent = '条码');
                document.getElementById('12') && (document.getElementById('12').textContent = '自定义1');
                document.getElementById('13') && (document.getElementById('13').textContent = '自定义2');
                document.getElementById('14') && (document.getElementById('14').textContent = '自定义3');
                document.getElementById('15') && (document.getElementById('15').textContent = '自定义4');
                document.getElementById('16') && (document.getElementById('16').textContent = '自定义5');

            } 
            else if(language == "zh-tw"){
                document.getElementById('1') && (document.getElementById('1').textContent = 'OK');
                document.getElementById('2') && (document.getElementById('2').textContent = 'NG');
                document.getElementById('3') && (document.getElementById('3').textContent = '超出上限');
                document.getElementById('4') && (document.getElementById('4').textContent = '低於下限');
                document.getElementById('5') && (document.getElementById('5').textContent = '工序完成信號');
                document.getElementById('6') && (document.getElementById('6').textContent = '完工信號');
                document.getElementById('7') && (document.getElementById('7').textContent = '馬達信號');
                document.getElementById('8') && (document.getElementById('8').textContent = '啟動信號');
                document.getElementById('9') && (document.getElementById('9').textContent = '拆螺絲');
                document.getElementById('10') && (document.getElementById('10').textContent = 'BS');
                document.getElementById('11') && (document.getElementById('11').textContent = '條碼');
                document.getElementById('12') && (document.getElementById('12').textContent = '自定義1');
                document.getElementById('13') && (document.getElementById('13').textContent = '自定義2');
                document.getElementById('14') && (document.getElementById('14').textContent = '自定義3');
                document.getElementById('15') && (document.getElementById('15').textContent = '自定義4');
                document.getElementById('16') && (document.getElementById('16').textContent = '自定義5');
            }
            
        },
        error: function(xhr, status, error) {
            console.error("AJAX request failed:", status, error);
        }
    }); 

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


function create_output_id() {
    var output_event = document.getElementById("Event_Option").value;
    var pinval = collectPinValues('input[name="pin_option"]');
  

    if (pinval.length > 0) {
        var pin_old = pinval[0]['id']; 
        var wave = pinval[0]['value'];
        
        var match = pin_old.match(/\d+/); 
        var output_pin = match ? parseInt(match[0]) : null;
        
        var time_ms = 'time'+ output_pin;
        var wave_on =  document.getElementById(time_ms).value;

        if (job_id) {
            $.ajax({
                url: "?url=Outputs/create_output_event",
                method: "POST",
                data: { 
                    job_id: job_id,
                    output_pin: output_pin,
                    output_event: output_event,
                    wave: wave,
                    wave_on: wave_on
                },
                success: function(response) {
                    var responseData = JSON.parse(response);
                    alertify.alert(responseData.res_type, responseData.res_msg, function() {
                        get_output_by_job_id(job_id);
                    });
                    document.getElementById('new_output').style.display = 'none';

                },
                error: function(xhr, status, error) {
                    console.error("AJAX request failed:", status, error);
                }
            });
        }
    } else {
        console.error("No pinval found or pinval[0] is undefined.");
    }
}

function edit_output_id(){
    var output_event = document.getElementById("edit_event_option").value;
    var pinval       = collectPinValues('input[name="edit_pin_option"]');
    var pin_old      = pinval[0]['id'];
    var wave         = pinval[0]['value'];
    var match        = pin_old.match(/\d+/); 
    var output_pin   = match ? parseInt(match[0]) : null;

    var time_ms = 'edit_time'+ output_pin;
    var wave_on =  document.getElementById(time_ms).value;
    if(job_id){
        $.ajax({
            url: "?url=Outputs/edit_output_event",
            method: "POST",
            data: { 
                job_id: job_id,
                output_pin: output_pin,
                output_event: output_event,
                wave: wave,
                wave_on: wave_on,
                old_output_event: old_output_event
            },
            success: function(response) {
                //console.log(response);
                var responseData = JSON.parse(response);
                alertify.alert(responseData.res_type, responseData.res_msg, function() {
                    get_output_by_job_id(job_id);
                    updateEventSelectAndPins(responseData.old_output_pin);
                });

                document.getElementById('edit_output').style.display='none';
                
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed:", status, error);
            }
        });         
    }
}
function resetalignsubmit(job_id) {

    var job_id_new = 0;
    if(job_id_new == 0){
        $.ajax({
            url: "?url=Outputs/output_alljob",
            method: "POST",
            data: {
                job_id_new: job_id_new
            },
            success: function (response) {
                get_output_by_job_id(job_id);
            },
            error: function (xhr, status, error) {

            }
        });

    }

}
function alignsubmit(job_id) {
    if (job_id) {
        $.ajax({
            url: "?url=Outputs/output_alljob",
            method: "POST",
            data: {
                job_id: job_id
            },
            success: function (response) {
                get_output_by_job_id(job_id);
            
                buttonDisabled = !buttonDisabled;
                document.getElementById('Button_Select').disabled = buttonDisabled;
     
                backgroundColorYellow = !backgroundColorYellow;
                if (backgroundColorYellow) {
                    document.getElementById('job_id').style.backgroundColor = 'yellow';
                } else {
                    document.getElementById('job_id').style.backgroundColor = '';
                }
            },
            error: function (xhr, status, error) {

            }
        });
    }
}
//copy
function copy_output_id(){

    var language = getCookie('language');
    if(language == "zh-cn"){
        var text_info ='若设定已存在，将会取代原有设定';
    }else if(language == "zh-tw"){
        var text_info ='若設定已存在，將會取代原有設定';
    }else{
        var text_info ='If the job input already exists, it will replace the original setting';
    }
    alertify.confirm( text_info, function (e) {
        if (e) {
            var to_job_id = document.getElementById("JobSelect1").value;
            if(to_job_id){
                $.ajax({
                    url: "?url=Outputs/copy_output",
                    method: "POST",
                    data: { 
                        from_job_id: job_id,
                        to_job_id: to_job_id
                    },
                    success: function(response) {

                        var responseData = JSON.parse(response);
                        alertify.alert(responseData.res_type, responseData.res_msg, function() {
                            get_output_by_job_id(job_id);
                        });

                        document.getElementById('copy_output').style.display='none';

                    },
                    error: function(xhr, status, error) {
                        
                    }
                });
        
            } 
        } else {
            // cancel
        }
    });
    document.getElementById('copy_output').style.display='none';
}

function get_output_info(job_id,output_event){

    if(job_id){
     $.ajax({
             url: "?url=Outputs/check_job_event",
             method: "POST",
             data: { 
                 job_id: job_id,
                 output_event: output_event
             },
             success: function(response) {
              
                var responseJSON = JSON.stringify(response);
                var cleanString = responseJSON.replace(/Array|\\n/g, '');
                var cleanString = cleanString.substring(2, cleanString.length - 2);
                var [, job_id] = cleanString.match(/\[output_job_id]\s*=>\s*([^ ]+)/) || [, null];
                var [, output_event] = cleanString.match(/\[output_event]\s*=>\s*([^ ]+)/) || [, null];
                var [, output_pin] = cleanString.match(/\[output_pin]\s*=>\s*([^ ]+)/) || [, null];
                var [, wave] = cleanString.match(/\[wave]\s*=>\s*([^ ]+)/) || [, null];
                var [, wave_on] = cleanString.match(/\[wave_on]\s*=>\s*([^ ]+)/) || [, null];

                var edit_output_pin = "edit_pin" + output_pin + "_"+ wave;
                var radioButton = document.getElementById(edit_output_pin);
                radioButton.removeAttribute('disabled');

                var time_ms = 'edit_time'+ output_pin;

                if(wave != 2){
                    var time_id = 'edit_time' + output_pin;
                    var element = document.getElementById(time_id);
                    
                    if(element){
                        element.disabled = true
                    }
                }
           
                //完工信號 && 馬達信號 && 啟動信號
                if (output_event == 8  || output_event == 6 || output_event == 7 ) {
                    for(let i = 1; i <= 11; i++) {
                        let element1 = document.getElementById(`edit_pin${i}_1`);
                        if (element1) {
                            element1.disabled = true;
                        }
                
                        let element2 = document.getElementById(`edit_pin${i}_2`);
                        if (element2) {
                            element2.disabled = true;
                        }
                    }

                    if (Array.isArray(temp)) {
                        //過濾出包含 "edit_pin" 的字串
                        const filteredArray = temp.filter(item => item.includes("edit_pin"));
                        
                        const updatedArray = filteredArray.map(item => {
                            // 如果字串為空，直接返回
                            if (item.length === 0) {
                                return item;
                            }
                            //強制字串的最後一個字元更換為 '3'
                            return item.slice(0, -1) + '3';
                        });
                        
                        console.log("Updated Array:", updatedArray);
                        updatedArray.forEach(item => {
                            const radio = document.getElementById(item);
                            if (radio && radio.type === 'radio') {
                                radio.disabled = true;
                            }
                        });

                    }
                    
                }

                if (wave_on !== "0") {
                    document.getElementById(time_ms).value = wave_on;
                }
 
                 old_output_even = output_event;
 
                 if(radioButton){
                     radioButton.checked = true;
                 }
                 
                 document.querySelector("select[name='edit_event_option']").value = output_event;
                 document.getElementById("edit_event_option").onchange = function() {
                     var selectedValue = this.value; 
                 };
             },
             error: function(xhr, status, error) {
                 console.error("AJAX request failed:", status, error);
             }
     });      
    }
  
}

function toggleOnputTime(inputId, checked, option) {
    var inputElement = document.getElementById(inputId);
    
    if (!inputElement) {
        console.error(`Element with ID '${inputId}' not found.`);
        return; 
    }

   
    if (inputElement.type === 'checkbox' || inputElement.type === 'radio') {

        if (inputElement.checked !== checked) {
            console.warn(`The checked state of the element with ID '${inputId}' does not match the provided 'checked' value.`);
        }
    }

    
    if (option != '2') {
        var newId = inputId.replace(/^pin(\d+)_\d+$/, 'time$1');
        var element = document.getElementById(newId);
        if (element) {
            element.disabled = true;
        }
        //alert('eew');
    } else { 
        var newId = inputId.replace(/^pin(\d+)_\d+$/, 'time$1');
        var element = document.getElementById(newId);
        if (element) {
            element.disabled = false;
        }
    }
}

function toggleOnputTime_edit(inputId, checked, option) {
    var inputElement = document.getElementById(inputId);
    
    if (!inputElement) {
        console.error(`Element with ID '${inputId}' not found.`);
        return; // Exit if element is not found
    }

    if (inputElement.type === 'checkbox' || inputElement.type === 'radio') {

        if (inputElement.checked !== checked) {
            console.warn(`The checked state of the element with ID '${inputId}' does not match the provided 'checked' value.`);
        }
    }

    if (option != '2') {
        var newId = inputId.replace(/^edit_pin(\d+)_\d+$/, 'edit_time$1');
        var element = document.getElementById(newId);
        if (element) {
            element.disabled = true;
        }
        
    } else { 
        var newId = inputId.replace(/^edit_pin(\d+)_\d+$/, 'edit_time$1');
        var element = document.getElementById(newId);
        if (element) {
            element.disabled = false;
        }
    }
}

function disableElements(filtered_array) {
    // 生成新的 id 数组，去除末尾的数字并添加 "_1", "_2", "_3" 和 "time1" 到 "time11"
    let new_array = filtered_array
        .map(item => item.replace(/_\d$/, ''))  // 去除原始字符串末尾的数字
        .flatMap(item => {
            let result = [
                item + "_1",
                item + "_2",
                item + "_3"
            ];

            // 新增 "time" + 1 到 11
            for (let i = 1; i <= 11; i++) {
                result.push("time" + i);
            }

            return result;
        });

    // 遍历新生成的 id 数组，如果元素存在就禁用它
    new_array.forEach(id => {
        let element = document.getElementById(id); 
        if (element) {
            element.disabled = true;  // 禁用该元素
        }
    });
}


// 更新 Event_Option 的選擇，並解除相應 pin 的 disabled
function updateEventSelectAndPins(old_input_pin) {
    // 更新 select 選單的值，如果當前值不是 -1 則設為 -1
    var eventSelect = document.getElementById('Event_Option');
    if (eventSelect && eventSelect.value !== '-1') {
        eventSelect.value = '-1';
    }

    // 根據 old_input_pin 解除 pin 的 disabled
    if (old_input_pin) {
        // 解析數字部分
        var pinNumber = old_input_pin.match(/\d+/)[0];

        // 組合出需要操作的元素 ID
        var pinHighId = 'pin' + pinNumber + '_high';
        var pinLowId = 'pin' + pinNumber + '_low';

        // 解除 disabled 屬性 及 checked 屬性
        var pinHighElement = document.getElementById(pinHighId);
        var pinLowElement = document.getElementById(pinLowId);

        var pinNumbers = ['1', '2', '3'];  
        pinNumbers.forEach(function(suffix) {
            var pinid = 'pin' + pinNumber + '_' + suffix;
            console.log(pinid);

            document.getElementById(pinid).checked = false;
        });


        if (pinHighElement) {
            pinHighElement.disabled = false;
            //pinHighElement.checked  = false;
        }
        if (pinLowElement) {
            pinLowElement.disabled = false;
            //pinLowElement.checked  = false;
        }
    }
}


</script>
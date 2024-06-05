
<?php require APPROOT . 'views/inc/header.php'; ?>
<link rel="stylesheet" type="text/css" href="../public/css/tcc_output.css">
<body>
<div class="container-ms">
    <div class="w3-text-white w3-center">
        <table class="no-border">
            <tr id="header">
                <td width="100%">
                    <h3>I/O Output</h3>
                </td>
                <td>
                    <img src="./img/btn_home.png" style="margin-right: 10px">
                </td>
            </tr>
        </table>
    </div>

    <div class="main-content">
        <div class="center-content">
            <div class="topnav">
                <label style="font-size:2.5vmin;color: #000; padding-left: 2%" for="job_id">Job ID :</label>&nbsp;
                <input type="text" id="job_id" name="job_id" size="8" maxlength="20" value="1" disabled style="height:30px; font-size:2.5vmin;text-align: center; background-color: #DDDDDD; border:0;">&nbsp;&nbsp;
                <button id="Button_Select" type="button" onclick="document.getElementById('JobSelect').style.display='block'">Select</button>
            </div>

            <!-- Job Select Modal -->
            <div id="JobSelect" class="modal">
                <form class="w3-modal-content w3-card-4 w3-animate-zoom" style="width: 400px; top: 12%; left: -20%" action="">
                    <div class="w3-light-grey">
                        <header class="w3-container w3-dark-grey" style="height: 48px">
                            <span onclick="document.getElementById('JobSelect').style.display='none'" class="w3-button w3-red w3-large w3-display-topright" style="margin: 2px">&times;</span>
                            <h3 style="margin: 5px">Job Select</h3>
                        </header>
                        <table id="Job_Select">
                            <tr>
                                <td>
                                    <select style="margin: center" id="JobNameSelect" name="JobNameSelect" size="200">
                                        <option value="1">Sample Job 1</option>
                                        <option value="2">Sample Job 2</option>
                                        <option value="3">Sample Job 3</option>
                                        <option value="4">Sample Job 4</option>
                                        <option value="5">Sample Job 5</option>                                                                                                                             
                                     </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer justify-content-center w3-dark-grey" style="height: 48px">
                        <button id="select_confirm" type="button" class="btn btn-primary">Confirm</button>
                        <button id="select_close" type="button" class="btn btn-secondary" onclick="document.getElementById('JobSelect').style.display='none'">Close</button>
                    </div>
                </form>
            </div>

            <!-- Table Input -->
            <div id="TableOutputSetting">
                <div class="table-output">
                    <div class="scrollbar" id="style-outputtable">
                        <div class="scrollbar-force-overflow">
                            <table id="output_table" class="table w3-table-all w3-hoverable">
                                <thead class="header-table">
                                    <tr class="w3-dark-grey">
                                        <th>Event</th>
                                        <th>1</th>
                                        <th>2</th>
                                        <th>3</th>
                                        <th>4</th>
                                        <th>5</th>
                                        <th>6</th>
                                        <th>7</th>
                                        <th>8</th>
                                        <th>9</th>
                                        <th>10</th>
                                        <th>11</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>

                                <tbody style="font-size: 1.8vmin;text-align: center;">
                                    <tr>
                                        <td>NG</td>
                                        <td></td>
                                        <td></td>
                                        <td><img src="./img/signal01.png" style="max-width: 50px;"></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>NG-Higt</td>
                                        <td><img src="./img/signal02.png" style="max-width: 50px;"></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>500</td>
                                    </tr>
                                    <tr>
                                        <td>OK-Job</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><img src="./img/signal02.png" style="max-width: 50px;"></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>1000</td>
                                    </tr>
                                    <tr>
                                        <td>Tool Runing</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><img src="./img/trigger.png" style="max-width: 50px;"></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="footer">
                    <div class="buttonbox">
                        <input id="S1" name="New_Submit" type="button" value="New" tabindex="1" onclick="document.getElementById('new_output').style.display='block'">
                        <input id="S2" name="Edit_Submit" type="button" value="Edit" tabindex="1">
                        <input id="S3" name="Copy_Submit" type="button" value="Copy" tabindex="1" onclick="document.getElementById('copy_output').style.display='block'">
                        <input id="S4" name="Delete_Submit" type="button" value="Delete" tabindex="1">
                        <input id="S6" name="Align_Submit" type="button" value="Align" tabindex="1">
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
                            <h3 id='modal_title'>Create Event</h3>
                        </header>

                        <div class="modal-body">
                            <form id="new_output_form" style="padding-left: 5%">
                                <div class="row">
                                    <div for="event" class="col-3 t1">Event :</div>
                                    <div class="col-2 t2">
                                        <select id="Event_Option" class="col custom-file">
                                            <option value="-1" disabled selected>Option</option>
                                            <option value="1">OK</option>
                                            <option value="2">NG</option>
                                            <option value="3">NG-High</option>
                                            <option value="4">NG-Low</option>
                                            <option value="5">OK-Sequence</option>
                                            <option value="6">OK-Job</option>
                                            <option value="7">Tool Runing</option>
                                            <option value="8">Tool Trigger</option>
                                            <option value="9">Reverse</option>
                                            <option value="10">BS</option>
                                            <option value="11">Barcode</option>
                                            <option value="12">UserDefine1</option>
                                            <option value="13">UserDefine2</option>
                                            <option value="14">UserDefine3</option>
                                            <option value="15">UserDefine4</option>
                                            <option value="16">UserDefine5</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row output-pin">
                                    <div class="col-sm-2 t1">1:</div>
                    			    <div class="col-sm-2 t2 form-check form-check-inline">
                    				    <input class="form-check-input" type="radio" name="pin_option" id="pin1_signal01" value="0">
                    				    <label class="form-check-label" for="pin1_signal01"><img src="./img/signal01.png"></label>
                    				</div>
                    				<div class="col-sm-2 t2 form-check form-check-inline">
                    				    <input class="form-check-input" type="radio" name="pin_option" id="pin1_signal02" value="1" checked="checked">
                    				    <label class="form-check-label" for="pin1_signal02"><img src="./img/signal02.png"></label>
                    				</div>
                    				<div class="col-sm-2 t2 form-check form-check-inline">
                    				    <input class="form-check-input" type="radio" name="pin_option" id="pin1_trigger" value="0">
                    				    <label class="form-check-label" for="pin1_trigger"><img src="./img/trigger.png"></label>
                    				</div>
                  				    <div class="col-sm-2 t2">
                				        <input type="text" class="form-control" id="time2" placeholder="ms" value="500" disabled style="height: 28px;text-align: center;">
                   				    </div>
                                </div>
                                <div class="row output-pin">
                                    <div class="col-sm-2 t1">2:</div>
                  			      	<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin2_signal01" value="0">
                   					    <label class="form-check-label" for="pin2_signal01"><img src="./img/signal01.png"></label>
                   					</div>
                   					<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin2_signal02" value="1">
                   					    <label class="form-check-label" for="pin2_signal02"><img src="./img/signal02.png"></label>
                   					</div>
                   					<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin2_trigger" value="0">
                   					    <label class="form-check-label" for="pin2_trigger"><img src="./img/trigger.png"></label>
                   					</div>
              				        <div class="col-sm-2 t2">
            				            <input type="text" class="form-control" id="Time1" placeholder="ms" disabled style="height: 28px; text-align: center;">
               				        </div>
                                </div>
                                <div class="row output-pin">
                                    <div class="col-sm-2 t1">3:</div>
                   			      	<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin3_signal01" value="0">
                   					    <label class="form-check-label" for="pin3_signal01"><img src="./img/signal01.png"></label>
                   					</div>
                   					<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin3_signal02" value="1">
                   					    <label class="form-check-label" for="pin3_signal02"><img src="./img/signal02.png"></label>
                   					</div>
                   					<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin3_trigger" value="0">
                   					    <label class="form-check-label" for="pin3_trigger"><img src="./img/trigger.png"></label>
                   					</div>
              				        <div class="col-sm-2 t2">
              				            <input type="text" class="form-control" id="time3" placeholder="ms" value="" disabled style="height: 28px;text-align: center;">
               				        </div>
                                </div>
                                <div class="row output-pin">
                                    <div class="col-sm-2 t1">4:</div>
                   			      	<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin4_signal01" value="0">
                   					    <label class="form-check-label" for="pin4_signal01"><img src="./img/signal01.png"></label>
                   					</div>
                   					<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin4_signal02" value="1">
                   					    <label class="form-check-label" for="pin4_signal02"><img src="./img/signal02.png"></label>
                   					</div>
                   					<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin4_trigger" value="0">
                   					    <label class="form-check-label" for="pin4_trigger"><img src="./img/trigger.png"></label>
                   					</div>
              				        <div class="col-sm-2 t2">
              				            <input type="text" class="form-control" id="time4" placeholder="ms" disabled style="height: 28px;text-align: center;">
               				        </div>
                                </div>
                                <div class="row output-pin">
                                    <div class="col-sm-2 t1">5:</div>
                   			      	<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin5_signal01" value="0">
                   					    <label class="form-check-label" for="pin5_signal01"><img src="./img/signal01.png"></label>
                   					</div>
                   					<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin5_signal02" value="1">
                   					    <label class="form-check-label" for="pin5_signal02"><img src="./img/signal02.png"></label>
                   					</div>
                   					<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin5_trigger" value="0">
                   					    <label class="form-check-label" for="pin5_trigger"><img src="./img/trigger.png"></label>
                   					</div>
              				        <div class="col-sm-2 t2">
              				            <input type="text" class="form-control" id="time5" placeholder="ms" disabled style="height: 28px;text-align: center;">
               				        </div>
                                </div>
                                <div class="row output-pin">
                                    <div class="col-sm-2 t1">6:</div>
                   			      	<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin6_signal01" value="0">
                   					    <label class="form-check-label" for="pin6_signal01"><img src="./img/signal01.png"></label>
                   					</div>
                   					<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin6_signal02" value="1">
                   					    <label class="form-check-label" for="pin6_signal02"><img src="./img/signal02.png"></label>
                   					</div>
                   					<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin6_trigger" value="0">
                   					    <label class="form-check-label" for="pin6_trigger"><img src="./img/trigger.png"></label>
                   					</div>
              				        <div class="col-sm-2 t2">
              				            <input type="text" class="form-control" id="time6" placeholder="ms" disabled style="height: 28px;text-align: center;">
               				        </div>
                                </div>
                                <div class="row output-pin">
                                    <div class="col-sm-2 t1">7:</div>
                   			      	<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin7_signal01" value="0">
                   					    <label class="form-check-label" for="pin7_signal01"><img src="./img/signal01.png"></label>
                   					</div>
                   					<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin7_signal02" value="1">
                   					    <label class="form-check-label" for="pin7_signal02"><img src="./img/signal02.png"></label>
                   					</div>
                   					<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin7_trigger" value="0">
                   					    <label class="form-check-label" for="pin7_trigger"><img src="./img/trigger.png"></label>
                   					</div>
              				        <div class="col-sm-2 t2">
              				            <input type="text" class="form-control" id="time7" placeholder="ms" disabled style="height: 28px;text-align: center;">
               				        </div>
                                </div>
                                <div class="row output-pin">
                                    <div class="col-sm-2 t1">8:</div>
                   			      	<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin8_signal01" value="0">
                   					    <label class="form-check-label" for="pin8_signal01"><img src="./img/signal01.png"></label>
                   					</div>
                   					<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin8_signal02" value="1">
                   					    <label class="form-check-label" for="pin8_signal02"><img src="./img/signal02.png"></label>
                   					</div>
                   					<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin8_trigger" value="0">
                   					    <label class="form-check-label" for="pin8_trigger"><img src="./img/trigger.png"></label>
                   					</div>
              				        <div class="col-sm-2 t2">
              				            <input type="text" class="form-control" id="time8" placeholder="ms" value="" disabled style="height: 28px;text-align: center">
               				        </div>
                                </div>
                                <div class="row output-pin">
                                    <div class="col-sm-2 t1">9:</div>
                   			      	<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin9_signal01" value="1">
                   					    <label class="form-check-label" for="pin9_signal01"><img src="./img/signal01.png"></label>
                   					</div>
                   					<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin9_signal02" value="1">
                   					    <label class="form-check-label" for="pin9_signal02"><img src="./img/signal02.png"></label>
                   					</div>
                   					<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin9_trigger" value="1">
                   					    <label class="form-check-label" for="pin9_trigger"><img src="./img/trigger.png"></label>
                   					</div>
              				        <div class="col-sm-2 t2">
              				            <input type="text" class="form-control" id="time9" placeholder="ms" disabled style="height: 28px;text-align: center">
               				        </div>
                                </div>
                                <div class="row output-pin">
                                    <div class="col-sm-2 t1">10:</div>
                   			      	<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin10_signal01" value="1">
                   					    <label class="form-check-label" for="pin10_signal01"><img src="./img/signal01.png"></label>
                   					</div>
                   					<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin10_signal02" value="1">
                   					    <label class="form-check-label" for="pin10_signal02"><img src="./img/signal02.png"></label>
                   					</div>
                   					<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin10_trigger" value="1">
                   					    <label class="form-check-label" for="pin10_trigger"><img src="./img/trigger.png"></label>
                   					</div>
              				        <div class="col-sm-2 t2">
              				            <input type="text" class="form-control" id="time10" placeholder="ms" disabled style="height: 28px;text-align: center">
               				        </div>
                                </div>
                                <div class="row output-pin">
                                    <div class="col-sm-2 t1">11:</div>
                   			      	<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin11_signal01" value="1">
                   					    <label class="form-check-label" for="pin11_signal01"><img src="./img/signal01.png"></label>
                   					</div>
                   					<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin11_signal02" value="1">
                   					    <label class="form-check-label" for="pin11_signal02"><img src="./img/signal02.png"></label>
                   					</div>
                   					<div class="col-sm-2 t2 form-check form-check-inline">
                   					    <input class="form-check-input" type="radio" name="pin_option" id="pin11_trigger" value="1">
                   					    <label class="form-check-label" for="pin11_trigger"><img src="./img/trigger.png"></label>
                   					</div>
              				        <div class="col-sm-2 t2">
              				            <input type="text" class="form-control" id="time11" placeholder="ms" disabled style="height: 28px;text-align: center">
               				        </div>
                                </div>


                            </form>
                        </div>

                        <div class="modal-footer justify-content-center">
                            <button id="" class="button-modal" onclick="add_member_user()">Save</button>
                            <button id="" class="button-modal" onclick="document.getElementById('new_output').style.display='none'" class="closebtn">Close</button>
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
                            <h3 id='modal_title'>Copy Input</h3>
                        </header>

                        <div class="modal-body">
                            <form id="new_output_form">
                	            <label for="from_job_id" class="col col-form-label" style="font-weight: bold;padding-left: 5%;">Copy From</label>
                	            <div style="padding-left: 10%;">
                		            <div class="row">
                				        <label for="from_job_id" class="t1 col-4 col-form-label">Job ID :</label>
                				        <div class="col-5 t2 ">
                				            <input type="number" class="form-control" id="from_job_id" disabled>
                				        </div>

                				        <label for="from_job_name" class="t1 col-4 col-form-label">Job Name :</label>
                				        <div class="col-5 t2 ">
                				            <input type="number" class="form-control" id="from_job_name" disabled>
                				        </div>
                				    </div>
                			    </div>

                			    <label for="from_job_id" class="col col-form-label" style="font-weight: bold;padding-left: 5%;">Copy To</label>
                			    <div style="padding-left: 10%">
                				    <div class="row">
                				        <label for="to_step_id" class="t1 col-4 col-form-label">Job :</label>
                				        <div class="t2 col-6">
                                            <select id="JobSelect" class="col custom-file" style="margin: center; width: 160px">
                                                <option value="1">1 - Job1</option>
                                                <option value="2">2 - Job2</option>
                                                <option value="3">3 - Job </option>
                                                <option value="4">4 - Job </option>
                                                <option value="5">5 - Job5</option>
                                             </select>
                				        </div>
                				    </div>
                			    </div>
                			  </form>
                        </div>

                        <div class="modal-footer justify-content-center">
                            <button id="" class="button-modal" onclick="add_member_user()">Save</button>
                            <button id="" class="button-modal" onclick="document.getElementById('copy_output').style.display='none'" class="closebtn">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Change the color of a row in a table
$(document).ready(function () {
    highlight_row('output_table');
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
var modal = document.getElementById('newinput');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

</script>


</body>

</html>
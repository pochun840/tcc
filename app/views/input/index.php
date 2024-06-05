<?php require APPROOT . 'views/inc/header.php'; ?>
<link rel="stylesheet" type="text/css" href="../public/css/tcc_input.css">
<body>
<div class="container-ms">
    <div class="w3-text-white w3-center">
        <table class="no-border">
            <tr id="header">
                <td width="100%">
                    <h3>I/O Input</h3>
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
                <form class="w3-modal-content w3-card-4 w3-animate-zoom" style="width: 400px; top: 5%; left: -20%" action="">
                    <div class="w3-light-grey">
                        <header class="w3-container w3-dark-grey" style="height: 48px">
                            <span onclick="document.getElementById('JobSelect').style.display='none'" class="w3-button w3-red w3-large w3-display-topright" style="margin: 2px">&times;</span>
                            <h3 style="margin: 5px" onclick="get_job_list()">Job Select</h3>
                        </header>
                        <table id="Job_Select">
                            <tr>
                                <td>
                                    <select style="margin: center" id="JobNameSelect" name="JobNameSelect" size="200">
                                        <!--<option value="1">Sample Job 1</option>
                                        <option value="2">Sample Job 2</option>
                                        <option value="3">Sample Job 3</option>
                                        <option value="4">Sample Job 4</option>
                                        <option value="5">Sample Job 5</option>-->                                                                                                                             
                                     </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer justify-content-center w3-dark-grey" style="height: 48px">
                        <button id="select_confirm" type="button" class="btn btn-primary">Confirm</button>
                        <button id="select_close" type="button" class="btn btn-secondary" onclick="document.getElementById('JobSelect').style.display='none'" >Close</button>
                    </div>
                </form>
            </div>
            <div id="DivMode">
                <!-- Table Input -->
                <div id="TableInputSetting">
                    <div class="table-input">
                        <div class="scrollbar" id="style-inputtable">
                            <div class="scrollbar-force-overflow">
                                <table id="input_table" class="table w3-table-all w3-hoverable">
                                    <thead class="header-table">
                                        <tr class="w3-dark-grey">
                                            <th>Event</th>
                                            <th>2</th>
                                            <th>3</th>
                                            <th>4</th>
                                            <th>5</th>
                                            <th>6</th>
                                            <th>7</th>
                                            <th>8</th>
                                            <th>9</th>
                                            <th>10</th>
                                            <th>Confirm</th>
                                            <th>Page</th>
                                            <th>Mode</th>
                                        </tr>
                                    </thead>

                                    <tbody style="font-size: 1.8vmin;text-align: center;">
                                        <tr>
                                            <td>Disable</td>
                                            <td><img src="./img/high.png" style="max-width: 50px;"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>No</td>
                                            <td>1</td>
                                            <td>Event</td>
                                        </tr>
                                        <tr>
                                            <td>Clear</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><img src="./img/low.png" style="max-width: 50px;"></td>
                                            <td>No</td>
                                            <td>1</td>
                                            <td>Event</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="footer">
                        <div class="buttonbox">
                            <input id="S1" name="New_Submit" type="button" value="New" tabindex="1" onclick="document.getElementById('newinput').style.display='block'">
                            <input id="S2" name="Edit_Submit" type="button" value="Edit" tabindex="1">
                            <input id="S3" name="Copy_Submit" type="button" value="Copy" tabindex="1" onclick="document.getElementById('copyinput').style.display='block'">
                            <input id="S4" name="Delete_Submit" type="button" value="Delete" tabindex="1">
                            <input id="S5" name="Table_Submit" type="button" value="Table" tabindex="1" onclick="toggleDivs()">
                            <input id="S6" name="Align_Submit" type="button" value="Align" tabindex="1">
                        </div>
                    </div>
                </div>

                <!-- Table Data Information -->
                <div id="TableDataInput" style="display: none">
                    <div style="height: calc(100vh - 370px); padding-bottom: 5px">
                        <div class="scrollbar" id="style-tableinput">
                            <div class="force-overflow">
                                <table class="table w3-table-all w3-hoverable" >
                                    <thead class="header-table">
                                        <tr class="w3-dark-grey">
                                            <th>No</th>
                                            <th>Event ID</th>
                                            <th>2</th>
                                            <th>3</th>
                                            <th>4</th>
                                            <th>5</th>
                                            <th>6</th>
                                            <th>7</th>
                                            <th>8</th>
                                            <th>9</th>
                                            <th>10</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>101</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><img src="./img/low.png" style="max-width: 50px;"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>102</td>
                                            <td></td>
                                            <td><img src="./img/high.png" style="max-width: 50px;"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div id="Event_List" align="center" style="margin-top: 10px;background-color: #F2F2D9">
                        <div class="w3-border-bottom" style="font-size: 20px;">Event List</div>
                        <table class="w3-table-all">
                            <tr>
                                <td class="w3-left-align">1-50 SW Job ID</td>
                                <td class="w3-left-align">101 Disable</td>
                                <td class="w3-left-align">102 Enable</td>
                                <td class="w3-left-align">103 Clear</td>
                                <td class="w3-left-align">104 Confirm </td>
                            </tr>
                            <tr>
                                <td class="w3-left-align">105 Start-IN(Remote)</td>
                                <td class="w3-left-align">106 Unscrew(Remote)</td>
                                <td class="w3-left-align">107 Sequence Clear</td>
                                <td class="w3-left-align">108 Reboot</td>
                                <td class="w3-left-align">109 Gate Once</td>
                            </tr>
                            <tr>
                                <td class="w3-left-align">110 UsreDefine1</td>
                                <td class="w3-left-align">111 UsreDefine2</td>
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
                    <div class="modal-content w3-animate-zoom" style="width: 70%">
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
                                        <select id="Event_Option" class="col custom-file">
                                            <option value="-1" disabled selected>Option</option>
                                            <option value="101">Disable</option>
                                            <option value="102">Enable</option>
                                            <option value="103">Clear</option>
                                            <option value="104">Confirm</option>
                                            <option value="105">Start-IN(Remote)</option>
                                            <option value="106">Unscrew(Remote)</option>
                                            <option value="107">Sequence Clear</option>
                                            <option value="108">Reboot</option>
                                            <option value="109">Gate Once</option>
                                            <option value="110">UserDefine1</option>
                                            <option value="111">UserDefine2</option>
                                            <option value="112">UserDefine3</option>
                                            <option value="113">UserDefine4</option>
                                            <option value="114">UserDefine5</option>
                                            <option value="1">SW Job1</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row input-pin">
                                    <div class="col-1 t1">2:</div>
                                    <div class="col t2" >
                    			      	<div class="col-4 form-check form-check-inline">
                    					    <input class="form-check-input" type="radio" name="pin_option" id="pin2_high" value="1">
                    					    <label class="form-check-label" for="pin2_high"><img src="./img/high.png"></label>
                    					</div>
                    					<div class="form-check form-check-inline">
                    					    <input class="form-check-input" type="radio" name="pin_option" id="pin2_low" value="1">
                    					    <label class="form-check-label" for="pin2_low"><img src="./img/low.png"></label>
                    					</div>
                                    </div>

                                    <div class="col-1 t1">7:</div>
                                    <div class="col t2" >
                    			      	<div class="col-4 form-check form-check-inline">
                    					    <input class="form-check-input" type="radio" name="pin_option" id="pin7_high" value="1">
                    					    <label class="form-check-label" for="pin7_high"><img src="./img/high.png"></label>
                    					</div>
                    					<div class="form-check form-check-inline">
                    					    <input class="form-check-input" type="radio" name="pin_option" id="pin7_low" value="1">
                    					    <label class="form-check-label" for="pin7_low"><img src="./img/low.png"></label>
                    					</div>
                                    </div>
                                </div>

                                <div class="row input-pin">
                                    <div class="col-1 t1">3:</div>
                                    <div class="col t2" >
                    			      	<div class="col-4 form-check form-check-inline">
                    					    <input class="form-check-input" type="radio" name="pin_option" id="pin3_high" value="1">
                    					    <label class="form-check-label" for="pin3_high"><img src="./img/high.png"></label>
                    					</div>
                    					<div class="form-check form-check-inline">
                    					    <input class="form-check-input" type="radio" name="pin_option" id="pin3_low" value="1">
                    					    <label class="form-check-label" for="pin3_low"><img src="./img/low.png"></label>
                    					</div>
                                    </div>

                                    <div class="col-1 t1">8:</div>
                                    <div class="col t2" >
                    			      	<div class="col-4 form-check form-check-inline">
                    					    <input class="form-check-input" type="radio" name="pin_option" id="pin8_high" value="1">
                    					    <label class="form-check-label" for="pin8_high"><img src="./img/high.png"></label>
                    					</div>
                    					<div class="form-check form-check-inline">
                    					    <input class="form-check-input" type="radio" name="pin_option" id="pin8_low" value="1">
                    					    <label class="form-check-label" for="pin8_low"><img src="./img/low.png"></label>
                    					</div>
                                    </div>
                                </div>

                                <div class="row input-pin">
                                    <div class="col-1 t1">4:</div>
                                    <div class="col t2" >
                    			      	<div class="col-4 form-check form-check-inline">
                    					    <input class="form-check-input" type="radio" name="pin_option" id="pin4_high" value="1">
                    					    <label class="form-check-label" for="pin4_high"><img src="./img/high.png"></label>
                    					</div>
                    					<div class="form-check form-check-inline">
                    					    <input class="form-check-input" type="radio" name="pin_option" id="pin4_low" value="1">
                    					    <label class="form-check-label" for="pin4_low"><img src="./img/low.png"></label>
                    					</div>
                                    </div>

                                    <div class="col-1 t1">9:</div>
                                    <div class="col t2">
                    			      	<div class="col-4 form-check form-check-inline">
                    					    <input class="form-check-input" type="radio" name="pin_option" id="pin9_high" value="1">
                    					    <label class="form-check-label" for="pin9_high"><img src="./img/high.png"></label>
                    					</div>
                    					<div class="form-check form-check-inline">
                    					    <input class="form-check-input" type="radio" name="pin_option" id="pin9_low" value="1">
                    					    <label class="form-check-label" for="pin9_low"><img src="./img/low.png"></label>
                    					</div>
                                    </div>
                                </div>

                                <div class="row input-pin">
                                    <div class="col-1 t1">5:</div>
                                    <div class="col t2" >
                    			      	<div class="col-4 form-check form-check-inline">
                    					    <input class="form-check-input" type="radio" name="pin_option" id="pin5_high" value="1">
                    					    <label class="form-check-label" for="pin5_high"><img src="./img/high.png"></label>
                    					</div>
                    					<div class="form-check form-check-inline">
                    					    <input class="form-check-input" type="radio" name="pin_option" id="pin5_low" value="1">
                    					    <label class="form-check-label" for="pin5_low"><img src="./img/low.png"></label>
                    					</div>
                                    </div>

                                    <div class="col-1 t1">10:</div>
                                    <div class="col t2">
                    			      	<div class="col-4 form-check form-check-inline">
                    					    <input class="form-check-input" type="radio" name="pin_option" id="pin10_high" value="1">
                    					    <label class="form-check-label" for="pin10_high"><img src="./img/high.png"></label>
                    					</div>
                    					<div class="form-check form-check-inline">
                    					    <input class="form-check-input" type="radio" name="pin_option" id="pin10_low" value="1">
                    					    <label class="form-check-label" for="pin10_low"><img src="./img/low.png"></label>
                    					</div>
                                    </div>
                                </div>

                                <div class="row input-pin">
                                    <div class="col-1 t1">6:</div>
                                    <div class="col t2" >
                    			      	<div class="col-2 form-check form-check-inline">
                    					    <input class="form-check-input" type="radio" name="pin_option" id="pin6_high" value="1">
                    					    <label class="form-check-label" for="pin6_high"><img src="./img/high.png"></label>
                    					</div>
                    					<div class="col form-check form-check-inline" style="margin-left: -10px">
                    					    <input class="form-check-input" type="radio" name="pin_option" id="pin6_low" value="1">
                    					    <label class="form-check-label" for="pin6_low"><img src="./img/low.png"></label>
                    					</div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer justify-content-center">
                            <button id="" class="button-modal" onclick="add_member_user()">Save</button>
                            <button id="" class="button-modal" onclick="document.getElementById('newinput').style.display='none'" class="closebtn">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Copy Input -->
            <div id="copyinput" class="modal">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content w3-animate-zoom" style="width: 60%;">
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
                				            <input type="number" class="form-control" id="from_job_name" disabled>
                				        </div>
                				    </div>
                			    </div>

                			    <label for="from_job_id" class="col col-form-label" style="font-weight: bold;padding-left: 5%;">Copy To</label>
                			    <div style="padding-left: 10%">
                				    <div class="row">
                				        <label for="to_step_id" class="t1 col-4 col-form-label">Job :</label>
                				        <div class="t2 col-6">
                                            <select id="JobSelect" class="col custom-file" style="margin: center; width: 153px">
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
                            <button id="" class="button-modal" onclick="document.getElementById('copyinput').style.display='none'" class="closebtn">Close</button>
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
    highlight_row('input_table');
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
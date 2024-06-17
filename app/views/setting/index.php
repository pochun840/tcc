<?php require APPROOT . 'views/inc/header.php'; ?>
<body>
<div class="container-ms">
    <div class="w3-text-white w3-center">
        <table class="no-border">
            <tr id="header">
                <td width="100%">
                    <h3>Setting</h3>
                </td>
                <td>
                 <img src="./img/btn_home.png" style="margin-right: 10px" onclick="window.location.href = '?url=In';">
                </td>
            </tr>
        </table>
    </div>
    <div class="main-content">
        <div class="center-content">
            <div class="w3-center">
                <button id="bnt1" name="Controller_Display" class="button active" onclick="OpenButton('Controller')">Controller</button>
                <button id="bnt2" name="System_Display" class="button" onclick="OpenButton('System')">System</button>
                <button id="bnt3" name="Barcode_Display" class="button" onclick="OpenButton('Barcode')">Barcode</button>
                <button id="bnt4" name="Connect_Display" class="button" onclick="OpenButton('Connect')">Connection</button>
                <button id="bnt5" name="iDas_Display" class="button" onclick="OpenButton('Update')">iDAS</button>
            </div>
        
            <div id="Controller_Setting" class="divMode">
                <div class="col t1" style="padding-left: 3%;font-weight: bold; padding-top: 1%">Controller Setting</div>
                <div class="row t2">
                    <div class="col-3 t1">ID:</div>
                    <div class="col-3 t2">
                        <input id="control_id" name="control_id" type="number" max=250 min=1 maxlength="3" value="<?php echo $data['controller_info']['device_id'];?>" class="t3 form-control"  required>
                    </div>
                </div>    
                <div class="row t2">
                    <div class="col-3 t1">Name:</div>
                    <div class="col-3 t2">
                        <input id="control_name" name="control_name" maxlength="12" type="text" value="" class="t3 form-control"  required>
                    </div>
                </div>    
                <div class="row t2">
                    <div class="col-3 t1">Language:</div>
                    <div class="col-3 t2">
                        <select class="form-select" id="select_language" name="select_language">
                            <?php foreach($data['lang_arr'] as $k_lang =>$v_lang){?>
                            <option value="<?php echo $k_lang;?>"><?php echo $v_lang;?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>    
                <div class="row t2">
                    <div class="col-3 t1">Batch Mode:</div>
                    <div class="col t2" >
      			      	<div class="col-1 form-check form-check-inline">
        				    <input class="form-check-input" type="radio" name="batch-mode-option" id="dec" value="1" checked="checked">
            				<label class="form-check-label" for="dec">DEC</label>
            			</div>
            			<div class="form-check form-check-inline">
            			    <input class="form-check-input" type="radio" name="batch-mode-option" id="inc" value="2">
            				<label class="form-check-label" for="inc">INC</label>
            			</div>
                    </div>
                </div>
                <div class="row t2">
                    <div class="col-3 t1">Buzzer:</div>
                    <div class="col t2">
      			      	<div class="col-1 form-check form-check-inline">
           				    <input class="form-check-input" type="radio" name="buzzer-option" id="buzzer-on" value="1" checked="checked">
               				<label class="form-check-label" for="buzzer-on">ON</label>
               			</div>
              			<div class="form-check form-check-inline">
               			    <input class="form-check-input" type="radio" name="buzzer-option" id="buzzer-off" value="2">
               				<label class="form-check-label" for="buzzer-off">OFF</label>
               			</div>
                    </div>
                </div>
                <div style="text-align: center;margin-top: 50px;">
                    <button class="all-btn w3-button w3-border w3-round-large" id="cc_save" onclick="cc_save()">Save</button>
                </div>
            </div>

            <div id="System_Setting" class="divMode" style="display: none">
                <div class="col t1" style="padding-left: 3%;font-weight: bold; padding-top: 1%">System Setting</div>
                <div class="row t2">
                    <div class="col-3 t1">Password:</div>
                    <div class="col t2">
                        <form id="edit_password" method="get" style="margin: 3px 0px">
                            <input type="password" id="new_password" size="15" placeholder="New Password" maxlength="10" required class="t3 w3-submit w3-border w3-round">&nbsp;
                            <input type="password" id="comfirm_password" size="15" placeholder="Confirm Password" maxlength="10" required class="t3 w3-submit w3-border w3-round">
                            <input type="submit" value="Save" class="all-btn w3-submit w3-border w3-round-large" style="float: right">
                        </form>
                    </div>        
                </div>          
                <div class="row t2">
                    <div class="col-3 t1">System Date(UTC):</div>
                    <div class="col t2">
                        <form style="margin: 3px 0px">
                            <span id="currentSystemTime"></span>&nbsp;
                            <input type="datetime-local" id="newTime" value="" required class="t3 w3-submit w3-border w3-round">
                            <!-- 使用按钮来触发日期时间选择器 -->
                            <input type="submit" value="Save" class="all-btn w3-submit w3-border w3-round-large" style="float: right">
                        </form>
                    </div>        
                </div>          
                <div class="row t2">
                    <div class="col t1">Export Config:</div>
                    <div class="col t2">
                        <button class="all-btn w3-button w3-border w3-round-large" style="float: right">Export Config</button>
                    </div>        
                </div>          
                <div class="row t2">
                    <div class="col-3 t1">Import Config:</div>
                    <div class="col t2">
                        <input type="file" id="import-file-uploader" data-target="import-file-uploader" accept=".cfg" class="t3 w3-submit w3-border w3-round">
                        <button class="all-btn w3-button w3-border w3-round-large" style="float: right">Export Config</button>
                    </div>        
                </div>          
                <div class="row t2">
                    <div class="col-3 t1">Firmware Update:</div>
                    <div class="col t2">
                        <input type="file" id="firmware-file-uploader" data-target="firmware-file-uploader" accept=".cfg" class="t3 w3-submit w3-border w3-round">
                        <button class="all-btn w3-button w3-border w3-round-large" style="float: right">Firmware Update</button>
                    </div>        
                </div>          
            </div>

            <div id="Barcode_Setting" class="divMode" style="display: none">
                <div class="col t1" style="padding-left: 3%;font-weight: bold; padding-top: 1%">Barcode Setting</div>
                <div class="table-container">
                    <div class="scrollbar" id="style-table">
                        <div class="scrollbar-force-overflow">
                            <table id="job_table" class="table w3-table-all w3-hoverable">
                                <thead id="header-table">
                                    <tr class="w3-dark-grey">
                                        <th></th>
                                        <th>Job ID</th>
                                        <th>Job Name</th>
                                        <th>Barcode</th>
                                        <th>From</th>
                                        <th>Count</th>
                                    </tr>
                                </thead>

                                <tbody style="font-size: 1.8vmin;text-align: center;">
                                    <tr>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <input class="form-check-input" type="checkbox" name="barcode_check" id="" value="" style="zoom:1.2">
                                        </td>
                                        <td>1</td>
                                        <td>Sample Job 1</td>
                                        <td>833940022047229394673624</td>
                                        <td>1</td>
                                        <td>20</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <input class="form-check-input" type="checkbox" name="barcode_check" id="" value="" style="zoom:1.2">
                                        </td>
                                        <td>2</td>
                                        <td>Sample Job 2</td>
                                        <td>940204722394636407524</td>
                                        <td>1</td>
                                        <td>18</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <input class="form-check-input" type="checkbox" name="barcode_check" id="" value="" style="zoom:1.2">
                                        </td>
                                        <td>3</td>
                                        <td>Sample Job 3</td>
                                        <td>402472394364074854721232</td>
                                        <td>1</td>
                                        <td>20</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <input class="form-check-input" type="checkbox" name="barcode_check" id="" value="" style="zoom:1.2">
                                        </td>
                                        <td>4</td>
                                        <td>Sample Job 4</td>
                                        <td>402472394364074854721232</td>
                                        <td>1</td>
                                        <td>20</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <input class="form-check-input" type="checkbox" name="barcode_check" id="" value="" style="zoom:1.2">
                                        </td>
                                        <td>5</td>
                                        <td>Sample Job 5</td>
                                        <td>402472394364074854721232</td>
                                        <td>1</td>
                                        <td>20</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                 
                <hr>
                               
                <div class="row t2">
                    <div class="col-3 t1">Barcode:</div>
                    <div class="col-6 t2">
                        <input id="barcode_name" name="barcode_name" style="height: 32px" type="text" value="" maxlength="54" class="form-control" required>
                    </div>
                </div>
                <div class="row t2">
                    <div class="col-3 t1">From:</div>
                    <div class="col-3 t2">
                        <input id="barcode_from" name="barcode_from" style="height: 32px" type="text" value="" class="form-control">
                    </div>
                </div>
                <div class="row t2">
                    <div class="col-3 t1">Count:</div>
                    <div class="col-3 t2">
                        <input id="barcode_count" name="barcode_count" style="height: 32px" type="text" value="" class="form-control">
                    </div>
                </div>
                <div class="row t2">
                    <div class="col-3 t1">Select Job:</div>
                    <div class="col-3 t2">
                        <select class="form-select" id="barcode_mode" style="height: 32px">
                            <option value="-1">Please Select Job</option>
                            <option value="1">Sample Job 1</option>
                            <option value="2">Sample Job 2</option>
                            <option value="3">Sample Job 3</option>
                        </select>
                    </div>
                </div>
                <div style="text-align: center;margin-top: 50px;">
                    <button class="all-btn w3-button w3-border w3-round-large">Save</button>&nbsp;&nbsp;
                    <button class="all-btn w3-button w3-border w3-round-large">Delete</button>
                </div>               
            </div>

            <div id="Connect_Setting" class="divMode" style="display: none">
                <div class="col t1" style="padding-left: 3%;font-weight: bold; padding-top: 1%">Connection Setting</div>
                <div class="row t2">
                    <div class="col-3 t1">Number of Connection:</div>
                    <div class="col t2">
                        <form id="edit_max_link" style="margin: 3px 0px" method="post">
                            <input type="text" name="max_user" id="max_user" inputmode="numeric" pattern="[0-9]*" min='1' size="15" maxlength="2" required class="t3 w3-submit w3-border w3-round">&nbsp;
                            <span>maximum number of connect : 5</span>
                            <input type="submit" value="Save" class="all-btn w3-submit w3-border w3-round-large" style="float: right">
                        </form>
                    </div>
                </div>
                <div class="row t2">
                    <div class="col-3 t1">Guest Password:</div>
                    <div class="col t2">
                        <form id="edit_guest_password" method="post" style="margin: 3px 0px">
                            <input type="password" id="new_password_guest" size="15" placeholder="New Password" maxlength="10" required class="t3 w3-submit w3-border w3-round">&nbsp;
                            <input type="password" id="comfirm_password_guest" size="15" placeholder="Confirm Password" maxlength="10" required class="t3 w3-submit w3-border w3-round">
                            <input type="submit" value="Save" class="all-btn w3-submit w3-border w3-round-large" style="float: right">
                        </form>
                    </div>        
                </div>          
                <div class="row t2">
                    <div class="col-3 t1">Agent IP:</div>
                    <div class="col t2">
                        <form id="agent_ip" style="margin: 3px 0px" method="post">
                            <input type="text" name="agent_server_ip" id="agent_server_ip" size="15" required class="t3 w3-submit w3-border w3-round">&nbsp;
                            <span>Agent IP : 192.168.0.186</span>
                            <input type="submit" value="Save" class="all-btn w3-submit w3-border w3-round-large" style="float: right">
                        </form>
                    </div>
                </div>
                <div class="row t2">
                    <div class="col-3 t1">Agent Type:</div>
                    <div class="col t2">
                        <form id="agent_type_form" onsubmit="set_agent_type();return false;" method="post">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="agent_type" id="agent_type_0" value="0">
                                <label class="form-check-label" for="agent_type_0">None</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="agent_type" id="agent_type_1" value="1">
                                <label class="form-check-label" for="agent_type_1">Client</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="agent_type" id="agent_type_2" value="2" required>
                                <label class="form-check-label" for="agent_type_2">Server</label>
                            </div>

                            <input type="submit" value="Save" class="all-btn w3-submit w3-border w3-round-large" style="float: right">
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-3 t1"></div>
                        <div class="col t2">
                            <span>Client Status:<div id="c_status" style="display:inline-block;"></div></span>
                            <span>Server Status:<div id="s_status" style="display:inline-block;"></div></span>

                            <button class="all-btn w3-button w3-border w3-round-large" style="margin: 5px">Check</button>
                            <button class="all-btn w3-button w3-border w3-round-large" style="margin: 5px;">START</button>
                            <button class="all-btn w3-button w3-border w3-round-large" style="margin: 5px">STOP</button>
                        </div>
                    </div>
                </div>

                <hr>
                
                <form action="" method="post" style="padding: 0px 15px; ">
                    <div class="table-responsive" style="overflow-y: auto; margin-bottom: 20px">
                        <div class="scrollbar" id="style-table">
                            <div class="scrollbar-force-overflow">
                                <table class="table w3-table-all w3-hoverable">
                                    <thead id="header-table">
                                        <tr class="w3-dark-grey">
                                            <th>Select</th>
                                            <th>User</th>
                                            <th>IP</th>
                                            <th>Last Connection</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 1.8vmin;text-align: center;">
                                        <tr>
                                            <td style="text-align: center; vertical-align: middle;">
                                                <input class="form-check-input" type="checkbox" name="barcode_check" id="" value="" style="zoom:1.2">
                                            </td>
                                            <td>admin</td>
                                            <td>192.168.0.19</td>
                                            <td>2024-06-10 11:08:09</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>    
                        </div>  
                    </div>

                    <input type="submit" value="Delete" class="all-btn w3-submit w3-border w3-round-large" style="float: right;">

                </form>
            </div>

            <div id="iDas-Update_Setting" class="divMode" style="display: none;">
               <div class="row t2" style="padding-top: 30px">
                    <div class="col-3 t1">Current iDAS Version:</div>
                    <div class="col-3 t2">
                        <input id="idas_software_version" name="idas_software_version" type="text" value="" style="height: 32px" class="form-control" disabled>
                    </div>
                </div>
                <div class="row t2">
                    <div class="col-3 t1">Match Controller Version:</div>
                    <div class="col-3 t2">
                        <input id="match_control_version" name="match_control_version" type="text" value="" style="height: 32px" class="form-control" disabled>
                    </div>
                </div>
                <div class="row t2">
                    <div class="col-3 t1">Upload file:</div>
                    <div class="col-3 t2">
                        <input type="file" id="file-uploader" data-target="file-uploader" accept=".pack" class="form-control" style="height: 32px">
                    </div>
                </div>

                <div style="text-align: center;margin-top:50px;">
                    <input class="all-btn w3-submit w3-border w3-round-large" type="button" value="Update">
                </div> 
            </div>
        </div>
    </div>        
</div>

<script>
// Button mode setting
function OpenButton(ButtonMode)
{
    if (ButtonMode == "Controller")
    {
        document.getElementById('Controller_Setting').style.display = "";
        document.getElementById('System_Setting').style.display = "none";
        document.getElementById('Barcode_Setting').style.display = "none";
        document.getElementById('Connect_Setting').style.display = "none";
        document.getElementById('iDas-Update_Setting').style.display = "none";
        document.getElementById('bnt1').classList.add("active");
        document.getElementById('bnt2').classList.remove("active");   
        document.getElementById('bnt3').classList.remove("active");
        document.getElementById('bnt4').classList.remove("active");
        document.getElementById('bnt5').classList.remove("active");
    }
    else if (ButtonMode == "System")
    {
        document.getElementById('System_Setting').style.display = "";
        document.getElementById('Controller_Setting').style.display = "none";
        document.getElementById('Barcode_Setting').style.display = "none";
        document.getElementById('Connect_Setting').style.display = "none";
        document.getElementById('iDas-Update_Setting').style.display = "none";
        document.getElementById('bnt2').classList.add("active");
        document.getElementById('bnt1').classList.remove("active");
        document.getElementById('bnt3').classList.remove("active");
        document.getElementById('bnt4').classList.remove("active");
        document.getElementById('bnt5').classList.remove("active");


        var times ='<?php echo?>'
    }
    else if (ButtonMode == "Barcode")
    {
        document.getElementById('Barcode_Setting').style.display = "";
        document.getElementById('System_Setting').style.display = "none";
        document.getElementById('Controller_Setting').style.display = "none";
        document.getElementById('Connect_Setting').style.display = "none";
        document.getElementById('iDas-Update_Setting').style.display = "none";
        document.getElementById('bnt3').classList.add("active");
        document.getElementById('bnt2').classList.remove("active");
        document.getElementById('bnt1').classList.remove("active");
        document.getElementById('bnt4').classList.remove("active");
        document.getElementById('bnt5').classList.remove("active");
    }
    else if (ButtonMode == "Connect")
    {
        document.getElementById('Connect_Setting').style.display = "";
        document.getElementById('Barcode_Setting').style.display = "none";
        document.getElementById('System_Setting').style.display = "none";
        document.getElementById('Controller_Setting').style.display = "none";
        document.getElementById('iDas-Update_Setting').style.display = "none";
        document.getElementById('bnt4').classList.add("active");
        document.getElementById('bnt3').classList.remove("active");
        document.getElementById('bnt2').classList.remove("active");
        document.getElementById('bnt1').classList.remove("active");
        document.getElementById('bnt5').classList.remove("active");
    }
    else if (ButtonMode == "Update")
    {
        document.getElementById('iDas-Update_Setting').style.display = "";
        document.getElementById('Connect_Setting').style.display = "none";
        document.getElementById('Barcode_Setting').style.display = "none";
        document.getElementById('System_Setting').style.display = "none";
        document.getElementById('Controller_Setting').style.display = "none";
        document.getElementById('bnt5').classList.add("active");
        document.getElementById('bnt4').classList.remove("active");
        document.getElementById('bnt3').classList.remove("active");
        document.getElementById('bnt2').classList.remove("active");
        document.getElementById('bnt1').classList.remove("active");
    }
    else
    {
        alert("Function ["+ ButtonMode +"] is under constructing ...");
    }
}

function getCookie(name) 
{
    var nameEQ = name + "=";
    //alert(document.cookie);
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1);
        if (c.indexOf(nameEQ) != -1) return c.substring(nameEQ.length, c.length);
    }
    return null;
}


function cc_save(){
    //
    var control_id = document.getElementById('control_id').value;
    if (isNaN(control_id) || control_id < 1 || control_id > 250) {
        return false;
    }

    var control_name = document.getElementById('control_name').value;
    var lang_val = document.getElementById('select_language').value;
    var batch_val = document.querySelector('input[name="batch-mode-option"]:checked').value;
    var buzzer_val = document.querySelector('input[name="buzzer-option"]:checked').value;

    if(control_id){
        $.ajax({
            url: "?url=Settings/control_setting",
            method: "POST",
            data:{ 
                control_id: control_id,
                control_name: control_name,
                lang_val: lang_val,
                batch_val:batch_val,
                buzzer_val:buzzer_val

            },
            success: function(response) {
                console.log( response);
                //history.go(0);
            },
            error: function(xhr, status, error) {
                
            }
        });   
    }






}
</script>    

</body>

</html>
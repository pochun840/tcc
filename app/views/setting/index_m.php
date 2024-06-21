<!DOCTYPE HTML>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/css/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/css/w3.css">
    <link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>//css/tcc_setting_m.css?v=202406210900">
    <script src="<?php echo URLROOT; ?>js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
 
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
  
    <script src="<?php echo URLROOT; ?>js/all.js?v=202406131200"></script>
    <script src="<?php echo URLROOT; ?>js/settings.js?v=202406210900"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<style>

.t1{font-size: 17px; margin: 5px 0px; display: flex; align-items: center;padding-left: 5%}
.t2{font-size: 17px; margin: 5px 0px;}
.t3{font-size: 17px; height: 30px; margin-bottom:2px}

</style>

</head>

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
            
            <!-- Controller Setting -->        
            <div id="Controller_Setting" class="divMode">
                <div class="col t1" style="padding-left: 3%;font-weight: bold; padding-top: 1%">Controller Setting</div>
                <div class="row t2">
                    <div class="col-5 t1">ID:</div>
                    <div class="col-4 t2">
                        <input id="control_id" name="control_id" type="number" max=250 min=1 maxlength="3" value="<?php echo $data['controller_info']['device_id'];?>" class="t3 form-control"  required>
                    </div>
                </div>    
                <div class="row t2">
                    <div class="col-5 t1">Name:</div>
                    <div class="col-5 t2">
                        <input id="control_name" name="control_name" maxlength="14" type="text" value="<?php echo $data['controller_info']['device_name'];?>"  class="t3 form-control"  required>
                    </div>
                </div>    
                <div class="row t2">
                    <div class="col-5 t1">Language:</div>
                    <div class="col-5 t2">
                        <select class="form-select" id="select_language" name="select_language">
                            <?php foreach($data['lang_arr'] as $k_lang =>$v_lang){?>
                            <option value="<?php echo $k_lang;?>"  <?php echo $k_lang == $data['controller_info']['device_language'] ? 'selected' : ''; ?> ><?php echo $v_lang;?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>    
                <div class="row t2">
                    <div class="col-5 t1">Batch Mode:</div>
                    <div class="col t2" >
      			      	<div class="col-3 form-check form-check-inline">
        				    <input class="form-check-input" type="radio" name="batch-mode-option" id="dec" value="1" <?php echo $data['controller_info']['batch'] == 1 ? 'checked="checked"' : ''; ?> >
            				<label class="form-check-label" for="dec">DEC</label>
            			</div>
            			<div class="form-check form-check-inline">
            			    <input class="form-check-input" type="radio" name="batch-mode-option" id="inc" value="2" <?php echo $data['controller_info']['batch'] == 2 ? 'checked="checked"' : ''; ?>>
            				<label class="form-check-label" for="inc">INC</label>
            			</div>
                    </div>
                </div>
                <div class="row t2">
                    <div class="col-5 t1">Buzzer:</div>
                    <div class="col t2">
      			      	<div class="col-3 form-check form-check-inline">
           				    <input class="form-check-input" type="radio" name="buzzer-option" id="buzzer-on" value="1"  <?php echo $data['controller_info']['buzzer_mode'] == 1 ? 'checked="checked"' : ''; ?>>
               				<label class="form-check-label" for="buzzer-on">ON</label>
               			</div>
              			<div class="form-check form-check-inline">
               			    <input class="form-check-input" type="radio" name="buzzer-option" id="buzzer-off" value="2" <?php echo $data['controller_info']['buzzer_mode'] == 2 ? 'checked="checked"' : ''; ?>>
               				<label class="form-check-label" for="buzzer-off">OFF</label>
               			</div>
                    </div>
                </div>
                <div style="text-align: center;margin-top: 50px;">
                    <button class="all-btn w3-button w3-border w3-round-large" onclick="cc_save()">Save</button>
                </div>
            </div>
            
            <!-- System Setting -->
            <div id="System_Setting" class="divMode" style="display: none">
                <div class="col t1" style="padding-left: 3%;font-weight: bold; padding-top: 1%;">System Setting</div>
                <div class="system-scrollbar" id="style-system">
                    <div class="system-force-overflow">
                        <div class="col t1">Password:</div>
                        <div class="row t2 border-bottom">
                            <div class="col t2">
                                <form id="edit_password" method="get" style="margin: 3px 0px; margin-left: 15%">
                                    <input type="password" id="new_password" size="18" placeholder="New Password" maxlength="10" required class="t3 w3-submit w3-border w3-round"><br>
                                    <input type="password" id="comfirm_password" size="18" placeholder="Confirm Password" maxlength="10" required class="t3 w3-submit w3-border w3-round">
                                    <input type="button" value="Save"  onclick="edit_password()"  class="all-btn w3-submit w3-border w3-round-large" style="float: right">
                                </form>
                            </div>        
                        </div>          

                        <div class="col t1">System Date(UTC):</div>
                        <div class="row t2 border-bottom">
                            <div class="col t2">
                                <form style="margin-left: 15%">
                                    <span id="currentSystemTime"></span><br>
                                    <input type="datetime-local" id="newTime" value="" required class="t3 w3-submit w3-border w3-round" style="width: 200px">
                                    
                                    <input type="button" value="Save" class="all-btn w3-submit w3-border w3-round-large" style="float: right" onclick="time_save()">
                                </form>
                            </div>        
                        </div>          
                        <div class="row t2 border-bottom">
                            <div class="col t1">Export Config:</div>
                            <div class="col t2">
                                <button class="all-btn w3-button w3-border w3-round-large" style="float: right" onclick="Export_SystemConfig();">Export Config</button>
                            </div>        
                        </div> 
                        
                        <div class="col t1">Import Config:</div>         
                        <div class="row t2 border-bottom">
                            <div class="col t2" style="margin-left: 15%">
                                <input type="file" id="import-file-uploader" data-target="import-file-uploader" accept=".cfg" class="t3 w3-submit w3-border w3-round" style="width: 250px">
                            </div>        
                            <div class="col t2">
                                <button class="all-btn w3-button w3-border w3-round-large" style="float: right" onclick="Import_SystemConfig();">Import Config</button>
                            </div>
                        </div>          
                        
                        <div class="col t1">Firmware Update:</div>
                        <div class="row t2">
                            <div class="col t2" style="margin-left: 15%">
                                <input type="file" id="firmware-file-uploader" data-target="firmware-file-uploader" accept=".cfg" class="t3 w3-submit w3-border w3-round" style="width: 250px">
                            </div>        
                            <div class="col t2">
                                <button class="all-btn w3-button w3-border w3-round-large" style="float: right" onclick="Firmware_Update();" >Firmware Update<</button>
                            </div>
                        </div>  
                    </div>
                </div>                
            </div>

            <!-- barcode Setting -->
            <div id="Barcode_Setting" class="divMode" style="display: none">
                <div class="col t1" style="padding-left: 3%;font-weight: bold; padding-top: 1%">Barcode Setting</div>
                <div class="barcode-scrollbar" id="style-barcode">
                    <div class="barcode-force-overflow">                
                        <div class="table-container" style="padding: 0px 10px;">
                            <div class="scrollbar" id="style-table">
                                <div class="force-overflow">
                                    <table id="job_table" class="table w3-table-all w3-hoverable">
                                        <thead id="header-table" style="font-size: 2.3vmin;text-align: center;">
                                            <tr class="w3-dark-grey">
                                                <th></th>
                                                <th>Job ID</th>
                                                <th>Job Name</th>
                                                <th>Barcode</th>
                                                <th>From</th>
                                                <th>Count</th>
                                            </tr>
                                        </thead>

                                        <tbody style="font-size: 2.2vmin;text-align: center;">
                                        <?php foreach ($data['barcodes'] as $k_b =>$v_b){?>
                                            <tr>
                                                <td style="text-align: center; vertical-align: middle;">
                                                    <input class="form-check-input" type="checkbox" name="barcode_check" id="barcode_check" value="<?php echo $v_b['barcode_selected_job'];?>" style="zoom:1.2">
                                                </td> 
                                                <td><?php echo $v_b['barcode_selected_job'];?></td>
                                                <td><?php echo $v_b['job_name'];?></td>
                                                <td><?php echo $v_b['barcode'];?></td>
                                                <td><?php echo $v_b['barcode_range_from'];?></td>
                                                <td><?php echo $v_b['barcode_range_count'];?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                         
                        <hr>
                                       
                        <div class="row t2">
                            <div class="col-4 t1">Barcode:</div>
                            <div class="col-8 t2">
                                <input id="barcode_name" name="barcode_name" style="height: 32px" type="text" value="" maxlength="54" class="form-control" required>
                            </div>
                        </div>
                        <div class="row t2">
                            <div class="col-4 t1">From:</div>
                            <div class="col-5 t2">
                                <input id="barcode_from" name="barcode_from" style="height: 32px" type="text" value="" class="form-control">
                            </div>
                        </div>
                        <div class="row t2">
                            <div class="col-4 t1">Count:</div>
                            <div class="col-5 t2">
                                <input id="barcode_count" name="barcode_count" style="height: 32px" type="text" value="" class="form-control">
                            </div>
                        </div>
                        <div class="row t2">
                            <div class="col-4 t1">Select Job:</div>
                            <div class="col-5 t2">
                            <select class="form-select" id="barcode_job" name="barcode_job">
                                <option value="-1">Please Select Job</option>
                                    <?php
                                    foreach ($data['job_list'] as $key => $value) {?>
                                        <option value='<?php echo $value['job_id'];?>'><?php echo $value['job_id']." ".$value['job_name'];?></option>
                                    <?php }?>
                                    
                            </select>
                            </div>
                        </div>
                    </div>
                </div>    
                        
                <div style="text-align: center;margin-top: 30px;">
                    <button class="all-btn w3-button w3-border w3-round-large" onclick="update_barcode()" >Save</button>&nbsp;&nbsp;
                    <button class="all-btn w3-button w3-border w3-round-large" onclick="delete_barcode()" >Delete</button>
                </div>               
            </div>

            <!-- Connection Setting -->
            <div id="Connect_Setting" class="divMode" style="display: none">
                <div class="col t1" style="padding-left: 3%;font-weight: bold; padding-top: 1%">Connection Setting</div>
                <div class="connect-scrollbar" id="style-connection">
                    <div class="connect-force-overflow">
                        <div class="col t1">Number of Connection:</div>
                        <div class="row t2 border-bottom">
                            <div class="col t2">
                                <form id="edit_max_link" style="margin: 3px 0px; margin-left: 14%" method="post">
                                    <input type="text" name="max_user" id="max_user" inputmode="numeric" pattern="[0-9]*" min='1' size="15" maxlength="2" required class="t3 w3-submit w3-border w3-round"><br>
                                    <span>max number of connect : <?php echo $data['max_user']; ?></span>
                                    <input type="button" onclick="set_max_link()" value="Save" class="all-btn w3-submit w3-border w3-round-large" style="float: right">
                                </form>
                            </div>
                        </div>
                        
                        <div class="col t1">Guest Password:</div>
                        <div class="row t2 border-bottom">
                            <div class="col t2">
                                <form  style="margin: 3px 0px; margin-left: 14%">
                                    <input type="password" id="new_password_guest" size="15" placeholder="New Password" maxlength="10" required class="t3 w3-submit w3-border w3-round">&nbsp;
                                    <input type="password" id="comfirm_password_guest" size="15" placeholder="Confirm Password" maxlength="10" required class="t3 w3-submit w3-border w3-round">
                                    <input type="button" value="Save" onclick ="button_save_password_gust()" class="all-btn w3-submit w3-border w3-round-large" style="float: right">
                                </form>
                            </div>        
                        </div>          

                        <div class="col t1">Agent IP:</div>
                        <div class="row t2 border-bottom">
                            <div class="col t2">
                                <form id="agent_ip" style="margin: 3px 0px; margin-left: 14%" method="post">
                                    <input type="text" name="agent_server_ip" id="agent_server_ip" size="15" required class="t3 w3-submit w3-border w3-round"><br>
                                    <span>Agent IP : <?php echo $data['agent_server_ip']; ?></span> 
                                    <input type="button" value="Save" onclick="set_agent_ip()" class="all-btn w3-submit w3-border w3-round-large" style="float: right">
                                </form>
                            </div>
                        </div>

                        <div class="col t1">Agent Type:</div>
                        <div class="row t2">
                            <div class="col t2">
                                <form id="agent_type_form"  method="post" style="margin: 3px 0px; margin-left: 9%">
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
                                    <input type="button" value="Save" onclick="set_agent_type()" class="all-btn w3-submit w3-border w3-round-large" style="float: right">
                            </div>
                            <div class="row">
                                <div class="col t2" style="margin: 3px 0px; margin-left: 9%">
                                    <span>Client Status:<div id="c_status" style="display:inline-block;"></div></span>
                                    <span>Server Status:<div id="s_status" style="display:inline-block;"></div></span>

                                    <button class="all-btn w3-button w3-border w3-round-large" style="margin: 5px"  onclick="StatusCheck()"  >Check</button>
                                    <button class="all-btn w3-button w3-border w3-round-large" style="margin: 5px;" onclick="StatusCheck('start')">START</button>
                                    <button class="all-btn w3-button w3-border w3-round-large" style="margin: 5px"  onclick="StatusCheck('stop')" >STOP</button>
                                </div>
                            </div>
                        </div>

                        <hr>
                        
                        <form action="" method="post" style="padding: 0px 10px">
                            <div class="table-responsive" style="overflow-y: auto; margin-bottom: 20px">
                                <div class="scrollbar" id="style-table">
                                    <div class="scrollbar-force-overflow">
                                        <table class="table w3-table-all w3-hoverable">
                                            <thead id="header-table" style="font-size: 2.3vmin;text-align: center;">
                                                <tr class="w3-dark-grey">
                                                    <th>Select</th>
                                                    <th>User</th>
                                                    <th>IP</th>
                                                    <th>Last Connection</th>
                                                </tr>
                                            </thead>
                                            <tbody style="font-size: 2.2vmin;text-align: center;">
                                                <?php foreach($data['active_session'] as $key =>$val){?>
                                                    <tr>
                                                        <td style="text-align: center; vertical-align: middle;">
                                                            <input class="form-check-input" type="checkbox" name="barcode_check" id="" value="<?php echo $val['id'];?>" style="zoom:1.2">
                                                        </td>
                                                        <td><?php echo $val['username'];?></td>
                                                        <td><?php echo $val['ip'];?></td>
                                                        <td><?php echo $val['timestamp'];?></td>

                                                    </tr>
                                                <?php }?>
                                                
                                              
                                            
                                              
                                                
                                            </tbody>
                                        </table>
                                    </div>    
                                </div>
                                <input type="submit" value="Delete" class="all-btn w3-submit w3-border w3-round-large" style="float: right; margin-top: 10px">                              
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- iDas Update Setting -->
            <div id="iDas-Update_Setting" class="divMode" style="display: none;">
                <div class="col t1" style="padding-top: 5%">Current iDAS Version:</div>
                <div class="row t2">
                    <div class="col-6 t2" style="margin-left: 20%">
                        <input id="idas_software_version" name="idas_software_version" type="text" value="<?php echo $data['iDas_Vesion'];?>"  style="height: 32px" class="form-control" disabled>
                    </div>
                </div>

                <div class="col t1">Match Controller Version:</div>
                <div class="row t2">
                    <div class="col-6 t2" style="margin-left: 20%">
                        <input id="match_control_version" name="match_control_version" type="text" value="" style="height: 32px" class="form-control" disabled>
                    </div>
                </div>

                <div class="col t1">Upload file:</div>
                <div class="row t2">
                    <div class="col-8 t2" style="margin-left: 20%">
                        <input type="file" id="file-uploader" data-target="file-uploader" accept=".pack" class="form-control" style="height: 32px;">
                    </div>
                </div>

                <div style="text-align: center;margin-top:50px;">
                    <input class="all-btn w3-submit w3-border w3-round-large" type="button" value="Update" onclick='idas_update();'>
                </div> 
            </div>
        </div>
    </div>        
</div>

<script>




function time_save(){
    var newTime = document.getElementById('newTime').value;
    var device_id = <?php echo $data['controller_info']['device_id'];?>;
    if(newTime){
        $.ajax({
            url: "?url=Settings/edit_system_date",
            method: "POST",
            data:{ 
                device_id: device_id,
                newTime: newTime

            },
            success: function(response) {
                console.log( response);
                alert(response);
                //history.go(0);
            },
            error: function(xhr, status, error) {
                
            }
        });       
    }

}

function edit_password(){
    var new_password = document.getElementById('new_password').value;
    var comfirm_password = document.getElementById('comfirm_password').value;

    var device_id = <?php echo $data['controller_info']['device_id'];?>;

    if(new_password == comfirm_password){
        $.ajax({
            url: "?url=Settings/edit_password",
            method: "POST",
            data:{ 
                device_id: device_id,
                new_password: new_password

            },
            success: function(response) {
                console.log(response);
                alert(response);
                history.go(0);
            },
            error: function(xhr, status, error) {
                
            }
        });   
    }else{
        alert("請確認密碼");
        return false;
    }
}

function button_save_password_gust(){

    var device_id = <?php echo $data['controller_info']['device_id'];?>;

    var pass_guest1 = document.getElementById('new_password_guest').value;
    var pass_guest2 = document.getElementById('comfirm_password_guest').value;

    //正規化 密碼格式(1個英文+1個數字,長度:4)
    var pattern = /^(?=.*[A-Za-z])(?=.*\d).{4,}$/;
    if(pass_guest1 == pass_guest2 && pattern.test(pass_guest1)){
        $.ajax({
            url: "?url=Admins/EditGuestPwd",
            method: "POST",
            data:{ 
                device_id: device_id,
                new_password: pass_guest1

            },
            success: function(response) {
                console.log(response);
                alert(response);
                history.go(0);
            },
            error: function(xhr, status, error) {
                
            }
        });   
    }else{
        alert('密碼格式不符合要求');
    }

}


</script>    

</body>

</html>
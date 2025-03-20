
<link rel="stylesheet" href="<?php echo URLROOT; ?>css/tcc_setting.css" type="text/css">
<style>
    .form-control{
        width: auto!important; 
        display: initial!important;
    }

    .form-control.is-invalid{
        padding-right:inherit!important;
    }
    .is-invalid~.invalid-feedback{
        display: inline!important;
    }

    .main-content.overlay-active {
    filter: grayscale(100%); /* 完全灰化 */
    pointer-events: none; /* 禁止點擊 */
    opacity: 0.3; /* 降低不透明度 */
    }
</style>

<div class="container-ms">
    <div class="w3-text-white w3-center">
        <table class="no-border">
            <tr id="header">
                <td width="100%"><h3><?php echo $text['setting'];?></h3></td>
                <td><img src="./img/btn_home.png" style="margin-right: 10px" onclick="back()"></td>
            </tr>
        </table>
    </div>
    <div class="main-content">
        <div class="center-content">
            <div class="w3-center">
                <button id="bnt1" name="Controller_Display" class="button active" onclick="OpenButton('Controller')"><?php echo $text['controller_setting'];?></button>
                <button id="bnt2" name="System_Display" class="button" onclick="OpenButton('System')"><?php echo $text['system_setting'];?></button>
                <button id="bnt3" name="Barcode_Display" class="button" onclick="OpenButton('Barcode')"><?php echo $text['system_barcode_setting'] ;?></button>
                <button id="bnt4" name="Connect_Display" class="button" onclick="OpenButton('Connect')"><?php echo $text['system_connect_setting'];?></button>
                <button id="bnt5" name="iDas_Display" class="button" onclick="OpenButton('Update')">iDAS</button>
            </div>
        
            <div id="Controller_Setting" class="divMode" style="display:block;">
                <div class="col t1" style="padding-left: 3%;font-weight: bold; padding-top: 1%"><?php echo $text['controller_setting'];?></div>
                <div class="row t2">
                    <div class="col-3 t1"><?php echo $text['system_id'];?>:</div>
                    <div class="col-3 t1">
                        <input id="control_id" name="control_id" type="number" max=250 min=1 maxlength="3" value="<?php echo $data['controller_info']['device_id'];?>" class="t3 form-control"  required>
                    </div>
                </div>    
                <div class="row t2">
                    <div class="col-3 t1"><?php echo $text['system_name'];?>:</div>
                    <div class="col-3 t1">
                        <input id="control_name" name="control_name" maxlength="12" type="text" value="<?php echo $data['controller_info']['device_name'];?>" class="t3 form-control"  required>
                    </div>
                </div>    
                <div class="row t2">
                    <div class="col-3 t1"><?php echo $text['system_language'];?>:</div>
                    <div class="col-3 t1">
                        <select class="form-select" id="select_language" name="select_language" style="height: 35px;">
                            <?php 
                            foreach($data['lang_arr'] as $k_lang =>$v_lang) { 
                                $selected = ($data['controller_info']['device_language'] ==$k_lang) ? 'selected' : '';
                            ?>
                                <option value="<?php echo $k_lang; ?>" <?php echo $selected; ?>>
                                    <?php echo $v_lang; ?>
                                </option>
                            <?php } ?>
                        </select>

                    </div>
                </div>
                
                <div class="row t2">
                    <div class="col-3 t1"><?php echo $text['torque_unit'];?>:</div>
                    <div class="col-3 t1">
                        <select class="form-select" id="select_torque_unit" name="select_torque_unit" style="height: 35px;">
                            <?php 
                            foreach($data['unit_arr'] as $k_unit => $v_unit) { 
                                $selected = ($data['controller_info']['torque_unit'] == $k_unit) ? 'selected' : '';
                            ?>
                                <option value="<?php echo $k_unit; ?>" <?php echo $selected; ?>>
                                    <?php echo $v_unit; ?>
                                </option>
                            <?php } ?>
                        </select>

                    </div>
                </div>    

                <div class="row t2">
                    <div class="col-3 t1"><?php echo $text['system_batch'];?>:</div>
                    <div class="col t1">
      			      	<div class="col-2 form-check form-check-inline">
        				    <input class="form-check-input" type="radio" name="batch-mode-option" id="dec" value="0"  <?php echo $data['controller_info']['device_batch_mode'] == 0 ? 'checked="checked"' : ''; ?>>
            				<label class="form-check-label" for="dec"><?php echo $text['system_dec'];?></label>
            			</div>
            			<div class="form-check form-check-inline">
            			    <input class="form-check-input" type="radio" name="batch-mode-option" id="inc" value="1"  <?php echo $data['controller_info']['device_batch_mode'] == 1 ? 'checked="checked"' : ''; ?> >
            				<label class="form-check-label" for="inc"><?php echo $text['system_inc'];?></label>
            			</div>
                    </div>
                </div>
                
                <div class="row t2">
                    <div class="col-3 t1"><?php echo $text['system_buzzer'];?>:</div>
                    <div class="col t1">
      			      	<div class="col-2 form-check form-check-inline">
           				    <input class="form-check-input" type="radio" name="buzzer-option" id="buzzer-off" value="0"  <?php echo $data['controller_info']['device_buzzer_mode'] == 0 ? 'checked="checked"' : ''; ?>>
               				<label class="form-check-label" for="buzzer-off"><?php echo $text['switch_off'];?></label>
               			</div>
              			<div class="form-check form-check-inline">
               			    <input class="form-check-input" type="radio" name="buzzer-option" id="buzzer-on" value="1"  <?php echo $data['controller_info']['device_buzzer_mode'] == 1 ? 'checked="checked"' : ''; ?>>
               				<label class="form-check-label" for="buzzer-on"><?php echo $text['switch_on'];?></label>
               			</div>
                    </div>
                </div>
                <div style="text-align: center;margin-top: 50px;">
                    <button class="all-btn w3-button w3-border w3-round-large" id="cc_save" onclick="cc_save()"><?php echo $text['save'];?></button>
                </div>
            </div>

            <div id="System_Setting" class="divMode" style="display: none">
                <div class="col t1" style="padding-left: 3%;font-weight: bold; padding-top: 1%"><?php echo $text['system_setting'];?></div>
                <div class="row t2">
                    <div class="col-3 t1"><?php echo $text['system_password'];?>:</div>
                    <div class="col t3">
                        <form id="edit_password"  style="margin: 3px 0px">
                            <input type="password" id="new_password" size="15" placeholder="<?php echo $text['system_new_password'];?>" maxlength="10" required class="t3 w3-submit w3-border w3-round">&nbsp;
                            <input type="password" id="comfirm_password" size="15" placeholder="<?php echo $text['system_confirm_password'];?>" maxlength="10" required class="t3 w3-submit w3-border w3-round">
                            <input type="button" value="<?php echo $text['save'];?>" onclick="edit_password()" class="all-btn w3-submit w3-border w3-round-large" style="float: right">
                        </form>
                    </div>        
                </div>          
                <div class="row t2">
                    <div class="col-3 t1"><?php echo $text['system_sys_date'];?>(UTC):</div>
                    <div class="col t3">
                        <form style="margin: 3px 0px">
                            <span id="currentSystemTime"></span>&nbsp;
                            <input type="datetime-local" id="newTime" value="" required class="t3 w3-submit w3-border w3-round">
                            <input type="button" value="<?php echo $text['save'];?>" class="all-btn w3-submit w3-border w3-round-large" style="float: right" onclick="time_save()">
                        </form>
                    </div>        
                </div>          
                <div class="row t2">
                    <div class="col t1"><?php echo $text['system_export_config'];?>:</div>
                    <div class="col t3">
                        <button class="all-btn w3-button w3-border w3-round-large" style="float: right" onclick="Export_SystemConfig();"><?php echo $text['system_export_config'];?></button>
                    </div>        
                </div>          
                <div class="row t2">
                    <div class="col-3 t1"><?php echo $text['system_import_config'];?>:</div>
                    <div class="col t3">
                        <input type="file" id="import-file-uploader" data-target="import-file-uploader" accept=".cfg" class="t3 w3-submit w3-border w3-round">
                        <button class="all-btn w3-button w3-border w3-round-large" style="float: right" onclick="Import_SystemConfig();"><?php echo $text['system_import_config'];?></button>
                    </div>        
                </div>          
                <div class="row t2">
                    <div class="col-3 t1"><?php echo $text['system_firmware_update'];?>:</div>
                    <div class="col t3">
                        <input type="file" id="firmware-file-uploader" data-target="firmware-file-uploader" accept=".cfg" class="t3 w3-submit w3-border w3-round">
                        <button class="all-btn w3-button w3-border w3-round-large" style="float: right" onclick="Firmware_Update();"><?php echo $text['system_firmware_update'];?></button>
                    </div>        
                </div>          
            </div>

            <!-- barcode Setting -->
            <div id="Barcode_Setting" class="divMode" style="display: none">
                <div class="col t1" style="padding-left: 3%;font-weight: bold; padding-top: 1%"><?php echo $text['system_barcode_setting'] ;?></div>
                <div class="table-container">
                    <div class="scrollbar" id="style-table">
                        <div class="force-overflow">
                            <table id="job_table" class="table w3-table">
                                <thead id="header-table">
                                    <tr class="w3-dark-grey">
                                        <th></th>
                                        <th><?php echo $text['job_id'];?></th>
                                        <th><?php echo $text['job_name'];?></th>
                                        <th><?php echo $text['system_barcode'];?></th>
                                        <th><?php echo $text['system_barcode_from'];?></th>
                                        <th><?php echo $text['system_barcode_to'];?></th>
                                    </tr>
                                </thead>

                                <tbody style="font-size: 1.8vmin;text-align: center;" id='total_barcodes'>
                                  
                                    <?php foreach ($data['barcodes'] as $k_b =>$v_b){?>
                                        <tr>
                                            <td style="text-align: center; vertical-align: middle;" >
                                                <input class="form-check-input" type="checkbox" name="barcode_check" id="barcode_check" value="<?php echo $v_b['barcode_selected_job'];?>" style="zoom:1.2">
                                            </td> 
                                            <td><?php echo $v_b['barcode_selected_job'];?></td>
                                            <td><?php echo $v_b['job_name'];?></td>
                                            <td><?php echo $v_b['barcode'];?></td>
                                            <td><?php echo $v_b['barcode_mask_from'];?></td>
                                            <td><?php echo $v_b['barcode_mask_count'];?></td>
                                        </tr>
                                    <?php } ?>
                                  
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                 
                <hr>
                               
                <div class="row t2">
                    <div class="col-3 t1"><?php echo $text['system_barcode'];?>:</div>
                    <div class="col-6 t2">
                        <input id="barcode_content" name="barcode_content" style="height: 32px" type="text" value="" maxlength="54" class="form-control" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                
                <div class="row t2">
                    <div class="col-3 t1"><?php echo $text['system_barcode_match_from'];?>:</div>
                    <div class="col-3 t2">
                        <input id="barcode_mask_from" name="barcode_mask_from" style="height: 32px" type="text" value="" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                
                <div class="row t2">
                    <div class="col-3 t1"><?php echo $text['system_barcode_match_to'];?>:</div>
                    <div class="col-3 t2">
                        <input id="barcode_mask_count" name="barcode_mask_count" style="height: 32px" type="text" value="" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="row t2">
                    <div class="col-3 t1"><?php echo $text['select_job'];?>:</div>
                    <div class="col-3 t2">
                        <select class="form-select" id="barcode_selected_job" name="barcode_selected_job">
                            <option value="-1"><?php echo $text['system_barcode_select_job_m'];?></option>
                                <?php
                                foreach ($data['job_list'] as $key => $value) {?>
                                    <option value='<?php echo $value['job_id'];?>'><?php echo $value['job_id']." ".$value['job_name'];?></option>
                                <?php }?>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="row t2">
                    
                    <div class="col-3 t1"><?php echo  $text['system_barcode_mode'];?>:</div>
                    <div class="col-3 t2">
                        <select class="form-select" id="barcode_enable" name="barcode_enable">
                            <option value="-1"><?php echo $text['system_barcode_select'];?></option>
                                <?php
                                foreach ($data['barcode_mode'] as $key_barcode => $value_barcode) {?>
                                    <option value='<?php echo $key_barcode;?>'><?php echo $value_barcode;?></option>
                                <?php }?>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <div style="text-align: center;margin-top: 50px;">
                    <button class="all-btn w3-button w3-border w3-round-large" onclick="update_barcode()" ><?php echo $text['save'];?></button>&nbsp;&nbsp;
                    <button class="all-btn w3-button w3-border w3-round-large" onclick="delete_barcode()" ><?php echo $text['delete_text'];?></button>
                </div>               
            </div>

            <div id="Connect_Setting" class="divMode" style="display: none">
               
                         
                <div class="row t2">
                    <div class="col-3 t1">Agent IP:</div>
                    <div class="col t3">
                            <input type="text" name="agent_server_ip" id="agent_server_ip" size="15" required class="t3 w3-submit w3-border w3-round">&nbsp;
                            <input type="button" onclick="agent_ip_save()" value="<?php echo $text['save'];?>" class="all-btn w3-submit w3-border w3-round-large" style="float: right">
                    </div>
                </div>
                <div class="row t2">
                    <div class="col-3 t1">Agent Type:</div>
                    <div class="col t3">
                        <form id="agent_type_form" method="post">
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

                            <input type="button" onclick="agent_type_save()" value="<?php echo $text['save'];?>" class="all-btn w3-submit w3-border w3-round-large" style="float: right">
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-3 t1"></div>
                        <div class="col t3">
                            <span>Client Status:<div id="c_status" style="display:inline-block;"></div></span>
                            <span>Server Status:<div id="s_status" style="display:inline-block;"></div></span>

                            <button class="all-btn w3-button w3-border w3-round-large" style="margin: 5px"  onclick="StatusCheck()" >Check</button>
                            <button class="all-btn w3-button w3-border w3-round-large" style="margin: 5px;" onclick="StatusCheck('start')" >START</button>
                            <button class="all-btn w3-button w3-border w3-round-large" style="margin: 5px"  onclick="StatusCheck('stop')" >STOP</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="iDas-Update_Setting" class="divMode" style="display: none;">
               <div class="row t2" style="padding-top: 30px">
                    <div class="col-3 t1">Current iDAS Version:</div>
                    <div class="col-3 t2">
                       
                        <input id="idas_software_version" name="idas_software_version" type="text" value="<?php echo $data['iDas_Vesion'];?>" style="height: 32px" class="form-control" value="" disabled>
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
                    <input class="all-btn w3-submit w3-border w3-round-large" type="button" value="Update" onclick='idas_update();'>
                </div> 
            </div>
        </div>
    </div>  
    
    <!-- 加载動畫 OP -->
    <div id="spinner" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only"></span>
        </div>
    </div>
    <!-- 加载動畫 ED -->

</div>

<script>
window.onload = function() {
    // 檢查 sessionStorage 中的設置，並恢復顯示狀態
    if (sessionStorage.getItem('Barcode_Setting') === 'block') {
        document.getElementById('Barcode_Setting').style.display = "block";
        document.getElementById('System_Setting').style.display = "none";
        document.getElementById('Controller_Setting').style.display = "none";
        
    }

    //
    var tourque_unit = '<?php echo $data['controller_info']['device_torque_unit']?>'; // 3
    var agent_server_ip_current = '<?php echo $data['agent_server_ip']?>';
    document.getElementById('agent_server_ip').value =agent_server_ip_current;
  
    var agent_type_current = '<?php echo $data['agent_type']?>'; 
    document.getElementById('agent_type_' + agent_type_current).checked = true; 



};


function StatusCheck(action) {
        let work_icon = '<svg height="18" width="18" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="M9.001.666A8.336 8.336 0 0 0 .668 8.999c0 4.6 3.733 8.334 8.333 8.334s8.334-3.734 8.334-8.334S13.6.666 9 .666Zm0 15a6.676 6.676 0 0 1-6.666-6.667A6.676 6.676 0 0 1 9 2.333a6.676 6.676 0 0 1 6.667 6.666A6.676 6.676 0 0 1 9 15.666Zm-1.666-4.833L5.168 8.666 4.001 9.833l3.334 3.333L14 6.499l-1.166-1.166-5.5 5.5Z" fill="#1E8E3E" fill-rule="evenodd"></path></svg>';
        let not_work_icon = '<svg height="18" width="18" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="M11.16 5.666 9 7.824 6.843 5.666 5.668 6.841l2.158 2.158-2.158 2.159 1.175 1.175 2.158-2.159 2.159 2.159 1.175-1.175-2.159-2.159 2.159-2.158-1.175-1.175ZM9 .666A8.326 8.326 0 0 0 .668 8.999a8.326 8.326 0 0 0 8.333 8.334 8.326 8.326 0 0 0 8.334-8.334A8.326 8.326 0 0 0 9 .666Zm0 15a6.676 6.676 0 0 1-6.666-6.667A6.676 6.676 0 0 1 9 2.333a6.676 6.676 0 0 1 6.667 6.666A6.676 6.676 0 0 1 9 15.666Z" fill="#D93025" fill-rule="evenodd"></path></svg>';

        let url = '?url=Admins/AgentTest';
        if(action == 'start'){
            url = '?url=Admins/StartAgent';
        }
        if(action == 'stop'){
            url = '?url=Admins/CloseAgent';
        }

        $.ajax({ // 提醒
            type: "POST",
            data: { },
            dataType: "json",
            url: url,
            beforeSend: function() {
                $('#overlay').removeClass('hidden');
            },
        }).done(function(result) { //成功且有回傳值才會執行
            $('#overlay').addClass('hidden');
            if(result.server_status == "true"){
                document.getElementById('s_status').innerHTML = work_icon;
            }else{
                document.getElementById('s_status').innerHTML = not_work_icon;
            }
            if(result.server_status == "true"){
                document.getElementById('c_status').innerHTML = work_icon;
            }else{
                document.getElementById('c_status').innerHTML = not_work_icon;
            }
        });
    }




function agent_type_save(){

    var agent_type = document.querySelector('input[name="agent_type"]:checked').value;
    if(agent_type){
        document.querySelector(".main-content").classList.add("overlay-active");
        document.getElementById('spinner').style.display = 'block';

        $.ajax({
            url: "?url=Admins/SetAgentType",
            method: "POST",
            data:{ 
                agent_type: agent_type
            },
            success: function(response) {
                var responseData = JSON.parse(response);
                alertify.alert(responseData.res_type, responseData.res_msg);
                
                setTimeout(function() {
                    alertify.closeAll(); 
                    document.getElementById('spinner').style.display = 'none';
                    document.querySelector(".main-content").classList.remove("overlay-active"); 
                }, 3000); 
            },
            
            error: function(xhr, status, error) {
                
            }
        });   
    }

}

function agent_ip_save() {
    var language = getCookie('language') || 'en-us'; 
    var agent_server_ip = document.getElementById('agent_server_ip').value;

    // 正規表達式：檢查 IPv4 位址的格式是否正確
    var ipRegex = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;

    // 錯誤提示訊息
    var errorMessage = {
        'en-us': "Please enter a valid IP address.",
        'zh-tw': "請輸入有效的 IP 地址。",
        'zh_cn': "请输入有效的 IP 地址。"  
    };

    // 如果有填寫 IP，且格式符合正規表達式
    if (agent_server_ip && ipRegex.test(agent_server_ip)) {  
        
        // 顯示加載動畫
        document.getElementById('spinner').style.display = 'block';

        $.ajax({
            url: "?url=Admins/SetAgentIp",
            method: "POST",
            data: { 
                agent_server_ip: agent_server_ip
            },
            success: function(response) {
                var responseData = JSON.parse(response); 

                console.log(responseData);  
                
                // 延遲 1000 毫秒後隱藏加載動畫，並在隱藏後顯示 alertify 彈跳視窗
                setTimeout(function() {
                    // 隱藏 'copyjob' 和 'spinner' 加載動畫
                    document.querySelector(".main-content").classList.remove("overlay-active");
                    document.getElementById('spinner').style.display = 'none';  

                    sessionStorage.setItem('Connect_Setting', 'block');
                    sessionStorage.setItem('Controller_Setting', 'none');

                    alertify.alert(responseData.res_type, responseData.res_msg, function() {
                        history.go(0); 
                    });
                    setTimeout(function() {
                        alertify.closeAll(); 
                    }, 3000); 
                }, 1000); 
                
                document.getElementById('agent_server_ip').innerText = responseData.res_number;
            },

            error: function(xhr, status, error) {
            }
        });
    } else {
        alertify.alert("Error", language === 'en-us' ? errorMessage.en : (language === 'zh-tw' ? errorMessage.zh : errorMessage.zh_cn), function() {
            setTimeout(function() {
                alertify.closeAll();  
            }, 3000); 
        });
    }
}




function edit_password() {
    var new_password = document.getElementById('new_password').value;
    var confirm_password = document.getElementById('comfirm_password').value;

    var language = getCookie('language') || 'en'; 
    var text_info, title, confirm_text;
    
    if(language == "zh-cn") {
        text_info = '新密码与确认密码不一致，请重新输入。';
        title = '修改密码';
        confirm_text = '您确定要修改密码吗？'; 
    } else if(language == "zh-tw") {
        text_info = '新密碼與確認密碼不一致，請重新輸入。';
        title = '修改密碼';
        confirm_text = '您確定要修改密碼嗎？'; 
    } else {
        text_info = 'The new password and confirm password do not match. Please try again.';
        title = 'Edit Password';
        confirm_text = 'Are you sure you want to change the password?'; 
    }


    if (new_password !== confirm_password) {
        alertify.alert(title, text_info);
        return; 
    }

    var device_id = <?php echo $data['controller_info']['device_id'];?>;

    alertify.confirm(confirm_text, function(result) {
        if (result) {
            document.getElementById('spinner').style.display = 'block';

            $.ajax({
                url: "?url=Settings/edit_password",
                method: "POST",
                data: { 
                    device_id: device_id,
                    new_password: new_password
                },
                success: function(response) {
                    var responseData = JSON.parse(response);  

                    setTimeout(function() {
                        document.getElementById('spinner').style.display = 'none';
                        alertify.alert(responseData.res_type, responseData.res_msg, function() {
                            sessionStorage.setItem('System_Setting', 'block');
                            sessionStorage.setItem('Controller_Setting', 'none');
                            history.go(0); 
                        });

                        setTimeout(function() {
                            alertify.closeAll(); 
                        }, 3000);
                    }, 1000);
                },
                error: function(xhr, status, error) {
                    alertify.alert('Error', 'An error occurred while updating the password.');
                }
            });
        }
    });
}


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
            success: function(response){
                alert(response);
                //history.go(0);
            },
            error: function(xhr, status, error) {
                
            }
        });       
    }

}



function set_agent_type(argument) {

    var  agent_type = document.querySelector('input[name="agent_type"]:checked').value;
    if(agent_type ){
        $.ajax({
            url: "?url=Admins/SetAgentType",
            method: "POST",
            data:{ 
                agent_type: agent_type
            },
            success: function(response) {
                console.log(response);
                alert(response);
                //history.go(0);
            },
            error: function(xhr, status, error) {
                
            }
        });   
    }
 
}



/*const fileUploader = document.querySelector('#file-uploader');

function idas_update() {
    let ff = document.querySelector('#file-uploader').files;
    let bb = document.getElementById("file-uploader").files[0];
    let form = new FormData();
    form.append("file", bb)

    let url = '?url=Settings/iDas_Update';
    $.ajax({ // 提醒
        type: "POST",
        processData: false,
        cache: false,
        contentType: false,
        data: form,
        dataType: "json",
        url: url,
        beforeSend: function() {
            $('#overlay').removeClass('hidden');
        },
    }).done(function(result) { //成功且有回傳值才會執行
        $('#overlay').addClass('hidden');

        if (result.message != '') {
            Swal.fire({ // DB sync notice
                title: 'Error',
                text: result.message,
            })
        } else {
            Swal.fire('', '', 'success');
            setTimeout(function() {history.go(0)}, 2000);
        }
        document.getElementById("file-uploader").value = '';
        
    });
}*/

function OpenButton(ButtonMode){

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
}

function input_check_savebarcode() {

    let rangeLabel = "<?php echo $error_message['OOR']; ?>"; 

    let conditions = [
        { id: 'barcode_content', pattern: /^[a-zA-Z0-9\u4E00-\u9FA5\-]+$/, min: null, max: null },
        { id: 'barcode_mask_from', pattern: /^[0-9]+$/, min: 1, max: 54 },
        { id: 'barcode_mask_count', pattern: /^[0-9]+$/, min: 1, max: 54 },

    ];

    let isFormValid = true;

    conditions.forEach(function(input) {
        var element = document.getElementById(input.id);
        if (input.id !== 'barcode_content') {
            element.nextElementSibling.innerHTML = `${rangeLabel} ${input.min} ~ ${input.max}`;
        }

        if (!validateInput(element, input.pattern, input.min, input.max)) {
            isFormValid = false;
        }
    });

    return isFormValid;
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
</script>    
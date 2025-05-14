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
            <div class="menu-button w3-center">
                <button id="bnt1" name="Controller_Display" class="button active" data-mode="Controller"><?php echo $text['controller_setting'];?></button>
                <button id="bnt2" name="System_Display" class="button" data-mode="System"><?php echo $text['system_setting'];?></button>
                <button id="bnt3" name="Barcode_Display" class="button" data-mode="Barcode"><?php echo $text['system_barcode_setting'] ;?></button>
                <button id="bnt4" name="Connect_Display" class="button" data-mode="Connect"><?php echo $text['system_connect_setting'];?></button>
                <button id="bnt5" name="iDas_Display" class="button" data-mode="Update">iDAS</button>
            </div>
            
            <!-- Controller Setting -->        
            <div id="Controller_Setting" class="divMode active">
                <div class="col t1" style="padding-left: 3%;font-weight: bold; padding-top: 1%"><?php echo $text['controller_setting'];?></div>
                <div class="barcode-scrollbar" id="style-barcode">
                    <div class="barcode-force-overflow">   
                        <div class="row t2">
                            <div class="col-6 t1"><?php echo $text['system_id'];?>:</div>
                            <div class="col-4 t2">
                                <input id="control_id" name="control_id" type="number" max=250 min=1 maxlength="3" value="<?php echo $data['controller_info']['device_id'];?>" class="t3 form-control"  required>
                            </div>
                        </div>    
                        <div class="row t2">
                            <div class="col-6 t1"><?php echo $text['system_name'];?>:</div>
                            <div class="col-4 t2">
                                <input id="control_name" name="control_name" maxlength="" type="text" value="<?php echo $data['controller_info']['device_name'];?>"  class="t3 form-control"  required>
                            </div>
                        </div>    
                        <div class="row t2">
                            <div class="col-6 t1"><?php echo $text['system_language'];?>:</div>
                            <div class="col-4 t2">
                                <select class="form-select" id="select_language" name="select_language" style="height: 35px;" >
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
                            <div class="col-6 t1"><?php echo $text['torque_unit'];?>:</div>
                            <div class="col-4 t2">
                                <select class="form-select" id="select_torque_unit" name="select_torque_unit" style="height: 35px;" >
                                    <?php 
                                    foreach($data['unit_arr'] as $k_unit => $v_unit) { 
                                        $selected = ($data['controller_info']['device_torque_unit'] == $k_unit) ? 'selected' : '';
                                    ?>
                                        <option value="<?php echo $k_unit; ?>" <?php echo $selected; ?>>
                                            <?php echo $v_unit; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>  

                        <div class="row t2">
                            <div class="col-6 t1"><?php echo $text['system_batch'];?>:</div>
                            <div class="col t2" >
              			      	<div class="col-3 form-check form-check-inline">
                				    <input class="form-check-input" type="radio" name="batch-mode-option" id="dec" value="0" <?php echo $data['controller_info']['device_batch_mode'] == 0 ? 'checked="checked"' : ''; ?> >
                    				<label class="form-check-label" for="dec"><?php echo $text['system_dec'];?></label>
                    			</div>
                    			<div class="form-check form-check-inline">
                    			    <input class="form-check-input" type="radio" name="batch-mode-option" id="inc" value="1" <?php echo $data['controller_info']['device_batch_mode'] == 1 ? 'checked="checked"' : ''; ?>>
                    				<label class="form-check-label" for="inc"><?php echo $text['system_inc'];?></label>
                    			</div>
                            </div>
                        </div>
                        <div class="row t2">
                            <div class="col-6 t1"><?php echo $text['system_buzzer'];?>:</div>
                            <div class="col t2">
              			      	<div class="col-3 form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="buzzer-option" id="buzzer-off" value="0"  <?php echo $data['controller_info']['device_buzzer_mode'] == 0 ? 'checked="checked"' : ''; ?>>
                                    <label class="form-check-label" for="buzzer-off"><?php echo $text['switch_off'];?></label>
                       			</div>
                      			<div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="buzzer-option" id="buzzer-on" value="1"  <?php echo $data['controller_info']['device_buzzer_mode'] == 1 ? 'checked="checked"' : ''; ?>>
                                    <label class="form-check-label" for="buzzer-on"><?php echo $text['switch_on'];?></label>
                       			</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="text-align: center;margin-top: 10px;">
                    <button class="all-btn w3-button w3-border w3-round-large" onclick="cc_save()"><?php echo $text['save'];?></button>
                </div>
            </div>
            
            <!-- System Setting -->
            <div id="System_Setting" class="divMode" >
                <div class="col t1" style="padding-left: 3%;font-weight: bold; padding-top: 1%;"><?php echo $text['system_setting'];?></div>
                <div class="system-scrollbar" id="style-system">
                    <div class="system-force-overflow">
                        <div class="col t1"><?php echo $text['system_password'];?>:</div>
                        <div class="row t2 border-bottom">
                            <div class="col t2">
                                <form id="edit_password" method="get" style="margin: 3px 0px; margin-left: 5%">
                                    <input type="password" id="new_password" size="18" placeholder="<?php echo $text['system_new_password'];?>" maxlength="10" required class="t3 w3-submit w3-border w3-round"><br>
                                    <input type="password" id="comfirm_password" size="18" placeholder="<?php echo $text['system_confirm_password'];?>" maxlength="10" required class="t3 w3-submit w3-border w3-round">
                                    <input type="button" value="<?php echo $text['save'];?>"  onclick="edit_password()"  class="all-btn w3-submit w3-border w3-round-large" style="float: right">
                                </form>
                            </div>        
                        </div>          

                        <div class="col t1"><?php echo $text['system_sys_date'];?>(UTC):</div>
                        <div class="row t2 border-bottom">
                            <div class="col t2">
                                <form style="margin-left: 5%"  onsubmit="change_datetime();return false;" >
                                    <span id="currentSystemTime"></span>
                                    <input type="datetime-local" id="newTime" value="" size="25" required class="w3-submit w3-border" style="margin: 0px 0px 5px; height: 32px">
                                    <input type="button" value="<?php echo $text['save'];?>" class="all-btn w3-submit w3-border w3-round-large" style="float: right" onclick="time_save()">
                                </form>
                            </div>        
                        </div>          
                        <div class="row t2 border-bottom">
                            <div class="col t1"><?php echo $text['system_export_config'];?>:</div>
                            <div class="col t2">
                                <button class="all-btn w3-button w3-border w3-round-large" style="float: right" onclick="Export_SystemConfig();"><?php echo $text['system_export_config'];?></button>
                            </div>        
                        </div> 
                        
                        <div class="col t1"><?php echo $text['system_import_config'];?>:</div>         
                        <div class="row t2 border-bottom">
                            <div class="col t2" style="margin-left: 5%">
                                <input type="file" id="import-file-uploader" data-target="import-file-uploader" accept=".cfg" class="t3 w3-submit w3-border w3-round" style="width: 300px; height: 34px">
                            </div>        
                            <div class="col t2">
                                <button class="all-btn w3-button w3-border w3-round-large" style="float: right" onclick="Import_SystemConfig();"><?php echo $text['system_import_config'];?></button>
                            </div>
                        </div>          
                        
                        <div class="col t1"><?php echo $text['system_firmware_update'];?>:</div>
                        <div class="row t2">
                            <div class="col t2" style="margin-left: 5%">
                                <input type="file" id="firmware-file-uploader" data-target="firmware-file-uploader" accept=".cfg" class="t3 w3-submit w3-border w3-round" style="width: 300px; height: 34px">
                            </div>        
                            <div class="col t2">
                                <button class="all-btn w3-button w3-border w3-round-large" style="float: right" onclick="Firmware_Update();" ><?php echo $text['system_firmware_update'];?></button>
                            </div>
                        </div>  
                    </div>
                </div>                
            </div>

            <!-- barcode Setting -->
            <div id="Barcode_Setting" class="divMode">
                <div class="col t1" style="padding-left: 3%;font-weight: bold; padding-top: 1%"><?php echo $text['system_barcode_setting'] ;?></div>
                <div class="barcode-scrollbar" id="style-barcode">
                    <div class="barcode-force-overflow">   
                        <div style="position: relative; max-width: 100%;">             
                            <div class="table-container" id="tableContainer">
                                <table id="job_table" class="setting-table w3-table w3-hoverable">
                                    <thead style="font-size: 3vmin;">
                                        <tr class="w3-dark-grey">
                                            <th></th>
                                            <th><?php echo $text['job_id'];?></th>
                                            <th><?php echo $text['job_name'];?></th>
                                            <th><?php echo $text['system_barcode'];?></th>
                                            <th><?php echo $text['system_barcode_from'];?></th>
                                            <th><?php echo $text['system_barcode_mode'];?></th>
                                            <th>Count</th>
                                        </tr>
                                    </thead>

                                    <tbody style="font-size: 3vmin;" id='total_barcodes'>
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
                                                <th><?php echo $data['barcode_mode'][$v_b['barcode_enable']];?></th>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="bacode-btn-container">
                                <button class="bacode-btn" onclick="changePage('job_table', -1)">&#60;</button>
                                <button class="bacode-btn" onclick="changePage('job_table', 1)">&#62;</button>
                            </div>
                        </div> 
                            
                        <hr>
                                       
                        <div class="row t2">
                            <div class="col-5 t1"><?php echo $text['system_barcode'];?>:</div>
                            <div class="col-7 t2">
                                <input id="barcode_content" name="barcode_content" style="height: 32px" type="text" value="" maxlength="54" class="form-control" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row t2">
                            <div class="col-5 t1"><?php echo $text['system_barcode_match_from'];?>:</div>
                            <div class="col-7 t2">
                                <input id="barcode_mask_from" name="barcode_mask_from" style="height: 32px" type="text" value="" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row t2">
                            <div class="col-5 t1"><?php echo $text['system_barcode_match_to'];?>:</div>
                            <div class="col-7 t2">
                                <input id="barcode_mask_count" name="barcode_mask_count" style="height: 32px" type="text" value="" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row t2">
                            <div class="col-5 t1"><?php echo $text['select_job'];?>:</div>
                            <div class="col t2">
                                <select class="form-select" id="barcode_selected_job" name="barcode_selected_job">
                                    <option value="-1"><?php echo $text['system_barcode_select_job_m'];?></option>
                                    
                                    <?php if (!empty($data['job_list']) && is_array($data['job_list'])) { ?>
                                        <?php foreach ($data['job_list'] as $key => $value) { ?>
                                            <option value='<?php echo $value['job_id'];?>'><?php echo $value['job_id']." ".$value['job_name'];?></option>
                                        <?php } ?>
                                    <?php } ?>
                                    
                                    <!--
                                        <?php
                                        foreach ($data['job_list'] as $key => $value) {?>
                                            <option value='<?php echo $value['job_id'];?>'><?php echo $value['job_id']." ".$value['job_name'];?></option>
                                        <?php }?>
                                    -->    
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row t2">
                            <div class="col-5 t1"><?php echo $text['system_barcode_mode']?>:</div>
                                <div class="col t2">
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
                    </div>
                </div>    
                        
                <div style="text-align: center;margin-top: 10px;">
                    <button class="all-btn w3-button w3-border w3-round-large" onclick="update_barcode()"><?php echo $text['save'];?></button>&nbsp;&nbsp;
                    <button class="all-btn w3-button w3-border w3-round-large" onclick="delete_barcode()"><?php echo $text['delete_text'];?></button>
                </div>               
            </div>

            <!-- Connection Setting -->
            <div id="Connect_Setting" class="divMode">
                <div class="col t1" style="padding-left: 3%;font-weight: bold; padding-top: 1%"><?php echo $text['system_connect_setting'];?></div>
                <div class="connect-scrollbar" id="style-connection">
                    <div class="connect-force-overflow">
            
                        <div class="col t1">Agent IP:</div>
                        <div class="row t2 border-bottom">
                            <div class="col t2">
                                    <input type="text" name="agent_server_ip" id="agent_server_ip" size="15" value='<?php echo $data['agent_server_ip'];?>' required class="t3 w3-submit w3-border w3-round"><br>
                                    <input type="button" value="Save" onclick="agent_ip_save()" class="all-btn w3-submit w3-border w3-round-large" style="float: right">
                            </div>
                        </div>

                        <div class="col t1">Agent Type:</div>
                        <div class="row t2">
                            <div class="col t2">
                                <form id="agent_type_form"  method="post" style="margin: 3px 0px; margin-left: 5%">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="agent_type" id="agent_type_0" value="0" <?php if($data[''])?>>
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
                                </form>    
                            </div>
                            <div class="row">
                                <div class="col t2" style="margin: 3px 0px; margin-left: 5%">
                                    <span>Client Status:<div id="c_status" style="display:inline-block;"></div></span>
                                    <span>Server Status:<div id="s_status" style="display:inline-block;"></div></span>&nbsp;

                                    <button class="all-btn w3-button w3-border w3-round-large" style="margin: 5px"  onclick="StatusCheck()"  >Check</button>
                                    <button class="all-btn w3-button w3-border w3-round-large" style="margin: 5px;" onclick="StatusCheck('start')">START</button>
                                    <button class="all-btn w3-button w3-border w3-round-large" style="margin: 5px"  onclick="StatusCheck('stop')" >STOP</button>
                                </div>
                            </div>
                        </div>

                        <hr>
                        
                    </div>
                </div>
            </div>

            <!-- iDas Update Setting -->
            <div id="iDas-Update_Setting" class="divMode">
                <div class="col t1" style="padding-top: 5%">Current iDAS Version:</div>
                <div class="row t2">
                    <div class="col-6 t2" style="margin-left: 5%">
                        <input id="idas_software_version" name="idas_software_version" type="text" value="<?php echo $data['iDas_Vesion'];?>"  style="height: 32px" class="form-control" disabled>
                    </div>
                </div>

                <div class="col t1">Match Controller Version:</div>
                <div class="row t2">
                    <div class="col-6 t2" style="margin-left: 5%">
                        <input id="match_control_version" name="match_control_version" type="text" value="" style="height: 32px" class="form-control" disabled>
                    </div>
                </div>

                <div class="col t1">Upload file:</div>
                <div class="row t2">
                    <div class="col-6 t2" style="margin-left: 5%">
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

document.addEventListener('DOMContentLoaded', function () {
    const sectionMap = {
        "Controller": "Controller_Setting",
        "System": "System_Setting",
        "Barcode": "Barcode_Setting",
        "Connect": "Connect_Setting",
        "Update": "iDas-Update_Setting"
    };

    const buttons = document.querySelectorAll('.button');
    const sections = document.querySelectorAll('.divMode');

    buttons.forEach(button => {
        button.addEventListener('click', function () {
            const mode = this.getAttribute('data-mode');
            const targetId = sectionMap[mode];

            // 清除所有按鈕與區塊的 active/hidden
            buttons.forEach(btn => btn.classList.remove('active'));
            sections.forEach(sec => {
                sec.classList.remove('active');
                sec.classList.add('hidden');
            });

            // 顯示對應區塊，標記按鈕為 active
            this.classList.add('active');
            document.getElementById(targetId).classList.add('active');
            document.getElementById(targetId).classList.remove('hidden');
        });
    });


    getCurrentSystemTime();
});


function change_datetime() {
    var newTime = document.getElementById("newTime").value;
    var language = getCookie('language') || 'default';

    var messages = {
        'zh-tw': {
            'select': '請選擇時間',
            'success': '設定成功',
            'fail': '設定失敗',
            'error': '通訊錯誤，請稍後再試。'
        },
        'zh-cn': {
            'select': '请选择时间',
            'success': '设置成功',
            'fail': '设置失败',
            'error': '通信错误，请稍后再试。'
        },
        'default': {
            'select': 'Please select a time',
            'success': 'Success',
            'fail': 'Failed',
            'error': 'Communication error. Please try again later.'
        }
    };

    alert(newTime);

    var msg = messages[language] || messages['default'];

    if (!newTime) {
        alert(msg.select);
        return;
    }

    document.getElementById('spinner').style.display = 'block';

    $.ajax({
        type: "POST",
        url: "?url=Settings/edit_system_date",
        data: { datetime: newTime },
        dataType: "json",
        success: function(response) {
            document.getElementById('spinner').style.display = 'none';

            if (response.error) {
                alertify.alert(msg.fail, response.error);
            } else {
                alertify.alert(msg.success, msg.success);
                setTimeout(function () {
                    alertify.closeAll();
                    location.reload(); // ✅ 自動重整
                }, 3000); // ✅ 自動關閉時間：3秒
            }
        },
        error: function() {
            document.getElementById('spinner').style.display = 'none';
            alertify.alert(msg.fail, msg.error);
        }
    });
}




function getCurrentSystemTime() {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var serverTime = xhr.responseText.trim().replace(/-/g, "/");
            var serverDateTime = new Date(serverTime);
            updateCurrentTime(serverDateTime);
        }
    };
    xhr.open("GET", "?url=Settings/get_system_time", true);
    xhr.send();
}

function updateCurrentTime(serverDateTime) {
    var el = document.getElementById("currentSystemTime");

    function pad(n) {
        return n < 10 ? '0' + n : n;
    }

    function formatTime(date) {
        var year = date.getFullYear();
        var month = pad(date.getMonth() + 1);
        var day = pad(date.getDate());
        var hour = date.getHours();
        var minute = pad(date.getMinutes());
        var second = pad(date.getSeconds());

        let isPM = hour >= 12;
        let period = isPM ? '下午' : '上午';

        // 使用 12 小時制顯示
        let hour12 = hour % 12 || 12;

        return `${year}/${month}/${day} ${period} ${pad(hour12)}:${minute}:${second}`;
    }

    // 初次顯示
    let lastText = formatTime(serverDateTime);
    el.innerText = lastText;

    // 每秒更新一次，但只有在內容變化時才更新畫面，避免閃爍
    setInterval(function () {
        serverDateTime.setSeconds(serverDateTime.getSeconds() + 1);
        let currentText = formatTime(serverDateTime);

        if (el.innerText !== currentText) {
            el.innerText = currentText;
        }
    }, 1000);
}


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
                alert(response);
            },
            error: function(xhr, status, error) {
                
            }
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

function Export_SystemConfig(argument) {

    var xhr = new XMLHttpRequest();
    // 設置回應類型為二進位檔案
    xhr.responseType = "blob";
    // 當下載完成時執行的函數
    xhr.onload = function() {
        if (xhr.status === 200) {
            // 創建一個 <a> 元素來觸發下載
            var a = document.createElement("a");
            a.href = window.URL.createObjectURL(xhr.response);
            a.download = "tcccon.cfg"; // 下載時的檔案名稱
            a.style.display = "none";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    };

    xhr.open("GET", "?url=Settings/export_sysytem_config", true);
    xhr.send();
}

function Import_SystemConfig() {
    var import_file = document.getElementById("import-file-uploader").files[0];
    var form = new FormData();
    form.append("file", import_file);
    var url = '?url=Settings/Import_Config';
    
    // 語言設置
    var language = getCookie('language') || 'en';
    var text_info, title, confirm_text;
    
    if(language == "zh-cn") {
        text_info = '您確定要導入資料庫檔案嗎？';
        title = '導入配置';
        confirm_text = '您確定要進行此操作嗎？';
    } else if(language == "zh-tw") {
        text_info = '您確定要導入資料庫檔案嗎？';
        title = '導入配置';
        confirm_text = '您確定要進行此操作嗎？';
    } else {
        text_info = 'Are you sure you want to import the database file?';
        title = 'Import Configuration';
        confirm_text = 'Are you sure you want to perform this action?';
    }

    if (import_file) {
        alertify.confirm(confirm_text, function(result) {
            if (result) {
                document.getElementById('spinner').style.display = 'block'; // 顯示加載動畫

                $.ajax({
                    url: url,
                    method: "POST",
                    data: form,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        var responseData = JSON.parse(response);

                        setTimeout(function() {
                            document.getElementById('spinner').style.display = 'none';
                            alertify.alert(responseData.res_type, responseData.res_msg, function() {
                                history.go(0); 
                            });

                            setTimeout(function() {
                                alertify.closeAll(); 
                            }, 3000);
                        }, 1000);
                    },
                    error: function(xhr, status, error) {
                        alertify.alert('Error', 'An error occurred while importing the configuration file.');
                    }
                });
            }
        });
    } else {
        alertify.alert(title, text_info); // 如果 import_file 沒有值，顯示提示訊息
    }
}



// Bacode & Connection Change page
// Bacode & Connection Change page
// 儲存每個表格的當前頁面狀態
const rowsPerPage = 2; // 每頁顯示的行數

// 儲存各表格的頁面狀態
const tableState = {
    "job_table": 0,
    "connection-table": 0
};

// 顯示指定表格頁面的資料
function showPage(tableId, page) {
    const table = document.getElementById(tableId);
    if (!table) return;

    const tableRows = Array.from(table.getElementsByTagName("tr"));
    const totalRows = tableRows.length - 1; // 不算標題行

    const start = page * rowsPerPage + 1; // 因為第0行是標題，實際資料從第1行開始
    const end = Math.min(start + rowsPerPage, totalRows + 1);

    tableRows.forEach((row, index) => {
        // 隱藏或顯示資料行，跳過標題行
        row.style.display = (index >= start && index < end) ? "" : "none";
    });
}

// 切換表格頁面
function changePage(tableId, direction) {
    const table = document.getElementById(tableId);
    if (!table) return;

    const tableRows = table.getElementsByTagName("tr");
    const totalRows = tableRows.length - 1; // 不算標題行

    // 更新表格的頁面狀態
    tableState[tableId] += direction;

    // 確保頁面不會超過總頁數
    const totalPages = Math.ceil(totalRows / rowsPerPage);
    if (tableState[tableId] < 0) tableState[tableId] = 0;
    if (tableState[tableId] >= totalPages) tableState[tableId] = totalPages - 1;

    // 顯示當前頁面
    showPage(tableId, tableState[tableId]);
}

// **當頁面載入完成時，確保表格顯示第一頁的兩行資料**
document.addEventListener("DOMContentLoaded", () => {
    showPage("job_table", 0);
    showPage("connection-table", 0);
});

</script>    


<style>
.divMode.hidden {
  display: none !important;
}
.divMode.active {
  display: block !important;
}


#Controller_Setting,
#System_Setting,
#Barcode_Setting,
#Connect_Setting,
#iDas-Update_Setting {
    background-color: transparent !important; /* ✅ 清除藍色背景 */
}
</style>
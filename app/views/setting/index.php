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
        
            <div id="Controller_Setting" class="divMode hidden"> 
                <div class="col t1" style="padding-left: 3%;font-weight: bold; padding-top: 1%"><?php echo $text['controller_setting'];?></div>
                <div class="row t2">
                    <div class="col-3 t1"><?php echo $text['system_id'];?>:</div>
                    <div class="col-3 t1">
                        <input id="control_id" name="control_id" type="number" max=250 min=1 maxlength="3" value="<?php echo $data['controller_info']['device_id'];?>" class="form-control input-ms"  required>
                    </div>
                </div>    
                <div class="row t2">
                    <div class="col-3 t1"><?php echo $text['system_name'];?>:</div>
                    <div class="col-3 t1">
                        <input id="control_name" name="control_name" maxlength="12" type="text" value="<?php echo $data['controller_info']['device_name'];?>" class="form-control input-ms"  required>
                    </div>
                </div>    
                <div class="row t2">
                    <div class="col-3 t1"><?php echo $text['system_language'];?>:</div>
                    <div class="col t1">
                        <select id="select_language" name="select_language">
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
                    <div class="col t1">
                        <select id="select_torque_unit" name="select_torque_unit">
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
                    <div class="col-3 t1"><?php echo $text['system_batch'];?>:</div>
                    <div class="col t1">
      			      	<div class="col-1 form-check form-check-inline">
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
      			      	<div class="col-1 form-check form-check-inline">
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

            <div id="System_Setting" class="divMode hidden" >
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
                        <form onsubmit="change_datetime();return false;">
                                    <span id="currentSystemTime"></span>
                                    <input type="datetime-local" id="newTime" value="" size="25" required class="w3-submit w3-border" style="margin: 0px 0px 5px; height: 32px">
                                    <input type="submit" value="<?php echo $text['save']; ?>" class="all-btn w3-submit w3-border w3-round-large" style="float: right">
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
                <div class="row t2 align-items-center">  
                    <div class="col-3 t1"><?php echo $text['system_diskfull_warning']; ?>:</div>
                    <div class="col t3">
                        <div class="progress custom-bg" style="height: 25px; width: 40%; border-radius: 10px;">
                            <div id="diskProgressBar" class="progress-bar custom-bar" role="progressbar" 
                                style="border-radius: 10px; text-align: center; color: white;font-weight: bold;" 
                                aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                25%
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row t2">
                    <div class="col-3 t1"><?php echo $text['system_delete_database']; ?></div>
                    <div  class="col t3">
                        <?php 
                            if (!empty($data['history_year_arr'])) {
                                foreach ($data['history_year_arr'] as $key => $val) { ?>
                                    <input type="checkbox"
                                        name="year[]"
                                        value="<?php echo htmlspecialchars($val); ?>"
                                        onclick="onlyOne(this)"
                                        <?php echo ($key === 0) ? 'checked' : ''; ?>>
                                    <?php echo htmlspecialchars($val); ?>&nbsp;&nbsp;
                            <?php }
                            }
                        ?>
                        <!-- 刪除按鈕 -->
                        <button onclick="deleteSelectedFiles()" style="float: right" class="all-btn w3-button w3-border w3-round-large" ><?php echo $text['delete_text']; ?></button>
                    </div>
                </div>

            </div>

            <!-- barcode Setting -->
            <div id="Barcode_Setting" class="divMode hidden" >
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
                                        <th><?php echo $text['system_barcode_mode'];?></th>
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
                                            <td><?php echo $data['barcode_mode'][$v_b['barcode_enable']];?></td>
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
                        <input id="barcode_content" name="barcode_content" style="height: 32px" type="text" value="" maxlength="100" class="form-control" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                
                <div class="row t2">
                    <div class="col-3 t1"><?php echo $text['system_barcode_match_from'];?>:</div>
                    <div class="col-3 t2">
                        <input id="barcode_mask_from" name="barcode_mask_from" style="height: 32px" type="text" value="1" class="form-control">
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
                    
                    <div class="col-3 t1"><?php echo  $text['system_barcode_mode'];?>:</div>
                    <div class="col t2">
                        <select id="barcode_enable" name="barcode_enable"  onchange="toggleBarcodeSeq()" >
                            <option value="-1"><?php echo $text['system_barcode_select'];?></option>
                                <?php
                                foreach ($data['barcode_mode'] as $key_barcode => $value_barcode) {?>
                                    <option value='<?php echo $key_barcode;?>'><?php echo $value_barcode;?></option>
                                <?php }?>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="row t2">
                    <div class="col-3 t1"><?php echo $text['system_barcode_select_job'];?>:</div>
                    <div class="col t2">
                        <select id="barcode_job" name="barcode_job" onchange="fetchSeqList()" >
                            <option value="-1"><?php echo $text['system_barcode_select_job_m'];?></option>
                                <?php
                                foreach ($data['job_list'] as $key => $value) {?>
                                    <option value='<?php echo $value['job_id'];?>'><?php echo $value['job_id']." ".$value['job_name'];?></option>
                                <?php }?>
                                
                        </select>
                    </div>
                </div>

                <div id="barcode_select_seq" style="display:none;">
                    <div class="row t2">
                        <div class="col-3 t1"><?php echo $text['system_barcode_select_seq'];?>:</div>
                        <div class="col t2">
                            <select id="barcode_seq" name="barcode_seq">
                                <option value="-1"><?php echo $text['system_barcode_select_seq_m'];?></option>   
                            </select>
                        </div>
                    </div>
                </div>



                <div style="text-align: center;margin-top: 20px;">
                    <button class="all-btn w3-button w3-border w3-round-large" onclick="update_barcode()" ><?php echo $text['save'];?></button>&nbsp;&nbsp;
                    <button class="all-btn w3-button w3-border w3-round-large" onclick="delete_barcode()" ><?php echo $text['delete_text'];?></button>
                </div>               
            </div>

            <div id="Connect_Setting" class="divMode hidden" >         
                <div class="row t2" style="padding-top: 2%">
                    <div class="col-3 t1">Agent IP:</div>
                    <div class="col t3">
                            <input type="text" name="agent_server_ip" id="agent_server_ip" size="15"  value='<?php echo $data['agent_server_ip'];?>' required class="t3 w3-submit w3-border w3-round">&nbsp;
                            <input type="button" onclick="agent_ip_save()" value="<?php echo $text['save'];?>" class="all-btn w3-submit w3-border w3-round-large">
                    </div>
                </div>
                <div class="row t2">
                    <div class="col-3 t1">Agent Type:</div>
                    <div class="col t3">
                        <form id="agent_type_form" method="post">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="agent_type" id="agent_type_0" value="0" <?php if($data['agent_type'] == 0){ echo "checked";} ?> >
                                <label class="form-check-label" for="agent_type_0">None</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="agent_type" id="agent_type_1" value="1" <?php if($data['agent_type'] == 1){ echo "checked";} ?> >
                                <label class="form-check-label" for="agent_type_1">Client</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="agent_type" id="agent_type_2" value="2" <?php if($data['agent_type'] == 2){ echo "checked";} ?>  required>
                                <label class="form-check-label" for="agent_type_2">Server</label>
                            </div>

                            <input type="button" onclick="agent_type_save()" value="<?php echo $text['save'];?>" class="all-btn w3-submit w3-border w3-round-large">
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

            <div id="iDas-Update_Setting" class="divMode hidden">
               <div class="row t2" style="padding-top: 30px">
                    <div class="col-3 t1">Current iDAS Version:</div>
                    <div class="col-3 t2">
                       
                        <input id="idas_software_version" name="idas_software_version" type="text" value="<?php echo $data['iDas_Vesion'];?>" style="height: 32px" class="form-control" value="" disabled>
                    </div>
                </div>

                <!--<div class="row t2">
                    <div class="col-3 t1">Match Controller Version:</div>
                    <div class="col-3 t2">
                        <input id="match_control_version" name="match_control_version" type="text" value="" style="height: 32px" class="form-control" disabled>
                    </div>
                </div>-->
                
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
        <?php require_once '../app/views/inc/include_spinner.php';?>
    <!-- 加载動畫 ED -->

</div>


<!-- 處理  Diskfull Wraning -->
<script>
    (function () {
        const usedPercent = <?php echo is_numeric($data['disk_usage_percent']) ? $data['disk_usage_percent'] : 0; ?>;
        const bar = document.getElementById('diskProgressBar');

        // 動態設百分比
        bar.style.width = usedPercent + '%';
        bar.textContent = usedPercent + '%';

        // 動態變色
        if (usedPercent < 60) {
            bar.style.backgroundColor = '#4caf50'; // 綠
        } else if (usedPercent < 80) {
            bar.style.backgroundColor = '#ff9900'; // 橘
        } else {
            bar.style.backgroundColor = '#e53935'; // 紅
        }
    })();


    function onlyOne(checkbox) {
        const checkboxes = document.getElementsByName('year[]');
        checkboxes.forEach((item) => {
            if (item !== checkbox) item.checked = false;
        });
    }

    function deleteSelectedFiles(){

        var del_year_id = [];
        var checkboxes = document.querySelectorAll('input[name="year[]"]:checked');
        var language = getCookie('language');

        checkboxes.forEach(function (checkbox) {
            del_year_id.push(checkbox.value);
        });

        if (del_year_id.length > 0) {
            $.ajax({
                url: "?url=Settings/delete_files",
                method: "POST",
                data: { 
                    del_year_id: del_year_id
                },
               success: function(response) {
                    var res = JSON.parse(response);
                    if (res.result === true) {
                        var res = JSON.parse(response);
                        const texts = i18nAlert[language] || i18nAlert['en-us'];

                        if (res.result === true) {
                            showAlert("successTitle", res.res_msg || texts.successMsg, 3);
                        } else {
                            showAlert("errorTitle", res.res_msg || texts.errorMsg, 3);
                        }

                        // 將已勾選的 checkbox 與其旁邊的年份一起從畫面上移除
                        const checkboxes = document.querySelectorAll('input[name="year[]"]:checked');
                        checkboxes.forEach(function (checkbox) {
                            // 移除 checkbox 和其後的文字節點與空白
                            const labelText = checkbox.nextSibling;
                            if (labelText && labelText.nodeType === Node.TEXT_NODE) {
                                labelText.remove();
                            }
                            checkbox.remove();
                        });

                    } else {
                        //alertify.error("刪除失敗：" + (res.msg || "未知錯誤"));
                    }
                },
                error: function(xhr, status, error) {
                    console.error("刪除失敗", error);
                    alertify.error("AJAX 錯誤：" + error);
                }
            });
        } else {
            alert("請先選擇要刪除的年份");
        }
    }



</script>



<script>

$(document).ready(function () {
    const sectionMap = {
        "Controller": "Controller_Setting",
        "System": "System_Setting",
        "Barcode": "Barcode_Setting",
        "Connect": "Connect_Setting",
        "Update": "iDas-Update_Setting"
    };

    const lastSection = sessionStorage.getItem('last_section') || 'Controller_Setting';

    $('.divMode').addClass('hidden').removeClass('active');
    $('#' + lastSection).removeClass('hidden').addClass('active');

    $('.button').removeClass('active');
    if (lastSection === 'Controller_Setting') $('#bnt1').addClass('active');
    if (lastSection === 'System_Setting') $('#bnt2').addClass('active');
    if (lastSection === 'Barcode_Setting') $('#bnt3').addClass('active');
    if (lastSection === 'Connect_Setting') $('#bnt4').addClass('active');
    if (lastSection === 'iDas-Update_Setting') $('#bnt5').addClass('active');

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


function StatusCheck(action) {
  // 狀態圖示（沿用你的 SVG）
  const work_icon = '<svg height="18" width="18" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="M9.001.666A8.336 8.336 0 0 0 .668 8.999c0 4.6 3.733 8.334 8.333 8.334s8.334-3.734 8.334-8.334S13.6.666 9 .666Zm0 15a6.676 6.676 0 0 1-6.666-6.667A6.676 6.676 0 0 1 9 2.333a6.676 6.676 0 0 1 6.667 6.666A6.676 6.676 0 0 1 9 15.666Zm-1.666-4.833L5.168 8.666 4.001 9.833l3.334 3.333L14 6.499l-1.166-1.166-5.5 5.5Z" fill="#1E8E3E" fill-rule="evenodd"></path></svg>';
  const not_work_icon = '<svg height="18" width="18" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="M11.16 5.666 9 7.824 6.843 5.666 5.668 6.841l2.158 2.158-2.158 2.159 1.175 1.175 2.158-2.159 2.159 2.159 1.175-1.175-2.159-2.159 2.159-2.158-1.175-1.175ZM9 .666A8.326 8.326 0 0 0 .668 8.999a8.326 8.326 0 0 0 8.333 8.334 8.326 8.326 0 0 0 8.334-8.334A8.326 8.326 0 0 0 9 .666Zm0 15a6.676 6.676 0 0 1-6.666-6.667A6.676 6.676 0 0 1 9 2.333a6.676 6.676 0 0 1 6.667 6.666A6.676 6.676 0 0 1 9 15.666Z" fill="#D93025" fill-rule="evenodd"></path></svg>';

  // 小工具：把各種回傳（boolean/"true"/"1"/"ok"/"running"...）正規化成布林
  function toBool(v) {
    if (typeof v === 'boolean') return v;
    if (typeof v === 'number')  return v > 0;
    if (typeof v === 'string') {
      const s = v.trim().toLowerCase();
      return ['true','1','ok','on','running','up','yes','y'].includes(s);
    }
    return false;
  }

  // 小工具：安全更新狀態圖示
  function setStatusIcon(elId, isWorking) {
    const el = document.getElementById(elId);
    if (el) el.innerHTML = isWorking ? work_icon : not_work_icon;
  }

  // 依 action 選 URL
  let url = '?url=Admins/AgentTest';
  if (action === 'start') url = '?url=Admins/StartAgent';
  else if (action === 'stop') url = '?url=Admins/CloseAgent';

  $.ajax({
    type: 'POST',
    data: {},
    dataType: 'json',
    url: url,
    beforeSend: function () {
      $('#overlay').removeClass('hidden');
    }
  })
  .done(function (result) {
    setStatusIcon('s_status', toBool(result?.server_status));
    setStatusIcon('c_status', toBool(result?.client_status));
  })
  .fail(function () {
    // 失敗就先標成 not work（也可維持原狀，視需求）
    setStatusIcon('s_status', false);
    setStatusIcon('c_status', false);
  })
  .always(function () {
    $('#overlay').addClass('hidden');
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


                setTimeout(function() {
                    document.getElementById('spinner').style.display = 'none';
                    alertify.alert(responseData.res_type, responseData.res_msg, function() {
                        sessionStorage.setItem('Connect_Setting', 'block');
                        sessionStorage.setItem('Controller_Setting', 'none');
                        //history.go(0); 
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

    var language = getCookie('language') || 'en-us'; 
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

    alertify.confirm(confirm_text, function(result) {
        if (result) {
            document.getElementById('spinner').style.display = 'block';

            $.ajax({
                url: "?url=Settings/edit_password",
                method: "POST",
                data: { 
                    new_password: new_password
                },
                success: function(response) {
                    try {
                        var responseData = JSON.parse(response);

                        console.log(responseData);
                        document.getElementById('spinner').style.display = 'none';

                        alertify.alert(responseData.res_type, responseData.res_msg, function() {
                            sessionStorage.setItem('System_Setting', 'block');
                            sessionStorage.setItem('Controller_Setting', 'none');
                            history.go(0);
                        });

                        setTimeout(function () {
                            alertify.closeAll();
                        }, 3000);
                    } catch (e) {
                        document.getElementById('spinner').style.display = 'none';
                        alertify.alert("Error", "Invalid server response.");
                        console.error("JSON parse error:", e, response);
                    }
                },
                error: function(xhr, status, error) {
                    alertify.alert('Error', 'An error occurred while updating the password.');
                }
            });
        }
    });
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


function OpenButton(ButtonMode) {
    const sectionMap = {
        "Controller": "Controller_Setting",
        "System": "System_Setting",
        "Barcode": "Barcode_Setting",
        "Connect": "Connect_Setting",
        "Update": "iDas-Update_Setting"
    };

    const buttonMap = {
        "Controller": "bnt1",
        "System": "bnt2",
        "Barcode": "bnt3",
        "Connect": "bnt4",
        "Update": "bnt5"
    };

    $('.divMode').addClass('hidden').removeClass('active');
    $('.button').removeClass('active');

    const sectionId = sectionMap[ButtonMode];
    const buttonId = buttonMap[ButtonMode];

    $('#' + sectionId).removeClass('hidden').addClass('active');
    $('#' + buttonId).addClass('active');

    sessionStorage.setItem('last_section', sectionId);
}


function input_check_savebarcode() {

    let rangeLabel = "<?php echo $error_message['OOR']; ?>"; 

    let conditions = [
        { id: 'barcode_content',  pattern: /.*/, min: 1, max: 100, isString: true },
        { id: 'barcode_mask_from', pattern: /^[0-9]+$/, min: 1, max: 54 },
        { id: 'barcode_mask_count', pattern: /^[0-9]+$/, min: 1, max: 100 },

    ];

    let isFormValid = true;

    conditions.forEach(function(input) {
        var element = document.getElementById(input.id);
        if (input.id !== 'barcode_content') {
            element.nextElementSibling.innerHTML = `${rangeLabel} ${input.min} ~ ${input.max}`;
        }

        if (!validateInput(element, input.pattern, input.min, input.max, input.isString)) {
            isFormValid = false;
        }
    });


    return isFormValid;
}


function validateInput(element, pattern, min, max, isString) {
    let value = element.value.trim();
    let isValid = true;

    if (value === "") {
        element.classList.add("is-invalid");
        isValid = false;
    }
    else if (!pattern.test(value)) {
        element.classList.add("is-invalid");
        isValid = false;
    }
    else if (isString) {
        // 檢查字串長度
        if (min !== null && value.length < min) {
            element.classList.add("is-invalid");
            isValid = false;
        }
        else if (max !== null && value.length > max) {
            element.classList.add("is-invalid");
            isValid = false;
        } else {
            element.classList.remove("is-invalid");
        }
    }
    else {
        // 檢查數字大小
        if (min !== null && parseFloat(value) < min) {
            element.classList.add("is-invalid");
            isValid = false;
        }
        else if (max !== null && parseFloat(value) > max) {
            element.classList.add("is-invalid");
            isValid = false;
        } else {
            element.classList.remove("is-invalid");
        }
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

function toggleBarcodeSeq() {
    const barcodeMode = document.getElementById('barcode_enable');  // ✅ 修正為正確 ID
    const barcodeSeq = document.getElementById('barcode_seq');
    const seqContainer = document.getElementById("barcode_select_seq");

    // 防呆：元素不存在時直接返回，避免錯誤
    if (!barcodeMode || !barcodeSeq || !seqContainer) {
        console.warn("One or more elements not found: barcode_enable, barcode_seq, barcode_select_seq");
        return;
    }

    if (barcodeMode.value === '0' || barcodeMode.value === '1') {
        barcodeSeq.disabled = true;
        seqContainer.style.display = 'none';
    } else if (barcodeMode.value === '2') {
        barcodeSeq.disabled = false;
        seqContainer.style.display = 'block';

        // 若已選擇 Job，則自動載入對應的 SEQ
        const jobId = document.getElementById('barcode_job')?.value || '-1';
        if (jobId !== '-1') {
            fetchSeqList();
        } else {
            // 未選 Job，清空 SEQ 並加預設提示
            barcodeSeq.innerHTML = '';
            const defaultOption = document.createElement('option');
            defaultOption.value = "-1";
            defaultOption.textContent = "<?php echo $text['system_barcode_select_seq_m'];?>";
            barcodeSeq.appendChild(defaultOption);
        }
    } else {
        barcodeSeq.disabled = false;
        seqContainer.style.display = 'block';
    }
}



//透過JOBID 取得對應的SEQ
function fetchSeqList() {
    const jobId = document.getElementById('barcode_job').value;
    const barcodeSeq = document.getElementById('barcode_seq');

    // Reset list
    barcodeSeq.innerHTML = '';

    // 預設項目
    const defaultOption = document.createElement('option');
    defaultOption.value = "-1";
    defaultOption.textContent = "Please Select Seq";
    barcodeSeq.appendChild(defaultOption);

    if (jobId === '-1') return;

    $.ajax({
        url: '?url=Settings/GetJobSeq',
        type: 'POST',
        data: { job_id: jobId },
        success: function(response) {
            try {
                const seqList = JSON.parse(response);

                // ✅ 使用正確的屬性名稱：seq_id、seq_name
                if (Array.isArray(seqList)) {
                    seqList.forEach(seq => {
                        const option = document.createElement('option');
                        option.value = seq.seq_id; // 注意這裡用小寫
                        option.textContent = `${seq.seq_id} ${seq.seq_name}`;
                        barcodeSeq.appendChild(option);
                    });
                } else {
                    console.error("Response is not an array:", seqList);
                }
            } catch (e) {
                console.error("JSON parse error:", e, "Raw response:", response);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error occurred:', error);
        }
    });
}


</script>    

<style>
.divMode.hidden {
  display: none !important;
}
.divMode.active {
  display: block !important;
}

#timeWrapper {
  height: 28px; /* 固定容器高度 */
  overflow: hidden;
}

#currentSystemTime {
  font-family: 'Courier New', monospace;
  font-size: 18px;
  white-space: nowrap;
  display: inline-block;
  width: 260px;               /* 固定寬度 */
  text-align: center;
  box-sizing: border-box;     /* 保證寬度不超出 */
  padding: 0 10px;            /* 可選：增加內距讓數字更穩定 */
}
</style>
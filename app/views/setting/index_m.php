
<script>
window.APP = {
    page: 'settings',
    lang: "<?php echo $_SESSION['language'] ?? 'en-us'; ?>"
};
</script>


<div class="container-ms">
    <div class="w3-text-white w3-center">
        <table class="no-border">
            <tr id="header">
                <td width="100%"><h3><?php echo $text['setting'];?></h3></td>
                <td><img src="./img/btn_home.png" style="margin-right: 10px"  onclick="location.href='?url=Dashboards'" ></td>
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
                                <form onsubmit="change_datetime();return false;">
                                    <span id="currentSystemTime"></span>
                                    <input type="datetime-local" id="newTime" value="" size="25" required class="w3-submit w3-border" style="margin: 0px 0px 5px; height: 32px">
                                    <input type="submit" value="<?php echo $text['save']; ?>" class="all-btn w3-submit w3-border w3-round-large" style="float: right">
                                </form>
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
                                        <?php foreach ($data['barcodes'] as $k_b =>$v_b){
                                            $barcodeRaw = (string)($v_b['barcode'] ?? '');
                                            $barcodeDisplay = strtr($barcodeRaw, [
                                                "\r\n" => '<CRLF>',
                                                "\r"   => '<CR>',
                                                "\n"   => '<LF>',
                                                "\t"   => '<TAB>',
                                                chr(29) => '<GS>',
                                                chr(2)  => '<STX>',
                                                chr(3)  => '<ETX>',
                                                chr(27) => '<ESC>',
                                            ]);

                                            $barcodeEsc = htmlspecialchars($barcodeDisplay, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

                                            $barcodeB64Esc = htmlspecialchars(base64_encode($barcodeRaw), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                                            $jobIdEsc   = htmlspecialchars((string)($v_b['barcode_selected_job'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                                            $jobNameEsc = htmlspecialchars((string)($v_b['job_name'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                                            $fromEsc    = htmlspecialchars((string)($v_b['barcode_mask_from'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                                            $countEsc   = htmlspecialchars((string)($v_b['barcode_mask_count'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                                            $enableEsc  = htmlspecialchars((string)($v_b['barcode_enable'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                                            $seqEsc     = htmlspecialchars((string)($v_b['barcode_selected_seq'] ?? '-1'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                                            $modeEsc    = htmlspecialchars((string)($data['barcode_mode'][$v_b['barcode_enable']] ?? $v_b['barcode_enable'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                                        ?>
                                            <tr>
                                                <td style="text-align: center; vertical-align: middle;" >
                                                   <input class="form-check-input" type="checkbox" name="barcode_check" value="<?php echo $jobIdEsc; ?>" data-barcode="<?php echo $barcodeEsc; ?>" data-barcode-b64="<?php echo $barcodeB64Esc; ?>" data-from="<?php echo $fromEsc; ?>" data-count="<?php echo $countEsc; ?>" data-enable="<?php echo $enableEsc; ?>" data-job="<?php echo $jobIdEsc; ?>" data-seq="<?php echo $seqEsc; ?>" style="zoom:1.2">
                                                </td> 
                                                <td><?php echo $jobIdEsc; ?></td>
                                                <td><?php echo $jobNameEsc; ?></td>
                                                <td title="<?php echo $barcodeEsc; ?>" style="white-space: break-spaces; word-break: break-all; overflow-wrap: anywhere; text-align:left;"><?php echo $barcodeEsc; ?></td>
                                                <td><?php echo $fromEsc; ?></td>
                                                <td><?php echo $countEsc; ?></td>
                                                <th><?php echo $modeEsc; ?></th>
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
                                                                 
                    </div>
                </div>    
                                  
            </div>

            <!-- Connection Setting -->
            <div id="Connect_Setting" class="divMode">
                <div class="col t1" style="padding-left: 3%;font-weight: bold; padding-top: 1%"><?php echo $text['system_connect_setting'];?></div>
                <div class="connect-scrollbar" id="style-connection">
                    <div class="connect-force-overflow">
            
                        <div class="col t1"><?php echo $text['system_agent_ip'];?>:</div>
                        <div class="row t2 border-bottom">
                            <div class="col t2">
                                    <input type="text" name="agent_server_ip" id="agent_server_ip" size="15" value='<?php echo $data['agent_server_ip'];?>' required class="t3 w3-submit w3-border w3-round"><br>
                                    <input type="button" value="Save" onclick="agent_ip_save()" class="all-btn w3-submit w3-border w3-round-large" style="float: right">
                            </div>
                        </div>

                        <div class="col t1"><?php echo $text['system_agent_type'];?>:</div>
                        <div class="row t2">
                            <div class="col t2">
                                <form id="agent_type_form"  method="post" style="margin: 3px 0px; margin-left: 5%">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="agent_type" id="agent_type_0" value="0" <?php if($data['agent_type'] == 0){ echo "checked";} ?>  >
                                        <label class="form-check-label" for="agent_type_0"><?php echo $text['system_agent_none'];?></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="agent_type" id="agent_type_1" value="1" <?php if($data['agent_type'] == 1){ echo "checked";} ?>>
                                        <label class="form-check-label" for="agent_type_1"><?php echo $text['system_agent_client'];?></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="agent_type" id="agent_type_2" value="2" <?php if($data['agent_type'] == 2){ echo "checked";} ?> required>
                                        <label class="form-check-label" for="agent_type_2"><?php echo $text['system_agent_server'];?></label>
                                    </div>
                                    <input type="button" value="Save" onclick="set_agent_type()" class="all-btn w3-submit w3-border w3-round-large" style="float: right">
                                </form>    
                            </div>
                            <div class="row">
                                <div class="col t2" style="margin: 3px 0px; margin-left: 5%">
                                    <span><?php echo $text['system_agent_client'];?>:<div id="c_status" style="display:inline-block;"></div></span>
                                    <span><?php echo $text['system_server_status'];?>:<div id="s_status" style="display:inline-block;"></div></span>&nbsp;

                                    <button class="all-btn w3-button w3-border w3-round-large" style="margin: 5px"  onclick="StatusCheck()"  ><?php echo $text['system_agent_check'];?></button>
                                    <button class="all-btn w3-button w3-border w3-round-large" style="margin: 5px;" onclick="StatusCheck('start')"><?php echo $text['system_agent_start'];?></button>
                                    <button class="all-btn w3-button w3-border w3-round-large" style="margin: 5px"  onclick="StatusCheck('stop')" ><?php echo $text['system_agent_stop'];?></button>
                                </div>
                            </div>
                        </div>

                        <hr>
                        
                    </div>
                </div>
            </div>

            <!-- iDas Update Setting -->
            <div id="iDas-Update_Setting" class="divMode">
                <div class="col t1" style="padding-top: 5%"><?php echo $text['system_idas_current_version'];?>:</div>
                <div class="row t2">
                    <div class="col-6 t2" style="margin-left: 5%">
                        <input id="idas_software_version" name="idas_software_version" type="text" value="<?php echo $data['iDas_Vesion'];?>"  style="height: 32px" class="form-control" disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
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


    
    function deleteSelectedFiles() {

        const language = getCookie('language') || 'en-us';
        const texts = (window.i18nAlert && window.i18nAlert[language])
            ? window.i18nAlert[language]
            : window.i18nAlert['en-us'];

        const checked = document.querySelector('input[name="year[]"]:checked');
        if (!checked) {
            popupAlert(texts.noticeTitle, texts.selectYearFirst, texts);
            return;
        }

        const del_year = checked.value;
        const confirmMsg = texts.deleteConfirmMsg.replace('{year}', del_year);

        alertify.confirm(
            texts.deleteConfirmTitle,
            confirmMsg,
            function onOK() {
                // ⭐⭐ 這一行：進入 loading
                setDeleteLoading(true, texts);
                doDeleteYear(del_year, checked, texts);
            },
            function onCancel() {}
        ).set('labels', { ok: texts.ok, cancel: texts.cancel });
    }



    function doDeleteYear(del_year, checkboxEl, texts) {

        $.ajax({
            url: "?url=Settings/delete_files",
            method: "POST",
            data: { del_year: del_year },
            success: function (response) {

                let res;
                try {
                    res = JSON.parse(response);
                } catch (e) {
                    notify("errorTitle", 'Invalid response', texts);
                    setDeleteLoading(false, texts);
                    return;
                }

                if (res.result === true) {

                    notify("successTitle", res.res_msg || texts.successMsg, texts);

                    checkboxEl.closest('.year-item')
                        ? checkboxEl.closest('.year-item').remove()
                        : checkboxEl.remove();

                } else {
                    notify("errorTitle", res.res_msg || texts.errorMsg, texts);
                }

                // ⭐⭐ 不論成功失敗都解除
                setDeleteLoading(false, texts);
            },
            error: function (xhr, status, error) {
                notify("errorTitle", texts.errorMsg, texts);
                setDeleteLoading(false, texts);
            }
        });
    }



    function setDeleteLoading(isLoading, texts = {}) {

        const btn = document.getElementById('btn-delete-year');
        if (!btn) return;

        if (isLoading) {
            btn.dataset.originText = btn.innerHTML;
            btn.innerHTML = `
                <span class="spinner-border spinner-border-sm" style="margin-right:6px;"></span>
                ${texts.deleting || 'Deleting...'}
            `;
            btn.disabled = true;
            btn.classList.add('disabled');
            btn.style.opacity = '0.6';
            btn.style.pointerEvents = 'none';
        } else {
            btn.innerHTML = btn.dataset.originText || btn.innerHTML;
            btn.disabled = false;
            btn.classList.remove('disabled');
            btn.style.opacity = '';
            btn.style.pointerEvents = '';
        }
    }


    function notify(typeKey, msg, texts = {}) {

        const isSuccess = (typeKey === 'successTitle');

        const title = isSuccess
            ? (texts.successTitle || 'Success')
            : (texts.errorTitle || 'Error');

        if (window.alertify && alertify.alert) {
            const dlg = alertify.alert(title, msg);
            dlg.set('labels', { ok: texts.ok || 'OK' });
            return;
        }

        // fallback
        alert(title + "\n" + msg);
    }

</script>


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
    let value = isString ? element.value : element.value.trim();
    let valueForLength = value;
    if (isString && element && element.id === 'barcode_content' && typeof barcodeEditableTextToRaw === 'function') {
        valueForLength = barcodeEditableTextToRaw(value);
    }
    let valueLength = (typeof barcodeRawLength === 'function')
        ? barcodeRawLength(valueForLength)
        : String(valueForLength || '').length;
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
        if (min !== null && valueLength < min) {
            element.classList.add("is-invalid");
            isValid = false;
        }
        else if (max !== null && valueLength > max) {
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


function Export_SystemConfig() {
    var xhr = new XMLHttpRequest();
    xhr.responseType = "blob";

    xhr.onload = function () {
        if (xhr.status === 200) {
            // 產生 YYYYMMDD_HHMMSS
            const d = new Date();
            const pad = (n) => String(n).padStart(2, '0');
            const ts =
                d.getFullYear() +
                pad(d.getMonth() + 1) +
                pad(d.getDate()) + '_' +
                pad(d.getHours()) +
                pad(d.getMinutes()) +
                pad(d.getSeconds());

            const filename = `system_config_${ts}.zip`;

            var a = document.createElement("a");
            a.href = window.URL.createObjectURL(xhr.response);
            a.download = filename; // ✅ 前端顯示名稱帶時間
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

    const fileInput = document.getElementById("import-file-uploader");
    const import_file = fileInput?.files?.[0];
    const spinner = document.getElementById('spinner');

    const language = (getCookie('language') || 'en-us').toLowerCase();

    const I18N = {
        title: {
            'zh-tw': '匯入系統設定',
            'zh-cn': '导入系统配置',
            'en': 'Import System Configuration',
            'en-us': 'Import System Configuration'
        },
        noFile: {
            'zh-tw': '請先選擇要匯入的設定檔（.zip）。',
            'zh-cn': '请先选择要导入的配置文件（.zip）。',
            'en': 'Please select a configuration file (.zip) first.',
            'en-us': 'Please select a configuration file (.zip) first.'
        },
        mustLogout: {
            'zh-tw': '控制器目前為登入狀態，請先於控制器登出後再匯入設定。',
            'zh-cn': '控制器目前为登录状态，请先在控制器登出后再导入配置。',
            'en': 'Controller is logged in. Please log out on the controller before importing.',
            'en-us': 'Controller is logged in. Please log out on the controller before importing.'
        },
        confirmMsg: {
            'zh-tw':
                '此操作將覆蓋目前的系統設定與工具資料，並立即生效。\n\n是否確定要匯入設定檔？',
            'zh-cn':
                '此操作将覆盖当前系统设置与工具资料，并立即生效。\n\n是否确定要导入配置文件？',
            'en':
                'This action will overwrite current system settings and tool data, and will take effect immediately.\n\nAre you sure you want to import?',
            'en-us':
                'This action will overwrite current system settings and tool data, and will take effect immediately.\n\nAre you sure you want to import?'
        },
        serverBad: {
            'zh-tw': '伺服器回應格式異常。',
            'zh-cn': '服务器返回格式异常。',
            'en': 'Invalid server response.',
            'en-us': 'Invalid server response.'
        },
        importFailed: {
            'zh-tw': '匯入失敗，請稍後再試。',
            'zh-cn': '导入失败，请稍后再试。',
            'en': 'Import failed. Please try again later.',
            'en-us': 'Import failed. Please try again later.'
        },
        reloadAsk: {
            'zh-tw': '匯入完成，是否立即重新整理頁面？',
            'zh-cn': '导入完成，是否立即刷新页面？',
            'en': 'Import completed. Reload the page now?',
            'en-us': 'Import completed. Reload the page now?'
        }
    };

    const T = (k) => I18N[k]?.[language] || I18N[k]?.['en-us'] || '';
    const title = T('title');

    /* ===============================
     * 0) 沒選檔案直接擋
     * =============================== */
    if (!import_file) {
        alertify.closeAll();
        alertify.alert(title, T('noFile'));
        return;
    }

    /* ===============================
     * 安全解析登入狀態（不動後端）
     * =============================== */
    function parseLoginStatus(raw) {
        if (typeof raw !== 'string') return 0;
        const cleaned = raw.replace(/\uFEFF/g, '').trim();
        const m = cleaned.match(/([01])\s*$/);
        return m ? parseInt(m[1], 10) : 0;
    }

    /* ===============================
     * 1) 檢查控制器是否登出
     * =============================== */
    $.ajax({
        url: "?url=Settings/get_controller_login",
        method: "POST",
        success: function (response) {

            const loginStatus = parseLoginStatus(response);

            // ★ 關掉所有 dialog & spinner，避免 UI 阻塞
            alertify.closeAll();
            if (spinner) spinner.style.display = 'none';

            if (loginStatus === 1) {
                alertify.alert('Error', T('mustLogout'));
                return;
            }

            /* ===============================
             * 2) 二次確認（最穩 confirm 寫法）
             * =============================== */
            alertify
                .confirm(
                    T('confirmMsg'),
                    function () {
                        // OK
                        startImport();
                    },
                    function () {
                        // Cancel：什麼都不做
                    }
                )
                .set('title', title);
        },
        error: function () {
            alertify.closeAll();
            alertify.alert('Error', T('importFailed'));
        }
    });

    /* ===============================
     * 3) 真正執行匯入
     * =============================== */
    function startImport() {

        const form = new FormData();
        form.append("file", import_file);

        if (spinner) spinner.style.display = 'block';

        $.ajax({
            url: '?url=Settings/Import_Config',
            method: "POST",
            data: form,
            processData: false,
            contentType: false,
            success: function (resp) {
                if (spinner) spinner.style.display = 'none';

                let data;
                try {
                    data = JSON.parse(resp);
                } catch (e) {
                    alertify.closeAll();
                    alertify.alert('Error', T('serverBad'));
                    return;
                }

                alertify.closeAll();

                if (data.res_type === 'Success') {
                    alertify
                        .confirm(
                            (data.res_msg || '') + '\n\n' + T('reloadAsk'),
                            function () {
                                location.reload();
                            },
                            function () {}
                        )
                        .set('title', title);
                } else {
                    alertify.alert(
                        data.res_type || 'Error',
                        data.res_msg || T('importFailed')
                    );
                }
            },
            error: function () {
                if (spinner) spinner.style.display = 'none';
                alertify.closeAll();
                alertify.alert('Error', T('importFailed'));
            }
        });
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


let barcodeSeqLoadToken = 0;
let barcodeSeqXHR = null;

function resetBarcodeSeqOptions() {
    const barcodeSeq = document.getElementById('barcode_seq');
    if (!barcodeSeq) return;

    barcodeSeq.innerHTML = '';

    const defaultOption = document.createElement('option');
    defaultOption.value = "-1";
    defaultOption.textContent = "<?php echo $text['system_barcode_select_seq_m'];?>";
    barcodeSeq.appendChild(defaultOption);
}

function toggleBarcodeSeq(autoFetch) {
    if (typeof autoFetch === 'undefined') autoFetch = true;

    const barcodeMode = document.getElementById('barcode_enable');
    const barcodeSeq = document.getElementById('barcode_seq');
    const seqContainer = document.getElementById("barcode_select_seq");

    // 防呆：元素不存在時直接返回，避免錯誤
    if (!barcodeMode || !barcodeSeq || !seqContainer) {
        console.warn("One or more elements not found: barcode_enable, barcode_seq, barcode_select_seq");
        return;
    }

    const modeValue = String(barcodeMode.value);
    const needSeq = (modeValue !== '-1' && modeValue !== '0' && modeValue !== '1');

    if (!needSeq) {
        barcodeSeq.disabled = true;
        seqContainer.style.display = 'none';
        resetBarcodeSeqOptions();
        return;
    }

    barcodeSeq.disabled = false;
    seqContainer.style.display = 'block';

    const jobId = document.getElementById('barcode_job')?.value || '-1';
    if (jobId === '-1') {
        resetBarcodeSeqOptions();
    } else if (autoFetch && barcodeSeq.options.length <= 1) {
        // Switch Seq / Switch Job + Seq 都需要載入 SEQ 清單。
        // 不再只限定 mode === '2'，避免 Switch Job / Seq 的 key 不是 2 時看不到 SEQ-1。
        fetchSeqList();
    }
}



//透過JOBID 取得對應的SEQ
function fetchSeqList(callback) {
    const barcodeJob = document.getElementById('barcode_job');
    const barcodeSeq = document.getElementById('barcode_seq');
    if (!barcodeJob || !barcodeSeq) return;

    const jobId = barcodeJob.value;
    const requestToken = ++barcodeSeqLoadToken;

    // 取消前一次尚未完成的請求，避免快速切換 Job / Barcode 時舊回應又 append 一次。
    if (barcodeSeqXHR && barcodeSeqXHR.readyState !== 4) {
        barcodeSeqXHR.abort();
    }

    resetBarcodeSeqOptions();

    if (jobId === '-1') {
        if (typeof callback === 'function') callback();
        return;
    }

    barcodeSeqXHR = $.ajax({
        url: '?url=Settings/GetJobSeq',
        type: 'POST',
        data: { job_id: jobId },
        success: function(response) {
            if (requestToken !== barcodeSeqLoadToken) return;

            try {
                const seqList = JSON.parse(response);
                resetBarcodeSeqOptions();

                // ✅ 使用正確的屬性名稱：seq_id、seq_name
                // ✅ 同時以前端 Set 去重，避免重複 AJAX 或 DB 重複資料造成下拉選單重複。
                if (Array.isArray(seqList)) {
                    const addedSeqIds = new Set(['-1']);

                    seqList.forEach(seq => {
                        const seqId = String(seq.seq_id ?? '');
                        if (!seqId || addedSeqIds.has(seqId)) return;

                        addedSeqIds.add(seqId);

                        const option = document.createElement('option');
                        option.value = seqId;
                        option.textContent = `${seqId} ${seq.seq_name ?? ''}`.trim();
                        barcodeSeq.appendChild(option);
                    });
                } else {
                    console.error("Response is not an array:", seqList);
                }

                if (typeof callback === 'function') {
                    callback();
                }
            } catch (e) {
                console.error("JSON parse error:", e, "Raw response:", response);
            }
        },
        error: function(xhr, status, error) {
            if (status === 'abort') return;
            console.error('Error occurred:', error);
        },
        complete: function() {
            if (requestToken === barcodeSeqLoadToken) {
                barcodeSeqXHR = null;
            }
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


#Controller_Setting,
#System_Setting,
#Barcode_Setting,
#Connect_Setting,
#iDas-Update_Setting {
    background-color: transparent !important; /* ✅ 清除藍色背景 */
}
</style>



<script>
/**
 * 全域 i18n（Alert / Confirm 專用）
 * 不依賴後端，不會 undefined
 */
window.i18nAlert = {
    'en-us': {
        deleteConfirmTitle: 'Confirm Delete',
        deleteConfirmMsg:   'Are you sure you want to delete database for year {year}?',
        successMsg:         'Deleted successfully',
        errorMsg:           'Delete failed',
        ok:                 'OK',
        cancel:             'Cancel',
        selectYearFirst: 'Please select a year to delete.',
        deleting: 'Deleting...',
    },
    'zh-tw': {
        deleteConfirmTitle: '確認刪除',
        deleteConfirmMsg:   '確定要刪除 {year} 年的資料嗎？',
        successMsg:         '刪除成功',
        errorMsg:           '刪除失敗',
        ok:                 '確定',
        cancel:             '取消',
        selectYearFirst: '請先選擇要刪除的年份',
        deleting: '刪除中…',
    },
    'zh-cn': {
        deleteConfirmTitle: '确认删除',
        deleteConfirmMsg:   '确定要删除 {year} 年的数据吗？',
        successMsg:         '删除成功',
        errorMsg:           '删除失败',
        ok:                 '确定',
        cancel:             '取消',
        selectYearFirst: '请先选择要删除的年份',
        deleting: '删除中…',
    }
};
</script>

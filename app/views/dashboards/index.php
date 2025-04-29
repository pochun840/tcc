<link rel="stylesheet" href="<?php echo URLROOT; ?>css/tcc_main.css" type="text/css">

<div class="container-ms">
    <div class="main-content">
        <div class="center-content w3-center">
            <div style="text-shadow:3px 5px 0 #444;" class="wrapper w3-center w3-text-red">
                <div class="buttonbox" style=" top: 2%;right: 10px;text-align: right;position: absolute;">
                <input type="button" name="" value="Logout" onclick="logout()" >
                <input type="button" name="" value="简中" data-language="zh-cn" onclick="language_change('zh-cn');"  disabled>
                <input type="button" name="" value="繁中" data-language="zh-tw" onclick="language_change('zh-tw');" disabled>
                <input type="button" name="" value="English" data-language="en-us" onclick="language_change('en-us');">
                </div>

     
                <div style=" margin-top: 3%">   
                    <h1 class="col-ms-3 pt-5"  style="font-size: 50px;"><?php echo TITLE_INDEX; ?></h1>
                    <div style="text-shadow:2px 2px 0 #444; font-size: 30px" class="text w3-center w3-text-yellow"><?php echo SUBTITLE_INDEX; ?></div>
                </div>
            </div>

            <div class="button col pt-5">
                <button class="menu-item blue" id="job_manager" style="font-size: 20px;" onclick="window.location.href='?url=Jobs/index'"><span style="visibility: hidden;">Job</span></button>
                <button class="menu-item green" id="io_input" style="font-size: 20px;"   onclick="window.location.href='?url=Inputs/index'"><span style="visibility: hidden;">IO Input</span></button>
                <button class="menu-item orange" id="io_output" style="font-size: 20px"  onclick="window.location.href='?url=Outputs/index'"><span style="visibility: hidden;">IO Output</span></button>
                <br><br>
                <button class="menu-item purple" id="operation" style="font-size: 20px" onclick="window.location.href='?url=Dashboards/operation'"><span style="visibility: hidden;">Operation</span></button>
                <button class="menu-item lightblue" id="data" style="font-size: 20px" onclick="window.location.href='?url=Data/index'"><span style="visibility: hidden;">Data</span></button>
                <button class="menu-item pink" id="tool" style="font-size: 20px" onclick="window.location.href='?url=Tools/index'"><span style="visibility: hidden;">Tool</span></button>
                <button class="menu-item PaleGreen" id="setting" style="font-size: 20px;" onclick="window.location.href='?url=Settings/index'"><span style="visibility: hidden;">Setting</span></button>
                <br><br>
               
                <?php if($_SESSION['privilege'] == 'admin'){ ?>
                <div>
                    <?php if($data['agent_type'] == '2'){ ?>
                            <button class="menu-item lime" id="agent" style="font-size: 24px" onclick="window.location.href='?url=Agents'"><span style="visibility: hidden;">Agent</span></button>
                    <?php } ?>
                            <button class="menu-item indigo" id="load" style="font-size: 24px" onclick="DB_sync_idas_load('C2D')"><span style="visibility: hidden;">Load</span></button>
                            <button class="menu-item deep-orange" id="save" style="font-size: 24px;" onclick="DB_sync_idas('D2C')"><span style="visibility: hidden;">Save</span></button>
                </div>
                <?php } ?>

            </div>


            <div class='bottom-right'>
                <?php if (!empty($data['idas_online_version'])) echo "Version: {$data['idas_online_version']}"; ?>
            </div>

        </div>
    </div>
</div>

<script>

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

function language_change(language){
    if( language){
        $.ajax({
            url: "?url=Dashboards/change_language",
            method: "POST",
            data:{ 
                language: language

            },
            success: function(response) {
                history.go(0);
            },
            error: function(xhr, status, error) {
                
            }
        });

    }
}

function DB_sync_idas(argument) {
    var language = getCookie('language');
    var titles = {
        "zh-cn": {
            "D2C": '同步iDas的DB到控制器'
        },
        "zh-tw": {
            "D2C": '同步iDas的DB到控制器'
        },
        "default": {
            "D2C": 'Sync iDas DB to controller'
        }
    };

    var messages = {
        "zh-cn": {
            "D2C": '同步后目前控制器上的资料将被覆盖，确认是否同步'
        },
        "zh-tw": {
            "D2C": '同步後目前控制器上的資料將被覆蓋，確認是否同步'
        },
        "default": {
            "D2C": 'After synchronization, the data on the controller will be overwritten'
        }
    };

    var syncingTexts = {
        "zh-cn": "同步中，请稍候...",
        "zh-tw": "同步中，請稍候...",
        "default": "Syncing, please wait..."
    };

    var title = titles[language] ? titles[language][argument] : titles["default"][argument];
    var message = messages[language] ? messages[language][argument] : messages["default"][argument];
    var syncingText = syncingTexts[language] || syncingTexts["default"];

    alertify.confirm(title, message,
        function () {
            // --------這裡設定總秒數---------
            var totalSeconds = 8;  // <-- 想改秒數，這裡直接改
            // ---------------------------------

            // 加上遮罩，禁止點擊
            addOverlay();

            var progress = 0;
            var intervalTime = (totalSeconds * 1000) / 100; // 每次更新間隔(ms)

            var progressDialog = alertify.alert(
                '<div id="syncText">' + syncingText + ' 0%</div>' + 
                '<progress id="syncProgress" value="0" max="100" style="width: 100%; height: 20px;"></progress>'
            );

            var interval = setInterval(function () {
                progress += 1;
                if (progress >= 100) {
                    progress = 100;
                    clearInterval(interval);

                    // 進度條完成，開始送出同步
                    $.ajax({
                        url: "?url=Settings/Sync_check_db",
                        method: "POST",
                        data: { argument: argument },
                        success: function (response) {
                            var responseData = JSON.parse(response);

                            if (responseData.res_type === "Success") {
                                var successDialog = alertify.alert(responseData.res_type, responseData.res_msg);
                                setTimeout(function () {
                                    alertify.dismissAll();
                                    removeOverlay(); // 成功，移除遮罩
                                    history.go(0);    // 成功 reload
                                }, 3000);
                            } else {
                                var failDialog = alertify.alert(responseData.res_type, responseData.res_msg);
                                removeOverlay(); // 失敗，移除遮罩
                                // 不 reload
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("AJAX request failed:", status, error);
                            alertify.alert('Error', '同步失敗，請稍後再試');
                            removeOverlay(); // 失敗，移除遮罩
                        }
                    });
                }
                // 更新進度條
                var progressBar = document.getElementById('syncProgress');
                if (progressBar) {
                    progressBar.value = progress;
                }
                // 更新同步中文字 + 百分比
                var syncText = document.getElementById('syncText');
                if (syncText) {
                    syncText.innerHTML = syncingText + ' ' + progress + '%';
                }
            }, intervalTime);
        },
        function () {
            alertify.error('已取消');
        }
    );

    // --- 以下是加遮罩用 ---
    function addOverlay() {
        var overlay = document.createElement('div');
        overlay.id = 'overlayMask';
        overlay.style.position = 'fixed';
        overlay.style.top = '0';
        overlay.style.left = '0';
        overlay.style.width = '100%';
        overlay.style.height = '100%';
        overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.3)'; // 透明黑
        overlay.style.zIndex = '9999'; // 要比 alertify 還高
        document.body.appendChild(overlay);
    }

    function removeOverlay() {
        var overlay = document.getElementById('overlayMask');
        if (overlay) {
            overlay.remove();
        }
    }
}




function DB_sync_idas_load(argument) {
    var language = getCookie('language');

    var titles = {
        "zh-cn": { "C2D": '同步控制器的DB到iDas' },
        "zh-tw": { "C2D": '同步控制器的DB到iDas' },
        "default": { "C2D": 'Sync controller DB to iDas' }
    };

    var messages = {
        "zh-cn": { "C2D": '同步后目前iDas上的资料将被覆盖，确认是否同步' },
        "zh-tw": { "C2D": '同步後目前iDas上的資料將被覆蓋，確認是否同步' },
        "default": { "C2D": 'After synchronization, the current data on iDas will be overwritten' }
    };

    var syncingTexts = {
        "zh-cn": "同步中，请稍候...",
        "zh-tw": "同步中，請稍候...",
        "default": "Syncing, please wait..."
    };

    var title = titles[language]?.[argument] || titles["default"][argument];
    var message = messages[language]?.[argument] || messages["default"][argument];
    var syncingText = syncingTexts[language] || syncingTexts["default"];

    alertify.confirm(title, message,
        function () {
            // 按下確認後，先檢查控制器登入狀態
            $.ajax({
                url: "?url=Settings/get_controller_login",
                method: "POST",
                success: function (response) {
                    var loginStatus = parseInt(response);

                    if (loginStatus === 0) {
                        // 沒有人登入，開始跑進度條

                        // 加遮罩
                        addOverlay();

                        var totalSeconds = 8;  // 這裡改秒數
                        var progress = 0;
                        var intervalTime = (totalSeconds * 1000) / 100; // 每次更新(ms)

                        var progressDialog = alertify.alert(
                            '<div id="syncText">' + syncingText + ' 0%</div>' +
                            '<progress id="syncProgress" value="0" max="100" style="width: 100%; height: 20px;"></progress>'
                        );

                        var interval = setInterval(function () {
                            progress += 1;
                            if (progress >= 100) {
                                progress = 100;
                                clearInterval(interval);

                                // 進度條跑完，正式送同步 API
                                $.ajax({
                                    url: "?url=Settings/Sync_check_db_load",
                                    method: "POST",
                                    data: { argument: argument },
                                    success: function (response) {
                                        try {
                                            var responseData = JSON.parse(response);

                                            if (responseData.res_type === "Success") {
                                                var successDialog = alertify.alert(responseData.res_type, responseData.res_msg);
                                                setTimeout(function () {
                                                    alertify.dismissAll();
                                                    removeOverlay();
                                                    history.go(0);
                                                }, 3000);
                                            } else {
                                                var failDialog = alertify.alert(responseData.res_type, responseData.res_msg);
                                                removeOverlay();
                                            }
                                        } catch (e) {
                                            console.error("Response JSON parse error:", e, response);
                                            alertify.alert('Error', '回傳資料錯誤');
                                            removeOverlay();
                                        }
                                    },
                                    error: function (xhr, status, error) {
                                        console.error("AJAX request failed:", status, error);
                                        alertify.alert('Error', '同步失敗，請稍後再試');
                                        removeOverlay();
                                    }
                                });
                            }

                            // 更新進度條
                            var progressBar = document.getElementById('syncProgress');
                            if (progressBar) {
                                progressBar.value = progress;
                            }
                            var syncText = document.getElementById('syncText');
                            if (syncText) {
                                syncText.innerHTML = syncingText + ' ' + progress + '%';
                            }
                        }, intervalTime);

                    } else {
                        // 有人登入，不能同步
                        alertify.alert('Error', '目前控制器有人登入，無法進行同步！');
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX check login failed:", status, error);
                    alertify.alert('Error', '無法確認控制器登入狀態');
                }
            });
        },
        function () {
            alertify.error('已取消');
        }
    );

    // --- 加遮罩 function ---
    function addOverlay() {
        var overlay = document.createElement('div');
        overlay.id = 'overlayMask';
        overlay.style.position = 'fixed';
        overlay.style.top = '0';
        overlay.style.left = '0';
        overlay.style.width = '100%';
        overlay.style.height = '100%';
        overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.3)';
        overlay.style.zIndex = '9999';
        document.body.appendChild(overlay);
    }

    function removeOverlay() {
        var overlay = document.getElementById('overlayMask');
        if (overlay) {
            overlay.remove();
        }
    }
}




</script>

<style>
    #job_manager {
        background: url("<?php echo $text['img_job']; ?>") no-repeat;
    }
    #job_manager:hover {
        background: url("<?php echo $text['img_job_hover']; ?>") no-repeat;
    }

    #io_input {
        background: url("<?php echo $text['img_io_input']; ?>") no-repeat;
    }
    #io_input:hover {
        background: url("<?php echo $text['img_io_input_hover']; ?>") no-repeat;
    }

    #io_output {
        background: url("<?php echo $text['img_io_output']; ?>") no-repeat;
    }
    #io_output:hover {
        background: url("<?php echo $text['img_io_output_hover']; ?>") no-repeat;
    }

    #operation {
        background: url("<?php echo $text['img_operation']; ?>") no-repeat;
    }
    #operation:hover {
        background: url("<?php echo $text['img_operation_hover']; ?>") no-repeat;
    }
    
    #data {
        background: url("<?php echo $text['img_data']; ?>") no-repeat;
    }
    #data:hover {
        background: url("<?php echo $text['img_data_hover']; ?>") no-repeat;
    }

    #tool {
        background: url("<?php echo $text['img_tool']; ?>") no-repeat;
    }
    #tool:hover {
        background: url("<?php echo $text['img_tool_hover']; ?>") no-repeat;
    }

    #setting {
        background: url("<?php echo $text['img_setting']; ?>") no-repeat;
    }
    #setting:hover {
        background: url("<?php echo $text['img_setting_hover']; ?>") no-repeat;
    }

    #load {
        background: url("<?php echo $text['img_load']; ?>") no-repeat;
    }
    #load:hover {
        background: url("<?php echo $text['img_load_hover']; ?>") no-repeat;
    }

    #save {
        background: url("<?php echo $text['img_save']; ?>") no-repeat;
    }
    #save:hover {
        background: url("<?php echo $text['img_save_hover']; ?>") no-repeat;
    }

    #agent {
        background: url("<?php echo $text['img_agent']; ?>") no-repeat;
    }
    #agent:hover {
        background: url("<?php echo $text['img_agent_hover']; ?>") no-repeat;
    }


 
    .alertify-custom-dialog .ajs-buttons {
        padding: 5px 10px !important; 
        text-align: center;
    }

    .alertify-custom-dialog .ajs-buttons .ajs-ok, 
    .alertify-custom-dialog .ajs-buttons .ajs-cancel {
        font-size: 12px !important; 
        padding: 5px 10px !important;
        margin: 0 3px !important; 
        font-weight: bold; 
        border-radius: 3px !important; 
    }

    .alertify-custom-dialog .ajs-buttons .ajs-ok {
        background-color: #4CAF50; 
        color: white;
    }

    .alertify-custom-dialog .ajs-buttons .ajs-cancel {
        background-color: #f44336; 
        color: white;
    }

    .alertify-custom-dialog .ajs-buttons .ajs-ok:hover,
    .alertify-custom-dialog .ajs-buttons .ajs-cancel:hover {
        background-color: #45a049;*/
    }

    .ajs-button {
        padding: 5px 10px;
        font-size: 12px;
        transform: translateY(-30px); /* 向上移動 30px */
    }

    .ajs-footer {
        height: 85px; /* 設定區塊的高度為50px */
    }

    .bottom-right {
        position: absolute;
        bottom: 20px;
        right: 20px;
        color: white;
        font-size: 18px;
    }

            
</style>
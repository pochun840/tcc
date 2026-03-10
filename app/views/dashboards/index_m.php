<link rel="stylesheet" href="<?php echo URLROOT; ?>css/tcc_main.css?v=<?php echo ASSET_VERSION; ?>" type="text/css">

<div class="container-ms">
    <div class="main-content">
        <div class="center-content w3-center">
            <div style="text-shadow:3px 5px 0 #444;" class="wrapper w3-center w3-text-red">
                <div class="buttonbox" style="top: 2%; right: 10px; text-align: right; position: absolute;">
                    <input type="button" id="btnLogout" value="Logout" onclick="logout()">
                    <input type="button" value="简中" data-language="zh-cn" onclick="language_change('zh-cn');">
                    <input type="button" value="繁中" data-language="zh-tw" onclick="language_change('zh-tw');">
                    <input type="button" value="English" data-language="en-us" onclick="language_change('en-us');">
                </div>

                <div style="margin-top: 5%">
                    <h1 class="col-ms-3 pt-5" style="font-size: 50px;"><?php echo TITLE_INDEX; ?></h1>
                    <div style="text-shadow:2px 2px 0 #444; font-size: 30px" class="text w3-center w3-text-yellow">
                        <?php echo SUBTITLE_INDEX; ?>
                    </div>
                </div>
            </div>

            <div class="w3-center button-container">
                <button class="menu-item blue" id="job_manager" onclick="window.location.href='?url=Jobs/index'">
                    <span><?php echo $text['job_manager'] ?? 'Job'; ?></span>
                </button>

                <button class="menu-item purple" id="operation" onclick="window.location.href='?url=Dashboards/operation'">
                    <span><?php echo $text['operation'] ?? 'Operation'; ?></span>
                </button>

                <button class="menu-item green" id="io_input" onclick="window.location.href='?url=Inputs/index'">
                    <span><?php echo $text['io_input'] ?? 'IO Input'; ?></span>
                </button>

                <button class="menu-item orange" id="io_output" onclick="window.location.href='?url=Outputs/index'">
                    <span><?php echo $text['io_output'] ?? 'IO Output'; ?></span>
                </button>

                <button class="menu-item lightblue" id="data" onclick="window.location.href='?url=Data/index'">
                    <span><?php echo $text['data'] ?? 'Data'; ?></span>
                </button>

                <button class="menu-item pink" id="tool" onclick="window.location.href='?url=Tools/index'">
                    <span><?php echo $text['tool'] ?? 'Tool'; ?></span>
                </button>

                <button class="menu-item PaleGreen" id="setting" onclick="window.location.href='?url=Settings/index'">
                    <span><?php echo $text['setting'] ?? 'Setting'; ?></span>
                </button>

                <button class="menu-item lime" id="remote" onclick="window.location.href='?url=Remotes'">
                    <span><?php echo $text['remote'] ?? 'Remotes'; ?></span>
                </button>

                <button class="menu-item indigo" id="load" onclick="DB_sync_idas_load('C2D')">
                    <span><?php echo $text['load'] ?? 'Load'; ?></span>
                </button>

                <button class="menu-item deep-orange" id="save" onclick="DB_sync_idas('D2C')">
                    <span><?php echo $text['save'] ?? 'Save'; ?></span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="bottom-right">
    <?php if (!empty($data['idas_online_version'])) echo "Version: {$data['idas_online_version']}"; ?>
</div>

<style>
html, body {
    height: auto !important;
    min-height: 100%;
    overflow-x: hidden !important;
    overflow-y: auto !important;
    -webkit-overflow-scrolling: touch;
}

body {
    margin: 0;
    position: static !important;
}

.container-ms {
    margin: 0 auto;
    padding: 0;
    min-height: 100vh;
    height: auto !important;
    overflow: visible !important;
    position: relative;
}

.main-content {
    display: block !important;
    width: 100%;
    min-height: 100vh;
    overflow: visible !important;
}

.center-content {
    width: 100%;
    padding: 10px 10px 80px;
    box-sizing: border-box;
    overflow: visible !important;
}

.wrapper {
    position: relative;
    overflow: visible !important;
    padding-top: 10px;
}

.button-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 12px;
    margin: 20px;
    padding-bottom: 24px;
}

.menu-item {
    position: relative;
    background-position: center 18px !important;
    background-repeat: no-repeat !important;
    background-size: 64px 64px !important;
    padding-top: 88px;
    padding-bottom: 12px;
    min-height: 120px;
    min-width: 120px;
    text-align: center;
    vertical-align: top;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    border: none;
    cursor: pointer;
}

.menu-item span {
    visibility: visible !important;
    display: block;
    font-size: 16px;
    font-weight: bold;
    color: #fff;
    text-shadow: 1px 1px 2px #000;
    line-height: 1.2;
    padding: 0 4px;
    margin-top: 0;
}

.menu-item:hover {
    transform: scale(1.03);
}

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

#remote {
    background: url("<?php echo $text['img_remote']; ?>") no-repeat;
}
#remote:hover {
    background: url("<?php echo $text['img_remote_hover']; ?>") no-repeat;
}

@media only screen and (max-width: 768px) {
    .buttonbox {
        position: static !important;
        text-align: center !important;
        margin-bottom: 12px;
    }

    .button-container {
        justify-content: space-evenly;
        gap: 10px;
        margin: 5px;
    }

    .menu-item {
        position: relative;

        width:120px;
        height:120px;

        background-position:center center !important;
        background-repeat:no-repeat !important;
        background-size:90px 90px !important;

        border:none;
        cursor:pointer;

        display:flex;
        align-items:center;
        justify-content:center;
    }

    .menu-item span {
        display:none !important;
    }

    .bottom-right {
        position: static !important;
        margin: 12px;
        text-align: right;
        color: white;
        font-size: 16px;
    }
}
</style>

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
    if (language) {
        $.ajax({
            url: "?url=Dashboards/change_language",
            method: "POST",
            data:{ language: language },
            success: function(response) {
                history.go(0);
            },
            error: function(xhr, status, error) {
            }
        });
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

    var errorMessages = {
        "zh-cn": {
            "login": "目前控制器有人登入，无法进行同步！",
            "check": "无法确认控制器登入状态",
            "syncFail": "同步失败，请稍后再试",
            "json": "回传资料错误"
        },
        "zh-tw": {
            "login": "目前控制器有人登入，無法進行同步！",
            "check": "無法確認控制器登入狀態",
            "syncFail": "同步失敗，請稍後再試",
            "json": "回傳資料錯誤"
        },
        "default": {
            "login": "Someone is logged in on the controller. Sync cannot proceed.",
            "check": "Unable to verify controller login status",
            "syncFail": "Synchronization failed. Please try again later.",
            "json": "Invalid response from server"
        }
    };

    var title = titles[language]?.[argument] || titles["default"][argument];
    var message = messages[language]?.[argument] || messages["default"][argument];
    var syncingText = syncingTexts[language] || syncingTexts["default"];
    var errorText = errorMessages[language] || errorMessages["default"];

    alertify.confirm(title, message,
        function () {
            $.ajax({
                url: "?url=Settings/get_controller_login",
                method: "POST",
                success: function (response) {
                    var loginStatus = parseInt(response);
                    if (loginStatus === 0) {
                        addOverlay();
                        createProgressDialog(syncingText);

                        var totalSeconds = 8;
                        var progress = 0;
                        var intervalTime = (totalSeconds * 1000) / 100;

                        var interval = setInterval(function () {
                            progress += 1;
                            if (progress >= 100) {
                                progress = 100;
                                clearInterval(interval);
                                removeProgressDialog();

                                $.ajax({
                                    url: "?url=Settings/Sync_check_db_load",
                                    method: "POST",
                                    data: { argument: argument },
                                    success: function (response) {
                                        try {
                                            var responseData = JSON.parse(response);
                                            showAlertAutoClose(responseData.res_type, responseData.res_msg);
                                            setTimeout(function () {
                                                removeOverlay();
                                                if (responseData.res_type === "Success") history.go(0);
                                            }, 3000);
                                        } catch (e) {
                                            console.error("Response JSON parse error:", e, response);
                                            showAlertAutoClose('Error', errorText.json);
                                            setTimeout(removeOverlay, 3000);
                                        }
                                    },
                                    error: function (xhr, status, error) {
                                        console.error("AJAX request failed:", status, error);
                                        showAlertAutoClose('Error', errorText.syncFail);
                                        setTimeout(removeOverlay, 3000);
                                    }
                                });
                            }

                            var progressBar = document.getElementById('syncProgress');
                            if (progressBar) progressBar.value = progress;

                            var syncText = document.getElementById('syncText');
                            if (syncText) syncText.innerHTML = syncingText + ' ' + progress + '%';
                        }, intervalTime);

                    } else {
                        showAlertAutoClose('Error', errorText.login);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX check login failed:", status, error);
                    showAlertAutoClose('Error', errorText.check);
                }
            });
        },
        function () {
        }
    );

    function showAlertAutoClose(title, message, delay = 3000) {
        const dialog = alertify.alert(title, message);
        dialog.set('onshow', function () {
            setTimeout(() => {
                alertify.dismissAll();
            }, delay);
        });
    }

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
        if (overlay) overlay.remove();
    }

    function createProgressDialog(syncingText) {
        var dialog = document.createElement("div");
        dialog.id = "customProgressDialog";
        dialog.style.position = "fixed";
        dialog.style.top = "30%";
        dialog.style.left = "50%";
        dialog.style.transform = "translate(-50%, -30%)";
        dialog.style.padding = "20px";
        dialog.style.background = "#fff";
        dialog.style.borderRadius = "10px";
        dialog.style.boxShadow = "0 0 10px rgba(0,0,0,0.3)";
        dialog.style.zIndex = "10000";
        dialog.innerHTML =
            '<div id="syncText" style="margin-bottom: 10px; text-align:center;">' + syncingText + ' 0%</div>' +
            '<progress id="syncProgress" value="0" max="100" style="width: 100%; height: 20px;"></progress>';
        document.body.appendChild(dialog);
    }

    function removeProgressDialog() {
        var dialog = document.getElementById("customProgressDialog");
        if (dialog) dialog.remove();
    }
}

function DB_sync_idas(argument) {
    var language = getCookie('language');

    var titles = {
        "zh-cn": { "D2C": '同步 iDas的数据库到控制器' },
        "zh-tw": { "D2C": '同步iDas的DB到控制器' },
        "default": { "D2C": 'Sync iDas DB to controller' }
    };

    var messages = {
        "zh-cn": { "D2C": '同步后目前控制器上的资料将被覆盖，确认是否同步' },
        "zh-tw": { "D2C": '同步後目前控制器上的資料將被覆蓋，確認是否同步' },
        "default": { "D2C": "After synchronization, the controller's current data will be overwritten. Confirm sync" }
    };

    var syncingTexts = {
        "zh-cn": "同步中，请稍候...",
        "zh-tw": "同步中，請稍候...",
        "default": "Syncing, please wait..."
    };

    var errorMessages = {
        "zh-cn": {
            "login": "目前控制器有人登入，无法进行同步！",
            "check": "无法确认控制器登入状态",
            "syncFail": "同步失败，请稍后再试",
            "json": "回传资料错误"
        },
        "zh-tw": {
            "login": "目前控制器有人登入，無法進行同步！",
            "check": "無法確認控制器登入狀態",
            "syncFail": "同步失敗，請稍後再試",
            "json": "回傳資料錯誤"
        },
        "default": {
            "login": "Someone is logged in on the controller. Sync cannot proceed.",
            "check": "Unable to verify controller login status",
            "syncFail": "Synchronization failed. Please try again later.",
            "json": "Invalid response from server"
        }
    };

    var title = titles[language]?.[argument] || titles["default"][argument];
    var message = messages[language]?.[argument] || messages["default"][argument];
    var syncingText = syncingTexts[language] || syncingTexts["default"];
    var errorText = errorMessages[language] || errorMessages["default"];

    alertify.confirm(title, message,
        function () {
            $.ajax({
                url: "?url=Settings/get_controller_login",
                method: "POST",
                success: function (response) {
                    var loginStatus = parseInt(response);
                    if (loginStatus == 1) {
                        showAlertAutoClose('Error', errorText.login);
                        return;
                    }

                    var totalSeconds = 8;
                    var progress = 0;
                    var intervalTime = (totalSeconds * 1000) / 100;

                    addOverlay();
                    createProgressDialog(syncingText);

                    var interval = setInterval(function () {
                        progress += 1;
                        if (progress >= 100) {
                            progress = 100;
                            clearInterval(interval);
                            removeProgressDialog();

                            $.ajax({
                                url: "?url=Settings/Sync_check_db",
                                method: "POST",
                                data: { argument: argument },
                                success: function (response) {
                                    try {
                                        var responseData = JSON.parse(response);
                                        showAlertAutoClose(responseData.res_type, responseData.res_msg);
                                        setTimeout(function () {
                                            removeOverlay();
                                            if (responseData.res_type === "Success") history.go(0);
                                        }, 3000);
                                    } catch (e) {
                                        console.error("Response JSON parse error:", e, response);
                                        showAlertAutoClose('Error', errorText.json);
                                        setTimeout(removeOverlay, 3000);
                                    }
                                },
                                error: function (xhr, status, error) {
                                    console.error("AJAX request failed:", status, error);
                                    showAlertAutoClose('Error', errorText.syncFail);
                                    setTimeout(removeOverlay, 3000);
                                }
                            });
                        }

                        var progressBar = document.getElementById('syncProgress');
                        if (progressBar) progressBar.value = progress;

                        var syncText = document.getElementById('syncText');
                        if (syncText) syncText.innerHTML = syncingText + ' ' + progress + '%';
                    }, intervalTime);
                },
                error: function (xhr, status, error) {
                    console.error("AJAX login check failed:", status, error);
                    showAlertAutoClose('Error', errorText.check);
                }
            });
        },
        function () {
        }
    );

    function showAlertAutoClose(title, message, delay = 3000) {
        const dialog = alertify.alert(title, message);
        dialog.set('onshow', function () {
            setTimeout(() => {
                alertify.dismissAll();
            }, delay);
        });
    }

    function addOverlay() {
        var overlay = document.createElement('div');
        overlay.id = 'overlayMask';
        overlay.style.position = 'fixed';
        overlay.style.top = '0';
        overlay.style.left = '0';
        overlay.style.width = '100%';
        overlay.style.height = '100%';
        overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.3)';
        overlay.style.zIndex = '9998';
        document.body.appendChild(overlay);
    }

    function removeOverlay() {
        var overlay = document.getElementById('overlayMask');
        if (overlay) overlay.remove();
    }

    function createProgressDialog(syncingText) {
        var dialog = document.createElement("div");
        dialog.id = "customProgressDialog";
        dialog.style.position = "fixed";
        dialog.style.top = "30%";
        dialog.style.left = "50%";
        dialog.style.transform = "translate(-50%, -30%)";
        dialog.style.padding = "20px";
        dialog.style.background = "#fff";
        dialog.style.borderRadius = "10px";
        dialog.style.boxShadow = "0 0 10px rgba(0,0,0,0.3)";
        dialog.style.zIndex = "9999";
        dialog.innerHTML =
            '<div id="syncText" style="margin-bottom: 10px; text-align:center;">' + syncingText + ' 0%</div>' +
            '<progress id="syncProgress" value="0" max="100" style="width: 100%; height: 20px;"></progress>';
        document.body.appendChild(dialog);
    }

    function removeProgressDialog() {
        var dialog = document.getElementById("customProgressDialog");
        if (dialog) dialog.remove();
    }
}
</script>

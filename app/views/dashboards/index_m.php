<link rel="stylesheet" href="<?php echo URLROOT; ?>css/tcc_main.css?v=<?php echo ASSET_VERSION; ?>" type="text/css">

<div class="container-ms page-scroll-fix">
    <div class="main-content main-scroll-fix">
        <div class="center-content w3-center content-scroll-fix">
            <div style="text-shadow:3px 5px 0 #444;" class="wrapper w3-center w3-text-red">
                <div class="buttonbox top-tools">
                    <input type="button" name="" value="Logout" onclick="logout()">
                    <input type="button" name="" value="简中" data-language="zh-cn" onclick="language_change('zh-cn');">
                    <input type="button" name="" value="繁中" data-language="zh-tw" onclick="language_change('zh-tw');">
                    <input type="button" name="" value="English" data-language="en-us" onclick="language_change('en-us');">
                </div>

                <div class="title-block">
                    <h1 class="col-ms-3 pt-5 main-title"><?php echo TITLE_INDEX; ?></h1>
                    <div class="sub-title text w3-center w3-text-yellow"><?php echo SUBTITLE_INDEX; ?></div>
                </div>
            </div>

            <div class="w3-center button-container">
                <button class="menu-item blue" id="job_manager" onclick="window.location.href='?url=Jobs/index'"><span style="visibility: hidden;">Job</span></button>
                <button class="menu-item purple" id="operation" onclick="window.location.href='?url=Dashboards/operation'"><span style="visibility: hidden;">Operation</span></button>

                <button class="menu-item green" id="io_input" onclick="window.location.href='?url=Inputs/index'"><span style="visibility: hidden;">IO Input</span></button>
                <button class="menu-item orange" id="io_output" onclick="window.location.href='?url=Outputs/index'"><span style="visibility: hidden;">IO Output</span></button>

                <button class="menu-item lightblue" id="data" onclick="window.location.href='?url=Data/index'"><span style="visibility: hidden;">Data</span></button>
                <button class="menu-item pink" id="tool" onclick="window.location.href='?url=Tools/index'"><span style="visibility: hidden;">Tool</span></button>

                <button class="menu-item PaleGreen" id="setting" onclick="window.location.href='?url=Settings/index'"><span style="visibility: hidden;">Setting</span></button>

                <button class="menu-item lime" id="remote" style="font-size: 24px" onclick="window.location.href='?url=Remotes'"><span style="visibility: hidden;">Remotes</span></button>
                <button class="menu-item indigo" id="load" onclick="DB_sync_idas_load('C2D')"><span style="visibility: hidden;">Load</span></button>
                <button class="menu-item deep-orange" id="save" onclick="DB_sync_idas('D2C')"><span style="visibility: hidden;">Save</span></button>
            </div>

            <div class="bottom-right">
                <?php if (!empty($data['idas_online_version'])) echo "Version: {$data['idas_online_version']}"; ?>
            </div>
        </div>
    </div>
</div>

<style>
/* =========================
   關鍵：不要靠 body 捲，改靠頁面自己的容器捲
========================= */
.page-scroll-fix {
    position: relative !important;
    width: 100% !important;
    height: 100vh !important;
    min-height: 100vh !important;
    overflow: hidden !important;
}

.main-scroll-fix {
    position: relative !important;
    width: 100% !important;
    height: 100% !important;
    min-height: 100% !important;
    overflow: hidden !important;
}

.content-scroll-fix {
    position: relative !important;
    width: 100% !important;
    height: 100% !important;
    min-height: 100% !important;
    overflow-y: auto !important;
    overflow-x: hidden !important;
    -webkit-overflow-scrolling: touch !important;
    box-sizing: border-box !important;
    padding: 10px 10px 90px !important;
}

/* 桌機/手機都避免被外層 fixed 影響 */
.wrapper {
    position: relative !important;
    height: auto !important;
    min-height: 170px !important;
    padding-top: 10px !important;
}

.top-tools {
    z-index: 2;
    margin: 6px 0 10px 0;
}

.title-block {
    margin-top: 5%;
}

.main-title {
    font-size: 50px;
}

.sub-title {
    text-shadow: 2px 2px 0 #444;
    font-size: 30px;
}

.button-container {
    display: flex !important;
    flex-wrap: wrap !important;
    justify-content: center !important;
    gap: 10px !important;
    margin: 20px auto !important;
    padding-bottom: 20px !important;
    overflow: visible !important;
}

.bottom-right {
    margin: 16px 0 12px;
    color: white;
    font-size: 18px;
    text-align: center;
}

/* 背景按鈕 */
#job_manager {
    background: url("<?php echo $text['img_job']; ?>") no-repeat center center;
}
#job_manager:hover {
    background: url("<?php echo $text['img_job_hover']; ?>") no-repeat center center;
}

#io_input {
    background: url("<?php echo $text['img_io_input']; ?>") no-repeat center center;
}
#io_input:hover {
    background: url("<?php echo $text['img_io_input_hover']; ?>") no-repeat center center;
}

#io_output {
    background: url("<?php echo $text['img_io_output']; ?>") no-repeat center center;
}
#io_output:hover {
    background: url("<?php echo $text['img_io_output_hover']; ?>") no-repeat center center;
}

#operation {
    background: url("<?php echo $text['img_operation']; ?>") no-repeat center center;
}
#operation:hover {
    background: url("<?php echo $text['img_operation_hover']; ?>") no-repeat center center;
}

#data {
    background: url("<?php echo $text['img_data']; ?>") no-repeat center center;
}
#data:hover {
    background: url("<?php echo $text['img_data_hover']; ?>") no-repeat center center;
}

#tool {
    background: url("<?php echo $text['img_tool']; ?>") no-repeat center center;
}
#tool:hover {
    background: url("<?php echo $text['img_tool_hover']; ?>") no-repeat center center;
}

#setting {
    background: url("<?php echo $text['img_setting']; ?>") no-repeat center center;
}
#setting:hover {
    background: url("<?php echo $text['img_setting_hover']; ?>") no-repeat center center;
}

#load {
    background: url("<?php echo $text['img_load']; ?>") no-repeat center center;
}
#load:hover {
    background: url("<?php echo $text['img_load_hover']; ?>") no-repeat center center;
}

#save {
    background: url("<?php echo $text['img_save']; ?>") no-repeat center center;
}
#save:hover {
    background: url("<?php echo $text['img_save_hover']; ?>") no-repeat center center;
}

#agent {
    background: url("<?php echo $text['img_agent']; ?>") no-repeat center center;
}
#agent:hover {
    background: url("<?php echo $text['img_agent_hover']; ?>") no-repeat center center;
}

#remote {
    background: url("<?php echo $text['img_remote']; ?>") no-repeat center center;
}
#remote:hover {
    background: url("<?php echo $text['img_remote_hover']; ?>") no-repeat center center;
}

/* 桌機 */
@media only screen and (min-width: 769px) {
    .top-tools {
        position: absolute;
        top: 2%;
        right: 10px;
        text-align: right;
    }

    .bottom-right {
        text-align: right;
        margin-right: 10px;
    }
}

/* 手機 */
@media only screen and (max-width: 768px) {
    .content-scroll-fix {
        padding: 8px 8px 70px !important;
    }

    .top-tools {
        position: relative !important;
        top: auto !important;
        right: auto !important;
        display: flex !important;
        flex-wrap: wrap !important;
        justify-content: center !important;
        gap: 8px !important;
        text-align: center !important;
        margin: 4px 0 14px 0 !important;
    }

    .top-tools input[type="button"] {
        font-size: 14px;
        padding: 6px 10px;
    }

    .title-block {
        margin-top: 8px !important;
    }

    .main-title {
        font-size: 34px !important;
        line-height: 1.2;
        margin: 8px 0 !important;
    }

    .sub-title {
        font-size: 20px !important;
        line-height: 1.3;
    }

    .button-container {
        justify-content: space-evenly !important;
        gap: 10px !important;
        margin: 16px auto !important;
    }

    .bottom-right {
        font-size: 16px;
        text-align: center;
        margin-top: 14px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 移除 alertify header
    var observer = new MutationObserver(function() {
        var headerElements = document.querySelectorAll('.ajs-header');
        headerElements.forEach(function(headerElement) {
            if (headerElement.parentNode) {
                headerElement.parentNode.removeChild(headerElement);
            }
        });
    });
    observer.observe(document.body, { childList: true, subtree: true });

    // 強制讓這頁使用自己的 scroll container
    var page = document.querySelector('.page-scroll-fix');
    var main = document.querySelector('.main-scroll-fix');
    var content = document.querySelector('.content-scroll-fix');

    if (page) {
        page.style.height = window.innerHeight + 'px';
        page.style.overflow = 'hidden';
    }
    if (main) {
        main.style.height = '100%';
        main.style.overflow = 'hidden';
    }
    if (content) {
        content.style.height = '100%';
        content.style.overflowY = 'auto';
        content.style.overflowX = 'hidden';
        content.style.webkitOverflowScrolling = 'touch';
    }

    // 某些平板 WebView 要補 touch action
    document.body.style.touchAction = 'pan-y';
});

window.addEventListener('resize', function() {
    var page = document.querySelector('.page-scroll-fix');
    if (page) {
        page.style.height = window.innerHeight + 'px';
    }
});

function language_change(language){
    if (language){
        $.ajax({
            url: "?url=Dashboards/change_language",
            method: "POST",
            data:{ language: language },
            success: function() {
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

                            var syncTextEl = document.getElementById('syncText');
                            if (syncTextEl) syncTextEl.innerHTML = syncingText + ' ' + progress + '%';
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
        function () {}
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

                        var syncTextEl = document.getElementById('syncText');
                        if (syncTextEl) syncTextEl.innerHTML = syncingText + ' ' + progress + '%';
                    }, intervalTime);
                },
                error: function (xhr, status, error) {
                    console.error("AJAX login check failed:", status, error);
                    showAlertAutoClose('Error', errorText.check);
                }
            });
        },
        function () {}
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
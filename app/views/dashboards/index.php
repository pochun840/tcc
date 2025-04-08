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
            "C2D": '同步控制器的DB到iDas',
            "D2C": '同步iDas的DB到控制器'
        },
        "zh-tw": {
            "C2D": '同步控制器的DB到iDas',
            "D2C": '同步iDas的DB到控制器'
        },
        "default": {
            "C2D": 'Sync controller DB to iDas',
            "D2C": 'Sync iDas DB to controller'
        }
    };

    var messages = {
        "zh-cn": {
            "C2D": '同步后目前iDas上的资料将被覆盖，确认是否同步',
            "D2C": '同步后目前控制器上的资料将被覆盖，确认是否同步'
        },
        "zh-tw": {
            "C2D": '同步後目前iDas上的資料將被覆蓋，確認是否同步',
            "D2C": '同步後目前控制器上的資料將被覆蓋，確認是否同步'
        },
        "default": {
            "C2D": 'After synchronization, the current data on iDas will be overwritten',
            "D2C": 'After synchronization, the data on the controller will be overwritten'
        }
    };

    var title = titles[language] ? titles[language][argument] : titles["default"][argument];
    var message = messages[language] ? messages[language][argument] : messages["default"][argument];

    alertify.confirm(title, message, 
        function () {
            $.ajax({
                url: "?url=Settings/Sync_check_db",
                method: "POST",
                data: { argument: argument },
                success: function (response) {
                    var responseData = JSON.parse(response);
                    alertify.alert(responseData.res_type, responseData.res_msg, function () {
                        setTimeout(function() {
                            alertify.dismissAll(); 
                            history.go(0); 
                        }, 3000);
                    });
                },
                error: function (xhr, status, error) {
                    console.error("AJAX request failed:", status, error);
                }
            });
        }, 
        function () {
            alertify.error('Cancelled');
        }
    )
}


function DB_sync_idas_load(argument){
    var language = getCookie('language');
    var titles = {
        "zh-cn": {
            "C2D": '同步控制器的DB到iDas',
            "D2C": '同步iDas的DB到控制器'
        },
        "zh-tw": {
            "C2D": '同步控制器的DB到iDas',
            "D2C": '同步iDas的DB到控制器'
        },
        "default": {
            "C2D": 'Sync controller DB to iDas',
            "D2C": 'Sync iDas DB to controller'
        }
    };

    var messages = {
        "zh-cn": {
            "C2D": '同步后目前iDas上的资料将被覆盖，确认是否同步',
            "D2C": '同步后目前控制器上的资料将被覆盖，确认是否同步'
        },
        "zh-tw": {
            "C2D": '同步後目前iDas上的資料將被覆蓋，確認是否同步',
            "D2C": '同步後目前控制器上的資料將被覆蓋，確認是否同步'
        },
        "default": {
            "C2D": 'After synchronization, the current data on iDas will be overwritten',
            "D2C": 'After synchronization, the data on the controller will be overwritten'
        }
    };

    var title = titles[language] ? titles[language][argument] : titles["default"][argument];
    var message = messages[language] ? messages[language][argument] : messages["default"][argument];

    // 设置 alertify 默认配置
    alertify.defaults = alertify.defaults || {};
    alertify.defaults.transition = 'zoom'; // 可选的过渡动画效果
    alertify.defaults.glossary.title = title;
    alertify.defaults.glossary.ok = '确认'; // 设置中文的确认按钮文本
    alertify.defaults.glossary.cancel = '取消'; // 设置中文的取消按钮文本

    // 设置中文默认语言的对话框
    if (language === 'zh-cn' || language === 'zh-tw') {
        alertify.defaults.glossary.ok = '确认';
        alertify.defaults.glossary.cancel = '取消';
    } else {
        alertify.defaults.glossary.ok = 'OK';
        alertify.defaults.glossary.cancel = 'Cancel';
    }

    // 自定义 CSS 类来设置对话框的样式
    alertify.defaults.cssClass = 'alertify-custom-dialog'; // 为对话框添加自定义类

    // 调整确认框尺寸
    alertify.confirm(title, message, 
            function () {
                $.ajax({
                    url: "?url=Settings/Sync_check_db_load",
                    method: "POST",
                    data: { argument: argument },
                    success: function (response) {
                        var responseData = JSON.parse(response);
                    
                        // 显示来自服务器的 res_type 和 res_msg
                        alertify.alert(responseData.res_type, responseData.res_msg, function () {
                            setTimeout(function() {
                                alertify.dismissAll();
                                history.go(0); 
                            }, 3000);
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX 请求失败:", status, error);
                    }
                });
            }, 
            function () {
                alertify.error('已取消');
            }
        );
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
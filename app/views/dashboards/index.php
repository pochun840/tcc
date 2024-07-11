<link rel="stylesheet" href="<?php echo URLROOT; ?>css/tcc_main.css" type="text/css">

<div class="container-ms">
    <div class="main-content">
        <div class="center-content w3-center">
            <div style="text-shadow:3px 5px 0 #444;" class="wrapper w3-center w3-text-red">
                <div class="buttonbox" style=" top: 2%;right: 10px;text-align: right;position: absolute;">
                <input type="button" name="" value="简中" data-language="zh-cn" onclick="language_change('zh-cn');" >
                <input type="button" name="" value="繁中" data-language="zh-tw" onclick="language_change('zh-tw');">
                <input type="button" name="" value="English" data-language="en-us" onclick="language_change('en-us');">
                </div>

     
                <div style=" margin-top: 5%">   
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
                            <button class="menu-item lime" id="" style="font-size: 24px" onclick="window.location.href='?url=Agents'">Agent</button>
                    <?php } ?>
                            <button class="menu-item indigo" id="load" style="font-size: 24px" onclick="DB_sync('C2D')"><span style="visibility: hidden;">Load</span></button>
                            <button class="menu-item deep-orange" id="save" style="font-size: 24px;" onclick="DB_sync('D2C')"><span style="visibility: hidden;">Save</span></button>
                </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div>

</body>

</html>
<script>
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
            
</style>
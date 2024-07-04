
<?php require APPROOT . 'views/inc/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>css/tcc_main.css">

<body>
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
                    <h1 class="col" style="font-size: 50px;">KILEWS</h1>
                    <div style="text-shadow:2px 2px 0 #444; font-size: 30px" class="text w3-center w3-text-yellow">iDAS FOR TCC</div>
                </div>
            </div>

            <div class="button col pt-5">
                <button class="menu-item blue" id="job_manager" style="font-size: 20px;" onclick="window.location.href='?url=Jobs/index'">Job</button>
                <button class="menu-item green" id="io_input" style="font-size: 20px;"   onclick="window.location.href='?url=Inputs/index'">IO Input</button>
                <button class="menu-item orange" id="io_output" style="font-size: 20px"  onclick="window.location.href='?url=Outputs/index'">IO Output</button>
                <br><br>
                <button class="menu-item purple" id="operation" style="font-size: 20px" onclick="window.location.href='?url=Dashboards/operation'">Operation</button>
                <button class="menu-item lightblue" id="data" style="font-size: 20px" onclick="window.location.href='?url=Data/index'">Data</button>
                <button class="menu-item pink" id="tool" style="font-size: 20px" onclick="window.location.href='?url=Tools/index'">Tool</button>
                <button class="menu-item PaleGreen" id="setting" style="font-size: 20px;" onclick="window.location.href='?url=Settings/index'">Setting</button>
                <br><br>
                <?php if($_SESSION['privilege'] == 'admin'){?>
                <div>
                <?php if($data['agent_type'] == '2'){ ?>
                <!--<button class="menu-item lime" id="" style="font-size: 24px" onclick="window.location.href='?url=Agents'">Agent</button>-->
                <?php } ?>
                <button class="menu-item blue" id="download" style="font-size: 20px" //onclick="DB_sync('C2D')">Load</button>
                <button class="menu-item green" id="upload" style="font-size: 20px;" //onclick="DB_sync('D2C')">Save</button>
                </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div>

</body>

</html>
<script>
   function language_change(language) {
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
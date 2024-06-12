<?php require APPROOT . 'views/inc/header.php'; ?>
<link rel="stylesheet" type="text/css" href="./css/tcc_operation.css">

<body>
<div class="container-ms">
    <div class="w3-text-white w3-center">
        <table class="no-border">
            <tr id="header">
                <td width="100%">
                    <h3>Operation</h3>
                </td>
                <td>
                    <img src="./img/btn_home.png" style="margin-right: 10px"  onclick="window.location.href = '?url=In';">
                </td>
            </tr>
        </table>
    </div>
    <div class="main-content">
        <div class="center-content">
            <div class="topnav">
                <label style="font-size:18px;color: #fff; padding-left: 1%" for="job_name">Job Name :</label>&nbsp;
                <input type="text" id="Job_Name" name="Job_Name" size="15" maxlength="20" value="1-Sample Job 1" disabled>

                <label style="font-size:18px;color: #fff; padding-left: 2%" for="seq_name">Job Name :</label>&nbsp;
                <input type="text" id="Seq_Name" name="Seq_Name" size="15" maxlength="20" value="3-Sample Seq 3" disabled>

                <label style="font-size:18px;color: #fff; padding-left: 2%" for="screw">Screw :</label>&nbsp;
                <input type="text" id="Screws" name="Screws" size="4" maxlength="20" value="2/5" disabled>
            </div>
            
            <div class="operation-setting">
                <div class="column">
                    <div class="item-target-torque w3-display-container">
                        <div class="w3-display-topmiddle w3-border-top w3-border-bottom w3-border-red">TORQUE (N-m)</div>
                        <div id="Target_Torque" class="w3-display-middle" style="font-size: 6vmin">0.011</div>
                    </div>
                    <div class="item-result w3-display-container">
                        <div class="w3-display-topmiddle w3-border-top w3-border-bottom w3-border-black">RESULT</div>
                        <div id="Torque_Result" class="w3-display-middle" style="font-size: 6vmin">OK-JOB</div>            
                    </div>
                </div>
                <div class="column">
                    <div class="item-targer-angle w3-display-container">
                        <div class="w3-display-topmiddle w3-border-top w3-border-bottom w3-border-red">ANGLE (Deg)</div>
                        <div id="Target_Angle" class="w3-display-middle" style="font-size: 6vmin">195</div>                        
                    </div>
                    <div class="item-message w3-display-container">
                        <div class="w3-display-topmiddle w3-border-top w3-border-bottom w3-border-red">MESSAGE</div>
                        <div id="Message" class="w3-display-middle" style="font-size: 28px">No Error</div>                                    
                    </div>
                </div>
            </div>
            
            <div class="operation-setting">
                <div class="column">
                    <div class="item-chart">
                        <div class="button-chart">
                            <button class="btn-chart active" id="torque_time" type="button" onclick="changeBackgroundColor(this)">Torque Time</button>
                            <button class="btn-chart" id="angle_time" type="button" onclick="changeBackgroundColor(this)">Angle Time</button>
                            <button class="btn-chart" id="rpm_time" type="button" onclick="changeBackgroundColor(this)">RPM Time</button>
                            <button class="btn-chart" id="torque_angle" type="button" onclick="changeBackgroundColor(this)">Torque Angle</button>
                        </div>
                        <div id="graph" class="display-chart">
                            <div id="chart" style="width: 100%; height: 100%">
                                <img src="img/chart.png" alt="" style="width: 100%; height: 100%">                            
                            </div>
                        </div>      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
// Button Home


// change button background coler
function changeBackgroundColor(button) {
    var buttons = document.getElementsByClassName('btn-chart');
    for (var i = 0; i < buttons.length; i++) {
        buttons[i].classList.remove('active');
    }
    button.classList.add('active');
}
</script>

</body>

</html>
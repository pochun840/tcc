<div class="container-ms">
    <div class="w3-text-white w3-center">
        <table>
            <tr id="header">
                <td width="100%">
                    <h3><?php echo $text['tool']; ?></h3>
                </td>
                <td>
                    <button class="w3-btn w3-round-large" style="height:50px;padding: 0" onclick="window.location.href='./?url=Dashboards'"> <img src="../public/img/btn_home.png"></button>
                </td>
            </tr>
        </table>
    </div>

    <div class="main-content">
        <div class="center-content">
            <div class="container" style="padding: 10px;border-radius: 5px ;box-shadow: 0px 3px 8px 0px rgba(0, 0, 0, 0.2);">
                <div id="Tool_Setting">
                        <h3 style="margin: 5px 3px 10px"><b><?php echo $text['tool_info'];?></b></h3>
                
                        <div class="row border-bottom">
                            <div class="col-4 t1"><?php echo $text['maintain_counts'];?>:</div>
                            <div class="col t1"><?php echo $data['tools_info']['tool_maintain_counts'];?></div>&nbsp;   
                        </div>

                        <div class="row border-bottom">
                            <div class="col-4 t1"><?php echo $text['total_counts'];?>:</div>
                            <div class="col t1"><?php echo $data['tools_info']['tool_total_counts'];?></div>
                        </div>

                        <div class="row border-bottom">
                            <div class="col-4 t1"><?php echo $text['Torque'];?>:</div>
                            <div class="col t1"><?php echo  $data['tools_info']['tool_mintorque'];?>/<?php echo  $data['tools_info']['tool_maxtorque'];?></div>
                        </div>

                        <div class="row border-bottom">
                            <div class="col-4 t1"><?php echo $text['rpm'];?>:</div>
                            <div class="col t1"><?php echo $data['tools_info']['tool_maxrpm'];?>/<?php echo $data['tools_info']['tool_minrpm'];?></div>
                        </div>

                        <div class="row border-bottom">
                            <div class="col-4 t1"><?php echo $text['calibration_value'];?>:</div>
                            <div class="col t1"><?php echo $data['tools_info']['tool_calibration'];?></div>
                        </div>

                        <div class="row border-bottom">
                            <div class="col-4 t1"><?php echo $text['calibration_time'];?>:</div>
                            <div class="col t1"><?php echo $data['tools_info']['tool_calib_time'];?></div>
                        </div>

                        <hr>

                        <h3 style="margin: 5px 3px 10px"><b><?php echo $text['controller_info'];?></b></h3>
                        <div class="row border-bottom">
                            <div class="col-4 t1"><?php echo $text['controller_sn'];?>:</div>
                            <div class="col t1"><?php echo $data['controller_info']['device_sn'];?></div>
                        </div>
                        <div class="row border-bottom">
                            <div class="col-4 t1"><?php echo $text['controller_version'];?>:</div>
                            <div class="col t1"><?php echo $data['controller_info']['device_version'];?></div>
                        </div>
                        <div class="row border-bottom">
                            <div class="col-4 t1"><?php echo $text['mcb_version'];?>:</div>
                            <div class="col t1"><?php echo $data['controller_info']['device_mcbswversion'];?></div>
                        </div>
                        <div class="row border-bottom">
                            <div class="col-4 t1"><?php echo $text['image_version'];?>:</div>
                            <div class="col t1"><?php echo $data['controller_info']['image_version'];?></div>
                        </div>
                        <div class="row border-bottom">
                            <div class="col-4 t1"><?php echo $text['network_ip'];?>:</div>
                            <div class="col t1"><?php echo $data['IP']; ?></div>
                        </div>
                        <div class="row border-bottom">
                            <div class="col-4 t1"><?php echo $text['Mac'];?>:</div>
                            <div class="col t1"><?php echo $data['MAC']; ?></div>
                        </div>

                        <hr>

                        <div class="row border-bottom" style="display: flex; justify-content: center; align-items: center; height: 150px;">
                            <img 
                                    src="img/qr_code_tcc.jpeg" 
                                    alt="QR Code" 
                                    style="width: 150px; height: 150px; cursor: pointer;" 
                                    onclick="window.open('https://www.kilews.com.tw/Upload/download/download_202406141109081.pdf', '_blank');"
                            >
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
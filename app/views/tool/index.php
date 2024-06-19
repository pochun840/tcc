<?php require APPROOT . 'views/inc/header.php'; ?>
<body>
<div class="container-ms">
    <div class="w3-text-white w3-center">
        <table class="no-border">
            <tr id="header">
                <td width="100%">
                    <h3>Tool</h3>
                </td>
                <td>
                <img src="./img/btn_home.png" style="margin-right: 10px" onclick="window.location.href = '?url=In';">
                </td>
            </tr>
        </table>
    </div>

    <div class="main-content">
        <div class="center-content">
            <div class="tool-setting">
                <div class="scrollbar" id="style-tool">
                    <div class="force-overflow">
                        <div class="col t1" style="padding-left: 3%;font-weight: bold; padding-top: 1%">Tool Information</div>
                        <div class="row t2">
                            <div class="col-4 t1">Tool Type:</div>
                            <div class="col t2">
                                <div</div>
                            </div>
                        </div>    
                        <div class="row t2">
                            <div class="col-4 t1">Tool SN:</div>
                            <div class="col t2">
                                <div></div>
                            </div>
                        </div> 
                        <div class="row t2">
                            <div class="col-4 t1">Calibration Time:</div>
                            <div class="col t2">
                                <div></div>
                            </div>
                        </div>    
                        <div class="row t2">
                            <div class="col-4 t1">Total Count:</div>
                            <div class="col t2">
                                <div></div>
                            </div>
                        </div>    
                        <div class="row t2">
                            <div class="col-4 t1">RPM:</div>
                            <div class="col t2">
                                <div></div>
                            </div>
                        </div>    
                        <div class="row t2">
                            <div class="col-4 t1">Torque (N-m):</div>
                            <div class="col t2">
                                <div></div>
                            </div>
                        </div>    
                        <div class="row t2">    
                            <div class="col-4 t1">F/W Version:</div>
                            <div class="col t2">
                                <div></div>
                            </div>
                        </div>    
                        
                        <hr>
                        
                        <div class="col t1" style="padding-left: 3%;font-weight: bold">Controller</div>            
                        <div class="row t2">
                            <div class="col-4 t1">Controller SN:</div>
                            <div class="col t2">
                                <div></div>
                            </div>
                        </div>    
                        <div class="row t2">
                            <div class="col-4 t1">Controller Version:</div>
                            <div class="col t2">
                                <div></div>
                            </div>
                        </div>    
                        <div class="row t2">
                            <div class="col-4 t1">MCB Version:</div>
                            <div class="col t2">
                                <div></div>
                            </div>
                        </div>    
                        <div class="row t2">
                            <div class="col-4 t1">Image Version:</div>
                            <div class="col t2">
                                <div></div>
                            </div>
                        </div>    
                        <div class="row t2">
                            <div class="col-4 t1">Network IP:</div>
                            <div class="col t2">
                                <div><?php echo $data['IP'];?></div>
                            </div>
                        </div>    
                        <div class="row t2">
                            <div class="col-4 t1">Mac:</div>
                            <div class="col t2">
                                <div><?php echo $data['MAC'];?></div>
                            </div>
                        </div>    
                    </div>
                </div>              
            </div>
        </div>
    </div>
</div>    
   
</body>

</html>
<?php require APPROOT . 'views/inc/header.php'; ?>
<link rel="stylesheet" type="text/css" href="./css/tcc_operation.css">

<body>
<?php 



?>
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

                <label style="font-size:18px;color: #fff; padding-left: 2%" for="seq_name">Sequence Name :</label>&nbsp;
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
                            <button class="btn-chart active" id="torque_time" type="button" onclick="chart_type('C1')">Torque Time</button>
                            <button class="btn-chart" id="angle_time" type="button" onclick="chart_type('C2')">Angle Time</button>
                            <button class="btn-chart" id="rpm_time" type="button" onclick="chart_type('C3')">RPM Time</button>
                            <button class="btn-chart" id="torque_angle" type="button" onclick="chart_type('C4')">Torque Angle</button>
                        </div>
                        <div id="graph" class="display-chart">
                            <div id="chart" style="width: 100%; height: 100%">    
                            <div id="chart" style="width: 800px;height:600px;"></div>                
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

function chart_type(argument){

    //f()

    
}


var myChart = echarts.init(document.getElementById('chart'));
var x_data_val = <?php echo  $data['chart_info']['x_val']; ?>;
var y_data_val = <?php echo  $data['chart_info']['y_val']; ?>;
var x_title    = '<?php echo addslashes($data['echart_name'][1]); ?>';
var y_title    = '<?php echo addslashes($data['echart_name'][0]); ?>';

var option = {
    title: {
        text: ''
    },
    tooltip: {
        trigger: 'axis',
        position: function (pt) {
            return [pt[0], '10%'];
        },
        formatter: function (params) {
            var state = '<span style="color: red;">' + y_title + '</span>';
            var value = '<span style="color: red;">' + params[0].value + '</span>';
            return state + ': ' + value; 
        },
        
    },
    xAxis:{
        type: 'category',
        boundaryGap: false,
        name: x_title,
        data: x_data_val
    },
    yAxis: {
        type: 'value',
        name: y_title,
        boundaryGap: [0, '100%']
    },
    dataZoom: generateDataZoom(),
    series: [
            {
                name:'',
                type:'line',
                symbol: 'none',
                sampling: 'average',
                
                itemStyle: {
                    normal: {
                        color: 'rgb(255,0,0)'
                    }
                },
                areaStyle: {
                    normal: {
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 0, [{
                            offset: 0,
                            color: 'rgb(255,255,255)'
                        }, {
                            offset: 0,
                            color: 'rgb(255,255,255)'
                        }])
                    }
                },
                lineStyle: {width: 0.75},
                data: y_data_val
            }
        ]
};

myChart.setOption(option);

function generateDataZoom() {
    return [
        {
            type: 'inside',
            start: 0,
            end: 100
        },
        {
            show: false,
            type: 'slider',
            start: 0,
            end: 100,
            handleIcon: 'M10.7,11.9v-1.3H9.3v1.3c-4.9,0.3-8.8,4.4-8.8,9.4c0,5,3.9,9.1,8.8,9.4v1.3h1.3v-1.3c4.9-0.3,8.8-4.4,8.8-9.4C19.5,16.3,15.6,12.2,10.7,11.9z M13.3,24.4H6.7V23h6.6V24.4z M13.3,19.6H6.7v-1.4h6.6V19.6z',
            handleSize: '80%',
            handleStyle: {
                color: '#fff',
                shadowBlur: 3,
                shadowColor: 'rgba(0, 0, 0, 0)',
                shadowOffsetX: 0,
                shadowOffsetY: 0
            }
        }
    ];
}
</script>

</body>

</html>
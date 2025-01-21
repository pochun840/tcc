<?php require APPROOT . 'views/inc/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>css/tcc_operation_m.css" type="text/css">

<style type="text/css">
    @font-face
    {
      font-family: 'LED字型';
      src: url('<?php echo URLROOT; ?>font/Petitinho.ttf') format('truetype');
    }
    @font-face
    {
      font-family: 'fa-solid-900';
      src: url('<?php echo URLROOT; ?>font/fa-solid-900.woff2') format('truetype');
    }
    .led-number
    {
/*      font-family: 'LED字型', sans-serif;*/
    }

    /* 在手機旋轉時套用的 CSS 樣式 */
    @media screen and (orientation: landscape)  {
      /* 手機為橫向旋轉狀態時的 CSS */
      /* 在此設定您的 CSS 樣式 */
        .panel-container
        {
            height: 65%!important;margin: 3px;
        }

        .w3-container, .w3-panel
        {
            padding: 0.01em 5px;
        }

        .message-font
        {
            font-size: 4vmin!important;
        }

        .w3-panel
        {
            margin-top: 5px!important;
        }

        .table-font
        {
            font-size: 3vmin!important;
        }


        .p1{ width:33%; height:23%; background-color: #CDC5BF; }
        .p2{ width:33%; height:23%; background-color: #CDC9C9; }
        .p3{ width:33%; height:23%; background-color: #CDC9C9;position: absolute; left: 33.5%; top: 0%; }
        .p4{ width:50%; height:23%; background-color: #CDC5BF;position: absolute; right: 0; top: 26%; display:none; }
        .p5{ margin: 0px;padding: 0;position: absolute; left: 17%; top: 26%; width:83%; height: 100% }
        .p6{ margin: 0px;padding: 0;position: absolute; left: 0; top: 26%; width: 20%; text-align:center; }
        .nav-item{margin-bottom: 5px;}

        #Target_Torque{top: 70%!important;}
        #Torque_Result{top: 70%!important;}
        #Target_Angle{top: 70%!important;}
        .i-btn{display: block!important;}
    }

    .panel-container
    {
        height: 83%;margin: 3px;
    }

    @media screen and (orientation: portrait) 
    {
      /* 手機為直向旋轉狀態時的 CSS */
      /* 在此設定您的 CSS 樣式 */
     /* .panel-container{
        height: 80%;margin: 5px;
       }*/
       .message-font
       {
            font-size: 4vmin!important;
        }
        .table-font
        {
            font-size: 3vmin!important;
        }

        .p1{ width:48%; height:20%; background-color: #CDC5BF;}
        .p2{ width:50%; height:20%; background-color: #CDC9C9;}
        .p3{ width:48%; height:20%; background-color: #CDC9C9;position: absolute; left: 0; top: 21%;}
        .p4{ width:50%; height:20%; background-color: #CDC5BF;position: absolute; right: 0; top: 21%;}
        .p5{ margin: 0px;padding: 0;position: absolute; left:0; top: 42%;}
        .p6{ margin: 0px;padding: 0;position: absolute; left: 0; top: 92%; text-align:center; }
        .nav-item{margin-bottom: 5px; width: 20%;}

    }


</style>

<body>
<div class="container-ms">
    <div class="w3-text-white w3-center">
        <table class="no-border">
            <tr id="header">
                <td width="100%">
                    <h3><?php echo $text['operation_result'];?></h3>
                </td>
                <td>
                    <img id="back_home" src="./img/btn_home.png" style="margin-right: 10px"  onclick="window.location.href = '?url=In';">
                </td>
            </tr>
        </table>
    </div>
    <div class="main-content">
        <div class="center-content">
            <div class="topnav">
                <table class="w3-table w3-dark-grey table-font">
                    <tr>
                        <td>
                            <label style="color: #FFF; font-weight: bold" for="Job_Name"><?php echo $text['job']; ?>:</label>
                            <input style=" color: #000" type="text" id="Job_Name" name="Job_Name" size="10" maxlength="15" value="" disabled>
                        </td>
                        <td>
                            <label style="color: #FFF; font-weight: bold" for="Seq_Name"><?php echo $text['sequence']; ?>:</label>
                            <input style=" color: #000" type="text" id="Seq_Name" name="Seq_Name" size="10" maxlength="15" value="" disabled>
                        </td>
                        <td>
                            <label style="color: #FFF; font-weight: bold" for="Screws"><?php echo $text['screws']; ?>:</label>
                            <input style=" color: #000; text-align: center" type="text" id="Screws" name="Screws" size="4" maxlength="5" value="" disabled>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="operation-setting">
                <div class="column">
                    <div class="item-target-torque w3-display-container">
                        <div class="w3-display-topmiddle w3-border-top w3-border-bottom w3-border-red"><?php echo $text['final_torque'] ;?>(<?php echo $text['N.m'];?>)</div>
                        <div id="Target_Torque" class="w3-display-middle" style="font-size: 6vmin; margin: 5px 0;"></div>
                    </div>
                    <div class="item-result w3-display-container">
                        <div class="w3-display-topmiddle w3-border-top w3-border-bottom w3-border-black"><?php echo $text['final_result'];?></div>
                        <div id="Torque_Result" class="w3-display-middle" style="font-size: 6vmin; margin: 5px 0"></div>            
                    </div>
                </div>
                <div class="column">
                    <div class="item-targer-angle w3-display-container">
                        <div class="w3-display-topmiddle w3-border-top w3-border-bottom w3-border-red"><?php echo $text['final_angle'];?></div>
                        <div id="Target_Angle" class="w3-display-middle" style="font-size: 6vmin; margin: 5px 0"></div>                        
                    </div>
                    <div class="item-message w3-display-container">
                        <div class="w3-display-topmiddle w3-border-top w3-border-bottom w3-border-red"><?php echo $text['final_message'];?></div>
                        <div id="Message" class="w3-display-middle" style="font-size: 5vmin; margin: 5px 0"></div>                                    
                    </div>
                </div>
            </div>
            <div class="chart-setting">
                <div class="button-chart">
                    <?php foreach($data['chart_menu_arr'] as $k_menu =>$v_menu){?>
                            <button type="button" <?php if($data['chart_mode'] == $k_menu){ echo $class ='class="btn-chart active"';}else { echo $class ='class="btn-chart"'; }?>   id= '<?php echo $v_menu['id'];?>' onclick="chart_type('<?php echo $v_menu['id'];?>')" ><?php echo $text[$v_menu['name']];?></button>
                    <?php }?>
                </div>
                <div id="graph" class="display-chart">
                    <div id="chart" style="max-width: 100%; height: 290px;"></div>
                </div>                         
            </div>
        </div>
    </div>
</div>


<script>
function chart_type(argument){

    var currentUrl = window.location.href;

    // 處理button的class 
    var buttons = document.getElementsByClassName("btn-chart");
    for (var i = 0; i < buttons.length; i++) {
        buttons[i].classList.remove("active");
    }
    var activeButton = document.getElementById(argument);
    activeButton.classList.add("active");

    var chartIndex = currentUrl.indexOf('chart=');

    var chart;

    if(argument == "torque_time"){
        chart = 1;
    }

    if(argument == "angle_time"){
        chart = 2;
    }

    if(argument == "rpm_time"){
        chart = 3;
    }

    if(argument == "torque_angle"){
        chart = 4;
    }

    var nextinfo_url;

    if (chartIndex !== -1) {
        var nextChartValue = 'chart=' + chart;
        nextinfo_url = currentUrl.substring(0, chartIndex) + nextChartValue;
    } else {
        var separator = currentUrl.indexOf('?') !== -1 ? '&' : '?';
        nextinfo_url = currentUrl + separator + 'chart=' + chart;
    }

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            window.location.assign(nextinfo_url);
        }
    };
    xhttp.open("GET", nextinfo_url, true);
    xhttp.send();
}
var language = getCookie('language');
var myChart = echarts.init(document.getElementById('chart'));
var x_data_val = <?php echo  $data['chart_info']['x_val']; ?>;
var y_data_val = <?php echo  $data['chart_info']['y_val']; ?>;
var x_title    = '<?php echo addslashes($data['echart_name'][1]); ?>';
var y_title    = '<?php echo addslashes($data['echart_name'][0]); ?>';

if(language =="zh-tw"){
    if(x_title =="Time(MS)"){
        x_title ="時間";
    }

    if(x_title =="Angle"){
        x_title ="角度";
    }
    if(x_title =="Torque"){
        x_title ="扭力";
    }

    if(y_title =="Angle"){
        y_title ="角度";
    }
    if(y_title =="Torque"){
        y_title ="扭力";
    }

    if(y_title =="RPM"){
        y_title ="轉速";
    }

}

if(language =="zh-cn"){
    if(x_title =="Time(MS)"){
        x_title ="时间";
    }

    if(x_title =="Angle"){
        x_title ="角度";
    }
    if(x_title =="Torque"){
        x_title ="扭力";
    }

    if(y_title =="Angle"){
        y_title ="角度";
    }
    if(y_title =="Torque"){
        y_title ="扭力";
    }

    if(y_title =="RPM"){
        y_title ="转速";
    }

}



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
                        color: new echarts.graphic.LinearGradient(0, 0, 1, 0, [{
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
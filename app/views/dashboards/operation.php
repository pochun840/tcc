<?php require APPROOT . 'views/inc/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>css/tcc_operation.css" type="text/css">

<body>
<div class="container-ms">
    <div class="w3-text-white w3-center">
        <table class="no-border">
            <tr id="header">
                <td width="100%"><h3><?php echo $text['operation_result'];?></h3></td>
                <td><img src="./img/btn_home.png" style="margin-right: 10px" onclick="back()"></td>
            </tr>
        </table>
    </div>
    <div class="main-content">
        <div class="center-content">
            <div class="topnav">
                <label style="font-size:18px;color: #fff; padding-left: 1%" for="job_name"><?php echo $text['job_name'];?> :</label>&nbsp;
                <input type="text" id="job_name" name="job_name" size="15" maxlength="20" disabled>
                <input type="hidden" id="system_sn" name="system_sn" size="15" disabled>

                <label style="font-size:18px;color: #fff; padding-left: 2%" for="seq_name"><?php echo $text['seq_name'];?> :</label>&nbsp;
                <input type="text" id="seq_name" name="seq_name" size="15" maxlength="20"  disabled>

                <label style="font-size:18px;color: #fff; padding-left: 2%" for="screw"><?php echo $text['screws'];?> :</label>&nbsp;
                <input type="text" id="max_screw_count" name="max_screw_count" size="4" maxlength="20"  disabled>
            </div>
            
            <div class="operation-setting">
                <div class="column">
                    <div class="item-target-torque w3-display-container">
                        <div class="w3-display-topmiddle w3-border-top w3-border-bottom w3-border-red"><?php echo $text['final_torque'] ;?>(<span id='fasten_status_unit_explain'></span>)</div>
                        <div id="fasten_torque" class="w3-display-middle" style="font-size: 6vmin"></div>
                    </div>
                    <div id='fasten_status_bg' class="item-result w3-display-container">
                        <div class="w3-display-topmiddle w3-border-top w3-border-bottom w3-border-black"><?php echo $text['final_result'];?></div>
                        <div id="fasten_status_explain" class="w3-display-middle" style="font-size: 6vmin">

                        </div>            
                    </div>
                </div>
                <div class="column">
                    <div class="item-targer-angle w3-display-container">
                        <div class="w3-display-topmiddle w3-border-top w3-border-bottom w3-border-red"><?php echo $text['final_angle'];?></div>
                        <div id="fasten_angle" class="w3-display-middle" style="font-size: 6vmin"></div>                        
                    </div>
                    <div class="item-message w3-display-container">
                        <div class="w3-display-topmiddle w3-border-top w3-border-bottom w3-border-red"><?php echo $text['final_message'];?></div>
                        <div id="error_massage_explanation" class="w3-display-middle" style="font-size: 28px">

                        </div>                                    
                    </div>
                </div>
            </div>
            
            <div class="operation-setting">
                <div class="column">
                    <div class="item-chart">
                        <div class="button-chart">

                            <?php foreach($data['chart_menu_arr'] as $k_menu =>$v_menu){?>
                                <button type="button" <?php if($data['chart_mode'] == $k_menu){ echo $class ='class="btn-chart active"';}else { echo $class ='class="btn-chart"'; }?>   id= '<?php echo $v_menu['id'];?>' onclick="chart_type('<?php echo $v_menu['id'];?>')" ><?php echo $text[$v_menu['name']];?></button>
                            <?php }?>
                        </div>

                    
                       
                        <div id="graph" class="display-chart">
                        <?php if(!empty($data['other_data'])){?>
                            <table class="chart-table">
                                <thead>
                                    <tr>
                                        <th><?php echo $text['step']; ?></th>
                                        <?php for ($i = 1; $i <= 4; $i++){?>
                                            <th><?php echo $i; ?></th>
                                        <?php }?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $rows = array(
                                        $text['Torque'] => $data['other_data']['torque'],
                                        $text['Angle'] => $data['other_data']['angle']
                                    );
                                    foreach ($rows as $label => $values){?>
                                        <tr>
                                            <td><?php echo $label; ?></td>
                                            <?php for ($i = 1; $i <= 4; $i++){?>
                                                <td><?php echo isset($values[$i]) ? $values[$i] : 'N/A'; ?></td>
                                            <?php } ?>
                                        </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        <?php }?>

                            <div id="chart" style="height: calc(50vh - 100px)"></div>
                        </div>      

                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    // Button Home

    // 改變按鈕背景顏色
    function changeBackgroundColor(button) {
        var buttons = document.getElementsByClassName('btn-chart');
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].classList.remove('active');
        }
        button.classList.add('active');
    }

    function chart_type(argument) {
        var currentUrl = window.location.href;

        // 處理按鈕的class
        var buttons = document.getElementsByClassName("btn-chart");
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].classList.remove("active");
        }
        var activeButton = document.getElementById(argument);
        activeButton.classList.add("active");

        var chartIndex = currentUrl.indexOf('chart=');
        var chart;

        // 根據選擇的圖表類型設定圖表編號
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

        // 如果 URL 已經包含 chart 參數，更新該參數
        if (chartIndex !== -1) {
            var nextChartValue = 'chart=' + chart;
            nextinfo_url = currentUrl.substring(0, chartIndex) + nextChartValue;
        } else {
            var separator = currentUrl.indexOf('?') !== -1 ? '&' : '?';
            nextinfo_url = currentUrl + separator + 'chart=' + chart;
        }

        // 發送請求並跳轉到新的 URL
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

    // 宣告 myChart 為全域變數
    var myChart;

    function initializeChart() {
        // 取得 x 和 y 的數值
        var x_data_val = <?php echo json_encode($data['chart_info']['x_val']); ?>;
        var y_data_val = <?php echo json_encode($data['chart_info']['y_val']); ?>;
        var x_title = '<?php echo addslashes($data['echart_name'][1]); ?>';
        var y_title = '<?php echo addslashes($data['echart_name'][0]); ?>';

        // 檢查 x 和 y 的數值是否為空或 null，如果是則停止執行
        if (!x_data_val || !y_data_val || x_data_val.length === 0 || y_data_val.length === 0) {
            console.log("x_val 或 y_val 为空，停止执行图表初始化。");
            return;  // 停止後續執行
        }

        // 根據語言來本地化圖表標題
        if (language == "zh-tw") {
            if (x_title == "Time(MS)") x_title = "時間";
            if (x_title == "Angle") x_title = "角度";
            if (x_title == "Torque") x_title = "扭力";
            if (y_title == "Angle") y_title = "角度";
            if (y_title == "Torque") y_title = "扭力";
            if (y_title == "RPM") y_title = "轉速";
        }

        if (language == "zh-cn") {
            if (x_title == "Time(MS)") x_title = "时间";
            if (x_title == "Angle") x_title = "角度";
            if (x_title == "Torque") x_title = "扭力";
            if (y_title == "Angle") y_title = "角度";
            if (y_title == "Torque") y_title = "扭力";
            if (y_title == "RPM") y_title = "转速";
        }

        // 初始化圖表
        myChart = echarts.init(document.getElementById('chart'));

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
            xAxis: {
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
                    name: '',
                    type: 'line',
                    symbol: 'none',
                    sampling: 'average',
                    itemStyle: {
                        normal: {
                            color: 'rgb(255,0,0)'
                        }
                    },
                    areaStyle: {
                        normal: {
                            color: new echarts.graphic.LinearGradient(0, 0, 0, 0, [
                                { offset: 0, color: 'rgb(255,255,255)' },
                                { offset: 0, color: 'rgb(255,255,255)' }
                            ])
                        }
                    },
                    lineStyle: { width: 0.75 },
                    data: y_data_val
                }
            ]
        };

        myChart.setOption(option);
    }

    // 生成 DataZoom 配置
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

    // 在頁面加載後調用 initializeChart 函數
    initializeChart();

    let pollingActive = true;
    async function fetchData(url, system_sn, chart_mode) {
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ system_sn: system_sn, chart_mode: chart_mode })// 將 system_sn  && chart_mode 包裝成 JSON 物件並發送
            });

            if (!response.ok) {
                throw new Error(`HTTP 錯誤！狀態碼: ${response.status}`);
            }

            const data = await response.json();
            console.log('最新資料:', data);
            updateDataOnPage(data); // 更新頁面數據

        } catch (error) {
            console.error('API 調用錯誤:', error);
        }
    }

    // 每隔 interval 毫秒調用一次 API
    function startApiPolling(url = '?url=Dashboards/get_new_data', interval = 3000) {
        const system_sn = document.getElementById('system_sn').value || '--'; 

        const urlParams = new URLSearchParams(window.location.search);
        const chart_mode = urlParams.get('chart') || 1;  // 如果沒有 chart 參數，默認為 1
        console.log("chart_mode:", chart_mode);
        async function poll() {
            if (pollingActive) {
                await fetchData(url, system_sn,chart_mode);
                setTimeout(poll, interval); 
            }
        }
        poll();
    }

    // 更新頁面數據的函式
    function updateDataOnPage(data) {
        if (!data) return;

        document.getElementById('system_sn').value = data.system_sn || '--';
        document.getElementById('job_name').value = data.job_name || '--';
        document.getElementById('seq_name').value = data.seq_name || '--';
        document.getElementById('max_screw_count').value = data.max_screw_count || '--';
        document.getElementById('fasten_torque').innerText = data.fasten_torque || 'N/A';
        document.getElementById('fasten_angle').innerText = data.fasten_angle || 'N/A';
        document.getElementById('fasten_status_explain').innerText = data.fasten_status_explain || 'N/A';
        document.getElementById('fasten_status_unit_explain').innerText = data.fasten_status_unit_explain || '';
        document.getElementById('error_massage_explanation').innerText = data.error_massage_explanation || '';

        const bgColor = data.fasten_status_bg || '';  
        document.getElementById('fasten_status_bg').style.backgroundColor = bgColor;

        if (data.chart_data) {
            updateChart(data.chart_data);  // 更新圖表
        }
    }

    // 更新圖表
    function updateChart(chartData) {
        if (!chartData) return;

        var option = {
            xAxis: {
                name: chartData.x_title,
                data: chartData.x_val
            },
            yAxis: {
                name: chartData.y_title
            },
            series: [
                {
                    type: 'line',
                    data: chartData.y_val
                }
            ]
        };

        if (myChart) {
            myChart.setOption(option);  // 確保 ECharts 實例已經初始化
        }
    }

    // 開始即時 API 調用，每 3 秒更新一次數據
    startApiPolling();
</script>

</body>

</html>
<style>
  .chart-table {
    width: 99%; /* Or adjust as needed */
    border-collapse: collapse;
    margin-bottom: 20px; /* Space between table and chart */
}

.chart-table th, .chart-table td {
    border: 1px solid #ddd; /* Light gray borders */
    padding: 5px; /* Reduced padding */
    text-align: center;
    font-size: 12px; /* Smaller font size */
    height: 25px; /* Adjust row height as needed */
}

.chart-table th {
    background-color: #f0f0f0; /* Light gray header */
}
</style> 


<div class="container-ms">
    <div class="w3-text-white w3-center">
        <table class="no-border">
            <tr id="header">
                <td width="100%"><h3><?php echo $text['operation_result'];?></h3></td>
                <td><img src="./img/btn_home.png" style="margin-right: 10px"  onclick="location.href='?url=Dashboards'"></td>
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



<script>
/* =====================================================
 * 共用工具（原本保留）
 * ===================================================== */

// 固定 Step 顏色
const STEP_COLORS = [
    '#e53935', '#1e88e5', '#43a047', '#fb8c00',
    '#8e24aa', '#00897b', '#6d4c41', '#546e7a'
];

function getStepColor(stepNo) {
    const n = Number(stepNo) || 1;
    return STEP_COLORS[(n - 1) % STEP_COLORS.length];
}

// 切換按鈕背景
function changeBackgroundColor(button) {
    var buttons = document.getElementsByClassName('btn-chart');
    for (var i = 0; i < buttons.length; i++) {
        buttons[i].classList.remove('active');
    }
    button.classList.add('active');
}

// 語系轉換
function localizeAxisTitle(language, x_title, y_title) {

    if (language === "zh-tw") {
        if (x_title === "Time(MS)") x_title = "時間";
        if (x_title === "Angle")    x_title = "角度";
        if (x_title === "Torque")   x_title = "扭力";
        if (y_title === "Angle")    y_title = "角度";
        if (y_title === "Torque")   y_title = "扭力";
        if (y_title === "RPM")      y_title = "轉速";
    }

    if (language === "zh-cn") {
        if (x_title === "Time(MS)") x_title = "时间";
        if (x_title === "Angle")    x_title = "角度";
        if (x_title === "Torque")   x_title = "扭力";
        if (y_title === "Angle")    y_title = "角度";
        if (y_title === "Torque")   y_title = "扭力";
        if (y_title === "RPM")      y_title = "转速";
    }

    return { x_title, y_title };
}

// chart 切換（保留你原本的 URL reload 行為）
function chart_type(argument) {

    var currentUrl = window.location.href;
    var chart;

    if (argument === "torque_time") chart = 1;
    if (argument === "angle_time")  chart = 2;
    if (argument === "rpm_time")    chart = 3;
    if (argument === "torque_angle")chart = 4;

    var chartIndex = currentUrl.indexOf('chart=');
    var nextinfo_url;

    if (chartIndex !== -1) {
        nextinfo_url = currentUrl.substring(0, chartIndex) + 'chart=' + chart;
    } else {
        var sep = currentUrl.indexOf('?') !== -1 ? '&' : '?';
        nextinfo_url = currentUrl + sep + 'chart=' + chart;
    }

    window.location.assign(nextinfo_url);
}


/* =====================================================
 * 統一曲線圖（唯一畫圖流程）
 * ===================================================== */

var language = getCookie('language');
var myChart = null;

// 後端統一給的資料（關鍵）
const chartPayload = <?= json_encode($data['chart_payload'], JSON_UNESCAPED_UNICODE) ?>;
console.log(chartPayload);

window.addEventListener('load', function () {

    const dom = document.getElementById('chart');
    if (!dom) {
        console.warn('[chart] dom not found');
        return;
    }

    myChart = echarts.init(dom);

    if (
        !chartPayload ||
        !Array.isArray(chartPayload.xAxis) ||
        chartPayload.xAxis.length === 0 ||
        !Array.isArray(chartPayload.series) ||
        chartPayload.series.length === 0
    ) {
        console.log('[chart] payload empty, skip draw');
        return;
    }

    drawUnifiedChart(chartPayload);
});

function drawUnifiedChart(payload) {

    const series = payload.series.map(s => ({
        name: s.name,
        type: 'line',
        data: s.data,
        showSymbol: false,
        smooth: false,
        lineStyle: { width: 1.6 }
    }));

    const option = {
        tooltip: {
            trigger: 'axis'
        },
        legend: { show: false }, 
        grid: {
            left: '8%',
            right: '6%',
            top: '12%',
            bottom: '12%'
        },
        xAxis: {
            type: 'category',
            data: payload.xAxis,
            boundaryGap: false
        },
        yAxis: {
            type: 'value',
            scale: true
        },
        series: series
    };

    myChart.clear();
    myChart.setOption(option, true);
}


/* =====================================================
 * Resize / Orientation（保留）
 * ===================================================== */

window.addEventListener("resize", function() {
    if (myChart && myChart.resize) myChart.resize();
});

window.addEventListener("orientationchange", function() {
    setTimeout(function() {
        if (myChart && myChart.resize) myChart.resize();
    }, 300);
});


/* =====================================================
 * 即時資料 polling
 * ===================================================== */
let lastDataVersion = 0;
let pollingActive = true;

async function fetchData(url, system_sn, chart_mode) {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                system_sn,
                chart_mode,
                last_version: lastDataVersion   // ⭐新增
            })
        });

        if (!response.ok) throw new Error(response.status);

        const data = await response.json();
        updateDataOnPage(data);

    } catch (e) {
        console.error('API error:', e);
    }
}

function updateDataOnPage(data) {
    if (!data) return;

    /* =============================
       更新文字資訊
    ============================= */

    document.getElementById('system_sn').value = data.system_sn || '--';
    document.getElementById('job_name').value = data.job_id + "/" + data.jobs_count;
    document.getElementById('seq_name').value = data.seq_id + "/" + data.seqs_count;
    document.getElementById('max_screw_count').value =
        data.last_screw_count + "/" + data.max_screw_count;

    document.getElementById('fasten_torque').innerText = data.fasten_torque || 'N/A';
    document.getElementById('fasten_angle').innerText  = data.fasten_angle || 'N/A';
    document.getElementById('fasten_status_explain').innerText =
        data.fasten_status_explain || 'N/A';
    document.getElementById('fasten_status_unit_explain').innerText =
        data.fasten_status_unit_explain || '';
    document.getElementById('error_massage_explanation').innerText =
        data.error_massage_explanation || '';

    if (data.fasten_status_bg) {
        document.getElementById('fasten_status_bg').style.backgroundColor =
            data.fasten_status_bg;
    }

    /* =============================
       即時更新曲線圖
    ============================= */

    if (!data.data_version) return;

    // 沒有新資料 → 不重畫
    if (data.data_version === lastDataVersion) return;

    console.log("📈 New fastening detected → redraw chart");

    lastDataVersion = data.data_version;

    if (!data.chart_payload || !myChart) return;

    drawUnifiedChart(data.chart_payload);
}


function startApiPolling(url = '?url=Dashboards/get_new_data', interval = 1000) {
    const system_sn = document.getElementById('system_sn').value || '--';
    const chart_mode = new URLSearchParams(location.search).get('chart') || 1;

    async function poll() {
        if (!pollingActive) return;
        await fetchData(url, system_sn, chart_mode);
        setTimeout(poll, interval);
    }
    poll();
}

// 啟動 polling
startApiPolling();

</script>

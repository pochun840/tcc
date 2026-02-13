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

input:disabled 
{
    opacity: 1; /* Giữ độ rõ nét */
    color: #000; /* Đảm bảo chữ vẫn có màu đen rõ ràng */
}

</style>


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
                            <input style=" color: #000" type="text" id="job_name" name="job_name" size="10" maxlength="15" value="" disabled>
                            <input type="hidden" id="system_sn" name="system_sn" size="15" disabled>
                        </td>
                        <td>
                            <label style="color: #FFF; font-weight: bold" for="Seq_Name"><?php echo $text['sequence']; ?>:</label>
                            <input style=" color: #000" type="text" id="seq_name" name="seq_name" size="10" maxlength="15" value="" disabled>
                        </td>
                        <td>
                            <label style="color: #FFF; font-weight: bold" for="Screws"><?php echo $text['screws']; ?>:</label>
                            <input style=" color: #000; text-align: center" type="text" id="max_screw_count" name="max_screw_count" size="4" maxlength="5" value="" disabled>
                            <input style=" color: #000; text-align: center" type="hidden" id="last_screw_count" name="last_screw_count" size="4" maxlength="5" value="" disabled>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="operation-setting">
                <div class="column">
                    <div class="item-target-torque w3-display-container">
                        <div class="w3-display-topmiddle w3-border-top w3-border-bottom w3-border-red"><?php echo $text['final_torque'] ;?>(<span id='fasten_status_unit_explain'></span>)</div>
                        <div id="fasten_torque" class="w3-display-middle" style="font-size: 6vmin; margin: 5px 0;"></div>
                    </div>
                    <div id='fasten_status_bg' class="item-result w3-display-container">
                        <div class="w3-display-topmiddle w3-border-top w3-border-bottom w3-border-black"><?php echo $text['final_result'];?></div>
                        <div id="fasten_status_explain" class="w3-display-middle" style="font-size: 6vmin; margin: 5px 0"></div>            
                    </div>
                </div>
                <div class="column">
                    <div class="item-targer-angle w3-display-container">
                        <div class="w3-display-topmiddle w3-border-top w3-border-bottom w3-border-red"><?php echo $text['final_angle'];?></div>
                        <div id="fasten_angle" class="w3-display-middle" style="font-size: 6vmin; margin: 5px 0"></div>                        
                    </div>
                    <div class="item-message w3-display-container">
                        <div class="w3-display-topmiddle w3-border-top w3-border-bottom w3-border-red"><?php echo $text['final_message'];?></div>
                        <div id="error_massage_explanation" class="w3-display-middle" style="font-size: 5vmin; margin: 5px 0"></div>                                    
                    </div>
                </div>
            </div>
            <div class="chart-setting">
                <div class="button-chart">
                    <?php foreach($data['chart_menu_arr'] as $k_menu => $v_menu): ?>
                        <?php
                            $isActive = ($data['chart_mode'] == $k_menu) ? 'active' : '';
                            $id = $v_menu['id'];
                        ?>
                        <button
                            type="button"
                            class="btn-chart <?php echo $isActive; ?>"
                            id="<?php echo $id; ?>"
                            onclick="chart_type('<?php echo $id; ?>')">
                            <?php echo $text[$v_menu['name']]; ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <div id="graph" class="display-chart">
                    <?php if (!empty($data['other_data'])): ?>
                        <table class="chart-table">
                            <thead>
                                <tr>
                                    <th><?php echo $text['step']; ?></th>
                                    <?php for ($i = 1; $i <= 4; $i++): ?>
                                        <th><?php echo $i; ?></th>
                                    <?php endfor; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $rows = [
                                    $text['Torque'] => $data['other_data']['torque'],
                                    $text['Angle']  => $data['other_data']['angle']
                                ];
                                foreach ($rows as $label => $values): ?>
                                    <tr>
                                        <td><?php echo $label; ?></td>
                                        <?php for ($i = 1; $i <= 4; $i++): ?>
                                            <td><?php echo isset($values[$i]) ? $values[$i] : 'N/A'; ?></td>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>

                    <div id="chart" align="center" style="max-width: 100%; height: 290px;"></div>
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
let lastDataVersion = 0;
let pollingActive = true;

/* =====================================================
 * 共用工具
 * ===================================================== */

// 切換按鈕 active 樣式
function changeBackgroundColor(button) {
    document.querySelectorAll('.btn-chart').forEach(btn => {
        btn.classList.remove('active');
    });
    button.classList.add('active');
}

// chart 切換（不 reload 頁面）
function chart_type(argument) {

    document.querySelectorAll('.btn-chart').forEach(btn => {
        btn.classList.toggle('active', btn.id === argument);
    });

    let chart = 1;
    if (argument === "angle_time")  chart = 2;
    if (argument === "rpm_time")    chart = 3;
    if (argument === "torque_angle")chart = 4;

    const url = new URL(window.location);
    url.searchParams.set("chart", chart);
    window.history.replaceState({}, '', url);

    // ⭐強制重新抓新曲線
    lastDataVersion = 0;

    const sn = document.getElementById('system_sn').value;
    if (!sn) return;   // ⭐這行非常重要

    fetchData('?url=Dashboards/get_new_data', sn, chart);
}



/* =====================================================
 * 統一曲線圖（唯一入口）
 * ===================================================== */
function getLangSafe() {
  return (getCookie?.('language') || 'zh-tw').toLowerCase();
}

function t(key) {
  const lang = getLangSafe();
  const dict = {
    waiting_fasten: {
      'zh-tw': '等待鎖附資料中…',
      'zh-cn': '等待锁附数据中…',
      'en-us': 'Waiting for fastening data…'
    }
  };
  return (dict[key] && (dict[key][lang] || dict[key]['en-us'])) || key;
}

var myChart = null;

// 後端統一輸出的資料
const chartPayload = <?= json_encode($data['chart_payload'], JSON_UNESCAPED_UNICODE) ?>;
window.chartPayload = chartPayload;

window.addEventListener('load', function () {

    const dom = document.getElementById('chart');
    if (!dom) return;

    myChart = echarts.init(dom);

    if (
        !chartPayload ||
        !Array.isArray(chartPayload.xAxis) ||
        chartPayload.xAxis.length === 0 ||
        !Array.isArray(chartPayload.series) ||
        chartPayload.series.length === 0
    ) {
        dom.innerHTML =
             `<div style="text-align:center;color:#999;padding-top:80px;">${t('waiting_fasten')}</div>`;
        return;
    }

    drawUnifiedChart(chartPayload);

    if (chartPayload && chartPayload.version) {
        lastDataVersion = chartPayload.version;
    }

    // ⭐搬到這裡
    const mainContent = document.querySelector('.main-content');
    if (mainContent) {
        mainContent.scrollIntoView({ behavior: 'auto', block: 'start' });
    }
});


function drawUnifiedChart(payload) {

    if (!myChart) return;

    const series = payload.series.map(s => ({
        name: s.name,
        type: 'line',
        data: s.data,
        showSymbol: false,
        smooth: false,
        lineStyle: { width: 1.5 }
    }));

    const option = {
        tooltip: { trigger: 'axis' },
        legend: { show: false }, 
        grid: {
            left: '10%',
            right: '6%',
            top: '14%',
            bottom: '14%'
        },
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: payload.xAxis
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
 * Resize / Orientation
 * ===================================================== */

window.addEventListener("resize", function () {
    if (myChart && myChart.resize) myChart.resize();
});

window.addEventListener("orientationchange", function () {
    setTimeout(function () {
        if (myChart && myChart.resize) myChart.resize();
    }, 300);
});

/* =====================================================
 * 即時資料 polling（只更新右側狀態，不再動 chart）
 * ===================================================== */

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
       更新右側文字資訊
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

    // 後端沒有給 version → 不更新
    if (!data.data_version) return;

    // 沒有新鎖附 → 不重畫
    if (data.data_version === lastDataVersion) return;

    console.log("📈 New fastening detected → redraw chart");

    lastDataVersion = data.data_version;

    if (!data.chart_payload) return;

    // 更新全域 payload
    window.chartPayload = data.chart_payload;

    // 重畫圖
    drawUnifiedChart(data.chart_payload);
}


function startApiPolling(url = '?url=Dashboards/get_new_data', interval = 1000) {

    async function poll() {

        if (!pollingActive) return;

        const system_sn =
            document.getElementById('system_sn').value || '';

        const chart_mode =
            new URLSearchParams(location.search).get('chart') || 1;

        // ⭐沒有 system_sn 不要打 API
        if (!system_sn) {
            setTimeout(poll, interval);
            return;
        }

        await fetchData(url, system_sn, chart_mode);
        setTimeout(poll, interval);
    }

    poll();
}

startApiPolling();

</script>


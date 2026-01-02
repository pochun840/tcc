
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


<script>
    // Button Home

        // ============================
        // 固定 Step 顏色設定
        // ============================
        const STEP_COLORS = [
            '#e53935', // red
            '#1e88e5', // blue
            '#43a047', // green
            '#fb8c00', // orange
            '#8e24aa', // purple
            '#00897b', // teal
            '#6d4c41', // brown
            '#546e7a'  // gray
        ];

    // 依 step number 取得固定顏色
    function getStepColor(stepNo) {
        const n = Number(stepNo) || 1;
        return STEP_COLORS[(n - 1) % STEP_COLORS.length];
    }



    // 改變按鈕背景顏色
    function changeBackgroundColor(button) {
        var buttons = document.getElementsByClassName('btn-chart');
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].classList.remove('active');
        }
        button.classList.add('active');
    }

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

    // ============================
    // 初始化曲線圖（chart_mode 1/2/3）
    // ============================
    function initializeChart() {

        var x_data_val = <?= isset($data['chart_info']['x_val']) ? json_encode($data['chart_info']['x_val']) : 'null' ?>;
        var y_data_val = <?= isset($data['chart_info']['y_val']) ? json_encode($data['chart_info']['y_val']) : 'null' ?>;

        
        // ✅ 修正：後端可能已經 json_encode 過（變字串），這裡要轉回陣列
        try {
            if (typeof x_data_val === "string") x_data_val = JSON.parse(x_data_val);
            if (typeof y_data_val === "string") y_data_val = JSON.parse(y_data_val);
        } catch (e) {
            console.error("x/y JSON parse failed", e, x_data_val, y_data_val);
            return;
        }

        var x_title = '<?php echo isset($data['echart_name'][1]) ? addslashes($data['echart_name'][1]) : ''; ?>';
        var y_title = '<?php echo isset($data['echart_name'][0]) ? addslashes($data['echart_name'][0]) : ''; ?>';

        if (!x_data_val || !y_data_val || x_data_val.length === 0 || y_data_val.length === 0) {
            console.log("x_val 或 y_val 為空，略過曲線圖初始化");
            return;
        }

        // 語系轉換
        var t = localizeAxisTitle(language, x_title, y_title);
        x_title = t.x_title;
        y_title = t.y_title;

        // ⭐ 關鍵：組成 [x, y]（Time-based chart 必須這樣）
        var seriesData = x_data_val.map(function (x, i) {
            return [ Number(x), Number(y_data_val[i]) ];
        });

        myChart = echarts.init(document.getElementById('chart'));

        var option = {
            tooltip: {
                trigger: 'axis'
            },
            xAxis: {
                type: 'value',        // ✅ 修正：數值軸
                name: x_title,
                boundaryGap: false
            },
            yAxis: {
                type: 'value',
                name: y_title
            },
            dataZoom: generateDataZoom(),
            series: [{
                type: 'line',
                symbol: 'none',
                lineStyle: { width: 0.75 },
                data: seriesData
            }]
        };

        myChart.setOption(option, true);
    }

    // ============================
    // 即時更新曲線（polling）
    // ============================
    function updateChart(chartData) {

        // ✅ ① chart=4 完全不走 polling 畫線（CSV-only）
        const chartMode = new URLSearchParams(location.search).get('chart') || "1";
        if (chartMode === "4") return;

        if (!chartData) return;

        let x = chartData.x_val;
        let y = chartData.y_val;

        // ✅ ② 防呆：後端可能回傳的是 JSON 字串
        try {
            if (typeof x === "string") x = JSON.parse(x);
            if (typeof y === "string") y = JSON.parse(y);
        } catch (e) {
            console.error("[updateChart] x/y parse failed", e, x, y);
            return;
        }

        if (!Array.isArray(x) || !Array.isArray(y) || x.length === 0) return;

        // 語系轉換
        var t = localizeAxisTitle(language, chartData.x_title, chartData.y_title);
        chartData.x_title = t.x_title;
        chartData.y_title = t.y_title;

        // ⭐ 關鍵：即時資料也要 [x, y]
        var seriesData = x.map(function (vx, i) {
            return [ Number(vx), Number(y[i]) ];
        });

        var option = {
            xAxis: {
                type: 'value',
                name: chartData.x_title,
                boundaryGap: false
            },
            yAxis: {
                type: 'value',
                name: chartData.y_title
            },
            series: [{
                type: 'line',
                symbol: 'none',
                data: seriesData
            }]
        };

        if (myChart) {
            myChart.setOption(option, true);
        }
    }


    // ============================
    // DataZoom（原樣保留）
    // ============================
    function generateDataZoom() {
        return [
            { type: 'inside', start: 0, end: 100 },
            { show: false, type: 'slider', start: 0, end: 100 }
        ];
    }


    // ============================
    // CSV parser（簡單、穩定版）
    // ============================
    function parseCSV(text) {
        if (!text) return null;

        const lines = text.trim().split(/\r?\n/);
        if (lines.length <= 1) return null;

        const delimiter = lines[0].includes('\t') ? '\t' : ',';
        lines.shift(); // remove header

        const angle = [];
        const torque = [];
        const step = [];   // 可能全空

        lines.forEach(line => {
            const cols = line.split(delimiter);

            const tor = Number(cols[1]);
            const ang = Number(cols[2]);

            // step 可能不存在或是空字串
            const stpRaw = cols[4];
            const stp = (stpRaw !== undefined && String(stpRaw).trim() !== '')
                ? Number(stpRaw)
                : null;

            if (isNaN(tor) || isNaN(ang)) return;
            if (tor === 0 && ang === 0) return;

            torque.push(tor);
            angle.push(ang);
            step.push(Number.isFinite(stp) ? stp : null);
        });

        return { angle, torque, step };
    }





    // 2. Khi xoay hoặc resize màn hình → biểu đồ tự điều chỉnh lại
    window.addEventListener("resize", function() {
        if (typeof myChart !== 'undefined' && myChart.resize) {
            myChart.resize();
        }
    });

    window.addEventListener("orientationchange", function() {
        setTimeout(function() {
            if (typeof myChart !== 'undefined' && myChart.resize) {
                myChart.resize();
            }
        }, 300);
    });


    // 在頁面加載後調用 initializeChart 函數
    (function startChart() {
        const chartMode = new URLSearchParams(location.search).get('chart') || "1";

        if (chartMode === "4") {
            loadCSVAndRenderTorqueAngle();   // CSV-only
        } else {
            initializeChart();               // 原本 chart 1~3
        }
    })();




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

    // ============================
    // chart=4 Torque / Angle renderer
    // ============================
    function renderTorqueAngle(d) {
        if (!d || !d.angle || !d.torque) return;

        // 確保 myChart 存在
        if (!myChart) {
            const dom = document.getElementById('chart');
            if (!dom) return;
            myChart = echarts.init(dom);
        }

        // ============================
        // chart=4：強制忽略 step（就算 CSV 有 step）
        // ============================
        const FORCE_NO_STEP = true;

        const hasStep = !FORCE_NO_STEP &&
            Array.isArray(d.step) &&
            d.step.some(v => v !== null);

        let series = [];

        if (hasStep) {
            // ========= 有 step：依 step 分組 =========
            const seriesByStep = {};
            d.step.forEach((s, i) => {
                const stepNo = (s === null ? 1 : s);
                if (!seriesByStep[stepNo]) seriesByStep[stepNo] = [];
                seriesByStep[stepNo].push([Number(d.angle[i]), Number(d.torque[i])]);
            });

            series = Object.keys(seriesByStep).map(stepNo => ({
                type: 'line',
                showSymbol: false,
                data: seriesByStep[stepNo],
                lineStyle: { width: 1, color: getStepColor(stepNo) },
                itemStyle: { color: getStepColor(stepNo) }
            }));

        } else {
            
            // ========= 沒 step：只取「Torque 單調上升段」 =========
            const TORQUE_START = 0.01;   // 扭力啟動
            const FALL_COUNT_LIMIT = 5;  // ⭐ 連續下降幾次視為結束（重點）

            let started = false;
            let fallCount = 0;
            let lastTorque = null;

            const mainSegment = [];

            for (let i = 0; i < d.angle.length; i++) {
                const ang = Number(d.angle[i]);
                const tor = Number(d.torque[i]);

                if (!Number.isFinite(ang) || !Number.isFinite(tor)) continue;

                // 還沒開始鎖附
                if (!started) {
                    if (tor >= TORQUE_START) {
                        started = true;
                        mainSegment.push([ang, tor]);
                        lastTorque = tor;
                    }
                    continue;
                }

                // 已進入鎖附段
                if (tor >= lastTorque) {
                    // 扭力仍在上升或持平
                    mainSegment.push([ang, tor]);
                    lastTorque = tor;
                    fallCount = 0;
                } else {
                    // 扭力下降
                    fallCount++;

                    if (fallCount >= FALL_COUNT_LIMIT) {
                        // ⭐ 視為主鎖附結束
                        break;
                    }

                    // 下降初期仍允許少量點（避免雜訊）
                    mainSegment.push([ang, tor]);
                    lastTorque = tor;
                }
            }

            series = [{
                type: 'line',
                showSymbol: false,
                smooth: false,
                lineStyle: { width: 1.8 },
                data: mainSegment
            }];

        }

        // === chart=4 座標語系化 ===
        let xTitle = 'Angle';
        let yTitle = 'Torque';

        // 使用你既有的語系轉換
        const t = localizeAxisTitle(language, xTitle, yTitle);
        xTitle = t.x_title;
        yTitle = t.y_title;

        myChart.setOption({
            tooltip: {
                trigger: 'axis',
                formatter: function (params) {
                    if (!params || !params.length) return '';
                    const p = params[0].value;
                    return `${xTitle} : ${p[0]}<br/>${yTitle} : ${p[1]}`;
                }
            },
            xAxis: {
                type: 'value',
                name: xTitle
            },
            yAxis: {
                type: 'value',
                name: yTitle
            },
            series
        }, true);

    }




    async function loadCSVAndRenderTorqueAngle() {

        const csvName = "<?= $data['latest_csv'] ?? '' ?>";
        if (!csvName) {
            console.warn('[chart=4] no latest_csv');
            return;
        }

        const url = `${location.origin}/idas/public/ftp/${csvName}?t=${Date.now()}`;
        const res = await fetch(url);
        if (!res.ok) {
            console.error('[chart=4] csv fetch failed');
            return;
        }

        const text = await res.text();
        if (!text || text.trim().length === 0) {
            console.warn('[chart=4] empty csv');
            return;
        }

        const parsed = parseCSV(text);
        if (!parsed || !parsed.angle.length) {
            console.warn('[chart=4] parsed csv empty');
            return;
        }

        renderTorqueAngle(parsed);
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
        document.getElementById('job_name').value = data.job_id + "/" + data.jobs_count;
        document.getElementById('seq_name').value = data.seq_id + "/" + data.seqs_count;

        document.getElementById('max_screw_count').value = data.last_screw_count +"/" + data.max_screw_count;

        document.getElementById('fasten_torque').innerText = data.fasten_torque || 'N/A';
        document.getElementById('fasten_angle').innerText = data.fasten_angle || 'N/A';
        document.getElementById('fasten_status_explain').innerText = data.fasten_status_explain || 'N/A';
        document.getElementById('fasten_status_unit_explain').innerText = data.fasten_status_unit_explain || '';
        document.getElementById('error_massage_explanation').innerText = data.error_massage_explanation || '';

        const bgColor = data.fasten_status_bg || '';
        document.getElementById('fasten_status_bg').style.backgroundColor = bgColor;

        if (data.chart_data) {
            updateChart(data.chart_data); // 更新圖表
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

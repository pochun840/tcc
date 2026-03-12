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
        /* font-family: 'LED字型', sans-serif; */
    }

    @media screen and (orientation: landscape)  {
        .panel-container {
            height: 65%!important;
            margin: 3px;
        }

        .w3-container, .w3-panel {
            padding: 0.01em 5px;
        }

        .message-font {
            font-size: 4vmin!important;
        }

        .w3-panel {
            margin-top: 5px!important;
        }

        .table-font {
            font-size: 3vmin!important;
        }

        .p1{ width:33%; height:23%; background-color: #CDC5BF; }
        .p2{ width:33%; height:23%; background-color: #CDC9C9; }
        .p3{ width:33%; height:23%; background-color: #CDC9C9; position: absolute; left: 33.5%; top: 0%; }
        .p4{ width:50%; height:23%; background-color: #CDC5BF; position: absolute; right: 0; top: 26%; display:none; }
        .p5{ margin: 0px; padding: 0; position: absolute; left: 17%; top: 26%; width:83%; height: 100% }
        .p6{ margin: 0px; padding: 0; position: absolute; left: 0; top: 26%; width: 20%; text-align:center; }
        .nav-item{ margin-bottom: 5px; }

        #Target_Torque{ top: 70%!important; }
        #Torque_Result{ top: 70%!important; }
        #Target_Angle{ top: 70%!important; }
        .i-btn{ display: block!important; }
    }

    .panel-container {
        height: 83%;
        margin: 3px;
    }

    @media screen and (orientation: portrait) {
        .message-font {
            font-size: 4vmin!important;
        }

        .table-font {
            font-size: 3vmin!important;
        }

        .p1{ width:48%; height:20%; background-color: #CDC5BF; }
        .p2{ width:50%; height:20%; background-color: #CDC9C9; }
        .p3{ width:48%; height:20%; background-color: #CDC9C9; position: absolute; left: 0; top: 21%; }
        .p4{ width:50%; height:20%; background-color: #CDC5BF; position: absolute; right: 0; top: 21%; }
        .p5{ margin: 0px; padding: 0; position: absolute; left:0; top: 42%; }
        .p6{ margin: 0px; padding: 0; position: absolute; left: 0; top: 92%; text-align:center; }
        .nav-item{ margin-bottom: 5px; width: 20%; }
    }

    input:disabled {
        opacity: 1;
        color: #000;
    }

    .chart-table {
        width: 99%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .chart-table th,
    .chart-table td {
        border: 1px solid #ddd;
        padding: 5px;
        text-align: center;
        font-size: 12px;
        height: 25px;
    }

    .chart-table th {
        background-color: #f0f0f0;
    }
</style>

<div class="container-ms">
    <div class="w3-text-white w3-center">
        <table class="no-border">
            <tr id="header">
                <td width="100%">
                    <h3><?php echo $text['operation_result']; ?></h3>
                </td>
                <td>
                    <img id="back_home" src="./img/btn_home.png" style="margin-right: 10px" onclick="window.location.href='?url=In';">
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
                            <label style="color: #FFF; font-weight: bold" for="job_name"><?php echo $text['job']; ?>:</label>
                            <input style="color: #000" type="text" id="job_name" name="job_name" size="10" maxlength="15" value="" disabled>
                            <input type="hidden" id="system_sn" name="system_sn" size="15"
                                   value="<?php echo isset($data['system_sn']) ? htmlspecialchars($data['system_sn']) : ''; ?>" disabled>
                        </td>
                        <td>
                            <label style="color: #FFF; font-weight: bold" for="seq_name"><?php echo $text['sequence']; ?>:</label>
                            <input style="color: #000" type="text" id="seq_name" name="seq_name" size="10" maxlength="15" value="" disabled>
                        </td>
                        <td>
                            <label style="color: #FFF; font-weight: bold" for="max_screw_count"><?php echo $text['screws']; ?>:</label>
                            <input style="color: #000; text-align: center" type="text" id="max_screw_count" name="max_screw_count" size="4" maxlength="5" value="" disabled>
                            <input style="color: #000; text-align: center" type="hidden" id="last_screw_count" name="last_screw_count" size="4" maxlength="5" value="" disabled>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="operation-setting">
                <div class="column">
                    <div class="item-target-torque w3-display-container">
                        <div class="w3-display-topmiddle w3-border-top w3-border-bottom w3-border-red">
                            <?php echo $text['final_torque']; ?>(<span id="fasten_status_unit_explain"></span>)
                        </div>
                        <div id="fasten_torque" class="w3-display-middle" style="font-size: 6vmin; margin: 5px 0;"></div>
                    </div>

                    <div id="fasten_status_bg" class="item-result w3-display-container">
                        <div class="w3-display-topmiddle w3-border-top w3-border-bottom w3-border-black">
                            <?php echo $text['final_result']; ?>
                        </div>
                        <div id="fasten_status_explain" class="w3-display-middle" style="font-size: 6vmin; margin: 5px 0"></div>
                    </div>
                </div>

                <div class="column">
                    <div class="item-targer-angle w3-display-container">
                        <div class="w3-display-topmiddle w3-border-top w3-border-bottom w3-border-red">
                            <?php echo $text['final_angle']; ?>
                        </div>
                        <div id="fasten_angle" class="w3-display-middle" style="font-size: 6vmin; margin: 5px 0"></div>
                    </div>

                    <div class="item-message w3-display-container">
                        <div class="w3-display-topmiddle w3-border-top w3-border-bottom w3-border-red">
                            <?php echo $text['final_message']; ?>
                        </div>
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

<script>
let lastDataVersion = 0;
let pollingActive = true;
let myChart = null;
let pollTimer = null;

/* =====================================================
 * 共用工具
 * ===================================================== */
function getLangSafe() {
    return (typeof getCookie === 'function'
        ? (getCookie('language') || 'zh-tw')
        : 'zh-tw'
    ).toLowerCase();
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

function setActiveChartButton(argument) {
    document.querySelectorAll('.btn-chart').forEach(btn => {
        btn.classList.toggle('active', btn.id === argument);
    });
}

function chartIdToMode(argument) {
    if (argument === 'angle_time') return 2;
    if (argument === 'rpm_time') return 3;
    if (argument === 'torque_angle') return 4;
    return 1;
}

function safeResizeChart(delay = 80) {
    setTimeout(function () {
        if (myChart && typeof myChart.resize === 'function') {
            myChart.resize();
        }
    }, delay);
}

/* =====================================================
 * chart 切換
 * ===================================================== */
function chart_type(argument) {
    setActiveChartButton(argument);

    const chart = chartIdToMode(argument);

    const url = new URL(window.location.href);
    url.searchParams.set('chart', chart);
    window.history.replaceState({}, '', url.toString());

    // 切圖時清掉版本，強制後端給最新圖
    lastDataVersion = 0;

    const sn = document.getElementById('system_sn').value || '--';
    fetchData('?url=Dashboards/get_new_data', sn, chart);
}

/* =====================================================
 * 統一曲線圖
 * ===================================================== */
const initialChartPayload = <?= json_encode($data['chart_payload'], JSON_UNESCAPED_UNICODE) ?>;
window.chartPayload = initialChartPayload;

function showWaitingOnChart() {
    if (!myChart) return;

    myChart.clear();
    myChart.setOption({
        animation: false,
        grid: {
            left: '10%',
            right: '6%',
            top: '14%',
            bottom: '14%'
        },
        xAxis: { show: false, type: 'category', data: [] },
        yAxis: { show: false, type: 'value' },
        series: [],
        graphic: {
            type: 'text',
            left: 'center',
            top: 'middle',
            style: {
                text: t('waiting_fasten'),
                fill: '#999',
                fontSize: 16,
                fontWeight: 400
            }
        }
    }, true);

    safeResizeChart(50);
}

function initChart() {
    const dom = document.getElementById('chart');
    if (!dom) return;

    // 防止重複 init
    if (myChart) {
        try { myChart.dispose(); } catch (e) {}
        myChart = null;
    }

    myChart = echarts.init(dom);

    console.log('[initChart] initialChartPayload =', initialChartPayload);
    console.log('[initChart] chart size =', dom.clientWidth, dom.clientHeight);

    const hasXAxis =
        initialChartPayload &&
        Array.isArray(initialChartPayload.xAxis) &&
        initialChartPayload.xAxis.length > 0;

    const hasSeries =
        initialChartPayload &&
        Array.isArray(initialChartPayload.series) &&
        initialChartPayload.series.length > 0;

    if (hasXAxis && hasSeries) {
        drawUnifiedChart(initialChartPayload);

        if (initialChartPayload.version !== undefined && initialChartPayload.version !== null) {
            lastDataVersion = initialChartPayload.version;
        }
    } else {
        showWaitingOnChart();
    }

    safeResizeChart(120);
    safeResizeChart(250);
}

function drawUnifiedChart(payload) {
    if (!myChart) return;

    console.log('[drawUnifiedChart] payload =', payload);

    const xAxisData = Array.isArray(payload?.xAxis) ? payload.xAxis : [];
    const rawSeries = Array.isArray(payload?.series) ? payload.series : [];

    if (xAxisData.length === 0 || rawSeries.length === 0) {
        console.warn('[drawUnifiedChart] empty payload, skip draw');
        showWaitingOnChart();
        return;
    }

    const series = rawSeries.map(s => ({
        name: s?.name || '',
        type: 'line',
        data: Array.isArray(s?.data) ? s.data : [],
        showSymbol: false,
        smooth: false,
        connectNulls: true,
        lineStyle: { width: 1.5 }
    }));

    const option = {
        animation: false,
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            show: false
        },
        grid: {
            left: '10%',
            right: '6%',
            top: '14%',
            bottom: '14%'
        },
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: xAxisData
        },
        yAxis: {
            type: 'value',
            scale: true
        },
        series: series
    };

    myChart.clear();
    myChart.setOption(option, true);

    safeResizeChart(50);
    safeResizeChart(150);
}

/* =====================================================
 * API
 * ===================================================== */
async function fetchData(url, system_sn, chart_mode) {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                system_sn: system_sn,
                chart_mode: chart_mode,
                last_version: lastDataVersion
            })
        });

        if (!response.ok) {
            throw new Error('HTTP ' + response.status);
        }

        const data = await response.json();
        console.log('[fetchData] API data =', data);
        updateDataOnPage(data);

    } catch (e) {
        console.error('API error:', e);
    }
}

function updateDataOnPage(data) {
    if (!data) return;

    const systemSnEl = document.getElementById('system_sn');
    const jobNameEl = document.getElementById('job_name');
    const seqNameEl = document.getElementById('seq_name');
    const maxScrewCountEl = document.getElementById('max_screw_count');
    const lastScrewCountEl = document.getElementById('last_screw_count');
    const fastenTorqueEl = document.getElementById('fasten_torque');
    const fastenAngleEl = document.getElementById('fasten_angle');
    const fastenStatusExplainEl = document.getElementById('fasten_status_explain');
    const fastenStatusUnitExplainEl = document.getElementById('fasten_status_unit_explain');
    const errorMassageExplanationEl = document.getElementById('error_massage_explanation');
    const fastenStatusBgEl = document.getElementById('fasten_status_bg');

    if (systemSnEl) {
        systemSnEl.value = data.system_sn || '--';
    }

    if (jobNameEl) {
        jobNameEl.value = (data.job_id ?? '--') + '/' + (data.jobs_count ?? '--');
    }

    if (seqNameEl) {
        seqNameEl.value = (data.seq_id ?? '--') + '/' + (data.seqs_count ?? '--');
    }

    if (maxScrewCountEl) {
        maxScrewCountEl.value = (data.last_screw_count ?? '--') + '/' + (data.max_screw_count ?? '--');
    }

    if (lastScrewCountEl) {
        lastScrewCountEl.value = data.last_screw_count ?? '--';
    }

    if (fastenTorqueEl) {
        fastenTorqueEl.innerText = data.fasten_torque || 'N/A';
    }

    if (fastenAngleEl) {
        fastenAngleEl.innerText = data.fasten_angle || 'N/A';
    }

    if (fastenStatusExplainEl) {
        fastenStatusExplainEl.innerText = data.fasten_status_explain || 'N/A';
    }

    if (fastenStatusUnitExplainEl) {
        fastenStatusUnitExplainEl.innerText = data.fasten_status_unit_explain || '';
    }

    if (errorMassageExplanationEl) {
        errorMassageExplanationEl.innerText = data.error_massage_explanation || '';
    }

    if (fastenStatusBgEl && data.fasten_status_bg) {
        fastenStatusBgEl.style.backgroundColor = data.fasten_status_bg;
    }

    // 只要 API 有圖就直接畫
    if (data.chart_payload) {
        console.log('[updateDataOnPage] chart_payload =', data.chart_payload);
        window.chartPayload = data.chart_payload;
        drawUnifiedChart(data.chart_payload);
    }

    // version 只做記錄
    if (data.data_version !== undefined && data.data_version !== null) {
        lastDataVersion = data.data_version;
    }
}

/* =====================================================
 * Polling
 * ===================================================== */
function startApiPolling(url = '?url=Dashboards/get_new_data', interval = 1000) {
    async function poll() {
        if (!pollingActive) return;

        const system_sn = document.getElementById('system_sn').value || '--';
        const chart_mode = new URLSearchParams(window.location.search).get('chart') || 1;

        await fetchData(url, system_sn, chart_mode);
        pollTimer = setTimeout(poll, interval);
    }

    if (pollTimer) {
        clearTimeout(pollTimer);
        pollTimer = null;
    }

    poll();
}

/* =====================================================
 * 初始化
 * ===================================================== */
window.addEventListener('load', function () {
    initChart();

    const mainContent = document.querySelector('.main-content');
    if (mainContent) {
        mainContent.scrollIntoView({ behavior: 'auto', block: 'start' });
    }

    // 延遲一點抓第一次，避免手機版 layout 尚未穩定
    setTimeout(function () {
        const initSn = document.getElementById('system_sn').value || '--';
        const initChartMode = new URLSearchParams(window.location.search).get('chart') || 1;
        fetchData('?url=Dashboards/get_new_data', initSn, initChartMode);
    }, 150);

    setTimeout(function () {
        startApiPolling();
    }, 300);
});

/* =====================================================
 * Resize / Orientation
 * ===================================================== */
window.addEventListener('resize', function () {
    safeResizeChart(30);
});

window.addEventListener('orientationchange', function () {
    safeResizeChart(200);
    safeResizeChart(400);
});
</script>
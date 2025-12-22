<?php
// 語系設定
switch ($_SESSION['language'] ?? '') {
    case 'zh-cn':
        $calendar_lang = 'zh';
        break;
    case 'zh-tw':
        $calendar_lang = 'zh_tw';
        break;
    default:
        $calendar_lang = '';
        break;
}


#顯示 表格
function renderTableRows($records, $unit_arr, $status_arr, $text) {
    foreach ($records as $row) {
        $status = $row['fasten_status'];

        // 狀態分類用 class
        if ($status == 7 || $status == 8) {
            $class = 'status-ng';
        } elseif ($status == 5 || $status == 6) {
            $class = 'status-warn';
        } else {
            $class = 'status-ok';
        }

        // job_name 處理
        $jobNameFull = $row['job_name'];
        $jobNameEscaped = htmlspecialchars($jobNameFull, ENT_QUOTES); // for title
        $jobNameShort = (mb_strlen($jobNameFull) > 15) 
            ? mb_substr($jobNameFull, 0, 15) . '***' 
            : $jobNameFull;

        // seq_name 處理
        $seqNameFull = $row['seq_name'];
        $seqNameEscaped = htmlspecialchars($seqNameFull, ENT_QUOTES);
        $seqNameShort = (mb_strlen($seqNameFull) > 15) 
            ? mb_substr($seqNameFull, 0, 15) . '***' 
            : $seqNameFull;

        // 單位轉換
        $unitKey = $unit_arr[$row['step_tor_unit']] ?? '';
        $unitText = $text[$unitKey] ?? '';

        echo "<tr>
              
                <td>{$row['fasten_torque']}</td>
              </tr>";
    }
}



?>

<div class="container-ms">
    <div class="w3-text-white w3-center">
        <table class="no-border">
            <tr id="header">
                <td width="100%"><h3><?php echo $text['data'];?></h3></td>
                <td><img src="./img/btn_home.png" style="margin-right: 10px"    onclick="location.href='?url=Dashboards'"></td>
            </tr>
        </table>
    </div>
    
    <div class="main-content">
        <div class="center-content">
            
            
            <div id="DataButtonMode">
                <div id="HistoryDisplay">
                     <!-- 三個資料區塊 -->
                     <?php
                    $tableConfigs = array(
                        ['id' => 'res_data_all', 'label' => $text['data_history_success'], 'data' => $data['res_data'], 'display' => 'block'],
                        ['id' => 'res_data_ok', 'label' => $text['data_history_success'], 'data' => $data['res_data_ok'], 'display' => 'none'],
                        ['id' => 'res_data_nok', 'label' => $text['data_history_fail'], 'data' => $data['res_data_nok'], 'display' => 'none']
                    );

                    foreach ($tableConfigs as $config){?>
                        <div class="table-container" id="<?php echo $config['id']; ?>" style="display: <?php echo $config['display']; ?>;">
                            <div style="font-weight: bold; font-size: 20px; padding-left: 1%"><?php echo $config['label']; ?></div>
                            <div class="scrollbar" id="style-data">
                                <table class="table w3-table w3-hoverable">
                                    <thead>
                                        <tr style="font-size: 16px; color: white;">
                                            <th><?php echo $text['torque']; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody id="<?php echo $config['id']; ?>_tbody" style="font-size: 16px; text-align: center;">
                                        <?php renderTableRows($config['data'], $data['unit_arr'], $data['status_arr'], $text); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } ?>                                                                                                       
                </div>
                
                <div id="ExportdataDisplay" style="display:none;">
                    <div class="data-export" style="background-color: #F2F1F1;">
                        <h2><?php echo $text['data_export'];?></h2>
                        <div class="row">
                            <div class="col-sm-6">
                                <div style="max-width: 450px;margin: auto;text-align: center;">
                                    <label for="start" style="font-size:20px;">📅 <?php echo $text['start_date'];?> :</label>
                                    <div class="mb-3">
                                        <input type="text" id="start_date" placeholder="Select datetime" class="form-control" style="background-color: #fff;display:none;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div style="max-width: 450px;margin: auto;text-align: center;">
                                    <label for="start" style="font-size:20px;">📅 <?php echo $text['end_date'];?> :</label>
                                    <div class="mb-3">
                                        <input type="text" id="end_date" placeholder="Select datetime" class="form-control" style="background-color: #fff;display: none;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row" style="padding-left: 30%">
                            <div class="col-4 t1"><?php echo $text['Export Format'];?>:</div>
                            <div class="col t2">
                                <div class="form-check form-check-inline">
                                    <input class="t2 form-check-input" type="radio" name="export-option" id="export-csv" value="0" style="zoom:1.2; vertical-align: middle" checked>
                                    <label class="t2 form-check-label" for="export-csv" style="font-weight: normal">CSV</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="t2 form-check-input" type="radio" name="export-option" id="export-zip" value="1" style="zoom:1.2; vertical-align: middle">
                                    <label class="t2 form-check-label" for="export-zip" style="font-weight: normal">ZIP</label>
                                </div>
                            </div>    
                        </div>
                        
                        <div style="text-align: center;margin-top: 20px;">
                            <button class="btn-export w3-button w3-border w3-round" onclick="exportData()"><?php echo $text['data_export'];?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    

</div>


<script>
document.addEventListener("DOMContentLoaded", function () {
    initTooltip();        // 初始化 tooltip
    initDatePickers();    // 初始化日期選擇器
    fetchRealTimeData();  // 初始載入
    setupDropdown();      // dropdown 切換

    // 每 2 秒更新資料
    setInterval(() => {
        fetchRealTimeData(currentMode);
    }, 2000);
});

// ✅ Tooltip 初始化
function initTooltip() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (el) {
        new bootstrap.Tooltip(el);
    });
}

// ✅ 日期選擇器初始化
function initDatePickers() {
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');

    const startStr = `${yyyy}-${mm}-${dd} 00:00:00`;
    const endStr   = `${yyyy}-${mm}-${dd} 23:59:59`;

    document.getElementById("start_date").value = startStr;
    document.getElementById("end_date").value = endStr;

    flatpickr("#start_date", {
        enableTime: true,
        static: true,
        inline: true,
        dateFormat: "Y-m-d H:i:S",
        defaultDate: startStr,
        locale: "<?php echo $calendar_lang; ?>",
        disableMobile: "true",
        maxDate: `${yyyy}-12-31`,
        time_24hr: true
    });

    flatpickr("#end_date", {
        enableTime: true,
        enableSeconds: true,
        static: true,
        inline: true,
        dateFormat: "Y-m-d H:i:S",
        defaultDate: endStr,
        locale: "<?php echo $calendar_lang; ?>",
        disableMobile: "true",
        maxDate: `${yyyy}-12-31`,
        time_24hr: true
    });
}

// ✅ 切換下拉選單處理
function setupDropdown() {
    document.getElementById('data_select').addEventListener('change', function () {
        currentMode = this.value;
        DataMode();
        fetchRealTimeData(currentMode);
    });
}

// ✅ 切換顯示資料區塊
function DataMode() {
    const mode = document.getElementById("data_select").value;
    const map = {
        'ALL': 'res_data_all',
        'OK': 'res_data_ok',
        'NOK': 'res_data_nok'
    };

    ['res_data_all', 'res_data_ok', 'res_data_nok'].forEach(id => {
        document.getElementById(id).style.display = 'none';
    });

    const target = map[mode];
    if (target) {
        document.getElementById(target).style.display = 'block';
    }
}

// ✅ 取得即時資料
function fetchRealTimeData(mode = currentMode) {
    const formData = new FormData();
    formData.append('mode', mode);

    const baseURL = `${window.location.protocol}//${window.location.hostname}/tccidas/public/?url=Data/getreal_time_data`;

    fetch(baseURL, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(result => {
        if (result.success) {
            updateTable(mode, result.records, result.unit_arr, result.status_arr);
        } else {
            console.warn(result.msg);
        }
    })
    .catch(error => console.error('資料載入失敗', error));
}

// ✅ 更新資料表格
function updateTable(mode, records, unit_arr, status_arr) {
    const tbodyId = `res_data_${mode.toLowerCase()}_tbody`;
    const tbody = document.getElementById(tbodyId);
    if (!tbody) return;

    tbody.innerHTML = ''; // 清空原內容

    records.forEach(row => {
        let status = row.fasten_status;
        let className = 'status-ok';
        if (status == 7 || status == 8) className = 'status-ng';
        else if (status == 5 || status == 6) className = 'status-warn';

        // 處理 job_name 與 seq_name tooltip
        const jobNameFull = row.job_name || '';
        const jobNameShort = jobNameFull.length > 15 ? jobNameFull.slice(0, 15) + '***' : jobNameFull;

        const seqNameFull = row.seq_name || '';
        const seqNameShort = seqNameFull.length > 15 ? seqNameFull.slice(0, 15) + '***' : seqNameFull;

        const html = `
            <tr>
                <td>${row.fasten_torque}</td>
            </tr>`;
        tbody.insertAdjacentHTML('beforeend', html);
    });

    initTooltip(); // 重新啟用 tooltip
}

// ✅ 預設模式
let currentMode = 'ALL';
</script>

</body>

</html>



<?php

class Data extends Controller
{
    private $DataModel;
    private $MiscellaneousModel;

    // 建構子：初始化 Model
    public function __construct()
    {
        $this->DataModel = $this->model('Datas');                 // 資料查詢模型
        $this->MiscellaneousModel = $this->model('Miscellaneous'); // 含單位、狀態等通用設定
    }

    // 首頁顯示：載入資料頁面
    public function index()
    {
        $type = 'ALL';
        $isMobile = $this->isMobileCheck(); // 判斷是否為手機裝置

        // 取得目前年度資料庫路徑（Linux專用）
        if (PHP_OS_FAMILY === 'Linux') {
            $db_path = "/var/www/html/database/data" . date('Y') . ".db";
            $db_exists = file_exists($db_path);

            if ($db_exists) {
                // 若資料庫存在則撈取資料
                $res_data     = $this->DataModel->getData('ALL');
                $res_data_ok  = $this->DataModel->getData('OK');
                $res_data_nok = $this->DataModel->getData('NOK');
            } else {
                // 否則回傳空陣列
                $res_data     = [];
                $res_data_ok  = [];
                $res_data_nok = [];
            }
        } else {
            // 非 Linux 直接查詢（假設 DB 一定存在）
            $res_data     = $this->DataModel->getData('ALL');
            $res_data_ok  = $this->DataModel->getData('OK');
            $res_data_nok = $this->DataModel->getData('NOK');
            $db_exists = '';
            $db_path = '';
        }

        // 查詢單位與狀態、裝置資訊
        $unit_arr    = $this->MiscellaneousModel->details('torque_unit');
        $status_arr  = $this->MiscellaneousModel->details('status');
        $device_info = $this->Device_Info();

        // 組合資料傳給 view
        $data = array(
            'isMobile'      => $isMobile,
            'res_data'      => $res_data,
            'res_data_ok'   => $res_data_ok,
            'res_data_nok'  => $res_data_nok,
            'device_info'   => $device_info,
            'unit_arr'      => $unit_arr,
            'status_arr'    => $status_arr,
            'db_exists'     => $db_exists,
            'db_path'       => $db_path
        );

        $this->view('data/index', $data);
    }

    // AJAX 請求：取得指定模式資料（ALL / OK / NOK）
    public function search_info()
    {
        $unit_arr = $this->MiscellaneousModel->details('torque_unit');
        $status_arr = $this->MiscellaneousModel->details('status');

        $mode = $_POST['mode'] ?? null;

        if ($mode) {
            $res_data = $this->DataModel->getData($mode);
            if (!empty($res_data)) {
                $info_data = '';
                foreach ($res_data as $ve) {
                    // 根據 fasten_status 決定顏色
                    if ($ve['fasten_status'] == 7 || $ve['fasten_status'] == 8) {
                        $style = 'style="background: red"';
                    } elseif ($ve['fasten_status'] == 5 || $ve['fasten_status'] == 6) {
                        $style = 'style="background: #FFEF62"';
                    } else {
                        $style = 'style="background: green"';
                    }

                    
                    $info_data .= '<tr>';
                    $info_data .= "<td>{$ve['system_sn']}</td>";
                    $info_data .= "<td>{$ve['data_time']}</td>";
                    $info_data .= "<td>{$ve['job_name']}</td>";
                    $info_data .= "<td>{$ve['sequence_name']}</td>";
                    $info_data .= "<td>{$ve['fasten_torque']}</td>";
                    $info_data .= "<td id='{$unit_arr[$ve['torque_unit']]}'>{$unit_arr[$ve['torque_unit']]}</td>";
                    $info_data .= "<td>{$ve['fasten_angle']}</td>";
                    $info_data .= "<td>{$ve['total_screw_count']}</td>";
                    $info_data .= "<td>{$ve['last_screw_count']}</td>";
                    $info_data .= "<td $style>{$status_arr[$ve['fasten_status']]}</td>";
                    $info_data .= '</tr>';
                }

                echo $info_data; // 回傳給前端顯示
            }
        }
    }

    // 匯出資料：依據日期範圍輸出 CSV 或 ZIP
    public function exportData()
    {
        $input_check = true;

        // 日期驗證與格式化
        try {
            $start_date = new DateTime($_POST['start_date'] ?? '');
            $end_date = new DateTime($_POST['end_date'] ?? '');
            $start_date = $start_date->format('Ymd H:i:s');
            $end_date = $end_date->format('Ymd H:i:s');
            $end_date = str_replace("00:00:00", "23:59:59", $end_date); // 時間結尾補滿
        } catch (Exception $e) {
            $input_check = false;
        }

        // 匯出格式：0=csv, 1=zip
        $expert_val = $_POST['expert_val'] ?? "0";

        if ($input_check) {
            $unit_arr = $this->MiscellaneousModel->details('torque_unit');
            $status_arr = $this->MiscellaneousModel->details('status');

            // 資料範圍查詢
            $dataset = $this->DataModel->get_range_data($start_date, $end_date);
            $dataset = array_slice($dataset, 0, 10000); 

            // 補充 torque_unit / fasten_status 對應文字
            foreach ($dataset as $key => $val) {
                $dataset[$key]['torque_unit'] = $unit_arr[$val['step_tor_unit']] ?? '';
                $dataset[$key]['fasten_status'] = $status_arr[$val['fasten_status']] ?? '';
            }

            if ($dataset && $expert_val == "0") {
                // 匯出為 CSV
                $csv_headers = array_keys($dataset[0]);
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=tcc_data.csv');

                $output = fopen('php://output', 'w');
                fputcsv($output, $csv_headers);
                foreach ($dataset as $row) {
                    fputcsv($output, $row);
                }
                fclose($output);
                exit();

            } elseif ($dataset && $expert_val == "1") {
                // 匯出 ZIP
                $csv_content = implode(',', array_keys($dataset[0])) . "\n";
                foreach ($dataset as $row) {
                    $csv_content .= implode(',', $row) . "\n";
                }

                $zip = new ZipArchive();
                $zip_filename = tempnam(sys_get_temp_dir(), 'tcc_data') . '.zip';

                if ($zip->open($zip_filename, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
                    $zip->addFromString("data.csv", $csv_content);
                    $zip->close();

                    header('Content-Type: application/zip');
                    header('Content-Disposition: attachment; filename=tcc_data.zip');
                    header('Content-Length: ' . filesize($zip_filename));
                    readfile($zip_filename);
                    unlink($zip_filename);
                    exit();
                } else {
                    echo "無法建立 ZIP 檔案";
                }
            }
        } else {
            echo "輸入參數不正確";
        }
    }

    // 取得最新資料（即時刷新）
    public function getreal_time_data()
    {
        $mode = $_POST['mode'] ?? 'ALL';
        $db_path = "/var/www/html/database/data" . date('Y') . ".db";

        if (!file_exists($db_path)) {
            echo json_encode(['success' => false, 'msg' => "資料庫不存在"]);
            return;
        }

        $res_data = $this->DataModel->getData($mode);
        $unit_arr = $this->MiscellaneousModel->details('torque_unit');
        $status_arr = $this->MiscellaneousModel->details('status');

        echo json_encode([
            'success' => true,
            'records' => $res_data,
            'unit_arr' => $unit_arr,
            'status_arr' => $status_arr
        ]);
    }
}
?>

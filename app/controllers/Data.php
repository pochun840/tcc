<?php

class Data extends Controller
{
    // 在建構子中將 Post 物件（Model）實例化
    public function __construct()
    {
        $this->DataModel = $this->model('Datas');
        $this->MiscellaneousModel = $this->model('Miscellaneous');
    }

    // 取得所有Jobs
    public function index(){
        
        $type = 'ALL';
        $isMobile = $this->isMobileCheck();

        // 取得當前年份
        if (PHP_OS_FAMILY === 'Linux') {
            $db_path = "/var/www/html/database/data".date('Y').".db";

            // 檢查資料庫是否存在
            $db_exists = file_exists($db_path);
    
            if ($db_exists) {
                $res_data     = $this->DataModel->getData('ALL');
                $res_data_ok  = $this->DataModel->getData('OK');
                $res_data_nok = $this->DataModel->getData('NOK');
            } else {
                $res_data     = [];
                $res_data_ok  = [];
                $res_data_nok = [];
            }

        }else{
            $res_data     = $this->DataModel->getData('ALL');
            $res_data_ok  = $this->DataModel->getData('OK');
            $res_data_nok = $this->DataModel->getData('NOK');
            $db_exists = '';
            $db_path = '';
        }
      

        $unit_arr   = $this->MiscellaneousModel->details('torque_unit');
        $status_arr = $this->MiscellaneousModel->details('status');
        $device_info = $this->Device_Info();

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

    public function search_info() {
        $unit_arr = $this->MiscellaneousModel->details('torque_unit');
        $status_arr = $this->MiscellaneousModel->details('status');
    
        $input_check = true;
        if (!empty($_POST['mode']) && isset($_POST['mode'])) {
            $mode = $_POST['mode'];
        } else {
            $input_check = false;
        }
    
        if ($input_check) {
            $res_data = $this->DataModel->getData($mode);
            if (!empty($res_data)) {
                $info_data = '';
                foreach ($res_data as $ve) {
                    if ($ve['fasten_status'] == 7 || $ve['fasten_status'] == 8) {
                        $style = 'style="background: red"';
                    } else if ($ve['fasten_status'] == 5 || $ve['fasten_status'] == 6) {
                        $style = 'style="background: #FFEF62"';
                    } else {
                        $style = 'style="background: green"';
                    }
    
                    $info_data .= '<tr>';
                    $info_data .= "<td>".$ve['system_sn']."</td>";
                    $info_data .= "<td>".$ve['data_time']."</td>";
                    $info_data .= "<td>".$ve['job_name']."</td>";
                    $info_data .= "<td>".$ve['sequence_name']."</td>";
                    $info_data .= "<td>".$ve['fasten_torque']."</td>";
                    $info_data .= "<td id='".$unit_arr[$ve['torque_unit']]."'>".$unit_arr[$ve['torque_unit']]."</td>";
                    $info_data .= "<td>".$ve['fasten_angle']."</td>";
                    $info_data .= "<td>".$ve['total_screw_count']."</td>";
                    $info_data .= "<td>".$ve['last_screw_count']."</td>";
                    $info_data .= "<td $style>".$status_arr[$ve['fasten_status']]."</td>";
                    $info_data .= '</tr>';
                }
    
                // 將所有資料一次性輸出
                echo $info_data;
            }
        }
    }
    

   
    public function exportData() {
        $input_check = true;
    
        // 檢查開始日期
        if (!empty($_POST['start_date']) && isset($_POST['start_date'])) {
            // 使用 DateTime 類別來解析並格式化日期
            try {
                $start_date = new DateTime($_POST['start_date']);
                $start_date = $start_date->format('Ymd H:i:s'); // 格式化為 'Y-m-d H:i:s'
            } catch (Exception $e) {
                $input_check = false;  // 如果日期格式錯誤，標示為無效
            }
        } else {
            $input_check = false;
        }
    
        // 檢查結束日期
        if (!empty($_POST['end_date']) && isset($_POST['end_date'])) {
            try {
                $end_date = new DateTime($_POST['end_date']);
                $end_date = $end_date->format('Ymd H:i:s');  // 格式化為 'Y-m-d H:i:s'
                // 將時間部分設置為 23:59:59
                $end_date = str_replace("00:00:00", "23:59:59", $end_date);
            } catch (Exception $e) {
                $input_check = false;  // 如果日期格式錯誤，標示為無效
            }
        } else {
            $input_check = false;
        }
    
    
    
        // 確認是否有選擇類型
        $expert_val = isset($_POST['expert_val']) ? $_POST['expert_val'] : "0";
    
        if ($input_check) {
            $unit_arr = $this->MiscellaneousModel->details('torque_unit');
            $status_arr = $this->MiscellaneousModel->details('status');
    
            // 使用處理過的日期範圍來取得資料
            $dataset = $this->DataModel->get_range_data($start_date, $end_date);
    
            // 取前 10000 條資料
            $dataset = array_slice($dataset, 0, 10000);
    
            // 給每一筆資料加上 torque_unit 和 fasten_status 的描述
            foreach ($dataset as $key => $val) {
                $dataset[$key]['torque_unit'] = $unit_arr[$val['step_tor_unit']];
                $dataset[$key]['fasten_status'] = $status_arr[$val['fasten_status']];
            }
    
            // 將資料導出為 CSV 或 ZIP 文件
            if ($dataset && $expert_val == "0") {
                // 導出為 CSV 格式
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
                // 導出為 CSV 並壓縮成 ZIP 格式
                $csv_content = '';
                $csv_headers = array_keys($dataset[0]);
                $csv_content .= implode(',', $csv_headers) . "\n";
    
                foreach ($dataset as $row) {
                    $csv_content .= implode(',', $row) . "\n";
                }
    
                // 創建 ZIP 文件並將 CSV 文件放入其中
                $zip = new ZipArchive();
                $zip_filename = tempnam(sys_get_temp_dir(), 'tcc_data') . '.zip';
    
                if ($zip->open($zip_filename, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
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
    
    
}
?>
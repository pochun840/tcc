<?php

class Dashboards extends Controller
{
    private $DashboardModel;
    private $AdminModel;
    private $SettingModel;
    private $MiscellaneousModel;
    private $DataModel;
    private $jobModel;
    private $sequenceModel;

    // 在建構子中將 Post 物件（Model）實例化
    public function __construct()
    {
        $this->DashboardModel = $this->model('Dashboard');
        $this->AdminModel = $this->model('Admin');
        $this->MiscellaneousModel = $this->model('Miscellaneous');
        $this->DataModel = $this->model('Datas');
        $this->SettingModel = $this->model('Setting');
        $this->jobModel = $this->model('Job');
        $this->sequenceModel = $this->model('Sequence');

    }

    // 取得所有Jobs
    public function index(){

        
        $isMobile = $this->isMobileCheck();
        $agent_type = $this->AdminModel->Get_Das_Config('agent_type');
        $device_info = $this->Device_Info();

        $iDas_Vesion = $this->AdminModel->Get_Das_Config('idas_version');
        $idas_online_version = $this->AdminModel->Get_Das_Config('idas_online_version');

        $data = [
            'isMobile' => $isMobile,
            'agent_type' => $agent_type,
            'device_info' => $device_info,
            'iDas_Vesion' => $iDas_Vesion,
            'idas_online_version' =>$idas_online_version
        ];

    
        if($isMobile){
            $this->view('dashboards/index_m', $data);
        }else{
            $this->view('dashboards/index', $data);
        }

    }

    // operation即時面板
    public function operation(){

        $isMobile = $this->isMobileCheck();

        $status_arr = $this->MiscellaneousModel->details('status');
        $unit_arr   = $this->MiscellaneousModel->details('torque_unit');

        // 控制器扭力單位
        $res_device = $this->SettingModel->GetControllerInfo();
        $step_torque_unit = !empty($res_device)
            ? (int)$res_device['device_torque_unit']
            : 0;

        // 最新一筆鎖附資料
        $first_data = $this->get_current_data();

        // 同步 CSV（給 chart=4 用）
        $this->auto_fix_and_sync_csv();
        $this->cleanCsvKeepLast10Core();

        // 整理狀態顯示
        if(!empty($first_data)){

            $first_data['status_explain'] =
                $status_arr[$first_data['fasten_status']] ?? '';

            if ($first_data['fasten_status'] == "4") {
                $first_data['fasten_status_bg'] = 'green';
            } elseif ($first_data['fasten_status'] == "5" || $first_data['fasten_status'] == "6") {
                $first_data['fasten_status_bg'] = '#FFCC00';
            } else {
                $first_data['fasten_status_bg'] = 'red';
            }

            $step_tor_unit_tmp = (int)$first_data['step_tor_unit'];
            if ($step_tor_unit_tmp == $step_torque_unit) {
                $first_data['status_unit_explain'] =
                    $unit_arr[$first_data['step_tor_unit']] ?? '';
            } else {
                $first_data['status_unit_explain'] =
                    $unit_arr[$step_torque_unit] ?? '';
            }
        }

        // chart mode（只允許 1~4）
        $chart_mode = isset($_GET['chart']) ? (int)$_GET['chart'] : 1;
        if ($chart_mode < 1 || $chart_mode > 4) {
            $chart_mode = 1;
        }

        // chart menu / title
        $chart_menu_arr = $this->MiscellaneousModel->details('chart_menu');
        $chart_mode_arr = $this->MiscellaneousModel->details('chart_mode');
        $echart_name    = explode("/", $chart_mode_arr[$chart_mode]);

        // -----------------------------
        // chart 資料（1~3 用）
        // -----------------------------
        $temp_chart = [];

        if ($chart_mode != 4) {

            // X 軸資料
            $x_val = $this->DashboardModel->get_csv_first_column($chart_mode);
            if (!empty($x_val)) {
                $x_val = array_slice($x_val, 1);
            }

            // CSV 資料
            $csvdata_arr = $this->DashboardModel->get_info($chart_mode);

            if (!empty($csvdata_arr)) {

                $unit_name = $this->MiscellaneousModel->get_unit_name_by_index($step_torque_unit);

                $chart_temp = $this->MiscellaneousModel->batch_convert_grouped_by_unit_chart($csvdata_arr, 1);

                $csvdata_arr = $chart_temp[$unit_name] ?? [];
            }

            if (!empty($csvdata_arr)) {

                // 移除表頭
                $csvdata_arr = array_slice($csvdata_arr, 1);

                $temp_chart = $this->ChartData(
                    $chart_mode,
                    $csvdata_arr,
                    $chart_mode,
                    $x_val
                );
            }
        }

        // 若無鎖附資料，圖表清空
        if (empty($first_data)) {
            $temp_chart = [];
        }

        // -----------------------------
        // chart=4 專用：最新 CSV
        // -----------------------------
        $latest_csv = '';
        if ($chart_mode == 4) {
            $latest_csv = $this->getLatestCsvFromPublicFtp();
        }

        // view data
        $data = [
            'isMobile'       => $isMobile,
            'chart_info'     => $temp_chart,   // chart 1~3 用
            'echart_name'    => $echart_name,
            'chart_mode'     => $chart_mode,
            'chart_menu_arr'=> $chart_menu_arr,
            'latest_csv'     => $latest_csv    // chart=4 CSV-only
        ];

        if ($isMobile) {
            $this->view('dashboards/operation_m', $data);
        } else {
            $this->view('dashboards/operation', $data);
        }
    }



    public function getLatestCsvFromPublicFtp(

        string $dir = '/var/www/html/idas/public/ftp'
    ): string {

        if (!is_dir($dir)) return '';

        $files = glob(rtrim($dir, '/') . '/*.csv');
        if (empty($files)) return '';

        usort($files, function ($a, $b) {
            if (
                preg_match('/DATALOG_(\d{14})_/', basename($a), $ma) &&
                preg_match('/DATALOG_(\d{14})_/', basename($b), $mb)
            ) {
                return $mb[1] <=> $ma[1];
            }
            return filemtime($b) <=> filemtime($a);
        });

        return basename($files[0]);
    }


    public function get_current_data(){

        $status_arr = $this->MiscellaneousModel->details('status');
        $unit_arr   = $this->MiscellaneousModel->details('torque_unit');

        $current_data = $this->DataModel->get_operation_info(); 

        return $current_data;
    
    }



    public function get_new_data() {

        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }

        $inputData = json_decode(file_get_contents('php://input'), true);

        $system_sn = isset($inputData['system_sn']) ? trim($inputData['system_sn']) : '';
        $chart_mode = isset($inputData['chart_mode']) ? (int)$inputData['chart_mode'] : 1;

        $status_arr = $this->MiscellaneousModel->details('status');
        $unit_arr   = $this->MiscellaneousModel->details('torque_unit');
        
    
   
    
        // 根據 system_sn 取得最新資料
        $first_data = $this->DataModel->get_new_info($system_sn); 
    
        if (!empty($first_data)) {
            // 整理狀態說明與背景顏色
            $first_data['fasten_status_explain'] = $status_arr[$first_data['fasten_status']] ?? '';
    
            switch ($first_data['fasten_status']) {
                case "4":
                    $first_data['fasten_status_bg'] = 'green';
                    break;
                case "5":
                case "6":
                    $first_data['fasten_status_bg'] = '#FFCC00';
                    break;
                default:
                    $first_data['fasten_status_bg'] = 'red';
                    break;
            }
    

            //控制器的扭力單位
            $res_device = $this->SettingModel->GetControllerInfo();
            if(!empty($res_device)){
                $step_torque_unit = (int)$res_device['device_torque_unit'];  
            }

            $step_tor_unit_tmp = (int)$first_data['step_tor_unit'];
            
            if($step_tor_unit_tmp == $first_data['fasten_status']){
                $flag = "Y";
                $first_data['fasten_status_unit_explain'] = $unit_arr[$first_data['step_tor_unit']];
            }else{
                $flag = "N";
                $first_data['fasten_status_unit_explain'] = $unit_arr[$step_torque_unit];

                //扭力單位換算
                $res_device = $this->SettingModel->GetControllerInfo();
                $step_torque_unit = (int)$res_device['device_torque_unit']; // ex: 0~4
                $unit_name       = $this->MiscellaneousModel->get_unit_name_by_index($step_torque_unit); //取得扭力單位的中文名稱
                $temp_tor  = $this->MiscellaneousModel->convert_all_torque_units($first_data['fasten_torque'], 1); // 1 = N.m
                $first_data['fasten_torque'] = $temp_tor[$unit_name]; 
                           
            }

            $first_data['error_massage_explanation'] = $error_message['ERR_'.$first_data['error_message']];

            //取得目前的job數量 
            $jobs_count  = $this->jobModel->countjob();
            if(!empty($jobs_count)){
                 $first_data['jobs_count'] = $jobs_count;
            }
            //透過job_id 去找出對應的seq數量
            $seqs_count = $this->sequenceModel->countseq($first_data['job_id']);
            if(!empty($seqs_count)){
                $first_data['seqs_count'] = $seqs_count;
            }

        }


        #即時曲線圖
        if(!empty($first_data)){
           
            $chart_data = $this->live_line_chart($chart_mode);
            $first_data['chart_data'] = $chart_data;
        }
        
        echo json_encode($first_data);

    }



    public function live_line_chart($chart_mode) {

        $x_val = $this->DashboardModel->get_csv_first_column($chart_mode);
        if (!empty($x_val)) {
            $x_val = array_slice($x_val, 1);
        }
    
        $chart_mode_arr = $this->MiscellaneousModel->details('chart_mode');
        $echart_name = explode("/", $chart_mode_arr[$chart_mode]);
    
        $csvdata_arr = $this->DashboardModel->get_info($chart_mode);

        if($chart_mode != 2 && $chart_mode != 3) {
            $res_device = $this->SettingModel->GetControllerInfo();
            $step_torque_unit = (int)$res_device['device_torque_unit']; // 0~4
            $unit_name = $this->MiscellaneousModel->get_unit_name_by_index($step_torque_unit); // ex: N.m
            $temp_tor = $this->MiscellaneousModel->batch_convert_grouped_by_unit_chart($csvdata_arr, 1); // N.m to all
            $csvdata_arr = $temp_tor[$unit_name];
        }

    
        if (!empty($csvdata_arr)) {
            if ($chart_mode != 5) {
                $csvdata_arr = array_slice($csvdata_arr, 1);
            } else {
                array_shift($csvdata_arr['torque']);
                array_shift($csvdata_arr['rpm']);
            }
        }

        //去除重複
        $x_val  = array_unique($x_val);
        
        // 返回曲線圖數據
        return [
            'x_val' => $x_val ?? [],
            'y_val' => $csvdata_arr ?? [],
            'x_title' => $echart_name[1] ?? 'Time',
            'y_title' => $echart_name[0] ?? 'Value'
        ];
    }
    
    


    public function change_language(){

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if( !empty($_POST['language']) && isset($_POST['language'])  ){
            $language = $_POST['language'];
        }else{ 
            $input_check = false; 
            $error_message .= "language,";
        }
        $_SESSION['language'] = $language;

        $response = array(
            'language' => $language,
            'result' => true,
        );
        echo json_encode($response);
    

    }



    private function ChartData($chat_mode, $csvdata_arr, $chat_mode_arr,$x_val){
        $chart_info = array();
   
           
        if(($chat_mode == "1" || $chat_mode == "3" || $chat_mode == "4")){
            
            $chart_info['y_val'] = json_encode($csvdata_arr);

            $temp_val = json_decode($chart_info['y_val']); 

            $chart_info['max'] = max($temp_val);
            $chart_info['min'] = min($temp_val);

        }else{
            $chart_info['y_val'] = json_encode($csvdata_arr);
            $chart_info['max'] = max($csvdata_arr);
            $chart_info['min'] = min($csvdata_arr);
        }
        

        // 去除 .0 的部分
        $x_val = array_map(function($value) {
            return ($value == (int)$value) ? (int)$value : $value;
        }, $x_val);

        $x_val = array_unique($x_val);

        $chart_info['x_val'] = json_encode($x_val);


        return $chart_info;
    }

    public function auto_fix_and_sync_csv(){

        $sourceDir = '/mnt/ramdisk/ftp';
        $targetDir = '/var/www/html/idas/public/ftp';

        @mkdir($targetDir, 0777, true);
        $logFile = $targetDir . '/sync_debug.log';
        $fp = @fopen($logFile, "a");

        $log = function($msg) use ($fp) {
            if ($fp) fwrite($fp, "[" . date('Y-m-d H:i:s') . "] $msg\n");
        };

        $log("==== auto_fix_and_sync_csv START ====");

        // Step 1：找來源 CSV
        $files = glob($sourceDir . '/*.csv');
        if (!$files) {
            $log("No source CSV found");
            return;
        }

        // ★ Step 1-1：依 DATALOG_YYYYMMDDHHIISS 排序（DESC）
        usort($files, function($a, $b) {

            // 取出時間戳
            preg_match('/DATALOG_(\d{14})_/', basename($a), $ma);
            preg_match('/DATALOG_(\d{14})_/', basename($b), $mb);

            $ta = $ma[1] ?? '0';
            $tb = $mb[1] ?? '0';

            return $tb <=> $ta; // 時間 DESC
        });

        // 最新 CSV
        $src  = $files[0];
        $name = basename($src);

        $log("Latest CSV (by timestamp) = $name");

        // 目標檔案
        $dest = $targetDir . "/" . $name;
        $temp = $targetDir . "/." . $name . ".tmp";

        // Step 2：原子 copy
        if (!file_exists($dest) || filesize($src) !== filesize($dest)) {

            $log("Copying using temp…");

            if (!@copy($src, $temp)) {
                $log("ERROR: temp copy failed");
                return;
            }

            if (!@rename($temp, $dest)) {
                $log("ERROR: rename failed");
                return;
            }

            $log("Copied OK → $name");

        } else {
            $log("No change, skip copy");
        }

        // Step 3：只保留最新一個 CSV（依時間戳）
        $targetFiles = glob($targetDir . '/*.csv');

        if ($targetFiles && count($targetFiles) > 1) {

            usort($targetFiles, function($a, $b) {

                preg_match('/DATALOG_(\d{14})_/', basename($a), $ma);
                preg_match('/DATALOG_(\d{14})_/', basename($b), $mb);

                $ta = $ma[1] ?? '0';
                $tb = $mb[1] ?? '0';

                return $tb <=> $ta;
            });

            // 保留最新，其餘刪除
            $delete = array_slice($targetFiles, 1);

            foreach ($delete as $del) {
                @unlink($del);
                $log("Deleted old CSV: " . basename($del));
            }
        }

        $log("==== auto_fix_and_sync_csv END ====");
        if ($fp) fclose($fp);
    }



    public function cleanCsvKeepLast10Core(){

        $dir = '/var/www/html/idas/public/ftp';

        if (!is_dir($dir)) {
            return [false, "目錄不存在：{$dir}"];
        }

        // 抓所有 .csv 檔
        $pattern = rtrim($dir, '/') . '/*.csv';
        $files = glob($pattern);

        if (!$files || count($files) <= 1) {
            // 沒有或少於等於 10 筆，不需要刪
            return [true, "目前 CSV 數量 <= 10，無需刪除"];
        }

        // 依照「最後修改時間」由新到舊排序
        usort($files, function ($a, $b) {
            $ma = @filemtime($a) ?: 0;
            $mb = @filemtime($b) ?: 0;
            // 新的在前面
            return $mb <=> $ma;
        });

        // 保留前 1 筆，其餘刪除
        $keep   = array_slice($files, 0, 1);
        $delete = array_slice($files, 1);

        $deleted = [];
        $failed  = [];

        foreach ($delete as $file) {
            if (@is_file($file)) {
                if (@unlink($file)) {
                    $deleted[] = basename($file);
                } else {
                    $failed[] = basename($file);
                }
            }
        }

        $msg = "總共檔案數：" . count($files) .
            "，保留：" . count($keep) .
            "，刪除：" . count($deleted);

        if ($failed) {
            $msg .= "，刪除失敗：" . implode(',', $failed);
            return [false, $msg];
        }

        return [true, $msg];
    }


    
}
?>
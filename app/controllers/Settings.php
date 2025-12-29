<?php

class Settings extends Controller
{
    private $SettingModel;
    private $AdminModel;
    private $ToolModel;
    private $MiscellaneousModel;
    private $LoginModel;
    private $DataModel;

    // 在建構子中將 Post 物件（Model）實例化
    public function __construct()
    {
        $this->SettingModel = $this->model('Setting');
        $this->AdminModel = $this->model('Admin');
        $this->ToolModel = $this->model('Tool');
        $this->MiscellaneousModel = $this->model('Miscellaneous');
        $this->LoginModel = $this->model('Login');
        $this->DataModel = $this->model('Datas');
    }

    // 取得所有info
    public function index(){

        $isMobile = $this->isMobileCheck();

        $lang = $this->MiscellaneousModel->details('lang');
        $controller_info = $this->SettingModel->GetControllerInfo();
        $active_session = $this->AdminModel->GetActiveSession();
        $iDas_Vesion = $this->AdminModel->Get_Das_Config('idas_version');
        $max_user = $this->AdminModel->Get_Das_Config('max_concurrent_users');
        $agent_server_ip = $this->AdminModel->Get_Das_Config('agent_server_ip');
        $agent_type = $this->AdminModel->Get_Das_Config('agent_type');
        $job_list = $this->SettingModel->get_job_list();
        $barcodes = $this->GetBarcodes();
        $unit_arr = $this->MiscellaneousModel->details('torque_unit');
        $barcode_mode = $this->MiscellaneousModel->details('barcode_mode');


        $disk_usage_percent = $this->SettingModel->system_storage();
        $history_year_arr = $this->get_history_year();

        $data = array(
            'lang_arr'        => $lang,
            'controller_info' => $controller_info,
            'active_session'  => $active_session,
            'iDas_Vesion'     => $iDas_Vesion,
            'max_user'        => $max_user,
            'agent_server_ip' => $agent_server_ip,
            'agent_type'      => $agent_type,
            'job_list'        => $job_list,
            'barcodes'        => $barcodes,
            'unit_arr'        => $unit_arr,
            'barcode_mode'   => $barcode_mode,
            'disk_usage_percent' => $disk_usage_percent,
            'history_year_arr' => $history_year_arr 


        );
        if($isMobile){
            $this->view('setting/index_m', $data);
        }else{
            $this->view('setting/index', $data);
        }
       

    }

    // Job Threshold Torque Lưu đơn vị mới khi chọn từ Setting
    public function update_unit_ajax() {
        $new_unit = $_POST['unit']; // index: 0, 1, 2, 3, 4

        $_SESSION['selected_unit'] = $new_unit; // hoặc ghi vào DB nếu muốn lưu cho từng user

        echo json_encode(['success' => true]);
    }

    public function job_tree(){   
     
        //select all job
        $jobs = $this->SettingModel->GetAllJobs();
        //select all sequence
        $seqs = $this->SettingModel->GetAllSequences();
        //select all step
        $steps = $this->SettingModel->GetAllSteps();

        // var_dump($steps);
        $data_array = array();
        foreach ($jobs as $key => $value) {
            $temp = ["id" => 'job_'.$value['job_id'], "parent" => "#", "text" => $value['job_name'] ];
            $data_array[] = $temp;
        }
        foreach ($seqs as $key => $value) {
            $temp = ["id" => 'job_'.$value['job_id'].'_'.'seq_'.$value['seq_id'], "parent" => 'job_'.$value['job_id'], "text" => $value['seq_name'] ];
            $data_array[] = $temp;
        }
        foreach ($steps as $key => $value) {
            $temp = ["id" => $value['job_id'].'_'.$value['seq_id'].'_'.$value['step_id'], "parent" => 'job_'.$value['job_id'].'_'.'seq_'.$value['seq_id'], "text" => $value['step_name'] ];
            $data_array[] = $temp;
        }

        echo json_encode($data_array);
    }

    public function edit_password(){


        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }

        
        $conset = array();
        $input_check = true;
        
        if( !empty($_POST['new_password']) && isset($_POST['new_password'])){
             $conset['new_password']  = $_POST['new_password'];
        }else{ 
            $input_check = false; 
        }
        


        if($input_check){
            $result = array();
            $res = $this->SettingModel->Edit_Login_Password($conset);
            if($res){
                $res_type = 'Succes';
                $res_msg = $text['Edit']." : ".$text['success'];
                $this->MiscellaneousModel->generateErrorResponse('Succes', $res_msg );
            }else{
                $res_type = 'Error';
                $res_msg = $text['Edit']." : ".$text['fail'];
                $this->MiscellaneousModel->generateErrorResponse('Error', $res_msg );
            }
        }
       
    }


    public function edit_permission()
    {
        //default array
        $input_check = true;
        $error_message = '';
        $priviledge = [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1];

        if( !empty($_POST['Permission_Confirm']) && isset($_POST['Permission_Confirm'])  ){
            if($_POST['Permission_Confirm'] == 'false'){
                $Permission_Confirm = 1;
            }else{
                $Permission_Confirm = 0;
            }
        }else{ 
            $input_check = false; 
            $error_message .= "Permission_Confirm,";
        }

        if( !empty($_POST['Permission_Clear']) && isset($_POST['Permission_Clear'])  ){
            if($_POST['Permission_Clear'] == 'false'){
                $Permission_Clear = 1;
            }else{
                $Permission_Clear = 0;
            }
        }else{ 
            $input_check = false; 
            $error_message .= "Permission_Clear,";
        }

        if( !empty($_POST['Permission_Seq_Clear']) && isset($_POST['Permission_Seq_Clear'])  ){
            if($_POST['Permission_Seq_Clear'] == 'false'){
                $Permission_Seq_Clear = 1;
            }else{
                $Permission_Seq_Clear = 0;
            }
        }else{ 
            $input_check = false; 
            $error_message .= "Permission_Seq_Clear,";
        }

        if( !empty($_POST['Permission_SW']) && isset($_POST['Permission_SW'])  ){
            if($_POST['Permission_SW'] == 'false'){
                $Permission_SW = 0;
            }else{
                $Permission_SW = 1;
            }
        }else{ 
            $input_check = false; 
            $error_message .= "Permission_SW,";
        }

        if( !empty($_POST['Permission_Export']) && isset($_POST['Permission_Export'])  ){
            if($_POST['Permission_Export'] == 'false'){
                $Permission_Export = 0;
            }else{
                $Permission_Export = 1;
            }
        }else{ 
            $input_check = false; 
            $error_message .= "Permission_Export,";
        }

        if( !empty($_POST['Permission_Barcode']) && isset($_POST['Permission_Barcode'])  ){
            if($_POST['Permission_Barcode'] == 'false'){
                $Permission_Barcode = 0;
            }else{
                $Permission_Barcode = 1;
            }
        }else{ 
            $input_check = false; 
            $error_message .= "Permission_Barcode,";
        }

        if($input_check){

            $priviledge[12] = $Permission_Confirm;
            $priviledge[11] = $Permission_Clear;
            $priviledge[10] = $Permission_Seq_Clear;
            $priviledge[13] = $Permission_Export;
            $priviledge[14] = $Permission_SW;
            $priviledge[15] = $Permission_Barcode;

            $array2int = $this->bitArrayToDecimal($priviledge);
            $result = $this->SettingModel->Edit_Priviledge($array2int);

            if($result){// copy DB
                $copy_result =  $this->copyDB_to_RamdiskDB();
                if($copy_result){
                    $this->logMessage('edit_permission:set '.$array2int.' copyDB success');
                }else{
                    $this->logMessage('edit_permission:set '.$array2int.' copyDB fail');
                }
            }


            echo json_encode(array('error' => ''));
            exit();
        }else{
            echo json_encode(array('error' => $error_message));
            exit();
        }
        
    }

    private function intTo16BitArray($value) {
        // 確保值在 0 到 65535 的範圍內
        $value = max(0, min(65535, $value));

         // 將值拆分為二進制位元的陣列
        $bitArray = [];
        for ($i = 15; $i >= 0; $i--) {
            $bitArray[] = ($value >> $i) & 1;
        }
        
        // 返回二進制位元的陣列
        return $bitArray;
    }

    private function bitArrayToDecimal($bitArray) {
        // 確保陣列長度為 16
        if (count($bitArray) !== 16) {
            throw new InvalidArgumentException("陣列長度必須為 16");
        }
        
        // 將位元陣列轉換為十進位整數
        $decimalValue = 0;
        for ($i = 15; $i >= 0; $i--) {
            $decimalValue += $bitArray[$i] * pow(2, 15 - $i);
        }
        
        return $decimalValue;
    }


    public function control_setting() {

        $file = $this->MiscellaneousModel->lang_load();
        if (!empty($file)) {
            include $file;
        }

        $con_setting = [];
        $input_check = true;

        $get = function($key, $default = null) {
            return isset($_POST[$key]) && $_POST[$key] !== '' ? $_POST[$key] : $default;
        };

        // 必填欄位驗證 - Xác thực trường bắt buộc
        //$required_fields = ['control_id', 'control_name', 'storage_warning', 'torque_filter'];
        $required_fields = ['control_id', 'control_name', 'lang_val', 'batch_val','buzzer_val','torque_unit'];
        foreach ($required_fields as $field) {
            $val = $get($field);
            if ($val === null) {
                $input_check = false;
            } else {
                $con_setting[$field] = $val;
            }
        }

        // 可選欄位（含預設值）- Kě xuǎn lán wèi (hán yù shè zhí)
        $con_setting['lang_val'] = (int)$get('lang_val', 0);
        $con_setting['unit_val'] = (int)$get('unit_val', 0);

        $optional_fields = [
            'counting_method',
            'circular_archive',
            'blackout_recovery',
            'buzzer_mode',
            'global_downshift_torque',
            'global_downshift_speed'
        ];

        foreach ($optional_fields as $field) {
            $con_setting[$field] = $get($field, ''); // 空字串作為預設值
        }

        // 若前面驗證通過 - Ruò qiánmiàn yànzhèng tōngguò
        if ($input_check) {
            $res = $this->SettingModel->GetControllerInfo_count($con_setting['control_id']);

            if ($res['count'] === "1") {
                $result = $this->SettingModel->Controller_Setting($con_setting);

                if ($result) {
                    $res_msg = $text['success'] ?? 'Success';
                    $this->MiscellaneousModel->generateErrorResponse('Success', $res_msg);
                } else {
                    $res_msg = $text['fail'] ?? 'Fail';
                    $this->MiscellaneousModel->generateErrorResponse('Error', $res_msg);
                }
            } else {
                $res_msg = $text['not_found'] ?? 'Controller not found';
                $this->MiscellaneousModel->generateErrorResponse('Error', $res_msg);
            }
        } else {
            $res_msg = $text['form_invalid'] ?? 'Invalid input';
            $this->MiscellaneousModel->generateErrorResponse('Error', $res_msg);
        }
    }

    
    public function edit_system_date() {

        if (PHP_OS_FAMILY == 'Linux') {
            $dateTime = $_POST["datetime"] ?? '';
            $dateTime = str_replace("T", " ", $dateTime); // YYYY-MM-DD HH:MM
    
            if (!preg_match("/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/", $dateTime)) {
                echo json_encode(['error' => '請提供有效的日期和時間格式（YYYY-MM-DD HH:MM）。']);
                exit;
            }
    
            exec("sudo timedatectl set-ntp no");
            $escapedDateTime = escapeshellarg($dateTime);
            $rr = exec("sudo date -s $escapedDateTime");
            exec("sudo hwclock --systohc");
    
            $this->logMessage("set date -s {$dateTime} " . ($rr !== false ? "success" : "fail"));
    
            echo json_encode(['error' => '', 'result' => $rr]);
        } else {
            echo json_encode(['error' => '非 Linux 系統無法設定時間']);
        }
        exit;
    }
    
    public function get_system_time() {

        header("Content-Type: text/plain; charset=utf-8");
        if (PHP_OS_FAMILY === 'Linux') {
            $output = shell_exec("date '+%Y-%m-%d %H:%M:%S'");
        } else {
            $output = date("Y-m-d H:i:s");
        }

        echo trim($output);
    }

     function firmware_reset()
    {
        // code...
    }


    public function export_sysytem_config(){

        // 4 個都打包
        $files = [
            '/var/log/syslog',
            '/home/kls/project/system/oplog0.bin',
            '/var/www/html/database/tcccon.db',
            '/var/www/html/database/tccdev.db',
        ];

        // zip 存放目錄
        $zipDir = '/mnt/ramdisk/tmp/';
        if (!is_dir($zipDir)) {
            @mkdir($zipDir, 0777, true);
        }

        $zipFile = $zipDir . 'system_config_' . date('Ymd_His') . '.zip';

        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            http_response_code(500);
            echo json_encode(['error' => 'Unable to create zip file']);
            exit;
        }

        $added = [];
        $missing = [];

        foreach ($files as $file) {
            if (file_exists($file) && is_readable($file)) {
                // 用 basename 存入 zip（不帶完整路徑）
                $zip->addFile($file, basename($file));
                $added[] = $file;
            } else {
                $missing[] = $file;
            }
        }

        // 寫入 manifest，方便你核對
        //$manifest = "Export Time: " . date('Y-m-d H:i:s') . "\n\n";
        //$manifest .= "[ADDED]\n" . (count($added) ? implode("\n", $added) : "(none)") . "\n\n";
        //$manifest .= "[MISSING]\n" . (count($missing) ? implode("\n", $missing) : "(none)") . "\n";
        //$zip->addFromString('manifest.txt', $manifest);

        $zip->close();

        if (!file_exists($zipFile)) {
            http_response_code(404);
            echo json_encode(['error' => 'Zip file not found']);
            exit;
        }

        // 提供前端下載
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="system_config_' . date('Ymd_His') . '.zip"');
        header('Content-Length: ' . filesize($zipFile));
        header('Pragma: no-cache');
        header('Expires: 0');

        readfile($zipFile);
        exit;
    }





    public function system_storage(){
        $EMMC_BASE = "/var/www/html/database/"; //目標目錄路徑
        if( PHP_OS_FAMILY == 'Linux'){
            $size = 0;
            foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($EMMC_BASE)) as $file) {
                if ($file->isFile()) {
                    $size += $file->getSize();
                }
            }
    
            $gigatmp = $size / 1024 / 1024 / 1024;
            $device_diskfull_percent = ceil(($gigatmp / 1.1) * 100);
    
            echo "{$device_diskfull_percent}";
        }else{
            echo "X";
        }
    }

    public function get_file_list($value='')
    {
        $year = date("Y");

        if( PHP_OS_FAMILY == 'Linux'){
            $folderPath = "/var/www/html/database/"; // 修改為你的資料夾路徑
        }else{
            $folderPath = "../"; // 修改為你的資料夾路徑
        }

        $excludeFiles = ["tcscon.db", "tcsdev.db"]; // 要排除的檔案名稱 ,"data{$year}.db"
        $allowedExtensions = ["db"]; // 允許的附檔名


        $fileList = scandir($folderPath);
        // 過濾不要顯示的檔案
        $fileList = array_filter($fileList, function ($fileName) use ($excludeFiles) {
            return !in_array($fileName, $excludeFiles);
        });

        // 過濾只顯示符合條件的檔案
        $fileList = array_filter($fileList, function ($fileName) {
            // 檢查檔案名稱是否以 "data" 開頭且副檔名為 ".db"
            return (strpos($fileName, "data") === 0 && pathinfo($fileName, PATHINFO_EXTENSION) === "db");
        });


        $fileList = array_diff($fileList, array(".", "..")); // 移除 . 和 .. 條目
        echo json_encode(array_values($fileList));
    }


    public function firmware_update() //FTP 上傳檔案大小限制 : 500M
    {
        // code...
    }


    //DB匯入提醒判斷
    public function SyncCheck($value='')
    {
        // session_start();
        /*$this->language_auto(); //從瀏覽器帶入語系
        //multi language
        $language = array("language"=>$_SESSION['language']);
        // 如果檔案存在就引入它
        if(file_exists('../app/language/' . $language['language'] . '.php')){
            require_once '../app/language/' . $language['language'] . '.php';
        } else { //預設語系
            require_once '../app/language/en-us.php';
        }
        
        //C2D可以不判斷
        if ( isset($_GET["way"]) ) {
            $way = $_GET["way"];
        }else if ( isset($_POST["way"]) ) {
            $way = $_POST["way"];
        }

        // 1. filetime
        // 2. db version
        // 3. compare
        $notice = '';
        $warning = '';
        $Das_DB_Location = '/var/www/html/database/iDas-tcscon.db';
        $Con_DB_Location = '/var/www/html/database/tcscon.db';

        if($this->LoginCheck() == 1){
            echo json_encode(array('warning' => $text['system_sync_warning_login']));
            exit();
        }

        if($way == 'C2D'){
            echo json_encode( array('notice'=>'','warning'=>'') );
            exit();
        }

        if( PHP_OS_FAMILY == 'Linux' && $way == 'D2C'){

            //時間差異提醒
            if( filemtime($Con_DB_Location) > filemtime($Das_DB_Location) ){
                $notice = $text['system_sync_notice'].date("Y-m-d H:i:s.", filemtime($Con_DB_Location));
            }

            //DB版本差異判斷
            $C_DB_Version = $this->SettingModel->Get_Controller_DB_version();
            $Controller_Info = $this->SettingModel->GetControllerInfo();
            if ($Controller_Info['tcscondb_version'] < $C_DB_Version) {
                $warning = $text['system_sync_warning'];
            }

            //idas版本驗證 符合match_gtcs_app_version
            $match_gtcs_app_version = $this->AdminModel->Get_Das_Config('match_gtcs_app_version');
            $C_Device_Vesion = $this->SettingModel->Get_Controller_Device_version();
            if($match_gtcs_app_version != $C_Device_Vesion){
                $warning = 'APP Version Not Match';
            }

            //DB欄位差異判斷
            if(!$this->Database_Column_Diff()){
                $warning .= 'DB is different';
            }
            
            //資料是否有Null判斷
        }

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest' && $_SERVER['REQUEST_METHOD'] == 'GET' ) {
            // 这是一个外部的 AJAX 请求
            echo json_encode( array('notice'=>$notice,'warning'=>$warning) );
            exit();
        } else {
            // 这是内部调用
            return array('notice'=>$notice,'warning'=>$warning);
        }*/
        

    }

    public function Sync_check_db() {

        $file = $this->MiscellaneousModel->lang_load();
        if (!empty($file)) {
            include $file;
        }
    
        $argument = !empty($_POST['argument']) ? $_POST['argument'] : '';
    
        $Das_DB_Location = '/var/www/html/database/idas_data.db'; 
        $Con_DB_Location = '/var/www/html/database/tcccon.db'; 
        $Backup_DB_Location = '/var/www/html/database/tcccon_bk.db';
        $destination = "/mnt/ramdisk/ftp/iDas.cfg";
        $Ramdisk_DB_Location = '/mnt/ramdisk/tcccon.db';
    
        if (!empty($argument)) {
            if (PHP_OS_FAMILY == 'Linux' && $argument == 'D2C') {
    
                // 1. 確認 iDas 資料庫是否存在
                if (!file_exists($Das_DB_Location)) {
                    $res_msg = "Error: idas_data.db does not exist.";
                    $this->MiscellaneousModel->generateErrorResponse('Error', $res_msg);
                    return;
                }
    
                // 2. 備份 tcccon.db
                if (file_exists($Con_DB_Location)) {
                    if (copy($Con_DB_Location, $Backup_DB_Location)) {
                        $this->logMessage("tcccon.db backup successful to tcccon_bk.db");
                    } else {
                        $res_msg = "Error: Failed to backup tcccon.db to tcccon_bk.db.";
                        $this->MiscellaneousModel->generateErrorResponse('Error', $res_msg);
                        return;
                    }
                } else {
                    $this->logMessage("tcccon.db does not exist, skip backup.");
                }
    
                // 3. 複製 iDas 資料庫到控制器
                if (copy($Das_DB_Location, $Con_DB_Location)) {
                    $res_msg = "SYNC" . $text['success'];
                    $this->MiscellaneousModel->generateErrorResponse('Success', $res_msg);
                } else {
                    $res_msg = "SYNC" . $text['fail'];
                    $this->MiscellaneousModel->generateErrorResponse('Error', $res_msg);
                    return;
                }
    
                // 4.新增：複製 iDas 資料庫到 /mnt/ramdisk/tcccon.db
                if (copy($Das_DB_Location, $Ramdisk_DB_Location)) {
                    $this->logMessage('Successfully copied idas_data.db to /mnt/ramdisk/tcccon.db');
                } else {
                    $res_msg = "Error: Failed to copy idas_data.db to /mnt/ramdisk/tcccon.db.";
                    $this->MiscellaneousModel->generateErrorResponse('Error', $res_msg);
                    return;
                }
    
                // 5. 使用 modbus 通知
                if (copy($Con_DB_Location, $destination)) {
    
                    require_once '../modules/phpmodbus-master/Phpmodbus/ModbusMaster.php';
                    $modbus = new ModbusMaster("127.0.0.1", "TCP");
    
                    try {
                        $modbus->port = 502;
                        $modbus->timeout_sec = 10;
                        $data = array(1, 26948, 24947);
                        $dataTypes = array("INT","INT","INT","INT","INT","INT","INT","INT","INT","INT","INT","INT","INT","INT","INT","INT");
    
                        $modbus->writeMultipleRegister(0, 506, $data, $dataTypes);
                        $this->logMessage('modbus write 506 , array = '.implode("','", $data));
                        $this->logMessage('modbus status:' . $modbus->status);
    
                        $modbus->writeMultipleRegister(0, 462, array(1), $dataTypes);
                        //echo json_encode(array('error' => ''));
    
                        exit();
    
                    } catch (Exception $e) {
                        $this->logMessage('modbus write fail: ' . $e->getMessage());
                        $this->logMessage('db_sync C2D end');
                        exit();
                    }
                }
            }
        }
    }

  
    

    public function Sync_check_db_load() {
        $file = $this->MiscellaneousModel->lang_load();
        if (!empty($file)) {
            include $file;
        }
    
        $argument = $_POST['argument'] ?? '';
    
        $Das_DB_Location     = '/var/www/html/database/idas_data.db'; // iDAS DB
        $Con_DB_Location     = '/var/www/html/database/tcccon.db';    // 控制器 DB
        $Backup_DB_Location  = '/var/www/html/database/tcccon_bk.db'; // 控制器備份
        $Copy_Destination    = '/mnt/ramdisk/ftp/iDas.cfg';           // RAMDISK 快取位置
    
        if (!empty($argument) && PHP_OS_FAMILY === 'Linux' && $argument === 'C2D') {
    
            // 時間比對提示（非強制）
            if (filemtime($Con_DB_Location) > filemtime($Das_DB_Location)) {
                $notice = ($text['system_sync_notice'] ?? 'Controller DB is newer: ') . date("Y-m-d H:i:s", filemtime($Con_DB_Location));
                $this->logMessage($notice);
            }
    
            // 結構比對提示（非強制）
            if (!$this->Database_Column_Diff()) {
                $this->logMessage('DB structure is different.');
            }
    
            // Step 1: 備份控制器 DB
            if (!copy($Con_DB_Location, $Backup_DB_Location)) {
                $this->MiscellaneousModel->generateErrorResponse('Error', 'Backup tcccon.db to tcccon_bk.db failed');
                return;
            }
    
            // Step 2: 備份檔複製成 iDAS 使用
            if (!copy($Backup_DB_Location, $Das_DB_Location)) {
                $this->MiscellaneousModel->generateErrorResponse('Error', 'Copy backup to idas_data.db failed');
                return;
            }
    
            // Step 2-1: SHA1 比對備份檔與 idas_data.db
            $sha1_backup = sha1_file($Backup_DB_Location);
            $sha1_idas   = sha1_file($Das_DB_Location);
    
            $this->logMessage("SHA1 tcccon_bk.db = $sha1_backup");
            $this->logMessage("SHA1 idas_data.db = $sha1_idas");
    
            if ($sha1_backup !== $sha1_idas) {
                $this->logMessage("SHA1 mismatch: tcccon_bk.db != idas_data.db");
                $this->MiscellaneousModel->generateErrorResponse('Error', 'SHA1 mismatch: sync integrity failed');
                return;
            }
    
            // Step 3: 成功回應
            $res_msg = "SYNC" . ($text['success'] ?? 'Success');
            $this->MiscellaneousModel->generateErrorResponse('Success', $res_msg);
    
            // Step 4: 通知控制器 Modbus 同步完成
            if (copy($Das_DB_Location, $Copy_Destination)) {
                require_once '../modules/phpmodbus-master/Phpmodbus/ModbusMaster.php';
                $modbus = new ModbusMaster("127.0.0.1", "TCP");
                try {
                    $modbus->port = 502;
                    $modbus->timeout_sec = 10;
                    $data = array(1, 26948, 24947);
                    $dataTypes = array("INT", "INT", "INT");
    
                    $modbus->writeMultipleRegister(0, 506, $data, $dataTypes);
                    $this->logMessage('modbus write 506, array = ' . implode(',', $data));
                    $this->logMessage('modbus status: ' . $modbus->status);
    
                } catch (Exception $e) {
                    $this->logMessage('modbus write 506 fail: ' . $e->getMessage());
                    $this->logMessage('modbus status: ' . $modbus->status);
                    return;
                }
            } else {
                $this->MiscellaneousModel->generateErrorResponse('Error', 'Failed to copy idas_data.db to iDas.cfg');
            }
        }
    }
    

    //get barcode
    public function GetBarcodes(){
        $barcodes = $this->SettingModel->GetAllBarcodes();
        return $barcodes;
    }

    public function show_Barcodes(){

        $isMobile = $this->isMobileCheck();
        $barcode_list = '';
        $barcodes = $this->SettingModel->GetAllBarcodes();
        $barcode_mode = $this->MiscellaneousModel->details('barcode_mode');
        if(!empty($barcodes)){
            
            if(!$isMobile){

                foreach($barcodes as $kk =>$vv){
                    $barcode_list = '<tr style="text-align: center; vertical-align: middle;" >';
                    $barcode_list .= "<td><input class='form-check-input' type='checkbox' name='barcode_check' id='barcode_check' style='zoom:1.2' value='".$vv['barcode_selected_job']."'></td>";
                    $barcode_list .= '<td>'.$vv['barcode_selected_job'].'</td>';
                    $barcode_list .= '<td>'.$vv['job_name'].'</td>';
                    $barcode_list .= '<td>'.$vv['barcode'].'</td>';
                    $barcode_list .= '<td>'.$vv['barcode_mask_from'].'</td>';
                    $barcode_list .= '<td>'.$vv['barcode_mask_count'].'</td>';
                    $barcode_list .= '<td>'.$vv['barcode_enable'].'</td>';
                    $barcode_list .= '<tr>';
    
                    echo $barcode_list;
                }

            }else{
                foreach($barcodes as $kk =>$vv){
                    $barcode_list = '<tr style="text-align: center; vertical-align: middle;" >';
                    $barcode_list .= "<td><input class='form-check-input' type='checkbox' name='barcode_check' id='barcode_check' style='zoom:1.2' value='".$vv['barcode_selected_job']."'></td>";
                    $barcode_list .= '<td>'.$vv['barcode_selected_job'].'</td>';
                    $barcode_list .= '<td>'.$vv['job_name'].'</td>';
                    $barcode_list .= '<td>'.$vv['barcode'].'</td>';
                    $barcode_list .= '<td>'.$vv['barcode_mask_from'].'</td>';
                    $barcode_list .= '<td>'.$vv['barcode_mask_count'].'</td>';
                    $barcode_list .= '<td>'.$barcode_mode[$vv['barcode_enable']].'</td>';
                    $barcode_list .= '<tr>';
    
                    echo $barcode_list;
                }

            }
          
        }

    }

    //update barcode
    public function Update_Barcode(){
        
        $barcode = array();

        $barcode['barcode_content']       = $_POST['barcode_content'] ?? null;
        $barcode['barcode_mask_from']     = $_POST['barcode_mask_from'] ?? null;
        $barcode['barcode_mask_count']    = $_POST['barcode_mask_count'] ?? null;
        $barcode['barcode_selected_job']  = $_POST['barcode_selected_job'] ?? null;
        $barcode['barcode_enable']        = $_POST['barcode_enable'] ?? null;
        $barcode['barcode_selected_seq']  = $_POST['barcode_selected_seq'] ?? null;
        if(!empty($barcode)){
            $barcode_result = $this->SettingModel->Update_Barcode($barcode);
            if($barcode_result){
                $res_msg = 'edit barcode :'. $barcode['barcode_content'].' success';
                $this->MiscellaneousModel->generateErrorResponse('Success', $res_msg );

            }else{
                $res_msg = 'edit barcode :'. $barcode['barcode_content'].' fail';
                $this->MiscellaneousModel->generateErrorResponse('Error', $res_msg );
            }
               
        }
    }

    public function GetJobSeq(){

        $input_check = true;
        $error_message = '';
        
        if( !empty($_POST['job_id']) && isset($_POST['job_id'])  ){
            $job_id = $_POST['job_id'];
        }else{ 
            $input_check = false;
            $error_message .= "job_id,";
        }

        if($input_check){
            $result = $this->SettingModel->get_seq_list($job_id);
            echo json_encode($result);
            exit();
        }else{
            $data = [
                'result' => 'fail',
                'error_message' => $error_message
            ];
            echo json_encode($data);
            exit();
        }
    }


    
    public function GetJobSeq_for_modbus(){

        $input_check = true;
        $error_message = '';
        
        if( !empty($_GET['job_id']) && isset($_GET['job_id'])  ){
            $job_id = $_GET['job_id'];
        }else{ 
            $input_check = false;
            $error_message .= "job_id,";
        }

        if($input_check){
            $result = $this->SettingModel->get_seq_list_for_modbus($job_id);
            echo json_encode($result);
            exit();
        }else{
            $data = [
                'result' => 'fail',
                'error_message' => $error_message
            ];
            echo json_encode($data);
            exit();
        }


    }

    public function GetJobBarcode(){

        $input_check = true;
        $error_message = '';
        if( !empty($_GET['job_id']) && isset($_GET['job_id'])  ){
            $job_id = $_GET['job_id'];
        }else{ 
            $input_check = false;
            $error_message .= "job_id,";
        }

        if($input_check){
            $result = $this->SettingModel->e($job_id);
            echo json_encode($result);
            exit();
        }else{
            $data = [
                'result' => 'fail',
                'error_message' => $error_message
            ];
            echo json_encode($data);
            exit();
        }
    }

    public function delete_barcodes(){

        $input_check = true;
        $barcode = array();
        if(!empty($_POST['del_barcode_id']) && isset($_POST['del_barcode_id'])){
            $barcode = $_POST['del_barcode_id'];
        }else{ 
            $input_check = false;
        }

        if($input_check){
           $res = $this->SettingModel->delete_job_barcode($barcode);

           if($res){
                $res_msg = 'del barcode :'. $barcode[0].'success';
                $this->MiscellaneousModel->generateErrorResponse('Success', $res_msg );

           }else{
                $res_msg = 'del barcode :'. $barcode[0].'fail';
                $this->MiscellaneousModel->generateErrorResponse('Error', $res_msg );
           }
        }
      
    }

    #IDAS上傳 20250522 修改
    public function iDas_Update() {
        // 1. 紀錄目前 PHP 的上傳限制，方便除錯
        $maxUpload = ini_get('upload_max_filesize');
        $postMax = ini_get('post_max_size');
        error_log("目前 upload_max_filesize: $maxUpload");
        error_log("目前 post_max_size: $postMax");

        // 2. 載入語系檔，供 $text 語系變數使用
        $file = $this->MiscellaneousModel->lang_load();
        if (!empty($file)) include $file;

        // 3. 取得目前 iDAS 版本，之後會用來比對 info.json 的版本
        $iDas_Vesion = $this->AdminModel->Get_Das_Config('idas_version');

        //  4. 根據系統平台（Linux 或 Windows）設定根目錄與解壓縮路徑
        $file_location = (PHP_OS_FAMILY === 'Linux') ? '/var/www/html/' : $_SERVER['DOCUMENT_ROOT'] . '/';
        $extract_path = $file_location . 'extracted/';
        $main_folder = ''; // 後面會指定為解壓出來的主資料夾路徑

        try {
            //  5. 驗證上傳檔案是否存在且無錯誤
            if (empty($_FILES['file']) || $_FILES['file']['error'] !== 0) {
                $msg = empty($_FILES['file']) ? 'No file uploaded.' : 'File upload error: ' . $_FILES['file']['error'];
                return $this->sendResponse('Error', $msg);
            }

            //  6. 檢查檔案大小（限制為 30MB 以內）
            if ($_FILES['file']['size'] > 30 * 1024 * 1024) {
                return $this->sendResponse('Error', $text['over_size_text']);
            }

            //  7. 驗證副檔名必須為 .pack
            $uploaded_filename = $_FILES['file']['name'];
            if (strtolower(pathinfo($uploaded_filename, PATHINFO_EXTENSION)) !== 'pack') {
                return $this->sendResponse('Error', $text['invalid_file_extension']. $uploaded_filename);
            }

            //  8. 使用 ZipArchive 解壓縮 .pack 檔案
            $zip = new ZipArchive();
            if ($zip->open($_FILES['file']['tmp_name']) !== TRUE) {
                return $this->sendResponse('Error', $text['cannot_open_pack']);
            }

            //  9. 若解壓縮目錄不存在就先建立
            if (!is_dir($extract_path)) mkdir($extract_path, 0777, true);

            //  10. 解壓縮至指定目錄
            if (!$zip->extractTo($extract_path)) {
                $zip->close();
                return $this->sendResponse('Error', $text['extract_failed'] );
            }
            $zip->close();

            //  11. 找出解壓縮後的主資料夾
            $folders = array_filter(scandir($extract_path), fn($f) => is_dir($extract_path . $f) && !in_array($f, ['.', '..']));
            if (empty($folders)) {
                return $this->sendResponse('Error', $text['no_extracted_folder'] );
            }

            //  12. 指定主資料夾與 info.json 路徑
            $main_folder = $extract_path . reset($folders);
            $info_json_url = $main_folder . "/info.json";

            //  13. 檢查 info.json 是否存在
            if (!file_exists($info_json_url)) {
                return $this->sendResponse('Error', $text['missing_info_json'] );
            }

            //  14. 解析 info.json，取得更新檔版本資訊
            $verify_data = json_decode(@file_get_contents($info_json_url), true);
            if (!$verify_data || !isset($verify_data['Match_TCC_Version'])) {
                return $this->sendResponse('Error', $text['info_json_invalid']);
            }

            //  15. 比對版本：如果更新檔比目前版本還舊，就不更新
            $match_tcc_version = $verify_data['Match_TCC_Version'];
            if (version_compare($match_tcc_version, $iDas_Vesion, '<')) {
                return $this->sendResponse('Error',  $text['version_too_low'] . $iDas_Vesion . '，更新版本：' . $match_tcc_version);
            }


            // 16. 將 $verify_data['Match_TCC_Version'] 寫入到資料庫
            $this->AdminModel->Set_idas_version($verify_data['Match_TCC_Version']);


            //  17. 指定最終目標目錄（部署到 /idas/ 下）
            $target_directory = $_SERVER['DOCUMENT_ROOT'] . '/idas/';
            if (!is_dir($target_directory)) mkdir($target_directory, 0777, true);

            //  18. 複製解壓出來的檔案到正式目錄
            $this->copyDirectory($main_folder, $target_directory);

            //  19. 強制登出控制器使用者（安全性與更新重啟）
            $this->setting_logout();

        
            //  20. 成功更新回應
            return $this->sendResponse('Success',$text['update_success'] . '<script>window.location.href="?url=In";</script>');

        } finally {
            //  21. 無論成功或失敗，清除主資料夾與解壓縮目錄
            if (!empty($main_folder) && is_dir($main_folder)) {
                $this->deleteDirectory($main_folder);
            }
            if (is_dir($extract_path)) {
                $this->deleteDirectory($extract_path);
            }
        }
        
    }



    private function sendResponse($type, $msg) {
        $this->MiscellaneousModel->generateErrorResponse($type, $msg);
        exit();
    }

    private function copyDirectory($source, $destination) {
        if (!is_dir($destination)) mkdir($destination, 0777, true);
        foreach (scandir($source) as $file) {
            if (!in_array($file, ['.', '..'])) {
                $src = $source . '/' . $file;
                $dst = $destination . '/' . $file;
                if (is_dir($src)) {
                    $this->copyDirectory($src, $dst);
                } else {
                    if (file_exists($dst)) unlink($dst);
                    copy($src, $dst);
                }
            }
        }
    }

    private function deleteDirectory($dir) {
        if (!is_dir($dir)) return;
        foreach (scandir($dir) as $file) {
            if (!in_array($file, ['.', '..'])) {
                $path = $dir . '/' . $file;
                is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
            }
        }
        rmdir($dir);
    }


    public function Extract_File($file_location,$filename){

        // $filename = 'update_package.pack';
        $zip = new ZipArchive;

        if($zip->open($file_location.''.$filename)===TRUE){
            if($zip->setPassword('Vfxh]QaXxZF-eT1L9b@%pJ-F#U>]95Fr_9GQf5]KtZRhXiHXJ-6QW86.gXQdp9yEZK@fxVF!WJ>PXMdK]>eeh*_-=0')){
                $res = $zip->extractTo($file_location.'package_temp/'); //避免覆蓋，將解壓縮資料放進該資料夾
                $zip->close();
                return $res;
            }else{
                return false;
            }
            // echo "解壓縮完成";
        }else{
            return false;
        }

    }

    public function copyFolder($source, $destination) {
        if (is_dir($source)) {
            @mkdir($destination);
            
            $directory = dir($source);

            while (false !== ($entry = $directory->read())) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                }

                if (is_dir("$source/$entry")) {
                    $this->copyFolder("$source/$entry", "$destination/$entry");
                    continue;
                }

                copy("$source/$entry", "$destination/$entry");
            }

            $directory->close();
        } else {
            copy($source, $destination);
        }
    }

    public function deleteFolder($dir){

        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") {
                        $this->deleteFolder($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }


    public function Import_Config(){

        
        $file = $this->MiscellaneousModel->lang_load();
        if (!empty($file)) {
            include $file;
        }

        // 1) 控制器登入狀態檢查（已登入不可匯入）
        $idas_result = (int)$this->get_controller_login();
        if ($idas_result === 1) {
            $this->MiscellaneousModel->generateErrorResponse(
                'Error',
                'Controller is logged in. Please log out before importing config.'
            );
            exit();
        }

        // 2) 檢查是否有上傳檔案
        if (empty($_FILES) || !isset($_FILES['file'])) {
            $this->MiscellaneousModel->generateErrorResponse(
                'Error',
                'No file uploaded.'
            );
            exit();
        }

        // 3) 副檔名必須是 .cfg
        $file_name = $_FILES['file']['name'];
        $file_info = pathinfo($file_name);

        if (
            !isset($file_info['extension']) ||
            strtolower($file_info['extension']) !== 'cfg'
        ) {
            $this->MiscellaneousModel->generateErrorResponse(
                'Error',
                'The uploaded file is not a .cfg file.'
            );
            exit();
        }

        // 4) Linux：將 cfg 內容覆蓋寫入 tcccon.db，並複製成 idas_data.db
        if (PHP_OS_FAMILY === 'Linux') {

            $targetDir   = '/var/www/html/database/';
            $mainDb      = $targetDir . 'tcccon.db';
            $backupDb    = $targetDir . 'idas_data.db';

            // 確保目錄存在
            if (!is_dir($targetDir)) {
                @mkdir($targetDir, 0777, true);
            }

            // 先寫入 tcccon.db
            $result = move_uploaded_file($_FILES['file']['tmp_name'], $mainDb);

            if ($result) {

                // 權限處理
                @chmod($mainDb, 0666);

                // 再複製一份成 idas_data.db
                if (!@copy($mainDb, $backupDb)) {
                    $this->logMessage('Import cfg success, but copy to idas_data.db failed');

                    $this->MiscellaneousModel->generateErrorResponse(
                        'Error',
                        'Config imported, but failed to create idas_data.db.'
                    );
                    exit();
                }

                @chmod($backupDb, 0666);

                $this->logMessage('Import cfg -> tcccon.db & idas_data.db success');

                $this->MiscellaneousModel->generateErrorResponse(
                    'Success',
                    'Config imported successfully.'
                );
                exit();

            } else {
                $this->MiscellaneousModel->generateErrorResponse(
                    'Error',
                    'Config import failed.'
                );
                exit();
            }
        }

        // 非 Linux（保險）
        $this->MiscellaneousModel->generateErrorResponse(
            'Error',
            'Unsupported operating system.'
        );
        exit();
    }






    public function FirmwareUpdate(){

        $file_location = '';
        $result = '';

        if(empty($_FILES)){
            echo json_encode(["Error" => 'no file']);
            exit();
        }


        if( PHP_OS_FAMILY == 'Linux'){
            
            $this->logMessage('firmware update start');

            // $destination = "/mnt/ramdisk/FTP/iDas.cfg";
            $destination = "/mnt/ramdisk/FTP/".$_FILES['file']['name'];
            $filenameWithoutExtension = pathinfo($_FILES['file']['name'], PATHINFO_FILENAME);
            //將檔案移到指定位置
            $result =  move_uploaded_file($_FILES['file']['tmp_name'], $destination);
            $name_int16 = $this->asciiToHexToInt($filenameWithoutExtension);

            if ($result) {
                require_once '../modules/phpmodbus-master/Phpmodbus/ModbusMaster.php';
                $modbus = new ModbusMaster("127.0.0.1", "TCP");
                try {
                    $modbus->port = 502;
                    $modbus->timeout_sec = 10;
                    $data = array(1, $name_int16[0], $name_int16[1], $name_int16[2], $name_int16[3], $name_int16[4], $name_int16[5], $name_int16[6], $name_int16[7], $name_int16[8], $name_int16[9], $name_int16[10], $name_int16[11]);
                    $dataTypes = array("INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT");

                    // FC 16
                    $modbus->writeMultipleRegister(0, 480, $data, $dataTypes);
                    $this->logMessage('modbus write 480 ,array = '.implode("','", $data));
                    $this->logMessage('modbus status:'.$modbus->status);
                    $this->logMessage('firmware update end');
                    echo json_encode(array('error' => ''));
                    exit();

                } catch (Exception $e) {
      
                    $this->logMessage('modbus write 480 fail');
                    $this->logMessage('modbus status:'.$modbus->status);
                    $this->logMessage('firmware update end');
                    echo json_encode(array('error' => 'modbus error'));
                    exit();
                }
            } else {
                $this->logMessage('copy db error');
                $this->logMessage('firmware update end');
                echo json_encode(array('error' => 'copy db error'));
                exit();
            }

        }else{
            // $this->logMessage('Import config start');
            $file_location = $_SERVER['DOCUMENT_ROOT'].'/';
            echo json_encode(["Error" => 'not for windows']);
            exit();
        }

        echo json_encode(["message" => $result]);
    }

    function asciiToHexToInt($input) {
        // 将 ASCII 字符转换为十六进制
        $hex = bin2hex($input);

        // 将十六进制字符串按每 4 个字符为一组进行分割
        $chunks = str_split($hex, 4);

        $result = array();
        foreach ($chunks as $chunk) {
            // 将每组 4 个字符的十六进制转换为整数
            $result[] = hexdec($chunk);
        }

        return $result;
    }

    //DB欄位差異判斷
    function Database_Column_Diff()
    {
        $dbPath1 = '/var/www/html/database/iDas_data.db';
        $dbPath2 = '/var/www/html/database/data.db';

        if ($this->validateTableStructure($dbPath1, $dbPath2)) {
           //echo "两个数据库的表结构相同。\n";
        } else {
            //echo "两个数据库的表结构不同。\n";
            return false;
        }

        //確認idas的設定db沒有null
        $result = $this->checkForNullValues($dbPath1);
        if(!$result){
            return false;
        }else{
            return true;
        }
        return true;
    }


    // 連接到SQLite資料庫
    function connectToSQLite($dbPath) {
         try {
             $pdo = new PDO("sqlite:$dbPath");
             $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
             return $pdo;
         } catch (PDOException $e) {
             echo "連線到資料庫失敗: " . $e->getMessage();
             return null;
         }
    }

    // 驗證兩個SQLite資料庫中所有表格的列數和列名是否相同
    function validateTableStructure($dbPath1, $dbPath2) {
         $pdo1 = $this->connectToSQLite($dbPath1);
         $pdo2 = $this->connectToSQLite($dbPath2);

         if (!$pdo1 || !$pdo2) {
             return false;
         }

         $tables1 = $this->getTablesInfo($pdo1);
         $tables2 = $this->getTablesInfo($pdo2);

         if (count($tables1) !== count($tables2)) {
             return false;
         }

         foreach ($tables1 as $table => $columns1) {
             if (!isset($tables2[$table])) {
                 return false;
             }

             $columns2 = $tables2[$table];
             if ($columns1 !== $columns2) {
                 return false;
             }
         }

         return true;
    }

    // 取得資料庫中所有表格的列數和列名
    function getTablesInfo($pdo) {
         $tables = array();

         $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'");
         $tableNames = $stmt->fetchAll(PDO::FETCH_COLUMN);

         foreach ($tableNames as $tableName) {
             $stmt = $pdo->query("PRAGMA table_info('$tableName')");
             $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
             $tables[$tableName] = array_map(function($column) {
                 return $column['name'];
             }, $columns);
         }

         return $tables;
    }

    // 檢查SQLite資料庫中所有表格的欄位是否有NULL值
    function checkForNullValues($dbPath) {

        $pdo = $this->connectToSQLite($dbPath);

        if (!$pdo) {
            return false;
        }

        $tables = $this->getTablesInfo($pdo);

        foreach ($tables as $tableName => $columns) {
            foreach ($columns as $column) {
                $stmt = $pdo->query("SELECT COUNT(*) FROM $tableName WHERE $column IS NULL");
                $rowCount = $stmt->fetchColumn();
                if ($rowCount > 0) {
                return false;
                }
            }
        }

        return true;
    }    


    public function setting_logout() {
        foreach ($_COOKIE as $key => $value) {
            setcookie($key, '', time() - 3600, '/');
        }
    }


    //判斷控制器是否有登出
    public function get_controller_login(){
        $Controller_Info = $this->ToolModel->GetControllerInfo();
        if (!empty($Controller_Info) && isset($Controller_Info['user_logIn'])) {
            return (int)$Controller_Info['user_logIn'];
        }

        // 預設值（查不到或沒資料）
        return 0;
    }


    public function get_history_year() {
        // 僅在 Linux 環境下執行
        if (PHP_OS_FAMILY !== 'Linux') {
            return [];
        }

        $dir = '/var/www/html/database/';
        $files = scandir($dir);
        $years = [];

        foreach ($files as $file) {
            // 比對格式：data{year}.db 且年份在 1911~9999
            if (preg_match('/^data(\d{4})\.db$/', $file, $matches)) {
                $year = (int)$matches[1];
                if ($year >= 1911 && $year <= 9999) {
                    $years[] = $year;
                }
            }
        }

        // 大到小排序
        rsort($years, SORT_NUMERIC);

        return $years;
    }

    //取得年份後 刪除
    public function delete_files(){

        // ===== 載入語系 =====
        $file = $this->MiscellaneousModel->lang_load();
        if (!empty($file)) {
            include $file;
        }

        // ===== 取得年份 =====
        $del_year = $_POST['del_year'] ?? null;
        if (empty($del_year)) {
            echo json_encode([
                'result'   => false,
                'res_type' => 'Error',
                'res_msg'  => $text['delete_text'] . ' ' . ($text['fail'] ?? 'failed')
            ]);
            return;
        }

        // ===== 檢查控制器登入狀態 =====
        $idas_result = $this->get_controller_login();
        if ($idas_result != 0) {
            echo json_encode([
                'result'   => false,
                'res_type' => 'Error',
                'res_msg'  => $text['delete_text'] . ' ' . ($text['fail'] ?? 'failed')
            ]);
            return;
        }

        // ===== 決定資料夾路徑 =====
        if (PHP_OS_FAMILY === 'Linux') {
            $folderPath = '/var/www/html/database';
        } else {
            $folderPath = '../';
        }

        $dbFile = $folderPath . '/data' . intval($del_year) . '.db';

        // ===== 檢查檔案是否存在 =====
        if (!is_file($dbFile)) {
            echo json_encode([
                'result'   => false,
                'res_type' => 'Error',
                'res_msg'  => $text['delete_text'] . ' ' . ($text['fail'] ?? 'failed')
            ]);
            return;
        }

        // ===== 嘗試刪除 =====
        if (@unlink($dbFile)) {
            echo json_encode([
                'result'   => true,
                'res_type' => 'Success',
                'res_msg'  => $text['delete_text'] . ' ' . ($text['success'] ?? 'success')
            ]);
        } else {
            echo json_encode([
                'result'   => false,
                'res_type' => 'Error',
                'res_msg'  => $text['delete_text'] . ' ' . ($text['fail'] ?? 'failed')
            ]);
        }
    }






    

}
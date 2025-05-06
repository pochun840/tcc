<?php

class Settings extends Controller
{
    private $SettingModel;
    private $AdminModel;
    private $ToolModel;
    private $MiscellaneousModel;
    private $LoginModel;
    // 在建構子中將 Post 物件（Model）實例化
    public function __construct()
    {
        $this->SettingModel = $this->model('Setting');
        $this->AdminModel = $this->model('Admin');
        $this->ToolModel = $this->model('Tool');
        $this->MiscellaneousModel = $this->model('Miscellaneous');
        $this->LoginModel = $this->model('Login');
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
            'barcode_mode'   => $barcode_mode

        );
        if($isMobile){
            $this->view('setting/index_m', $data);
        }else{
            $this->view('setting/index', $data);
        }
       

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

    public function control_setting(){

        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }

        $input_check = true;

        if( !empty($_POST['control_id']) && isset($_POST['control_id'])  ){
            $con_setting['control_id'] = $_POST['control_id'];
        }else{ 
            $input_check = false; 
        }

        if( !empty($_POST['control_name']) && isset($_POST['control_name'])){
            $con_setting['control_name'] = $_POST['control_name'];
        }else{ 
            $input_check = false; 
        }

        if( !empty($_POST['lang_val']) && isset($_POST['lang_val'])){
            $lang_val =  $_POST['lang_val'];
            intval($lang_val);
        }else{ 
            $lang_val = 0;
        }

        $con_setting['lang_val']  = $lang_val;

        if( !empty($_POST['batch_val']) && isset($_POST['batch_val'])  ){
            $con_setting['batch_val'] = $_POST['batch_val'];
        }else{ 
            $input_check = false; 
        }

        if( !empty($_POST['buzzer_val']) && isset($_POST['buzzer_val'])  ){
            $con_setting['buzzer_val'] = $_POST['buzzer_val'];
        }else{ 
            $input_check = false;    
        }
        //torque_unit
        if(!empty($_POST['torque_unit']) && isset($_POST['torque_unit']) ){
            $con_setting['torque_unit'] = $_POST['torque_unit'];
        }else{
            $input_check = false; 
        }





        if(!empty($_POST)){
            $con_setting = $_POST;
        }

        if($con_setting){
        
          $res = $this->SettingModel->GetControllerInfo_count($con_setting['control_id']);
          if($res['count'] =="1"){
               
                $res = $this->SettingModel->Controller_Setting($con_setting);
                $result = array();
                if($res){
                    $res_type = 'Succes';
                    $res_msg = $text['Edit']." : ". $con_setting['control_id']."  ".$text['success'];
                    $this->MiscellaneousModel->generateErrorResponse('Succes', $res_msg );
                }else{
                    $res_type = 'Error';
                    $res_msg = $text['Edit']." : ". $con_setting['control_id']."  ".$text['fail'];
                    $this->MiscellaneousModel->generateErrorResponse('Error', $res_msg );
                }
                
          }

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
        $output = shell_exec("date '+%Y-%m-%d %H:%M:%S'");
    
        echo trim($output);
    }

     function firmware_reset()
    {
        // code...
    }

    
    public function export_sysytem_config()
    {
        if( PHP_OS_FAMILY == 'Linux'){
            require_once '../modules/phpmodbus-master/Phpmodbus/ModbusMaster.php';
            $modbus = new ModbusMaster("127.0.0.1", "TCP");
            try {
                $modbus->port = 502;
                $data = array(1);
                $dataTypes = array("INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT");

                // FC 16
                $modbus->writeMultipleRegister(0, 505, $data, $dataTypes);
                $this->logMessage('modbus write 505 ,array = '.implode("','", $data));
                $this->logMessage('modbus status:'.$modbus->status);
                // echo json_encode(array('error' => ''));
                // exit();

                header("Content-type: text/html; charset=utf-8");
                $file="/mnt/ramdisk/tcccon.db"; // 實際檔案的路徑+檔名
                $filename="tcccon.cfg"; // 下載的檔名
                //指定類型
                header("Content-type: ".filetype("$file"));
                //指定下載時的檔名
                header("Content-Disposition: attachment; filename=".$filename."");
                //輸出下載的內容。
                readfile($file);

            } catch (Exception $e) {
                $this->logMessage('modbus write 505 fail');
                $this->logMessage('db_sync D2C end');
                echo json_encode(array('error' => 'modbus error'));
                exit();
            }
        }else{//windows
            // echo json_encode(array('error' => ''));
                header("Content-type: text/html; charset=utf-8");
                $file="../tcscon.db"; // 實際檔案的路徑+檔名
                $filename="tcscon.cfg"; // 下載的檔名
                //指定類型
                header("Content-type: ".filetype("$file"));
                //指定下載時的檔名
                header("Content-Disposition: attachment; filename=".$filename."");
                //輸出下載的內容。
                readfile($file);
            exit();
        }
    }

    


    public function system_storage()
    {
        $EMMC_BASE = "/home/kls/tcc/resource/db_emmc/"; //目標目錄路徑
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
            $folderPath = "/home/kls/tcc/resource/db_emmc/"; // 修改為你的資料夾路徑
        }else{
            $folderPath = "../"; // 修改為你的資料夾路徑
        }

        $excludeFiles = ["data.db", "tcsdev.db"]; // 要排除的檔案名稱 ,"data{$year}.db"
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

    public function delete_files()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = json_decode(file_get_contents("php://input"), true);
            $filesToDelete = $data["files"];

            if( PHP_OS_FAMILY == 'Linux'){
                $folderPath = "/home/kls/tcc/resource/db_emmc"; // 修改為你的資料夾路徑
            }else{
                $folderPath = "../"; // 修改為你的資料夾路徑
            }

            $result = ["message" => ""];

            foreach ($filesToDelete as $fileName) {
                $filePath = $folderPath . "/" . $fileName;
                if (file_exists($filePath) && is_file($filePath)) {
                    if (unlink($filePath)) {
                        $result["message"] .= "成功刪除檔案：$fileName\n";
                        $this->logMessage('delete DB success:'. json_encode($result).'');
                    } else {
                        $result["message"] .= "無法刪除檔案：$fileName\n";
                        $this->logMessage('delete DB fail:'. json_encode($result).'');
                    }
                } else {
                    $result["message"] .= "檔案不存在：$fileName\n";
                }
            }

            echo json_encode($result);
        } else {
            echo json_encode(["message" => "無效的請求方法"]);
        }

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
        $barcode['barcode_selected_seq']  = '';
        
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
        
        if( !empty($_GET['job_id']) && isset($_GET['job_id'])  ){
            $job_id = $_GET['job_id'];
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

    #IDAS上傳 
    public function iDas_Update(){
        
        $file = $this->MiscellaneousModel->lang_load();
        if (!empty($file)) {
            include $file;
        }

        $iDas_Vesion = $this->AdminModel->Get_Das_Config('idas_version');

        $uploaded_filename = $_FILES['file']['name'];

        $file_extension = pathinfo($uploaded_filename, PATHINFO_EXTENSION);
        if (strtolower($file_extension) !== 'pack') {

            $res_type = 'Error';
            $res_msg  = 'The uploaded file must be in .pack format. You uploaded a file: ' . $uploaded_filename;
            $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);
            exit();
        }
        
        $file_location = (PHP_OS_FAMILY == 'Linux') ? '/var/www/html/' : $_SERVER['DOCUMENT_ROOT'] . '/';

        // 檢查是否有上傳檔案
        if (empty($_FILES) || !isset($_FILES['file'])) {
            $res_type = 'Error';
            $res_msg  = 'No file uploaded.';
            $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);
            exit();
        }

        // 檢查上傳文件是否有錯誤
        if ($_FILES['file']['error'] !== 0) {
            $res_type = 'Error';
            $res_msg  = 'File upload error:'.$_FILES['file']['error'];
            $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);
            exit();
        }

        // 解壓縮上傳的檔案 (.pack)
        $zip = new ZipArchive();

        if ($zip->open($_FILES['file']['tmp_name']) === TRUE) {
            
            // 設定解壓路徑
            $extract_path = $file_location . 'extracted/';

            //echo $extract_path;die();
            //echo "Extract path: " . $extract_path . "<br>"; 
            if (!is_dir($extract_path)) {
                mkdir($extract_path, 0777, true); 
            }

            if (!is_dir($extract_path)) {
                //echo "Trying to create directory: " . $extract_path . "<br>";
                if (mkdir($extract_path, 0777, true)) {
                    echo "Directory created successfully!";
                } else {
                    echo "Failed to create directory!";
                }
            }

             
            // 解壓檔案
            if ($zip->extractTo($extract_path)) {

                //echo "wwer";die();
                $zip->close();

                // 取得解壓縮後的目錄結構
                $extracted_folders = [];
                $scanned_files = scandir($extract_path);

                // 過濾出資料夾名稱 (排除 '.' 和 '..' 這兩個特殊目錄)
                foreach ($scanned_files as $file) {
                    // 檢查是否為資料夾
                    if (is_dir($extract_path . $file) && $file != '.' && $file != '..') {
                        $extracted_folders[] = $file; 
                    }
                }

                $info_json_url = $extract_path.$extracted_folders[0]."/info.json";

                if (file_exists($info_json_url)) {
                    $verify_data = json_decode(@file_get_contents($info_json_url), true) ?? [];
                }

                $match_tcc_version = $verify_data['Match_TCC_Version'] ?? '';

            

                if ($match_tcc_version == $iDas_Vesion) {
                
                    // 目標資料夾 tccidas 的路徑
                    $target_directory = $_SERVER['DOCUMENT_ROOT'] . '/tccidas/';
             
                
                    // 確保 tccidas 目錄存在，如果不存在則創建它
                    if (!is_dir($target_directory)) {
                        mkdir($target_directory, 0777, true); // 創建目標目錄
                    }
                
                    // 檢查 $extracted_folders 是否有資料
                    if (!empty($extracted_folders)) {
                        $source_directory = $extract_path . $extracted_folders[0];
                    
                        // 直接把該資料夾裡面的內容搬到 /tccidas 根目錄
                        $this->copyDirectory($source_directory, $target_directory);
                    
                        // 搬完後刪除原始解壓的資料夾
                        $this->deleteDirectory($source_directory);
                    } else {

                        $res_type = 'Error';
                        $res_msg = 'No extracted folder found.';
                        $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);
                        exit();
                    }
                    $this->deleteDirectory($extract_path);  
                    // 確認移動完成
                    $res_type = 'Success';
                    $res_msg  = 'Files successfully moved to the "tccidas" directory.';
                    $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);

                    $this->setting_logout();
                }
            } else {

                $res_type = 'Error';
                $res_msg  = 'Failed to extract the .pack file.';
                $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);
                exit();
            }

        } else {
            $res_type = 'Error';
            $res_msg  = 'Failed to open the uploaded .pack file.';
            $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);
            exit();
        }

    }

    // 複製資料夾及其內容的遞迴函數
    public function copyDirectory($source, $destination) {
        if (!is_dir($destination)) {
            mkdir($destination, 0777, true); // 如果目標資料夾不存在，就創建它
        }

        $files = scandir($source); // 列出源資料夾中的檔案

        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $source_file = $source . '/' . $file;
                $target_file = $destination . '/' . $file;

                if (is_dir($source_file)) {
                    $this->copyDirectory($source_file, $target_file); // 如果是資料夾，則遞迴複製
                } else {
                    copy($source_file, $target_file); // 如果是檔案，則複製檔案
                }
            }
        }
    }




    // 刪除資料夾及其內容的函數
    public function deleteDirectory($dir) {
        if (is_dir($dir)) {
            $files = scandir($dir); // 列出資料夾中的檔案

            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    $file_path = $dir . '/' . $file;
                    if (is_dir($file_path)) {
                        $this->deleteDirectory($file_path); // 如果是資料夾，遞迴刪除
                    } else {
                        unlink($file_path); // 如果是檔案，則刪除檔案
                    }
                }
            }
            rmdir($dir); // 刪除空的資料夾
        }
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

    public function Import_Config() {

        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }

        // 初始化
        $result = '';
        
        // 檢查是否有上傳檔案
        if (empty($_FILES) || !isset($_FILES['file'])) {
            $res_type = 'Error';
            $res_msg = 'No file uploaded.';
            $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);
            exit();
        }
    
        // 檢查檔案名稱
        $file_name = $_FILES['file']['name'];
        $file_info = pathinfo($file_name);
    
        // 檢查檔案的副檔名是否為 .cfg
        if (!isset($file_info['extension']) || strtolower($file_info['extension']) !== 'cfg') {
            $res_type = 'Error';
            $res_msg = 'The uploaded file is not a .cfg file.';
            $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);
            exit();
        }
    
    
        // 檢查操作系統並處理檔案上傳
        if (PHP_OS_FAMILY === 'Linux') {

            $destination = "/mnt/ramdisk/tcccon.cfg";
            //將檔案移到指定位置
            $result =  move_uploaded_file($_FILES['file']['tmp_name'], $destination);

            if ($result) {
                require_once '../modules/phpmodbus-master/Phpmodbus/ModbusMaster.php';
                $modbus = new ModbusMaster("127.0.0.1", "TCP");
                try {
                    $modbus->port = 502;
                    $modbus->timeout_sec = 10;
                    $data = array(1, 26948, 24947);
                    $dataTypes = array("INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT", "INT");

                    // FC 16
                    $modbus->writeMultipleRegister(0, 506, $data, $dataTypes);
                    $this->logMessage('modbus write 506 ,array = '.implode("','", $data));
                    $this->logMessage('modbus status:'.$modbus->status);
                    $this->logMessage('Import config end');
                    //echo json_encode(array('error' => '231005','diff' => $diff));


                    $res_type = 'Success';
                    $res_msg  = 'DB import successful';
                    $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);

                    exit();

                } catch (Exception $e) {
                    // Print error information if any
                    echo $modbus;
                    echo $e;
                    $this->logMessage('modbus write 506 fail');
                    $this->logMessage('modbus status:'.$modbus->status);
                    $this->logMessage('Import config end');
                    echo json_encode(array('error' => 'modbus error','diff' => $diff));
                    exit();
                }
            } else {
                $this->logMessage('copy db error');
                $this->logMessage('Import config end');
                echo json_encode(array('error' => 'copy db error','diff' => $diff));
                exit();
            }


        } else {

            // 指定目標文件名
            $new_file_name = 'idas_data.db';  // 新檔案名稱，不管原檔案名稱如何

            // 在非 Linux 系統中，將上傳的檔案移動到指定路徑
            $destination = "../" . $new_file_name; // 需要替換的檔案位置
    
            // 嘗試將上傳的檔案移動到新的位置並重命名
            $result = move_uploaded_file($_FILES['file']['tmp_name'], $destination);
    
            // 檢查檔案是否成功上傳
            if ($result) {
                $res_type = 'Success';
                $res_msg = 'File uploaded and renamed successfully to ' . $new_file_name . '.';
                $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);  // 假設有這個成功的回應方法
            } else {
                $res_type = 'Error';
                $res_msg = 'Failed to move the uploaded file.';
                $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);
            }
        }
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
                    // Print error information if any
                    // echo $modbus;
                    // echo $e;
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


    public function get_controller_login(){

        //判斷控制器是否有登出
        $Controller_Info = $this->ToolModel->GetControllerInfo();
        if(!empty($Controller_Info)){
            $user_logIn = $Controller_Info['user_logIn'];
            $user_logIn = (int)$user_logIn;
            echo $user_logIn;
        }
    }
}
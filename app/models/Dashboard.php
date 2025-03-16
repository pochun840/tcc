<?php

class Dashboard{
    //private $db_dev;//devdb tool
    private $db_data;//devdb tool
    private $dbh;

    // 在建構子將 Database 物件實例化
    public function __construct(){

        $this->db_data = new Database;
        $this->db_data = $this->db_data->getDb_data();

    }

    //驗證job id是否重複
    public function get_last_data()
    {
        $sql = "SELECT * FROM data ORDER BY system_sn DESC LIMIT 1";
        $statement = $this->db_data->prepare($sql);
        $results = $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    //get tool max,min rpm
    public function get_tool_info()
    {
        $sql = "SELECT *,
                   CASE tool_minrpm 
                       WHEN '20' 
                           THEN '60' 
                       ELSE '60' 
                   END tool_minrpm 
                FROM tool_info";
        $statement = $this->db_dev->prepare($sql);
        $results = $statement->execute();
        $rows = $statement->fetch(PDO::FETCH_ASSOC);

        return $rows;
    }

    //return datalog csv for graph
    public function get_device_datalog_frequency()
    {
        $sql = "SELECT device_datalog_frequency  FROM device ";
        $statement = $this->db->prepare($sql);
        $results = $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row['device_datalog_frequency'];
       
    }

    //get tool max,min rpm
    public function get_tool_info_unit_convert()
    {
        $sql = "SELECT * FROM tool_info";
        $statement = $this->db_dev->prepare($sql);
        $results = $statement->execute();
        $rows = $statement->fetch(PDO::FETCH_ASSOC);

        $sql2 = "SELECT device_torque_unit FROM device";
        $statement2 = $this->db->prepare($sql2);
        $results2 = $statement2->execute();
        $rows2 = $statement2->fetch(PDO::FETCH_ASSOC);

        // device_torque_unit
        // 0: 公斤米
        // 1: 牛頓米 起子預設是牛頓米
        // 2: 公斤公分
        // 3: 英鎊英寸

        //使用時機 1. output 輸出給前端時，要依據目前系統的扭力單位設定，顯示對應的扭力數值
        //使用時機 2. input 寫入資料庫時，要依據目前系統的扭力單位設定，將數值轉換為牛頓米寫到資料庫中


        return $rows;
    }

    public function get_csv_first_column() {
        
        //ini_set('memory_limit', '256M');

        $first_column = array();
        $directory = "/mnt/ramdisk/ftp/"; // CSV 檔案所在目錄
    
        // 取得最新的 CSV 檔案
        $files = glob($directory . "DATALOG_*_DEVICE_*_25.csv");
        if (empty($files)) {
            return null; // 如果沒有找到 CSV 檔案，回傳 null
        }
    
        // 根據檔案修改時間排序，取得最新的檔案
        usort($files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });
    
        $latestFile = $files[0]; // 最新的 CSV 檔案
    
        // 讀取 CSV 檔案內容
        $csvdata_tmp = file_get_contents($latestFile);
    
        if (empty($csvdata_tmp)) {
            return null; // 如果檔案內容為空，回傳 null
        }
    
        $lines = explode("\n", $csvdata_tmp); // 將檔案內容按行拆分
        $csv_array = array_map('str_getcsv', $lines); // 將每行轉換為 CSV 陣列格式
    
        // 取得每行的第一個欄位 (即 A 欄位)
        foreach ($csv_array as $subarray) {
            if (isset($subarray[0])) { // 確認該行有數據
                $first_column[] = $subarray[0]; // 將 A 欄位資料加入結果陣列
            }
        }
    
        return $first_column; // 回傳第一欄的資料陣列
    }
    


    public function get_csv_selected_columns($no) {
        $selected_columns = array(
            'step' => array(),
            'torque' => array(),
            'angle' => array()
        );  // 用於儲存 F, G, H 欄位的數據
    
        // 檔案類型
        $file_arr = array('_0p5', '_1p0', '_2p0');
    
        foreach ($file_arr as $v_f) {
            $infile = "../public/data/DATALOG_20241220150526_DEVICE_" . $no . $v_f . ".csv";
            if (file_exists($infile)) {
                $csvdata_tmp = file_get_contents($infile);
    
                if (!empty($csvdata_tmp)) {
                    $csvdata = $csvdata_tmp;
                    $lines = explode("\n", $csvdata);
                    $csv_array = array_map('str_getcsv', $lines);
    
                    // 取得每行的 F, G, H 欄位 (即 5, 6, 7 索引)
                    foreach ($csv_array as $subarray) {
                        // 確保該行有足夠的欄位數據 (至少 8 欄)
                        if (isset($subarray[5]) && isset($subarray[6]) && isset($subarray[7])) {
                            $selected_columns['step'][] = $subarray[5]; // 'step' 欄位數據
                            $selected_columns['torque'][] = $subarray[6]; // 'torque' 欄位數據
                            $selected_columns['angle'][] = $subarray[7]; // 'angle' 欄位數據
                        }
                    }
                    break;  // 若找到檔案後，就退出循環
                }
            }
        }

        array_shift($selected_columns['step']);
        array_shift($selected_columns['torque']);
        array_shift($selected_columns['angle']);
    
        // 只保留前 4 筆數據
        $selected_columns['step'] = array_slice($selected_columns['step'], 0, 4);
        $selected_columns['torque'] = array_slice($selected_columns['torque'], 0, 4);
        $selected_columns['angle'] = array_slice($selected_columns['angle'], 0, 4);

        // 重新索引數組，將索引從 1 開始
        $selected_columns['step'] = array_values($selected_columns['step']);
        $selected_columns['torque'] = array_values($selected_columns['torque']);
        $selected_columns['angle'] = array_values($selected_columns['angle']);

        // 更改索引，使其從 1 開始
        $selected_columns['step'] = array_combine(range(1, count($selected_columns['step'])), $selected_columns['step']);
        $selected_columns['torque'] = array_combine(range(1, count($selected_columns['torque'])), $selected_columns['torque']);
        $selected_columns['angle'] = array_combine(range(1, count($selected_columns['angle'])), $selected_columns['angle']);

    
        return $selected_columns;
    }
    




    public function get_info($chat_mode) {

        $resultarr = array();
        $csv_array = array();
        $directory = "/mnt/ramdisk/ftp/"; // CSV 檔案所在目錄
        
        // 取得最新的 CSV 檔案
        $files = glob($directory . "DATALOG_*_DEVICE_*_25.csv");
        if (empty($files)) {
            return null; // 如果沒有找到 CSV 檔案，回傳 null
        }
        
        // 根據檔案修改時間排序，取得最新的檔案
        usort($files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        
        $latestFile = $files[0]; // 最新的 CSV 檔案

    
        // 讀取 CSV 檔案內容
        $csvdata_tmp = file_get_contents($latestFile);
        
        if (empty($csvdata_tmp)) {
            return null; // 如果檔案內容為空，回傳 null
        }
        
        $lines = explode("\n", $csvdata_tmp); // 將檔案內容按行拆分
        $csv_array = array_map('str_getcsv', $lines); // 將每行轉換為 CSV 陣列格式
    
        $position = (int)$chat_mode; // 轉換 chat_mode 為數字，作為欄位位置
    
        // 如果 chat_mode 是 "5"，先取得 "1" 和 "3" 的結果
        if ($chat_mode == "5") {
            $resultarr['torque'] = $this->get_info("1"); // 取得 chat_mode = "1" 的結果（扭力）
            $resultarr['rpm'] = $this->get_info("3");    // 取得 chat_mode = "3" 的結果（轉速）
        }
    
        // 處理當前 chat_mode 的邏輯
        foreach ($csv_array as $subarray) {
            if (isset($subarray[$position])) { // 檢查該位置是否存在
                if ($chat_mode == "5" || $chat_mode == "6") { // 如果 chat_mode 為 "5" 或 "6"
                    if ($chat_mode == "6" && $position == 6) { 
                        // 如果 chat_mode 為 "6" 且位置為 6，將第 1 欄資料加入 torque
                        $resultarr['torque'][] = $subarray[1];
                    } else {
                        // 否則，將指定位置的資料加入 torque
                        $resultarr['torque'][] = $subarray[$position];
                    }
                } else {
                    $resultarr[] = $subarray[$position];
                }
            }
        }
    
        return $resultarr; // 回傳結果陣列
    }
    



    public function get_data_csv() {

        #CSV 所在的目錄
        $directory = '/mnt/ramdisk/ftp/';
    
        //取得該目錄中的所有文件
        $files = glob($directory . '*.csv');  // 只要.csv檔案
        if (empty($files)) {
            return null;
        }
    
        // 取得最新的檔案
        $latestFile = max($files, function($a, $b) {
            //按照檔案的時間 進行排序
            return filemtime($a) - filemtime($b); 
        });
    
        return $latestFile;
    }
    
}

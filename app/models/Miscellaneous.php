<?php

class Miscellaneous{
    private $db;//condb control box
    private $db_data;//devdb tool
    private $dbh;
    private $db_tools;
    private $db_iDas;
    // 在建構子將 Database 物件實例化
    public function __construct()
    {
        $this->db_iDas = new Database;
        $this->db_iDas = $this->db_iDas->getDb_das();

        $this->db_tools = new Database;
        $this->db_tools = $this->db_tools->getDb_tools();

    }


    public function details($mode){
        
        $array = array();
        if($mode == "rev_direction"){

            $array = array(
                0 => 'CW',
                1 => 'CCW',
                2 => 'Disable'
                
            );
        }

        if($mode == "torque_unit"){
            $array = array(
                0 => 'kgf.m',
                1 => 'N.m',
                2 => 'kgf.cm',
                3 => 'Lbf.in',
                4 => 'cN.m'
                
            );
        }

        if($mode == "torque_unit_name"){
            $array = array(
                0 => 'kgf.m',
                1 => 'N.m',
                2 => 'kgf.cm',
                3 => 'Lbf.in',
                4 => 'cN.m'
                
            );
        }

        
        if($mode == "target_option" ){
            $array = array(
                0 => 'Torque',
                1 => 'Angle',
                2 => 'Delay Time',
                
            );
        }

        if($mode == "target_option_change" ){
            $array = array(
                1 => 'Angle',
                2 => 'Delay Time',
                
            );
        }

        if($mode == "target_option_only_tor" ){
            $array = array(
                0 => 'Torque'
            );
        }

        if($mode =="io_input"){
            $array = array(

                200 => 'Start-IN(Remote)',
                201 => 'Reverse(Remote)',
                202 => 'Disable',
                203 => 'Enable',
                204 => 'Confirm',
                205 => 'Clear',
                206 => 'Sequence Clear',
                207 => 'Reboot',
                208 => 'UserDefine1',
                209 => 'UserDefine2',
                210 => 'Gate Once',
            );
        }

        if($mode =="io_output"){
            $array = array(
                1   => 'OK',
                2   => 'NG',
                3   => 'NG-High',
                4   => 'NG-Low',
                5   => 'OK-Sequence',
                6   => 'OK-Job',
                7   => 'Tool Runing',
                8   => 'Tool Trigger',
                9   => 'Reverse',
                10  => 'UserDefine1',
                11  => 'UserDefine2',
                12  => 'BS',
                13  => 'Barcode',
                14  => 'Turning CW',
                15  => 'Turning CCW',
            );
        }

        if($mode =="chart_mode"){
            $array = array(
                1 => 'Torque/Time(MS)',
                2 => 'Angle/Time(MS)',
                3 => 'RPM/Time(MS)',
                4 => 'Torque/Angle',
            );
        }

        if($mode == "chart_menu"){
            $array = array(
                1 => array('name'=>'Torque Time', 'id'=>'torque_time'),
                2 => array('name'=>'Angle Time',  'id'=>'angle_time'),
                3 => array('name'=>'RPM Time',    'id'=>'rpm_time'),
                4 => array('name'=>'Torque Angle','id'=>'torque_angle'),
            );
        }

        if($mode == "status"){
            $array = array(
                0 => 'INIT', 
                1 => 'READY',
                2 => 'RUNNING',
                3 => 'REVERSE',
                4 => 'OK',
                5 => 'OK-SEQ',
                6 => 'OK-JOB',
                7 => 'NG',
                8 => 'NS',
                9 => 'SETTING',
                10 => 'EOC',
                11 => 'C1',
                12 => 'C1_ERR',
                13 => 'C2',
                14 => 'C2_ERR',
                15 => 'C4',
                16 => 'C4_ERR',
                17 => 'C5',
                18 => 'C5_ERR',
                19 => 'BS'
            );

        }

        if($mode =="lang"){
            $array = array(
                0 => 'English',
                1 => '繁體中文',
                2 => '簡體中文',
            );    
        }


        if($mode =="barcode_mode"){
            $array = array(
                0 => 'BS',
                1 => 'BS (free)',
                2 => 'Switch Job / Seq',
            );    
            
        }

          if($mode =="customize"){
            
            $array = array(
                0  => 'system_sn',
                1  => 'data_time',
                2  => 'device_type',
                3  => 'device_id',
                4  => 'device_name',
                5  => 'tool_type',
                6  => 'tool_sn',
                7  => 'tool_status',
                8  => 'job_id',
                9  => 'job_name',
                10 => 'seq_id',
                11 => 'seq_name',
                12 => 'step_id',
                13 => 'step_tar_type',
                14 => 'step_tar_tor',
                15 => 'step_tar_ang',
                16 => 'step_tar_delay',
                17 => 'step_tor_unit',
                18 => 'fasten_torque',
                19 => 'fasten_angle',
                20 => 'fasten_time',
                21 => 'fasten_status',
                22 => 'error_message',
                23 => 'count_dir',
                24 => 'last_screw_count',
                25 => 'max_screw_count',
                26 => 'step_rpm',
                27 => 'step_tool_dir',
                28 => 'step_hi_tor',
                29 => 'step_lo_tor',
                30 => 'step_hi_ang',
                31 => 'step_lo_ang',
                32 => 'step_th_mode',
                33 => 'step_th_tor',
                34 => 'step_ds_tor',
                35 => 'step_ds_speed',
                36 => 'barcode',
         
            );

            // $lastIndex = empty($array) ? -1 : max(array_keys($array));
            // $idx = $lastIndex + 1;   // 這裡會是 43
            // for ($i = 1; $i <= 5; $i++) {
            //     $array[$idx++] = "step{$i}_last_torque";
            //     $array[$idx++] = "step{$i}_last_angle";
            // }



        }
        


        return $array;

    }

    #驗證name 
    public function validateName($jobName){
        if (!empty($jobName)) {
            if (preg_match('/^[a-zA-Z0-9-]+$/', $jobName)) {
                if (strlen($jobName) > 12) {
                    return  false;
                } else {
                    return true; 
                }
            } else {
                return false;
            }
        }else{
            return false;
        }
    }

    public function validateUnscrewPower($unscrewPower) {
        if (is_numeric($unscrewPower)) {
            if ($unscrewPower > 0 && $unscrewPower <= 10) {
                return true; 
            } else {
                return false; 
            }
        } else {
            return false; 
        }
    }


    public function seq_validate($value, $type) {
        switch ($type) {
            // Seq_name
            case 'name':
                return !empty($value) && 
                       preg_match('/^[a-zA-Z0-9-]+$/', $value) && 
                       strlen($value) <= 12;
    
            // 顆數
            case 'seq_tr':
                return is_numeric($value) && 
                       $value >= 1 && 
                       $value <= 99;
            
            //join_val
            case 'join_val':
                return !empty($value);
                

            // OKTIME
            case 'okTime':
                return is_numeric($value) && 
                       $value >= 0.0 && 
                       $value <= 9.9;
    
            // K_value
            case 'kValue':
                return is_numeric($value) && 
                       $value >= 30 && 
                       $value <= 300;
    
            // offset
            case 'seq_ofs':
                return is_numeric($value) && 
                       $value >= -254 && 
                       $value <= 254;
    
            default:
                return false;
        }
    }


    #扭力單位轉換
    public function unitarr_change($torValue, $inputType, $TransType){
        
        $inputType = (int)$inputType;
        $TransType = (int)$TransType;

        $new_TorqueUnit = [
            "kgf.m"  => 0,
            "N.m"    => 1,
            "kgf.cm" => 2,
            "Lbf.in" => 3,
            "cN.m"   => 4
        ];

        $convertedValues = [];
        $is_single = !is_array($torValue);
        if ($is_single) {
            $torValue = [$torValue];
        }

        foreach ($torValue as $value) {
            if (!is_numeric($value)) {
                continue; // 或 throw new \Exception("無效數值：$value");
            }

            $tor = floatval($value);

            if ($inputType === $new_TorqueUnit["N.m"]) {
                if ($TransType === $new_TorqueUnit["kgf.m"]) {
                    $convertedValues[] = round($tor * 0.102, 4);
                } elseif ($TransType === $new_TorqueUnit["kgf.cm"]) {
                    $convertedValues[] = round($tor * 10.2, 2);
                } elseif ($TransType === $new_TorqueUnit["lbf.in"]) {
                    $convertedValues[] = round($tor * 10.2 * 0.86805, 2);
                } elseif ($TransType === $new_TorqueUnit["N.m"]) {
                    $convertedValues[] = round($tor, 3);
                } elseif ($TransType === $new_TorqueUnit["cN.m"]) {
                    $convertedValues[] = round(round($tor * 10.2, 2) * 9.80392156, 1);
                }
            } elseif ($inputType === $new_TorqueUnit["kgf.m"]) {
                if ($TransType === $new_TorqueUnit["kgf.m"]) {
                    $convertedValues[] = round($tor, 4);
                } elseif ($TransType === $new_TorqueUnit["kgf.cm"]) {
                    $convertedValues[] = round($tor * 100, 2);
                } elseif ($TransType === $new_TorqueUnit["lbf.in"]) {
                    $convertedValues[] = round($tor * 100 * 0.86805, 2);
                } elseif ($TransType === $new_TorqueUnit["N.m"]) {
                    $convertedValues[] = round($tor * 9.80392156, 3);
                } elseif ($TransType === $new_TorqueUnit["cN.m"]) {
                    $convertedValues[] = round(round($tor * 100, 2) * 9.80392156, 1);
                }
            } elseif ($inputType === $new_TorqueUnit["kgf.cm"]) {
                if ($TransType === $new_TorqueUnit["kgf.m"]) {
                    $convertedValues[] = round($tor * 0.01, 4);
                } elseif ($TransType === $new_TorqueUnit["kgf.cm"]) {
                    $convertedValues[] = round($tor, 2);
                } elseif ($TransType === $new_TorqueUnit["lbf.in"]) {
                    $convertedValues[] = round($tor * 0.86805, 2);
                } elseif ($TransType === $new_TorqueUnit["N.m"]) {
                    $convertedValues[] = round($tor * 0.0980392156, 3);
                } elseif ($TransType === $new_TorqueUnit["cN.m"]) {
                    $convertedValues[] = round($tor * 9.80392156, 1);
                }
            } elseif ($inputType === $new_TorqueUnit["lbf.in"]) {
                if ($TransType === $new_TorqueUnit["kgf.m"]) {
                    $convertedValues[] = round($tor * 1.152 * 0.01, 4);
                } elseif ($TransType === $new_TorqueUnit["kgf.cm"]) {
                    $convertedValues[] = round($tor * 1.152, 2);
                } elseif ($TransType === $new_TorqueUnit["lbf.in"]) {
                    $convertedValues[] = round($tor, 2);
                } elseif ($TransType === $new_TorqueUnit["N.m"]) {
                    $convertedValues[] = round($tor * 0.11294117637119998, 3);
                } elseif ($TransType === $new_TorqueUnit["cN.m"]) {
                    $convertedValues[] = round(round($tor * 1.152, 2) * 9.80392156, 1);
                }
            } elseif ($inputType === $new_TorqueUnit["cN.m"]) {
                if ($TransType === $new_TorqueUnit["kgf.m"]) {
                    $convertedValues[] = round(round($tor * 0.102, 2) * 0.01, 4);
                } elseif ($TransType === $new_TorqueUnit["kgf.cm"]) {
                    $convertedValues[] = round($tor * 0.102, 2);
                } elseif ($TransType === $new_TorqueUnit["lbf.in"]) {
                    $convertedValues[] = round(round($tor * 0.102, 2) * 0.86805, 2);
                } elseif ($TransType === $new_TorqueUnit["N.m"]) {
                    $convertedValues[] = round(round($tor * 0.102, 2) * 0.0980392156, 3);
                } elseif ($TransType === $new_TorqueUnit["cN.m"]) {
                    $convertedValues[] = round($tor, 1);
                }
            }
        }
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
        return $is_single ? (string)$convertedValues[0] : $convertedValues;
    }


    public function convert_all_torque_units($value, $inputType, $useExcelMode = true) {

        $unit_names = [
            0 => "kgf.m",
            1 => "N.m",
            2 => "kgf.cm",
            3 => "Lbf.in",
            4 => "cN.m"
        ];

        $decimals = [
            0 => 4, // kgf.m
            1 => 3, // N.m
            2 => 2, // kgf.cm
            3 => 2, // Lbf.in
            4 => 1  // cN.m
        ];

        if (!is_numeric($value) || !isset($unit_names[$inputType])) {
            return "Invalid input.";
        }

        $value = floatval($value);

        // Step 1: 輸入值轉為 N.m（統一中介單位）
        switch ($inputType) {
            case 0: $Nm = $value * 9.80665; break;             // kgf.m → N.m
            case 1: $Nm = $value; break;                       // N.m
            case 2: $Nm = $value * 0.0980665; break;           // kgf.cm → N.m
            case 3: $Nm = $value * 0.112984829333; break;      // Lbf.in → N.m
            case 4: $Nm = $value * 0.01; break;                // cN.m → N.m
            default: return "Invalid unit index.";
        }

        $result = [];

        // Step 2: N.m → 其他單位（用 Excel 模擬倍率）
        foreach ($unit_names as $targetType => $unitName) {
            if ($useExcelMode) {
                switch ($targetType) {
                    case 0: $converted = $Nm * 0.10197; break;        // N.m → kgf.m (Excel)
                    case 1: $converted = $Nm; break;                  // N.m
                    case 2: $converted = $Nm * 10.2; break;           // N.m → kgf.cm (Excel)
                    case 3: $converted = $Nm * 8.85411; break;        // N.m → Lbf.in (Excel)
                    case 4: $converted = $Nm * 100; break;            // N.m → cN.m
                }
            } else {
                switch ($targetType) {
                    case 0: $converted = $Nm / 9.80665; break;        // N.m → kgf.m
                    case 1: $converted = $Nm; break;                  // N.m
                    case 2: $converted = $Nm / 0.0980665; break;      // N.m → kgf.cm
                    case 3: $converted = $Nm / 0.112984829333; break; // N.m → Lbf.in
                    case 4: $converted = $Nm * 100; break;            // N.m → cN.m
                }
            }

            // 四捨五入 & 去除尾端多餘 0
            $rounded = round($converted, $decimals[$targetType]);
            $result[$unitName] = number_format($rounded, $decimals[$targetType], '.', '');
            // ✅ 如果是整數，直接轉 int；否則去除多餘 0
            /*if (fmod($rounded, 1) == 0.0) {
                $result[$unitName] = (string)(int)$rounded;
            } else {
                $result[$unitName] = rtrim(rtrim((string)$rounded, '0'), '.');
            }*/

        }

        return $result;
    }



   

    public function get_unit_name_by_index($index) {
        $unit_map = [
            0 => "kgf.m",
            1 => "N.m",
            2 => "kgf.cm",
            3 => "Lbf.in",
            4 => "cN.m"
        ];
        return isset($unit_map[$index]) ? $unit_map[$index] : null;
    }


    public function batch_convert_grouped_by_unit_chart(?array $values, int $inputType){
        
        if (empty($values) || !is_array($values)) {
            return [
                "kgf.m"  => [],
                "N.m"    => [],
                "kgf.cm" => [],
                "lbf.in" => [],
                "cN.m"   => [],
            ];
        }

        $unit_keys = ["kgf.m", "N.m", "kgf.cm", "lbf.in", "cN.m"];
        $result = array_fill_keys($unit_keys, []);

        foreach ($values as $val) {
            if (!is_numeric($val)) continue;

            $converted = $this->convert_all_torque_units($val, $inputType);
            foreach ($converted as $unit => $convertedValue) {
                $result[$unit][] = $convertedValue;
            }
        }

        return $result;
    }




    public function lang_load() {
        $language = $_COOKIE['language'] ?? 'en-us';
        $language = preg_replace('/[^a-zA-Z0-9_-]/', '', $language);

        $language_file = '../app/language/' . $language . '.php';

        return file_exists($language_file) ? $language_file : null;
    }


    public function generateErrorResponse($errorType, $errorMessage) {
        $response = array(
            'res_type' => $errorType,
            'res_msg'  => $errorMessage
        );
        echo json_encode($response);
    }   

    public function generateErrorResponse_1($errorType, $errorMessage,$res_number) {
        $response = array(
            'res_type' => $errorType,
            'res_msg'  => $errorMessage,
            'res_number' => $res_number
        );
        echo json_encode($response);
    }   


    
    public function get_tcc_controller_login(){
        
        $sql = "SELECT * FROM device_info";
        $statement = $this->db_tools->prepare($sql);
        $results = $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row;
    

    }
     
}

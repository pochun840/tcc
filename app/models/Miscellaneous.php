<?php

class Miscellaneous{
    private $db;//condb control box
    private $db_data;//devdb tool
    private $dbh;

    // 在建構子將 Database 物件實例化
    public function __construct()
    {
        $this->db_iDas = new Database;
        $this->db_iDas = $this->db_iDas->getDb_das();

    }


    public function details($mode){
        
        $array = array();
        if($mode == "unscrew_direction"){

            $array = array(
                0 => 'CW',
                1 => 'CCW'
                
            );
        }


        if($mode == "torque_unit"){
            $array = array(
                0 => 'kgf.cm',
                1 => 'lbf.in',
                2 => 'kgf.m',
                3 => 'N.m',
                
            );
        }

        if($mode == "target_option" ){
            $array = array(
                0 => 'Torque',
                1 => 'Angle',
                2 => 'Delay Time',
                
            );
        }

        if($mode =="io_input"){
            $array = array(
                101 => 'Disable',
                102 => 'Enable',
                103 => 'Clear',
                104 => 'Confirm',
                105 => 'Start-IN(Remote)',
                106 => 'Unscrew(Remote)',
                107 => 'Sequence Clear',
                108 => 'Reboot',
                109 => 'Gate Once',
                110 => 'UserDefine1',
                111 => 'UserDefine2',
                112 => 'UserDefine3',
                113 => 'UserDefine4',
                114 => 'UserDefine5',
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
                10  => 'BS',
                11  => 'Barcode',
                12  => 'UserDefine1',
                13  => 'UserDefine2',
                14  => 'UserDefine3',
                15  => 'UserDefine4',
                16  => 'UserDefine5',
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
            if ($unscrewPower >= 0 && $unscrewPower <= 10) {
                return true; 
            } else {
                return false; 
            }
        } else {
            return false; 
        }
    }

    #扭力單位轉換
    public function unitarr_change($torValues, $inputType, $TransType){
        
        $inputType = (int)$inputType;
        $TransType = (int)$TransType;

        
        $TorqueUnit = [
            "N_M" => 1,
            "KGF_M" => 0,
            "KGF_CM" => 2,
            "LBF_IN" => 3
        ];

        $convertedValues = array();
        foreach($torValues as $torValue) {
           
            $torValue = floatval($torValue);
           
            if ($inputType == $TorqueUnit["N_M"]) {
                if ($TransType == $TorqueUnit["KGF_M"]) {
                
                    $convertedValues[] = round($torValue * 0.102, 4);
                } elseif ($TransType == $TorqueUnit["KGF_CM"]) {
                
                    $convertedValues[] = round($torValue * 10.2, 2);
                } elseif ($TransType == $TorqueUnit["LBF_IN"]) {
                  
                    $convertedValues[] = round($torValue * 10.2 * 0.86805, 2);
                } elseif ($TransType == $TorqueUnit["N_M"]) {
                  
                    $convertedValues[] = round($torValue, 3);
                }
            } 
            
            elseif ($inputType == $TorqueUnit["KGF_M"]) {
                if ($TransType == $TorqueUnit["KGF_M"]) {
                    $convertedValues[] = round($torValue, 4);
                } elseif ($TransType == $TorqueUnit["KGF_CM"]) {
                    $convertedValues[] = round($torValue * 100, 2);
                } elseif ($TransType == $TorqueUnit["LBF_IN"]) {
                    
                    $convertedValues[] = round($torValue * 100 * 0.86805, 2);
                } elseif ($TransType == $TorqueUnit["N_M"]) {
                    $convertedValues[] = round($torValue * 9.80392156, 3);
                }
            }

            elseif ($inputType == $TorqueUnit["KGF_CM"]) {
                if ($TransType == $TorqueUnit["KGF_M"]) {
                    $convertedValues[] = round($torValue * 0.01, 4);
                } elseif ($TransType == $TorqueUnit["KGF_CM"]) {
                    $convertedValues[] = round($torValue, 2);
                } elseif ($TransType == $TorqueUnit["LBF_IN"]) {
                    $convertedValues[] = round($torValue * 0.86805, 2);
                } elseif ($TransType == $TorqueUnit["N_M"]) {
                    $convertedValues[] = round($torValue * 0.0980392156, 3);
                }
            }

            elseif ($inputType == $TorqueUnit["LBF_IN"]) {
                
                if ($TransType == $TorqueUnit["KGF_M"]) {
                    $convertedValues[] = round($torValue * 1.152 * 0.01, 4);
                } elseif ($TransType == $TorqueUnit["KGF_CM"]) {
                    $convertedValues[] = round($torValue * 1.152, 2);
                } elseif ($TransType == $TorqueUnit["LBF_IN"]) {
                    $convertedValues[] = round($torValue, 2);
                } elseif ($TransType == $TorqueUnit["N_M"]) {
                    $convertedValues[] = round($torValue * 0.11294117637119998, 3);
                }
            }
        }

        return $convertedValues;
    }





    
}

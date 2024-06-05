<?php

class Miscellaneous{
    private $db;//condb control box
    private $db_dev;//devdb tool
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

        return $array;


    }

    #驗證name 
    function validateName($jobName){
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

    function validateUnscrewPower($unscrewPower) {
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




    
}

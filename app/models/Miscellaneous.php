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
        if($mode=="unscrew_direction"){

            $array = array(
                0 => 'CW',
                1 => 'CCW'
                
            );
        }


        if($mode ="torque_unit"){
            $array = array(
                0 => 'kgf.cm',
                1 => 'lbf.in',
                2 => 'kgf.m',
                3 => 'N.m',
                
            );
        }

        return $array;


    }


    
}

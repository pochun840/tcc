<?php

class Database
{
    // 定義一些操作 Database 的變數，例如：
    private $dbh;
    private $stmt;
    private $error;

    //private $db_con;// db con
    //private $db_dev;// db dev
    private $db_data;// db dev
    private $db_iDas;//iDas db
    private $db_iDas_login;
    private $db_tools;
    public function __construct()
    {
        // 透過 PDO 建立資料庫連線
  

        // 透過 PHP_OS_FAMILY 判斷，目前執行的系統，決定要採用的DB路徑
        
        $Year = date("Y");// data db 用西元年命名
        $data_db_name = "data".$Year.".db";
        if( PHP_OS_FAMILY == 'Linux'){
            if(file_exists('sqlite:/var/www/html/database/tcccon.db') ){
                $source = '/var/www/html/database/tcccon.db';
                $destination = '/var/www/html/database/idas_data.db';
                copy($source, $destination);
                $this->db_iDas = new PDO('sqlite:' . $destination);
            }

            $this->db_iDas_login = new PDO('sqlite:/var/www/html/database/itccdev.db'); 
            $this->db_tools = new PDO('sqlite:/var/www/html/database/tccdev.db');

            if( file_exists('/var/www/html/database/'.$data_db_name) ){
                $this->db_data = new PDO('sqlite:/var/www/html/database/'.$data_db_name); 
            }

            if (!file_exists('/var/www/html/database/idas_data.db')) {
                $source = '/var/www/html/database/tcccon.db';
                $destination = '/var/www/html/database/idas_data.db';
                copy($source, $destination);
                $this->db_iDas = new PDO('sqlite:' . $destination);
            } else {
                $this->db_iDas = new PDO('sqlite:/var/www/html/database/idas_data.db');
            }

            
        }else{
            //$this->db_con = new PDO('sqlite:../idas_data.db'); 
            if(file_exists('../'.$data_db_name)){
                $this->db_data = new PDO('sqlite:../'.$data_db_name); 
            }

            if (!file_exists('../idas_data.db')) {
                $source = '../tcccon.db';
                $destination = '../idas_data.db';
                copy($source, $destination);
                $this->db_iDas = new PDO('sqlite:' . $destination);
            } else {
                $this->db_iDas = new PDO('sqlite:../idas_data.db');
            }

            $this->db_iDas_login = new PDO('sqlite:../itccdev.db'); 
            $this->db_tools = new PDO('sqlite:../tccdev.db'); 

        }

        $this->db_iDas->exec('set names utf-8'); 
        $this->db_iDas_login->exec('set names utf-8'); 
        $this->db_tools->exec('set names utf-8'); 

    }

    // Prepare statement with query
    

    /*public function getDb_dev() {
        if ($this->db_dev instanceof PDO) {
            return $this->db_dev;
        }
    }*/

    public function getDb_data() {
        if ($this->db_data instanceof PDO) {
            return $this->db_data;
        }
    }

    public function getDb_das() {
        if ($this->db_iDas instanceof PDO) {
            return $this->db_iDas;
        }
    }

    public function getDb_das_login() {
        if ($this->db_iDas_login instanceof PDO) {
            return $this->db_iDas_login;
        }
    }

    public function getDb_tools() {
        if ($this->db_tools instanceof PDO) {
            return $this->db_tools;
        }
    }



}

<?php
class Datas{
    private $db;//condb control box
    private $db_data;//devdb tool
    private $dbh;

    // 在建構子將 Database 物件實例化
    public function __construct(){

        $this->db_data = new Database;
        $this->db_data = $this->db_data->getDb_data();

    }

    public function getData($type){

       
        
        $sql = "SELECT * FROM data ORDER BY data_time DESC LIMIT 100";
        if ($type == 'OK') {
            $sql = "SELECT * FROM (
                        SELECT * FROM data 
                        WHERE fasten_status = 4 OR fasten_status = 5 OR fasten_status = 6 
                        ORDER BY data_time DESC 
                        LIMIT 100
                    ) AS recent_data ORDER BY data_time DESC";
        }
        if ($type == 'NOK') {
            $sql = "SELECT * FROM (
                        SELECT * FROM data 
                        WHERE fasten_status = 7 OR fasten_status = 8 
                        ORDER BY data_time DESC 
                        LIMIT 100
                    ) AS recent_data ORDER BY data_time DESC";
        }

        try {
            $statement = $this->db_data->prepare($sql);
            if ($statement !== false) {
                $statement->execute();
                $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                $statement = null; // 查詢完成後釋放資源
                return $rows;
            } else {
                return array();
            }
        } catch (PDOException $e) {
            if (isset($statement)) {
                $statement = null; // 查詢失敗也釋放資源
            }
            return array();
        }
    }


    public function get_range_data($start_date, $end_date){
            
      

        $sql = "SELECT * FROM data 
                WHERE data_time BETWEEN :start_date AND :end_date
                ORDER BY data_time DESC 
                LIMIT 10000 ";

        try {
            $statement = $this->db_data->prepare($sql);
            if ($statement !== false) {
                $statement->bindParam(':start_date', $start_date);
                $statement->bindParam(':end_date', $end_date);
                $statement->execute();
                $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                $statement = null; // 釋放資源
                return $rows;
            } else {
                return array();
            }
        } catch (PDOException $e) {
            if (isset($statement)) {
                $statement = null; // 發生錯誤也釋放資源
            }
            return array();
        }
    }


    public function get_new_info() {

  

        if (is_null($this->db_data)) {
            return null;
        }
    
        $sql = "SELECT * FROM data ORDER BY system_sn DESC LIMIT 1";
    
        try {
            $statement = $this->db_data->prepare($sql);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC); 
            $statement = null; // 釋放資源
            return $result ?: null;
    
        } catch (PDOException $e) {
            return null;
        }
    }
    


    public function get_operation_info() {

        
        if (is_null($this->db_data)) {
            return null;
        }
    
        $sql = "SELECT * FROM data ORDER BY system_sn DESC LIMIT 1";
    
        try {
            $statement = $this->db_data->prepare($sql);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC); 
            $statement = null; // 釋放資源
            return $result ?: null; // 沒資料也回傳 null
        } catch (PDOException $e) {
            $statement = null; // 釋放資源
            return null; // 發生錯誤也回傳 null
        }
    }
}
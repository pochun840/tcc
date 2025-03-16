<?php

class Input{
    private $db_iDas;
    //private $db_iDas_device;

    // 在建構子將 Database 物件實例化
    public function __construct(){

        $this->db_iDas = new Database;
        $this->db_iDas = $this->db_iDas->getDb_das();

        /*$this->db_iDas_device = new Database;
        $this->db_iDas_device = $this->db_iDas_device->getDb_das_device();*/


    }

    //get_input_by_job_id
    public function get_input_by_job_id($job_id)
    {   
        $sql = "SELECT * FROM input WHERE input_jobid = ? ORDER BY CASE WHEN input_event >= 200 THEN 0 ELSE 1 END, input_event";
        $statement = $this->db_iDas->prepare($sql);
        $results = $statement->execute([$job_id]);
        $row = $statement->fetchall(PDO::FETCH_ASSOC);

        return $row;
    }

    //get device_input_alljob
    public function get_input_alljob()
    {   
        $sql = "SELECT * FROM device";
        $statement = $this->db_iDas->prepare($sql);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    //get all job
    public function get_job_list()
    {
        $sql = " SELECT  * FROM job  ORDER BY job_id ASC ";
        $statement = $this->db_iDas->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        return $result;
    }

    public function check_job_event_conflict($input_jobid,$input_event){
        
        $sql = "SELECT *  FROM input WHERE input_jobid = ? AND input_event = ?";
        $statement = $this->db_iDas->prepare($sql);
        $statement->execute([$input_jobid,$input_event]);
        $rows = $statement->fetch();

        return $rows;
    }

    public function check_job_event($input_jobid){
        
        $sql = "SELECT *  FROM input WHERE input_jobid = ? ";
        $statement = $this->db_iDas->prepare($sql);
        $statement->execute([$input_jobid]);
        $rows = $statement->fetchAll();

        return $rows;

    }

    public function create_input($input_data){   

        $sql = "INSERT INTO `input` (input_jobid, input_event, input_pin1, input_pin2, input_pin3, input_pin4, input_pin5, input_pin6, input_pin7, input_pin8, input_pin9, input_pin10, input_gateconfirm, input_pagemode, input_seqid) ";
        $sql .= "VALUES (:input_jobid, :input_event, :input_pin1, :input_pin2, :input_pin3, :input_pin4, :input_pin5, :input_pin6, :input_pin7, :input_pin8, :input_pin9, :input_pin10, :input_gateconfirm, :input_pagemode, :input_seqid);";
    
        // 準備 SQL 查詢語句
        $statement = $this->db_iDas->prepare($sql);
    
        // 綁定參數
        $statement->bindValue(':input_jobid', $input_data['input_jobid']);
        $statement->bindValue(':input_event', $input_data['input_event']);
        $statement->bindValue(':input_pin1', isset($input_data['input_pin1'])  ? $input_data['input_pin1'] : 0);
        $statement->bindValue(':input_pin2', isset($input_data['input_pin2'])  ? $input_data['input_pin2'] : 0);
        $statement->bindValue(':input_pin3', isset($input_data['input_pin3'])  ? $input_data['input_pin3'] : 0);
        $statement->bindValue(':input_pin4', isset($input_data['input_pin4'])  ? $input_data['input_pin4'] : 0);
        $statement->bindValue(':input_pin5', isset($input_data['input_pin5'])  ? $input_data['input_pin5'] : 0);
        $statement->bindValue(':input_pin6', isset($input_data['input_pin6'])  ? $input_data['input_pin6'] : 0);
        $statement->bindValue(':input_pin7', isset($input_data['input_pin7'])  ? $input_data['input_pin7'] : 0);
        $statement->bindValue(':input_pin8', isset($input_data['input_pin8'])  ? $input_data['input_pin8'] : 0);
        $statement->bindValue(':input_pin9', isset($input_data['input_pin9'])  ? $input_data['input_pin9'] : 0);
        $statement->bindValue(':input_pin10',isset($input_data['input_pin10']) ? $input_data['input_pin10'] : 0);


        $statement->bindValue(':input_gateconfirm', $input_data['input_gateconfirm']);
        $statement->bindValue(':input_pagemode', $input_data['input_pagemode']);
        $statement->bindValue(':input_seqid', $input_data['input_seqid']);
    
        // 執行 SQL 查詢
        $results = $statement->execute();
    
        return $results;
    }
    

    public function edit_input($jobdata){

        if (!isset($jobdata['input_jobid'], $jobdata['input_event'], $jobdata['input_pin'], $jobdata['input_wave'])) {
            throw new Exception('Missing required fields in jobdata');
        }
    
        $sql = "UPDATE `input` 
                SET input_event = :input_event, 
                    input_wave = :input_wave, 
                    input_pin  = :input_pin
                WHERE input_jobid = :input_jobid";
    
        $statement = $this->db_iDas->prepare($sql);
    
        $statement->bindValue(':input_jobid', $jobdata['input_jobid']);
        $statement->bindValue(':input_event', $jobdata['input_event']);
        $statement->bindValue(':input_pin', $jobdata['input_pin']);
        $statement->bindValue(':input_wave', $jobdata['input_wave']);
    
        $statement->execute();
        $results = $statement->rowCount();  
    
        return $results;
    }
    


    public function copy_input_by_id($from_job_id,$to_job_id){
        // 判斷job_id是否存在，若存在就先把舊的刪除
        // $dupli_flag true:表示job_id已存在 false:表示job_id不存在
        if(true){//先刪除再複製
            $this->delete_input_by_id($to_job_id);
        }
        $sql= "INSERT INTO input ( input_jobid,input_event,input_pin1,input_pin2,input_pin3,input_pin4,input_pin5,input_pin6,input_pin7,input_pin8,input_pin9,input_pin10,input_gateconfirm,input_pagemode,input_seqid )
                SELECT  ?,input_event,input_pin1,input_pin2,input_pin3,input_pin4,input_pin5,input_pin6,input_pin7,input_pin8,input_pin9,input_pin10,input_gateconfirm,input_pagemode,input_seqid
                FROM    input
                WHERE input_jobid = ? ";
        $statement = $this->db_iDas->prepare($sql);

        return $results = $statement->execute([$to_job_id,$from_job_id]);
    }

    //delete input by job_id
    public function delete_input_by_id($job_id){

        $sql= "DELETE FROM input WHERE input_jobid = ?";
        $statement = $this->db_iDas->prepare($sql);
        $results = $statement->execute([$job_id]);

        return $results;
    }


    //delete input by job_id and event_id
    public function delete_input_event_by_id($job_id,$input_event){
        $sql= "DELETE FROM input WHERE input_jobid = ? AND input_event = ?";
        $statement = $this->db_iDas->prepare($sql);
        $results = $statement->execute([$job_id,$input_event]);

        return $results;
    }

    //set input_alljob
    public function set_input_alljob($input_jobid){
        $sql = "UPDATE device SET device_input_all_job = ?";
        $statement = $this->db_iDas_device->prepare($sql);
        $results   = $statement->execute([$input_jobid]);
        return $results;
    }

    public function generateTableCell($input_data) {
        $tableCells = "";
        
        // 循環處理每個 input_pin1 到 input_pin10 
        // input_pin1 沒用到 從 input_pin2 開始
        for ($i = 2; $i <= 10; $i++) {
            // 動態構建 input_pin 名稱
            $pin_name = 'input_pin' . $i;
            
            // 檢查對應的 input_pin 是否有值
            if (!empty($input_data[$pin_name])) {
            
                if ($input_data[$pin_name] == 1) {
                    $img = '<img src="./img/high.png" style="max-width: 50px;">';
                } else if ($input_data[$pin_name] == 2) {
                    $img = '<img src="./img/low.png" style="max-width: 50px;">';
                }
                $tableCells .= "<td>".$img."</td>";
            } else {
                $tableCells .= "<td></td>";
            }
        }
        return $tableCells;
    }
    
}

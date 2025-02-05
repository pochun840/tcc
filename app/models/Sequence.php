<?php

class Sequence{
    private $db;//condb control box
    private $dbh;
    private $tool_max_rpm;
    private $tool_min_rpm;
    private $db_iDas;

    // 在建構子將 Database 物件實例化
    public function __construct()
    {
        $this->db_iDas = new Database;
        $this->db_iDas = $this->db_iDas->getDb_das();

    }

    #取得所有sequences
    public function getSequences_by_job_id($job_id){

        $sql ="SELECT seq.*,count(ns.seq_id) as total_step FROM sequence as seq LEFT JOIN step as ns ON seq.seq_id = ns.seq_id AND seq.job_id = ns.job_id WHERE seq.job_id = '".$job_id."' group by seq.job_id,seq.seq_id ";
        $statement = $this->db_iDas->prepare($sql);
        $statement->execute();
        return $statement->fetchall();

    }

    #透過 job_id  取得當前有幾個seq
    public function countseq($jobid ){
        $sql = "SELECT COUNT(*) as count FROM sequence WHERE job_id = ? ";
        $statement = $this->db_iDas->prepare($sql);
        $statement->execute([$jobid]);
        $result = $statement->fetch();
        return $result['count'];
    }

    #新增sequence
    public function create_seq($mode, $jobdata) {


        if(intval($jobdata['job_id']) > 50 || intval($jobdata['seq_id']) > 50) {
           
            return false; 
        }

        $sql = "INSERT INTO `sequence` (job_id, seq_id, seq_name, seq_en, tr, ns, seq_ok, stop_seq_ok, opt, k_value, ofs)";
        $sql .= " VALUES (:job_id, :seq_id, :seq_name, :seq_en, :tr, :ns, :seq_ok, :stop_seq_ok, :opt, :k_value, :ofs);";
        $statement = $this->db_iDas->prepare($sql);
    

        if ($mode == "create") {
            $statement->bindValue(':job_id', $jobdata['job_id']);
            $statement->bindValue(':seq_id', $jobdata['seq_id']);
            $statement->bindValue(':seq_name', $jobdata['seq_name']);

        }else if($mode =="copy"){
            
            $statement->bindValue(':job_id', $jobdata['job_id']);
            $statement->bindValue(':seq_id', $jobdata['seq_id']);
            $statement->bindValue(':seq_name', $jobdata['seq_name']);
        }
    
        $statement->bindValue(':tr', $jobdata['tr']);
        $statement->bindValue(':seq_ok', $jobdata['seq_ok']);
        $statement->bindValue(':stop_seq_ok', $jobdata['stop_seq_ok']);
        $statement->bindValue(':ns', $jobdata['ns']);
        $statement->bindValue(':seq_en', $jobdata['seq_en']);
        $statement->bindValue(':opt', $jobdata['opt']);
        $statement->bindValue(':k_value', $jobdata['k_value']);
        $statement->bindValue(':ofs', $jobdata['ofs']);
    
        $results = $statement->execute();

        return $results;

    }

    public function copy_seq_by_seq_id($new_temp_seq){

        $sql = "INSERT INTO `sequence` (job_id, seq_id, seq_name, seq_en, tr, ns, seq_ok, stop_seq_ok, opt, k_value, ofs)";
        $sql .= " VALUES (:job_id, :seq_id, :seq_name, :seq_en, :tr, :ns, :seq_ok, :stop_seq_ok, :opt, :k_value, :ofs);";

        $statement = $this->db_iDas->prepare($sql);
        $insertedrecords = 0; 
        foreach ($new_temp_seq as $seq) {            
            if ($statement->execute($seq)) {
                $insertedrecords++;
            }
        }
        return $insertedrecords;

    }

    public function copy_step_by_seq_id($new_temp_step){

        $sql = "INSERT INTO `step` (job_id, seq_id, step_id, target_option, target_torque, target_angle, target_delaytime, hi_torque, lo_torque, hi_angle, lo_angle, rpm, direction, downshift, threshold_torque, 	downshift_torque,downshift_speed )";
        $sql .= " VALUES (:job_id,:seq_id,:step_id,:target_option,:target_torque,:target_angle,:target_delaytime,:hi_torque,:lo_torque,:hi_angle,:lo_angle,:rpm,:direction,:downshift,:threshold_torque,:downshift_torque,:downshift_speed )";

        $statement = $this->db_iDas->prepare($sql);
        $insertedrecords = 0; 
        foreach ($new_temp_step as $seq) {            
            if ($statement->execute($seq)) {
                $insertedrecords++;
            }
        }
        return $insertedrecords;

    }

    #刪除sequences
    public function delete_seq_by_id($jobid,$seqid){

        
        $sql= " DELETE FROM sequence WHERE job_id = ? AND seq_id = ? ";
        $statement = $this->db_iDas->prepare($sql);
        $results = $statement->execute([$jobid, $seqid]);

        /*if ($seqid != 50 ) {
            $sql_update = "UPDATE sequence  SET seq_id = seq_id - 1 WHERE job_id = ? AND seq_id > ?";
            $statement_update = $this->db_iDas->prepare($sql_update);
            $statement_update->execute([$jobid, $seqid]);
        }*/   
        return $results;

    }

    public function delete_step_by_job_id($jobid,$seqid){

        $sql= "DELETE FROM step WHERE  job_id = ? AND seq_id = ? ";
        $statement = $this->db_iDas->prepare($sql);
        $results = $statement->execute([$jobid, $seqid]);

        return $results;
    }


    #查詢 單筆的sequences
    public function search_seqinfo($jobid,$seqid){

        $sql= " SELECT *  FROM sequence WHERE job_id = ? AND seq_id = ? ";
        $statement = $this->db_iDas->prepare($sql);
        $statement->execute([$jobid, $seqid]);
        
        return $statement->fetchall();

    }

    #修改 sequences
    public function update_seq_by_id($jobdata){


        if(intval($jobdata['job_id']) > 50 || intval($jobdata['seq_id']) > 50) {   
            return false; 
        }

        $sql = "UPDATE `sequence` SET  seq_name = :seq_name,
                                  tr = :tr, 
                                  ns = :ns, 
                                  seq_ok  =:seq_ok,
                                  stop_seq_ok =:stop_seq_ok,
                                  opt = :opt,
                                  k_value = :k_value,
                                  ofs = :ofs
        WHERE job_id = :job_id  AND   seq_id = :seq_id ";


        $statement = $this->db_iDas->prepare($sql);
        $statement->bindValue(':seq_name', $jobdata['seq_name']);
        $statement->bindValue(':tr', $jobdata['tr']);
        $statement->bindValue(':seq_ok', $jobdata['seq_ok']);
        $statement->bindValue(':stop_seq_ok', $jobdata['stop_seq_ok']);
        $statement->bindValue(':ns', $jobdata['ns']);
        $statement->bindValue(':opt', $jobdata['opt']);
        $statement->bindValue(':k_value', $jobdata['k_value']);
        $statement->bindValue(':ofs', $jobdata['ofs']);
        $statement->bindValue(':job_id', $jobdata['job_id']);
        $statement->bindValue(':seq_id', $jobdata['seq_id']);
        $results = $statement->execute();

        return $results;


    }

    #修改單筆的sequence的狀態
    public function check_seq_type($jobid, $seqid, $seq_en) {
        $sql = "UPDATE `sequence` SET seq_en = :seq_en WHERE job_id = :job_id AND seq_id = :seq_id ";
        $statement = $this->db_iDas->prepare($sql);
    
        $statement->bindValue(':seq_en', $seq_en);
        $statement->bindValue(':job_id', $jobid);
        $statement->bindValue(':seq_id', $seqid);
        
        $success = $statement->execute();    
        return $success;
    }

    public function update_seq_type($seq_data) {
        $sql = "UPDATE `sequence` SET seq_en = :seq_en WHERE job_id = :job_id AND seq_id= :seq_id ";
        $statement = $this->db_iDas->prepare($sql);
    
        $statement->bindValue(':seq_en', $seq_data['seq_en']);
        $statement->bindValue(':job_id', $seq_data['jobid']);
        $statement->bindValue(':seq_id', $seq_data['seqid']);
        
        $success = $statement->execute();    
        return $success;
    }


    #用jobid seqid oldseqname 查詢該筆的所有資料
    public function search_old_data($jobid,$seqid,$oldseqname){

        $sql= " SELECT * FROM sequence WHERE job_id = ? AND seq_id = ? AND seq_name = ? ";
        $statement = $this->db_iDas->prepare($sql);
        $statement->execute([$jobid,$seqid,$oldseqname]);
        $rows = $statement->fetch();

        return $rows;
    }


    public function swapupdate($jobid, $rowInfoArray,$new_info) {
        $temp = array();
        foreach ($rowInfoArray as $k_s => $v_s) {
            $sql = "SELECT seq_id FROM sequence WHERE job_id = ? AND seq_name = ? ";
            $statement = $this->db_iDas->prepare($sql);
            $statement->execute([$jobid, $v_s['seq_name']]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                $new_val = 'New_Value'.($k_s + 1);
                $update_sql = "UPDATE sequence SET seq_id = ? WHERE job_id = ? AND seq_name = ? ";

                $update_statement = $this->db_iDas->prepare($update_sql);
                $update_statement->execute([$new_val, $jobid, $v_s['seq_name']]);


                $rows_count = $update_statement->rowCount();
                if ($rows_count  > 0){
                    $new_val = 'New_Value'.($k_s + 1);
                    $updated_seq_id = preg_replace('/[^0-9]/', '', $new_val);
                    
                    $update_id_sql = "UPDATE sequence SET seq_id = ? WHERE job_id = ? AND seq_name = ? ";
                    $update_id_statement = $this->db_iDas->prepare($update_id_sql);
                    $update_id_statement->execute([$updated_seq_id, $jobid, $v_s['seq_name']]);                  
                }

            }

            //最終再次檢查 強制把 欄位seq_id 不是數字的 通通移除
            $force_update_sql = "UPDATE sequence SET seq_id = CAST(REPLACE(seq_id, 'New_Value', '') AS UNSIGNED) WHERE job_id =  ? ";
            $force_update_statement = $this->db_iDas->prepare($force_update_sql);
            $force_update_statement->execute([$jobid]);
            


        }

        if(!empty($new_info)){

            //var_dump($new_info);die();
            foreach($new_info as $key =>$val){
                $new_val = $key; // 使用陣列的鍵作為 new_val
                $seq_id = $val['seq_id'];

                $sql_select = "SELECT count(*) FROM step WHERE job_id = :jobid AND seq_id = :seq_id";
                $select_statement = $this->db_iDas->prepare($sql_select);

                $select_statement->bindValue(':jobid', $jobid);
                $select_statement->bindValue(':seq_id', $seq_id);
    
                // 執行查詢
                $select_statement->execute();
                $count = $select_statement->fetchColumn();
                if ($count > 0) {

                    $sql_step = "UPDATE step SET seq_id = '".$key."' WHERE job_id = '".$jobid."' AND seq_id = '". $val['seq_id']."' ";
                    $update_statement = $this->db_iDas->prepare($sql_step);
    
                    $update_statement->execute();


                    $sql_step = "UPDATE step SET seq_id = :new_val WHERE job_id = :jobid AND seq_id = :seq_id";
                    $update_statement = $pdo->prepare($sql_step);
                    
                    $update_statement->bindValue(':new_val', $key, PDO::PARAM_INT);
                    $update_statement->bindValue(':jobid', $jobid, PDO::PARAM_INT);
                    $update_statement->bindValue(':seq_id', $seq_id, PDO::PARAM_INT);
        
                    $update_statement->execute();

                    
                }else{
                  
                }

            }

        }
        return true;
   
    }
    
    


    #驗證seq id是否重複
    public function seq_id_repeat($jobid,$seqid)
    {
        $sql = "SELECT count(*) as count FROM sequence WHERE 	job_id AND seq_id = ?";
        $statement = $this->db_iDas->prepare($sql);
        $results = $statement->execute([$jobid,$seqid]);
        $rows = $statement->fetch();

        if ($rows['count'] > 0) {

            //如果有的話
            $sql_d = "DELETE FROM step WHERE  job_id = ? AND seq_id = ? ";
            $statement = $this->db_iDas->prepare($sql_d);
            $results_d = $statement->execute([$jobid, $seqid]);

            return "True"; // seq_id已存在
        }else{
            return "False"; // seq_id不存在
        }


    }


    public function search_stepinfo($jobid,$seqid){

        $sql= " SELECT *  FROM step WHERE job_id = ? AND seq_id = ? ";
        $statement = $this->db_iDas->prepare($sql);
        $statement->execute([$jobid,$seqid]);
        
        return $statement->fetchall();

    }


    #用 $jobid,$newseqid 尋找有沒有對應的資料
    #有的話就刪除唷
    public function del_seq_type($jobid, $newseqid) {
        #查詢資料是否存在
        $sql = "SELECT COUNT(*) FROM sequence WHERE job_id = ? AND seq_id = ?";
        $statement = $this->db_iDas->prepare($sql);
        $statement->execute([$jobid, $newseqid]);
        $count = $statement->fetchColumn();
        $count = intval($count);
       
        //var_dump($count);
        //die();
        if ($count > 0) {
            #如果資料存在，則刪除
            $deleteSql = "DELETE FROM sequence  WHERE job_id = ? AND seq_id = ?";
            $deleteStatement = $this->db_iDas->prepare($deleteSql);
            $deleteStatement->execute([$jobid, $newseqid]);
    
            return true;
        } else {
            return false;
        }
    }

    public function del_step_type($jobid, $newseqid){

        #查詢資料是否存在
        $sql = "SELECT COUNT(*) FROM step WHERE job_id = ? AND seq_id = ?";
        $statement = $this->db_iDas->prepare($sql);
        $statement->execute([$jobid, $newseqid]);
        $count = $statement->fetchColumn();
        $count = intval($count);

          //die();
        if ($count > 0) {
            #如果資料存在，則刪除
            $delete_step_sql = "DELETE FROM step  WHERE job_id = ? AND seq_id = ?";
            $deleteStatement = $this->db_iDas->prepare($delete_step_sql);
            $deleteStatement->execute([$jobid, $newseqid]);
    
            return true;
        } else {
            return false;
        }
    }


    
}

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

        $sql = "INSERT INTO `sequence` (job_id, seq_id, seq_name, seq_en, seq_tr, seq_ns, seq_ok, seq_ok_stop, seq_opt, seq_k_val, seq_ofs, seq_work_limit, seq_dt, seq_tt)";
        $sql .= " VALUES (:job_id, :seq_id, :seq_name, :seq_en, :seq_tr, :seq_ns, :seq_ok, :seq_ok_stop, :seq_opt, :seq_k_val, :seq_ofs, :seq_work_limit, :seq_dt, :seq_tt);";
   
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
    
        $statement->bindValue(':seq_tr', $jobdata['seq_tr']);
        $statement->bindValue(':seq_ok', $jobdata['seq_ok']);
        $statement->bindValue(':seq_ok_stop', $jobdata['seq_ok_stop']);
        $statement->bindValue(':seq_ns', $jobdata['seq_ns']);
        $statement->bindValue(':seq_en', $jobdata['seq_en']);
        $statement->bindValue(':seq_opt', $jobdata['seq_opt']);
        $statement->bindValue(':seq_k_val', $jobdata['seq_k_val']);
        $statement->bindValue(':seq_ofs', $jobdata['seq_ofs']);

        $statement->bindValue(':seq_work_limit', $jobdata['seq_work_limit']);
        $statement->bindValue(':seq_dt', $jobdata['seq_dt']);
        $statement->bindValue(':seq_tt', $jobdata['seq_tt']);


    
        $results = $statement->execute();



        return $results;

    }

    public function copy_seq_by_seq_id($new_temp_seq){

        $sql = "INSERT INTO `sequence` (job_id, seq_id, seq_name, seq_en, seq_tr, seq_ns, seq_ok, seq_ok_stop, seq_opt, seq_k_val, seq_ofs, seq_work_limit, seq_dt, seq_tt)";
        $sql .= " VALUES (:job_id, :seq_id, :seq_name, :seq_en, :seq_tr, :seq_ns, :seq_ok, :seq_ok_stop, :seq_opt, :seq_k_val, :seq_ofs, :seq_work_limit, :seq_dt, :seq_tt);";

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

        $sql = "INSERT INTO `step` (job_id, seq_id, step_id,target_opt, target_tor, target_ang, target_delay, tor_hi, tor_lo, ang_hi, ang_lo, rpm, direction, th_mode, ds_tor, ds_speed, th_tor, record_ang, tor_unit,pnf_set )";
        $sql .= " VALUES (:job_id,:seq_id,:step_id,:target_opt,:target_tor,:target_ang,:target_delay,:tor_hi,:tor_lo,:ang_hi,:ang_lo,:rpm,:direction,:th_mode,:ds_tor,:ds_speed,:th_tor,:record_ang,:tor_unit,:pnf_set)";
        
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

        if ($seqid != 50 ) {
            $sql_update = "UPDATE sequence  SET seq_id = seq_id - 1 WHERE job_id = ? AND seq_id > ?";
            $statement_update = $this->db_iDas->prepare($sql_update);
            $statement_update->execute([$jobid, $seqid]);
        }   

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
                                  seq_tr = :seq_tr, 
                                  seq_ns = :seq_ns, 
                                  seq_ok  =:seq_ok,
                                  seq_ok_stop =:seq_ok_stop,
                                  seq_opt = :seq_opt,
                                  seq_k_val = :seq_k_val,
                                  seq_ofs = :seq_ofs,
                                  seq_work_limit = :seq_work_limit,
                                  seq_dt = :seq_dt,
                                  seq_tt = :seq_tt
        WHERE job_id = :job_id  AND   seq_id = :seq_id ";


        $statement = $this->db_iDas->prepare($sql);
        $statement->bindValue(':seq_name', $jobdata['seq_name']);
        $statement->bindValue(':seq_tr', $jobdata['seq_tr']);
        $statement->bindValue(':seq_ok', $jobdata['seq_ok']);
        $statement->bindValue(':seq_ok_stop', $jobdata['seq_ok_stop']);
        $statement->bindValue(':seq_ns', $jobdata['seq_ns']);
        $statement->bindValue(':seq_opt', $jobdata['seq_opt']);
        $statement->bindValue(':seq_k_val', $jobdata['seq_k_val']);
        $statement->bindValue(':seq_ofs', $jobdata['seq_ofs']);
        $statement->bindValue(':job_id', $jobdata['job_id']);
        $statement->bindValue(':seq_id', $jobdata['seq_id']);

        $statement->bindValue(':seq_work_limit', $jobdata['seq_work_limit']);
        $statement->bindValue(':seq_dt', $jobdata['seq_dt']);
        $statement->bindValue(':seq_tt', $jobdata['seq_tt']);
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

    public function swapupdate($jobid, $rowInfoArray, $new_info) {
        // 開啟事務
        $this->db_iDas->beginTransaction();
    
        try {
            // 遍歷 $rowInfoArray，更新 sequence 表和 step 表
            foreach ($rowInfoArray as $k_s => $v_s) {
                // 檢查是否存在該 sequence
                $sql = "SELECT seq_id FROM sequence WHERE job_id = ? AND seq_name = ?";
                $statement = $this->db_iDas->prepare($sql);
                $statement->execute([$jobid, $v_s['sequence_name']]);
                $result = $statement->fetch(PDO::FETCH_ASSOC);
    
                if ($result) {
                    $old_seq_id = $result['seq_id']; // 取得舊的 seq_id
    
                    // 生成新的 seq_id
                    $new_val = 'New_Value' . ($k_s + 1);
                    $updated_seq_id = preg_replace('/[^0-9]/', '', $new_val); // 移除 "New_Value" 部分，保留純數字
    
                    // 檢查 $updated_seq_id 是否為 1，如果是，則改為 777
                    if ($updated_seq_id == 1) {
                        $temp_seq_id = 777;
                    } else {
                        $temp_seq_id = $updated_seq_id;
                    }
    
                    // 更新 sequence 表中的 seq_id
                    $update_sql = "UPDATE sequence SET seq_id = ? WHERE job_id = ? AND seq_name = ?";
                    $update_statement = $this->db_iDas->prepare($update_sql);
                    $update_statement->execute([$temp_seq_id, $jobid, $v_s['sequence_name']]);
    
                    // 更新 step 表中的 seq_id (使用 CASE 語句)
                    $update_step_sql = "UPDATE step SET seq_id = CASE
                        WHEN seq_id = :old_seq_id THEN :temp_seq_id
                        ELSE seq_id  -- 保留其他 seq_id 不變
                    END
                    WHERE job_id = :jobid AND seq_id = :old_seq_id";
    
                    $update_step_statement = $this->db_iDas->prepare($update_step_sql);
                    $update_step_statement->bindValue(':temp_seq_id', $temp_seq_id); // 使用 $temp_seq_id
                    $update_step_statement->bindValue(':jobid', $jobid);
                    $update_step_statement->bindValue(':old_seq_id', $old_seq_id);
                    $update_step_statement->execute();
                }
            }
    
            // 遍歷 $rowInfoArray，將 seq_id 為 777 的改回 1
            foreach ($rowInfoArray as $k_s => $v_s) {
                $sql = "SELECT seq_id FROM sequence WHERE job_id = ? AND seq_name = ?";
                $statement = $this->db_iDas->prepare($sql);
                $statement->execute([$jobid, $v_s['sequence_name']]);
                $result = $statement->fetch(PDO::FETCH_ASSOC);
    
                if ($result && $result['seq_id'] == 777) {
                    $update_sql = "UPDATE sequence SET seq_id = 1 WHERE job_id = ? AND seq_name = ?";
                    $update_statement = $this->db_iDas->prepare($update_sql);
                    $update_statement->execute([$jobid, $v_s['sequence_name']]);
    
                    $update_step_sql = "UPDATE step SET seq_id = 1 WHERE job_id = ? AND seq_id = 777";
                    $update_step_statement = $this->db_iDas->prepare($update_step_sql);
                    $update_step_statement->execute([$jobid]);
                }
            }
    
            // 提交事務
            $this->db_iDas->commit();
    
        } catch (Exception $e) {
            // 發生錯誤時回滾事務
            $this->db_iDas->rollBack();
            // 重新拋出異常
            throw $e;
        }
    
        return true;
    }
    
    
    
    #驗證seq id是否重複
    public function seq_id_repeat($jobid, $seqid){
        // 查詢是否有相同的 job_id 和 seq_id
        $sql = "SELECT count(*) as count FROM sequence WHERE job_id = ? AND seq_id = ?";
        $statement = $this->db_iDas->prepare($sql);
        $results = $statement->execute([$jobid, $seqid]);
        $rows = $statement->fetch();

        if ($rows['count'] > 0) {
            // 如果有重複，刪除相關的 step
            $sql_d = "DELETE FROM step WHERE job_id = ? AND seq_id = ?";
            $statement = $this->db_iDas->prepare($sql_d);
            $results_d = $statement->execute([$jobid, $seqid]);

            return "True"; // seq_id已存在
        } else {
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

     //查詢 seq_id 還沒有 被使用的 取出 最小值
     public function get_head_seq_id($job_id) {

        // 檢查 seq_id 是否有 1，如果沒有就直接返回 1
        $query = "SELECT seq_id FROM sequence WHERE job_id ='".$job_id."' AND seq_id = 1 ";
        $statement = $this->db_iDas->prepare($query);
        $statement->execute();
    
        $result = $statement->fetch();
        if (!$result) {
            return array('missing_id' => 1); // 如果 seq_id = 1 不存在，返回 1
        }
    
        // 如果 seq_id = 1 存在，查找最小的可用 seq_id
        $query = "SELECT seq_id + 1 AS missing_id
                  FROM sequence 
                  WHERE (seq_id + 1) NOT IN (SELECT seq_id FROM sequence) AND job_id ='".$job_id."' 
                  ORDER BY missing_id
                  LIMIT 1";
    
        $statement = $this->db_iDas->prepare($query);
        $statement->execute();
    
        return $statement->fetch();
    }
   
}

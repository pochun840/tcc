<?php

class Sequence{
    private $db;//condb control box
    private $db_dev;//devdb tool
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

        $sql ="SELECT * FROM sequence  
                LEFT JOIN  job  on  sequence.job_id = job.job_id 
                WHERE sequence.job_id = '".$job_id."' group by sequence.job_id,sequence.sequence_id  ";
        $statement = $this->db_iDas->prepare($sql);
        $statement->execute();
        return $statement->fetchall();

    }

    #透過job_id 取的當前的step數量 
    public function getStep_by_job_id($job_id){

        $sql = "SELECT  count(job_id)  as total_step FROM step  WHERE job_id ='".$job_id."'";
        $statement = $this->db_iDas->prepare($sql);
        $statement->execute();
        return $statement->fetchall();

    }


    #新增sequence
    public function create_seq($mode, $jobdata) {

        $sql = "INSERT INTO `sequence` (job_id, sequence_id, sequence_name, tightening_repeat, ng_stop, sequence_enable, screw_join, okall_stop, opt, torque_unit, k_value, ok_time, okall_alarm_time, offset)";
        $sql .= " VALUES (:job_id, :sequence_id, :sequence_name, :tightening_repeat, :ng_stop, :sequence_enable, :screw_join, :okall_stop, :opt, :torque_unit, :k_value, :ok_time, :okall_alarm_time, :offset);";
        $statement = $this->db_iDas->prepare($sql);
    

        if ($mode == "create") {
            $statement->bindValue(':job_id', $jobdata['job_id']);
            $statement->bindValue(':sequence_id', $jobdata['sequence_id']);
            $statement->bindValue(':sequence_name', $jobdata['sequence_name']);

        }else if($mode =="copy"){
            
            $statement->bindValue(':job_id', $jobdata['job_id']);
            $statement->bindValue(':sequence_id', $jobdata['sequence_id']);
            $statement->bindValue(':sequence_name', $jobdata['sequence_name']);
        }
    
        $statement->bindValue(':tightening_repeat', $jobdata['tightening_repeat']);
        $statement->bindValue(':ng_stop', $jobdata['ng_stop']);
        $statement->bindValue(':sequence_enable', $jobdata['sequence_enable']);
        $statement->bindValue(':screw_join', $jobdata['screw_join']);
        $statement->bindValue(':okall_stop', $jobdata['okall_stop']);
        $statement->bindValue(':opt', $jobdata['opt']);
        $statement->bindValue(':torque_unit', $jobdata['torque_unit']);
        $statement->bindValue(':k_value', $jobdata['k_value']);
        $statement->bindValue(':ok_time', $jobdata['ok_time']);
        $statement->bindValue(':okall_alarm_time', $jobdata['okall_alarm_time']);
        $statement->bindValue(':offset', $jobdata['offset']);
    
        $results = $statement->execute();

        return $results;

    }

    #刪除sequences
    public function delete_seq_by_id($jobid,$seqid){

        $table_name = 'sequence'; 
        $sql= " DELETE FROM sequence WHERE job_id = ? AND sequence_id = ? ";
        $statement = $this->db_iDas->prepare($sql);
        $results = $statement->execute([$jobid, $seqid]);

        /*
        0if (!$this->is_table_empty($table_name)) {
            #沒有資料，不需要调整 step_id 順序
            return true;
        }*/
        
        return $results;

    }


    #查詢 單筆的sequences
    public function search_seqinfo($jobid,$seqid){

        $sql= " SELECT *  FROM sequence WHERE job_id = ? AND sequence_id = ? ";
        $statement = $this->db_iDas->prepare($sql);
        $statement->execute([$jobid, $seqid]);
        
        return $statement->fetchall();

    }

    #修改 sequences
    public function update_seq_by_id($jobdata){

        $sql = "UPDATE `sequence` SET  sequence_name = :sequence_name,
                                  tightening_repeat = :tightening_repeat, 
                                  ng_stop = :ng_stop, 
                                  screw_join = :screw_join, 
                                  okall_stop = :okall_stop,
                                  opt = :opt,
                                  torque_unit = :torque_unit,
                                  k_value = :k_value,
                                  ok_time = :ok_time,
                                  okall_alarm_time = :okall_alarm_time,
                                  offset = :offset
                WHERE job_id = :job_id  AND   sequence_id = :sequence_id ";


        $statement = $this->db_iDas->prepare($sql);
        $statement->bindValue(':sequence_name', $jobdata['sequence_name']);
        $statement->bindValue(':tightening_repeat', $jobdata['tightening_repeat']);
        $statement->bindValue(':ng_stop', $jobdata['ng_stop']);
        $statement->bindValue(':screw_join', $jobdata['screw_join']);
        $statement->bindValue(':okall_stop', $jobdata['okall_stop']);
        $statement->bindValue(':opt', $jobdata['opt']);
        $statement->bindValue(':torque_unit', $jobdata['torque_unit']);
        $statement->bindValue(':k_value', $jobdata['k_value']);
        $statement->bindValue(':ok_time', $jobdata['ok_time']);
        $statement->bindValue(':okall_alarm_time', $jobdata['okall_alarm_time']);
        $statement->bindValue(':offset', $jobdata['offset']);
        $statement->bindValue(':job_id', $jobdata['job_id']);
        $statement->bindValue(':sequence_id', $jobdata['sequence_id']);
        $results = $statement->execute();

        return $results;


    }

    #修改單筆的sequence的狀態
    public function check_seq_type($jobid, $seqname, $type_value) {
        $sql = "UPDATE `sequence` SET sequence_enable = :sequence_enable WHERE job_id = :job_id AND sequence_name = :sequence_name ";
        $statement = $this->db_iDas->prepare($sql);
    
        $statement->bindValue(':sequence_enable', $type_value);
        $statement->bindValue(':job_id', $jobid);
        $statement->bindValue(':sequence_name', $seqname);
        
        $success = $statement->execute();
    
        return $success;
    }

    #用jobid seqid oldseqname 查詢該筆的所有資料
    public function search_old_data($jobid,$seqid,$oldseqname){

        $sql= " SELECT * FROM sequence WHERE job_id = ? AND sequence_id = ? AND sequence_name = ? ";
        $statement = $this->db_iDas->prepare($sql);
        $statement->execute([$jobid,$seqid,$oldseqname]);
        $rows = $statement->fetch();

        return $rows;
    }


    public function swapupdate($jobid, $rowInfoArray) {
        $temp = array();
        foreach ($rowInfoArray as $k_s => $v_s) {
            $sql = "SELECT sequence_id FROM sequence WHERE job_id = ? AND sequence_name = ? ";
            $statement = $this->db_iDas->prepare($sql);
            $statement->execute([$jobid, $v_s['sequence_name']]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                $new_val = 'New_Value'.($k_s + 1);
                $update_sql = "UPDATE sequence SET sequence_id = ? WHERE job_id = ? AND sequence_name = ? ";
                $update_statement = $this->db_iDas->prepare($update_sql);
                $update_statement->execute([$new_val, $jobid, $v_s['sequence_name']]);
                
                $rows_count = $update_statement->rowCount();
                if ($rows_count  > 0){
                    $new_val = 'New_Value'.($k_s + 1);
                    $updated_sequence_id = preg_replace('/[^0-9]/', '', $new_val);
                    
                    $update_id_sql = "UPDATE sequence SET sequence_id = ? WHERE job_id = ? AND sequence_name = ? ";
                    $update_id_statement = $this->db_iDas->prepare($update_id_sql);
                    $update_id_statement->execute([$updated_sequence_id, $jobid, $v_s['sequence_name']]);
                }
            }

            //最終再次檢查 強制把 欄位sequence_id 不是數字的 通通移除
            $force_update_sql = "UPDATE sequence SET sequence_id = CAST(REPLACE(sequence_id, 'New_Value', '') AS UNSIGNED) WHERE job_id =  ? ";
            $force_update_statement = $this->db_iDas->prepare($force_update_sql);
            $force_update_statement->execute([$jobid]);

        }
        return true;
   
    }
}

<?php

class Steptcc{
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


    #透過 job_id 及 seq_id 取得當前有幾個step
    public function countstep($jobid, $seqid){

        $sql = "SELECT COUNT(*) as count FROM step WHERE job_id = ? AND seq_id = ?";
        $statement = $this->db_iDas->prepare($sql);
        $statement->execute([$jobid, $seqid]);
        $result = $statement->fetch();
        return $result['count'];
    }

    #透過 job_id 及 seq_id 取得當前有幾個step && 第一個step的 target_opt 是不是扭力
    public function check_step_targe_topt($jobid, $seqid) {

        $sql = "SELECT target_opt FROM step WHERE job_id = ? AND seq_id = ? ORDER BY step_id ASC";
        $statement = $this->db_iDas->prepare($sql);
        $statement->execute([$jobid, $seqid]);
        $rows = $statement->fetchAll();

        $step_count = count($rows);
        $first_target_opt = 'N'; // 預設不是 0

        if ($step_count > 0 && $rows[0]['target_opt'] == 0) {
            $first_target_opt = 'Y';
        }

        return [
            'count' => $step_count,
            'first_target_opt' => $first_target_opt
        ];
    }



    #透過job_id 及 seq_id 取得對應的step
    public function getStep($job_id, $seq_id) {

        $sql = "SELECT * FROM step WHERE job_id = ? AND seq_id = ? ORDER BY step_id ASC ";
        $statement = $this->db_iDas->prepare($sql);
        $statement->execute([$job_id, $seq_id]);
        return $statement->fetchAll();
    }

    #透過job_id 及 seq_id 及 step_id取得對應的資料
    public function getStepNo($job_id,$seq_id,$old_step_id){

        $sql = "SELECT * FROM step WHERE job_id = ? AND seq_id = ? AND step_id = ?";
        $statement = $this->db_iDas->prepare($sql);
        $statement->execute([$job_id, $seq_id, $old_step_id]);
        return $statement->fetchAll();

    }

    #檢查同一個seq中所建立的Step Target Torque 只能有一個
    public function check_step_target($jobid,$seqid,$step_id,$check_opt = 0){

        if ($check_opt == 0) {
            $sql = "SELECT COUNT(*) AS count_records FROM step WHERE job_id = ? AND seq_id = ? AND step_id = ? AND target_opt = '0'";
        } else {
            $sql = "SELECT COUNT(*) AS count_records FROM step WHERE job_id = ? AND seq_id = ? AND step_id != ? AND target_opt = '0'";
        }
        $statement = $this->db_iDas->prepare($sql);
        $statement->execute([$jobid, $seqid,$step_id]);
        return $statement->fetchAll();
    }

    public function chek_step_target_torque($job_id,$seq_id){
        $sql = "SELECT COUNT(*) AS counts FROM step WHERE job_id = ? AND seq_id = ?  AND target_opt = '0'";
        $statement = $this->db_iDas->prepare($sql);
        $statement->execute([$job_id, $seq_id]);
        return $statement->fetchAll();
    }





    #COPY專用 檢查被複製的Step_id 有沒有設置Target Torque
    public function check_copy_step($job_id,$seq_id,$old_step_id){
        
        $sql = "SELECT target_opt  FROM step WHERE job_id = ? AND seq_id = ?  AND step_id = ? ";
        $statement = $this->db_iDas->prepare($sql);
        $statement->execute([$job_id,$seq_id,$old_step_id]);
        return $statement->fetchAll();
    }


    #透過 job_id 及 seq_id 及 step_id 刪除對應的資料
    public function delete_step_id($jobid,$seqid,$stepid){

        $sql= " DELETE FROM step WHERE job_id = ? AND seq_id = ?  AND step_id = ? ";
        $statement = $this->db_iDas->prepare($sql);
        $results = $statement->execute([$jobid, $seqid,$stepid]);


        if ($stepid != 4) {
            $sql_update = "UPDATE step SET step_id = step_id - 1 WHERE job_id = ? AND seq_id = ? AND step_id > ?";
            $statement_update = $this->db_iDas->prepare($sql_update);
            $statement_update->execute([$jobid, $seqid, $stepid]);
        }
            
        return $results;


    }


    public function create_step($mode, $jobdata) {
    
    
        if (empty($jobdata['job_id'])) {
            return false; 
        }
        
        $sql = "INSERT INTO `step` (job_id, seq_id, step_id, target_opt, target_tor, target_ang, target_delay, tor_hi, tor_lo, ang_hi, ang_lo, rpm, direction, th_mode, ds_tor, ds_speed, th_tor,record_ang,tor_unit) ";
        $sql .= "VALUES (:job_id, :seq_id, :step_id, :target_opt, :target_tor, :target_ang, :target_delay, :tor_hi, :tor_lo, :ang_hi, :ang_lo, :rpm, :direction, :th_mode, :ds_tor, :ds_speed, :th_tor,:record_ang,:tor_unit);";
    
        // 检查数据库连接
        if ($this->db_iDas === null) {
            echo "数据库连接无效。";
            return false;
        }
    
        $statement = $this->db_iDas->prepare($sql);
        
        // 检查 prepare 是否成功
        if (!$statement) {
            echo "SQL 错误: " . implode(", ", $this->db_iDas->errorInfo());
            return false;
        }
    
        // 強制設置 $jobdata['record_ang'] 為 0 如果不存在
        $jobdata['record_ang'] = isset($jobdata['record_ang']) ? $jobdata['record_ang'] : 0;


        
        $statement->bindValue(':job_id', $jobdata['job_id']);
        $statement->bindValue(':seq_id', $jobdata['seq_id']);
        $statement->bindValue(':step_id', $jobdata['step_id']);
        $statement->bindValue(':target_opt', $jobdata['target_opt']);
        $statement->bindValue(':target_tor', $jobdata['target_tor']);
        $statement->bindValue(':target_ang', $jobdata['target_ang']);
        $statement->bindValue(':target_delay', $jobdata['target_delay']);
        $statement->bindValue(':tor_hi', $jobdata['tor_hi']);
        $statement->bindValue(':tor_lo', $jobdata['tor_lo']);
        $statement->bindValue(':ang_hi', $jobdata['ang_hi']);
        $statement->bindValue(':ang_lo', $jobdata['ang_lo']);
        $statement->bindValue(':rpm', $jobdata['rpm']);
        $statement->bindValue(':direction', $jobdata['direction']);
        $statement->bindValue(':th_mode', $jobdata['th_mode']);
        $statement->bindValue(':ds_tor', $jobdata['ds_tor']);
        $statement->bindValue(':ds_speed', $jobdata['ds_speed']);
        $statement->bindValue(':th_tor', $jobdata['th_tor']);
        $statement->bindValue(':record_ang', $jobdata['record_ang']);
        $statement->bindValue(':tor_unit', $jobdata['tor_unit']);
    
        $results = $statement->execute();
        if (!$results) {
            //echo "执行错误: " . implode(", ", $statement->errorInfo());
            //echo die();
        }
    
        return $results;
    }
    


    public function update_step_by_id($jobdata){


        // 強制設置 $jobdata['record_ang'] 為 0 如果不存在
        $jobdata['record_ang'] = isset($jobdata['record_ang']) ? $jobdata['record_ang'] : 0;

        $sql = "UPDATE `step` SET 
                    target_opt = :target_opt,
                    target_tor = :target_tor, 
                    target_ang = :target_ang, 
                    target_delay = :target_delay, 
                    tor_hi = :tor_hi,
                    tor_lo = :tor_lo,
                    ang_hi = :ang_hi,
                    ang_lo = :ang_lo,
                    rpm = :rpm,
                    direction = :direction,
                    th_mode = :th_mode,
                    th_tor = :th_tor,
                    ds_tor = :ds_tor,
                    ds_speed = :ds_speed,
                    record_ang =:record_ang,
                    tor_unit =:tor_unit
        WHERE job_id = :job_id  AND   seq_id = :seq_id  AND step_id = :step_id ";
        $statement = $this->db_iDas->prepare($sql);

        $statement->bindValue(':job_id', $jobdata['job_id']);
        $statement->bindValue(':seq_id', $jobdata['seq_id']);
        $statement->bindValue(':step_id', $jobdata['step_id']);
        $statement->bindValue(':target_opt', $jobdata['target_opt']);
        $statement->bindValue(':target_tor', $jobdata['target_tor']);
        $statement->bindValue(':target_ang', $jobdata['target_ang']);
        $statement->bindValue(':target_delay', $jobdata['target_delay']);
        $statement->bindValue(':tor_hi', $jobdata['tor_hi']);
        $statement->bindValue(':tor_lo', $jobdata['tor_lo']);
        $statement->bindValue(':ang_hi', $jobdata['ang_hi']);
        $statement->bindValue(':ang_lo', $jobdata['ang_lo']);
        $statement->bindValue(':rpm', $jobdata['rpm']);
        $statement->bindValue(':direction', $jobdata['direction']);
        $statement->bindValue(':th_mode', $jobdata['th_mode']);
        $statement->bindValue(':ds_tor', $jobdata['ds_tor']);
        $statement->bindValue(':ds_speed', $jobdata['ds_speed']);
        $statement->bindValue(':th_tor', $jobdata['th_tor']);
        $statement->bindValue(':record_ang', $jobdata['record_ang']);
        $statement->bindValue(':tor_unit', $jobdata['tor_unit']);
        $results = $statement->execute();


        return $results;


    }


    public function swapupdate($jobid, $rowInfoArray){
        $temp = array();

        // 遍歷每個步驟
        foreach ($rowInfoArray as $k_s => $v_s) {
            // 查詢該工作與該序列的步驟 ID
            $sql = "SELECT step_id FROM step WHERE job_id = ? AND seq_id = ? ";
            $statement = $this->db_iDas->prepare($sql);
            $statement->execute([$jobid, $v_s['sequence_id']]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);

            // 如果找到步驟資料
            if ($result) {
                $new_val = 'New_Value' . ($k_s + 1);
                
                // 更新步驟的 step_id
                $update_sql = "UPDATE step SET step_id = ? WHERE job_id = ? AND seq_id = ? AND step_id = ?";
                $update_statement = $this->db_iDas->prepare($update_sql);
                $update_statement->execute([$new_val, $jobid, $v_s['sequence_id'], $v_s['step_id']]);
            }
        }

        // 在所有更新結束後，將 step_id 中的 "New_Value" 部分移除，只留下數字
        $final_update_sql = "UPDATE step SET step_id = CAST(REPLACE(step_id, 'New_Value', '') AS UNSIGNED) WHERE job_id = ?";
        $final_update_statement = $this->db_iDas->prepare($final_update_sql);
        $final_update_statement->execute([$jobid]);

        return true;
    }


    #輸入tools_id  及 最佳化
    public function check_seq_opt($tools_id,$seq_opt,$torque){

        $tools_arr =array();
        if($tools_id == 4 && $seq_opt == 1){
             

        }

    }


    
    

    
}
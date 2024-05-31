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
    
        // 绑定参数
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

        $sql= " DELETE FROM sequence WHERE job_id = ? AND sequence_id = ? ";
        $statement = $this->db_iDas->prepare($sql);
        $results = $statement->execute([$jobid, $seqid]);
        
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
        $results = $statement->execute([$jobid,$seqid,$oldseqname]);
        $rows = $statement->fetch();

        return $rows;
    }

    public function swapAndUpdate($jobid, $seqname, $seqid_new_values) {
        $temp = array();
        foreach($seqname as $k_s => $v_s) {
            $sql = "SELECT * FROM sequence WHERE job_id = '".$jobid."' AND sequence_name = '".$v_s."' ";
            $statement = $this->db_iDas->prepare($sql);
            $statement->execute(); 
            $result = $statement->fetch();
            if($result) {
                $temp[] = $result; 
            }
        }

        foreach($temp as $k => $record) {
            if (is_array($seqid_new_values)) {
                $mm = $seqid_new_values[$k]+99;
                $mm_array[] = $mm;
                $sql = " UPDATE `sequence` SET sequence_id = '".$mm."' WHERE job_id = '".$record['job_id']."' AND sequence_name = '".$record['sequence_name']."' ";
                $statement = $this->db_iDas->prepare($sql);
                $statement->execute(); 
                //echo "<br>";

            }
        }
        
        /*$new_mm = array();
        foreach ($mm_array as $value) {
            $new_value = intval($value) - 100;
            $new_mm[] = $new_value;
        }

        $keys = array_keys($new_mm);

        foreach ($temp as $kei =>$vei){
            if (isset($keys[$kei])) {
                $nn = $new_mm[$keys[$kei]] + 1;
                $sql1 = " UPDATE `sequence` SET sequence_id = '".$nn."' WHERE job_id = '".$vei['job_id']."' AND sequence_name = '".$vei['sequence_name']."' ";
                //echo $sql1;
                $statement = $this->db_iDas->prepare($sql1);
                $statement->execute(); 
                echo "<br>";

            }
       

        }*/
        //var_dump($new_mm);die();



        //return true;
    }

   // echo $sql 得到
    //UPDATE `sequence` SET sequence_id = '101' WHERE job_id = '3' AND sequence_name = 'Seq-301'
    //UPDATE `sequence` SET sequence_id = '100' WHERE job_id = '3' AND sequence_name = 'Seq-300'
    //想要再變成
    //UPDATE `sequence` SET sequence_id = '1' WHERE job_id = '3' AND sequence_name = 'Seq-301'
    //UPDATE `sequence` SET sequence_id = '2' WHERE job_id = '3' AND sequence_name = 'Seq-300'





}

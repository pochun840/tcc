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


    #新增sequences
    public function create_job($mode, $jobdata){
    
            $sql = "INSERT INTO `sequences`(job_id, sequence_id, sequence_name,tightening_repeat, ng_stop,sequence_enable,screw_join,okall_stop,opt,torque_unit,k_value,ok_time,okall_alarm_time,offset)";
            $sql .= "VALUES(:job_id, :sequence_id, :sequence_name, :tightening_repeat, :ng_stop, :sequence_enable,:screw_join,:okall_stop,:opt,:torque_unit,:k_value,:ok_time,:okall_alarm_time,:offset);";
            $statement = $this->db_iDas->prepare($sql);


            if($mode == "create"){

                if (($jobdata['sequence_id'] = intval($jobdata['sequence_id'])) > 50 ) return false;
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




 
  
   

}

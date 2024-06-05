<?php

class Job{
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
        //$tool_rpm = $this->dbh->get_tool_rpm();
        //$this->tool_max_rpm = $tool_rpm['tool_maxrpm'];
        //$this->tool_min_rpm = $tool_rpm['tool_minrpm'];

    }

    #取得所有Job
    public function getJobs(){

        $sql = "SELECT job.*, IFNULL(COUNT(sequence.job_id), 0) as total_seq  
                FROM `job`
                LEFT JOIN sequence on job.job_id = sequence.job_id 
                WHERE job.job_id != ''
                GROUP BY job.job_id ";
        $statement = $this->db_iDas->prepare($sql);
        $statement->execute();


        return $statement->fetchall();
    }

    #刪除JOB 
    public function delete_job_by_id($jobid){

        $sql= "DELETE FROM job WHERE job_id = ?";
        $statement = $this->db_iDas->prepare($sql);
        $results = $statement->execute([$jobid]);


        if($jobid != 50 ){
            $sql_update = "UPDATE job SET job_id = job_id - 1 WHERE job_id > ?";
            $statement_update = $this->db_iDas->prepare($sql_update);
            $statement_update->execute([$jobid]);

        }
        return $results;
    }


    #新增JOB
    public function create_job($mode, $jobdata){
        
        
        $sql = "INSERT INTO `job`(job_id, job_name, unscrew_power, unscrew_rpm, unscrew_direction)";
        $sql .= "VALUES(:job_id, :job_name, :unscrew_power, :unscrew_rpm, :unscrew_direction);";
        $statement = $this->db_iDas->prepare($sql);
    
    
        if($mode == "create"){

            if (($jobdata['job_id'] = intval($jobdata['job_id'])) > 50) return false;

            $statement->bindValue(':job_id', $jobdata['job_id']);
            $statement->bindValue(':job_name', $jobdata['job_name']);
        } else {

             if (($jobdata['new_jobid'] = intval($jobdata['new_jobid'])) > 50) return false;

            $statement->bindValue(':job_id', $jobdata['new_jobid']);
            $statement->bindValue(':job_name', $jobdata['new_jobname']);
        }
    
        $statement->bindValue(':unscrew_power', $jobdata['unscrew_power']);
        $statement->bindValue(':unscrew_rpm', $jobdata['unscrew_rpm']);
        $statement->bindValue(':unscrew_direction', $jobdata['unscrew_direction']);
    
        $results = $statement->execute();
    
        return $results;
    }
    
    #修改JOB
    public function update_job_by_id($jobdata){
        
        $sql = "UPDATE `job` SET  job_name = :job_name, unscrew_power = :unscrew_power, unscrew_rpm = :unscrew_rpm, unscrew_direction = :unscrew_direction WHERE job_id = :job_id ";
        $statement = $this->db_iDas->prepare($sql);
        $statement->bindValue(':job_name', $jobdata['job_name']);
        $statement->bindValue(':unscrew_power', $jobdata['unscrew_power']);
        $statement->bindValue(':unscrew_rpm', $jobdata['unscrew_rpm']);
        $statement->bindValue(':unscrew_direction', $jobdata['unscrew_direction']);
        $statement->bindValue(':job_id', $jobdata['job_id']);
        $results = $statement->execute();

        return $results;


    }


    #查詢JOB 
    public function search($jobid){
        $sql= "SELECT * FROM job WHERE job_id = ?";
        $statement = $this->db_iDas->prepare($sql);
        $results = $statement->execute([$jobid]);
        $rows = $statement->fetch();

        return $rows;
    }

    #計算 有幾個JOB
    public function countjob(){

        $sql = "SELECT  COUNT(*) as count FROM job ";
        $statement = $this->db_iDas->prepare($sql);
        $statement->execute();
        $result = $statement->fetch();
        return $result['count'];
    }


}

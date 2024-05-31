<?php

class Step{
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

    #透過job_id 及 seq_id 取得對應的step
    public function getStep($job_id, $seq_id) {
        $sql = "SELECT * FROM step WHERE job_id = ? AND sequence_id = ?";
        $statement = $this->db_iDas->prepare($sql);
        $statement->execute([$job_id, $seq_id]);
        return $statement->fetchAll();
    }

    
}

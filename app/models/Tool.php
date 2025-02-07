<?php

class Tool{
    private $db;//condb control box
    private $db_data;//devdb tool
    private $dbh;

    // 在建構子將 Database 物件實例化
    public function __construct()
    {
        //$this->db = new Database;
        //$this->db = $this->db->getDb();

        $this->db_tools = new Database;
        $this->db_tools = $this->db_tools->getDb_tools();

        $this->dbh = new Database;

    }


    #取的tool 相關資料
    public function GetToolInfo()
    {
        $sql = "SELECT * FROM tool_info ";
        $statement = $this->db_tools->prepare($sql);
        $results = $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    /*public function GetControllerInfo()
    {
        $sql = "SELECT * FROM device ";
        $statement = $this->db->prepare($sql);
        $results = $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    public function GetDeviceInfo()
    {
        $sql = "SELECT * FROM device_info ";
        $statement = $this->db_dev->prepare($sql);
        $results = $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    public function GetToolInfo()
    {
        $sql = "SELECT * FROM tool_info ";
        $statement = $this->db_dev->prepare($sql);
        $results = $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row;
    }*/

}

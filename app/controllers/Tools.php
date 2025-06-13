<?php

class Tools extends Controller
{
    private $ToolModel;
    // 在建構子中將 Post 物件（Model）實例化
    public function __construct(){
        $this->ToolModel = $this->model('Tool');
    }

    // 取得所有info
    public function index(){

        $isMobile = $this->isMobileCheck();
        $Controller_Info = $this->ToolModel->GetControllerInfo();
        $Tool_Info = $this->ToolModel->GetToolInfo();

        if (!empty($Tool_Info['tool_calib_time'])) {
            if ($date = DateTime::createFromFormat('YmdHis', $Tool_Info['tool_calib_time'])) {
                $Tool_Info['tool_calib_time'] = $date->format('Y/m/d');
            }
        }

        $MAC = $this->getMacAddress();
        $ip_addr = $this->getIp();
        $data = [
            'isMobile' => $isMobile,
            'controller_info' => $Controller_Info,
            'tools_info' => $Tool_Info,
            'IP' => $ip_addr,
            'MAC' => $MAC,
        ];



        $this->view('tool/index', $data);
    }

    public function getMacAddress(){

        if( PHP_OS_FAMILY == 'Linux'){
            $output = shell_exec("ip link show");

            preg_match('/link\/ether (\w{2}:\w{2}:\w{2}:\w{2}:\w{2}:\w{2})/', $output, $matches);
            if (!empty($matches)) {
                return strtoupper($matches[1]);
            } else {
                return false;
            }

        }else{
            $MAC = exec('getmac');
            $MAC = strtok($MAC, ' ');
            $MAC = str_replace('-',':',$MAC);
            return $MAC;
        }
        
    }

    public function getIp()
    {
        if( PHP_OS_FAMILY == 'Linux'){
            $Ips = trim(shell_exec("/sbin/ip -o -4 addr list  | awk '{print $4}' | cut -d/ -f1"));
            $Ip = explode(PHP_EOL, $Ips);
            
            return strtoupper($Ip[1]);
        }else{
            $host_addr= gethostname();
            $ip_addr = gethostbyname($host_addr);
            return strtoupper($ip_addr);
        }
    }


    public function compareSchemaDifferences(){

        
        try {
            $db1 = new PDO('sqlite:/var/www/html/database/tcccon.db');
            $db2 = new PDO('sqlite:/var/www/html/database/idas_data.db');

            $stmt1 = $db1->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            $stmt2 = $db2->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");

            $tables1 = $stmt1->fetchAll(PDO::FETCH_COLUMN);
            $tables2 = $stmt2->fetchAll(PDO::FETCH_COLUMN);

            $onlyInTcccon = array_diff($tables1, $tables2);
            $onlyInIdas   = array_diff($tables2, $tables1);
            $commonTables = array_intersect($tables1, $tables2);

            echo "<pre>";

            // ✅ 顯示獨有的表
            if (!empty($onlyInTcccon)) {
                echo "📂 Tables only in tcccon.db:\n";
                foreach ($onlyInTcccon as $t) echo "  - $t\n";
                echo "\n";
            }

            if (!empty($onlyInIdas)) {
                echo "📂 Tables only in idas_data.db:\n";
                foreach ($onlyInIdas as $t) echo "  - $t\n";
                echo "\n";
            }

            $schemaDifferences = [];

            // ✅ 比較相同表格中的欄位差異
            foreach ($commonTables as $table) {
                $cols1 = $db1->query("PRAGMA table_info('$table')")->fetchAll(PDO::FETCH_ASSOC);
                $cols2 = $db2->query("PRAGMA table_info('$table')")->fetchAll(PDO::FETCH_ASSOC);

                $colList1 = array_map(function($c) {
                    return $c['name'] . ' ' . strtoupper($c['type']);
                }, $cols1);

                $colList2 = array_map(function($c) {
                    return $c['name'] . ' ' . strtoupper($c['type']);
                }, $cols2);

                $diff1 = array_diff($colList1, $colList2);
                $diff2 = array_diff($colList2, $colList1);

                if (!empty($diff1) || !empty($diff2)) {
                    echo "🔍 Schema difference in table: $table\n";

                    if (!empty($diff1)) {
                        echo "  🟥 Columns only in tcccon.db:\n";
                        foreach ($diff1 as $col) echo "    - $col\n";
                    }

                    if (!empty($diff2)) {
                        echo "  🟦 Columns only in idas_data.db:\n";
                        foreach ($diff2 as $col) echo "    - $col\n";
                    }

                    echo "\n";
                }
            }

            echo "✅ Schema comparison complete.\n</pre>";

        } catch (PDOException $e) {
            echo "<pre>❌ Error: " . $e->getMessage() . "</pre>";
        }
    }




}
?>
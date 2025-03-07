<?php

class Tools extends Controller
{
    private $ToolModel;
    // 在建構子中將 Post 物件（Model）實例化
    public function __construct()
    {
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

}
?>
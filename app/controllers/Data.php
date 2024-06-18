<?php

class Data extends Controller
{
    // 在建構子中將 Post 物件（Model）實例化
    public function __construct()
    {
        $this->DataModel = $this->model('Datas');
        $this->MiscellaneousModel = $this->model('Miscellaneous');
    }

    // 取得所有Jobs
    public function index(){


        $type ='ALL';
        $isMobile = $this->isMobileCheck();
        $res_data = $this->DataModel->getData('ALL');

        $unit_arr   = $this->MiscellaneousModel->details('torque_unit');
        $status_arr = $this->MiscellaneousModel->details('status');
        $device_info = $this->Device_Info();
        $data = [
            'isMobile' => $isMobile,
            'res_data' => $res_data,
            'device_info' => $device_info,
            'unit_arr' => $unit_arr,
            'status_arr' => $status_arr
        ];
        
        $this->view('data/index', $data);

    }


    public function search_info(){

        $unit_arr    = $this->MiscellaneousModel->details('torque_unit');
        $status_arr  = $this->MiscellaneousModel->details('status');

        $input_check = true;
        if( !empty($_POST['mode']) && isset($_POST['mode'])  ){
            $mode = $_POST['mode'];
        }else{ 
            $input_check = false; 
        }
        
        if($input_check){
            $res_data = $this->DataModel->getData($mode);
            if(!empty($res_data)){
                $info_data = '';
                foreach($res_data as $ke => $ve){
                    
                    if($ve['fasten_status'] == 7 || $ve['fasten_status'] == 8 ){
                        $style = 'style="background: red"';
                    }else if($ve['fasten_status'] == 5 || $ve['fasten_status'] == 6){
                        $style ='style="background: #FFEF62"';
                    }else{
                        $style = 'style="background: green"';
                    }
                    //#FFEF62
                    $info_data  ='<tr>';
                    $info_data .= "<td id='system_sn'>".$ve['system_sn']."</td>";
                    $info_data .= "<td>".$ve['data_time']."</td>";
                    $info_data .= "<td>".$ve['job_name']."</td>";
                    $info_data .= "<td>".$ve['sequence_name']."</td>";
                    $info_data .= "<td>".$ve['fasten_torque']."</td>";
                    $info_data .= "<td>".$unit_arr[$ve['torque_unit']]."</td>";
                    $info_data .= "<td>".$ve['fasten_angle']."</td>";
                    $info_data .= "<td>".$ve['total_screw_count']."</td>";
                    $info_data .= "<td>".$ve['last_screw_count']."</td>";
                    $info_data .= "<td $style >". $status_arr[$ve['fasten_status']]."</td>";
                    $info_data  .='</tr>';

                    echo $info_data;
                }

            }

        }

    }

   
    public function exportData() {
        $input_check = true;
        if (!empty($_POST['start_date']) && isset($_POST['start_date'])) {
            $start_date = $_POST['start_date'];
        } else {
            $input_check = false;
        }
        if (!empty($_POST['end_date']) && isset($_POST['end_date'])) {
            $end_date = $_POST['end_date'];
        } else {
            $input_check = false;
        }
    
        if (!empty($_POST['expert_val']) && isset($_POST['expert_val'])) {
            $expert_val = $_POST['expert_val'];
        } else {
            $expert_val = "0";
        }
    
        if ($input_check) {
            $start_date = str_replace('-', "", $start_date);
            $end_date = str_replace('-', "", $end_date);
            $dataset = $this->DataModel->get_range_data($start_date, $end_date);
    
            $dataset = array_slice($dataset, 0, 10000);
    
            if ($dataset && $expert_val == "0") {

              
                $csv_headers = array_keys($dataset[0]);
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=data.csv');
    
                $output = fopen('php://output', 'w');
                fputcsv($output, $csv_headers);
    
                foreach ($dataset as $row) {
                    fputcsv($output, $row);
                }
    
                fclose($output);
                exit();
            } elseif ($dataset && $expert_val == "1") {
         
                $csv_content = '';
                $csv_headers = array_keys($dataset[0]);
                $csv_content .= implode(',', $csv_headers) . "\n";
    
                foreach ($dataset as $row) {
                    $csv_content .= implode(',', $row) . "\n";
                }
    
                $zip = new ZipArchive();
                $zip_filename = tempnam(sys_get_temp_dir(), 'exported_data') . '.zip';
    
                if ($zip->open($zip_filename, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                    
                    $zip->addFromString("data.csv", $csv_content);
                    $zip->close();
    
                    header('Content-Type: application/zip');
                    header('Content-Disposition: attachment; filename=exported_data.zip');
                    header('Content-Length: ' . filesize($zip_filename));
                    readfile($zip_filename);
                    unlink($zip_filename);
                    exit();
                } else {
                    echo "無法建立 ZIP 檔案";
                }
            }
        }
    }
    
    
    
    
}
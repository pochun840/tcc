<?php

class Inputs extends Controller
{
    // 在建構子中將 Post 物件（Model）實例化
    public function __construct()
    {
        $this->InputModel = $this->model('Input');
        $this->MiscellaneousModel = $this->model('Miscellaneous');
        $this->jobModel = $this->model('Job');
    }

    // 取得所有Inputs
    public function index(){

        //要檢查是否有alljobinput，有的話要直接帶入
        $isMobile = $this->isMobileCheck();
        $joblist  = $this->InputModel->get_job_list();
        $event    = $this->MiscellaneousModel->details('io_input');
        $device_data = $this->InputModel->get_input_alljob();

        if(!empty($joblist)){
            $job_list_new = array();
            foreach($joblist as $kk =>$vv){
                $job_list_new[$vv['job_id']] =$vv;  
            }
        }
        $data = array();
        $data = array(
            'isMobile'     => $isMobile,
            'job_list'     => $joblist,
            'event'        => $event,
            'job_list_new' => $job_list_new,
            'device_data'  => $device_data,   
        );

        if($isMobile){
            $this->view('input/index_m', $data);
        }else{
            $this->view('input/index', $data);
        }
    }

    // get_input_by_job_id
    public function get_input_by_job_id($job_id) {
        $event = $this->MiscellaneousModel->details('io_input');
    
        $input_check = true;
        if (!empty($_POST['jobid']) && isset($_POST['jobid'])) {
            $job_id = $_POST['jobid'];
        } else {
            $input_check = false; 
        }
    
        if ($input_check) {
            $job_inputs = $this->InputModel->get_input_by_job_id($job_id);
            $temp = array(); 
            $tempA = array();
            $temp_gateconfirm = array();
            $job_inputlist = ''; 
    
            if (!empty($job_inputs)) {
                foreach ($job_inputs as $kk => $vv) {
                    // 遍歷 input_pin1 到 input_pin10 並檢查其是否為非零值
                    for ($i = 1; $i <= 10; $i++) {
                        $pin_key = 'input_pin' . $i;
                        if (isset($vv[$pin_key]) && $vv[$pin_key] != 0) {
                            // 取得非零的 pin 編號
                            $pin_number = $i; // 這是針對每個 pin 的編號
                            $gateconfirm = "1";  // 假設 gateconfirm 固定為 1
                            $temp[] = "pin" . $pin_number . "_high";
                            $temp[] = "pin" . $pin_number . "_low";
                            $temp[] = "edit_pin" . $pin_number . "_high";
                            $temp[] = "edit_pin" . $pin_number . "_low";
                            $temp[] = "check_" . $gateconfirm;
                        }
                    }
    
                    if (!empty($vv['input_event'])) {
                        $tempA[] = $vv['input_event'];
                    }
    
                    if (!empty($vv['input_gateconfirm'])) {
                        $temp_gateconfirm[] = $vv['input_gateconfirm'];
                    }
    
                    $isMobile = $this->isMobileCheck();
    
                    if ($isMobile) {
                        // 根據 input_pin1 到 input_pin10 檢查並顯示
                        $pin_display = ''; // 初始化 pin 顯示
                        for ($i = 1; $i <= 10; $i++) {
                            $pin_key = 'input_pin' . $i;
                            if (isset($vv[$pin_key]) && $vv[$pin_key] != 0) {
                                $pin_display .= $pin_number . "<br>";
                            }
                        }
    
                        // 根據 input_wave 顯示不同的圖片 (這裡假設使用 input_wave 或其他欄位)
                        $wave_img = '';
                        for ($i = 1; $i <= 10; $i++) {
                            $wave_key = 'input_pin' . $i;  // 假設你使用 input_wave1 到 input_wave10
                            if ( $vv[$wave_key] == 1) {
                                $wave_img = '<img src="./img/high.png" style="max-width: 50px;">';
                            } else if( $vv[$wave_key] == 2){
                                $wave_img = '<img src="./img/low.png" style="max-width: 50px;">';
                            }
                        }
    
                        // 將所有資料顯示為行 (行動版格式)
                        $job_inputlist .= "<tr data-event = '" . $vv['input_event'] . "' >";
                        $job_inputlist .= "<td id='" . $vv['input_event'] . "'>" . $event[$vv['input_event']] . "</td>";
                        $job_inputlist .= "<td>" . $pin_display . "</td>"; // 顯示 pin 的資料
                        $job_inputlist .= "<td>" . $wave_img . "</td>";  // 顯示 wave 的圖片
                        $job_inputlist .= "</tr>";
                    } else {
                        // 桌面版格式

                        $input_gateconfirm = $vv['input_gateconfirm'];
                        if($input_gateconfirm == 0){$input_gateconfirm_text ="NO";}else{$input_gateconfirm_text ="YES";}
                        $job_inputlist .= "<tr data-event = '" . $vv['input_event'] . "' >";
                        $job_inputlist .= "<td id='" . $vv['input_event'] . "'>" . $event[$vv['input_event']] . "</td>";
                        $job_inputlist .= $this->InputModel->generateTableCell($vv); // 呼叫 generateTableCell
                        $job_inputlist .= '<td>' . $input_gateconfirm_text . '</td>';
                        $job_inputlist .= '</tr>';
                    }
                }
            }
        }
    
        $response = array(
            'job_inputlist' => $job_inputlist,
            'temp' => $temp,
            'tempA' => $tempA,
            'temp_gateconfirm' => $temp_gateconfirm
        );
    
        echo json_encode($response);
    }
    
    



    public function check_job_event_conflict($value=''){

        $input_check = true;
        if( !empty($_POST['job_id']) && isset($_POST['job_id'])  ){
            $job_id = $_POST['job_id'];
        }else{ 
            $input_check = false; 
        }
        if( !empty($_POST['input_event']) && isset($_POST['input_event'])  ){
           $input_event = $_POST['input_event'];
        }else{ 
            $input_check = false; 
        }

        if($input_check){
            $job_inputs = $this->InputModel->check_job_event_conflict($job_id,$input_event);    
        }

        print_r($job_inputs);
    }

    public function create_input_event(){

        // 載入語言包
        $file = $this->MiscellaneousModel->lang_load();
        if (!empty($file)) {
            include $file;
        }

        $event = $this->MiscellaneousModel->details('io_input');
        
        $input_check = true;
        $input_data = array();

        // 初始化所有的 input_pin_1 到 input_pin_10 設為 0
        for ($i = 1; $i <= 10; $i++) {
            $_POST["input_pin$i"] = 0;
        }

        // 處理 $_POST['input_pin']，從中提取出具體的 pin 編號並賦值給 input_pin
        if (isset($_POST['input_pin'])) {
            // 從 input_pin 取得數字部分 
            preg_match('/pin(\d+)/', $_POST['input_pin'], $matches);
            
            // 如果找到數字部分，則建立對應的 input_pin key
            if (isset($matches[1])) {
                $pin_number = $matches[1];  // 取得 pin 的編號
                $new_key = 'input_pin' . $pin_number;  // 根據編號動態建立新的 key 
                
                // 把 input_wave 的值賦給新的 input_pin
                if (isset($_POST['input_wave'])) {
                    $_POST[$new_key] = $_POST['input_wave'];  // 這裡將 input_wave 的值賦給對應的 input_pin
                }
            }
        }

        // 檢查是否有其他資料
        if (!empty($_POST)) {
            $input_data = $_POST;
            $input_data['input_jobid'] = $input_data['job_id'];  // 設定 input_jobid 為 job_id
        }

        // 進行檢查並將資料寫入資料庫
        if ($input_check) {
            $count = $this->InputModel->check_job_event_conflict($input_data['input_jobid'], $input_data['input_event']);
            
            if (!$count) {
                // 創建新事件
                $res = $this->InputModel->create_input($input_data);
                $result = array();
                
                if ($res) {
                    $res_type = 'Success';
                    $res_msg = $text['new_event'] . "  " . $text['job_id'] . ':' . $input_data['input_jobid'] . ',' . $text['event'] . ':' . $text[$event[$input_data['input_event']]] . "  " . $text['success'];
                } else {
                    $res_type = 'Error';
                    $res_msg = $text['new_event'] . "  " . $text['job_id'] . ':' . $input_data['input_jobid'] . ',' . $text['event'] . ':' . $text[$event[$input_data['input_event']]] . "  " . $text['fail'];
                }
                
                $result = array(
                    'res_type' => $res_type,
                    'res_msg' => $res_msg
                );
                
                // 返回結果
                echo json_encode($result);
            }
        }
    }


    public function edit_input_event(){

        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }

        $event = $this->MiscellaneousModel->details('io_input');

        //
        if(!empty($_POST['input_pin'])){
            $input_pins = array();
            $input_pins = array(
                0 =>$_POST['input_pin']
            );
            $numbers = [];
            foreach ($input_pins as $pin) {
                if (preg_match('/\d+/', $pin, $matches)) {
                    $numbers[] = $matches[0]; // 只保留數字部分
                }
            }
            $pin = $numbers[0];
        }
        

        // 初始化所有的 input_pin_1 到 input_pin_10 設為 0
        for ($i = 1; $i <= 10; $i++) {
            $_POST["input_pin$i"] = 0;
        }

        if(!empty($_POST)){
            $input_data = array();
            $input_data = $_POST;
            $input_data['input_jobid'] = $input_data['job_id'];
            $input_data['input_pin'.$pin] = $_POST['input_wave'];


        }
     
        $input_check = true;

        if($input_check){

          
            $ans  = $this->InputModel->delete_input_event_by_id($input_data['input_jobid'],$input_data['input_event']);
            $res  = $this->InputModel->create_input($input_data);

            $result = array();
            if($res){
                $res_type = 'Success';
                $res_msg  = $text['edit_event']."  ".$text['job_id'].':'.$input_data['input_jobid'].','.$text['event'].':'.$text[$event[$input_data['input_event']]]."  ".$text['success'];
            } else {
                $res_type = 'Error';
                $res_msg  = $text['edit_event']."  ".$text['job_id'].':'.$input_data['input_jobid'].','.$text['event'].':'.$text[$event[$input_data['input_event']]]."  ".$text['fail'];
            }
            
            $result = array(
                'res_type' => $res_type,
                'res_msg'  => $res_msg
                //'old_input_pin' =>$count['input_pin']
            );

            echo json_encode($result);
        }
    }

    public function copy_input_event(){


        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }

        $input_check = true;
        if (!empty($_POST['from_job_id']) && isset($_POST['from_job_id'])) {
            $input_jobid = $_POST['from_job_id'];
        } else {
            $input_check = false;
        }
        if (!empty($_POST['to_job_id']) && isset($_POST['to_job_id'])) {
            $to_job_id = $_POST['to_job_id'];
            
        } else {
            $input_check = false;
        }
        

        if ($input_check) {

            //強制 把 $to_job_id 原本設定的event 移除
            $this->InputModel->delete_input_by_id($to_job_id);
            $job_inputs_from = $this->InputModel->check_job_event($input_jobid);
            if (!empty($job_inputs_from)) {
                $copy_input_data = array();
                foreach ($job_inputs_from as $key => $val) {
                    if (isset($val['input_jobid'])) {
                        $copy_input_data[$key]['input_jobid'] = $to_job_id;
                    } else {
                        continue; 
                    }

                    $copy_input_data[$key]['input_event'] = $val['input_event'];
                    $copy_input_data[$key]['input_pin1'] = $val['input_pin1'];
                    $copy_input_data[$key]['input_pin2'] = $val['input_pin2'];
                    $copy_input_data[$key]['input_pin3'] = $val['input_pin3'];
                    $copy_input_data[$key]['input_pin4'] = $val['input_pin4'];
                    $copy_input_data[$key]['input_pin5'] = $val['input_pin5'];
                    $copy_input_data[$key]['input_pin6'] = $val['input_pin6'];
                    $copy_input_data[$key]['input_pin7'] = $val['input_pin7'];
                    $copy_input_data[$key]['input_pin8'] = $val['input_pin8'];
                    $copy_input_data[$key]['input_pin9'] = $val['input_pin9'];
                    $copy_input_data[$key]['input_pin10'] = $val['input_pin10'];
                    $copy_input_data[$key]['input_gateconfirm'] = $val['input_gateconfirm'];
                    $copy_input_data[$key]['input_pagemode'] = $val['input_pagemode'];
                    $copy_input_data[$key]['input_seqid'] = 0;
                    $res = $this->InputModel->create_input($copy_input_data[$key]);
             
                }

                $result = array();
                if($res){
                    $res_type = 'Success';
                    $res_msg  = $text['copy_input'].$to_job_id.$text['success'];
                } else {
                    $res_type = 'Error';
                    $res_msg  = $text['copy_input'].$to_job_id.$text['fail'];
                }
                $result = array(
                    'res_type' => $res_type,
                    'res_msg'  => $res_msg 
                ); 
    
                echo json_encode($result);      
            }
        }

    }


    public function delete_input(){

        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }

        $event    = $this->MiscellaneousModel->details('io_input');

        $input_check = true;
        if( !empty($_POST['job_id']) && isset($_POST['job_id'])  ){
            $job_id = $_POST['job_id'];
        }else{ 
            $input_check = false; 
        }
        if( !empty($_POST['input_event']) && isset($_POST['input_event'])  ){
            $input_event = $_POST['input_event'];
        }else{ 
            $input_check = false; 
        }


        if($input_check){
            //先取得要刪除資料的PIN角位
            $ans = $this->InputModel->check_job_event_conflict($job_id,$input_event);
            //進行資料的刪除
            $res = $this->InputModel->delete_input_event_by_id($job_id,$input_event);
            $result = array();
            if ($res) {
                $res_type = 'Success';
                $res_msg  = $text['del_event']."  ".$text['job_id'].':'.$job_id.','.$text['event'].':'.$text[$event[$input_event]]."  ".$text['success'];
            } else {
                $res_type = 'Error';
                $res_msg  = $text['del_event']."  ".$text['job_id'].':'.$job_id.','.$text['event'].':'.$text[$event[$input_event]]."  ".$text['fail'];
            }
            
            $result = array(
                'res_type' => $res_type,
                'res_msg'  => $res_msg
            );

            echo json_encode($result);
        }     
    }

    public function input_alljob()
    {
        $input_check = true;
        if( isset($_POST['job_id']) && $_POST['job_id'] >= 0 ){
            $input_jobid = $_POST['job_id'];
        }else if(isset($_POST['job_id_new']) && $_POST['job_id_new'] >= 0){
            $input_jobid = '';
        }else{
            $input_check = false; 
        }
        if($input_check){
            $res = $this->InputModel->set_input_alljob($input_jobid);
            if($res){
                $res_msg ='set inputall job:'.$input_jobid.' copyDB success';
            }else{
                $res_msg ='set inputall job:'.$input_jobid.' copyDB fail';
            }
            echo $res_msg;   
        }
    }    
}

?>
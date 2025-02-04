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
    public function get_input_by_job_id($job_id){

        $event = $this->MiscellaneousModel->details('io_input');

        $input_check = true;
        if (!empty($_POST['jobid']) && isset($_POST['jobid'])) {
            $job_id = $_POST['jobid'];
        } else {
            $input_check = false; 
        }

        if ($input_check) {
            $job_inputs = $this->InputModel->get_input_by_job_id($job_id);
            $temp  = array(); 
            $tempA = array();
            $temp_gateconfirm = array();
           
            $job_inputlist = ''; 
    
            if (!empty($job_inputs)) {
                foreach ($job_inputs as $kk => $vv) {
                    if (!empty($vv['input_pin'])) {
                        $pin_number = $vv['input_pin'];
                        $gateconfirm = "1";
                        $temp[] = "pin" . $pin_number . "_high";
                        $temp[] = "pin" . $pin_number . "_low";
                        $temp[] = "edit_pin" . $pin_number . "_high";
                        $temp[] = "edit_pin" . $pin_number . "_low";
                        $temp[] = "check_".$gateconfirm;

                    }

                    if (!empty($vv['input_event'])) {
                        $tempA[] = $vv['input_event'];
                    }

                    if(!empty($vv['gateconfirm'])){
                        $temp_gateconfirm[] = $vv['gateconfirm'];

                    }

                    $isMobile = $this->isMobileCheck();

                    if($isMobile){

                        if($vv['input_wave'] == 1){
                            $img = '<img src="./img/high.png" style="max-width: 50px;">';
                        }else{
                            $img = '<img src="./img/low.png" style="max-width: 50px;">';
                        }
                        
                        $job_inputlist .= "<tr data-event = '".$vv['input_event']."' >";
                        $job_inputlist .= "<td id='".$vv['input_event']."'>".$event[$vv['input_event']]."</td>";
                        $job_inputlist .= '<td>'.$vv['input_pin'].'</td>';
                        $job_inputlist .= '<td>'.$img.'</td>';
                        $job_inputlist .= '</tr>';
                        
                    }else{
               
                        $job_inputlist .= "<tr data-event = '".$vv['input_event']."' >";
                        $job_inputlist .= "<td id='".$vv['input_event']."'>".$event[$vv['input_event']]."</td>";
                        $job_inputlist .= $this->InputModel->generateTableCell($vv['input_pin'],$vv['input_wave']);
                        $job_inputlist .= '<td>'.$vv['gateconfirm'].'</td>';
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



    public function check_job_event_conflict($value='')
    {
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

    public function create_input_event()
    {

        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }

        $event    = $this->MiscellaneousModel->details('io_input');

        $input_check = true;
        $jobdata = array();

        if( !empty($_POST['job_id']) && isset($_POST['job_id'])  ){
            $jobdata['input_job_id'] = $_POST['job_id'];
        }else{ 
            $input_check = false; 
        }

        if( !empty($_POST['input_event']) && isset($_POST['input_event'])  ){
            $jobdata['input_event'] = $_POST['input_event'];
        }else{ 
            $input_check = false; 
        }

        if( !empty($_POST['input_pin']) && isset($_POST['input_pin'])  ){
            $jobdata['input_pin'] = intval($_POST['input_pin']);
        }else{ 
            $input_check = false; 
        }

        if( !empty($_POST['input_wave']) && isset($_POST['input_wave'])  ){
            $jobdata['input_wave'] = $_POST['input_wave'];
        }else{ 
            $input_check = false; 
        }

        if( isset($_POST['gateconfirm'])  ){
            $jobdata['gateconfirm'] = $_POST['gateconfirm'];
        }else{ 
            $input_check = false; 
        }

        if( isset($_POST['pagemode'])  ){
            $jobdata['pagemode'] = $_POST['pagemode'];
        }else{ 
            $input_check = false; 
        }

        if( isset($_POST['input_seqid'])  ){
            $jobdata['input_seqid'] = $_POST['input_seqid'];
        }else{ 
            $input_check = false; 
        }

        if($input_check){
            $count = $this->InputModel->check_job_event_conflict($jobdata['input_job_id'],$jobdata['input_event']);
            if(!$count){
               
                $res  = $this->InputModel->create_input($jobdata);
                $result = array();
                if($res){
                    $res_type = 'Success';
                    $res_msg  = $text['new_event']."  ".$text['job_id'].':'.$jobdata['input_job_id'].','.$text['event'].':'.$text[$event[$jobdata['input_event']]]."  ".$text['success'];
                }else{
                    $res_type = 'Error';
                    $res_msg  = $text['new_event']."  ".$text['job_id'].':'.$jobdata['input_job_id'].','.$text['event'].':'.$text[$event[$jobdata['input_event']]]."  ".$text['fail'];
                }
                
                $result = array(
                    'res_type' => $res_type,
                    'res_msg'  => $res_msg 
                );
    
                echo json_encode($result);

            }
        }
    }

    public function edit_input_event()
    {


        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }

        $event    = $this->MiscellaneousModel->details('io_input');
        
        $input_check = true;
        $jobdata = array();
        if( !empty($_POST['job_id']) && isset($_POST['job_id'])  ){
            $jobdata['input_job_id'] = $_POST['job_id'];
        }else{ 
            $input_check = false; 
        }

        if( !empty($_POST['input_event']) && isset($_POST['input_event'])  ){
            $jobdata['input_event'] = $_POST['input_event'];
        }else{ 
            $input_check = false; 
        }

        if( !empty($_POST['input_pin']) && isset($_POST['input_pin'])  ){
            $jobdata['input_pin'] = intval($_POST['input_pin']);
        }else{ 
            $input_check = false; 
        }

        if( !empty($_POST['input_wave']) && isset($_POST['input_wave'])  ){
            $jobdata['input_wave'] = $_POST['input_wave'];
        }else{ 
            $input_check = false; 
        }

      
        if($input_check){

            $count = $this->InputModel->check_job_event_conflict($jobdata['input_job_id'],$jobdata['input_event']);
            //var_dump($count['input_pin']);die();
            $ans  = $this->InputModel->delete_input_event_by_id($jobdata['input_job_id'],$jobdata['input_event']);
            $res  = $this->InputModel->create_input($jobdata);



            $result = array();
            if($res){
                $res_type = 'Success';
                $res_msg  = $text['edit_event']."  ".$text['job_id'].':'.$jobdata['input_job_id'].','.$text['event'].':'.$text[$event[$jobdata['input_event']]]."  ".$text['success'];
            } else {
                $res_type = 'Error';
                $res_msg  = $text['edit_event']."  ".$text['job_id'].':'.$jobdata['input_job_id'].','.$text['event'].':'.$text[$event[$jobdata['input_event']]]."  ".$text['fail'];
            }
            
            $result = array(
                'res_type' => $res_type,
                'res_msg'  => $res_msg,
                'old_input_pin' =>$count['input_pin']
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
            $input_job_id = $_POST['from_job_id'];
        } else {
            $input_check = false;
        }
        if (!empty($_POST['to_job_id']) && isset($_POST['to_job_id'])) {
            $to_job_id = $_POST['to_job_id'];
            
            //$this->InputModel->delete_input_by_id($to_job_id);
        } else {
            $input_check = false;
        }
        

        if ($input_check) {
            $job_inputs_from = $this->InputModel->check_job_event($input_job_id);
            if (!empty($job_inputs_from)) {
                $jobdata = array();
                foreach ($job_inputs_from as $key => $val) {
                
                    if (isset($val['input_job_id'])) {
                        $jobdata[$key]['input_job_id'] = $to_job_id;
                    } else {
                        continue; 
                    }

                    $jobdata[$key]['input_event'] = $val['input_event'];
                    $jobdata[$key]['input_pin'] = $val['input_pin'];
                    $jobdata[$key]['input_wave'] = $val['input_wave'];
                    $jobdata[$key]['gateconfirm'] = $val['gateconfirm'];
                    $jobdata[$key]['pagemode'] = $val['pagemode'];
                    $jobdata[$key]['input_seqid'] = 0;
                    $res = $this->InputModel->create_input($jobdata[$key]);
             
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
                'res_msg'  => $res_msg,
                'old_input_pin' => $ans['input_pin']
            );

            echo json_encode($result);
        }     
    }

    public function input_alljob()
    {
        $input_check = true;
        if( isset($_POST['job_id']) && $_POST['job_id'] >= 0 ){
            $input_job_id = $_POST['job_id'];
        }else if(isset($_POST['job_id_new']) && $_POST['job_id_new'] >= 0){
            $input_job_id = '';
        }else{
            $input_check = false; 
        }
        if($input_check){
            $res = $this->InputModel->set_input_alljob($input_job_id);
            if($res){
                $res_msg ='set inputall job:'.$input_job_id.' copyDB success';
            }else{
                $res_msg ='set inputall job:'.$input_job_id.' copyDB fail';
            }
            echo $res_msg;   
        }
    }    
}

?>
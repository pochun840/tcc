<?php

class Outputs extends Controller
{
    // 在建構子中將 Post 物件（Model）實例化
    public function __construct()
    {
        $this->OutputModel = $this->model('Output');
        $this->InputModel = $this->model('Input');
        $this->MiscellaneousModel = $this->model('Miscellaneous');
        $this->jobModel = $this->model('Job');
    }

    // 取得所有Jobs
    public function index(){

      
        //要檢查是否有alljobinput，有的話要直接帶入
        $isMobile     = $this->isMobileCheck();
        $joblist      = $this->InputModel->get_job_list();
        $event_output = $this->MiscellaneousModel->details('io_output');


        /*if(!empty($joblist)){
            $job_list_new = array();
            foreach($joblist as $kk =>$vv){
                $job_list_new[$vv['job_id']] =$vv;  
            }
        }*/

        
        $data = array();
        $data = array(
            'isMobile'     => $isMobile,
            'job_list'     => $joblist,
            'event_output' => $event_output,
            //'job_list_new' => $job_list_new,
            
        );


        $this->view('output/index', $data);
    }

    // get_input_by_job_id

    public function get_output_by_job_id(){

        $event_output = $this->MiscellaneousModel->details('io_output');

        $input_check = true;
        if( !empty($_POST['job_id']) && isset($_POST['job_id'])  ){
            $job_id = $_POST['job_id'];
        }else{ 
            $input_check = false; 
        }

        if($input_check){
            $job_outputs = $this->OutputModel->get_output_by_job_id($job_id);
            $temp  = array(); 
            $tempA = array();
            $job_outputlist = ''; 
        
            if (!empty($job_outputs)) {
                foreach ($job_outputs as $kk => $vv) {
                    if (!empty($vv['output_pin'])) {
                        $pin_number = $vv['output_pin'];
                        $temp[] = "pin" . $pin_number."_".$vv['wave'];
                    }

                    if (!empty($vv['output_event'])) {
                        $tempA[] = $vv['output_event'];
                    }

                    $job_outputlist .= "<tr class='".$vv['output_event']."'>";
                    $job_outputlist .= '<td>'.$event_output[$vv['output_event']].'</td>';
                    $job_outputlist .= $this->OutputModel->generateTableCell($vv['output_pin'],$vv['wave']);
                    $job_outputlist .= '<td>'.$vv['wave_on'].'</td>';
                    $job_outputlist .= '</tr>';
                }

            }
        }

        $response = array(
            'job_outputlist' => $job_outputlist,
            'temp' => $temp,
            'tempA' => $tempA,
        );
        echo json_encode($response);
        

    }

   

    public function check_job_output_conflict($value='')
    {
        $input_check = true;
        if( !empty($_POST['job_id']) && isset($_POST['job_id'])  ){
            $job_id = $_POST['job_id'];
        }else{ 
            $input_check = false; 
        }
        if( !empty($_POST['event_id']) && isset($_POST['event_id'])  ){
            $event_id = $_POST['event_id'];
        }else{ 
            $input_check = false; 
        }

        if($input_check){
            $job_inputs = $this->OutputModel->check_job_output_conflict($job_id,$event_id);    
        }

        echo json_encode($job_inputs);
    }

    public function create_output_event()
    {
        $input_check = true;
        $jobdata = array();
        if( !empty($_POST['job_id']) && isset($_POST['job_id'])  ){
            $jobdata['output_job_id'] = $_POST['job_id'];
        }else{ 
            $input_check = false; 
        }
        if( !empty($_POST['output_pin']) && isset($_POST['output_pin'])  ){
            $jobdata['output_pin'] = $_POST['output_pin'];
        }else{ 
            $input_check = false; 
        }
        if( !empty($_POST['output_event']) && isset($_POST['output_event'])  ){
            $jobdata['output_event'] = $_POST['output_event'];
        }else{ 
            $input_check = false; 
        }
        if( !empty($_POST['wave']) && isset($_POST['wave'])  ){
            $jobdata['wave'] = $_POST['wave'];
        }else{ 
            $input_check = false; 
        }
        if( isset($_POST['wave_on']) && $_POST['wave_on']>=0 && $_POST['wave_on'] <= 10000 ){
            $jobdata['wave_on'] = $_POST['wave_on'];
            if($jobdata['wave_on'] == ''){
                $jobdata['wave_on'] = 0;//預設值
            }
        }else{ 
            $input_check = false; 
        }


        if($input_check){
            $res = $this->OutputModel->create_output($jobdata);
            if($res){
                $res_msg = 'create input from job:'.$jobdata['output_job_id'].',event_id:'.$jobdata['output_event'].'  success';
            }else{
                $res_msg = 'create input from job:'.$jobdata['output_job_id'].',event_id:'.$jobdata['output_event'].'  fail';
            }
            echo $res_msg;
        }
       
    }

    public function copy_output()
    {
        $input_check = true;
        if( !empty($_POST['from_job_id']) && isset($_POST['from_job_id'])  ){
            $from_job_id = $_POST['from_job_id'];
        }else{ 
            $input_check = false; 
        }
        if( !empty($_POST['to_job_id']) && isset($_POST['to_job_id'])  ){
            $to_job_id = $_POST['to_job_id'];
        }else{ 
            $input_check = false; 
        }

        if($input_check){
            $job_inputs = $this->OutputModel->copy_output_by_id($from_job_id,$to_job_id);
            if ($job_inputs) { // copy DB
                $copy_result = $this->copyDB_to_RamdiskDB();
                if ($copy_result) {
                    $this->logMessage('copy output from_job_id:'.$from_job_id.',to_job_id:'.$to_job_id.' copyDB success');
                } else {
                    $this->logMessage('copy output from_job_id:'.$from_job_id.',to_job_id:'.$to_job_id.' copyDB fail');
                }
            }
        }

        echo json_encode($job_inputs);
    }

    public function delete_output(){
        $input_check = true;
        if( !empty($_POST['job_id']) && isset($_POST['job_id'])  ){
            $output_job_id	 = $_POST['job_id'];
        }else{ 
            $input_check = false; 
        }
        if( !empty($_POST['output_event']) && isset($_POST['output_event'])  ){
            $output_event = $_POST['output_event'];
        }else{ 
            $input_check = false; 
        }

        if($input_check){
            $res = $this->OutputModel->delete_output_event_by_id($output_job_id,$output_event);
            if ($res) {
                $res_msg = 'delete output from job:'.$output_job_id.',event_id:'.$output_event.'  success';
            } else {
                $res_msg = 'delete output from job:'.$output_job_id.',event_id:'.$output_event.'  fail';
            }
            echo $res_msg;
        }
    }

    public function output_alljob()
    {
        $input_check = true;
        if( isset($_POST['job_id']) && $_POST['job_id'] >= 0 ){
            $job_id = $_POST['job_id'];
        }else{ 
            $input_check = false; 
        }

        if($input_check){
            $job_inputs = $this->OutputModel->set_output_alljob($job_id);
            if ($job_inputs) { // copy DB
                $copy_result = $this->copyDB_to_RamdiskDB();
                if ($copy_result) {
                    $this->logMessage('set outputall job:'.$job_id.' copyDB success');
                } else {
                    $this->logMessage('set outputall job:'.$job_id.' copyDB fail');
                }
            }
        }

        echo json_encode($job_inputs);
    }    
}
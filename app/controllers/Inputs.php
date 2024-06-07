<?php

class Inputs extends Controller
{
    // 在建構子中將 Post 物件（Model）實例化
    public function __construct()
    {
        $this->InputModel = $this->model('Input');
        $this->MiscellaneousModel = $this->model('Miscellaneous');
        // $this->DashboardModel = $this->model('Dashboard');
    }

    // 取得所有Inputs
    public function index(){

        //要檢查是否有alljobinput，有的話要直接帶入
        $isMobile = $this->isMobileCheck();
        $joblist  = $this->InputModel->get_job_list();
        $event    = $this->MiscellaneousModel->details('io_input');
        $data = array();

        $data = array(
            'isMobile' => $isMobile,
            'job_list' => $joblist,
            'event'    => $event,
        );


        $this->view('input/index', $data);

    }

    // get_input_by_job_id
    public function get_input_by_job_id(){
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
            $job_inputlist = ''; 
    
            if (!empty($job_inputs)) {
                foreach ($job_inputs as $kk => $vv) {
                    if (!empty($vv['input_pin'])) {
                        $pin_number = $vv['input_pin'];
                        $temp[] = "pin" . $pin_number . "_high";
                        $temp[] = "pin" . $pin_number . "_low";
                    }

                    if (!empty($vv['input_event'])) {
                        $tempA[] = $vv['input_event'];
                    }
    
                    $job_inputlist .= "<tr class='".$vv['input_event']."'>";
                    $job_inputlist .= '<td>'.$event[$vv['input_event']].'</td>';
                    $job_inputlist .= $this->InputModel->generateTableCell($vv['input_pin'],$vv['input_wave']);
                    $job_inputlist .= '<td>NO</td>';
                    $job_inputlist .= '<td>1</td>';
                    $job_inputlist .= '<td>EVENT</td>';
                    $job_inputlist .= '</tr>';
                }

            }
        }
        
        $response = array(
            'job_inputlist' => $job_inputlist,
            'temp' => $temp,
            'tempA' => $tempA,
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
        if( !empty($_POST['event_id']) && isset($_POST['event_id'])  ){
            $event_id = $_POST['event_id'];
        }else{ 
            $input_check = false; 
        }

        if($input_check){
            $job_inputs = $this->InputModel->check_job_event_conflict($job_id,$event_id);    
        }

        echo json_encode($job_inputs);
    }

    public function create_input_event()
    {
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
                if($res){
                    $res_msg = 'create input from job:'.$jobdata['input_job_id'].',event_id:'.$jobdata['input_event'].'  success';
                }else{
                    $res_msg = 'create input from job:'.$jobdata['input_job_id'].',event_id:'.$jobdata['input_event'].'  fail';
                }
                echo $res_msg;
            }
        }
    }

    public function edit_input_event($value='')
    {
        $input_check = true;
        if( !empty($_POST['job_id']) && isset($_POST['job_id'])  ){
            $job_id = $_POST['job_id'];
        }else{ 
            $input_check = false; 
        }
        if( !empty($_POST['event_id_new']) && isset($_POST['event_id_new'])  ){
            $event_id_new = $_POST['event_id_new'];
        }else{ 
            $input_check = false; 
        }
        if( !empty($_POST['event_id_old']) && isset($_POST['event_id_old'])  ){
            $event_id_old = $_POST['event_id_old'];
        }else{ 
            $input_check = false; 
        }
        if( !empty($_POST['input_pin']) && isset($_POST['input_pin'])  ){
            $input_pin = $_POST['input_pin'];
        }else{ 
            $input_check = false; 
        }
        if( !empty($_POST['option']) && isset($_POST['option'])  ){
            $option = $_POST['option'];
        }else{ 
            $input_check = false; 
        }

        if($input_check){
            $job_inputs = $this->InputModel->create_input($job_id,$event_id,$input_pin,$option);
            if ($job_inputs) { // copy DB
                $copy_result = $this->copyDB_to_RamdiskDB();
                if ($copy_result) {
                    $this->logMessage('edit input job:'.$job_id.',event_id:'.$event_id.' copyDB success');
                } else {
                    $this->logMessage('edit input job:'.$job_id.',event_id:'.$event_id.' copyDB fail');
                }
            }
        }

        echo json_encode($job_inputs);
    }

    public function copy_input()
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
            $job_inputs = $this->InputModel->copy_input_by_id($from_job_id,$to_job_id);
            if ($job_inputs) { // copy DB
                $copy_result = $this->copyDB_to_RamdiskDB();
                if ($copy_result) {
                    $this->logMessage('copy input from job:'.$from_job_id.',to_job_id:'.$to_job_id.' copyDB success');
                } else {
                    $this->logMessage('copy input from job:'.$from_job_id.',to_job_id:'.$to_job_id.' copyDB fail');
                }
            }
        }

        echo json_encode($job_inputs);
    }

    public function delete_input(){
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
            $res = $this->InputModel->delete_input_event_by_id($job_id,$input_event);
            if ($res) {
                $res_msg = 'delete input from job:'.$job_id.',event_id:'.$input_event.'  success';
            } else {
                $res_msg = 'delete input from job:'.$job_id.',event_id:'.$input_event.'  fail';
            }
            echo $res_msg;
        }     
    }

    public function input_alljob()
    {
        $input_check = true;
        if( isset($_POST['job_id']) && $_POST['job_id'] >= 0 ){
            $job_id = $_POST['job_id'];
        }else{ 
            $input_check = false; 
        }

        if($input_check){
            $job_inputs = $this->InputModel->set_input_alljob($job_id);
            if ($job_inputs) { // copy DB
                $copy_result = $this->copyDB_to_RamdiskDB();
                if ($copy_result) {
                    $this->logMessage('set inputall job:'.$job_id.' copyDB success');
                } else {
                    $this->logMessage('set inputall job:'.$job_id.' copyDB fail');
                }
            }
        }

        echo json_encode($job_inputs);
    }

    public function get_job(){

        $res = $this->InputModel->get_job_list();

    }


   
    

    
}
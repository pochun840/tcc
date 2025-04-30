<?php
class Step extends Controller
{
   
    // 在建構子中將 Post 物件（Model）實例化
    public function __construct()
    {
        $this->ToolModel = $this->model('Tool');
        $this->MiscellaneousModel = $this->model('Miscellaneous');
        $this->stepModel = $this->model('Steptcc');
        $this->sequenceModel = $this->model('Sequence');
        $this->SettingModel = $this->model('Setting');

        
    }

    public function index($job_id,$seq_id){
        if( isset($job_id) && !empty($job_id)  && isset($seq_id) && !empty($seq_id)){

        }else{
            $job_id = 1;
            $seq_id = 1;
        }

        $isMobile = $this->isMobileCheck();
        $step = $this->stepModel->getStep($job_id, $seq_id);
        $target_option = $this->MiscellaneousModel->details("target_option");
        $torque_unit   = $this->MiscellaneousModel->details("torque_unit");
        $target_option_change = $this->MiscellaneousModel->details("target_option_change");

        $target_option_only_tor = $this->MiscellaneousModel->details("target_option_only_tor");
        $formatted_array = [];
        foreach ( $target_option_only_tor as $index => $item) {
            $formatted_array[] = array(
                'value' => $index, 
                'text' => $item   
            );
        }
        $json = json_encode($formatted_array);

        $direction = $this->MiscellaneousModel->details('rev_direction');
        $unit_arr  = $this->MiscellaneousModel->details('torque_unit');
        $seqinfo   = $this->sequenceModel->search_seqinfo($job_id,$seq_id);
  
        $tools     = $this->ToolModel->GetToolInfo();

        $step_count = $this->stepModel->countstep($job_id, $seq_id);
        $step_count = intval($step_count);
        $step_id = intval($step_count);
        if($step_count == 0){
            $step_count = 1;
            $step_id = 1;
        }

        $check = $this->stepModel->check_step_target($job_id,$seq_id,$step_id);

        $res_device = $this->SettingModel->GetControllerInfo();
        if(!empty($res_device)){
            $step_torque_unit = $res_device['device_torque_unit'];
            $unit_name = $torque_unit[$step_torque_unit];
  
        }
        
        if(empty($step)){
            $stepid_new = 1;
        }else{
            $stepid_new = count($step) + 1 ;
        }

        if(!empty($check[0]['count_records'])){
            $count_records = (int)$check[0]['count_records'];
            $check_step_torque = 1;
        }else{
            $check_step_torque = '';   
            $count_records = ''; 
        }

        #取得tools的型號
        $tools = $this->ToolModel->GetToolInfo();
        if(!empty($tools)){
            $tools_id = (int)$tools['SID5'];
        }

        #用job_id && seq_id查詢是否有使用最佳化 
        $seq_data = $this->sequenceModel->search_seqinfo($job_id,$seq_id);
        if(!empty($seq_data)){
            $seq_opt = (int)$seq_data[0]['seq_opt'];
        }

        #扭力單位換算 
        if(!empty($tools)){

            $tools['tool_maxtorque']  = $this->MiscellaneousModel->unitarr_change((float)$tools['tool_maxtorque'],1, $step_torque_unit)[0];
            $tools['tool_maxtorque_diff'] = $tools['tool_maxtorque']* 1.1; 

            $tools['tool_mintorque'] = $this->MiscellaneousModel->unitarr_change((float)$tools['tool_mintorque'],1, $step_torque_unit)[0];
            $tools['tool_mintorque_diff'] = floor($tools['tool_maxtorque_diff'] / 10 * 10) / 10;
        }





        $check_torque = $this->stepModel->chek_step_target_torque($job_id,$seq_id);
        if(!empty($check_torque)){
            $counts_torque = intval($check_torque[0]['counts']);
            //counts
        }


        $data = array(
            'isMobile' => $isMobile,
            'step' => $step,
            'target_option' => $target_option,
            'target_option_change' =>$target_option_change,
            'target_option_only_tor_json' => $json,
            'direction' => $direction,
            'job_id' => $job_id,
            'seq_id' => $seq_id,
            'step_id' => $stepid_new,
            'unit_arr' => $unit_arr,
            'step_torque_unit' => $step_torque_unit,
            'check' => $check,
            'seq_id' => $seq_id,
            'unit_name' => $unit_name,
            'check_step_torque' => $check_step_torque,
            'check' => $check,
            'step_count' => $step_count,
            'tools' => $tools,
            'count_records' => $count_records,
            'tools_id' => $tools_id,
            'seq_opt'  => $seq_opt,
            'counts_torque' => $counts_torque

        );
        
        if($isMobile){
            $this->view('step/index_m', $data);
        }else{
            $this->view('step/index', $data);
        }
        
    }

    public function create_step(){

        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }

        if(isset($_POST['jobid'])){

            $jobid = isset($_POST['jobid']) ? intval($_POST['jobid']) : 0;
            $seqid = isset($_POST['seqid']) ? intval($_POST['seqid']) : 0;
            $stepid = isset($_POST['stepid']) ? intval($_POST['stepid']) : 1; 
            $target_opt = isset($_POST['target_opt'])? intval($_POST['target_opt']) : 0; 
            $target_tor = isset($_POST['target_tor'])? floatval($_POST['target_tor']) : 0; 
            $target_ang = isset($_POST['target_ang'])? intval($_POST['target_ang']) : 0; 
            $target_delay = isset($_POST['target_delay'])? floatval($_POST['target_delay']) : 0; 
            $tor_hi = isset($_POST['tor_hi'])? floatval($_POST['tor_hi']) : 0; 
            $tor_lo = isset($_POST['tor_lo'])? floatval($_POST['tor_lo']) : 0; 
            $ang_hi  = isset($_POST['ang_hi'])? intval($_POST['ang_hi']) : 0; 
            $ang_lo  = isset($_POST['lo_angle'])? intval($_POST['ang_lo']) : 0; 
            $rpm       = isset($_POST['rpm'])? intval($_POST['rpm']) : 50;
            $direction = isset($_POST['direction'])? intval($_POST['direction']) : 0;
            $th_mode = isset($_POST['th_mode'])? intval($_POST['th_mode']) : 0;
            $ds_tor = isset($_POST['ds_tor'])? floatval($_POST['ds_tor']) : 0.3; 
            $ds_speed = isset($_POST['ds_speed'])? intval($_POST['ds_speed']) : 100;
            $th_tor = isset($_POST['th_tor'])? floatval($_POST['th_tor']) : 0;
            $record_ang = isset($_POST['record_ang'])? intval($_POST['record_ang']) : 0;
            $tor_unit = isset($_POST['tor_unit'])? intval($_POST['tor_unit']) : 1;





            #同一個step 只能有一個Target Torque
            //$check = $this->stepModel->check_step_target($jobid,$seqid);
            //$check = intval($check[0]['count_records']);


        

            if($target_opt  == 0 ){
                if ($ds_tor > $target_tor) {

                    $res_type = 'Error';
                    $res_msg  =  $error_message['downshift_torque_error'];
                    $result = array(
                        'res_type' => $res_type,
                        'res_msg'  => $res_msg 
                    );
                    echo json_encode($result);
                    exit();
                }

                if ($th_tor > $target_tor) {

                    $res_type = 'Error';
                    $res_msg  =  $error_message['threshold_torque_error'];
                    $result = array(
                        'res_type' => $res_type,
                        'res_msg'  => $res_msg 
                    );
                    echo json_encode($result);
                    exit();
                }

            }

            if($target_opt  == 1){

                if($tor_lo  > $tor_hi){
                    $res_type = 'Error';
                    $res_msg  =  $error_message['torque_error'];
                    
                    $result = array(
                        'res_type' => $res_type,
                        'res_msg'  => $res_msg 
                    );
                    
                    echo json_encode($result);
                    exit();
                }   

                if($ang_lo  > $ang_hi){
                    $res_type = 'Error';
                    $res_msg  = $error_message['angle_error'];

                    $result = array(
                        'res_type' => $res_type,
                        'res_msg'  => $res_msg 
                    );
                    
                    echo json_encode($result);
                    exit();
                }
            }
           
            if ($target_opt == 0) {
                $target_ang = 0;
                $target_delay = 0;
            } elseif ($target_opt == 1) {
                $target_tor = 0;
                $target_delay = 0;
            } elseif ($target_opt == 2) {
                $target_tor = 0;
                $target_ang = 0;
            }

            $record_ang = 0;

            $jobdata = array(
                'job_id'           => $jobid,
                'seq_id'           => $seqid,
                'step_id'          => $stepid,
                'target_opt'       => $target_opt,
                'target_tor'       => $target_tor,
                'target_ang'       => $target_ang,
                'target_delay'     => $target_delay,
                'tor_hi'           => $tor_hi,
                'tor_lo'           => $tor_lo,
                'ang_hi'           => $ang_hi,
                'ang_lo'           => $ang_lo,
                'rpm'              => $rpm,
                'direction'        => $direction,
                'th_mode'          => $th_mode,
                'th_tor'           => $th_tor,
                'ds_tor'           => $ds_tor,
                'ds_speed'         => $ds_speed,
                'record_ang'      => $record_ang,
                'tor_unit'         => $tor_unit
                
            );
   
            $mode = "create"; 
            $res = $this->stepModel->create_step($mode,$jobdata);
            $result = array();
            if($res){
                $res_type = 'Success';
                $res_msg = $text['new_step'].':'.$stepid."  ".$text['success'];
                $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);
            }else{
                $res_type = 'Error';
                $res_msg = $text['new_step'].':'.$stepid."  ".$text['fail'];
                $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);
            }
        }

    }

    public function edit_step(){

        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }

        #取得扭力單位
        $res_device = $this->SettingModel->GetControllerInfo();
        if(!empty($res_device)){
            $step_torque_unit = $res_device['torque_unit'];
            if (isset($torque_unit) && is_array($torque_unit) && isset($torque_unit[$step_torque_unit])) {
                $unit_name = $torque_unit[$step_torque_unit];
            } else {
                
                $unit_name = '';
            }
        }
        
        if(isset($_POST['jobid'])){

            $jobid = isset($_POST['jobid']) ? intval($_POST['jobid']) : 0;
            $seqid = isset($_POST['seqid']) ? intval($_POST['seqid']) : 0;
            $stepid = isset($_POST['stepid']) ? intval($_POST['stepid']) : 1; 
            $target_opt = isset($_POST['target_opt'])? intval($_POST['target_opt']) : 0; 
            $target_tor = isset($_POST['target_tor'])? floatval($_POST['target_tor']) : 0; 
            $target_ang = isset($_POST['target_ang'])? intval($_POST['target_ang']) : 0; 
            $target_delay = isset($_POST['target_delay'])? floatval($_POST['target_delay']) : 0; 
            $tor_hi = isset($_POST['tor_hi'])? floatval($_POST['tor_hi']) : 0; 
            $tor_lo = isset($_POST['tor_lo'])? floatval($_POST['tor_lo']) : 0; 
            $ang_hi  = isset($_POST['ang_hi'])? intval($_POST['ang_hi']) : 0; 
            $ang_lo  = isset($_POST['ang_lo'])? intval($_POST['ang_lo']) : 0; 
            $rpm       = isset($_POST['rpm'])? intval($_POST['rpm']) : 50;
            $direction = isset($_POST['direction'])? intval($_POST['direction']) : 0;
            $th_mode = isset($_POST['th_mode'])? intval($_POST['th_mode']) : 0;
            $ds_tor = isset($_POST['ds_tor'])? floatval($_POST['ds_tor']) : 0.3; 
            $ds_speed = isset($_POST['ds_speed'])? intval($_POST['ds_speed']) : 100;
            $th_tor = isset($_POST['th_tor'])? floatval($_POST['th_tor']) : 0;
            $record_ang = isset($_POST['record_ang'])? intval($_POST['record_ang']) : 0;
            $tor_unit = isset($_POST['tor_unit'])? intval($_POST['tor_unit']) : $step_torque_unit;


            if($target_opt  == 0 ){

                if ($ds_tor > $target_tor) {
                    $res_type = 'Error';
                    $res_msg  =  $error_message['downshift_torque_error'];
                    
                    $result = array(
                        'res_type' => $res_type,
                        'res_msg'  => $res_msg 
                    );

                    echo json_encode($result);
                    exit();
                }

                if ($th_tor > $target_tor) {
                    $res_type = 'Error';
                    $res_msg  =  $error_message['threshold_torque_error'];
                    
                    $result = array(
                        'res_type' => $res_type,
                        'res_msg'  => $res_msg 
                    );

                    echo json_encode($result);
                    exit();
                }

            }

            if($target_opt  == 1){

                if($tor_lo  > $tor_hi){
                    $res_type = 'Error';
                    $res_msg  =  $error_message['torque_error'];
                    $result = array(
                        'res_type' => $res_type,
                        'res_msg'  => $res_msg 
                    );
                    echo json_encode($result);
                    exit();
                }   

                if($ang_lo  > $ang_hi){
                    $res_type = 'Error';
                    $res_msg  = $error_message['angle_error'];
                    $result = array(
                        'res_type' => $res_type,
                        'res_msg'  => $res_msg 
                    );
                    echo json_encode($result);
                    exit();
                }
            }

            $record_ang = 0;
            $jobdata = array(
                'job_id'           => $jobid,
                'seq_id'           => $seqid,
                'step_id'          => $stepid,
                'target_opt'       => $target_opt,
                'target_tor'       => $target_tor,
                'target_ang'       => $target_ang,
                'target_delay'     => $target_delay,
                'tor_hi'           => $tor_hi,
                'tor_lo'           => $tor_lo,
                'ang_hi'           => $ang_hi,
                'ang_lo'           => $ang_lo,
                'rpm'              => $rpm,
                'direction'        => $direction,
                'th_mode'          => $th_mode,
                'th_tor'           => $th_tor,
                'ds_tor'           => $ds_tor,
                'ds_speed'         => $ds_speed,
                'record_ang'      => $record_ang,
                'tor_unit'         => $tor_unit
                
            );


            $res = $this->stepModel->update_step_by_id($jobdata);
            $result = array();
            if($res){
                $res_type = 'Success';
                $res_msg  = $text['edit_step'].':'. $stepid."  ".$text['success'];
            }else{
                $res_type = 'Error';
                $res_msg  = $text['edit_step'].':'. $stepid."  ".$text['fail'];
            }
            $result = array(
                'res_type' => $res_type,
                'res_msg'  => $res_msg 
            );

            echo json_encode($result);
        }
    }


    public function delete_step(){

        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }
        

        if(isset($_POST['stepid'])){
            
            $jobid = isset($_POST['jobid']) ? intval($_POST['jobid']) : '';
            $seqid = isset($_POST['seqid']) ? intval($_POST['seqid']) : '';
            $stepid = isset($_POST['stepid']) ? intval($_POST['stepid']) : '';    
            
            if(!empty($stepid)){
                $res = $this->stepModel->delete_step_id($jobid, $seqid, $stepid);
                $result = array();
                if($res){
                    $res_type = 'Success';
                    $res_msg = $text['del_step'].':'. $stepid."  ".$text['success'];
                    $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);
                }else{
                    $res_type = 'Error';
                    $res_msg = $text['del_step'].':'. $stepid."  ".$text['fail'];
                    $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);
                }
            }
      
        }
    
    }

    public function copy_tcc_step(){

        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }

        if(isset($_POST['job_id'])){
            $job_id      = isset($_POST['job_id']) ? intval($_POST['job_id']) : '';
            $seq_id      = isset($_POST['seq_id']) ? intval($_POST['seq_id']) : '';
            $old_step_id = isset($_POST['old_step_id']) ? intval($_POST['old_step_id']) : '';
            $new_step_id = isset($_POST['new_step_id']) ? intval($_POST['new_step_id']) : '';

            #檢查被複製的那個step 是不是  Target Torque
            $check = $this->stepModel->check_copy_step($job_id,$seq_id, $old_step_id);

            $check = intval($check[0]['target_opt']);
            if($check != 0){

                //可以新增資料  
                $old_res= $this->stepModel->getStepNo($job_id,$seq_id,$old_step_id);    
                if(!empty($old_res)){
                    $jobdata = array(
                        'job_id'           => $job_id,
                        'seq_id'           => $seq_id,
                        'step_id'          => $new_step_id,
                        'target_opt'       => $old_res[0]['target_opt'],
                        'target_tor'       => $old_res[0]['target_tor'],
                        'target_ang'       => $old_res[0]['target_ang'],
                        'target_delay'     => $old_res[0]['target_delay'],
                        'tor_hi'           => $old_res[0]['tor_hi'],
                        'tor_lo'           => $old_res[0]['tor_lo'],
                        'ang_hi'           => $old_res[0]['ang_hi'],
                        'ang_lo'           => $old_res[0]['ang_lo'],
                        'rpm'              => $old_res[0]['rpm'],
                        'direction'        => $old_res[0]['direction'],
                        'th_mode'          => $old_res[0]['th_mode'],
                        'th_tor'           => $old_res[0]['th_tor'],
                        'ds_tor'           => $old_res[0]['ds_tor'],
                        'ds_speed'         => $old_res[0]['ds_speed'],
                        'record_ang'      => $old_res[0]['record_ang'],
                        'tor_unit'         => $old_res[0]['tor_unit']
                    ); 

                    $mode = "copy"; 
                    $res = $this->stepModel->create_step($mode,$jobdata);
                    $result = array();
                    if($res){
                        $res_type = 'Success';
                        $res_msg = $text['copy_step'].':'.$new_step_id."  ".$text['success'];
                        $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);
                    }else{
                        $res_type = 'Error';
                        $res_msg = $text['copy_step'].':'.$new_step_id."  ".$text['fail'];
                        $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);
                    }
                }
            }else{

                $this->MiscellaneousModel->generateErrorResponse('Error', $text['check_step_target']);
                exit();

            }
        }
          

    }
   

    #查詢step data
    public function search_stepinfo(){

        $input_check = true;
        if(!empty($_POST['job_id']) && isset($_POST['job_id'])){
            $jobid = $_POST['job_id'];
        }else{
            $input_check = false; 
        }

        if(!empty($_POST['seq_id']) && isset($_POST['seq_id'])){
            $seqid = $_POST['seq_id'];
        }else{
            $input_check = false; 
        }


        if(!empty($_POST['step_id']) && isset($_POST['step_id'])){
            $stepid  = $_POST['step_id'];
        }else{
            $input_check = false; 
        }

        if($input_check){

            $res = $this->stepModel->getStepNo($jobid, $seqid, $stepid);
            print_r($res[0]);
        }

    }
        
    #排序step
    public function adjustment_order(){

        if (isset($_POST['jobid']) && isset($_POST['rowInfoArray'])) {
            $jobid = $_POST['jobid'];
            $rowInfoArray = $_POST['rowInfoArray'];
            $this->stepModel->swapupdate($jobid,$rowInfoArray);
        }
        
    }

}
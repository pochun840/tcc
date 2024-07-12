<?php

class Step extends Controller
{
   
    // 在建構子中將 Post 物件（Model）實例化
    public function __construct()
    {
        //$this->ToolModel = $this->model('Tool');
        $this->MiscellaneousModel = $this->model('Miscellaneous');
        $this->stepModel = $this->model('Steptcc');
        $this->sequenceModel = $this->model('Sequence');
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
        $target_option_change = $this->MiscellaneousModel->details("target_option_change");
        $direction = $this->MiscellaneousModel->details('unscrew_direction');
        $unit_arr  = $this->MiscellaneousModel->details('torque_unit');
        $seqinfo   = $this->sequenceModel->search_seqinfo($job_id,$seq_id);
        $check     = $this->stepModel->check_step_target($job_id,$seq_id);


        $unit = $unit_arr[$seqinfo[0]['torque_unit']];
  

        if(empty($step)){
            $stepid_new = 1;
        }else{
            $stepid_new = count($step) + 1 ;
        }


        $data = array(
            'isMobile' => $isMobile,
            'step' => $step,
            'target_option' => $target_option,
            'target_option_change' =>$target_option_change,
            'direction' => $direction,
            'job_id' => $job_id,
            'seq_id' => $seq_id,
            'stepid_new' => $stepid_new,
            'unit_arr' => $unit_arr,
            'unit' => $unit,
            'check' => $check
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
            $stepid = isset($_POST['stepid']) ? intval($_POST['stepid']) : 0; 
            $target_option = isset($_POST['target_option'])? intval($_POST['target_option']) : 0; 
            $target_torque = isset($_POST['target_torque'])? floatval($_POST['target_torque']) : 0; 
            $hi_torque = isset($_POST['hi_torque'])? floatval($_POST['hi_torque']) : 0; 
            $lo_torque = isset($_POST['lo_torque'])? floatval($_POST['lo_torque']) : 0; 
            $hi_angle  = isset($_POST['hi_angle'])? floatval($_POST['hi_angle']) : 0; 
            $lo_angle  = isset($_POST['lo_angle'])? floatval($_POST['lo_angle']) : 0; 
            $rpm       = isset($_POST['rpm'])? floatval($_POST['rpm']) : 0;
            $direction = isset($_POST['direction'])? intval($_POST['direction']) : 0;
            $downshift = isset($_POST['downshift'])? intval($_POST['downshift']) : 0;
            $threshold_torque = isset($_POST['threshold_torque'])? intval($_POST['threshold_torque']) : 0;
            $downshift_torque = isset($_POST['downshift_torque'])? intval($_POST['downshift_torque']) : 0;
            $downshift_rpm = isset($_POST['downshift_rpm'])? intval($_POST['downshift_rpm']) : 100;

            #同一個step 只能有一個Target Torque
            $check = $this->stepModel->check_step_target($jobid,$seqid);
            $check = intval($check[0]['count_records']);

            if($check > 1){
                $status_msg ='';
                $status_msg = $text['check_step_target'];
                echo $status_msg;
                exit();

            }

            if($target_option == 2){
                $target_delaytime = $target_torque; 
                if ($target_torque < 0.1 || $target_torque > 9.9){
                    echo "超過範圍";
                    return;
                }else{
                    $target_torque = 0;
                    $target_angle  = 0;
                }
            }

            if($target_option == 0){
                $target_angle  = 0;
                $target_delaytime = 0;
            }
            if($target_option == 1){
                $target_angle = $target_torque;
                $target_torque = 0;
                $target_delaytime = 0;
            }

            $jobdata = array(
                'job_id'           => $jobid,
                'sequence_id'      => $seqid,
                'step_id'          => $stepid,
                'target_option'    => $target_option,
                'target_torque'    => $target_torque,
                'target_angle'     => $target_angle,
                'target_delaytime' => $target_delaytime,
                'hi_torque'        => $hi_torque,
                'lo_torque'        => $lo_torque,
                'hi_angle'         => $hi_angle,
                'lo_angle'         => $lo_angle,
                'rpm'              => $rpm,
                'direction'        => $direction,
                'downshift'        => $downshift,
                'threshold_torque' => $threshold_torque,
                'downshift_torque' => $downshift_torque,
                'downshift_rpm'    => $downshift_rpm,
                
            );

            $mode = "create"; 
            $res = $this->stepModel->create_step($mode,$jobdata);
            $result = array();
            if($res){
                $res_type = 'Success';
                $res_msg = $text['new_step'].':'.$stepid."  ".$text['success'];
            }else{
                $res_type = 'Error';
                $res_msg = $text['new_step'].':'.$stepid."  ".$text['fail'];
            }

            $result = array(
                'res_type' => $res_type,
                'res_msg'  => $res_msg 
            );

            echo json_encode($result);
        }

    }

    public function edit_step(){

        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }

        if(isset($_POST['jobid'])){

            $jobid = isset($_POST['jobid']) ? intval($_POST['jobid']) : 0;
            $seqid = isset($_POST['seqid']) ? intval($_POST['seqid']) : 0;
            $stepid = isset($_POST['stepid']) ? intval($_POST['stepid']) : 0; 
            $target_option = isset($_POST['target_option'])? intval($_POST['target_option']) : 0; 

            $target_torque = isset($_POST['target_torque'])? floatval($_POST['target_torque']) : 0; 
            $target_angle = isset($_POST['target_angle'])? intval($_POST['target_angle']) : 0; 
            $target_delaytime = isset($_POST['target_delaytime'])? intval($_POST['target_delaytime']) : 0; 
            $hi_torque = isset($_POST['hi_torque'])? floatval($_POST['hi_torque']) : 0; 
            $lo_torque = isset($_POST['lo_torque'])? floatval($_POST['lo_torque']) : 0; 
            $hi_angle  = isset($_POST['hi_angle'])? floatval($_POST['hi_angle']) : 0; 
            $lo_angle  = isset($_POST['lo_angle'])? floatval($_POST['lo_angle']) : 0; 
            $rpm       = isset($_POST['rpm'])? intval($_POST['rpm']) : 0;
            $direction = isset($_POST['direction'])? intval($_POST['direction']) : 0;
            $downshift = isset($_POST['downshift'])? intval($_POST['downshift']) : 0;
            $threshold_torque = isset($_POST['threshold_torque'])? intval($_POST['threshold_torque']) : 0;
            $downshift_torque = isset($_POST['downshift_torque'])? intval($_POST['downshift_torque']) : 0;
            $downshift_rpm = isset($_POST['downshift_rpm'])? intval($_POST['downshift_rpm']) : 100;

            if($target_option == 2){
                if ($target_delaytime < 0.1 || $target_delaytime > 9.9){
                    echo "超過範圍";
                    return;
                }

            }

            #同一個step 只能有一個Target Torque
            $check = $this->stepModel->check_step_target($jobid,$seqid);
            $check = intval($check[0]['count_records']);

            if($check > 1){
                $status_msg ='';
                $status_msg = $text['check_step_target'];
                echo $status_msg;
                exit();

            }

            $jobdata = array(
                'job_id'           => $jobid,
                'sequence_id'      => $seqid,
                'step_id'          => $stepid,
                'target_option'    => $target_option,
                'target_torque'    => $target_torque,
                'target_angle'     => $target_angle,
                'target_delaytime' => $target_delaytime,
                'hi_torque'        => $hi_torque,
                'lo_torque'        => $lo_torque,
                'hi_angle'         => $hi_angle,
                'lo_angle'         => $lo_angle,
                'rpm'              => $rpm,
                'direction'        => $direction,
                'downshift'        => $downshift,
                'threshold_torque' => $threshold_torque,
                'downshift_torque' => $downshift_torque,
                'downshift_rpm'    => $downshift_rpm,
                
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
            
            $jobid = isset($_POST['jobid']) ? intval($_POST['jobid']) : 0;
            $seqid = isset($_POST['seqid']) ? intval($_POST['seqid']) : 0;
            $stepid = isset($_POST['stepid']) ? intval($_POST['stepid']) : 0;        
            $res = $this->stepModel->delete_step_id($jobid, $seqid, $stepid);
            $result = array();
            if($res){
                $res_type = 'Success';
                $res_msg = $text['del_step'].':'. $stepid."  ".$text['success'];
            }else{
                $res_type = 'Error';
                $res_msg = $text['del_step'].':'. $stepid."  ".$text['fail'];
            }
            $result = array(
                'res_type' => $res_type,
                'res_msg'  => $res_msg 
            );

            echo json_encode($result);
        
        }
    
    }

    public function copy_step(){

        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }

        if(isset($_POST['jobid'])){

            #如果 POST 中沒有，則使用預設值
            $jobid = isset($_POST['jobid']) ? intval($_POST['jobid']) : 0;
            $seqid = isset($_POST['seqid']) ? intval($_POST['seqid']) : 0;
            $stepid = isset($_POST['stepid']) ? intval($_POST['stepid']) : 0;
            $stepid_new = isset($_POST['stepid_new']) ? intval($_POST['stepid_new']) : 0;
            $step_count = $this->stepModel->countstep($jobid, $seqid);
            $step_count = intval($step_count);

            if($step_count >= 4) {
                echo "The maximum number of steps has been reached, unable to continue copying steps";
                return;
            }

            
            #檢查被複製的那個step 是不是  Target Torque
            $check = $this->stepModel->check_copy_step($jobid,$seqid,$stepid);
            $check = intval($check[0]['target_option']);
            if($check == 0 ){
                $status_msg ='';
                $status_msg = $text['check_step_target'];
                echo $status_msg;
                exit();

            }else{
                $old_res= $this->stepModel->getStepNo($jobid,$seqid,$stepid);
                if(!empty($old_res)){
                    $jobdata = array(
                        'job_id'           => $jobid,
                        'sequence_id'      => $seqid,
                        'step_id'          => $stepid_new,
                        'target_option'    => $old_res[0]['target_option'],
                        'target_torque'    => $old_res[0]['target_torque'],
                        'target_angle'     => $old_res[0]['target_angle'],
                        'target_delaytime' => $old_res[0]['target_delaytime'],
                        'hi_torque'        => $old_res[0]['hi_torque'],
                        'lo_torque'        => $old_res[0]['lo_torque'],
                        'hi_angle'         => $old_res[0]['hi_angle'],
                        'lo_angle'         => $old_res[0]['lo_angle'],
                        'rpm'              => $old_res[0]['rpm'],
                        'direction'        => $old_res[0]['direction'],
                        'downshift'        => $old_res[0]['downshift'],
                        'threshold_torque' => $old_res[0]['threshold_torque'],
                        'downshift_torque' => $old_res[0]['downshift_torque'],
                        'downshift_rpm'    => $old_res[0]['downshift_rpm']
                    );
    
                    $mode = "copy"; 
                    $res = $this->stepModel->create_step($mode,$jobdata);

                    if($res){
                        $res_type = 'Success';
                        $res_msg  = $text['copy_step'].':'.$stepid_new."  ".$text['success'];
                    }else{
                        $res_type = 'Error';
                        $res_msg  = $text['copy_step'].':'.$stepid_new."  ".$text['fail'];
                    }
        
                    $result = array(
                        'res_type' => $res_type,
                        'res_msg'  => $res_msg 
                    );
        
                    echo json_encode($result);
                }
     
            }
           
        }

    }


    public function search_stepinfo(){
        if(isset($_POST['stepid'])){
            
            $jobid = isset($_POST['jobid']) ? intval($_POST['jobid']) : 0;
            $seqid = isset($_POST['seqid']) ? intval($_POST['seqid']) : 0;
            $stepid = isset($_POST['stepid']) ? intval($_POST['stepid']) : 0;        
            $res = $this->stepModel->getStepNo($jobid, $seqid, $stepid);
            print_r($res[0]);
        
        }

    }


        
    public function adjustment_order(){
        if (isset($_POST['jobid']) && isset($_POST['rowInfoArray'])) {
            $jobid = $_POST['jobid'];
            $rowInfoArray = $_POST['rowInfoArray'];

            $this->stepModel->swapupdate($jobid,$rowInfoArray);
            
            //var_dump($jobid);die();
            //echo json_encode(['success' => true]);
        } else {
            // 如果缺少必要的数据，返回一个错误的响应
            //echo json_encode(['error' => 'Missing data']);
        }
        
    }

}
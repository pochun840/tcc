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
        $direction = $this->MiscellaneousModel->details('rev_direction');
        $unit_arr  = $this->MiscellaneousModel->details('torque_unit');
        $seqinfo   = $this->sequenceModel->search_seqinfo($job_id,$seq_id);
        $check     = $this->stepModel->check_step_target($job_id,$seq_id);
        $tools     = $this->ToolModel->GetToolInfo();

        $step_count = $this->stepModel->countstep($job_id, $seq_id);
        $step_count = intval($step_count);
        if($step_count ==0){
            $step_count = 1;
        }


        $res_device = $this->SettingModel->GetControllerInfo();
        if(!empty($res_device)){
            $step_torque_unit = $res_device['torque_unit'];
            $unit_name = $torque_unit[$step_torque_unit];
        }
        
        if(empty($step)){
            $stepid_new = 1;
        }else{
            $stepid_new = count($step) + 1 ;
        }

        //$count_records = (int)$check['count_records'];
        if(!empty($check[0]['count_records'])){


            $check_step_torque = 1;
        }else{
            $check_step_torque = '';
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
            'step_torque_unit' => $step_torque_unit,
            'check' => $check,
            'seq_id' => $seq_id,
            'unit_name' => $unit_name,
            'check_step_torque' => $check_step_torque,
            'check' => $check,
            'step_count' => $step_count,
            'tools' => $tools
            //'count_records' => $count_records

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
            $target_opt = isset($_POST['target_opt'])? intval($_POST['target_opt']) : 0; 
            $target_tor = isset($_POST['target_tor'])? floatval($_POST['target_tor']) : 0; 
            $target_ang = isset($_POST['target_ang'])? intval($_POST['target_ang']) : 0; 
            $target_delay = isset($_POST['target_delay'])? floatval($_POST['target_delay']) : 0; 
            $tor_hi = isset($_POST['tor_hi'])? floatval($_POST['tor_hi']) : 0; 
            $tor_lo = isset($_POST['tor_lo'])? floatval($_POST['tor_lo']) : 0; 
            $ang_hi  = isset($_POST['ang_hi'])? intval($_POST['ang_hi']) : 0; 
            $ang_lo  = isset($_POST['lo_angle'])? intval($_POST['ang_lo']) : 0; 
            $rpm       = isset($_POST['rpm'])? intval($_POST['rpm']) : 0;
            $direction = isset($_POST['direction'])? intval($_POST['direction']) : 0;
            $ds_mode = isset($_POST['ds_mode'])? intval($_POST['ds_mode']) : 0;
            $ds_tor = isset($_POST['ds_tor'])? floatval($_POST['ds_tor']) : 0; 
            $ds_speedg = isset($_POST['ds_speed'])? intval($_POST['ds_speed']) : 0;
            $th_tor = isset($_POST['th_tor'])? floatval($_POST['th_tor']) : 0;
            $record_ang = isset($_POST['record_ang'])? intval($_POST['record_ang']) : 0;
            $tor_unit = isset($_POST['tor_unit'])? intval($_POST['tor_unit']) : 0;

            #同一個step 只能有一個Target Torque
            $check = $this->stepModel->check_step_target($jobid,$seqid);
            $check = intval($check[0]['count_records']);



            if($target_option == 2){
                $downshift = 1;
            }
            


    
            #驗證hi_angle的範圍
            /*if(!empty($hi_angle)){
                $ans = $this->MiscellaneousModel->check_angle($hi_angle);
                if($ans == FALSE){
                    $res_type = 'Error';
                    $res_msg  = $error_message['High_Angle'];
                    $result = array(
                        'res_type' => $res_type,
                        'res_msg'  => $res_msg 
                    );
                    echo json_encode($result);
                    exit();

                }
            }*/

            #驗證lo_angle的範圍
            /*if(!empty($lo_angle)){
                $ans = $this->MiscellaneousModel->check_angle($lo_angle);
                if($ans == FALSE){
                    $res_type = 'Error';
                    $res_msg  = $error_message['Low_Angle'];
                    $result = array(
                        'res_type' => $res_type,
                        'res_msg'  => $res_msg 
                    );
                    echo json_encode($result);
                    exit();

                }
            }*/


            #最小角度 必須小於 最大角度
            /*if($lo_angle > $hi_angle){
                $res_type = 'Error';
                $res_msg  =  $error_message['angle_error'];
                $result = array(
                    'res_type' => $res_type,
                    'res_msg'  => $res_msg 
                );
                echo json_encode($result);
                exit();

            }*/


            if($check > 1){
                $this->MiscellaneousModel->generateErrorResponse('Error', $text['check_step_target']);
                exit();
            }

            if($target_option  == 0 && $target_option  == 1){
                #$target_torque 必填
                if(empty($target_torque)){
                    $res_type = 'Error';
                    $res_msg  =  $error_message['target_torque_empty'];
                    $result = array(
                        'res_type' => $res_type,
                        'res_msg'  => $res_msg 
                    );
                    echo json_encode($result);
                    exit();
                }

                #最小扭力 必須小於 最大扭力
                if($hi_torque < $lo_torque){
                    $res_type = 'Error';
                    $res_msg  =  $error_message['torque_error'];
                    $result = array(
                        'res_type' => $res_type,
                        'res_msg'  => $res_msg 
                    );
                    echo json_encode($result);
                    exit();
                }

                //if()
            }



            if($target_option == 2){
                $target_delaytime = $target_torque; 
                if ($target_torque < 0.1 || $target_torque > 9.9){
                    if($target_option == 2){
                        if ($target_delaytime < 0.1 || $target_delaytime > 9.9){
        
                            $res_type = 'Error';
                            $res_msg  =  $text['check_step_target'];
                            $result = array(
                                'res_type' => $res_type,
                                'res_msg'  => $res_msg 
                            );
                            echo json_encode($result);
                            exit();
                        }
        
                    }
        
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
                'seq_id'           => $seqid,
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
                'downshift_speed'    => $downshift_speed,
                
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

        if(isset($_POST['jobid'])){

            $jobid = isset($_POST['jobid']) ? intval($_POST['jobid']) : '';
            $seqid = isset($_POST['seqid']) ? intval($_POST['seqid']) : '';
            $stepid = isset($_POST['stepid']) ? intval($_POST['stepid']) : ''; 
            $target_option = isset($_POST['target_option'])? intval($_POST['target_option']) : ''; 

            $target_torque = isset($_POST['target_torque'])? floatval($_POST['target_torque']) : ''; 
            $target_angle = isset($_POST['target_angle'])? floatval($_POST['target_angle']) : ''; 
            $target_delaytime = isset($_POST['target_delaytime'])? floatval($_POST['target_delaytime']) : ''; 
            $hi_torque = isset($_POST['hi_torque'])? floatval($_POST['hi_torque']) : ''; 
            $lo_torque = isset($_POST['lo_torque'])? floatval($_POST['lo_torque']) : ''; 
            $hi_angle  = isset($_POST['hi_angle'])? intval($_POST['hi_angle']) : ''; 
            $lo_angle  = isset($_POST['lo_angle'])? intval($_POST['lo_angle']) : ''; 
            $rpm       = isset($_POST['rpm'])? intval($_POST['rpm']) : '';
            $direction = isset($_POST['direction'])? intval($_POST['direction']) : '';
            $downshift = isset($_POST['downshift'])? intval($_POST['downshift']) : '';
            $threshold_torque = isset($_POST['threshold_torque'])? intval($_POST['threshold_torque']) : '';
            $downshift_torque = isset($_POST['downshift_torque'])? intval($_POST['downshift_torque']) : '';
            $downshift_speed = isset($_POST['downshift_speed'])? intval($_POST['downshift_speed']) : 100;

            
            #驗證hi_angle的範圍
            if(!empty($hi_angle)){
                $ans = $this->MiscellaneousModel->check_angle($hi_angle);
                if($ans == FALSE){
                    $res_type = 'Error';
                    $res_msg  = $error_message['High_Angle'];
                    $result = array(
                        'res_type' => $res_type,
                        'res_msg'  => $res_msg 
                    );
                    echo json_encode($result);
                    exit();

                }
            }

            #驗證lo_angle的範圍
            if(!empty($lo_angle)){
                $ans = $this->MiscellaneousModel->check_angle($lo_angle);
                if($ans == FALSE){
                    $res_type = 'Error';
                    $res_msg  = $error_message['Low_Angle'];
                    $result = array(
                        'res_type' => $res_type,
                        'res_msg'  => $res_msg 
                    );
                    echo json_encode($result);
                    exit();

                }
            }

            #最小角度 必須小於 最大角度
            if($lo_angle > $hi_angle){
                $res_type = 'Error';
                $res_msg  =  $error_message['angle_error'];
                $result = array(
                    'res_type' => $res_type,
                    'res_msg'  => $res_msg 
                );
                echo json_encode($result);
                exit();

            }




            
            if($target_option  == 0 && $target_option  == 1){

                #$target_torque 必填
                if(empty($target_torque)){
                    $res_type = 'Error';
                    $res_msg  =  $error_message['target_torque_empty'];
                    $result = array(
                        'res_type' => $res_type,
                        'res_msg'  => $res_msg 
                    );
                    echo json_encode($result);
                    exit();
                }

                #最小扭力 必須小於 最大扭力
                if($hi_torque < $lo_torque){
                    $res_type = 'Error';
                    $res_msg  =  $error_message['torque_error'];
                    $result = array(
                        'res_type' => $res_type,
                        'res_msg'  => $res_msg 
                    );
                    echo json_encode($result);
                    exit();
                }
            }

      
            if($target_option == 2){
                if ($target_delaytime < 0.1 || $target_delaytime > 9.9){

                    $res_type = 'Error';
                    $res_msg  =  $text['check_step_target'];
                    $result = array(
                        'res_type' => $res_type,
                        'res_msg'  => $res_msg 
                    );
                    echo json_encode($result);
                    exit();
                }

            }

            #同一個step 只能有一個Target Torque
            $check = $this->stepModel->check_step_target($jobid,$seqid);
            $check = intval($check[0]['count_records']);
            
            if($check > 1 && $target_option == 0){
                $this->MiscellaneousModel->generateErrorResponse('Error', $text['check_step_target']);
                exit();

            }
            

            $jobdata = array(
                'job_id'           => $jobid,
                'seq_id'           => $seqid,
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
                'downshift_speed'    => $downshift_speed,
                
            );

            

            $res = $this->stepModel->update_step_by_id($jobdata);
            $result = array();
            if($res){
                $res_type = 'Success';
                $res_msg  = $text['edit_step'].':'. $_POST['stepid']."  ".$text['success'];
            }else{
                $res_type = 'Error';
                $res_msg  = $text['edit_step'].':'. $_POST['stepid']."  ".$text['fail'];
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

    public function copy_step(){

        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }

        if(isset($_POST['jobid'])){

            #如果 POST 中沒有，則使用預設值
            $jobid = isset($_POST['jobid']) ? intval($_POST['jobid']) : '';
            $seqid = isset($_POST['seqid']) ? intval($_POST['seqid']) : '';
            $stepid = isset($_POST['stepid']) ? intval($_POST['stepid']) : 0;
            $stepid_new = isset($_POST['stepid_new']) ? intval($_POST['stepid_new']) : '';
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
                $this->MiscellaneousModel->generateErrorResponse('Error', $text['check_step_target'] );

            }else{
                $old_res= $this->stepModel->getStepNo($jobid,$seqid,$stepid);
                if(!empty($old_res)){
                    $jobdata = array(
                        'job_id'           => $jobid,
                        'seq_id'           => $seqid,
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
                        'downshift_speed'  => $old_res[0]['downshift_speed']
                    );
    
                    $mode = "copy"; 
                    $res = $this->stepModel->create_step($mode,$jobdata);

                    if($res){
                        $res_type = 'Success';
                        $res_msg  = $text['copy_step'].':'.$stepid_new."  ".$text['success'];
                        $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);
                    }else{
                        $res_type = 'Error';
                        $res_msg  = $text['copy_step'].':'.$stepid_new."  ".$text['fail'];
                        $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);
                    }
        
                }
            }
        }

    }

    #查詢step data
    public function search_stepinfo(){

        
        $input_check = true;
        if(!empty($_POST['jobid']) && isset($_POST['jobid'])){
            $jobid = $_POST['jobid'];
        }else{
            $input_check = false; 
        }

        if(!empty($_POST['seqid']) && isset($_POST['seqid'])){
            $seqid = $_POST['seqid'];
        }else{
            $input_check = false; 
        }


        if(!empty($_POST['stepid']) && isset($_POST['stepid'])){
            $stepid  = $_POST['stepid'];
        }else{
            $input_check = false; 
        }

        if($input_check){

            $check = $this->stepModel->check_step_target($jobid, $seqid);
            $check_count = intval($check[0]['count_records']);

            $res = $this->stepModel->getStepNo($jobid, $seqid, $stepid);
            $res['check_count'] = $check_count;
            print_r($res);
        }

    }
        
    #排序step
    public function adjustment_order(){
        if (isset($_POST['jobid']) && isset($_POST['rowInfoArray'])) {
            $jobid = $_POST['jobid'];
            $rowInfoArray = $_POST['rowInfoArray'];

            $this->stepModel->swapupdate($jobid,$rowInfoArray);
        } else {
            
        }
        
    }

}
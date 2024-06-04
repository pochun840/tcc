<?php

class Step extends Controller
{
   
    // 在建構子中將 Post 物件（Model）實例化
    public function __construct()
    {
        //$this->ToolModel = $this->model('Tool');
        $this->MiscellaneousModel = $this->model('Miscellaneous');
        $this->stepModel = $this->model('Steptcc');
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
        $direction = $this->MiscellaneousModel->details('unscrew_direction');


        if(empty($step)){
            $stepid_new = 1;
        }else{
            $stepid_new = count($step) + 1 ;
        }
        
        $data = array(
            'isMobile' => $isMobile,
            'step' => $step,
            'target_option' => $target_option,
            'direction' => $direction,
            'job_id' => $job_id,
            'seq_id' => $seq_id,
            'stepid_new' => $stepid_new,
        );

        
        $this->view('step/index',$data);
        
    }


    #create step 
    public function create_step(){
        /*
         jobid: jobid,
                seqid: seqid,
                stepid: stepid,
                target_option: target_option,
                target_torque: target_torque,
                hi_torque: hi_torque,
                lo_torque: lo_torque,
                hi_angle: hi_angle,
                lo_angle: lo_angle,
                rpm: rpm,
                direction: direction,
                downshift: downshift,
                threshold_torque: threshold_torque,
                downshift_torque: downshift_torque,
                downshift_speed: downshift_speed
        */
        if(isset($_POST['jobid'])){

            $jobid = isset($_POST['jobid']) ? intval($_POST['jobid']) : 0;
            $seqid = isset($_POST['seqid']) ? intval($_POST['seqid']) : 0;
            $stepid = isset($_POST['stepid']) ? intval($_POST['stepid']) : 0; 
            $target_option = isset($_POST['target_option'])? intval($_POST['target_option']) : 0; 
            $target_torque = isset($_POST['target_torque'])? intval($_POST['target_torque']) : 0; 
            $hi_torque = isset($_POST['hi_torque'])? intval($_POST['hi_torque']) : 0; 
            $lo_torque = isset($_POST['lo_torque'])? intval($_POST['lo_torque']) : 0; 
            $hi_angle  = isset($_POST['hi_angle'])? intval($_POST['hi_angle']) : 0; 
            $lo_angle  = isset($_POST['lo_angle'])? intval($_POST['lo_angle']) : 0; 
            $rpm       = isset($_POST['rpm'])? intval($_POST['rpm']) : 0;
            $direction = isset($_POST['direction'])? intval($_POST['direction']) : 0;
            $downshift = isset($_POST['downshift'])? intval($_POST['downshift']) : 0;
            $threshold_torque = isset($_POST['threshold_torque'])? intval($_POST['threshold_torque']) : 0;
            $downshift_torque = isset($_POST['downshift_torque'])? intval($_POST['downshift_torque']) : 0;
            $downshift_rpm = isset($_POST['downshift_rpm'])? intval($_POST['downshift_rpm']) : 100;

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
            if($res){
                $res_msg = 'create:'.$stepid.'success';
            }else{
                $res_msg = 'create:'.$stepid.'fail';
            }
            echo $res_msg;
        }

    }

    #update step
    public function edit_step(){
        if(isset($_POST['jobid'])){

            $jobid = isset($_POST['jobid']) ? intval($_POST['jobid']) : 0;
            $seqid = isset($_POST['seqid']) ? intval($_POST['seqid']) : 0;
            $stepid = isset($_POST['stepid']) ? intval($_POST['stepid']) : 0; 
            $target_option = isset($_POST['target_option'])? intval($_POST['target_option']) : 0; 
            $target_torque = isset($_POST['target_torque'])? floatval($_POST['target_torque']) : 0; 
            $target_angle = isset($_POST['target_angle'])? intval($_POST['target_angle']) : 0; 
            $target_delaytime = isset($_POST['target_delaytime'])? intval($_POST['target_delaytime']) : 0; 
            $hi_torque = isset($_POST['hi_torque'])? intval($_POST['hi_torque']) : 0; 
            $lo_torque = isset($_POST['lo_torque'])? intval($_POST['lo_torque']) : 0; 
            $hi_angle  = isset($_POST['hi_angle'])? intval($_POST['hi_angle']) : 0; 
            $lo_angle  = isset($_POST['lo_angle'])? intval($_POST['lo_angle']) : 0; 
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
            if($res){
                $res_msg = 'update step:'. $stepid.' success';
            }else{
                $res_msg = 'update step:'. $stepid.' fail';
            }
            echo $res_msg;
        }
    }


    #delete step
    public function delete_step(){

        if(isset($_POST['stepid'])){
            
            $jobid = isset($_POST['jobid']) ? intval($_POST['jobid']) : 0;
            $seqid = isset($_POST['seqid']) ? intval($_POST['seqid']) : 0;
            $stepid = isset($_POST['stepid']) ? intval($_POST['stepid']) : 0;        
            $res = $this->stepModel->delete_step_id($jobid, $seqid, $stepid);
            if($res){
                $res_msg = 'delete step:'. $stepid.' success';
            }else{
                $res_msg = 'delete step:'. $stepid.' fail';
            }
            echo $res_msg;
        
        }
    
    }

    public function copy_step(){

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
                    $res_msg = 'copy:'.$stepid_new.'success';
                }else{
                    $res_msg = 'copy:'.$stepid_new.'fail';
                }
    
                echo $res_msg;
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
}
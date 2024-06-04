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







    
}
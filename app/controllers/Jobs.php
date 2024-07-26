<?php
class Jobs extends Controller
{
    private $jobModel;
    private $DashboardModel;
    private $ToolModel;
    private $SettingModel;

    // 在建構子中將 Post 物件（Model）實例化
    public function __construct()
    {
        $this->jobModel = $this->model('Job');
        $this->DashboardModel = $this->model('Dashboard');
        $this->MiscellaneousModel = $this->model('Miscellaneous');
        
    }

    // 取得所有Jobs
    public function index(){
        $data = array();

        $isMobile  = $this->isMobileCheck();
        $jobs      = $this->jobModel->getJobs();
        $direction = $this->MiscellaneousModel->details('unscrew_direction');

        $lastRow  = end($jobs);
        $jobIdInt = intval($lastRow['job_id']) + 1 ;
      
        $data = array(
            'jobint' => $jobIdInt,
            'jobs' => $jobs,
            'direction' => $direction,
        );
        
        if($isMobile){
            $this->view('jobs/job_management_m',$data);
        }else{
            $this->view('jobs/job_management', $data);
        }

    }


    #create 
    public function create_job(){

        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }


        if(isset($_POST['jobidnew'])){

            $jobdata = array(
                'job_id' => $_POST['jobidnew'],
                'job_name' => $_POST['jobname_val'],
                'unscrew_power' => $_POST['unscre_power_val'],
                'unscrew_rpm' => $_POST['unscrew_rpm_val'],
                'unscrew_direction' => $_POST['direction_val'],
            );

            //$mode = "create";
            $result3  = $this->MiscellaneousModel->validateName($jobdata['job_name']);
            $result1 = $this->MiscellaneousModel->validateUnscrewPower($jobdata['unscrew_power']);
            $result2 = $this->MiscellaneousModel->validateUnscrewPower($jobdata['unscrew_rpm']);

            if($result1 == false || $result2  == false){
                $this->MiscellaneousModel->generateErrorResponse('Error', $text['unfasten_force']);
            }

            if($result3 == false){
                $this->MiscellaneousModel->generateErrorResponse('Error', $text['error_job_name']);
            }

            if ($result3 == true  && $result1 == true && $result2 == true) {

                $job_count = $this->jobModel->countjob();
                /*if($job_count >= 50) {
                    echo "The maximum number of steps has been reached, unable to continue copying jobs";
                    return;
                }*/
    
                $res = $this->jobModel->create_job($jobdata);
                $result = array();
                if($res){
                    $res_msg  = $text['New']."  ".$text['job_id'].':'. $jobdata['job_id']."  ".$text['success'];
                    $this->MiscellaneousModel->generateErrorResponse('Success', $res_msg);
                }else{
                    $res_msg  = $text['New']."  ".$text['job_id'].':'.$jobdata['job_id']."  ".$text['fail'];
                    $this->MiscellaneousModel->generateErrorResponse('Error', $res_msg);
                }
            }
                      
        }
    }

    public function update_job(){

        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }

        
        $jobdata  = array();
        if(isset($_POST['jobid'])){

            $jobdata = array(
                'job_id' => $_POST['jobid'],
                'job_name' => $_POST['jobname'],
                'unscrew_power' => $_POST['powervalue'],
                'unscrew_rpm' => $_POST['rpmvalue'],
                'unscrew_direction' => $_POST['directionValue'],
            );
            
            $result3  = $this->MiscellaneousModel->validateName($jobdata['job_name']);
            $result1 = $this->MiscellaneousModel->validateUnscrewPower($jobdata['unscrew_power']);
            $result2 = $this->MiscellaneousModel->validateUnscrewPower($jobdata['unscrew_rpm']);

            if($result1 == false || $result2  == false){

                $this->MiscellaneousModel->generateErrorResponse('Error', $text['unfasten_force']);
            
            }


            if($result3 == false){
                $this->MiscellaneousModel->generateErrorResponse('Error', $text['error_job_name']);
            }

            if ($result3 == true  && $result1 == true && $result2 == true) {
                
                $res = $this->jobModel->update_job_by_id($jobdata);
                $result = array();
                if($res){
                    $res_msg = $text['Edit']."  ".$text['job_id'].':'. $jobdata['job_id']."  ".$text['success'];
                    $this->MiscellaneousModel->generateErrorResponse('Succes', $res_msg );
                }else{
                    $res_msg = $text['Edit']."  ".$text['job_id'].':'. $jobdata['job_id']."  ".$text['fail'];
                    $this->MiscellaneousModel->generateErrorResponse('Error', $res_msg );
                }

            }
        } 
    
    }

    #delete 
    public function delete_jobid() {

        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }
 
        $jobid = $_POST['jobid'] ?? null;
        if(!empty($jobid)){

            $res = $this->jobModel->delete_job_by_id($jobid);
            $ans = $this->jobModel->delete_sequence_by_job_id($jobid);
            $an1 = $this->jobModel->delete_step_by_job_id($jobid);


            $result = array();
            if($res){
                $res_msg = $text['Delete']."  ".$text['job_id'].':'. $jobid."  ".$text['success'];
                $this->MiscellaneousModel->generateErrorResponse('Success', $res_msg );
            }else{
                $res_msg = $text['Delete']."  ".$text['job_id'].':'. $jobid."  ".$text['fail'];
                $this->MiscellaneousModel->generateErrorResponse('Error', $res_msg );
            }

        }
   
    }

    public function search_job($jobid){
        $jobid = $_POST['jobid'] ?? null;
        if(!empty($jobid)){
            $res  = $this->jobModel->search_jobinfo($jobid);
            print_r($res);
        }
    }

    public function check_job_type(){
        $jobid = $_POST['new_jobid'] ?? null;

        if(!empty($jobid)){
            $res  = $this->jobModel->job_id_repeat($jobid);
            echo  $res;
        }
      

    }

    #copy 
    public function copy_job_data(){
        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }

        /*
         old_jobid: old_jobid,
                            old_jobname: oldjobname,
                            new_jobid: new_jobid,
                            new_jobname: new_jobname

        */

        $old_jobid   = $_POST['old_jobid'] ?? null;
        $old_jobname = $_POST['old_jobname'] ?? null;
        $new_jobid   = $_POST['new_jobid'] ?? null;
        $new_jobname = $_POST['new_jobname'] ?? null;



        if(!empty($old_jobid)){
            $job_count = $this->jobModel->countjob();
            if($job_count >= 50) {
          
            }else{
           
                $old_res = $this->jobModel->search_jobinfo($old_jobid);

                $this->jobModel->del_job_type($new_jobid);
                $this->jobModel->del_seq_type($jobid);
                $this->jobModel->del_step_type($jobid);
         
                if(!empty($old_res)){

                    #取得 unscrew_power && 	unscrew_rpm && unscrew_direction
                    $jobdata = array(
                        'job_id'   => $_POST['new_jobid'],
                        'job_name' => $_POST['new_jobname'],
                        'unscrew_power' => $old_res['unscrew_power'],
                        'unscrew_rpm' => $old_res['unscrew_rpm'],  
                        'unscrew_direction' => $old_res['unscrew_direction'],  

                    );
                    $res = $this->jobModel->create_job($jobdata);
                    //用job_id 找出對應的seq && step
                    $select_seq  = $this->jobModel->search_seqinfo($old_jobid); 
                    $select_step = $this->jobModel->search_stepnfo($old_jobid); 

                    if(!empty($select_seq)){
                        $new_temp_seq = array();
                        foreach($select_seq as $key =>$val){
                            $new_temp_seq[$key]['job_id'] = $new_jobid;
                            $new_temp_seq[$key]['sequence_id'] = $val['sequence_id'];
                            $new_temp_seq[$key]['sequence_name'] = $val['sequence_name'];
                            $new_temp_seq[$key]['tightening_repeat'] = $val['tightening_repeat'];
                            $new_temp_seq[$key]['ng_stop'] = $val['ng_stop'];
                            $new_temp_seq[$key]['sequence_enable'] = $val['sequence_enable'];
                            $new_temp_seq[$key]['screw_join'] = $val['screw_join'];
                            $new_temp_seq[$key]['okall_stop'] = $val['okall_stop'];
                            $new_temp_seq[$key]['opt'] = $val['opt'];
                            $new_temp_seq[$key]['torque_unit'] = $val['torque_unit'];
                            $new_temp_seq[$key]['k_value'] = $val['k_value'];
                            $new_temp_seq[$key]['ok_time'] = $val['ok_time'];
                            $new_temp_seq[$key]['okall_alarm_time'] = $val['okall_alarm_time'];
                            $new_temp_seq[$key]['offset'] = $val['offset'];
                        }

                        $insertedrecords = $this->jobModel->copy_sequence_by_job_id($new_temp_seq);                
                    }

                    if(!empty($select_step)){
                        $new_temp_step = array();
                        
                        foreach($select_step as $key_step =>$val_step){

                            $new_temp_step[$key_step]['job_id'] = $new_jobid;
                            $new_temp_step[$key_step]['sequence_id'] = $val_step['sequence_id'];
                            $new_temp_step[$key_step]['step_id'] = $val_step['step_id'];
                            $new_temp_step[$key_step]['target_option'] = $val_step['target_option']; 
                            $new_temp_step[$key_step]['target_torque'] = $val_step['target_torque'];
                            $new_temp_step[$key_step]['target_angle'] = $val_step['target_angle'];
                            $new_temp_step[$key_step]['target_delaytime'] = $val_step['target_delaytime'];
                            $new_temp_step[$key_step]['hi_torque'] = $val_step['hi_torque'];
                            $new_temp_step[$key_step]['lo_torque'] = $val_step['lo_torque'];
                            $new_temp_step[$key_step]['hi_angle'] = $val_step['hi_angle'];
                            $new_temp_step[$key_step]['lo_angle'] = $val_step['lo_angle'];
                            $new_temp_step[$key_step]['rpm'] = $val_step['rpm'];
                            $new_temp_step[$key_step]['direction'] = $val_step['direction'];
                            $new_temp_step[$key_step]['downshift'] = $val_step['downshift'];
                            $new_temp_step[$key_step]['threshold_torque'] = $val_step['threshold_torque'];
                            $new_temp_step[$key_step]['downshift_torque'] = $val_step['downshift_torque'];
                            $new_temp_step[$key_step]['downshift_rpm'] = $val_step['downshift_rpm'];
                        }

                        $insertedrecords = $this->jobModel->copy_step_by_job_id($new_temp_step);  
                    }
                    
                    if($res){
                        $res_msg = $text['Copy']."  ".$text['job_id'].':'. $_POST['new_jobid']."  ".$text['success'];
                        $this->MiscellaneousModel->generateErrorResponse('Success', $res_msg );
                    }else{
                        $res_msg = $text['Copy']."  ".$text['job_id'].':'. $_POST['new_jobid']."  ".$text['fail'];
                        $this->MiscellaneousModel->generateErrorResponse('Error', $res_msg );
                    }
                    
                }
            }
        
        }

    }

}

?>

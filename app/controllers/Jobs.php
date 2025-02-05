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
        $direction = $this->MiscellaneousModel->details('rev_direction');

        $next_job_id_arr = $this->jobModel->get_head_job_id();
        $next_job_id = (int)$next_job_id_arr['missing_id'];
        if(!empty($jobs)){
            $lastRow  = end($jobs);
            $jobIdInt = intval($lastRow['job_id']) + 1 ;   
        }else{
            $lastRow  = 1; 
            $jobIdInt = 1;
        }
        ;

        $data = array(
            'jobint' => $jobIdInt,
            'next_job_id' => $next_job_id,
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
                'rev_force' => $_POST['rev_force_val'],
                'rev_speed' => $_POST['rev_speed_val'],
                'rev_direction' => $_POST['direction_val'],
                'job_ok' => $_POST['job_ok_val'],
                'stop_job_ok' => $_POST['stop_job_ok_val']
            );

            $resultName  = $this->MiscellaneousModel->validate($jobdata['job_name'], 'name');
            $resultPower = $this->MiscellaneousModel->validate($jobdata['rev_force'], 'rev_force');
            $resultRpm   = $this->MiscellaneousModel->validate($jobdata['rev_speed'],'rev_speed');

            if($resultPower == false){
                $this->MiscellaneousModel->generateErrorResponse('Error', $text['unfasten_force']);
                exit();
            }

            if($resultName == false){
                $this->MiscellaneousModel->generateErrorResponse('Error', $text['error_job_name']);
                exit();
            }

            if ($resultName  == true  && $resultPower == true && $resultRpm == true) {

                $job_count = $this->jobModel->countjob();
                if($job_count >= 50) {
                    $this->MiscellaneousModel->generateErrorResponse('Error', $error_message['job_id']);
                    exit();
                }
    
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
                'rev_force' => $_POST['forcevalue'],
                'rev_speed' => $_POST['speedvalue'],
                'rev_direction' => $_POST['directionValue'],
                'job_ok' => $_POST['jobokValue'],
                'stop_job_ok' => $_POST['stopjobValue']

            );


            
            $resultName  = $this->MiscellaneousModel->validate($jobdata['job_name'], 'name');
            $resultPower = $this->MiscellaneousModel->validate($jobdata['rev_force'], 'rev_force');
            $resultspeed = $this->MiscellaneousModel->validate($jobdata['rev_speed'], 'rev_speed');

            if($resultPower == false){
                $this->MiscellaneousModel->generateErrorResponse('Error', $text['unfasten_force']);
                exit();
            }

            if($resultName == false){
                $this->MiscellaneousModel->generateErrorResponse('Error', $text['error_job_name']);
                exit();
            }

            if ($resultName  == true  && $resultPower == true && $resultspeed  == true) {
                
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
            $an2 = $this->jobModel->delete_input_by_job_id($jobid);
            $an3 = $this->jobModel->delete_output_by_job_id($jobid);

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

        $old_jobid   = $_POST['old_jobid'] ?? null;
        $old_jobname = $_POST['old_jobname'] ?? null;
        $new_jobid   = $_POST['new_jobid'] ?? null;
        $new_jobname = $_POST['new_jobname'] ?? null;

        if(!empty($old_jobid)){
            $job_count = $this->jobModel->countjob();
            if($job_count > 50) {
                $this->MiscellaneousModel->generateErrorResponse('Error', $error_message['job_id']);
                exit();
            }else{
           
                $old_res = $this->jobModel->search_jobinfo($old_jobid);

                $this->jobModel->del_job_type($new_jobid);
                $this->jobModel->del_seq_type($new_jobid);
                $this->jobModel->del_step_type($new_jobid);
         
                if(!empty($old_res)){

                    #取得 unscrew_power && 	unscrew_rpm && unscrew_direction
                    $jobdata = array(
                        'job_id'   => $_POST['new_jobid'],
                        'job_name' => $_POST['new_jobname'],
                        'rev_direction' => $old_res['rev_direction'],
                        'rev_speed' => $old_res['rev_speed'],  
                        'rev_force' => $old_res['rev_force'],  
                        'job_ok' =>$old_res['job_ok'],
                        'stop_job_ok' => $old_res['stop_job_ok']

                    );
                    $res = $this->jobModel->create_job($jobdata);
                    //用job_id 找出對應的seq && step
                    $select_seq  = $this->jobModel->search_seqinfo($old_jobid); 
                    $select_step = $this->jobModel->search_stepnfo($old_jobid); 

                    if(!empty($select_seq)){
                        $new_temp_seq = array();
                        foreach($select_seq as $key =>$val){
                 
                            $new_temp_seq[$key]['job_id'] = $new_jobid;
                            $new_temp_seq[$key]['seq_id'] = $val['seq_id'];
                            $new_temp_seq[$key]['seq_name'] = $val['seq_name'];
                            $new_temp_seq[$key]['seq_en'] = $val['seq_en'];
                            $new_temp_seq[$key]['tr'] = $val['tr'];
                            $new_temp_seq[$key]['ns'] = $val['ns']; 
                            $new_temp_seq[$key]['seq_ok'] = $val['seq_ok']; 
                            $new_temp_seq[$key]['stop_seq_ok'] = $val['stop_seq_ok']; 
                            $new_temp_seq[$key]['opt'] = $val['opt']; 
                            $new_temp_seq[$key]['k_value'] = $val['k_value']; 
                            $new_temp_seq[$key]['ofs'] = $val['ofs'];

                            
                        }

                        $insertedrecords = $this->jobModel->copy_sequence_by_job_id($new_temp_seq);                
                    }

                    if(!empty($select_step)){
                        $new_temp_step = array();
                        
                        foreach($select_step as $key_step =>$val_step){

                            $new_temp_step[$key_step]['job_id'] = $new_jobid;
                            $new_temp_step[$key_step]['seq_id'] = $val_step['seq_id'];
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
                            $new_temp_step[$key_step]['downshift_speed'] = $val_step['downshift_speed'];
                        }
                      
                        $res = $this->jobModel->copy_step_by_job_id($new_temp_step);     
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


    //查詢 job_id 還沒有 被使用的 取出 最小值
    public function get_min_job_id(){

        
    }

}

?>

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

        $isMobile = $this->isMobileCheck();
        $jobs = $this->jobModel->getJobs();
        $direction = $this->MiscellaneousModel->details('unscrew_direction');
        
        $lastRow = end($jobs);
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

        if(isset($_POST['jobidnew'])){
            $jobdata = array(
                'job_id' => $_POST['jobidnew'],
                'job_name' => $_POST['jobname_val'],
                'unscrew_power' => $_POST['unscre_power_val'],
                'unscrew_rpm' => $_POST['unscrew_rpm_val'],
                'unscrew_direction' => $_POST['direction_val'],
            );
            $mode = "create";

            #驗證

            $result  = $this->MiscellaneousModel->validateName($jobdata['job_name']);
            $result1 = $this->MiscellaneousModel->validateUnscrewPower($jobdata['unscrew_power']);
            $result2 = $this->MiscellaneousModel->validateUnscrewPower($jobdata['unscrew_rpm']);

            if ($result == true  && $result1 == true && $result2 == true) {

                $job_count = $this->jobModel->countjob();
                if($job_count >= 50) {
                    echo "The maximum number of steps has been reached, unable to continue copying jobs";
                    return;
                }
    
                $res = $this->jobModel->create_job($mode,$jobdata);
                if($res){
                    $res_msg = 'insert job:'. $jobdata['job_id'].'success';
                }else{
                    $res_msg = 'insert job:'. $jobdata['job_id'].'fail';
                }
                echo $res_msg;
            }
           
           
        }
    }


    public function update_job(){
        $jobdata  = array();
        if(isset($_POST['jobid'])){
            $jobdata = array(
                'job_id' => $_POST['jobid'],
                'job_name' => $_POST['jobname'],
                'unscrew_power' => $_POST['powervalue'],
                'unscrew_rpm' => $_POST['rpmvalue'],
                'unscrew_direction' => $_POST['directionValue'],
            );
            
            $result  = $this->MiscellaneousModel->validateName($jobdata['job_name']);
            $result1 = $this->MiscellaneousModel->validateUnscrewPower($jobdata['unscrew_power']);
            $result2 = $this->MiscellaneousModel->validateUnscrewPower($jobdata['unscrew_rpm']);

            if ($result == true  && $result1 == true && $result2 == true) {
                
                $res = $this->jobModel->update_job_by_id($jobdata);
                if($res){
                    $res_msg = 'update job:'. $jobdata['job_id'].'success';
                }else{
                    $res_msg = 'update job:'. $jobdata['job_id'].'fail';
                }

                echo $res_msg;  

            }
        } 
    
    }

    #delete 
    public function delete_jobid() {
 
        $jobid = $_POST['jobid'] ?? null;
        if(!empty($jobid)){
            $res = $this->jobModel->delete_job_by_id($jobid);
            if($res){
                $res_msg = 'delete job:'. $jobid.'success';
            }else{
                $res_msg = 'delete job:'. $jobid.'fail';
            }

            echo $res_msg;
        }
   
    }

    public function search_job($jobid){
        $jobid = $_POST['jobid'] ?? null;
        if(!empty($jobid)){
            $res  = $this->jobModel->search_jobinfo($jobid);
            print_r($res);
        }
    }

    #copy 
    public function copy_job(){
        /*
         old_jobid: old_jobid,
                old_jobname: old_jobname,
                new_jobid: new_jobid,
                new_jobname: new_jobname
        */
        $jobdata = array();
        $old_jobid = $_POST['old_jobid'] ?? null;
        if(!empty($old_jobid)){


            $job_count = $this->jobModel->countjob();
            if($job_count >= 50) {
                echo "The maximum number of steps has been reached, unable to continue copying jobs";
                return;
            }

            
            $old_res = $this->jobModel->search_jobinfo($old_jobid);
            if(!empty($old_res)){
                  #取得 unscrew_power && 	unscrew_rpm && unscrew_direction
                $jobdata = array(
                    'new_jobid'   => $_POST['new_jobid'],
                    'new_jobname' => $_POST['new_jobname'],
                    'unscrew_power' => $old_res['unscrew_power'],
                    'unscrew_rpm' => $old_res['unscrew_rpm'],  
                    'unscrew_direction' => $old_res['unscrew_direction'],  

                );
                #取得 unscrew_power && 	unscrew_rpm && unscrew_direction
                $mode = "copy";
                $res = $this->jobModel->create_job($mode,$jobdata);
                if($res){
                    $res_msg = 'copy:'. $_POST['new_jobid'].'success';
                }else{
                    $res_msg = 'copy:'. $_POST['new_jobid'].'fail';
                }
    
                echo $res_msg;
            }
        }
    }
}

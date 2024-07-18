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

    #copy 
    public function copy_job(){

        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }
        
        $jobdata = array();
        $old_jobid = $_POST['old_jobid'] ?? null;
        if(!empty($old_jobid)){
            $job_count = $this->jobModel->countjob();
            if($job_count >= 50) {
                //$res_msg = $error_message['job_id'];
                //$this->MiscellaneousModel->generateErrorResponse('Error', $res_msg );
            }
            else{  
                $old_res = $this->jobModel->search_jobinfo($old_jobid);
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
                    if($res){
                        $res_msg = $text['Copy']."  ".$text['job_id'].':'. $_POST['new_jobid']."  ".$text['success'];
                        $this->MiscellaneousModel->generateErrorResponse('Success', $res_msg );
                    }else{
                        //$res_msg = $text['Copy']."  ".$text['job_id'].':'. $_POST['new_jobid']."  ".$text['fail'];
                        //$this->MiscellaneousModel->generateErrorResponse('Error', $res_msg );
                    }
                    
                }

            }
        }

    }    
}

?>

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

    // 取得所有info
    public function index($job_id,$seq_id){


        if( isset($job_id) && !empty($job_id)  && isset($seq_id) && !empty($seq_id)){

        }else{
            $job_id = 1;
            $seq_id = 1;
        }

        $isMobile = $this->isMobileCheck();
        $step_info = $this->stepModel->getStep($job_id, $seq_id);
        $target_option = $this->MiscellaneousModel->details("target_option");

        $data = array(
            'isMobile' => $isMobile,
            'step_info' => $step_info,
            'target_option' => $target_option
        );

        
        $this->view('step/index',$data);
        
    }







    
}
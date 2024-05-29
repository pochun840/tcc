<?php

class Sequences extends Controller
{
    // 在建構子中將 Post 物件（Model）實例化
    public function __construct(){

        $this->sequenceModel = $this->model('Sequence');
        $this->MiscellaneousModel = $this->model('Miscellaneous');
    }

    // 取得所有Sequences
    public function index($job_id){
        
        if( isset($job_id) && !empty($job_id) ){

        }else{
            $job_id = 1;
        }

   
        $sequences  = $this->sequenceModel->getSequences_by_job_id($job_id);
        $total_step = $this->sequenceModel->getStep_by_job_id($job_id)[0]['total_step'] ?? 0;
        $unit_arr   = $this->MiscellaneousModel->details('torque_unit');

        if(empty($sequences)){
            $seq_id = 1;
        }else{
            $seq_id = count($sequences) + 1 ;
        }


        $isMobile = $this->isMobileCheck();
     
        $data =array();
        $data = array(
            'sequences' => $sequences,
            'job_id' => $job_id,
            'total_step' => $total_step,
            'unit_arr' => $unit_arr,
            'seq_id' => $seq_id,


        );
        

        $this->view('sequences/index', $data);
    }

    #create 
    public function create_seq(){

        if(isset($_POST['jobid'])){
            
            $k_value = empty($_POST['k_value']) ? 100.0 : $_POST['k_value'];
            $ok_time = empty($_POST['ok_time']) ? 9.9 : $_POST['ok_time'];
            $okall_alarm_time = empty($_POST['okall_alarm']) ? 1.0: $_POST['okall_alarm'];

            $jobdata = array(
                'job_id' => $_POST['jobid'],
                'sequence_id' => $_POST['seqid'],
                'sequence_name' => $_POST['seq_name'],
                'tightening_repeat' => $_POST['tighten_repeat'],
                'ng_stop' => 0,
                'sequence_enable' => 0,
                'screw_join' => $_POST['join_val'],
                'okall_stop' => $_POST['okall_stop_val'],
                'opt' => $_POST['opt_val'],
                'torque_unit' => $_POST['torque_unit_val'],
                'k_value' => $k_value,
                'ok_time' => $_POST['ok_time'],
                'okall_alarm_time' => $okall_alarm_time,
                'offset' => 0
            );
            $mode = "create";
            $res = $this->sequenceModel->create_seq($mode,$jobdata);
            if($res){
                $res_msg = 'insert seq:'. $jobdata['sequence_id'].'success';
            }else{
                $res_msg = 'insert seq:'. $jobdata['sequence_id'].'fail';
            }
            echo $res_msg;
        }

    }

   


    
}

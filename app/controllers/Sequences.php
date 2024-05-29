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
            'old_seqid' => '',


        );
        $this->view('sequences/index', $data);
    }

    #create 
    public function create_seq(){

        if(isset($_POST['jobid'])){
            
            #如果 POST 中沒有，則使用預設值
            $jobid = isset($_POST['jobid']) ? intval($_POST['jobid']) : 0;
            $seqid = isset($_POST['seqid']) ? intval($_POST['seqid']) : 0;

            $k_value = isset($_POST['k_value']) ? floatval($_POST['k_value']) : 100.0;
            $ok_time = isset($_POST['ok_time']) ? floatval($_POST['ok_time']) : 9.9;
            $okall_alarm_time = isset($_POST['okall_alarm']) ? floatval($_POST['okall_alarm']) : 1.0;
            $tighten_repeat = isset($_POST['tighten_repeat']) ? intval($_POST['tighten_repeat']) : 0;
            $join_val = isset($_POST['join_val']) ? intval($_POST['join_val']) : 0;
            $okall_stop_val = isset($_POST['okall_stop_val']) ? intval($_POST['okall_stop_val']) : 0;
            $opt_val = isset($_POST['opt_val']) ? intval($_POST['opt_val']) : 0;
            $torque_unit_val = isset($_POST['torque_unit_val']) ? intval($_POST['torque_unit_val']) : 0;
            $ng_stop = isset($_POST['ng_stop']) ? intval($_POST['ng_stop']) : 0;
            $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;      


            $jobdata = array(
                'job_id' => $jobid,
                'sequence_id' => $seqid,
                'sequence_name' => $_POST['seq_name'],
                'tightening_repeat' => $tighten_repeat,
                'ng_stop' => $ng_stop,
                'sequence_enable' => 0,
                'screw_join' => $join_val,
                'okall_stop' => $okall_stop_val,
                'opt' => $_POST['opt_val'],
                'torque_unit' => $torque_unit_val,
                'k_value' => $k_value,
                'ok_time' => $ok_time,
                'okall_alarm_time' => $okall_alarm_time,
                'offset' => $offset,
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

    public function delete_seq(){

        $jobid = $_POST['jobid'] ?? null;
        $seqid = $_POST['seqid'] ?? null;

        if(!empty($jobid)){

            $res = $this->sequenceModel->delete_seq_by_id($jobid,$seqid);
            if($res){
                $res_msg = 'delete seq:'. $seqid.'success';
            }else{
                $res_msg = 'delete seq:'. $seqid.'fail';
            }
            echo $res_msg;
        }
    }


    public function search_seqinfo(){

        $jobid = $_POST['jobid'] ?? null;
        $seqid = $_POST['seqid'] ?? null;

        if(!empty($jobid)){
            $res  = $this->sequenceModel->search_seqinfo($jobid,$seqid);
            print_r($res[0]);
        }

    }
    
}

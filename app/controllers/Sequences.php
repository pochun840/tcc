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
            'unit_arr' => $unit_arr,
            'seq_id' => $seq_id,
            'old_seqid' => '',


        );

        if($isMobile){
            $this->view('sequences/index_m', $data);
        }else{
            $this->view('sequences/index', $data);
        }
    }

    #create 
    public function create_seq(){


        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }



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
            
            
            if ($k_value < 1.5 || $k_value > 999.8){
                $k_value = 100.0;
            }

            if($ok_time < 0.0 || $ok_time > 9.9){
                $ok_time  = 9.9;
            }

            if($okall_alarm_time < 0.0 || $okall_alarm_time > 9.9){
                $okall_alarm_tim = 1.0;
            }

            if ($offset < -254 || $offset > 254) {
                $offset = 0; 
            }

            $offset = sprintf("%+03d", $offset);

            $jobdata = array(
                'job_id' => $jobid,
                'sequence_id' => $seqid,
                'sequence_name' => $_POST['seq_name'],
                'tightening_repeat' => $tighten_repeat,
                'ng_stop' => $ng_stop,
                'sequence_enable' => 1,
                'screw_join' => $join_val,
                'okall_stop' => $okall_stop_val,
                'opt' => $opt_val,
                'torque_unit' => $torque_unit_val,
                'k_value' => $k_value,
                'ok_time' => $ok_time,
                'okall_alarm_time' => $okall_alarm_time,
                'offset' => $offset,
            );
           
            $mode = "create";
            $res = $this->sequenceModel->create_seq($mode,$jobdata);
            $result = array();
            if($res){
                $res_type = 'Success';
                $res_msg  = $text['new_seq'].':'. $jobdata['sequence_id']."  ".$text['success'];
            }else{
                $res_type = 'Error';
                $res_msg  = $text['new_seq'].':'. $jobdata['sequence_id']."  ".$text['fail'];
            }
            
            $result = array(
                'res_type' => $res_type,
                'res_msg'  => $res_msg 
            );

            echo json_encode($result);

        }

    }

    public function delete_seq(){

        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }
        

        $jobid = $_POST['jobid'] ?? null;
        $seqid = $_POST['seqid'] ?? null;

        if(!empty($jobid)){
            $result = array();
            $res = $this->sequenceModel->delete_seq_by_id($jobid,$seqid);
            if($res){
                $res_type = 'Success';
                $res_msg  = $text['del_seq'].':'. $seqid."  ".$text['success'];
            }else{
                $res_type = 'Error';
                $res_msg  = $text['del_seq'].':'. $seqid."  ".$text['fail'];
            }

            $result = array(
                'res_type' => $res_type,
                'res_msg'  => $res_msg 
            );

            echo json_encode($result);

        }
    }

    #查詢seq data
    public function search_seqinfo(){

        $jobid = $_POST['jobid'] ?? null;
        $seqid = $_POST['seqid'] ?? null;

        if(!empty($jobid)){
            $res  = $this->sequenceModel->search_seqinfo($jobid,$seqid);
            print_r($res[0]);
        }

    }

    public function edit_seq(){


        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }

        if(isset($_POST['jobid'])){

            $jobid = isset($_POST['jobid']) ? intval($_POST['jobid']) : 0;
            $seqid = isset($_POST['seqid']) ? intval($_POST['seqid']) : 0;

            $k_value = isset($_POST['k_value']) ? floatval($_POST['k_value']) : 100.0;
            $ok_time = isset($_POST['ok_time']) ? floatval($_POST['ok_time']) : 9.9;
            $okall_alarm_time = isset($_POST['okall_alarm']) ? floatval($_POST['okall_alarm']) : 1.0;
            $tighten_repeat = isset($_POST['tightening_repeat']) ? intval($_POST['tightening_repeat']) : 0;
            $join_val = isset($_POST['join_val']) ? intval($_POST['join_val']) : 0;
            $okall_stop_val = isset($_POST['okall_stop_val']) ? intval($_POST['okall_stop_val']) : 0;
            $opt_val = isset($_POST['opt_val']) ? intval($_POST['opt_val']) : 0;
            $torque_unit_val = isset($_POST['torque_unit']) ? intval($_POST['torque_unit']) : 0;
            $ng_stop = isset($_POST['ng_stop']) ? intval($_POST['ng_stop']) : 0;
            $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;  

            if ($k_value < 1.5 || $k_value > 999.8){
                $k_value = 100.0;
            }

            if($ok_time < 0.0 || $ok_time > 9.9){
                $ok_time  = 9.9;
            }

            if($okall_alarm_time < 0.0 || $okall_alarm_time > 9.9){
                $okall_alarm_tim = 1.0;
            }

            if ($offset < -254 || $offset > 254) {
                $offset = 0; 
            }

            $offset = sprintf("%+03d", $offset);

            $seq_count = $this->sequenceModel->countseq($jobid);
            $seq_count = intval($seq_count);
           
            #檢查job 
            if($seq_count >= 50) {
                echo "The maximum number of steps has been reached, unable to continue copying seqs";
                return;
            }
           
            $jobdata = array(
                'job_id' => $jobid,
                'sequence_id' => $seqid,
                'sequence_name' => $_POST['seq_name'],
                'tightening_repeat' => $tighten_repeat,
                'ng_stop' => $ng_stop,
                //'sequence_enable' => 0,
                'screw_join' => $join_val,
                'okall_stop' => $okall_stop_val,
                'opt' => $opt_val,
                'torque_unit' => $torque_unit_val,
                'k_value' => $k_value,
                'ok_time' => $ok_time,
                'okall_alarm_time' => $okall_alarm_time,
                'offset' => $offset,
            );
           
            $res = $this->sequenceModel->update_seq_by_id($jobdata);
            $result = array();
            if($res){
                $res_type = 'Success';
                $res_msg  = $text['edit_seq'].':'. $seqid."  ".$text['success'];
            }else{
                $res_type = 'Error';
                $res_msg  = $text['edit_seq'].':'. $seqid."  ".$text['fail'];
            }

            $result = array(
                'res_type' => $res_type,
                'res_msg'  => $res_msg 
            );

            echo json_encode($result);

        }
    }


    public function check_seq_type(){

        $jobid = $_POST['jobid'] ?? null;
        $seqname = $_POST['seqname'] ?? null;
        $type_value = $_POST['type_value'] ?? 0;

        if(!empty($jobid)){
            $res = $this->sequenceModel->check_seq_type($jobid,$seqname,$type_value);
            if($res){ 
                //$res_msg = 'update seq:'. $seqid.'success';
            }else{
                //$res_msg = 'update seq:'. $seqid.'fail';
            }
            $res_msg ="";
            echo $res_msg;
        }

    }

    public function copy_seq(){

        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }

        $jobdata = array();
        $mode = "copy"; 

        $jobid = $_POST['jobid'] ?? null;
        $seqid = $_POST['oldseqid'] ?? null;
        $newseqid = $_POST['newseqid'] ?? null;
        $oldseqname = $_POST['oldseqname'] ?? null;
        $newseqname = $_POST['newseqname'] ?? null;

        if(!empty($jobid && $seqid && $oldseqname )){


            $seq_count = $this->sequenceModel->countseq($jobid);
            $seq_count = intval($seq_count);
           
            if($seq_count >= 50) {
                echo "The maximum number of steps has been reached, unable to continue copying seqs";
                return;
            }

            $old_res = $this->sequenceModel->search_old_data($jobid,$seqid,$oldseqname);

            if(!empty($old_res)){

                $jobdata = array(
                    'job_id'   => $jobid,
                    'sequence_id' => $newseqid,
                    'sequence_name' => $newseqname,
                    'tightening_repeat' => $old_res['tightening_repeat'],  
                    'ng_stop' => $old_res['ng_stop'],  
                    'sequence_enable' => $old_res['sequence_enable'], 
                    'screw_join' => $old_res['screw_join'], 
                    'okall_stop' => $old_res['okall_stop'], 
                    'opt' => $old_res['opt'], 
                    'torque_unit' => $old_res['torque_unit'], 
                    'k_value' => $old_res['k_value'], 
                    'ok_time' => $old_res['ok_time'], 
                    'okall_alarm_time' => $old_res['okall_alarm_time'], 
                    'offset' => $old_res['offset'], 

                );

                $res = $this->sequenceModel->create_seq($mode,$jobdata);
                $result = array();
                if($res){
                    $res_type = 'Success';
                    $res_msg  = $text['Copy_Sequence'].':'.$newseqid."  ".$text['success'];
                }else{
                    $res_type = 'Error';
                    $res_msg  = $text['Copy_Sequence'].':'.$newseqid."  ".$text['fail'];
                }

                $result = array(
                    'res_type' => $res_type,
                    'res_msg'  => $res_msg 
                );
    
                echo json_encode($result);

            }
        }

    }
    
    #seq 排序
    public function adjustment_order(){

        if(isset($_POST['jobid'])){
            $jobid = $_POST['jobid'];
            $rowInfoArray = $_POST['rowInfoArray'];
            $res = $this->sequenceModel->swapupdate($jobid,$rowInfoArray);

            if($res){
                $res_msg = 'success';
            }else{
                $res_msg = 'fail';
            }
            echo $res_msg;
        }
    }
}
?>

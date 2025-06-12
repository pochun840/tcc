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
            $next_seq_id = 1;
        }else{
            $seq_id = count($sequences) + 1 ;
            $next_seq_id = $seq_id;
        }

        

        $isMobile = $this->isMobileCheck();
     
        $data =array();
        $data = array(
            'sequences' => $sequences,
            'job_id' => $job_id,
            'unit_arr' => $unit_arr,
            'seq_id' => $seq_id,
            'old_seqid' => '',
            'next_seq_id' => $next_seq_id

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

            $seq_k_val = isset($_POST['seq_k_val']) ? intval($_POST['seq_k_val']) : 100;
            $seq_tr = isset($_POST['seq_tr']) ? intval($_POST['seq_tr']) : 1;
            $seq_ok = isset($_POST['seq_ok']) ? intval($_POST['seq_ok']) : 0;
            $seq_ok_stop = isset($_POST['seq_ok_stop']) ? intval($_POST['seq_ok_stop']) : 0;
            $seq_opt = isset($_POST['seq_opt']) ? intval($_POST['seq_opt']) : 0;
            $seq_ns = isset($_POST['seq_ns']) ? intval($_POST['seq_ns']) : 0;
            $seq_ofs = isset($_POST['seq_ofs']) ? intval($_POST['seq_ofs']) : 0;  

            $seq_work_limit = isset($_POST['seq_work_limit']) ? intval($_POST['seq_work_limit']) : null;
            $seq_dt = isset($_POST['seq_dt']) ? intval($_POST['seq_dt']) : 0;
            $seq_tt = isset($_POST['seq_tt']) ? intval($_POST['seq_tt']) : 0;


            $seq_name = $_POST['seq_name'];
        
            #驗證seq_name 
            if(!$this->MiscellaneousModel->seq_validate($seq_name, 'name')) {
                $this->MiscellaneousModel->generateErrorResponse('Error', $text['error_seq_name']);
                exit();
            }


            #驗證顆數
            if(!$this->MiscellaneousModel->seq_validate($seq_tr, 'seq_tr')) {
                $this->MiscellaneousModel->generateErrorResponse('Error', $error_message['tr']);
                exit();
            }


            #驗證k_value
            if(!$this->MiscellaneousModel->seq_validate($seq_k_val, 'kValue')) {
                $this->MiscellaneousModel->generateErrorResponse('Error', $error_message['ok_time']);
                exit();
            }

            #驗證offset
            if(!$this->MiscellaneousModel->seq_validate($seq_ofs, 'seq_ofs')) {
                $this->MiscellaneousModel->generateErrorResponse('Error', $error_message['joint_offset_val']);
                exit();
            }
            
            $jobdata = array(
                'job_id' => $jobid,
                'seq_id' => $seqid,
                'seq_name' => $seq_name,
                'seq_en' => 1,
                'seq_tr' => $seq_tr,
                'seq_ns' => $seq_ns,
                'seq_ok'  => $seq_ok,
                'seq_ok_stop' => $seq_ok_stop, 
                'seq_opt' => $seq_opt,
                'seq_k_val' => $seq_k_val,
                'seq_ofs' => $seq_ofs,
                'seq_work_limit' => $seq_work_limit,
                'seq_dt' => $seq_dt,
                'seq_tt' => $seq_tt,

            );


            $mode = "create";
            $res = $this->sequenceModel->create_seq($mode,$jobdata);
            $result = array();
            if($res){
                $res_type = 'Success';
                $res_msg  = $text['new_seq'].':'. $jobdata['seq_id']."  ".$text['success'];
            }else{
                $res_type = 'Error';
                $res_msg  = $text['new_seq'].':'. $jobdata['seq_id']."  ".$text['fail'];
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
            $res11 = $this->sequenceModel->delete_step_by_job_id($jobid,$seqid);
            if($res){
                $res_type = 'Success';
                $res_msg  = $text['del_seq'].':'. $seqid."  ".$text['success'];
                $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg );
            }else{
                $res_type = 'Error';
                $res_msg  = $text['del_seq'].':'. $seqid."  ".$text['fail'];
                $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg );
            }
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
            $seq_name = $_POST['seq_name'];
            $seq_tr = isset($_POST['seq_tr']) ? intval($_POST['seq_tr']) : 1;
            $seq_ns = isset($_POST['seq_ns']) ? intval($_POST['seq_ns']) : 0;
            $seq_ok = isset($_POST['seq_ok']) ? intval($_POST['seq_ok']) : 0;
            $seq_ok_stop = isset($_POST['seq_ok_stop']) ? intval($_POST['seq_ok_stop']) : 0;
            $seq_opt = isset($_POST['seq_opt']) ? intval($_POST['seq_opt']) : 0;
            $seq_k_val = isset($_POST['seq_k_val']) ? floatval($_POST['seq_k_val']) : 100;
            $seq_ofs = isset($_POST['seq_ofs']) ? intval($_POST['seq_ofs']) : 0;  

            $seq_work_limit = isset($_POST['seq_work_limit']) ? intval($_POST['seq_work_limit']) : null;
            $seq_dt = isset($_POST['seq_dt']) ? intval($_POST['seq_dt']) : 0;
            $seq_tt = isset($_POST['seq_tt']) ? intval($_POST['seq_tt']) : 0;


            #驗證seq_name 
            if(!$this->MiscellaneousModel->seq_validate($seq_name, 'name')) {
                $this->MiscellaneousModel->generateErrorResponse('Error', $text['error_seq_name']);
                exit();
            }

            #驗證顆數
            if(!$this->MiscellaneousModel->seq_validate($seq_tr, 'seq_tr')) {
                $this->MiscellaneousModel->generateErrorResponse('Error', $error_message['tr']);
                exit();
            }
    
            #驗證k_value
            if(!$this->MiscellaneousModel->seq_validate($seq_k_val, 'kValue')) {
                $this->MiscellaneousModel->generateErrorResponse('Error', $error_message['ok_time']);
                exit();
            }

            #驗證offset
            if(!$this->MiscellaneousModel->seq_validate($seq_ofs, 'seq_ofs')) {
                $this->MiscellaneousModel->generateErrorResponse('Error', $error_message['joint_offset_val']);
                exit();
            }

            //$seq_ofs = sprintf("%+03d", $seq_ofs);

            $seq_count = $this->sequenceModel->countseq($jobid);
            $seq_count = intval($seq_count);
           
           
            $jobdata = array(
                'job_id' => $jobid,
                'seq_id' => $seqid,
                'seq_name' =>$seq_name,
                'seq_en' => 1,
                'seq_tr' => $seq_tr,
                'seq_ns' => $seq_ns,
                'seq_ok'  => $seq_ok,
                'seq_ok_stop' =>$seq_ok_stop,
                'seq_opt' => $seq_opt,
                'seq_k_val' => $seq_k_val,
                'seq_ofs' => $seq_ofs,
                'seq_work_limit' => $seq_work_limit,
                'seq_dt' => $seq_dt,
                'seq_tt' => $seq_tt,

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
        $seqid = $_POST['newseqid'] ?? null;
        
        if(!empty($seqid)){
            $res  = $this->sequenceModel->seq_id_repeat($jobid,$seqid);
            if($res == "True"){

            }
            echo  $res;
        }
      

    }

    //check_seq_enable
    public function check_seq_enable(){

        $input_check = true;

        if(!empty($_POST)){
            $seq_data = array();
            $seq_data = $_POST;
        }else{
            $input_check = false; 
        }
    
        if($input_check){
            $this->sequenceModel->update_seq_type($seq_data) ;
        }
    }

    public function copy_seq_data(){

        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }

        $jobid = $_POST['jobid'] ?? null;
        $seqid = $_POST['seqid'] ?? null;
        $newseqid = $_POST['newseqid'] ?? null;
        $oldseqname = $_POST['oldseqname'] ?? null;
        $newseqname = $_POST['newseqname'] ?? null;


        //檢查 $seqid 是否有存在 
        $res_check = $this->sequenceModel->search_seqinfo($jobid,$seqid);
        if(!empty($res['job_id'])){
            $this->MiscellaneousModel->generateErrorResponse('Error', $error_message['job_id']);
            exit();
        }
        //用jobid 及 seqid 去找出 對應的資料
        $old_res = $this->sequenceModel->search_seqinfo($jobid,$seqid);


        
        $this->sequenceModel->del_seq_type($jobid,$newseqid);
        $this->sequenceModel->del_step_type($jobid,$newseqid);

        if(!empty($old_res)){
            $new_temp_seq = array();
            foreach($old_res as $kk =>$vv){
                $new_temp_seq[$kk]['job_id'] = $vv['job_id'];
                $new_temp_seq[$kk]['seq_id'] = $newseqid;
                $new_temp_seq[$kk]['seq_name'] = $newseqname;
                $new_temp_seq[$kk]['seq_en'] = $vv['seq_en'];
                $new_temp_seq[$kk]['seq_tr'] = $vv['seq_tr'];
                $new_temp_seq[$kk]['seq_ns'] = $vv['seq_ns']; 
                $new_temp_seq[$kk]['seq_ok'] = $vv['seq_ok']; 
                $new_temp_seq[$kk]['seq_ok_stop'] = $vv['seq_ok_stop']; 
                $new_temp_seq[$kk]['seq_opt'] = $vv['seq_opt']; 
                $new_temp_seq[$kk]['seq_k_val'] = $vv['seq_k_val']; 
                $new_temp_seq[$kk]['seq_ofs'] = $vv['seq_ofs'];
                $new_temp_seq[$kk]['seq_work_limit'] = $vv['seq_work_limit'];
                $new_temp_seq[$kk]['seq_dt'] = $vv['seq_dt'];
                $new_temp_seq[$kk]['seq_tt'] = $vv['seq_tt'];

            }  

            $rows = $this->sequenceModel->copy_seq_by_seq_id($new_temp_seq);
     
        }


        $select_step = $this->sequenceModel->search_stepinfo($jobid,$seqid);


        if(!empty($select_step)){
            $new_temp_step = array();
            foreach($select_step as $key_step =>$val_step){
                $new_temp_step[$key_step]['job_id'] = $val_step['job_id'];
                $new_temp_step[$key_step]['seq_id'] = $newseqid;
                $new_temp_step[$key_step]['step_id'] = $val_step['step_id'];
                $new_temp_step[$key_step]['target_opt'] = $val_step['target_opt']; 
                $new_temp_step[$key_step]['target_tor'] = $val_step['target_tor'];
                $new_temp_step[$key_step]['target_ang'] = $val_step['target_ang'];
                $new_temp_step[$key_step]['target_delay'] = $val_step['target_delay'];
                $new_temp_step[$key_step]['tor_hi'] = $val_step['tor_hi'];
                $new_temp_step[$key_step]['tor_lo'] = $val_step['tor_lo'];
                $new_temp_step[$key_step]['ang_hi'] = $val_step['ang_hi'];
                $new_temp_step[$key_step]['ang_lo'] = $val_step['ang_lo'];
                $new_temp_step[$key_step]['rpm'] = $val_step['rpm'];
                $new_temp_step[$key_step]['direction'] = $val_step['direction'];
                $new_temp_step[$key_step]['th_mode'] = $val_step['th_mode'];
                $new_temp_step[$key_step]['ds_tor'] = $val_step['ds_tor'];
                $new_temp_step[$key_step]['ds_speed'] = $val_step['ds_speed'];
                $new_temp_step[$key_step]['th_tor'] = $val_step['th_tor'];
                $new_temp_step[$key_step]['record_ang'] = $val_step['record_ang'];
                $new_temp_step[$key_step]['tor_unit'] = $val_step['tor_unit'];

            }

            $rows_temp = $this->sequenceModel->copy_step_by_seq_id($new_temp_step);

        }

        if($rows){
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
   
    #seq 排序
    public function adjustment_order(){
    
        if(isset($_POST['jobid'])){
            $jobid = $_POST['jobid'];
            $rowInfoArray = $_POST['rowInfoArray'];

            if(!empty($rowInfoArray)){

                $new_info = array();
                $index = 1;
                foreach ($rowInfoArray as $v_s) {
                    $new_info[$index] = $v_s;
                    $index++;
                }

                $res = $this->sequenceModel->swapupdate($jobid,$rowInfoArray,$new_info);
                if($res){
                    $this->sequenceModel->finalizeStepSeqId($jobid);
                    $res_msg = 'success';

                }else{
                    $res_msg = 'fail';
                }
                echo $res_msg;
                
            }
            
        }
    }
}
?>

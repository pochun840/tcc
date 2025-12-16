 #create 
    public function create_job(){

        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }

        if(isset($_POST['jobidnew'])){

            $rev_cnt_mode = isset($_POST['rev_cnt_mode']) ? intval($_POST['rev_cnt_mode']) : null;
            $rev_th_tor = isset($_POST['rev_th_tor']) ? floatval($_POST['rev_th_tor']) : 0.0;
            $rev_th_ang = isset($_POST['rev_th_ang']) ? intval($_POST['rev_th_ang']) : 0;
            $rev_tor_unit = isset($_POST['rev_tor_unit'])? intval($_POST['rev_tor_unit']) : 1;

            $jobdata = array(
                'job_id' => $_POST['jobidnew'],
                'job_name' => $_POST['jobname_val'],
                'rev_force' => $_POST['rev_force_val'],
                'rev_speed' => $_POST['rev_speed_val'],
                'rev_direction' => $_POST['direction_val'],
                'job_ok' => $_POST['job_ok_val'],
                'job_ok_stop' => $_POST['job_ok_stop_val'],
                'rev_cnt_mode' => $rev_cnt_mode,
                'rev_th_tor' => $rev_th_tor,
                'rev_th_ang' => $rev_th_ang,
                'rev_tor_unit' => $rev_tor_unit
            );


            if ($jobdata) {

                $job_count = $this->jobModel->countjob();
                if($job_count > 50) {
                    $this->MiscellaneousModel->generateErrorResponse('Error', $error_message['job_id']);
                    exit();
                }
    
                $res = $this->jobModel->create_job($jobdata);

                // 預設建立SEQ  

                
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


     function create_job()  建立JOB 時候 也要建立 預設的 SEQ
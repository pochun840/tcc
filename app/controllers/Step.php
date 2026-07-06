<?php
class Step extends Controller
{
   
    // 在建構子中將 Post 物件（Model）實例化
    private $MiscellaneousModel;
    private $sequenceModel;
    private $stepModel;
    private $ToolModel;
    private $SettingModel;
    public function __construct()
    {
        $this->ToolModel = $this->model('Tool');
        $this->MiscellaneousModel = $this->model('Miscellaneous');
        $this->stepModel = $this->model('Steptcc');
        $this->sequenceModel = $this->model('Sequence');
        $this->SettingModel = $this->model('Setting');

        
    }


    /**
     * 取得目前 Controller Setting 使用的扭力單位。
     *
     * 優先使用 torque_unit；
     * 舊版資料才 fallback 到 device_torque_unit。
     *
     * 0 = kgf.m
     * 1 = N.m
     * 2 = kgf.cm
     * 3 = Lbf.in
     * 4 = cN.m
     */
    private function getCurrentTorqueUnit(array $deviceInfo): int
    {
        foreach (['torque_unit', 'device_torque_unit'] as $field) {
            if (
                array_key_exists($field, $deviceInfo)
                && $deviceInfo[$field] !== ''
                && $deviceInfo[$field] !== null
            ) {
                $unit = (int)$deviceInfo[$field];

                if ($unit >= 0 && $unit <= 4) {
                    return $unit;
                }
            }
        }

        return 1;
    }


    public function index($job_id, $seq_id, $tools = null) {

        if (empty($job_id) || empty($seq_id)) {
            $job_id = 1;
            $seq_id = 1;
        }

        $isMobile = $this->isMobileCheck();
        $step = $this->stepModel->getStep($job_id, $seq_id);
        $target_option = $this->MiscellaneousModel->details("target_option");
        $torque_unit   = $this->MiscellaneousModel->details("torque_unit");
        $target_option_change = $this->MiscellaneousModel->details("target_option_change");
        $target_option_only_tor = $this->MiscellaneousModel->details("target_option_only_tor");

        $formatted_array = [];
        foreach ($target_option_only_tor as $index => $item) {
            $formatted_array[] = ['value' => $index, 'text' => $item];
        }
        $json = json_encode($formatted_array);

        $direction = $this->MiscellaneousModel->details('rev_direction');
        $unit_arr  = $this->MiscellaneousModel->details('torque_unit');
        $seqinfo   = $this->sequenceModel->search_seqinfo($job_id, $seq_id);

        $tools_from_outside = is_array($tools) && isset($tools['__from_outside']) && $tools['__from_outside'] === true;
        if (!$tools_from_outside) {
            $tools = $this->ToolModel->GetToolInfo();
        }

        $step_count = intval($this->stepModel->countstep($job_id, $seq_id));
        $step_id = ($step_count === 0) ? 1 : $step_count;

        $check = $this->stepModel->check_step_target($job_id, $seq_id, $step_id);
        $res_device = $this->SettingModel->GetControllerInfo();

        if (!empty($res_device)) {
            $step_torque_unit = $this->getCurrentTorqueUnit((array)$res_device);
            $unit_name = $torque_unit[$step_torque_unit] ?? '';
        }

        $stepid_new = empty($step) ? 1 : count($step) + 1;

        $count_records = !empty($check[0]['count_records']) ? (int)$check[0]['count_records'] : '';
        $check_step_torque = !empty($check[0]['count_records']) ? 1 : '';

        $tools_id = !empty($tools['SID5']) ? (int)$tools['SID5'] : null;

        $seq_data = $this->sequenceModel->search_seqinfo($job_id, $seq_id);
        $seq_opt = !empty($seq_data) ? (int)$seq_data[0]['seq_opt'] : null;

        // ✅ 只有在不是外部傳入的情況下才進行 torque 換算處理
        if (!$tools_from_outside && !empty($tools)) {
            $tool_min_torque = floatval($tools['tool_mintorque']);
            $tool_max_torque = floatval($tools['tool_maxtorque']);

            if (!empty($res_device)) {
                $step_torque_unit = $this->getCurrentTorqueUnit((array)$res_device);
                $unit_name = $this->MiscellaneousModel->get_unit_name_by_index($step_torque_unit);

                $tools['tool_maxtorque_diff'] = round($tool_max_torque * 1.1, 3);
                $tools['tool_mintorque_diff'] = floor($tools['tool_maxtorque_diff'] * 10) / 10;

                $low_torque_arr = $this->MiscellaneousModel->convert_all_torque_units($tool_min_torque, 1);
                $high_torque_arr = $this->MiscellaneousModel->convert_all_torque_units($tool_max_torque, 1);

                if (isset($low_torque_arr[$unit_name])) {
                    $tools['tool_low_torque'] = $low_torque_arr[$unit_name];
                }

                if (isset($high_torque_arr[$unit_name])) {
                    $tools['tool_high_torque'] = $high_torque_arr[$unit_name];
                }

                $tmp_torque_1 = $this->MiscellaneousModel->convert_all_torque_units($tools['tool_mintorque'], 1);
                $tmp_torque_2 = $this->MiscellaneousModel->convert_all_torque_units($tools['tool_maxtorque'], 1);

                if (isset($tmp_torque_1[$unit_name])) {
                    $tools['tool_mintorque'] = $tmp_torque_1[$unit_name];
                }

                if (isset($tmp_torque_2[$unit_name])) {
                    $tools['tool_maxtorque'] = $tmp_torque_2[$unit_name];
                }

                $tmp_torque_unified = $this->MiscellaneousModel->convert_all_torque_units($tool_max_torque, 1);
                if (isset($tmp_torque_unified[$unit_name])) {
                    $tools['tool_maxtorque_unified'] = $tmp_torque_unified[$unit_name];
                }

            }
        }

        // HQ / High Torque 規格上限固定為 55 N.m，依目前控制器扭力單位換算給前端驗證使用。
        $hq_torque_limit = 55;
        if (!empty($res_device)) {
            $step_torque_unit = $this->getCurrentTorqueUnit((array)$res_device);
            $unit_name_for_hq = $this->MiscellaneousModel->get_unit_name_by_index($step_torque_unit);
            $hq_torque_arr = $this->MiscellaneousModel->convert_all_torque_units(55, 1);
            if (is_array($hq_torque_arr) && isset($hq_torque_arr[$unit_name_for_hq])) {
                $hq_torque_limit = $hq_torque_arr[$unit_name_for_hq];
            }
        }

        $check_torque = $this->stepModel->chek_step_target_torque($job_id, $seq_id);
        $counts_torque = !empty($check_torque) ? (int)$check_torque[0]['counts'] : 0;

        $data = [
            'isMobile' => $isMobile,
            'step' => $step,
            'target_option' => $target_option,
            'target_option_change' => $target_option_change,
            'target_option_only_tor_json' => $json,
            'direction' => $direction,
            'job_id' => $job_id,
            'seq_id' => $seq_id,
            'step_id' => $stepid_new,
            'unit_arr' => $unit_arr,
            'step_torque_unit' => $step_torque_unit ?? '',
            'check' => $check,
            'unit_name' => $unit_name ?? '',
            'check_step_torque' => $check_step_torque,
            'step_count' => $step_count,
            'tools' => $tools,
            'count_records' => $count_records,
            'tools_id' => $tools_id,
            'seq_opt' => $seq_opt,
            'counts_torque' => $counts_torque,
            'target_tor_value' => $tools['tool_mintorque'] ?? $hq_torque_limit,
            'hq_torque_limit' => $hq_torque_limit
        ];

        //扭力單位換算過後的 final扭力值
        //$data['tools']['tool_maxtorque_unified']
        

      

        if ($isMobile) {
            $this->view('step/index_m', $data);
        } else {
            $this->view('step/index', $data);
        }
    }


    public function create_step(){

        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }

        // 前端表單顯示/輸入的扭力值，都是目前控制器扭力單位。
        // 因此儲存時 tor_unit 應以 device_torque_unit 為準，避免被前端固定傳 1(N.m) 導致再次轉換放大/縮小。
        $device_info_for_save = (array)(
            $this->SettingModel->GetControllerInfo()
            ?? []
        );
        $current_torque_unit_for_save =
            $this->getCurrentTorqueUnit(
                $device_info_for_save
            );

        if(isset($_POST['jobid'])){

            $jobid = isset($_POST['jobid']) ? intval($_POST['jobid']) : 0;
            $seqid = isset($_POST['seqid']) ? intval($_POST['seqid']) : 0;
            $stepid = isset($_POST['stepid']) ? intval($_POST['stepid']) : 1; 
            $target_opt = isset($_POST['target_opt'])? intval($_POST['target_opt']) : 0; 
            $target_tor = isset($_POST['target_tor'])? floatval($_POST['target_tor']) : 0; 
            $target_ang = isset($_POST['target_ang'])? intval($_POST['target_ang']) : 0; 
            $target_delay = isset($_POST['target_delay'])? floatval($_POST['target_delay']) : 0; 
            $tor_hi = isset($_POST['tor_hi'])? floatval($_POST['tor_hi']) : 0; 
            $tor_lo = isset($_POST['tor_lo'])? floatval($_POST['tor_lo']) : 0; 
            $ang_hi  = isset($_POST['ang_hi'])? intval($_POST['ang_hi']) : 30600; 
            $ang_lo  = isset($_POST['ang_lo'])? intval($_POST['ang_lo']) : 0; 
            $rpm       = isset($_POST['rpm'])? intval($_POST['rpm']) : 100;
            $direction = isset($_POST['direction'])? intval($_POST['direction']) : 0;
            $th_mode = isset($_POST['th_mode'])? intval($_POST['th_mode']) : 0;
            $ds_tor = isset($_POST['ds_tor'])? floatval($_POST['ds_tor']) : 0.0; 
            $ds_speed = isset($_POST['ds_speed'])? intval($_POST['ds_speed']) : 100;
            $th_tor = isset($_POST['th_tor'])? floatval($_POST['th_tor']) : 0.0;
            $record_ang = isset($_POST['record_ang'])? intval($_POST['record_ang']) : 0;
            $tor_unit = $current_torque_unit_for_save;
            $pnf_set = isset($_POST['pnf_set'])? intval($_POST['pnf_set']) : 0;

            // Downshift rule: only STEP 1 can enable Downshift.
            if ($stepid !== 1) {
                $th_mode = 0;
                $th_tor = 0.0;
                $ds_tor = 0.0;
                $ds_speed = 100;
            }

            if($target_opt  == 1){

                if($tor_lo  > $tor_hi){
                    $res_type = 'Error';
                    $res_msg  =  $error_message['torque_error'];
                    
                    $result = array(
                        'res_type' => $res_type,
                        'res_msg'  => $res_msg 
                    );
                    
                    echo json_encode($result);
                    exit();
                }   

                if($ang_lo  > $ang_hi){
                    $res_type = 'Error';
                    $res_msg  = $error_message['angle_error'];

                    $result = array(
                        'res_type' => $res_type,
                        'res_msg'  => $res_msg 
                    );
                    
                    echo json_encode($result);
                    exit();
                }
            }

            if ($target_opt == 0) {
                $target_ang = 0;
                $target_delay = 0;
            } elseif ($target_opt == 1) {
                $target_tor = 0;
                $target_delay = 0;
                $th_mode = 0;
                if ($target_ang <= 0) {
                    $target_ang = 1800;
                }
            } elseif ($target_opt == 2) {
                $target_tor = 0;
                $target_ang = 0;
                $th_mode = 0;
                if ($target_delay <= 0) {
                    $target_delay = 1.0;
                }
            }

            $record_ang = 0;

            $jobdata = array(
                'job_id'           => $jobid,
                'seq_id'           => $seqid,
                'step_id'          => $stepid,
                'target_opt'       => $target_opt,
                'target_tor'       => $target_tor,
                'target_ang'       => $target_ang,
                'target_delay'     => $target_delay,
                'tor_hi'           => $tor_hi,
                'tor_lo'           => $tor_lo,
                'ang_hi'           => $ang_hi,
                'ang_lo'           => $ang_lo,
                'rpm'              => $rpm,
                'direction'        => $direction,
                'th_mode'          => $th_mode,
                'th_tor'           => $th_tor,
                'ds_tor'           => $ds_tor,
                'ds_speed'         => $ds_speed,
                'record_ang'       => $record_ang,
                'tor_unit'         => $tor_unit,
                'pnf_set'          => $pnf_set
                
            );
   
            $mode = "create"; 
            $res = $this->stepModel->create_step($mode,$jobdata);
            $result = array();
            if($res){
                $res_type = 'Success';
                $res_msg = $text['new_step'].':'.$stepid."  ".$text['success'];
                $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);
            }else{
                $res_type = 'Error';
                $res_msg = $text['new_step'].':'.$stepid."  ".$text['fail'];
                $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);
            }
        }

    }

    public function edit_step(){

        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }

        #取得扭力單位
        $step_torque_unit = 1;
        $res_device = $this->SettingModel->GetControllerInfo();
        if(!empty($res_device)){
            $step_torque_unit = $this->getCurrentTorqueUnit((array)$res_device);
            if (isset($torque_unit) && is_array($torque_unit) && isset($torque_unit[$step_torque_unit])) {
                $unit_name = $torque_unit[$step_torque_unit];
            } else {
                
                $unit_name = '';
            }
        }
        
        //取得 起子的 最小扭力 
        $Tool_Info = $this->ToolModel->GetToolInfo();
        $min_tor = $Tool_Info['tool_mintorque'];

        
        if(isset($_POST['jobid'])){

            $jobid = isset($_POST['jobid']) ? intval($_POST['jobid']) : 0;
            $seqid = isset($_POST['seqid']) ? intval($_POST['seqid']) : 0;
            $stepid = isset($_POST['stepid']) ? intval($_POST['stepid']) : 1; 
            $target_opt = isset($_POST['target_opt'])? intval($_POST['target_opt']) : 0; 
            $target_tor = isset($_POST['target_tor'])? floatval($_POST['target_tor']) : 0; 
            $target_ang = isset($_POST['target_ang'])? intval($_POST['target_ang']) : 0; 
            $target_delay = isset($_POST['target_delay'])? floatval($_POST['target_delay']) : 0; 
            $tor_hi = isset($_POST['tor_hi'])? floatval($_POST['tor_hi']) : 0; 
            $tor_lo = isset($_POST['tor_lo'])? floatval($_POST['tor_lo']) : 0; 
            $ang_hi  = isset($_POST['ang_hi'])? intval($_POST['ang_hi']) : 0; 
            $ang_lo  = isset($_POST['ang_lo'])? intval($_POST['ang_lo']) : 0; 
            $rpm       = isset($_POST['rpm'])? intval($_POST['rpm']) : 10;
            $direction = isset($_POST['direction'])? intval($_POST['direction']) : 0;
            $th_mode = isset($_POST['th_mode'])? intval($_POST['th_mode']) : 0;
            $ds_tor = isset($_POST['ds_tor'])? floatval($_POST['ds_tor']) : 0.3; 
            $ds_speed = isset($_POST['ds_speed'])? intval($_POST['ds_speed']) : 100;
            $th_tor = isset($_POST['th_tor'])? floatval($_POST['th_tor']) : 0;
            $record_ang = isset($_POST['record_ang'])? intval($_POST['record_ang']) : 0;
            // Edit 表單送出的扭力數值已經是目前控制器單位，後端以 device_torque_unit 為準，避免前端錯傳 1(N.m)。
            $tor_unit = $step_torque_unit;
            $pnf_set = isset($_POST['pnf_set'])? intval($_POST['pnf_set']) : 0;

            // Downshift rule: only STEP 1 can enable Downshift.
            if ($stepid !== 1) {
                $th_mode = 0;
                $th_tor = 0.0;
                $ds_tor = 0.0;
                $ds_speed = 100;
            }


            if($target_opt  == 0 && $th_mode == 1 ){

                if ($ds_tor > $target_tor) {
                    $res_type = 'Error';
                    $res_msg  =  $error_message['downshift_torque_error'];
                    
                    $result = array(
                        'res_type' => $res_type,
                        'res_msg'  => $res_msg 
                    );

                    echo json_encode($result);
                    exit();
                }

                if ($th_tor > $target_tor) {
                    $res_type = 'Error';
                    $res_msg  =  $error_message['threshold_torque_error'];
                    
                    $result = array(
                        'res_type' => $res_type,
                        'res_msg'  => $res_msg 
                    );

                    echo json_encode($result);
                    exit();
                }

            }

            if($target_opt  == 1){

                if($tor_lo  > $tor_hi){
                    $res_type = 'Error';
                    $res_msg  =  $error_message['torque_error'];
                    $result = array(
                        'res_type' => $res_type,
                        'res_msg'  => $res_msg 
                    );
                    echo json_encode($result);
                    exit();
                }   

                if($ang_lo  > $ang_hi){
                    $res_type = 'Error';
                    $res_msg  = $error_message['angle_error'];
                    $result = array(
                        'res_type' => $res_type,
                        'res_msg'  => $res_msg 
                    );
                    echo json_encode($result);
                    exit();
                }
            }

            if ($target_opt == 0) {
                $target_ang = 0;
                $target_delay = 0;
            } elseif ($target_opt == 1) {
                $target_tor = 0;
                $target_delay = 0;
                $th_mode = 0;
                if ($target_ang <= 0) {
                    $target_ang = 1800;
                }
            } elseif ($target_opt == 2) {
                $target_tor = 0;
                $target_ang = 0;
                $th_mode = 0;
                if ($target_delay <= 0) {
                    $target_delay = 1.0;
                }
            }

            $record_ang = 0;
            $jobdata = array(
                'job_id'           => $jobid,
                'seq_id'           => $seqid,
                'step_id'          => $stepid,
                'target_opt'       => $target_opt,
                'target_tor'       => $target_tor,
                'target_ang'       => $target_ang,
                'target_delay'     => $target_delay,
                'tor_hi'           => $tor_hi,
                'tor_lo'           => $tor_lo,
                'ang_hi'           => $ang_hi,
                'ang_lo'           => $ang_lo,
                'rpm'              => $rpm,
                'direction'        => $direction,
                'th_mode'          => $th_mode,
                'th_tor'           => $th_tor,
                'ds_tor'           => $ds_tor,
                'ds_speed'         => $ds_speed,
                'record_ang'      => $record_ang,
                'tor_unit'         => $tor_unit,
                'pnf_set'          => $pnf_set
                
            );


            $res = $this->stepModel->update_step_by_id($jobdata);
            $result = array();
            if($res){
                $res_type = 'Success';
                $res_msg  = $text['edit_step'].':'. $stepid."  ".$text['success'];
            }else{
                $res_type = 'Error';
                $res_msg  = $text['edit_step'].':'. $stepid."  ".$text['fail'];
            }
            $result = array(
                'res_type' => $res_type,
                'res_msg'  => $res_msg 
            );

            echo json_encode($result);
        }
    }


    public function delete_step(){

        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }
        

        if(isset($_POST['stepid'])){
            
            $jobid = isset($_POST['jobid']) ? intval($_POST['jobid']) : '';
            $seqid = isset($_POST['seqid']) ? intval($_POST['seqid']) : '';
            $stepid = isset($_POST['stepid']) ? intval($_POST['stepid']) : '';    
            
            if(!empty($stepid)){
                $res = $this->stepModel->delete_step_id($jobid, $seqid, $stepid);
                $result = array();
                if($res){
                    $res_type = 'Success';
                    $res_msg = $text['del_step'].':'. $stepid."  ".$text['success'];
                    $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);
                }else{
                    $res_type = 'Error';
                    $res_msg = $text['del_step'].':'. $stepid."  ".$text['fail'];
                    $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);
                }
            }
      
        }
    
    }

    public function copy_tcc_step(){

        $file = $this->MiscellaneousModel->lang_load();
        if(!empty($file)){
            include $file;
        }

        if(isset($_POST['job_id'])){
            $job_id      = isset($_POST['job_id']) ? intval($_POST['job_id']) : '';
            $seq_id      = isset($_POST['seq_id']) ? intval($_POST['seq_id']) : '';
            $old_step_id = isset($_POST['old_step_id']) ? intval($_POST['old_step_id']) : '';
            $new_step_id = isset($_POST['new_step_id']) ? intval($_POST['new_step_id']) : '';

            // 2026-06-16: 移除 Target Torque 只能存在一筆的限制。
            // Copy Step 時，Torque / Angle / Delay 都允許複製。
            $old_res= $this->stepModel->getStepNo($job_id,$seq_id,$old_step_id);
            if(!empty($old_res)){
                // Downshift rule: only STEP 1 can enable Downshift.
                $copy_allow_downshift = ($new_step_id === 1)
                    && ((int)$old_res[0]['target_opt'] === 0)
                    && ((int)$old_res[0]['th_mode'] === 1);

                $copy_th_mode  = $copy_allow_downshift ? 1 : 0;
                $copy_th_tor   = $copy_allow_downshift ? $old_res[0]['th_tor'] : 0;
                $copy_ds_tor   = $copy_allow_downshift ? $old_res[0]['ds_tor'] : 0;
                $copy_ds_speed = $copy_allow_downshift ? $old_res[0]['ds_speed'] : 100;

                $jobdata = array(
                    'job_id'           => $job_id,
                    'seq_id'           => $seq_id,
                    'step_id'          => $new_step_id,
                    'target_opt'       => $old_res[0]['target_opt'],
                    'target_tor'       => $old_res[0]['target_tor'],
                    'target_ang'       => $old_res[0]['target_ang'],
                    'target_delay'     => $old_res[0]['target_delay'],
                    'tor_hi'           => $old_res[0]['tor_hi'],
                    'tor_lo'           => $old_res[0]['tor_lo'],
                    'ang_hi'           => $old_res[0]['ang_hi'],
                    'ang_lo'           => $old_res[0]['ang_lo'],
                    'rpm'              => $old_res[0]['rpm'],
                    'direction'        => $old_res[0]['direction'],
                    'th_mode'          => $copy_th_mode,
                    'th_tor'           => $copy_th_tor,
                    'ds_tor'           => $copy_ds_tor,
                    'ds_speed'         => $copy_ds_speed,
                    'record_ang'       => $old_res[0]['record_ang'],
                    'tor_unit'         => $old_res[0]['tor_unit'],
                    'pnf_set'          => $old_res[0]['pnf_set']
                );

                $mode = "copy";
                $res = $this->stepModel->create_step($mode,$jobdata);
                $result = array();
                if($res){
                    $res_type = 'Success';
                    $res_msg = $text['copy_step'].':'.$new_step_id."  ".$text['success'];
                    $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);
                }else{
                    $res_type = 'Error';
                    $res_msg = $text['copy_step'].':'.$new_step_id."  ".$text['fail'];
                    $this->MiscellaneousModel->generateErrorResponse($res_type, $res_msg);
                }
            }
        }


    }

    #查詢step data
    public function search_stepinfo(){

        $input_check = true;

        $jobid  = !empty($_POST['job_id'])  ? $_POST['job_id']  : ($input_check = false);
        $seqid  = !empty($_POST['seq_id'])  ? $_POST['seq_id']  : ($input_check = false);
        $stepid = !empty($_POST['step_id']) ? $_POST['step_id'] : ($input_check = false);

        if (!$input_check) {
            echo "Missing required parameters.";
            return;
        }

        /*
         * 查詢與儲存使用相同的 torque_unit 來源，
         * 避免重新進入 Edit 時再次換算。
         */
        $device = (array)(
            $this->SettingModel->GetControllerInfo()
            ?? []
        );

        $device_torque_unit = $this->getCurrentTorqueUnit(
            $device
        );

        $unit_arr = $this->MiscellaneousModel->details('torque_unit');
        $decimals = [
            0 => 4,
            1 => 3,
            2 => 2,
            3 => 2,
            4 => 1
        ];

        $res = $this->stepModel->getStepNo($jobid, $seqid, $stepid);

        if (empty($res[0])) {
            echo "Step data not found.";
            return;
        }

        $step_data = $res[0];
        $step_tor_unit = (int)($step_data['tor_unit'] ?? 1);
        $no_unit = $step_tor_unit;

        $unit_name = $unit_arr[$step_tor_unit] ?? "N.m";

        if ($step_tor_unit != $device_torque_unit) {
            // 轉成控制器單位
            $unit_name = $unit_arr[$device_torque_unit] ?? "N.m";
        }

        // 先轉換所有 torque 值
        $target_tor_tmp = $this->MiscellaneousModel->convert_all_torque_units($step_data['target_tor'], $no_unit);
        $tor_hi_tmp     = $this->MiscellaneousModel->convert_all_torque_units($step_data['tor_hi'],     $no_unit);
        $tor_lo_tmp     = $this->MiscellaneousModel->convert_all_torque_units($step_data['tor_lo'],     $no_unit);
        $ds_tor_tmp     = $this->MiscellaneousModel->convert_all_torque_units($step_data['ds_tor'],     $no_unit);
        $th_tor_tmp     = $this->MiscellaneousModel->convert_all_torque_units($step_data['th_tor'],     $no_unit);

        // 使用控制器單位或 step 單位填值
        if (!empty($target_tor_tmp)) {

            if ($step_tor_unit != $device_torque_unit) {
                // 不同單位 → 取轉換後值
                $step_data['target_tor'] = $target_tor_tmp[$unit_name];
                $step_data['tor_hi']     = $tor_hi_tmp[$unit_name];
                $step_data['tor_lo']     = $tor_lo_tmp[$unit_name];
                $step_data['th_tor']     = $th_tor_tmp[$unit_name];
                $step_data['ds_tor']     = $ds_tor_tmp[$unit_name];

                // 回傳值已是目前 Controller Setting 的單位。
                $step_data['tor_unit'] = $device_torque_unit;

            } else {
                // 相同單位 → 保留原值，但補小數位數
                $decimal = $decimals[$step_tor_unit] ?? 3;

                $step_data['target_tor'] = $this->formatTorque($step_data['target_tor'], $decimal);
                $step_data['tor_hi']     = $this->formatTorque($step_data['tor_hi'],     $decimal);
                $step_data['tor_lo']     = $this->formatTorque($step_data['tor_lo'],     $decimal);
                $step_data['th_tor']     = $this->formatTorque($step_data['th_tor'],     $decimal);
                $step_data['ds_tor']     = $this->formatTorque($step_data['ds_tor'],     $decimal);
            }
        }

        /*
         * tor_hi 必須保留資料庫實際儲存值。
         *
         * 55 N.m 只作為前端驗證允許的最高上限，不可在查詢 Step 時
         * 覆蓋使用者已儲存的扭力上限。例如使用者儲存 0.600 N.m，
         * 再次開啟 Edit 時仍應顯示 0.600 N.m。
         */

        // 取得工具資訊 → 全部轉成 unit_name 單位
        $tools = $this->ToolModel->GetToolInfo();

        if (!empty($tools)) {
            $tmp_torque_1 = $this->MiscellaneousModel->convert_all_torque_units($tools['tool_mintorque'], 1);
            $tmp_torque_2 = $this->MiscellaneousModel->convert_all_torque_units($tools['tool_maxtorque'], 1);

            if (is_array($tmp_torque_1) && isset($tmp_torque_1[$unit_name])) {
                $tools['tool_mintorque'] = $tmp_torque_1[$unit_name];
            }
            if (is_array($tmp_torque_2) && isset($tmp_torque_2[$unit_name])) {
                $tools['tool_maxtorque'] = $tmp_torque_2[$unit_name];
            }
        }

        $tools['__from_outside'] = true;

        $merged_info = array_merge($step_data, $tools);

        print_r($merged_info);
    }

    private function formatTorque($value, $decimal){
        if (!is_numeric($value)) return $value;
        return number_format((float)$value, $decimal, '.', '');
    }
        
    #排序step
    public function adjustment_order(){

        if (isset($_POST['jobid']) && isset($_POST['rowInfoArray'])) {
            $jobid = $_POST['jobid'];
            $rowInfoArray = $_POST['rowInfoArray'];
            $this->stepModel->swapupdate($jobid,$rowInfoArray);
        }
        
    }


    public function check_step_limit() {
        $jobid = $_POST['jobid'] ?? 0;
        $seqid = $_POST['seqid'] ?? 0;

        $result = $this->stepModel->check_step_targe_topt($jobid, $seqid);
        $count = $result['count'];
        $firstIsZero = $result['first_target_opt'] === 'Y';

        if ($count >= 4) {
            echo json_encode(['allow' => false, 'msg' => '最多只能新增 4 個步驟', 'count' => $count]);
            return;
        }

        if (!$firstIsZero) {
            echo json_encode(['allow' => false, 'msg' => '第一個步驟的 target_opt 必須為 0', 'count' => $count]);
            return;
        }

        echo json_encode(['allow' => true, 'count' => $count]);
    }



}
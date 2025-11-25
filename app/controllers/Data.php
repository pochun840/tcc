<?php

class Data extends Controller
{
    private $DataModel;
    private $MiscellaneousModel;

    // 建構子：初始化 Model
    public function __construct()
    {
        $this->DataModel = $this->model('Datas');                 // 資料查詢模型
        $this->MiscellaneousModel = $this->model('Miscellaneous'); // 含單位、狀態等通用設定
    }

    // 首頁顯示：載入資料頁面
    public function index()
    {
        $type = 'ALL';
        $isMobile = $this->isMobileCheck(); // 判斷是否為手機裝置

        // 取得目前年度資料庫路徑（Linux專用）
        if (PHP_OS_FAMILY === 'Linux') {
            $db_path = "/var/www/html/database/data" . date('Y') . ".db";
            $db_exists = file_exists($db_path);

            if ($db_exists) {
                // 若資料庫存在則撈取資料
                $res_data     = $this->DataModel->getData('ALL');
                $res_data_ok  = $this->DataModel->getData('OK');
                $res_data_nok = $this->DataModel->getData('NOK');
            } else {
                // 否則回傳空陣列
                $res_data     = [];
                $res_data_ok  = [];
                $res_data_nok = [];
            }
        } else {
            // 非 Linux 直接查詢（假設 DB 一定存在）
            $res_data     = $this->DataModel->getData('ALL');
            $res_data_ok  = $this->DataModel->getData('OK');
            $res_data_nok = $this->DataModel->getData('NOK');
            $db_exists = '';
            $db_path = '';
        }

        // 查詢單位與狀態、裝置資訊
        $unit_arr    = $this->MiscellaneousModel->details('torque_unit');
        $status_arr  = $this->MiscellaneousModel->details('status');
        $device_info = $this->Device_Info();

        // 組合資料傳給 view
        $data = array(
            'isMobile'      => $isMobile,
            'res_data'      => $res_data,
            'res_data_ok'   => $res_data_ok,
            'res_data_nok'  => $res_data_nok,
            'device_info'   => $device_info,
            'unit_arr'      => $unit_arr,
            'status_arr'    => $status_arr,
            'db_exists'     => $db_exists,
            'db_path'       => $db_path
        );

        $this->view('data/index', $data);
    }

    // AJAX 請求：取得指定模式資料（ALL / OK / NOK）
    public function search_info()
    {
        $unit_arr = $this->MiscellaneousModel->details('torque_unit');
        $status_arr = $this->MiscellaneousModel->details('status');

        $mode = $_POST['mode'] ?? null;

        if ($mode) {
            $res_data = $this->DataModel->getData($mode);
            if (!empty($res_data)) {
                $info_data = '';
                foreach ($res_data as $ve) {
                    // 根據 fasten_status 決定顏色
                    if ($ve['fasten_status'] == 7 || $ve['fasten_status'] == 8) {
                        $style = 'style="background: red"';
                    } elseif ($ve['fasten_status'] == 5 || $ve['fasten_status'] == 6) {
                        $style = 'style="background: #FFEF62"';
                    } else {
                        $style = 'style="background: green"';
                    }

                    
                    $info_data .= '<tr>';
                    $info_data .= "<td>{$ve['system_sn']}</td>";
                    $info_data .= "<td>{$ve['data_time']}</td>";
                    $info_data .= "<td>{$ve['job_name']}</td>";
                    $info_data .= "<td>{$ve['sequence_name']}</td>";
                    $info_data .= "<td>{$ve['fasten_torque']}</td>";
                    $info_data .= "<td id='{$unit_arr[$ve['torque_unit']]}'>{$unit_arr[$ve['torque_unit']]}</td>";
                    $info_data .= "<td>{$ve['fasten_angle']}</td>";
                    $info_data .= "<td>{$ve['total_screw_count']}</td>";
                    $info_data .= "<td>{$ve['last_screw_count']}</td>";
                    $info_data .= "<td $style>{$status_arr[$ve['fasten_status']]}</td>";
                    $info_data .= '</tr>';
                }

                echo $info_data; // 回傳給前端顯示
            }
        }
    }

    // 匯出資料：依據日期範圍輸出 CSV 或 ZIP
    public function exportData()
    {
        $input_check = true;

        // 日期驗證與格式化
        try {
            $start_date = new DateTime($_POST['start_date'] ?? '');
            $end_date = new DateTime($_POST['end_date'] ?? '');
            $start_date = $start_date->format('Ymd H:i:s');
            $end_date = $end_date->format('Ymd H:i:s');
            $end_date = str_replace("00:00:00", "23:59:59", $end_date); // 時間結尾補滿
        } catch (Exception $e) {
            $input_check = false;
        }

        // 匯出格式：0=csv, 1=zip
        $expert_val = $_POST['expert_val'] ?? "0";

        if ($input_check) {
            $unit_arr = $this->MiscellaneousModel->details('torque_unit');
            $status_arr = $this->MiscellaneousModel->details('status');

            // 資料範圍查詢
            $dataset = $this->DataModel->get_range_data($start_date, $end_date);
            $dataset = array_slice($dataset, 0, 10000); 

            // 補充 torque_unit / fasten_status 對應文字
            foreach ($dataset as $key => $val) {
                $dataset[$key]['torque_unit'] = $unit_arr[$val['step_tor_unit']] ?? '';
                $dataset[$key]['fasten_status'] = $status_arr[$val['fasten_status']] ?? '';
            }

            if ($dataset && $expert_val == "0") {
                // 匯出為 CSV
                $csv_headers = array_keys($dataset[0]);
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=tcc_data.csv');

                $output = fopen('php://output', 'w');
                fputcsv($output, $csv_headers);
                foreach ($dataset as $row) {
                    fputcsv($output, $row);
                }
                fclose($output);
                exit();

            } elseif ($dataset && $expert_val == "1") {
                // 匯出 ZIP
                $csv_content = implode(',', array_keys($dataset[0])) . "\n";
                foreach ($dataset as $row) {
                    $csv_content .= implode(',', $row) . "\n";
                }

                $zip = new ZipArchive();
                $zip_filename = tempnam(sys_get_temp_dir(), 'tcc_data') . '.zip';

                if ($zip->open($zip_filename, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
                    $zip->addFromString("data.csv", $csv_content);
                    $zip->close();

                    header('Content-Type: application/zip');
                    header('Content-Disposition: attachment; filename=tcc_data.zip');
                    header('Content-Length: ' . filesize($zip_filename));
                    readfile($zip_filename);
                    unlink($zip_filename);
                    exit();
                } else {
                    echo "無法建立 ZIP 檔案";
                }
            }
        } else {
            echo "輸入參數不正確";
        }
    }

    // 取得最新資料（即時刷新）
    public function getreal_time_data()
    {
        $mode = $_POST['mode'] ?? 'ALL';
        $db_path = "/var/www/html/database/data" . date('Y') . ".db";

        if (!file_exists($db_path)) {
            echo json_encode(['success' => false, 'msg' => "資料庫不存在"]);
            return;
        }

        $res_data = $this->DataModel->getData($mode);
        $unit_arr = $this->MiscellaneousModel->details('torque_unit');
        $status_arr = $this->MiscellaneousModel->details('status');

        echo json_encode([
            'success' => true,
            'records' => $res_data,
            'unit_arr' => $unit_arr,
            'status_arr' => $status_arr
        ]);
    }


    public function download_file() {

        // 僅支援 Linux
        if (PHP_OS_FAMILY !== 'Linux') {
            return $this->respondError('Error', '只支援在 Linux 環境下下載 CSV 壓縮包。');
        }

        $dir = '/mnt/ramdisk/ftp';
        if (!is_dir($dir) || !is_readable($dir)) {
            return $this->respondError('Error', "資料夾無法讀取：{$dir}");
        }

        // 收集 CSV：解析開頭流水號與時間戳（作為排序依據）
        $entries = [];
        try {
            $it = new DirectoryIterator($dir);
            foreach ($it as $f) {
                if (!$f->isFile() || !$f->isReadable()) continue;
                $name = $f->getFilename();
                $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                if ($ext !== 'csv') continue;

                $full   = $f->getPathname();
                $serial = -1; // 找不到就設為 -1（排最後）
                if (preg_match('/^(\d+)__/', $name, $m)) {
                    $serial = (int)$m[1];
                }

                // 解析檔名中的 14 碼時間戳；沒有就用 mtime
                $ts = 0;
                if (preg_match('/_(\d{14})(?:_|\.csv$)/i', $name, $m)) {
                    $dt = DateTime::createFromFormat('YmdHis', $m[1]);
                    if ($dt) $ts = $dt->getTimestamp();
                }
                if ($ts <= 0) {
                    $mtime = @filemtime($full);
                    if ($mtime !== false) $ts = (int)$mtime;
                }

                $entries[] = [
                    'path'   => $full,
                    'name'   => $name,
                    'serial' => $serial,
                    'ts'     => $ts,
                ];
            }
        } catch (Throwable $e) {
            return $this->respondError('Error', '掃描資料夾失敗：' . $e->getMessage());
        }

        // ★ 沒資料：回傳 JSON，讓前端彈「沒有曲線圖可下載」的提示
 
        
        if (empty($entries)) {
            if (!headers_sent()) {
                header('Content-Type: application/json; charset=utf-8');
                header('Cache-Control: no-store, no-cache, must-revalidate');
            }
            echo json_encode([
                'res_type' => 'Info',
                'res_code' => 'NO_CURVE_DATA',
                'res_msg'  => 'No curve data'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // 排序：先流水號(大→小)，再時間(新→舊)，再檔名
        usort($entries, function ($a, $b) {
            $as = $a['serial']; $bs = $b['serial'];
            if ($as < 0 && $bs >= 0) return 1;   // 無流水號者排後
            if ($bs < 0 && $as >= 0) return -1;
            if ($as !== $bs) return $bs <=> $as;                 // 流水號大→前
            if ($a['ts'] !== $b['ts']) return $b['ts'] <=> $a['ts']; // 新→前
            return strcmp($a['name'], $b['name']);
        });

        if (!class_exists('ZipArchive')) {
            return $this->respondError('Error', '伺服器未安裝 ZipArchive 擴充，無法建立 ZIP。');
        }

        // ZIP 檔名用 Linux 系統時間（fallback: PHP date）
        $ts          = $this->linuxNowOrPhp();
        $zipBasename = "csv_bundle_{$ts}.zip";
        $tmpZip      = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $zipBasename;

        $zip = new ZipArchive();
        if ($zip->open($tmpZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return $this->respondError('Error', '無法建立 ZIP 壓縮檔。');
        }

        foreach ($entries as $e) {
            $zip->addFile($e['path'], $e['name']); // 保留原檔名
        }
        $zip->close();

        if (!is_file($tmpZip) || !is_readable($tmpZip)) {
            return $this->respondError('Error', 'ZIP 產生失敗或不可讀取。');
        }

        // 串流下載
        @set_time_limit(0);
        if (function_exists('ob_get_level')) { while (ob_get_level() > 0) { @ob_end_clean(); } }
        header('Content-Description: File Transfer');
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zipBasename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($tmpZip));
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        $fp = fopen($tmpZip, 'rb');
        if ($fp) {
            while (!feof($fp)) { echo fread($fp, 8192); flush(); }
            fclose($fp);
        } else {
            @unlink($tmpZip);
            return $this->respondError('Error', '無法讀取 ZIP 檔案。');
        }
        @unlink($tmpZip);
        exit;
    }



    /** 取 Linux 系統時間（失敗退回 PHP date） */
    protected function linuxNowOrPhp(): string{

        $ts = date('YmdHis');
        if (PHP_OS_FAMILY === 'Linux') {
            $out = @shell_exec("date '+%Y%m%d%H%M%S' 2>/dev/null");
            $out = is_string($out) ? trim($out) : '';
            if (preg_match('/^\d{14}$/', $out)) $ts = $out;
        }
        return $ts;
    }

    /** 統一錯誤回應（沿用你的 MiscellaneousModel；沒有就回 JSON） */
    protected function respondError(string $type, string $msg){
        
        if (isset($this->MiscellaneousModel) && method_exists($this->MiscellaneousModel, 'generateErrorResponse')) {
            return $this->MiscellaneousModel->generateErrorResponse($type, $msg);
        }
        if (!headers_sent()) header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['res_type'=>$type, 'res_msg'=>$msg], JSON_UNESCAPED_UNICODE);
        return null;
    }


}
?>

<?php

class Logins extends Controller
{
    // 在建構子中將 Post 物件（Model）實例化
    public function __construct()
    {
        $this->LoginModel = $this->model('Login');
        $this->SettingModel = $this->model('Setting');
    }

    // 取得所有Jobs
    public function index($url){
        session_start();

        /*
         * 自動檢查品牌目錄權限。
         *
         * 若本次 GET 請求成功修正權限，重新載入一次頁面，
         * 讓 config.php 在新請求中重新判斷品牌碼。
         * POST 登入流程則不立即重新導向，避免遺失登入資料；
         * 登入成功後原本就會導向 Dashboards。
         */
        $brandPermissionStatus =
            $this->ensureBrandDirectoryPermission();

        if (
            $brandPermissionStatus === 'fixed'
            && empty($_POST)
        ) {
            $requestUri = $_SERVER['REQUEST_URI']
                ?? '/idas/public/';

            header('Location: ' . $requestUri);
            exit;
        }

        $device_info = $this->Device_Info();
        $_SESSION['sessionid'] = session_id();
        $_SESSION['privilege'] = '';

        $data = [
            'error_message' => '',
            'device_info'   => $device_info
        ];

        // 例外狀況：切換語系
        $exception = false;
        if (isset($url[1]) && $url[0] === 'Dashboards' && $url[1] === 'change_language') {
            $exception = true;
        }

        /* =========================
        * Login POST
        * ========================= */
        if (!empty($_POST['password'])) {

            // login attempt
            $this->logLoginAttempt();

            $authToken = hash('sha256', $_POST['password']);

            if ($this->verifyCredentials($authToken)) {

                if (PHP_OS === 'Linux') {
                    $this->SettingModel->login_db_load();
                }

                setcookie('auth_token', $authToken, time() + 6000000, '/');

                // ✅ 關鍵修正：登入成功後一定 redirect（PRG）
                         header('Location: /idas/public/?url=Dashboards');
                exit;
            }

            // 登入失敗
            $this->logout();
            $this->view('login/index', $data);
            exit;
        }

        /* =========================
        * 非 POST：檢查登入狀態
        * ========================= */
        if ($this->isAuthenticated() || $exception) {
            return true;
        }

        // 未登入
        $this->logout();
        $this->view('login/index', $data);
        exit;
    }



    public function isAuthenticated() {
        if (isset($_COOKIE['auth_token'])) {
            $authToken = $_COOKIE['auth_token'];
            
            // 解密和驗證令牌的有效性，根據需要進行自定義驗證
            $username = $this->verifyCredentials($authToken);

            if ($username !== false) {
                // 令牌有效，可以根據需要刷新 Cookie 的過期時間
                setcookie('auth_token', $authToken, time() + 600, '/');
                return true;
            }
        }

        return false;
    }

    // 退出登錄並清除身份驗證令牌
    public function logout() {
        setcookie('auth_token', '', time() - 3600, '/');
    }

    // 验证用户提交的用户名和密码
    public function verifyCredentials($authToken) {
        // 自定義的身份驗證邏輯，根據實際情況進行驗證
        // 返回 true 表示驗證成功，false 表示驗證失敗
        // 可以與數據庫或其他存儲進行比對驗證
        $pwd = $this->LoginModel->getpwd(); //控制器密碼
        $pwd2 = $this->LoginModel->GetiDasPwd(); //idas密碼
        $input = $authToken;
        $output = hash('sha256', $pwd['operator_adminpwd']);
        //$output2 = hash('sha256', $pwd2['password']);

         if($input == $output){
            //登入成功寫入 active_sessions 資料庫
            $reslut = $this->active_sessions('admin');

            if($reslut){
                $_SESSION['privilege'] = 'admin';
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function logLoginAttempt()
    {
        if (!empty($_SERVER["HTTP_CLIENT_IP"])){
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }else{
            $ip = $_SERVER["REMOTE_ADDR"];
        }
        $this->LoginModel->logLoginAttempt($ip);
    }
    
    public function active_sessions($username)
    {
        //0.先清理過期的session
        //1.先確認是否達連線上限
        //2.如果已達連線上限，回傳false
        //3.如果未達連線上限，寫入db
        //4.檢查session id是否存在
        //5.如果存在update time
        //6.如果不存在insert
        $max_concurrent_users = $this->Max_User();//連線數量限制
        $session_id = session_id();

        if (!empty($_SERVER["HTTP_CLIENT_IP"])){
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }else{
            $ip = $_SERVER["REMOTE_ADDR"];
        }

        //清理過期的session
        $this->LoginModel->cleanExpiredSessions();
        //確認目前連線數量，排除目前的session_id
        $concurrent_users = $this->LoginModel->GetConcurrentUsers($session_id);

        if($concurrent_users >= $max_concurrent_users && $username == 'guest'){
            $this->Users_Uplimit();
            return false;
        }else{
            $this->LoginModel->active_sessions($username,$session_id,$ip);
            return true;
        }

        

    }

    //連線數達到上限時，直接從這邊跳回登入畫面，並帶error message
    public function Users_Uplimit()
    {
        $error_message = '連線數已達上限';
        $authToken = '';
        $data = [
            'error_message' => $error_message
        ];

        $this->logout();
        $this->view('login/index', $data);
        exit();
    }


    /**
     * 自動確認 www-data 是否能讀取品牌目錄。
     *
     * 回傳值：
     *   ready  - 原本就可以讀取
     *   fixed  - 已透過 fix_brand_permission.php 修正
     *   failed - 修正失敗
     *
     * 注意：
     * 一般網頁由 www-data 執行，無法自行取得 root 權限。
     * 因此此處使用 sudo -n 呼叫固定的 PHP 修正工具。
     * 系統必須允許 www-data 免密碼執行該固定命令。
     */
    private function ensureBrandDirectoryPermission()
    {
        if (PHP_OS_FAMILY !== 'Linux') {
            return 'ready';
        }

        $brandDirectory =
            '/home/kls/project/system/ltver';

        clearstatcache(true, $brandDirectory);

        $files = @scandir($brandDirectory);

        if (is_array($files)) {
            return 'ready';
        }

        if (!function_exists('exec')) {
            error_log(
                '[Brand Permission] exec() is unavailable.'
            );

            return 'failed';
        }

        $disabledFunctions = array_filter(
            array_map(
                'trim',
                explode(
                    ',',
                    (string)ini_get('disable_functions')
                )
            )
        );

        if (
            in_array(
                'exec',
                $disabledFunctions,
                true
            )
        ) {
            error_log(
                '[Brand Permission] exec() is disabled.'
            );

            return 'failed';
        }

        $fixScript =
            '/var/www/html/idas/fix_brand_permission.php';

        if (!is_file($fixScript)) {
            error_log(
                '[Brand Permission] Fix script not found: '
                . $fixScript
            );

            return 'failed';
        }

        $sudo = '';

        foreach (
            array(
                '/usr/bin/sudo',
                '/bin/sudo',
            ) as $candidate
        ) {
            if (
                is_file($candidate)
                && is_executable($candidate)
            ) {
                $sudo = $candidate;
                break;
            }
        }

        $php = '';

        foreach (
            array(
                '/usr/bin/php',
                '/usr/local/bin/php',
                '/bin/php',
            ) as $candidate
        ) {
            if (
                is_file($candidate)
                && is_executable($candidate)
            ) {
                $php = $candidate;
                break;
            }
        }

        if ($sudo === '' || $php === '') {
            error_log(
                '[Brand Permission] sudo or php was not found.'
            );

            return 'failed';
        }

        /*
         * -n：禁止 sudo 等待輸入密碼，避免登入頁卡住。
         * --：結束 sudo 參數，後方為固定 PHP 命令。
         */
        $arguments = array(
            $sudo,
            '-n',
            '--',
            $php,
            $fixScript,
        );

        $command = implode(
            ' ',
            array_map(
                'escapeshellarg',
                $arguments
            )
        );

        $output = array();
        $exitCode = -1;

        exec(
            $command . ' 2>&1',
            $output,
            $exitCode
        );

        error_log(
            '[Brand Permission] command='
            . $command
            . ' exit='
            . $exitCode
            . ' output='
            . implode(' | ', $output)
        );

        clearstatcache(true, $brandDirectory);

        $files = @scandir($brandDirectory);

        if (
            $exitCode === 0
            && is_array($files)
        ) {
            return 'fixed';
        }

        return 'failed';
    }


    public function Max_User()
    {
        $reslut = $this->LoginModel->get_max_user();
        return $reslut;
    }

}
?>
<?php

class Core
{
    protected $currentController = 'Dashboards';
    protected $currentMethod     = 'index';
    protected $params            = [];

    public function __construct()
    {
        /* ============================
         * Login 驗證
         * ============================ */
        require_once dirname(dirname(__FILE__)) . '/controllers/Logins.php';
        $login_class = new Logins();

        $url = $this->getUrl() ?? [];

        $valid_result = $login_class->index($url);

        // 登入後導向 Dashboards
        if (isset($url[0]) && $url[0] === 'Logins' && $valid_result) {
            $url[0] = 'Dashboards';
        }

        /* ============================
         * API / Mobile 判斷
         * ============================ */
        $isAPI = false;
        if (isset($url[1]) && $url[0] === 'Dashboards' && $url[1] === 'get_last_data') {
            $isAPI = true;
        }

        $isMobile = $this->isMobileCheck();
        // 你原本的 mobile redirect 保留（目前註解）
        /*
        if ($isMobile && !$isAPI) {
            $url[0] = 'Dashboards';
            $url[1] = 'operation';
        }
        */

        /* ============================
         * Controller 防呆處理
         * ============================ */
        $controllerName = $this->currentController;

        if (!empty($url[0])) {
            // 大小寫容錯（JOBS / jobs / Jobs 都可）
            $controllerName = ucfirst(strtolower($url[0]));
        }

        $controllerFile = dirname(dirname(__FILE__)) . '/controllers/' . $controllerName . '.php';

        if (!file_exists($controllerFile)) {
            require_once dirname(dirname(__FILE__)) . '/controllers/Errors.php';
            $err = new Errors();
            $err->notFound();
            exit;
        }

        require_once $controllerFile;
        $this->currentController = new $controllerName();


        /* ============================
        * Method 防呆處理
        * ============================ */
        if (isset($url[1])) {
            $method = strtolower($url[1]);

            if (!method_exists($this->currentController, $method)) {
                require_once dirname(dirname(__FILE__)) . '/controllers/Errors.php';
                $err = new Errors();
                $err->notFound();
                exit;
            }

            $this->currentMethod = $method;
        }

        /* ============================
         * Params
         * ============================ */
        if (count($url) > 2) {
            $this->params = array_slice($url, 2);
        } else {
            $this->params = [];
        }

        /* ============================
         * Dispatch
         * ============================ */
        call_user_func_array(
            [$this->currentController, $this->currentMethod],
            $this->params
        );
    }

    public function getUrl()
    {
        if (isset($_GET['url']) && $_GET['url'] !== '') {
            return explode('/', trim($_GET['url'], '/'));
        }
        return [];
    }

    public function isMobileCheck()
    {
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

        return (
            stripos($ua, "iPod") !== false ||
            stripos($ua, "iPhone") !== false ||
            stripos($ua, "iPad") !== false ||
            (stripos($ua, "Android") !== false && stripos($ua, "mobile") !== false) ||
            stripos($ua, "webOS") !== false ||
            stripos($ua, "BlackBerry") !== false ||
            stripos($ua, "RIM Tablet") !== false
        );
    }
}

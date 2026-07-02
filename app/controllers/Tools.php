<?php

class Tools extends Controller
{
    private $ToolModel;

    // 在建構子中將 Model 實例化
    public function __construct()
    {
        $this->ToolModel = $this->model('Tool');
    }

    // 取得所有 info
    public function index()
    {
        // config.php 內的一般變數 $brand_code 在 class method 內不會自動可見。
        // 這裡統一讀 config.php 定義的 BRAND_CODE / ICONMODE 常數。
        $brand_code = $this->getBrandCode();
        $icon_mode  = $this->getIconMode();

        $isMobile = $this->isMobileCheck();

        $Controller_Info = [];
        $Tool_Info = [];
        $databaseError = '';

        try {
            $Controller_Info = (array)(
                $this->ToolModel->GetControllerInfo()
                ?? []
            );

            $Tool_Info = (array)(
                $this->ToolModel->GetToolInfo()
                ?? []
            );

            if (
                method_exists(
                    $this->ToolModel,
                    'getLastError'
                )
            ) {
                $databaseError = trim(
                    (string)$this->ToolModel->getLastError()
                );
            }
        } catch (Throwable $exception) {
            $databaseError =
                $exception->getMessage();

            error_log(
                '[Tools Controller] '
                . $databaseError
            );
        }

        /*
         * tool_calib_time 有時會讀到未初始化資料
         *（例如 0xFF，畫面會變成 ÿÿÿ...）。
         */
        $Tool_Info['tool_calib_time'] =
            $this->formatCalibrationTime(
                $Tool_Info['tool_calib_time']
                ?? ''
            );

        $MAC = $this->getMacAddress();
        $ip_addr = $this->getIp();
        /*
         * 依品牌取得 QR Code。
         *
         * 會檢查本機 PNG 是否：
         * - 檔案存在
         * - Apache 可讀
         * - 檔案大小合理
         * - 圖片寬高不是 1x1 或其他異常尺寸
         *
         * 本機圖片無效時，依 qrcode_url 產生正確 QR Code，
         * 避免畫面顯示透明圖片或錯誤品牌的 QR。
         */
        $qrConfig = $this->getQrCodeConfig(
            $brand_code
        );

        $qrcode = $qrConfig['image'];
        $qrcode_url = $qrConfig['target_url'];

        $data = [
            'isMobile'        => $isMobile,
            'controller_info' => $Controller_Info,
            'tools_info'      => $Tool_Info,
            'IP'              => $ip_addr,
            'MAC'             => $MAC,
            'brand_code'      => $brand_code,
            'icon_mode'       => $icon_mode,
            'device_type'     => defined('DEVICE_TYPE_10') ? DEVICE_TYPE_10 : '',
            'title_index'     => defined('TITLE_INDEX') ? TITLE_INDEX : '',
            'qrcode'          => $qrcode,
            'qrcode_url'      => $qrcode_url,
            'qrcode_source'   => $qrConfig['source'],
            'qrcode_error'    => $qrConfig['error'],
            'qrcode_local_path' => $qrConfig['local_path'],
            'database_error'  => $databaseError
         ];

   


        $this->view('tool/index', $data);
    }


    public function get_brand_code_test()
    {
        if (PHP_OS_FAMILY !== 'Linux') {
            return false;
        }

        $directory = '/home/kls/project/system/ltver';

        clearstatcache(true, $directory);

        if (!is_dir($directory)) {
            error_log(
                '[Brand Code] Directory not found or inaccessible: '
                . $directory
            );

            return false;
        }

        $fileList = @scandir($directory);

        if (!is_array($fileList)) {
            error_log(
                '[Brand Code] scandir failed. '
                . 'PHP user may not have permission: '
                . $directory
            );

            return false;
        }

        foreach ($fileList as $fileName) {
            if (
                $fileName === '.'
                || $fileName === '..'
            ) {
                continue;
            }

            /*
            * 支援：
            * BF05-1001-0-J60004.hex
            * BF05-1001-O-J60004.hex
            * BF05_1001.hex
            * BF05.1001.hex
            */
            if (
                preg_match(
                    '/^(BF\d{2})(?:[-_.]|$)/i',
                    $fileName,
                    $matches
                )
            ) {
                return strtoupper($matches[1]);
            }
        }

        error_log(
            '[Brand Code] No BFxx file found in: '
            . $directory
        );

        return false;
    }




    /**
     * 取得指定品牌的 QR Code 設定。
     */
    private function getQrCodeConfig(string $brandCode): array
    {
        $brandCode = strtoupper(
            trim($brandCode)
        );

        $configs = [
            'BF05' => [
                'relative_path' =>
                    'img/Sumake_icon/qr_code.png',
                'target_url' =>
                    'https://s3.hicloud.net.tw/electric-tools/'
                    . 'transducer/SCT-C2%20DIR-K2.pdf'
            ],

            'DEFAULT' => [
                'relative_path' =>
                    'img/qr_code.png',
                'target_url' =>
                    'https://www.kilews.com.tw/'
                    . 'tc/download-list.php'
            ]
        ];

        $selected = $configs[$brandCode]
            ?? $configs['DEFAULT'];

        return $this->resolveQrCodeImage(
            $selected['relative_path'],
            $selected['target_url']
        );
    }


    /**
     * 檢查本機 QR 圖片。
     *
     * 本機圖片有效：
     *     使用本機圖片 + filemtime 防快取。
     *
     * 本機圖片無效：
     *     依 target URL 使用遠端服務產生 QR Code。
     */
    private function resolveQrCodeImage(
        string $relativePath,
        string $targetUrl
    ): array {
        $publicRoot = $this->getPublicRoot();

        $normalizedRelativePath = ltrim(
            str_replace(
                ['\\', '..'],
                ['/', ''],
                $relativePath
            ),
            '/'
        );

        $localPath = $publicRoot
            . DIRECTORY_SEPARATOR
            . str_replace(
                '/',
                DIRECTORY_SEPARATOR,
                $normalizedRelativePath
            );

        $validation = $this->validateQrCodeImage(
            $localPath
        );

        if ($validation['valid']) {
            $version = @filemtime($localPath);

            if (!$version) {
                $version = defined('ASSET_VERSION')
                    ? ASSET_VERSION
                    : time();
            }

            return [
                'image' =>
                    $this->buildPublicAssetUrl(
                        $normalizedRelativePath
                    )
                    . '?v='
                    . rawurlencode((string)$version),

                'target_url' => $targetUrl,
                'source' => 'local',
                'error' => '',
                'local_path' => $localPath
            ];
        }

        $remoteEnabled = defined(
            'QR_CODE_REMOTE_FALLBACK'
        )
            ? (bool)QR_CODE_REMOTE_FALLBACK
            : true;

        if ($remoteEnabled) {
            $endpoint = defined(
                'QR_CODE_REMOTE_ENDPOINT'
            )
                ? trim((string)QR_CODE_REMOTE_ENDPOINT)
                : 'https://quickchart.io/qr';

            if ($endpoint !== '') {
                $query = http_build_query(
                    [
                        'text' => $targetUrl,
                        'size' => 180,
                        'margin' => 2,
                        'ecLevel' => 'M',
                        'format' => 'png'
                    ],
                    '',
                    '&',
                    PHP_QUERY_RFC3986
                );

                return [
                    'image' =>
                        rtrim($endpoint, '?&')
                        . '?'
                        . $query,

                    'target_url' => $targetUrl,
                    'source' => 'remote-generated',
                    'error' => $validation['message'],
                    'local_path' => $localPath
                ];
            }
        }

        /*
         * 完全離線且本機圖片無效時，顯示清楚的 placeholder，
         * 不再顯示透明的 1x1 PNG。
         */
        return [
            'image' =>
                $this->buildQrUnavailableDataUri(),

            'target_url' => $targetUrl,
            'source' => 'placeholder',
            'error' => $validation['message'],
            'local_path' => $localPath
        ];
    }


    /**
     * 驗證本機圖片是否可作為 QR Code。
     */
    private function validateQrCodeImage(
        string $path
    ): array {
        if (!is_file($path)) {
            return [
                'valid' => false,
                'message' =>
                    'QR image does not exist: '
                    . $path
            ];
        }

        if (!is_readable($path)) {
            return [
                'valid' => false,
                'message' =>
                    'QR image is not readable: '
                    . $path
            ];
        }

        $minimumFileSize = defined(
            'QR_CODE_MIN_FILE_SIZE'
        )
            ? (int)QR_CODE_MIN_FILE_SIZE
            : 256;

        $fileSize = @filesize($path);

        if (
            $fileSize === false
            || $fileSize < $minimumFileSize
        ) {
            return [
                'valid' => false,
                'message' =>
                    'QR image file is too small: '
                    . (int)$fileSize
                    . ' bytes'
            ];
        }

        $imageInfo = @getimagesize($path);

        if (
            !is_array($imageInfo)
            || !isset($imageInfo[0], $imageInfo[1])
        ) {
            return [
                'valid' => false,
                'message' =>
                    'QR image format is invalid.'
            ];
        }

        $minimumWidth = defined(
            'QR_CODE_MIN_WIDTH'
        )
            ? (int)QR_CODE_MIN_WIDTH
            : 64;

        $minimumHeight = defined(
            'QR_CODE_MIN_HEIGHT'
        )
            ? (int)QR_CODE_MIN_HEIGHT
            : 64;

        $width = (int)$imageInfo[0];
        $height = (int)$imageInfo[1];

        if (
            $width < $minimumWidth
            || $height < $minimumHeight
        ) {
            return [
                'valid' => false,
                'message' =>
                    'QR image dimensions are invalid: '
                    . $width
                    . 'x'
                    . $height
            ];
        }

        return [
            'valid' => true,
            'message' => '',
            'width' => $width,
            'height' => $height,
            'size' => (int)$fileSize
        ];
    }


    /**
     * 取得 /var/www/html/idas/public 實際路徑。
     */
    private function getPublicRoot(): string
    {
        if (defined('APPROOT')) {
            $appRoot = rtrim(
                (string)APPROOT,
                '/\\'
            );

            return dirname($appRoot)
                . DIRECTORY_SEPARATOR
                . 'public';
        }

        return dirname(__DIR__, 2)
            . DIRECTORY_SEPARATOR
            . 'public';
    }


    /**
     * 建立前端可用的圖片 URL。
     */
    private function buildPublicAssetUrl(
        string $relativePath
    ): string {
        $base = defined('URLROOT')
            ? rtrim((string)URLROOT, '/')
            : '../public';

        return $base
            . '/'
            . ltrim($relativePath, '/');
    }


    /**
     * 本機圖片無效且無法使用遠端服務時的提示圖片。
     */
    private function buildQrUnavailableDataUri(): string
    {
        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="180" height="180" viewBox="0 0 180 180">
  <rect width="180" height="180" fill="#ffffff"/>
  <rect x="1" y="1" width="178" height="178" fill="none" stroke="#c62828" stroke-width="2"/>
  <text x="90" y="82" text-anchor="middle" font-family="Arial,sans-serif" font-size="16" fill="#c62828">QR Code</text>
  <text x="90" y="105" text-anchor="middle" font-family="Arial,sans-serif" font-size="13" fill="#555555">Unavailable</text>
</svg>
SVG;

        return 'data:image/svg+xml;base64,'
            . base64_encode($svg);
    }


    private function formatCalibrationTime($value): string
    {
        $raw = trim((string)$value);

        if ($raw === '') {
            return '';
        }

        // 常見未初始化內容：0xFF 會顯示成 ÿ、或資料全為 F / 0。
        $upper = strtoupper($raw);
        if (preg_match('/^(ÿ+|Y+|F+|0+)$/u', $upper)) {
            return '';
        }

        // 只接受純數字日期，避免把亂碼直接顯示到畫面。
        if (!preg_match('/^\d{8}(\d{6})?$/', $raw)) {
            return '';
        }

        if (strlen($raw) === 8) {
            $date = DateTime::createFromFormat('Ymd', $raw);
        } else {
            $date = DateTime::createFromFormat('YmdHis', $raw);
        }

        if (!$date) {
            return '';
        }

        $errors = DateTime::getLastErrors();
        if (is_array($errors) && ($errors['warning_count'] > 0 || $errors['error_count'] > 0)) {
            return '';
        }

        return $date->format('Y/m/d');
    }

    private function getBrandCode()
    {
        if (defined('BRAND_CODE') && BRAND_CODE !== '') {
            return BRAND_CODE;
        }

        // fallback：config.php 若尚未新增 BRAND_CODE，但 get_brand_code() 已載入，仍可讀取
        if (function_exists('get_brand_code')) {
            $brandCode = get_brand_code();
            if (function_exists('normalize_brand_code')) {
                return normalize_brand_code($brandCode);
            }
            return $brandCode === false || $brandCode === '' ? 'BF01' : strtoupper((string)$brandCode);
        }

        // Windows / XAMPP 或讀不到品牌檔時，預設 Kilews
        return 'BF01';
    }

    private function getIconMode()
    {
        if (defined('ICONMODE')) {
            return ICONMODE;
        }

        if (function_exists('brand_code_to_icon_mode')) {
            return brand_code_to_icon_mode($this->getBrandCode());
        }

        return '0';
    }


    public function brand_debug()
    {
        header('Content-Type: text/plain; charset=utf-8');

        echo "PHP_OS_FAMILY = " . PHP_OS_FAMILY . PHP_EOL;
        echo "PHP_SAPI = " . PHP_SAPI . PHP_EOL;
        echo "PHP_CURRENT_USER = " . get_current_user() . PHP_EOL;

        if (
            function_exists('posix_geteuid')
            && function_exists('posix_getpwuid')
        ) {
            $userInfo = @posix_getpwuid(
                @posix_geteuid()
            );

            echo "PHP_EFFECTIVE_USER = "
                . (
                    is_array($userInfo)
                    ? ($userInfo['name'] ?? '')
                    : ''
                )
                . PHP_EOL;
        }

        echo "BRAND_CODE_RAW = "
            . (defined('BRAND_CODE_RAW') ? BRAND_CODE_RAW : '')
            . PHP_EOL;

        echo "BRAND_CODE = "
            . (defined('BRAND_CODE') ? BRAND_CODE : '')
            . PHP_EOL;

        echo "ICONMODE = "
            . (defined('ICONMODE') ? ICONMODE : '')
            . PHP_EOL;

        echo "BRAND_CODE_SOURCE = "
            . (defined('BRAND_CODE_SOURCE') ? BRAND_CODE_SOURCE : '')
            . PHP_EOL;

        echo "TITLE_INDEX = "
            . (defined('TITLE_INDEX') ? TITLE_INDEX : '')
            . PHP_EOL;

        echo "DEVICE_TYPE_10 = "
            . (defined('DEVICE_TYPE_10') ? DEVICE_TYPE_10 : '')
            . PHP_EOL;

        echo PHP_EOL
            . "---- WWW_DATA_LS_CHECK ----"
            . PHP_EOL;

        echo "CHECK_OK = "
            . (
                defined('BRAND_DIRECTORY_CHECK_OK')
                && BRAND_DIRECTORY_CHECK_OK
                    ? 'true'
                    : 'false'
            )
            . PHP_EOL;

        echo "PHP_EFFECTIVE_USER_AT_CHECK = "
            . (
                defined('BRAND_DIRECTORY_CHECK_USER')
                    ? BRAND_DIRECTORY_CHECK_USER
                    : ''
            )
            . PHP_EOL;

        echo "COMMAND = "
            . (
                defined('BRAND_DIRECTORY_CHECK_COMMAND')
                    ? BRAND_DIRECTORY_CHECK_COMMAND
                    : ''
            )
            . PHP_EOL;

        echo "EXIT_CODE = "
            . (
                defined('BRAND_DIRECTORY_CHECK_EXIT_CODE')
                    ? BRAND_DIRECTORY_CHECK_EXIT_CODE
                    : -1
            )
            . PHP_EOL;

        echo "OUTPUT = " . PHP_EOL;
        echo defined('BRAND_DIRECTORY_CHECK_OUTPUT')
            ? BRAND_DIRECTORY_CHECK_OUTPUT
            : '';
        echo PHP_EOL;

        echo PHP_EOL
            . "---- BRAND_DIRECTORY_ACCESS ----"
            . PHP_EOL;

        $brandDirectories = [
            '/home/kls',
            '/home/kls/project',
            '/home/kls/project/system',
            '/home/kls/project/system/ltver',
            '/home/kls/project/system/curver',
        ];

        foreach ($brandDirectories as $directory) {
            clearstatcache(true, $directory);

            echo "PATH = " . $directory . PHP_EOL;
            echo "  EXISTS = "
                . (file_exists($directory) ? 'true' : 'false')
                . PHP_EOL;
            echo "  IS_DIR = "
                . (is_dir($directory) ? 'true' : 'false')
                . PHP_EOL;
            echo "  READABLE = "
                . (is_readable($directory) ? 'true' : 'false')
                . PHP_EOL;

            if (
                is_dir($directory)
                && is_readable($directory)
            ) {
                $files = @scandir($directory);

                echo "  FILES = "
                    . (
                        is_array($files)
                            ? implode(
                                ', ',
                                array_values(
                                    array_filter(
                                        $files,
                                        static function ($file) {
                                            return $file !== '.'
                                                && $file !== '..';
                                        }
                                    )
                                )
                            )
                            : 'scandir failed'
                    )
                    . PHP_EOL;
            }
        }

        echo PHP_EOL
            . "ACL_FIX_COMMAND = "
            . "sudo php /var/www/html/idas/app/config/config.php --fix-brand-acl"
            . PHP_EOL;

        echo PHP_EOL . "---- BRAND_CODE_DEBUG ----" . PHP_EOL;
        echo defined('BRAND_CODE_DEBUG')
            ? BRAND_CODE_DEBUG
            : '';

        $qrConfig = $this->getQrCodeConfig(
            $this->getBrandCode()
        );

        echo PHP_EOL . PHP_EOL . "---- QR_CODE_DEBUG ----" . PHP_EOL;
        echo "QR_CODE_SOURCE = "
            . ($qrConfig['source'] ?? '')
            . PHP_EOL;
        echo "QR_CODE_LOCAL_PATH = "
            . ($qrConfig['local_path'] ?? '')
            . PHP_EOL;
        echo "QR_CODE_IMAGE = "
            . ($qrConfig['image'] ?? '')
            . PHP_EOL;
        echo "QR_CODE_TARGET_URL = "
            . ($qrConfig['target_url'] ?? '')
            . PHP_EOL;
        echo "QR_CODE_ERROR = "
            . ($qrConfig['error'] ?? '')
            . PHP_EOL;
    }

    /**
     * Tool DB 診斷：
     * ?url=Tools/db_debug
     */
    public function db_debug()
    {
        header(
            'Content-Type: text/plain; charset=utf-8'
        );

        $debug = method_exists(
            $this->ToolModel,
            'GetDatabaseDebug'
        )
            ? $this->ToolModel->GetDatabaseDebug()
            : [
                'connected' => false,
                'last_error' =>
                    'GetDatabaseDebug() is unavailable.'
            ];

        echo 'TOOL_DB_CONNECTED = '
            . (!empty($debug['connected'])
                ? 'true'
                : 'false')
            . PHP_EOL;

        echo 'TOOL_DB_PATH = '
            . ($debug['database_path'] ?? '')
            . PHP_EOL;

        echo 'TOOL_DB_ERROR = '
            . ($debug['last_error'] ?? '')
            . PHP_EOL;

        echo 'TOOL_DB_TABLES = '
            . implode(
                ', ',
                (array)($debug['tables'] ?? [])
            )
            . PHP_EOL;

        echo 'RESOLVED_TABLES = '
            . json_encode(
                $debug['resolved_tables'] ?? [],
                JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
            )
            . PHP_EOL;

        $expectedPath = '/var/www/html/database/tccdev.db';
        clearstatcache(true, $expectedPath);

        echo 'TOOL_DB_FILE_EXISTS = '
            . (is_file($expectedPath) ? 'true' : 'false')
            . PHP_EOL;

        echo 'TOOL_DB_FILE_READABLE = '
            . (is_readable($expectedPath) ? 'true' : 'false')
            . PHP_EOL;

        echo 'TOOL_DB_FILE_SIZE = '
            . (is_file($expectedPath)
                ? (string)@filesize($expectedPath)
                : '0')
            . PHP_EOL;

        echo 'PDO_DRIVERS = '
            . implode(', ', PDO::getAvailableDrivers())
            . PHP_EOL;

        echo PHP_EOL;
        echo 'Expected DB:' . PHP_EOL;
        echo '/var/www/html/database/tccdev.db'
            . PHP_EOL;

        echo PHP_EOL;
        echo 'Expected accessor:' . PHP_EOL;
        echo 'Database::getDb_tools()'
            . PHP_EOL;
    }


    public function getMacAddress()
    {
        if (PHP_OS_FAMILY == 'Linux') {
            $output = shell_exec("ip link show");

            preg_match('/link\/ether (\w{2}:\w{2}:\w{2}:\w{2}:\w{2}:\w{2})/', $output, $matches);
            if (!empty($matches)) {
                return strtoupper($matches[1]);
            }

            return false;
        }

        $MAC = exec('getmac');
        $MAC = strtok($MAC, ' ');
        $MAC = str_replace('-', ':', $MAC);
        return $MAC;
    }

    public function getIp()
    {
        if (PHP_OS_FAMILY == 'Linux') {
            $Ips = trim(shell_exec("/sbin/ip -o -4 addr list | awk '{print $4}' | cut -d/ -f1"));
            $Ip = array_values(array_filter(explode(PHP_EOL, $Ips)));

            // 原本直接取 $Ip[1]，若只有一張網卡會 undefined。保留優先第 2 筆，沒有則取第 1 筆。
            return strtoupper($Ip[1] ?? $Ip[0] ?? '');
        }

        $host_addr = gethostname();
        $ip_addr = gethostbyname($host_addr);
        return strtoupper($ip_addr);
    }

    public function compareSchemaDifferences()
    {
        try {
            $db1 = new PDO('sqlite:/var/www/html/database/tcccon.db');
            $db2 = new PDO('sqlite:/var/www/html/database/idas_data.db');

            $stmt1 = $db1->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            $stmt2 = $db2->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");

            $tables1 = $stmt1->fetchAll(PDO::FETCH_COLUMN);
            $tables2 = $stmt2->fetchAll(PDO::FETCH_COLUMN);

            $onlyInTcccon = array_diff($tables1, $tables2);
            $onlyInIdas   = array_diff($tables2, $tables1);
            $commonTables = array_intersect($tables1, $tables2);

            echo "<pre>";

            // ✅ 顯示獨有的表
            if (!empty($onlyInTcccon)) {
                echo "📂 Tables only in tcccon.db:\n";
                foreach ($onlyInTcccon as $t) {
                    echo "  - $t\n";
                }
                echo "\n";
            }

            if (!empty($onlyInIdas)) {
                echo "📂 Tables only in idas_data.db:\n";
                foreach ($onlyInIdas as $t) {
                    echo "  - $t\n";
                }
                echo "\n";
            }

            // ✅ 比較相同表格中的欄位差異
            foreach ($commonTables as $table) {
                $cols1 = $db1->query("PRAGMA table_info('$table')")->fetchAll(PDO::FETCH_ASSOC);
                $cols2 = $db2->query("PRAGMA table_info('$table')")->fetchAll(PDO::FETCH_ASSOC);

                $colList1 = array_map(function ($c) {
                    return $c['name'] . ' ' . strtoupper($c['type']);
                }, $cols1);

                $colList2 = array_map(function ($c) {
                    return $c['name'] . ' ' . strtoupper($c['type']);
                }, $cols2);

                $diff1 = array_diff($colList1, $colList2);
                $diff2 = array_diff($colList2, $colList1);

                if (!empty($diff1) || !empty($diff2)) {
                    echo "🔍 Schema difference in table: $table\n";

                    if (!empty($diff1)) {
                        echo "  🟥 Columns only in tcccon.db:\n";
                        foreach ($diff1 as $col) {
                            echo "    - $col\n";
                        }
                    }

                    if (!empty($diff2)) {
                        echo "  🟦 Columns only in idas_data.db:\n";
                        foreach ($diff2 as $col) {
                            echo "    - $col\n";
                        }
                    }

                    echo "\n";
                }
            }

            echo "✅ Schema comparison complete.\n</pre>";
        } catch (PDOException $e) {
            echo "<pre>❌ Error: " . $e->getMessage() . "</pre>";
        }
    }
}
?>

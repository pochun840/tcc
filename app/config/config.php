<?php

//sudo chmod -R 777 /var/www/html/idas
//sudo chmod -R 777 /var/www/html/database
//sudo rm -rf /var/www/html/database/itccdev.db
//sudo chown -R www-data:www-data /var/www/html/upload_status.php
//12345678rd
//sudo chmod -R 777 /var/www/html/idas_update.php

//sudo chmod -R 777 /var/www/html/upload.php idas_update.php
// App 根目錄，這是引入 app 資料夾裡的資源用的
define('APPROOT', dirname(dirname(__FILE__)) . '/');

// URL 根目錄，這是引入 public 資料夾裡的資源，或是頁面跳轉時用的
define('URLROOT', '../public/');

// 網站名稱
define('SITENAME', 'iDAS DEVICE');

// iDAS連線模式 0:單機版 1:連線版
define('IDASMODE', '1');

// 設定語言狀態
$language = array(
    0 => array('简中','zh-cn'),
    1 => array('繁中','zh-tw'),
    2 => array('English','en-us'),
);
define('LANGUAGE', $language);

// 每次刷新都取最新時間，避免快取
define('ASSET_VERSION', date('YmdHi'));

/*
 * Tool Information QR Code 設定。
 *
 * 本機圖片小於指定尺寸時，視為無效圖片。
 * 例如誤放成 1x1 transparent PNG 時，不再直接顯示。
 */
if (!defined('QR_CODE_MIN_WIDTH')) {
    define('QR_CODE_MIN_WIDTH', 64);
}

if (!defined('QR_CODE_MIN_HEIGHT')) {
    define('QR_CODE_MIN_HEIGHT', 64);
}

if (!defined('QR_CODE_MIN_FILE_SIZE')) {
    define('QR_CODE_MIN_FILE_SIZE', 256);
}

/*
 * 本機 QR 圖片無效時，是否使用遠端 QR 產生服務。
 * 正式設備若完全離線，可改成 false。
 */
if (!defined('QR_CODE_REMOTE_FALLBACK')) {
    define('QR_CODE_REMOTE_FALLBACK', true);
}

if (!defined('QR_CODE_REMOTE_ENDPOINT')) {
    define(
        'QR_CODE_REMOTE_ENDPOINT',
        'https://quickchart.io/qr'
    );
}

define('CONTROLLER_IP', '127.0.0.1');


/**
 * 將品牌路徑相關資料夾權限設定為 0755。
 *
 * 執行方式：
 *
 * sudo php /var/www/html/idas/app/config/config.php --fix-brand-permission-755
 *
 * 一般網頁請求不會執行此流程。
 */
function idas_fix_brand_permission_755_from_config()
{
    if (PHP_OS_FAMILY !== 'Linux') {
        fwrite(
            STDERR,
            "ERROR: This operation only supports Linux.\n"
        );
        return 1;
    }

    if (PHP_SAPI !== 'cli') {
        fwrite(
            STDERR,
            "ERROR: This operation can only run from PHP CLI.\n"
        );
        return 1;
    }

    if (
        !function_exists('posix_geteuid')
        || posix_geteuid() !== 0
    ) {
        fwrite(
            STDERR,
            "ERROR: Please run as root:\n"
            . "sudo php "
            . __FILE__
            . " --fix-brand-permission-755\n"
        );
        return 1;
    }

    $directories = array(
        '/home/kls',
        '/home/kls/project',
        '/home/kls/project/system',
        '/home/kls/project/system/ltver',
    );

    foreach ($directories as $directory) {
        clearstatcache(true, $directory);

        if (!is_dir($directory)) {
            fwrite(
                STDERR,
                "ERROR: Directory does not exist: "
                . $directory
                . "\n"
            );
            return 1;
        }

        if (!@chmod($directory, 0755)) {
            fwrite(
                STDERR,
                "ERROR: chmod 0755 failed: "
                . $directory
                . "\n"
            );
            return 1;
        }

        clearstatcache(true, $directory);

        $permissions = @fileperms($directory);

        echo sprintf(
            "OK: %s => %04o\n",
            $directory,
            $permissions !== false
                ? ($permissions & 0777)
                : 0
        );
    }

    /*
     * 使用 runuser 切換成 www-data 驗證是否能讀取 ltver。
     */
    $runuser = '';

    foreach (
        array(
            '/usr/sbin/runuser',
            '/usr/bin/runuser',
            '/sbin/runuser',
            '/bin/runuser',
        ) as $candidate
    ) {
        if (
            is_file($candidate)
            && is_executable($candidate)
        ) {
            $runuser = $candidate;
            break;
        }
    }

    $ls = '';

    foreach (
        array(
            '/usr/bin/ls',
            '/bin/ls',
        ) as $candidate
    ) {
        if (
            is_file($candidate)
            && is_executable($candidate)
        ) {
            $ls = $candidate;
            break;
        }
    }

    if ($runuser === '' || $ls === '') {
        echo "WARNING: Permission was changed, "
            . "but verification command was not found."
            . PHP_EOL;

        return 0;
    }

    $arguments = array(
        $runuser,
        '-u',
        'www-data',
        '--',
        $ls,
        '-la',
        '/home/kls/project/system/ltver',
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

    echo PHP_EOL;
    echo "Verify as www-data:" . PHP_EOL;
    echo implode(PHP_EOL, $output) . PHP_EOL;

    if ($exitCode !== 0) {
        fwrite(
            STDERR,
            "ERROR: www-data cannot read ltver. "
            . "Exit code: "
            . $exitCode
            . "\n"
        );

        return $exitCode;
    }

    echo PHP_EOL;
    echo "SUCCESS: Brand directories were set to 0755 "
        . "and www-data can read ltver."
        . PHP_EOL;

    return 0;
}

/*
 * 只有 CLI 明確帶入 --fix-brand-permission-755 時才執行。
 */
if (
    PHP_SAPI === 'cli'
    && isset($argv)
    && is_array($argv)
    && in_array(
        '--fix-brand-permission-755',
        $argv,
        true
    )
) {
    exit(
        idas_fix_brand_permission_755_from_config()
    );
}


/**
 * 一般網頁載入 config.php 時，自動檢查並嘗試修正品牌目錄權限。
 *
 * 前提：
 * /etc/sudoers.d/idas-brand-permission 必須允許 www-data 免密碼執行：
 *
 * /usr/bin/php /var/www/html/idas/app/config/config.php --fix-brand-permission-755
 *
 * 權限原本正常時不會執行 sudo。
 */
function idas_auto_fix_brand_permission_755(&$debug = array())
{
    $directory =
        '/home/kls/project/system/ltver';

    $result = array(
        'status' => 'skipped',
        'command' => '',
        'exit_code' => -1,
        'output' => array(),
    );

    if (PHP_OS_FAMILY !== 'Linux') {
        $debug[] =
            'auto permission fix: skipped (not Linux)';

        return $result;
    }

    clearstatcache(true, $directory);

    if (is_array(@scandir($directory))) {
        $result['status'] = 'ready';

        $debug[] =
            'auto permission fix: not required';

        return $result;
    }

    if (!function_exists('exec')) {
        $result['status'] = 'failed';

        $debug[] =
            'auto permission fix: exec() unavailable';

        return $result;
    }

    $disabledFunctions = array_values(
        array_filter(
            array_map(
                'trim',
                explode(
                    ',',
                    (string)ini_get('disable_functions')
                )
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
        $result['status'] = 'failed';

        $debug[] =
            'auto permission fix: exec() disabled';

        return $result;
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
        $result['status'] = 'failed';

        $debug[] =
            'auto permission fix: sudo or php not found';

        return $result;
    }

    $arguments = array(
        $sudo,
        '-n',
        $php,
        __FILE__,
        '--fix-brand-permission-755',
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

    $result['command'] = $command;
    $result['exit_code'] = $exitCode;
    $result['output'] = $output;

    $debug[] =
        'auto permission fix command: '
        . $command;

    $debug[] =
        'auto permission fix exit code: '
        . $exitCode;

    $debug[] =
        'auto permission fix output: '
        . (
            !empty($output)
                ? implode(' | ', $output)
                : '(empty)'
        );

    clearstatcache(true, $directory);

    if (
        $exitCode === 0
        && is_array(@scandir($directory))
    ) {
        $result['status'] = 'fixed';

        $debug[] =
            'auto permission fix result: success';

        return $result;
    }

    $result['status'] = 'failed';

    $debug[] =
        'auto permission fix result: failed';

    error_log(
        '[Brand Permission] '
        . implode(' | ', $debug)
    );

    return $result;
}

/**
 * 一次性設定 www-data 讀取品牌版本目錄的 ACL。
 *
 * 僅允許以下方式執行：
 *
 * sudo php /var/www/html/idas/app/config/config.php --fix-brand-acl
 *
 * 一般 Apache / 網頁請求不會執行此流程。
 */
function idas_fix_brand_acl_from_config()
{
    if (PHP_OS_FAMILY !== 'Linux') {
        fwrite(
            STDERR,
            "ERROR: Brand ACL setup only supports Linux.\n"
        );
        return 1;
    }

    if (PHP_SAPI !== 'cli') {
        fwrite(
            STDERR,
            "ERROR: Brand ACL setup can only run from PHP CLI.\n"
        );
        return 1;
    }

    if (
        !function_exists('posix_geteuid')
        || posix_geteuid() !== 0
    ) {
        fwrite(
            STDERR,
            "ERROR: Please run as root:\n"
            . "sudo php "
            . __FILE__
            . " --fix-brand-acl\n"
        );
        return 1;
    }

    if (!function_exists('exec')) {
        fwrite(
            STDERR,
            "ERROR: PHP exec() is unavailable.\n"
        );
        return 1;
    }

    $disabledFunctions = array_values(
        array_filter(
            array_map(
                'trim',
                explode(
                    ',',
                    (string)ini_get('disable_functions')
                )
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
        fwrite(
            STDERR,
            "ERROR: PHP exec() is disabled.\n"
        );
        return 1;
    }

    $setfacl = '';

    foreach (
        array(
            '/usr/bin/setfacl',
            '/bin/setfacl',
        ) as $candidate
    ) {
        if (
            is_file($candidate)
            && is_executable($candidate)
        ) {
            $setfacl = $candidate;
            break;
        }
    }

    if ($setfacl === '') {
        fwrite(
            STDERR,
            "ERROR: setfacl was not found.\n"
            . "Install it first:\n"
            . "sudo apt install -y acl\n"
        );
        return 1;
    }

    $requiredDirectories = array(
        '/home/kls',
        '/home/kls/project',
        '/home/kls/project/system',
        '/home/kls/project/system/ltver',
        '/home/kls/project/system/curver',
    );

    foreach ($requiredDirectories as $directory) {
        if (!is_dir($directory)) {
            fwrite(
                STDERR,
                "ERROR: Directory does not exist: "
                . $directory
                . "\n"
            );
            return 1;
        }
    }

    $commands = array(
        // 上層目錄只授予穿越權限。
        array(
            $setfacl,
            '-m',
            'u:www-data:--x',
            '/home/kls',
        ),
        array(
            $setfacl,
            '-m',
            'u:www-data:--x',
            '/home/kls/project',
        ),
        array(
            $setfacl,
            '-m',
            'u:www-data:--x',
            '/home/kls/project/system',
        ),

        // 版本目錄及現有檔案授予唯讀權限。
        array(
            $setfacl,
            '-R',
            '-m',
            'u:www-data:rX',
            '/home/kls/project/system/ltver',
        ),
        array(
            $setfacl,
            '-R',
            '-m',
            'u:www-data:rX',
            '/home/kls/project/system/curver',
        ),

        // 未來新增檔案自動繼承唯讀 ACL。
        array(
            $setfacl,
            '-d',
            '-m',
            'u:www-data:rX',
            '/home/kls/project/system/ltver',
        ),
        array(
            $setfacl,
            '-d',
            '-m',
            'u:www-data:rX',
            '/home/kls/project/system/curver',
        ),
    );

    foreach ($commands as $arguments) {
        $command = implode(
            ' ',
            array_map(
                'escapeshellarg',
                $arguments
            )
        );

        echo "Execute: " . $command . PHP_EOL;

        $output = array();
        $exitCode = 1;

        exec(
            $command . ' 2>&1',
            $output,
            $exitCode
        );

        foreach ($output as $line) {
            echo "  " . $line . PHP_EOL;
        }

        if ($exitCode !== 0) {
            fwrite(
                STDERR,
                "ERROR: ACL command failed with exit code "
                . $exitCode
                . "\n"
            );
            return $exitCode;
        }
    }

    /*
     * 使用 runuser 切換成 www-data 驗證 PHP 是否能讀取。
     */
    $runuser = '';

    foreach (
        array(
            '/usr/sbin/runuser',
            '/usr/bin/runuser',
            '/sbin/runuser',
            '/bin/runuser',
        ) as $candidate
    ) {
        if (
            is_file($candidate)
            && is_executable($candidate)
        ) {
            $runuser = $candidate;
            break;
        }
    }

    $verifyCode = <<<'PHP'
$directories = [
    "/home/kls/project/system/ltver",
    "/home/kls/project/system/curver",
];

$success = true;

foreach ($directories as $directory) {
    clearstatcache(true, $directory);

    echo "Directory: {$directory}\n";
    echo "  is_dir: "
        . (is_dir($directory) ? "true" : "false")
        . "\n";
    echo "  is_readable: "
        . (is_readable($directory) ? "true" : "false")
        . "\n";

    $files = @scandir($directory);

    if (!is_array($files)) {
        echo "  files: scandir failed\n";
        $success = false;
        continue;
    }

    $files = array_values(
        array_filter(
            $files,
            static function ($file) {
                return $file !== "."
                    && $file !== "..";
            }
        )
    );

    echo "  files: "
        . implode(", ", $files)
        . "\n";

    $brandFound = false;

    foreach ($files as $fileName) {
        if (
            preg_match(
                "/^(BF\\d{2})(?:[-_.]|$)/i",
                $fileName,
                $matches
            )
        ) {
            echo "  brand: "
                . strtoupper($matches[1])
                . "\n";

            $brandFound = true;
            break;
        }
    }

    if (!$brandFound) {
        echo "  brand: not found\n";
        $success = false;
    }
}

exit($success ? 0 : 1);
PHP;

    if ($runuser !== '') {
        echo PHP_EOL;
        echo "Verify as www-data:" . PHP_EOL;

        $verifyCommand = implode(
            ' ',
            array_map(
                'escapeshellarg',
                array(
                    $runuser,
                    '-u',
                    'www-data',
                    '--',
                    PHP_BINARY,
                    '-r',
                    $verifyCode,
                )
            )
        );

        passthru(
            $verifyCommand,
            $verifyExitCode
        );

        if ($verifyExitCode !== 0) {
            fwrite(
                STDERR,
                "ERROR: www-data verification failed.\n"
            );
            return $verifyExitCode;
        }
    } else {
        echo "WARNING: runuser not found; "
            . "www-data verification skipped."
            . PHP_EOL;
    }

    echo PHP_EOL;
    echo "SUCCESS: Brand directory ACL was configured."
        . PHP_EOL;

    echo "Open:"
        . PHP_EOL
        . "http://CONTROLLER_IP/idas/public/"
        . "?url=Tools/brand_debug"
        . PHP_EOL;

    return 0;
}

/*
 * 只有 CLI 明確帶入 --fix-brand-acl 時才執行。
 * 網頁載入 config.php 時不會進入。
 */
if (
    PHP_SAPI === 'cli'
    && isset($argv)
    && is_array($argv)
    && in_array(
        '--fix-brand-acl',
        $argv,
        true
    )
) {
    exit(
        idas_fix_brand_acl_from_config()
    );
}


/**
 * 以 www-data 身分檢查品牌目錄內容。
 *
 * Apache 網頁本身通常已經是 www-data，因此直接執行 ls。
 * root CLI 測試時則使用 runuser 切換成 www-data。
 * 這個函式只做檢查，不修改任何檔案或目錄權限。
 */
function idas_check_brand_directory_as_www_data(&$debug = array())
{
    $directory = '/home/kls/project/system/ltver';

    $result = array(
        'success' => false,
        'effective_user' => '',
        'command' => '',
        'exit_code' => -1,
        'output' => array(),
    );

    if (PHP_OS_FAMILY !== 'Linux') {
        $debug[] = 'www-data ls check: skipped (not Linux)';
        return $result;
    }

    if (!function_exists('exec')) {
        $debug[] = 'www-data ls check: exec() unavailable';
        return $result;
    }

    $disabledFunctions = array_values(
        array_filter(
            array_map(
                'trim',
                explode(
                    ',',
                    (string)ini_get('disable_functions')
                )
            )
        )
    );

    if (in_array('exec', $disabledFunctions, true)) {
        $debug[] = 'www-data ls check: exec() disabled';
        return $result;
    }

    $lsBinary = '';

    foreach (
        array(
            '/usr/bin/ls',
            '/bin/ls',
        ) as $candidate
    ) {
        if (
            is_file($candidate)
            && is_executable($candidate)
        ) {
            $lsBinary = $candidate;
            break;
        }
    }

    if ($lsBinary === '') {
        $debug[] = 'www-data ls check: ls command not found';
        return $result;
    }

    $effectiveUid = function_exists('posix_geteuid')
        ? @posix_geteuid()
        : null;

    $effectiveUser = '';

    if (
        $effectiveUid !== null
        && function_exists('posix_getpwuid')
    ) {
        $userInfo = @posix_getpwuid($effectiveUid);

        if (is_array($userInfo)) {
            $effectiveUser = (string)($userInfo['name'] ?? '');
        }
    }

    $result['effective_user'] = $effectiveUser;

    $arguments = array();

    /*
     * 一般 Apache 請求本來就是 www-data，直接 ls 即可。
     */
    if ($effectiveUser === 'www-data') {
        $arguments = array(
            $lsBinary,
            '-la',
            $directory,
        );
    } elseif ($effectiveUid === 0) {
        /*
         * root CLI 執行 config.php 時，使用 runuser 模擬 www-data。
         */
        $runuser = '';

        foreach (
            array(
                '/usr/sbin/runuser',
                '/usr/bin/runuser',
                '/sbin/runuser',
                '/bin/runuser',
            ) as $candidate
        ) {
            if (
                is_file($candidate)
                && is_executable($candidate)
            ) {
                $runuser = $candidate;
                break;
            }
        }

        if ($runuser === '') {
            $debug[] = 'www-data ls check: runuser not found';
            return $result;
        }

        $arguments = array(
            $runuser,
            '-u',
            'www-data',
            '--',
            $lsBinary,
            '-la',
            $directory,
        );
    } else {
        /*
         * 非 root CLI 環境無法安全切換帳號。
         * 使用 sudo -n 嘗試，不等待密碼，失敗結果會顯示在 Debug。
         */
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

        if ($sudo === '') {
            $debug[] = 'www-data ls check: sudo not found';
            return $result;
        }

        $arguments = array(
            $sudo,
            '-n',
            '-u',
            'www-data',
            '--',
            $lsBinary,
            '-la',
            $directory,
        );
    }

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

    $result['command'] = $command;
    $result['exit_code'] = $exitCode;
    $result['output'] = $output;
    $result['success'] = ($exitCode === 0);

    $debug[] = 'www-data ls check user: '
        . ($effectiveUser !== '' ? $effectiveUser : 'unknown');
    $debug[] = 'www-data ls check command: ' . $command;
    $debug[] = 'www-data ls check exit code: ' . $exitCode;
    $debug[] = 'www-data ls check output: '
        . (!empty($output)
            ? implode(' | ', $output)
            : '(empty)');

    return $result;
}

// 手動指定品牌碼。這台目前 ltver 檔案為 BF05-1001-O-J60004.hex，先固定 BF05。
// 之後若要恢復自動偵測，改成空字串：define('BRAND_CODE_FORCE', '');
if (!defined('BRAND_CODE_FORCE')) {
    define('BRAND_CODE_FORCE', '');
}

/**
 * 從指定資料夾讀取品牌代碼。
 * 支援檔名格式：BF05-1001-O-J60004.hex、BF01-xxxx、BF04_xxxx、BF06.xxx。
 * 這版會同時嘗試 scandir / glob / shell ls，並記錄 Debug 資訊。
 */
function find_brand_code_in_directory($directory, &$debug = array())
{
    $debug[] = "check directory: " . $directory;

    if (!is_dir($directory)) {
        $debug[] = "  - is_dir: false";
        $debug[] = "  - www-data cannot access this path";
        $debug[] = "  - run: sudo php "
            . __FILE__
            . " --fix-brand-acl";
        return false;
    }

    $debug[] = "  - is_dir: true";
    $debug[] = "  - is_readable: " . (is_readable($directory) ? 'true' : 'false');

    $fileList = array();

    // 方法 1：scandir
    $scanList = @scandir($directory);
    if ($scanList !== false) {
        $debug[] = "  - scandir: ok";
        $fileList = array_merge($fileList, $scanList);
    } else {
        $debug[] = "  - scandir: false";
    }

    // 方法 2：glob fallback
    $globList = @glob(rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*');
    if (is_array($globList) && !empty($globList)) {
        $debug[] = "  - glob: ok (" . count($globList) . ")";
        foreach ($globList as $path) {
            $fileList[] = basename($path);
        }
    } else {
        $debug[] = "  - glob: empty/false";
    }

    // 方法 3：Linux shell fallback（某些環境 scandir/glob 被限制時用）
    if (function_exists('shell_exec')) {
        $cmd = 'ls -1 ' . escapeshellarg($directory) . ' 2>/dev/null';
        $shellOutput = @shell_exec($cmd);
        if (is_string($shellOutput) && trim($shellOutput) !== '') {
            $shellFiles = preg_split('/\r\n|\r|\n/', trim($shellOutput));
            $debug[] = "  - shell ls: ok (" . count($shellFiles) . ")";
            $fileList = array_merge($fileList, $shellFiles);
        } else {
            $debug[] = "  - shell ls: empty/false";
        }
    } else {
        $debug[] = "  - shell_exec: disabled";
    }

    // 清理 . / .. / 空值 / 重複
    $fileList = array_values(array_unique(array_filter($fileList, function ($fileName) {
        return $fileName !== '' && $fileName !== '.' && $fileName !== '..';
    })));

    if (empty($fileList)) {
        $debug[] = "  - file list: empty";
        return false;
    }

    // 新檔優先，取不到修改時間也不影響比對
    usort($fileList, function ($a, $b) use ($directory) {
        $timeA = @filemtime($directory . DIRECTORY_SEPARATOR . $a) ?: 0;
        $timeB = @filemtime($directory . DIRECTORY_SEPARATOR . $b) ?: 0;
        return $timeB <=> $timeA;
    });

    $debug[] = "  - files: " . implode(', ', $fileList);

    foreach ($fileList as $fileName) {
        // 標準格式：BF05-1001-O-J60004.hex、BF01-xxxx、BF04_xxxx、BF06.xxx
        if (preg_match('/^(BF\d{2})(?:[-_.]|$)/i', $fileName, $matches)) {
            $brandCode = strtoupper($matches[1]);
            $debug[] = "  - matched standard: {$fileName} => {$brandCode}";
            return $brandCode;
        }
    }

    foreach ($fileList as $fileName) {
        // fallback：檔名中只要含有 BFxx 也嘗試抓取
        if (preg_match('/(BF\d{2})/i', $fileName, $matches)) {
            $brandCode = strtoupper($matches[1]);
            $debug[] = "  - matched fallback: {$fileName} => {$brandCode}";
            return $brandCode;
        }
    }

    $debug[] = "  - matched: false";
    return false;
}

/**
 * 取得品牌代碼。
 * 主要讀 /home/kls/project/system/ltver，讀不到再 fallback /home/kls/project/system/curver。
 * 注意：不要因 PHP_OS_FAMILY 不是 Linux 就直接 return，避免測試環境/特殊環境誤判。
 */
function get_brand_code(&$source = '', &$debug = array())
{
    $debug[] = "PHP_OS_FAMILY: " . PHP_OS_FAMILY;

    // 手動覆寫：BRAND_CODE_FORCE 有值時優先使用，例如 BF05。
    $manualOverride = defined('BRAND_CODE_FORCE') ? trim((string)BRAND_CODE_FORCE) : '';
    if ($manualOverride !== '') {
        $source = 'manual override / BRAND_CODE_FORCE';
        $debug[] = "manual override / BRAND_CODE_FORCE: {$manualOverride}";
        return strtoupper($manualOverride);
    }

    $directories = array(
        '/home/kls/project/system/ltver',
        '/home/kls/project/system/curver',
    );

    foreach ($directories as $directory) {
        $brandCode = find_brand_code_in_directory($directory, $debug);
        if ($brandCode !== false) {
            $source = $directory;
            return $brandCode;
        }
    }

    $source = 'fallback BF01';
    return false;
}

/**
 * 將 get_brand_code() 結果整理成系統可用品牌碼。
 * 找不到品牌碼時固定回 BF01，避免 Tools 顯示 string(0) ""。
 */
function normalize_brand_code($brandCode)
{
    $brandCode = strtoupper(trim((string)$brandCode));

    $allowedBrandCodes = array('BF01', 'BF02', 'BF04', 'BF05', 'BF06', 'BF07');
    if (in_array($brandCode, $allowedBrandCodes, true)) {
        return $brandCode;
    }

    return 'BF01';
}

/**
 * 將品牌碼轉成既有 ICONMODE。
 */
function brand_code_to_icon_mode($brandCode)
{
    $map = array(
        'BF01' => '0', // Kilews / Windows default
        'BF02' => '2', // 上海
        'BF04' => '4', // MyTorq
        'BF05' => '5', // SUMAKE
        'BF06' => '6', // DELTA
        'BF07' => '7', // 白牌
    );

    return $map[$brandCode] ?? '0';
}

// 抓取 APP 的檔案名稱，判斷是哪一個品牌
$brand_debug = array();
$brand_source = '';

/*
 * 一般網頁載入時先自動檢查權限。
 * 只有 scandir() 失敗時，才透過 sudo -n 執行 CLI 修正入口。
 */
$brand_permission_auto_fix =
    idas_auto_fix_brand_permission_755(
        $brand_debug
    );

/*
 * 一般網頁載入 config.php 時，直接用目前的 www-data 身分執行 ls。
 * 這裡只做存取檢查，品牌仍由 get_brand_code() 掃描 ltver / curver 判斷。
 */
$brand_directory_check =
    idas_check_brand_directory_as_www_data(
        $brand_debug
    );

$brand_code_raw = get_brand_code($brand_source, $brand_debug);
$brand_code = normalize_brand_code($brand_code_raw);
$brand = brand_code_to_icon_mode($brand_code);

// Debug 常數：可到 Tools/brand_debug 查看
if (!defined('BRAND_CODE_RAW')) {
    define('BRAND_CODE_RAW', $brand_code_raw === false ? '' : $brand_code_raw);
}
if (!defined('BRAND_CODE_SOURCE')) {
    define('BRAND_CODE_SOURCE', $brand_source);
}
if (!defined('BRAND_CODE_DEBUG')) {
    define('BRAND_CODE_DEBUG', implode("\n", $brand_debug));
}

if (!defined('BRAND_PERMISSION_AUTO_STATUS')) {
    define(
        'BRAND_PERMISSION_AUTO_STATUS',
        (string)($brand_permission_auto_fix['status'] ?? '')
    );
}
if (!defined('BRAND_PERMISSION_AUTO_COMMAND')) {
    define(
        'BRAND_PERMISSION_AUTO_COMMAND',
        (string)($brand_permission_auto_fix['command'] ?? '')
    );
}
if (!defined('BRAND_PERMISSION_AUTO_EXIT_CODE')) {
    define(
        'BRAND_PERMISSION_AUTO_EXIT_CODE',
        (int)($brand_permission_auto_fix['exit_code'] ?? -1)
    );
}
if (!defined('BRAND_PERMISSION_AUTO_OUTPUT')) {
    define(
        'BRAND_PERMISSION_AUTO_OUTPUT',
        implode(
            "\n",
            (array)($brand_permission_auto_fix['output'] ?? array())
        )
    );
}

// www-data ls 檢查結果，供 Tools/brand_debug 顯示。
if (!defined('BRAND_DIRECTORY_CHECK_OK')) {
    define(
        'BRAND_DIRECTORY_CHECK_OK',
        !empty($brand_directory_check['success'])
    );
}
if (!defined('BRAND_DIRECTORY_CHECK_USER')) {
    define(
        'BRAND_DIRECTORY_CHECK_USER',
        (string)($brand_directory_check['effective_user'] ?? '')
    );
}
if (!defined('BRAND_DIRECTORY_CHECK_COMMAND')) {
    define(
        'BRAND_DIRECTORY_CHECK_COMMAND',
        (string)($brand_directory_check['command'] ?? '')
    );
}
if (!defined('BRAND_DIRECTORY_CHECK_EXIT_CODE')) {
    define(
        'BRAND_DIRECTORY_CHECK_EXIT_CODE',
        (int)($brand_directory_check['exit_code'] ?? -1)
    );
}
if (!defined('BRAND_DIRECTORY_CHECK_OUTPUT')) {
    define(
        'BRAND_DIRECTORY_CHECK_OUTPUT',
        implode(
            "\n",
            (array)($brand_directory_check['output'] ?? array())
        )
    );
}

// 給 Controller / View 使用：class method 內請讀 BRAND_CODE，不要直接讀 $brand_code
if (!defined('BRAND_CODE')) {
    define('BRAND_CODE', $brand_code);
}

// iDAS出貨版本 0:Kilews 2:上海 shanhai 4:MyTorque 5:晶元SUMAKE 6:DELTA 7:白牌
if (!defined('ICONMODE')) {
    define('ICONMODE', $brand);
}

switch (ICONMODE) {
    case '0': // Kilews
        define('ICON_NORMAL',       URLROOT.'img/192.png');
        define('ICON_NORMAL_APPLE', URLROOT.'img/60.png');
        define('ICON_AGENT',        URLROOT.'img/192.png');
        define('ICON_AGENT_APPLE',  URLROOT.'img/60.png');
        define('TITLE_INDEX',       'KILEWS');
        define('SUBTITLE_INDEX',    'iDAS FOR KL-TCC-M7');
        define('TITLE_AGENT',       'KILEWS IoT Agent');
        define('DEVICE_TYPE_10',    'KL-TCC');
        break;

    case '4': // MyTorque
        define('ICON_NORMAL',       URLROOT.'img/MY-icon/yellow-192x192.png');
        define('ICON_NORMAL_APPLE', URLROOT.'img/MY-icon/yellow-60x60.png');
        define('ICON_AGENT',        URLROOT.'img/MY-icon/blue-192x192.png');
        define('ICON_AGENT_APPLE',  URLROOT.'img/MY-icon/blue-60x60.png');
        define('TITLE_INDEX',       'MYTORQ');
        define('SUBTITLE_INDEX',    'iDAS FOR MY-SIRIUS');
        define('TITLE_AGENT',       'MYTORQ IoT Agent');
        define('DEVICE_TYPE_10',    'MY-SIRIUS');
        break;

    case '2': // 上海 shanhai
        define('ICON_NORMAL',       URLROOT.'img/192.png');
        define('ICON_NORMAL_APPLE', URLROOT.'img/60.png');
        define('ICON_AGENT',        URLROOT.'img/192.png');
        define('ICON_AGENT_APPLE',  URLROOT.'img/60.png');
        define('TITLE_INDEX',       'KILEWS');
        define('SUBTITLE_INDEX',    'iDAS FOR KILEWS');
        define('TITLE_AGENT',       'KILEWS IoT Agent');
        define('DEVICE_TYPE_10',    'KL-EPIC');
        break;

    case '5': // 晶元SUMAKE
        define('ICON_NORMAL',       URLROOT.'img/Sumake_icon/a192x192.png');
        define('ICON_NORMAL_APPLE', URLROOT.'img/Sumake_icon/a60x60.png');
        define('ICON_AGENT',        URLROOT.'img/Sumake_icon/a192x192.png');
        define('ICON_AGENT_APPLE',  URLROOT.'img/Sumake_icon/a60x60.png');
        define('TITLE_INDEX',       'SUMAKE');
        define('SUBTITLE_INDEX',    'iDAS FOR SCT-C2');
        define('TITLE_AGENT',       'SUMAKE IoT Agent');
        define('DEVICE_TYPE_10',    'SCT-C2');
        break;

    case '6': // DELTA
        define('ICON_NORMAL',       URLROOT.'img/192.png');
        define('ICON_NORMAL_APPLE', URLROOT.'img/60.png');
        define('ICON_AGENT',        URLROOT.'img/192.png');
        define('ICON_AGENT_APPLE',  URLROOT.'img/60.png');
        define('TITLE_INDEX',       'DELTA');
        define('SUBTITLE_INDEX',    'iDAS FOR XTCA1');
        define('TITLE_AGENT',       'DELTA IoT Agent');
        define('DEVICE_TYPE_10',    'XTCA1');
        break;

    case '7': // 白牌
        define('ICON_NORMAL',       URLROOT.'img/192.png');
        define('ICON_NORMAL_APPLE', URLROOT.'img/60.png');
        define('ICON_AGENT',        URLROOT.'img/192.png');
        define('ICON_AGENT_APPLE',  URLROOT.'img/60.png');
        define('TITLE_INDEX',       '');
        define('SUBTITLE_INDEX',    'iDAS FOR OPT-GK TRS1');
        define('TITLE_AGENT',       'IoT Agent');
        define('DEVICE_TYPE_10',    'OPT-GK TRS1');
        break;

    default:
        define('ICON_NORMAL',       URLROOT.'img/192.png');
        define('ICON_NORMAL_APPLE', URLROOT.'img/60.png');
        define('ICON_AGENT',        URLROOT.'img/192.png');
        define('ICON_AGENT_APPLE',  URLROOT.'img/60.png');
        define('TITLE_INDEX',       'KILEWS');
        define('SUBTITLE_INDEX',    'iDAS FOR KL-TCC-M7');
        define('TITLE_AGENT',       'KILEWS IoT Agent');
        define('DEVICE_TYPE_10',    'KL-TCC');
        break;
}

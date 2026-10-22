<?php

class TccDebug
{
    private const DBS = [
        'iDAS Runtime DB' => '/var/www/html/database/idas_data.db',
        'TCC Controller DB' => '/var/www/html/database/tcccon.db',
        'TCC Device DB' => '/var/www/html/database/tccdev.db',
        'Login DB' => '/var/www/html/database/das.db',
        'iTCC Device DB' => '/var/www/html/database/itccdev.db',
    ];

    public static function collect(): array
    {
        $data = [
            'generated_at' => date('Y-m-d H:i:s'),
            'php' => self::phpRuntime(),
            'syntax' => self::phpSyntax(),
            'files' => self::files(),
            'dbs' => self::dbs(),
            'consistency' => self::consistency(),
            'disks' => self::disks(),
            'services' => self::services(),
            'versions' => self::versions(),
            'logs' => self::logs(),
            'functions' => self::functionChecks(),
        ];

        $data['summary'] = self::summary($data);
        $data['issues'] = [];
        $data['warnings'] = [];

        foreach ($data['summary'] as $item) {
            if (($item['status'] ?? '') === 'ng') {
                $data['issues'][] = ($item['label'] ?? 'Unknown') . ' failed.';
            } elseif (($item['status'] ?? '') === 'warning') {
                $data['warnings'][] = ($item['label'] ?? 'Unknown') . ' has a warning.';
            }
        }

        $data['overall_ok'] = empty($data['issues']);
        return $data;
    }

    private static function root(): string
    {
        return realpath(__DIR__ . '/../..') ?: (__DIR__ . '/../..');
    }

    private static function canExec(): bool
    {
        if (!function_exists('exec')) return false;
        $disabled = array_filter(array_map('trim', explode(',', (string)ini_get('disable_functions'))));
        return !in_array('exec', $disabled, true);
    }

    private static function phpRuntime(): array
    {
        return [
            'version' => PHP_VERSION,
            'version_id' => PHP_VERSION_ID,
            'sapi' => PHP_SAPI,
            'os' => PHP_OS_FAMILY . ' / ' . php_uname('m'),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? '',
            'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? '',
            'script_filename' => $_SERVER['SCRIPT_FILENAME'] ?? '',
            'php_ini_loaded' => php_ini_loaded_file() ?: '',
            'php_ini_scanned' => php_ini_scanned_files() ?: '',
            'display_errors' => ini_get('display_errors'),
            'display_startup_errors' => ini_get('display_startup_errors'),
            'log_errors' => ini_get('log_errors'),
            'error_log' => ini_get('error_log'),
            'error_reporting' => error_reporting(),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'max_input_time' => ini_get('max_input_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'default_socket_timeout' => ini_get('default_socket_timeout'),
            'timezone' => date_default_timezone_get(),
            'extensions' => [
                'PDO' => extension_loaded('PDO'),
                'pdo_sqlite' => extension_loaded('pdo_sqlite'),
                'SQLite3' => extension_loaded('sqlite3'),
                'curl' => extension_loaded('curl'),
                'mbstring' => extension_loaded('mbstring'),
                'json' => extension_loaded('json'),
                'openssl' => extension_loaded('openssl'),
            ],
        ];
    }

    private static function phpSyntax(): array
    {
        $result = [
            'available' => false,
            'binary' => '',
            'checked' => 0,
            'ok' => 0,
            'ng' => 0,
            'failed' => [],
            'error' => '',
        ];

        if (!self::canExec()) {
            $result['error'] = 'exec unavailable';
            return $result;
        }

        $php = '';
        foreach ([defined('PHP_BINARY') ? PHP_BINARY : '', '/usr/bin/php','/usr/local/bin/php','/bin/php'] as $candidate) {
            if ($candidate !== '' && is_file($candidate) && is_executable($candidate)) {
                $php = $candidate; break;
            }
        }
        if ($php === '') {
            $result['error'] = 'PHP CLI binary not found';
            return $result;
        }

        $result['available'] = true;
        $result['binary'] = $php;
        $root = self::root();

        $files = [];
        foreach (['app','api','service','public/index.php'] as $rel) {
            $path = $root . '/' . $rel;
            if (is_file($path) && strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'php') {
                $files[$path] = true;
            } elseif (is_dir($path)) {
                try {
                    $it = new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)
                    );
                    foreach ($it as $fi) {
                        if ($fi->isFile() && strtolower($fi->getExtension()) === 'php') {
                            $files[$fi->getPathname()] = true;
                        }
                    }
                } catch (Throwable $e) {}
            }
        }

        $paths = array_keys($files);
        sort($paths, SORT_STRING);

        foreach ($paths as $file) {
            $out = [];
            $code = 1;
            @exec(escapeshellarg($php) . ' -n -l ' . escapeshellarg($file) . ' 2>&1', $out, $code);
            $message = trim(implode("\n", $out));
            $result['checked']++;
            if ($code === 0) {
                $result['ok']++;
            } else {
                $result['ng']++;
                $line = null;
                if (preg_match('/\bon line\s+(\d+)\b/i', $message, $m)) $line = (int)$m[1];
                $result['failed'][] = [
                    'file' => str_replace($root . '/', '', $file),
                    'line' => $line,
                    'message' => $message,
                ];
            }
        }
        return $result;
    }

    private static function files(): array
    {
        $root = self::root();
        $list = [
            'public/index.php',
            'app/bootstrap.php',
            'app/config/config.php',
            'app/controllers/Settings.php',
            'app/libraries/Controller.php',
            'app/libraries/Database.php',
            'app/libraries/TccDebug.php',
            'app/models/Setting.php',
            'info.json',
        ];

        $result = [];
        foreach ($list as $relative) {
            $path = $root . '/' . $relative;
            $exists = is_file($path);
            $size = $exists ? (int)(@filesize($path) ?: 0) : 0;
            $result[] = [
                'file' => $relative,
                'exists' => $exists,
                'size' => $size,
                'readable' => $exists && is_readable($path),
                'writable' => $exists && is_writable($path),
                'permissions' => $exists && @fileperms($path) !== false ? substr(sprintf('%o', @fileperms($path)), -4) : '',
                'sha256' => ($exists && $size > 0 && is_readable($path)) ? (@hash_file('sha256', $path) ?: '') : '',
                'ok' => $exists && $size > 0 && is_readable($path),
            ];
        }
        return $result;
    }

    private static function inspectDb(string $name, string $path): array
    {
        $r = [
            'name' => $name,
            'path' => $path,
            'exists' => is_file($path),
            'size' => is_file($path) ? (int)(@filesize($path) ?: 0) : 0,
            'readable' => is_readable($path),
            'writable' => is_writable($path),
            'sqlite_ok' => false,
            'quick_check' => null,
            'integrity_ok' => null,
            'tables' => [],
            'device_columns' => [],
            'error' => '',
        ];

        if (!$r['exists'] || !$r['readable'] || $r['size'] <= 0) return $r;

        try {
            $pdo = new PDO('sqlite:' . $path);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec('PRAGMA busy_timeout=1500');
            $r['sqlite_ok'] = true;
            $r['tables'] = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name")
                ->fetchAll(PDO::FETCH_COLUMN) ?: [];
            $qc = $pdo->query('PRAGMA quick_check')->fetchAll(PDO::FETCH_COLUMN) ?: [];
            $r['quick_check'] = $qc;
            $r['integrity_ok'] = count($qc) === 1 && strtolower(trim((string)$qc[0])) === 'ok';

            if (in_array('device', $r['tables'], true)) {
                $cols = $pdo->query("PRAGMA table_info('device')")->fetchAll(PDO::FETCH_ASSOC) ?: [];
                $r['device_columns'] = array_map(static fn($x) => (string)($x['name'] ?? ''), $cols);
            }
        } catch (Throwable $e) {
            $r['error'] = $e->getMessage();
            $r['integrity_ok'] = false;
        }

        return $r;
    }

    private static function dbs(): array
    {
        $result = [];
        foreach (self::DBS as $name => $path) {
            $result[] = self::inspectDb($name, $path);
        }
        return $result;
    }

    private static function readDevice(string $path): array
    {
        if (!is_file($path) || !is_readable($path) || (int)(@filesize($path) ?: 0) <= 0) return [];
        try {
            $pdo = new PDO('sqlite:' . $path);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='device'")->fetchColumn();
            if (!$tables) return [];
            $row = $pdo->query('SELECT * FROM device LIMIT 1')->fetch(PDO::FETCH_ASSOC);
            return is_array($row) ? $row : [];
        } catch (Throwable $e) {
            return [];
        }
    }

    private static function consistency(): array
    {
        $a = self::readDevice('/var/www/html/database/idas_data.db');
        $b = self::readDevice('/var/www/html/database/tcccon.db');
        $fields = [
            'device_cfg_ver',
            'device_id',
            'device_name',
            'device_serverport',
            'device_protocol',
        ];
        $checks = [];
        foreach ($fields as $field) {
            $av = $a[$field] ?? null;
            $bv = $b[$field] ?? null;
            $checks[] = [
                'field' => $field,
                'idas_data' => $av,
                'tcccon' => $bv,
                'match' => $av !== null && $bv !== null && (string)$av === (string)$bv,
            ];
        }
        return $checks;
    }

    private static function disks(): array
    {
        $result = [];
        foreach ([
            ['/var/www/html/idas', false],
            ['/var/www/html/database', true],
            ['/mnt/ramdisk', true],
        ] as [$path, $writeExpected]) {
            $exists = is_dir($path);
            $total = $exists ? @disk_total_space($path) : false;
            $free = $exists ? @disk_free_space($path) : false;
            $result[] = [
                'path' => $path,
                'exists' => $exists,
                'writable' => $exists && is_writable($path),
                'write_expected' => $writeExpected,
                'total' => $total === false ? null : (int)$total,
                'free' => $free === false ? null : (int)$free,
                'free_percent' => ($total && $free !== false) ? round($free / $total * 100, 2) : null,
            ];
        }
        return $result;
    }

    private static function services(): array
    {
        $checks = [
            ['Apache', 'systemctl is-active apache2 2>&1'],
            ['Python / Agent', "pgrep -af 'python|agent_client|agent_initial' 2>&1"],
        ];

        $result = [];
        foreach ($checks as [$name, $command]) {
            if (!self::canExec()) {
                $result[] = ['name'=>$name,'status'=>'warning','detail'=>'exec unavailable'];
                continue;
            }
            $out = [];
            $code = 1;
            @exec($command, $out, $code);
            $result[] = [
                'name' => $name,
                'status' => $code === 0 ? 'ok' : 'warning',
                'detail' => trim(implode(' | ', $out)) ?: ('exit=' . $code),
            ];
        }
        return $result;
    }

    private static function versions(): array
    {
        $root = self::root();
        $info = [];
        $path = $root . '/info.json';
        if (is_file($path) && is_readable($path)) {
            $j = json_decode((string)@file_get_contents($path), true);
            if (is_array($j)) $info = $j;
        }

        $device = self::readDevice('/var/www/html/database/idas_data.db');

        return [
            'match_tcc_version' => $info['Match_TCC_Version'] ?? null,
            'device_cfg_ver' => $device['device_cfg_ver'] ?? null,
            'device_id' => $device['device_id'] ?? null,
            'device_name' => $device['device_name'] ?? null,
            'device_protocol' => $device['device_protocol'] ?? null,
            'device_serverport' => $device['device_serverport'] ?? null,
        ];
    }

    private static function tail(string $path, int $maxLines=25, int $maxBytes=131072): array
    {
        $size = @filesize($path);
        if ($size === false || $size <= 0) return [];
        $bytes = min((int)$size, $maxBytes);
        $fp = @fopen($path, 'rb');
        if (!$fp) return [];
        if ($bytes < $size) @fseek($fp, -$bytes, SEEK_END);
        $data = (string)@fread($fp, $bytes);
        fclose($fp);
        $lines = preg_split('/\r\n|\r|\n/', $data) ?: [];
        $lines = array_values(array_filter($lines, static fn($x) => trim((string)$x) !== ''));
        return count($lines) > $maxLines ? array_slice($lines, -$maxLines) : $lines;
    }

    private static function logs(): array
    {
        $targets = [
            ['PHP Error Log', (string)ini_get('error_log')],
            ['Apache Error Log', '/var/log/apache2/error.log'],
            ['Syslog', '/var/log/syslog'],
        ];

        $result = [];
        foreach ($targets as [$name, $path]) {
            $configured = $path !== '' && !in_array(strtolower($path), ['syslog','stderr'], true);
            $exists = $configured && is_file($path);
            $readable = $exists && is_readable($path);
            $result[] = [
                'name'=>$name,
                'path'=>$path,
                'exists'=>$exists,
                'readable'=>$readable,
                'size'=>$exists ? (int)(@filesize($path) ?: 0) : 0,
                'lines'=>$readable ? self::tail($path) : [],
            ];
        }
        return $result;
    }

    private static function functionChecks(): array
    {
        return [
            ['name'=>'PDO','ok'=>extension_loaded('PDO'),'detail'=>'PHP extension'],
            ['name'=>'pdo_sqlite','ok'=>extension_loaded('pdo_sqlite'),'detail'=>'PHP extension'],
            ['name'=>'SQLite3','ok'=>extension_loaded('sqlite3'),'detail'=>'PHP extension'],
            ['name'=>'Controller::Device_Info','ok'=>class_exists('Controller') && method_exists('Controller','Device_Info'),'detail'=>'Controller runtime DB reader'],
            ['name'=>'Setting::GetControllerInfo','ok'=>class_exists('Setting') && method_exists('Setting','GetControllerInfo'),'detail'=>'Setting model DB reader'],
        ];
    }

    private static function summary(array $d): array
    {
        $fileOk = true;
        foreach ($d['files'] as $x) if (empty($x['ok'])) { $fileOk=false; break; }

        $dbOk = true;
        foreach ($d['dbs'] as $x) {
            if (in_array($x['name'], ['iDAS Runtime DB','TCC Controller DB'], true)
                && (!$x['sqlite_ok'] || $x['integrity_ok'] !== true)) {
                $dbOk=false; break;
            }
        }

        $consistencyOk = true;
        foreach ($d['consistency'] as $x) if (empty($x['match'])) { $consistencyOk=false; break; }

        $diskOk = true;
        foreach ($d['disks'] as $x) if (empty($x['exists'])) { $diskOk=false; break; }

        $serviceStatus = 'ok';
        foreach ($d['services'] as $x) if (($x['status'] ?? '') !== 'ok') $serviceStatus='warning';

        $functionOk = true;
        foreach ($d['functions'] as $x) if (empty($x['ok'])) { $functionOk=false; break; }

        $syntaxStatus = !empty($d['syntax']['available'])
            ? ((int)$d['syntax']['ng'] === 0 ? 'ok' : 'ng')
            : 'warning';

        $logStatus = 'ok';
        foreach ($d['logs'] as $x) {
            if (!empty($x['exists']) && empty($x['readable'])) { $logStatus='warning'; break; }
        }

        return [
            ['label'=>'PHP Syntax','status'=>$syntaxStatus],
            ['label'=>'PHP Runtime','status'=>'ok'],
            ['label'=>'Function / Component Check','status'=>$functionOk ? 'ok' : 'ng'],
            ['label'=>'System File Integrity','status'=>$fileOk ? 'ok' : 'ng'],
            ['label'=>'DB Integrity / quick_check','status'=>$dbOk ? 'ok' : 'ng'],
            ['label'=>'idas_data ↔ tcccon Consistency','status'=>$consistencyOk ? 'ok' : 'ng'],
            ['label'=>'Disk / RAMDISK','status'=>$diskOk ? 'ok' : 'ng'],
            ['label'=>'Service Status','status'=>$serviceStatus],
            ['label'=>'Recent Logs','status'=>$logStatus],
            ['label'=>'Version / TCC Information','status'=>'ok'],
            ['label'=>'Emergency Syntax Check','status'=>'available'],
            ['label'=>'Download Debug Report','status'=>'available'],
            ['label'=>'Read-only Debug','status'=>'ok'],
        ];
    }
}

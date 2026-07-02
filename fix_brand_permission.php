<?php
/**
 * iDAS 品牌目錄權限修正工具
 *
 * 手動測試：
 *   sudo php /var/www/html/idas/fix_brand_permission.php
 *
 * Login Controller 自動呼叫：
 *   sudo -n /usr/bin/php /var/www/html/idas/fix_brand_permission.php
 */

if (PHP_OS_FAMILY !== 'Linux') {
    fwrite(STDERR, "ERROR: Linux only.\n");
    exit(1);
}

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "ERROR: CLI only.\n");
    exit(1);
}

if (
    !function_exists('posix_geteuid')
    || posix_geteuid() !== 0
) {
    fwrite(STDERR, "ERROR: Root permission is required.\n");
    exit(1);
}

$brandDirectory =
    '/home/kls/project/system/ltver';

/*
 * 保留原有權限，只新增：
 *   上層目錄：others execute
 *   ltver：others read + execute
 */
$permissionAdditions = array(
    '/home/kls' => 0001,
    '/home/kls/project' => 0001,
    '/home/kls/project/system' => 0001,
    $brandDirectory => 0005,
);

foreach ($permissionAdditions as $path => $addMode) {
    clearstatcache(true, $path);

    if (!is_dir($path)) {
        fwrite(
            STDERR,
            "ERROR: Directory not found: "
            . $path
            . "\n"
        );
        exit(1);
    }

    $permissions = @fileperms($path);

    if ($permissions === false) {
        fwrite(
            STDERR,
            "ERROR: Cannot read permission: "
            . $path
            . "\n"
        );
        exit(1);
    }

    $oldMode = $permissions & 0777;
    $newMode = $oldMode | $addMode;

    if (!@chmod($path, $newMode)) {
        fwrite(
            STDERR,
            sprintf(
                "ERROR: chmod failed: %s %04o -> %04o\n",
                $path,
                $oldMode,
                $newMode
            )
        );
        exit(1);
    }

    echo sprintf(
        "OK: %s %04o -> %04o\n",
        $path,
        $oldMode,
        $newMode
    );
}

clearstatcache(true, $brandDirectory);

$files = @scandir($brandDirectory);

if (!is_array($files)) {
    fwrite(
        STDERR,
        "ERROR: Brand directory is still unreadable.\n"
    );
    exit(1);
}

$brandCode = '';

foreach ($files as $fileName) {
    if (
        preg_match(
            '/^(BF\d{2})(?:[-_.]|$)/i',
            $fileName,
            $matches
        )
    ) {
        $brandCode = strtoupper($matches[1]);
        break;
    }
}

echo 'OK: Brand directory is readable.' . PHP_EOL;
echo 'OK: Detected brand: '
    . ($brandCode !== '' ? $brandCode : 'not found')
    . PHP_EOL;

exit(0);

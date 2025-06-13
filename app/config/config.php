<?php
// ────────────── 路徑設定 ──────────────
define('APPROOT', dirname(dirname(__FILE__)) . '/');           // App 根目錄
define('URLROOT', '../public/');                               // public 網址根目錄（local 用）
define('SITENAME', 'iDAS DEVICE');                             // 網站名稱

// ────────────── 系統模式設定 ──────────────
define('IDASMODE', '1');                                       // 0: 單機版, 1: 連線版

// ────────────── 語言設定 ──────────────
define('LANGUAGE', [
    0 => ['简中', 'zh-cn'],
    1 => ['繁中', 'zh-tw'],
    2 => ['English', 'en-us'],
]);

// ────────────── 避免快取 ──────────────
define('ASSET_VERSION', date('YmdHi'));                        // 每次重新刷新 asset 版本

// ────────────── 品牌定義（可切換不同品牌風格） ──────────────
// $brand = get_brand_code(); // 自動偵測品牌（未啟用）
$brand = '0'; // 預設為 KILEWS
define('ICONMODE', $brand);

// ────────────── ICON & Title 設定表 ──────────────
$brandConfigs = [
    '0' => [ // KILEWS
        'TITLE_INDEX'      => 'KILEWS',
        'SUBTITLE_INDEX'   => 'iDAS for KL-TCC-M7',
        'TITLE_AGENT'      => 'KILEWS IoT Agent',
        'DEVICE_TYPE_10'   => 'KL-TCC',
        'ICON_NORMAL'      => 'img/192.png',
        'ICON_NORMAL_APPLE'=> 'img/60.png',
        'ICON_AGENT'       => 'img/192.png',
        'ICON_AGENT_APPLE' => 'img/60.png',
    ],
    '2' => [ // 上海
        'TITLE_INDEX'      => 'KILEWS',
        'SUBTITLE_INDEX'   => 'iDAS FOR KILEWS',
        'TITLE_AGENT'      => 'KILEWS IoT Agent',
        'DEVICE_TYPE_10'   => 'KL-EPIC',
        'ICON_NORMAL'      => 'img/192.png',
        'ICON_NORMAL_APPLE'=> 'img/60.png',
        'ICON_AGENT'       => 'img/192.png',
        'ICON_AGENT_APPLE' => 'img/60.png',
    ],
    '4' => [ // MyTorque
        'TITLE_INDEX'      => 'MYTORQ',
        'SUBTITLE_INDEX'   => 'iDAS FOR MY-SIRIUS',
        'TITLE_AGENT'      => 'MYTORQ IoT Agent',
        'DEVICE_TYPE_10'   => 'MY-SIRIUS',
        'ICON_NORMAL'      => 'img/MY-icon/yellow-192x192.png',
        'ICON_NORMAL_APPLE'=> 'img/MY-icon/yellow-60x60.png',
        'ICON_AGENT'       => 'img/MY-icon/blue-192x192.png',
        'ICON_AGENT_APPLE' => 'img/MY-icon/blue-60x60.png',
    ],
    '5' => [ // SUMAKE
        'TITLE_INDEX'      => 'SUMAKE',
        'SUBTITLE_INDEX'   => 'iDAS FOR SMT-C2',
        'TITLE_AGENT'      => 'SUMAKE IoT Agent',
        'DEVICE_TYPE_10'   => 'SMT-C2',
        'ICON_NORMAL'      => 'img/192.png',
        'ICON_NORMAL_APPLE'=> 'img/60.png',
        'ICON_AGENT'       => 'img/192.png',
        'ICON_AGENT_APPLE' => 'img/60.png',
    ],
    '6' => [ // DELTA
        'TITLE_INDEX'      => 'DELTA',
        'SUBTITLE_INDEX'   => 'iDAS FOR XTCA1',
        'TITLE_AGENT'      => 'DELTA IoT Agent',
        'DEVICE_TYPE_10'   => 'XTCA1',
        'ICON_NORMAL'      => 'img/192.png',
        'ICON_NORMAL_APPLE'=> 'img/60.png',
        'ICON_AGENT'       => 'img/192.png',
        'ICON_AGENT_APPLE' => 'img/60.png',
    ],
    '7' => [ // 白牌
        'TITLE_INDEX'      => '',
        'SUBTITLE_INDEX'   => 'iDAS FOR OPT-GK TRS1',
        'TITLE_AGENT'      => 'IoT Agent',
        'DEVICE_TYPE_10'   => 'OPT-GK TRS1',
        'ICON_NORMAL'      => 'img/192.png',
        'ICON_NORMAL_APPLE'=> 'img/60.png',
        'ICON_AGENT'       => 'img/192.png',
        'ICON_AGENT_APPLE' => 'img/60.png',
    ]
];

// ────────────── 實際註冊常數 ──────────────
$conf = $brandConfigs[ICONMODE] ?? $brandConfigs['0']; // fallback to 0
foreach ($conf as $key => $val) {
    define($key, URLROOT . $val);
}

// ────────────── 品牌碼讀取邏輯（備用） ──────────────
/*
function get_brand_code()
{
    if (PHP_OS_FAMILY === 'Linux') {
        $directory = '/home/kls/project/system/ltver';
        if (is_dir($directory)) {
            $fileList = array_values(array_diff(scandir($directory), ['.', '..']));
            if (isset($fileList[0])) {
                $explode = explode('-', $fileList[0]);
                return $explode[0] ?? false;
            }
        }
    }
    return false;
}
*/

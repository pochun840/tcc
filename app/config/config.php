<?php
// 根目錄與資源路徑設定
define('APPROOT', dirname(dirname(__FILE__)) . '/');
define('URLROOT', '../public/'); // Local URL 用
define('SITENAME', 'iDAS DEVICE');
define('IDASMODE', '1'); // 0:單機版 1:連線版
define('ASSET_VERSION', date('YmdHi')); // 防快取用版本號

// 語言對應表
define('LANGUAGE', [
    0 => ['简中', 'zh-cn'],
    1 => ['繁中', 'zh-tw'],
    2 => ['English', 'en-us']
]);

// 品牌代碼與模式定義（0:Kilews, 2:上海, 4:MyTorque, 5:SUMAKE, 6:DELTA, 7:白牌）
$brand = '0'; // 預設 KILEWS
define('ICONMODE', $brand);

// 品牌設定表
$brandSettings = [
    '0' => [
        'title' => 'KILEWS',
        'subtitle' => 'iDAS for KL-TCC-M7',
        'agentTitle' => 'KILEWS IoT Agent',
        'deviceType' => 'KL-TCC',
        'icons' => [
            'normal' => '192.png',
            'apple' => '60.png',
            'agent' => '192.png',
            'agentApple' => '60.png'
        ]
    ],
    '2' => [
        'title' => 'KILEWS',
        'subtitle' => 'iDAS FOR KILEWS',
        'agentTitle' => 'KILEWS IoT Agent',
        'deviceType' => 'KL-EPIC',
        'icons' => [
            'normal' => '192.png',
            'apple' => '60.png',
            'agent' => '192.png',
            'agentApple' => '60.png'
        ]
    ],
    '4' => [
        'title' => 'MYTORQ',
        'subtitle' => 'iDAS FOR MY-SIRIUS',
        'agentTitle' => 'MYTORQ IoT Agent',
        'deviceType' => 'MY-SIRIUS',
        'icons' => [
            'normal' => 'MY-icon/yellow-192x192.png',
            'apple' => 'MY-icon/yellow-60x60.png',
            'agent' => 'MY-icon/blue-192x192.png',
            'agentApple' => 'MY-icon/blue-60x60.png'
        ]
    ],
    '5' => [
        'title' => 'SUMAKE',
        'subtitle' => 'iDAS FOR SMT-C2',
        'agentTitle' => 'SUMAKE IoT Agent',
        'deviceType' => 'SMT-C2',
        'icons' => [
            'normal' => '192.png',
            'apple' => '60.png',
            'agent' => '192.png',
            'agentApple' => '60.png'
        ]
    ],
    '6' => [
        'title' => 'DELTA',
        'subtitle' => 'iDAS FOR XTCA1',
        'agentTitle' => 'DELTA IoT Agent',
        'deviceType' => 'XTCA1',
        'icons' => [
            'normal' => '192.png',
            'apple' => '60.png',
            'agent' => '192.png',
            'agentApple' => '60.png'
        ]
    ],
    '7' => [
        'title' => '',
        'subtitle' => 'iDAS FOR OPT-GK TRS1',
        'agentTitle' => 'IoT Agent',
        'deviceType' => 'OPT-GK TRS1',
        'icons' => [
            'normal' => '192.png',
            'apple' => '60.png',
            'agent' => '192.png',
            'agentApple' => '60.png'
        ]
    ]
];

// 取得對應設定（如未定義品牌則使用 '0'）
$config = $brandSettings[ICONMODE] ?? $brandSettings['0'];

// 定義常數
define('ICON_NORMAL',        URLROOT . 'img/' . $config['icons']['normal']);
define('ICON_NORMAL_APPLE',  URLROOT . 'img/' . $config['icons']['apple']);
define('ICON_AGENT',         URLROOT . 'img/' . $config['icons']['agent']);
define('ICON_AGENT_APPLE',   URLROOT . 'img/' . $config['icons']['agentApple']);
define('TITLE_INDEX',        $config['title']);
define('SUBTITLE_INDEX',     $config['subtitle']);
define('TITLE_AGENT',        $config['agentTitle']);
define('DEVICE_TYPE_10',     $config['deviceType']);

//（選用）品牌自動抓取函式，視環境使用
/*
function get_brand_code()
{
    if (PHP_OS_FAMILY == 'Linux') {
        $directory = '/home/kls/project/system/ltver';
        $files = array_diff(scandir($directory), ['.', '..']);
        if (count($files) > 0) {
            $parts = explode("-", reset($files));
            return $parts[0] ?? false;
        }
    }
    return false;
}
*/

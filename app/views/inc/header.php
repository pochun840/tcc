<?php
$iconApple = (defined('ICON_NORMAL_APPLE') && ICON_NORMAL_APPLE !== '')
    ? ICON_NORMAL_APPLE
    : URLROOT . 'img/60.png';

$iconNormal = (defined('ICON_NORMAL') && ICON_NORMAL !== '')
    ? ICON_NORMAL
    : URLROOT . 'img/192.png';

$pageTitle = (defined('TITLE_INDEX') && trim((string) TITLE_INDEX) !== '')
    ? TITLE_INDEX
    : SITENAME;

$assetVersion = defined('ASSET_VERSION')
    ? (string) ASSET_VERSION
    : date('YmdHi');

/*
 * 使用匿名函式，避免 header.php 被重複載入時，
 * 發生 Cannot redeclare append_asset_version_for_header()。
 */
$appendAssetVersionForHeader = static function ($url, $version) {
    $url = (string) $url;
    $version = (string) $version;

    if ($version === '') {
        return $url;
    }

    $separator = strpos($url, '?') === false ? '?' : '&';

    return $url
        . $separator
        . 'v='
        . rawurlencode($version);
};

$iconAppleUrl = $appendAssetVersionForHeader(
    $iconApple,
    $assetVersion
);

$iconNormalUrl = $appendAssetVersionForHeader(
    $iconNormal,
    $assetVersion
);

$jqueryUrl = $appendAssetVersionForHeader(
    URLROOT . 'js/jquery-3.7.1.min.js',
    $assetVersion
);

$dataTablesUrl = $appendAssetVersionForHeader(
    URLROOT . 'js/jquery_dataTables_min.js',
    $assetVersion
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <link
        rel="apple-touch-icon"
        sizes="60x60"
        href="<?php echo htmlspecialchars(
            $iconAppleUrl,
            ENT_QUOTES,
            'UTF-8'
        ); ?>"
    >

    <link
        rel="icon"
        sizes="192x192"
        href="<?php echo htmlspecialchars(
            $iconNormalUrl,
            ENT_QUOTES,
            'UTF-8'
        ); ?>"
    >

    <meta
        name="mobile-web-app-capable"
        content="yes"
    >

    <meta
        name="apple-mobile-web-app-capable"
        content="yes"
    >

    <meta
        name="application-name"
        content="<?php echo htmlspecialchars(
            $pageTitle,
            ENT_QUOTES,
            'UTF-8'
        ); ?>"
    >

    <meta
        name="apple-mobile-web-app-title"
        content="<?php echo htmlspecialchars(
            $pageTitle,
            ENT_QUOTES,
            'UTF-8'
        ); ?>"
    >

    <meta
        name="theme-color"
        content="#000000"
    >

    <script src="<?php echo htmlspecialchars(
        $jqueryUrl,
        ENT_QUOTES,
        'UTF-8'
    ); ?>"></script>

    <script src="<?php echo htmlspecialchars(
        $dataTablesUrl,
        ENT_QUOTES,
        'UTF-8'
    ); ?>"></script>

    <?php include_once __DIR__ . '/include_css.php'; ?>

    <title>
        <?php echo htmlspecialchars(
            $pageTitle,
            ENT_QUOTES,
            'UTF-8'
        ); ?>
    </title>
</head>
<body>

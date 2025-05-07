<?php 
// 共用：條件式載入 CSS / JS（根據 URL 第一層）
function include_asset($part, $fileName) {
    $queryString = $_SERVER['QUERY_STRING'] ?? '';
    $queryStringWithoutUrl = str_replace('url=', '', $queryString);
    $firstPart = explode('/', $queryStringWithoutUrl)[0] ?? '';

    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
    $baseName  = pathinfo($fileName, PATHINFO_FILENAME);

    $isMobile = isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/Mobile|Android|iPhone|iPad|iPod/i', $_SERVER['HTTP_USER_AGENT']);

    // --- Sequences 特例處理 ---
    if ($firstPart === 'Sequences') {
        // JS：排除 sequences.js，改載入 seq.js
        if ($extension === 'js' && $fileName === 'sequences.js') {
            echo "<script src=\"" . URLROOT . "js/seq.js?v=" . ASSET_VERSION . "\"></script>\n";
            return;
        }

        // CSS：排除 sequences.css，改載入 tcc_seq[_m].css
        if ($extension === 'css' && $fileName === 'sequences.css') {
            $cssFile = 'tcc_seq' . ($isMobile ? '_m.css' : '.css');
            echo "<link rel=\"stylesheet\" href=\"" . URLROOT . "css/$cssFile?v=" . ASSET_VERSION . "\">\n";
            return;
        }
    }

    // --- 一般 CSS ---
    if ($extension === 'css' && $firstPart === $part) {
        $cssFile = 'tcc_' . $baseName . ($isMobile ? '_m.css' : '.css');
        echo "<link rel=\"stylesheet\" href=\"" . URLROOT . "css/$cssFile?v=" . ASSET_VERSION . "\">\n";
        return;
    }

    // --- 一般 JS ---
    if ($extension === 'js' && $firstPart === $part) {
        echo "<script src=\"" . URLROOT . "js/$fileName?v=" . ASSET_VERSION . "\"></script>\n";
        return;
    }
}


?>

    <!-- ================== 基礎 JS ================== -->
    <script src="<?php echo URLROOT; ?>js/jquery-3.7.1.min.js?v=<?php echo ASSET_VERSION; ?>"></script>

    <!-- ================== 基礎 CSS ================== -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>css/jquery_data_Tables.css?v=<?php echo ASSET_VERSION; ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>css/datatables.min.css?v=<?php echo ASSET_VERSION; ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>css/w3.css?v=<?php echo ASSET_VERSION; ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>css/font-awesome.min.css?v=<?php echo ASSET_VERSION; ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>css/flatpickr.min.css?v=<?php echo ASSET_VERSION; ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>css/alertify_min.css?v=<?php echo ASSET_VERSION; ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>css/default_min.css?v=<?php echo ASSET_VERSION; ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>css/tcc_verify.css?v=<?php echo ASSET_VERSION; ?>">

    <!-- ================== 共用 JS ================== -->
    <script src="<?php echo URLROOT; ?>js/all.js?v=<?php echo ASSET_VERSION; ?>"></script>
    <script src="<?php echo URLROOT; ?>js/echarts_min.js?v=<?php echo ASSET_VERSION; ?>"></script>
    <script src="<?php echo URLROOT; ?>js/jquery_data_Tables.js?v=<?php echo ASSET_VERSION; ?>"></script>
    <script src="<?php echo URLROOT; ?>js/alertify_min.js?v=<?php echo ASSET_VERSION; ?>"></script>


    <!-- ================== 模組 JS 及 CSS 動態載入 ================== -->
    <?php 
    $modules = ['Inputs', 'Outputs', 'Jobs', 'Data', 'Sequences', 'Step', 'Settings'];
    foreach ($modules as $mod) {
        include_asset($mod, strtolower($mod) . '.css');
        include_asset($mod, strtolower($mod) . '.js');
    }
    ?>

    <!-- ================== 其他工具 JS ================== -->
    <script src="<?php echo URLROOT; ?>js/flatpickr.js?v=<?php echo ASSET_VERSION; ?>"></script>
    <script src="<?php echo URLROOT; ?>js/flatpickr_zh-tw.js?v=<?php echo ASSET_VERSION; ?>"></script>
    <script src="<?php echo URLROOT; ?>js/tcc_data.js?v=<?php echo ASSET_VERSION; ?>"></script>
    <script src="<?php echo URLROOT; ?>js/jszip.js?v=<?php echo ASSET_VERSION; ?>"></script>

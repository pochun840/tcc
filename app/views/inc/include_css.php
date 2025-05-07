<?php 
// 共用：條件式載入 CSS / JS（根據 URL 第一層）
function include_asset($part, $fileName) {
    $queryString = $_SERVER['QUERY_STRING'] ?? '';
    $queryStringWithoutUrl = str_replace('url=', '', $queryString);
    $parts = explode('/', $queryStringWithoutUrl);
    $firstPart = $parts[0] ?? '';
    $extension = pathinfo($fileName, PATHINFO_EXTENSION);

    //特別排除 Sequences 頁面載入 sequences.js（強制不要載）
    if (!($firstPart === 'Sequences' && $fileName === 'sequences.js')) {
        if ($firstPart === $part) {
            $path = ($extension === 'css') ? 'css' : 'js';
            $tag = ($extension === 'css')
                ? "<link rel=\"stylesheet\" href=\"" . URLROOT . "$path/$fileName?v=" . ASSET_VERSION . "\">"
                : "<script src=\"" . URLROOT . "$path/$fileName?v=" . ASSET_VERSION . "\"></script>";
            echo $tag . "\n";
        }
    }

    //額外條件：若網址是 Sequences，就強制載入 seq.js
    if ($firstPart === 'Sequences' && $fileName === 'sequences.js') {
        echo "<script src=\"" . URLROOT . "js/seq.js?v=" . ASSET_VERSION . "\"></script>\n";
    }
}

function include_css() {
    $queryString = $_SERVER['QUERY_STRING'] ?? '';
    $queryString = str_replace('url=', '', $queryString);
    $route = explode('/', $queryString)[0] ?? '';

    if ($route === 'Jobs') {
        $isMobile = isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/Mobile|Android|iPhone|iPad|iPod/i', $_SERVER['HTTP_USER_AGENT']);
        $cssFile = $isMobile ? 'tcc_jobs_m.css' : 'tcc_jobs.css';
        echo '<link rel="stylesheet" href="' . URLROOT . 'css/' . $cssFile . '?v=' . ASSET_VERSION . '" type="text/css">' . "\n";
    }

    if ($route === 'Sequences') {
        $isMobile = isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/Mobile|Android|iPhone|iPad|iPod/i', $_SERVER['HTTP_USER_AGENT']);
        $cssFile = $isMobile ? 'tcc_seq_m.css' : 'tcc_seq.css';
        echo '<link rel="stylesheet" href="' . URLROOT . 'css/' . $cssFile . '?v=' . ASSET_VERSION . '" type="text/css">' . "\n";
    }


    if ($route === 'Step') {
        $isMobile = isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/Mobile|Android|iPhone|iPad|iPod/i', $_SERVER['HTTP_USER_AGENT']);
        $cssFile = $isMobile ? 'tcc_step_m.css' : 'tcc_step.css';
        echo '<link rel="stylesheet" href="' . URLROOT . 'css/' . $cssFile . '?v=' . ASSET_VERSION . '" type="text/css">' . "\n";
    }

    if ($route === 'Tools') { 
        $cssFile = 'tcc_tools.css';
        echo '<link rel="stylesheet" href="' . URLROOT . 'css/' . $cssFile . '?v=' . ASSET_VERSION . '" type="text/css">' . "\n";
    }

    if ($route === 'Data') { 
        $cssFile = 'tcc_data.css';
        echo '<link rel="stylesheet" href="' . URLROOT . 'css/' . $cssFile . '?v=' . ASSET_VERSION . '" type="text/css">' . "\n";
    }

    
    if ($route === 'Agents') { 
        $cssFile = 'tcc_agent.css';
        echo '<link rel="stylesheet" href="' . URLROOT . 'css/' . $cssFile . '?v=' . ASSET_VERSION . '" type="text/css">' . "\n";
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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>css/tcc_share.css?v=<?php echo ASSET_VERSION; ?>">

    <!-- ================== 共用 JS ================== -->
    <script src="<?php echo URLROOT; ?>js/all.js?v=<?php echo ASSET_VERSION; ?>"></script>
    <script src="<?php echo URLROOT; ?>js/echarts_min.js?v=<?php echo ASSET_VERSION; ?>"></script>
    <script src="<?php echo URLROOT; ?>js/jquery_data_Tables.js?v=<?php echo ASSET_VERSION; ?>"></script>
    <script src="<?php echo URLROOT; ?>js/alertify_min.js?v=<?php echo ASSET_VERSION; ?>"></script>


    <!-- ================== 模組 CSS 動態載入 ================== -->
    <?php echo include_css();?>

    <!-- ================== 模組 JS 動態載入 ================== -->
    <?php 
    $modules = ['Inputs', 'Outputs', 'Jobs', 'Data', 'Sequences', 'Step', 'Settings'];
    foreach ($modules as $mod) {
        include_asset($mod, strtolower($mod) . '.js');
    }

    
    ?>

    <!-- ================== 其他工具 JS ================== -->
    <script src="<?php echo URLROOT; ?>js/flatpickr.js?v=<?php echo ASSET_VERSION; ?>"></script>
    <script src="<?php echo URLROOT; ?>js/flatpickr_zh-tw.js?v=<?php echo ASSET_VERSION; ?>"></script>
    <script src="<?php echo URLROOT; ?>js/tcc_data.js?v=<?php echo ASSET_VERSION; ?>"></script>
    <script src="<?php echo URLROOT; ?>js/jszip.js?v=<?php echo ASSET_VERSION; ?>"></script>

<?php 
function includecss_file($part, $cssFileName) {
    $queryString = $_SERVER['QUERY_STRING'];
    $queryStringWithoutUrl = str_replace('url=', '', $queryString);
    $parts = explode('/', $queryStringWithoutUrl);
    $firstPart = $parts[0];
    
    if($firstPart === $part) { ?>
    <link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>css/<?php echo $cssFileName; ?>">
    <?php }?>
<?php }?>

    <script src="<?php echo URLROOT; ?>js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>css/datatables.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>css/w3.css">
    <script src="<?php echo URLROOT; ?>js/all.js?v=202406131200"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.2.2/dist/echarts.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <link rel="stylesheet" href="<?php echo URLROOT; ?>css/flatpickr.min.css" type="text/css">

    <script src="<?php echo URLROOT; ?>js/flatpickr.js?v=202406131200"></script>
    <script src="<?php echo URLROOT; ?>js/tcc_data.js?v=202406131200"></script>
    <script src="<?php echo URLROOT; ?>js/flatpickr.js"></script>
    <script src="<?php echo URLROOT; ?>js/flatpickr_zh-tw.js"></script>
    <script src="<?php echo URLROOT; ?>js/flatpickr_zh.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.6.0/jszip.min.js"></script>


    

<?php 

    includecss_file("Tools", "tcc_tool.css");
    includecss_file("Inputs", "tcc_input.css");
    includecss_file("Outputs", "tcc_output.css");
    includecss_file("Sequences", "tcc_seq.css");
    includecss_file("Jobs", "tcc_jobs.css");
    includecss_file("Step", "tcc_step.css");
    includecss_file("Dashboards","tcc_operation.css");
    includecss_file("Data","tcc_data.css");
    includecss_file("Settings","tcc_setting.css");

?>
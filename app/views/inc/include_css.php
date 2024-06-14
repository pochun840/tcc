<?php 
function includecss_file($part, $cssFileName) {
    $queryString = $_SERVER['QUERY_STRING'];
    $queryStringWithoutUrl = str_replace('url=', '', $queryString);
    $parts = explode('/', $queryStringWithoutUrl);
    $firstPart = $parts[0];
    
    if($firstPart === $part) { ?>
<link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>css/<?php echo $cssFileName; ?>">
    <?php }
}

includecss_file("Tools", "tcc_tool.css");
includecss_file("Inputs", "tcc_input.css");
includecss_file("Outputs", "tcc_output.css");
includecss_file("Sequences", "tcc_seq.css");
includecss_file("Jobs", "tcc_jobs.css");
includecss_file("Step", "tcc_step.css");
//<link rel="stylesheet" type="text/css" href="./css/tcc_seq.css">

?>
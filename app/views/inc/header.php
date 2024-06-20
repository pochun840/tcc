<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo ICON_NORMAL_APPLE; ?>">
    <link rel="icon" sizes="192x192" href="<?php echo ICON_NORMAL; ?>">

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#000000">

    <script src="<?php echo URLROOT; ?>js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <?php
        include_once 'include_css.php';
    ?>
    <title><?php echo SITENAME; ?></title>


    <?php 
      $queryString = $_SERVER['QUERY_STRING'];
      $queryStringWithoutUrl = str_replace('url=', '', $queryString);
      $parts = explode('/', $queryStringWithoutUrl);
      $firstPart = $parts[0];

      if($firstPart =="Tools"){?>

        <style>

            .t1{font-size: 15px; margin: 5px 0px; display: flex; align-items: center;padding-left: 7%}
            .t2{font-size: 14px; margin: 5px 0px;}

        </style>

      <?php } else if($firstPart =="Data"){?>
        <style>
            .t1{font-size: 17px; margin: 5px 0px; display: flex; align-items: center;}
            .t2{font-size: 17px; margin: 5px 0px;}
            .t3{font-size: 17px; margin: 3px 0px;}
        </style>

        
        <?php }else{ ?>

        <style>
            .t1{font-size: 17px; margin: 5px 0px; display: flex; align-items: center;}
            .t2{font-size: 17px; margin: 5px 0px;}
            .t3{font-size: 17px; margin: 3px 0px;}
            .t4{height: 28px;text-align: center;}   
            .selected { background-color: #9AC0CD !important;}
            .input-pin img{ height: 25px; width: 40px}
            .output-pin img{ height: 25px; width: 45px}
            .disabled_input{ color: #999; pointer-events: none;}
            .zoom{zoom:1.2; vertical-align: middle}
        </style>
        

    <?php } ?>
    
   



        
</head>
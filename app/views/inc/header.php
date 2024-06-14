<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo ICON_NORMAL_APPLE; ?>">
    <link rel="icon" sizes="192x192" href="<?php echo ICON_NORMAL; ?>">

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#000000">

    <link rel="stylesheet" href="<?php echo URLROOT; ?>css/datatables.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>css/w3.css">

    <script src="<?php echo URLROOT; ?>js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script src="<?php echo URLROOT; ?>js/all.js?v=202406131200"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>

    
    <?php include_once 'include_css.php'; ?>

    <title><?php echo SITENAME; ?></title>

    <style>
        .t1{font-size: 17px; margin: 5px 0px; display: flex; align-items: center;}
        .t2{font-size: 17px; margin: 5px 0px;}
        .t3{font-size: 17px; margin: 3px 0px;}
        .selected { background-color: #9AC0CD !important;}
        .input-pin img{ height: 25px; width: 40px}
        .output-pin img{ height: 25px; width: 45px}
        .disabled_input{ color: #999; pointer-events: none;}

    </style>



        
</head>
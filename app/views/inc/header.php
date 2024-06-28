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
    <?php include_once 'include_css.php';?>
    <title><?php echo SITENAME; ?></title>    
    <?php if(session_status() == PHP_SESSION_NONE) {
                session_start();
          }
          $lanuage = $_SESSION['language'];
          if(file_exists('../app/language/' . $lanuage . '.php')){
            require_once '../app/language/' . $lanuage . '.php';
          } else { //預設採用簡體中文
            require_once '../app/language/en-us.php';
          }
    ?>
</head>
<body>
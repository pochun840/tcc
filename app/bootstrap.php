<?php
// 顯示?�?�錯�?
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
require_once 'config/config.php';

spl_autoload_register(function($className){
    require_once 'libraries/' . $className . '.php';
});
<?php
/* Global Stuff */
define("NEW_LINE", (php_sapi_name() == "cli" ? "\n" : "<br/>"));

require('Config.php');
require('languages/' . Config::$app_language . '.php');

// Composer Autoloader
require('libs/vendor/autoload.php');


// App Autoloader
 function AppAutoloader($class) {
    $core_file  = dirname(__FILE__) . '/core/'  . $class . '.php';
    $lib        = dirname(__FILE__) . '/libs/'  . $class . '.php';
    $model      = dirname(__FILE__) . '/models/'. $class . '.php';         
    if(file_exists($core_file)) include_once $core_file;
    elseif(file_exists($lib))   include_once $lib;
    elseif(file_exists($model)) include_once $model;    
}
spl_autoload_register('AppAutoloader');

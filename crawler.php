<?php

/* ======================================================= *
 * App Bootstrap
 * ======================================================= */

// Get needed files
require_once (__DIR__ . '/core/AppAutoloader.php');

// Instanciate PersonalCrawler class and inject dependencies

// Get instance that can be shared
$localization_manager_intance   = new LocalizationManager();
$storage_manager_instance       = new SQLiteStorageManager();
$http_manager_instance          = new HttpManager();
$parameters_manager_instance    = new ParametersManager( $localization_manager_intance );
$crawling_manager_instance      = new CrawlingManager($storage_manager_instance, $http_manager_instance, $localization_manager_intance);

// Get insance of main class
$pc = new PersonalCrawler(
    $localization_manager_intance,
    $parameters_manager_instance,
    $crawling_manager_instance
);

// Initialize the app with parameters from command line execution
$pc->Initialize( $argv );

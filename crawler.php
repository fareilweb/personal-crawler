<?php
/* ======================================================= *
 * App Bootstrap
 * ======================================================= */

// Get needed files
require_once (__DIR__ . "/configuration.php");
require_once (PATH_APP . '/core/AppAutoloader.php');

// Instanciate PersonalCrawler class and inject dependencies
$pc = new PersonalCrawler ( new HttpManager(), new LocalizationManager(), new SQLiteStorageManager() );

// Initialize the app with the gived parameters
$pc->Initialize ( $argv );

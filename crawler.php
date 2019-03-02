<?php
/* ======================================================= *
 * App Bootstrap
 * ======================================================= */

// Get needed files
require_once (__DIR__ . '/core/AppAutoloader.php');

// Instanciate PersonalCrawler class and inject dependencies
$pc = new PersonalCrawler (
	new HttpManager(), 		
	new LocalizationManager(),
	new SQLiteStorageManager(),
	new ParametersManager()
);

// Initialize the app with the gived parameters
$pc->Initialize ( $argv );

<?php
/* ======================================================= *
 * App Bootstrap
 * ======================================================= */

// Get needed files
require_once (__DIR__ . '/core/AppAutoloader.php');

// Instanciate PersonalCrawler class and inject dependencies
$pc = new PersonalCrawler (
	new HttpManager(), 			// Will manage connection, web request, network comunication and related stuff
	new LocalizationManager(), 	// Will be responsible of all the translation stuff of the app
	new SQLiteStorageManager() 	// Will be responsible of the storage to a database. Can be exhanged with every class that implements IStorageManager
);

// Initialize the app with the gived parameters
$pc->Initialize ( $argv );

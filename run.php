<?php

/* App Autoloader */
function AppAutoloader($class_name) {

    $directories = [
		__DIR__ . '/core/'
	];

	$file_found = FALSE;
	$iteration_index = 0;
	while ($file_found || ($iteration_index) == count($directories)) {
		$file_path = $directories[$iteration_index] . $class_name . '.php';
		$file_found = file_exists($file_path);
		if($file_found) include_once($file_path);
		$iteration_index++;
	}


}
spl_autoload_register('AppAutoloader');

$pc = new PersonalCrawler();

$pc->Initialize($argv);

?>

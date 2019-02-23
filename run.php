<?php

/* App Autoloader */
function AppAutoloader($class_name) {

    $directories = [
		__DIR__ . '/core/'
	];

	$file_found = FALSE;
	$iteration_index = 0;
	do {
		$file_path = $directories[$iteration_index] . $class_name . '.php';
		$file_found = file_exists($file_path);
		if($file_found) include_once($file_path);
		$iteration_index++;
	} while ($file_found || ($iteration_index + 1) == count($directories));

}
spl_autoload_register('AppAutoloader');

$pc = new PersonalCrawler();

$pc->Initialize($argv);

?>

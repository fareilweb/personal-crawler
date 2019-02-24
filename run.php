<?php

/* App Autoloader */
function AppAutoloader($class_name) {

    $dirs = [
		__DIR__ . '/core/'
	];

	$file_found = FALSE;
	$index = 0;
	$dirs_count = count($dirs);

	while ($file_found == FALSE && $index < $dirs_count)
	{

		$file_path = $dirs[$index] . $class_name . '.php'; echo $file_path . " --- ";
		$file_found = file_exists($file_path);
		if($file_found) {
			include_once($file_path);
		}

		echo $index . ' - ' . $dirs_count . ' | ';

		$index++;

		if($index == 10) {
			exit;
		}
	}
}
spl_autoload_register('AppAutoloader');


$pc = new PersonalCrawler();
$pc->Initialize($argv);
?>

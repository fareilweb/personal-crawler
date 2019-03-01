<?php
/**
 * [App Autoloader Registering]
 * @param {string} $class_name
 */
function AppAutoloader ( $class_name ) 
{

	$directories = [
		PATH_APP,
		PATH_CORE,
		PATH_MANAGERS,
		PATH_MODELS,
		PATH_DTOS,
		PATH_INTERFACES
	];

	$index = 0;
	$directories_count = count($directories);
	$file_found = FALSE;

	while ($file_found == FALSE && $index < $directories_count)
	{
		$file_path = $directories[$index] . DIRECTORY_SEPARATOR . $class_name . '.php';
		$file_found = file_exists($file_path);
		if ( $file_found ) include_once($file_path);
		$index++;
	}

}

spl_autoload_register ( 'AppAutoloader' );

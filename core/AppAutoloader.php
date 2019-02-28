<?php
/**
 * [App Autoloader Registering]
 * @param {string} $class_name
 */
function AppAutoloader ( $class_name ) {
    $dirs = [
		__DIR__ . '/',
		__DIR__ . '/models/'
	];

	$file_found = FALSE;
	$index = 0;
	$dirs_count = count($dirs);

	while ($file_found == FALSE && $index < $dirs_count)
	{
		$file_path = $dirs[$index] . $class_name . '.php';
		$file_found = file_exists($file_path);
		if ( $file_found ) include_once($file_path);
		$index++;
	}
}
spl_autoload_register ( 'AppAutoloader' );

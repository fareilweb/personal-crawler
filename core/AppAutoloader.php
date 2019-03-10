<?php
/**
 * Get Configuration
 * This file is required and MUST be present to make the app working
 */
$configuration_file_path =__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "configuration.php";
if( !file_exists($configuration_file_path) )
{
	echo "No configuration file found! This file is required and MUST be present in root folder of the app. to make the app working.";
	exit;
}
require_once ( $configuration_file_path );


/**
 * App Autoloader Registering
 * @param string $class_name
 */
function AppAutoloader ( $class_name )
{
	$directories = [
		PATH_APP,
		PATH_CORE,
		PATH_MANAGERS,
		PATH_MODELS,
		PATH_DTOS,
		PATH_HELPERS,
		PATH_INTERFACES,
        PATH_ENUMERATIONS
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

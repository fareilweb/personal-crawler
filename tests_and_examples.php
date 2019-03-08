<?php
require_once (__DIR__ . '/core/AppAutoloader.php');

function GetUrlAndStoreToFolder( $document_url, $file_name )
{
	$httpManager = new HttpManager();
	$domManager =  new DomManager();
	$request_result = $httpManager->MakeCurlRequest( $document_url, FALSE );
	$file_path = __DIR__ . DIRECTORY_SEPARATOR . 'test_files' . DIRECTORY_SEPARATOR . $file_name;
	file_put_contents($file_path, $request_result->content);
}

$array = array(0 => ['name'=>'color', 'value' => 'blue'], 1 => ['name' => 'color', 'value' => 'green'], 3 => ['name' => 'color', 'value' => 'pink']);

$key = array_search('pink', array_column($array, 'value'));

print_r($key);

exit;






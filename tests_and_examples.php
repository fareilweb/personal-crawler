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

$date_old = new DateTime('2019-03-07');
$date_now = new DateTime();

$interval = $date_now->diff($date_old);
//echo $interval->format('%R%a days');
echo $interval->days;

//print_r($interval);

exit;






<?php
require_once (__DIR__ . '/core/AppAutoloader.php');

function GetUrlAndStoreToFolder( $document_url, $file_name )
{
	$httpManager = new HttpManager();
	$domManager =  new DomManager();
	$request_result = $httpManager->MakeRequest( $document_url, FALSE );
	$file_path = __DIR__ . DIRECTORY_SEPARATOR . 'test_files' . DIRECTORY_SEPARATOR . $file_name;
	file_put_contents($file_path, $request_result->content);
}


function GetStringWith(string $string_id, array $strings_to_interpolate = []) : string
{
	//$new_string = $this->GetString( $string_id );

	$new_string = "Mia super stringa con {0}";

	foreach( $strings_to_interpolate as $key => $val )
	{
		$new_string = str_replace('{'.$key.'}', $val, $new_string);
	}

	return $new_string;
}

echo GetStringWith("", ["parola"]);

exit;
<?php
require_once (__DIR__ . '/core/AppAutoloader.php');

function getUrlAndStoreToFolder( $document_url, $file_name )
{
	$httpManager = new HttpManager();
	$domManager =  new DomManager();
	$request_result = $httpManager->MakeRequest( $document_url, FALSE );
	$file_path = __DIR__ . DIRECTORY_SEPARATOR . 'test_files' . DIRECTORY_SEPARATOR . $file_name;
	file_put_contents($file_path, $request_result->content);
}



class MyClass
{
	private $a;
	protected $b;
	public $c;

	function __construct()
	{

	}

	function __destruct()
	{
		foreach( get_object_vars($this) as $key => $val)
		{
			unset( $this->{$key} );
		}
	}

}


$myclass = new MyClass();


exit;
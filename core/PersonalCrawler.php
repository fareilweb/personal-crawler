<?php
/**
 * PersonalCrawler
 */
class PersonalCrawler
{
	private $args = [];

	function __construct(Array $argv = [])
	{
		$this->Initialize($argv);
	}

	function Initialize(Array $argv)
	{
		unset($argv[0]); // Remove first argoument (script name)
		$this->args = array_values($argv); // Re-Index argouments array


		print_r($this->args);
	}

	

}

<?php
/**
 * [Personal Crawler]
 */
class PersonalCrawler
{
	private $_urlset = [];

	/**
	 * [__construct description]
	 * @param HttpHelper $httpHelper
	 */
	function __construct( HttpHelper $httpHelper )
	{

	}

	/**
	 * [Initialize - Set inititial stuff]
	 * @param array $argv
	 */
	function Initialize( array $argv )
	{
		unset( $argv[0] ); // Remove first argoument (script name)
		$params = array_values( $argv ); // Re-Index argouments array
		$this->ParseParams( $params );
	}

	/**
	 * [ParseParams - Get parameters array and switch the right action]
	 * @param array $params [parameters]
	 */
	private function ParseParams( array $params )
	{
		$action = ucfirst( $params[0] );
		$url = "";

		$url_key = array_search('-u', $params) || array_search('--url', $params);
		if($url_key) {
			$url = $params[ $url_key+1  ];
		}

		$this->{$action}( $url );
	}



	private function Crawl( string $url )
	{
		echo $url;
	}


}

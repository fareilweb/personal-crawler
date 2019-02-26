<?php

class PersonalCrawler
{

#region Fields

	/**
	 * @var array
	 */
	private $urlset;

#endregion Fields



#region Dependecies instances

	/**
	 * @var HttpManager $http
	 */
	private $http;

#endregion Dependecies instances


	/**
	 * The constructor of the class
	 * @param HttpManager $http_manager
	 */
	public function __construct( HttpManager $http_manager )
	{
		// Store dependencies
		$this->http = $http_manager;
	}


	/**
	 * Do all initialization stuff
	 *
	 * @param array $argv
	 */
	public function Initialize( array $argv )
	{
		// Initialize fields
		$this->urlset = [];

		// Parse parameters
		$this->ParseParams( $argv );
	}


	/**
	 * Add gived url to the current url set
	 *
	 * @param string $url - the url to add
	 * @return boolean - return TRUE if the url is valid FALSE otherwise
	 */
	public function AddUrl( $url ) : bool
	{
		if( !$this->http->IsValidUrl($url) )
			return FALSE;

		return array_push($this->urlset, $url);
	}


	/**
	 * Get parameters array and switch the right action
	 *
	 * @param array $params [parameters]
	 * @return void
	 */
	private function ParseParams( array $params )
	{
		unset( $params[0] ); // Remove first argoument (is the script file name)
		$params = array_values( $params ); // Re-Index argouments array

		if(count($params) == 0)
			return;

		// Get url parameter
		$url_key = array_search("--url", $params);
		if($url_key !== FALSE) {
			$url = $params[ $url_key+1  ];
			$this->AddUrl($url);
		}


		// $action = ucfirst( $params[0] );
		// $url = "";
		// $this->{$action}( $url );
	}


	/**
	 * Crawl- start to crawling from gived url
	 *
	 * @param string $url
	 * @return void
	 */
	public function Crawl()
	{
		$url = $this->urlset[0];
		$res = $this->http->MakeRequest( $url, TRUE );

		print_r( $res );

		$file = __DIR__ . '/../tmp/request_response.html';
		file_put_contents($file, $res->data);

	}


}

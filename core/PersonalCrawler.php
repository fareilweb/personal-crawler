<?php

class PersonalCrawler
{

	#region # Private fields ############################################################
	/**
	 * @var array
	 */
	private $urlset;

	/**
	 * @var HttpManager $http
	 */
	private $http;
	#endregion # Private fields #########################################################




	#region Public fields ###############################################################
	/**
	 * @var string $param_action
	 */
	public $param_action = "";

	/**
	 * @var string $param_url
	 */
	public $param_url = "";
	#endregion # Public fields ##########################################################




	#region # Public methods ############################################################
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


		// If an action was set execute it
		if( !empty($this->param_action) )
		{
			$action_method = ucfirst( $this->param_action );
			$this->{$action_method}();
		}					
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
	#endregion # Public methods ########################################################




	#region Action methods
	/**
	 * Crawl- start to crawling from a 0gived url
	 *
	 * @param string $url
	 * @return void
	 */
	public function Crawl()
	{
		if( empty($this->param_url) ) 
		{
			// TODO - implements and insert here a localized messaging system
			return;
		}	

		$request_response = $this->http->MakeRequest( $this->param_url, TRUE );
		print_r( $request_response );

		// print_r( $request_response );
		// $file = __DIR__ . '/../tmp/request_response.html';
		// file_put_contents($file, $res->data);
	}	
	#endregion Action methods


	#region # Private methods ##########################################################
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

		// Get "action" parameter
		$this->param_action = $this->GetParam("--action", "-a", $params);
		
		// Get url parameter
		$this->param_url = $this->GetParam("--url", "-u", $params);		
	}


	/**
	 * Retrieve the gived parameter value from $params array 
	 *
	 * @param string $extended_param_key - the extended version of the param key
	 * @param string $short_param_key - the short version of the param key
	 * @return string - the value of the searched param or an empty string if parameter not found or invalid
	 */
	private function GetParam(string $extended_param_key, string $short_param_key, array $params = []) : string
	{
		/* search extended version */
		$param_key_index = array_search( $extended_param_key, $params ); 
		if ( $param_key_index !== FALSE && count($params) > $param_key_index + 1 ) 		
			return $params[ $param_key_index + 1  ];
		
		/* search short version	*/
		$param_key_index = array_search( $short_param_key, $params ); 
		if ( $param_key_index !== FALSE && count($params) > $param_key_index + 1 ) 		
			return $params[ $param_key_index + 1  ];

		/* Parameter not found or invalid, return empty string */
		return "";
	}

	#endregion # Private methods ######################################################


}

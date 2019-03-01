<?php
/**
 * PersonalCrawler - Class that manage crawling, scraping, search, index, and more works.
 */
class PersonalCrawler
{

#region # Dependencies
	/**
	 * @var HttpManager $httpMgr
	 */
	private $httpMgr;

	/**
	 * @var LocalizationManager $locMgr
	 */
	private $locMgr;
#endregion # Dependencies




#region Fields
	/**
	 * @var array
	 */
	private $urlset;

	/**
	 * @var RequestResponseDto $current_response
	 */
	private $current_response;

	private $current_data;
#endregion Fields



#region Parameters

	/**
	 * @var boolean $param_help
	 */
	public $param_help =  FALSE;

	/**
	 * @var string $param_action
	 */
	public $param_action = "";

	/**
	 * @var string $param_url
	 */
	public $param_url = "";

	/**
	 * @var boolean $follow_redirect - default value is "TRUE"
	 */
	public $ignore_redirect;
#endregion # Parameters


#region # Public methods
	/**
	 * The constructor of the class
	 * @param HttpManager $http_manager
	 * @param LocalizationManager $localization_manager
	 */
	public function __construct( HttpManager $http_manager, LocalizationManager $localization_manager )
	{
		// Store dependencies
		$this->httpMgr = $http_manager;
		$this->locMgr = $localization_manager;
	}

	/**
	 * Do all initialization stuff
	 *
	 * @param array $argv
	 */
	public function Initialize ( array $argv )
	{
		// Initialize fields
		$this->urlset = [];


		// Parse parameters
		$this->ParseParams( $argv );

		// User ask for help
		if( $this->param_help )
		{
			$this->ShowUserManual();
			return;
		}

		// If an action was set execute it
		if( !empty($this->param_action) )
		{
			$action_method = ucfirst( $this->param_action );
			$this->{$action_method}();
		}
	}

	public function ShowUserManual()
	{
		$lang = "en-GB";

		$user_manual_path = PATH_DOC . DIRECTORY_SEPARATOR . "Personal-Crawler-User-Manual_{$lang}.txt";

		$user_manual_text = file_get_contents( $user_manual_path );

		echo $user_manual_text;
	}

	/**
	 * Add gived url to the current url set
	 *
	 * @param string $url - the url to add
	 * @return boolean - return TRUE if the url is valid FALSE otherwise
	 */
	public function AddUrl( $url ) : bool
	{
		if( !$this->httpMgr->IsValidUrl($url) )
			return FALSE;

		return array_push($this->urlset, $url);
	}
#endregion # Public methods

#region Action methods
	/**
	 * This method wrap the ShowUserManual() method into an action
	 *
	 * @return void
	 */
	public function Help()
	{
		$this->ShowUserManual();
	}

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
			echo $this->locMgr->GetString("no_url_provided_error");
			exit;
		}

		$this->StartCrawling( $this->param_url, $this->ignore_redirect );
	}

#endregion Action methods

#region # Private methods


	/**
	 * Start crawling and collect data for indxed
	 *
	 * @param string $url
	 * @param bool $ignore_redirect
	 * @return void
	 */
	private function StartCrawling( $url, $ignore_redirect ) : void
	{
		if( $this->current_response == NULL)
		{
			$this->current_response = $this->httpMgr->MakeRequest( $url, $ignore_redirect );
		}

		$data = $this->ExtractDataFromResponse( $this->current_response );

	}


	private function ExtractDataFromResponse( RequestResponseDto $response_dto ) : RequestResponseModel
	{
		$model = new RequestResponseModel(
			NULL,
			$response_dto->info['url'],
			$response_dto->info['redirect_url'],
			$response_dto->info['http_code'],
			$response_dto->info['content_type'],
			$response_dto->info['primary_ip'],
			$response_dto->info['primary_port']		
		);

		

		return $model;
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

		// Get "help" parameter
		$this->param_help = $this->HasParam("--help", "-h", $params) ? TRUE : FALSE;

		// Get "action" parameter
		$this->param_action = $this->GetParam("--action", "-a", $params, "crawl");

		// Get "url" parameter
		$this->param_url = $this->GetParam("--url", "-u", $params, NULL);

		// Get "follow redirect" parameter
		$this->ignore_redirect = $this->HasParam("--ignore-redirect", "-ir", $params) ? TRUE : FALSE;
	}


	/**
	 * Retrieve the gived parameter value from $params array
	 *
	 * @param string $extended_param_key - the extended version of the param key
	 * @param string $short_param_key - the short version of the param key
	 * @param mixed $default_value - the fallback value to use if no one will be found
	 * @param array $params - the list of parameters passed by the user
	 * @return string - the value of the searched param or a default value if parameter will not be found or invalid
	 */
	private function GetParam(string $extended_param_key, string $short_param_key, array $params = [], $default_value = "") : string
	{
		/* search extended version */
		$extended_param_key_index = array_search( $extended_param_key, $params );
		if ( $extended_param_key_index !== FALSE && count($params) > $extended_param_key_index + 1 )
			return $params[ $extended_param_key_index + 1  ];

		/* search short version	*/
		$short_param_key_index = array_search( $short_param_key, $params );
		if ( $short_param_key_index !== FALSE && count($params) > $short_param_key_index + 1 )
			return $params[ $short_param_key_index + 1  ];

		/* Parameter not found or invalid, return empty string */
		return "";
	}


	/**
	 * Test if the gived array contains the gived param key
	 *
	 * @param string $extended_param_key - the extended version of the param key
	 * @param string $short_param_key - the short version of the param key
	 * @param array $params - the list of parameters passed by the user
	 * @return boolean - return TRUE if parameter key was found FALSE otherwise
	 */
	private function HasParam( string $extended_param_key, string $short_param_key, $params = [] ) : bool
	{
		/* search extended version */
		$extended_param_key_index = array_search( $extended_param_key, $params );
		if ( $extended_param_key_index !== FALSE )
			return TRUE;

		/* search short version	*/
		$short_param_key_index = array_search( $short_param_key, $params );
		if ( $short_param_key_index !== FALSE )
			return TRUE;

		/* Param not found */
		return FALSE;
	}


#endregion # Private methods
}

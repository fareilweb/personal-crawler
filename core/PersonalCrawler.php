<?php
/**
 * PersonalCrawler - Class that manage crawling, scraping, search, index, and more works.
 */
class PersonalCrawler
{

#region # User Parameters

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

#endregion # User Parameters




#region # Dependencies instances

	/**
	 * @var HttpManager $httpManager
	 */
	private $httpManager;

	/**
	 * @var UrlHelper $urlHelper
	 */
	private $urlHelper;

	/**
	 * @var LocalizationManager $localizationManager
	 */
	private $localizationManager;

	/**
	 * @var IStorageManager $storageManager
	 */
	private $storageManager;

#endregion # Dependencies instances




#region Fields

	/**
	 * @var array
	 */
	private $urlset;

	/**
	 * @var RequestResponseModel $current_response_model
	 */
	private $current_response_model;

#endregion Fields




#region # Public methods
	/**
	 * The constructor of the class
	 * @param HttpManager $http_manager
	 * @param LocalizationManager $localization_manager
	 */
	public function __construct(
		HttpManager $http_manager,
		UrlHelper $url_helper,
		LocalizationManager $localization_manager,
		IStorageManager $storage_manager
	) {
		// Store dependencies instances
		$this->httpManager = $http_manager;
		$this->urlHelper = $url_helper;
		$this->localizationManager = $localization_manager;
		$this->storageManager = $storage_manager;
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

	/**
	 * Show help documentation
	 *
	 * @return void
	 */
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
		if( !$this->httpManager->IsValidUrl($url) )
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
			echo $this->localizationManager->GetString("no_url_provided_error");
			exit;
		}

		$this->StartCrawlingFromUrl( $this->param_url, $this->ignore_redirect );
	}

	public function Get()
	{
		if( empty($this->param_url) )
		{
			echo $this->localizationManager->GetString("no_url_provided_error");
			exit;
		}

		$this->GetContentOfUrl( $this->param_url, $this->ignore_redirect );
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
	private function StartCrawlingFromUrl( $url, $ignore_redirect ) : void
	{
		if( $this->current_response_model == NULL)
		{


		}
	}


	private function GetContentOfUrl( $url, $ignore_redirect ) : void
	{
		$requestResult = $this->httpManager->MakeRequest( $url, $ignore_redirect );

		$curlGetinfoResult 	= $requestResult['curl_getinfo_result'];
		$curlExecResult 	= $requestResult['curl_exec_result'];

		$requestInfoDto = new RequestInfoDto( $requestResult['curl_getinfo_result'] );



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

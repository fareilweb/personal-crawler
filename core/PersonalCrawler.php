<?php
/**
 * PersonalCrawler - Class that manage crawling, scraping, search, index, and more works.
 */
class PersonalCrawler
{
	#region # Fields

	/** @var HttpManager */
	private $httpManager;

	/** @var LocalizationManager */
	private $localizationManager;

	/** @var IStorageManager */
	private $storageManager;

	/** @var RequestResponseModel */
	private $current_response_model;

	/** @var array */
	private $urlset;

	/** @var ParametersManager */
	private $parametersManager;

	#endregion # Fields



	#region # Properties
	
	#endregion # Properties


	#region # Public methods

	/**
	 * The constructor of the class
	 * @param HttpManager
	 * @param LocalizationManager
	 * @param IStorageManager
	 * @param ParametersManager  
	 */
	public function __construct(
		HttpManager $http_manager,
		LocalizationManager $localization_manager,
		IStorageManager $storage_manager,
		ParametersManager $parameters_manager
	) {
		// Store dependencies instances
		$this->httpManager = $http_manager;
		$this->localizationManager = $localization_manager;
		$this->storageManager = $storage_manager;
		$this->parametersManager = $parameters_manager;
	}

	/**
	 * Do all initialization stuff
	 *
	 * @param array
	 */
	public function Initialize ( array $argv )
	{
		// Initialize fields
		$this->urlset = [];

		$this->parametersManager->ParseParams( $argv );

		// User ask for help
		if( $this->parametersManager->help )
		{
			$this->ShowUserManual();
			return;
		}

		// If an action was set execute it
		if( !empty($this->parametersManager->action) )
		{
			$action_method = ucfirst( $this->parametersManager->action );
			$this->{$action_method}();
		}
	}

	

	/**
	 * Add gived url to the current url set
	 *
	 * @param string - the url to add
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
	 * @return void
	 */
	public function Crawl()
	{
		$this->StartCrawlingFromUrl( $this->parametersManager->url, $this->parametersManager->signore_redirect );
	}


	public function Get()
	{
		if( empty($this->parametersManager->url) )
		{
			echo $this->localizationManager->GetString("no_url_provided_error");
			exit;
		}

		$action = $this->GetMethodByUrl( $this->parametersManager->url );
	}

	#endregion Action methods




	#region # Private methods

	/**
	 * Start crawling and collect data for indxed
	 *
	 * @param string $url
	 * @param bool $param_ignore_redirect
	 * @return void
	 */
	private function StartCrawlingFromUrl( $url, $param_ignore_redirect ) : void
	{
		if( empty($this->parametersManager->url) )
		{
			echo $this->localizationManager->GetString("no_url_provided_error");
			exit;
		}

		// $requestResult = $this->httpManager->MakeRequest( $url, $param_ignore_redirect );
		// $curlGetinfoResult 	= $requestResult['curl_getinfo_result'];
		// $curlExecResult 	= $requestResult['curl_exec_result'];
		// $requestInfoDto = new RequestInfoDto( $requestResult['curl_getinfo_result'] );
	}


	private function GetMethodByUrl( $url ) : string
	{
		$url_scheme = UrlHelper::GetUrlScheme( $url );
		switch ( $url_scheme )
		{
			case 'http':
				return "";
				
			case 'https':
				return "";

			case 'ftp':
				return "";

			case 'ftps':
				return "";

			case 'sftp':
				return "";

			case 'mailto':
				return "";

			case 'skype':
				return "";

			case 'tel':
				return "";

			default:
				return "";
		}
		
	}

	/**
	 * Show help documentation
	 *
	 * @return void
	 */
	private function ShowUserManual()
	{
		$lang = "en-GB";

		$user_manual_path = PATH_DOC . DIRECTORY_SEPARATOR . "Personal-Crawler-User-Manual_{$lang}.txt";

		$user_manual_text = file_get_contents( $user_manual_path );

		echo $user_manual_text;
	}

	#endregion # Private methods

}

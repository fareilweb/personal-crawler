<?php
/**
 * An helper to handle network comunications by HTTP/HTTPS protocol
 */
class HttpManager
{

	/**
	 * Instance of a model when store the request results data
	 *
	 * @var RequestResponse $request_response
	 */
	public $request_response;

	/**
	 * A list of known browser user agents
	 *
	 * @var array $user_agents
	 */
	private $user_agents = [
		'chrome' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.119 Safari/537.36',
		'firefox' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:63.0) Gecko/20100101 Firefox/63.0'
	];


	/**
	 * The current user agent
	 *
	 * @var string $current_user_agent - User agent string to use inside HTTP requests
	 */
	private $current_user_agent;


	/**
	 * A list of known browser request headers
	 *
	 * @var array
	 */
	private $headers = [
		'chrome' => [
			'method: GET',
			'path: /',
			'scheme: https',
			'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
			//'accept-encoding: gzip, deflate, br',
			'accept-language: it,en;q=0.9,ru;q=0.8,de;q=0.7,fr;q=0.6',
			'cache-control: no-cache',
			'cookie: ay=b',
			'dnt: 1',
			'pragma: no-cache',
			'upgrade-insecure-requests: 1'
		],
		'firefox' => []
	];


	/**
	 * The headers that will be used inside the request
	 *
	 * @var array $current_headers
	 */
	private $current_headers;



	/**
	 * The constructor of the class
	 */
	public function __construct() {
		// Store dependencies
		$this->current_user_agent = $this->user_agents[ 'chrome' ];
		$this->current_headers = $this->headers[ 'chrome' ];

		// Request response to return with request data
		$this->request_response = new RequestResponse();
	}


	/**
	 * Make an HTTP request by cURL
	 *
	 * @param string $url
	 * @param boolean $follow_redirect
	 * @return RequestResponse
	 */
	public function MakeRequest(string $url, bool $follow_redirect = FALSE) : RequestResponse
	{
		$curl = curl_init(); // Initialize curl

		// Set hardcoded options
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_VERBOSE, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		// Set if follow redirects
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, $follow_redirect);

		// Set user agent
		curl_setopt($curl, CURLOPT_USERAGENT, $this->current_user_agent);

		// Set headers
		curl_setopt($curl, CURLOPT_HTTPHEADER, $this->current_headers );

		// Set URL
		curl_setopt($curl, CURLOPT_URL, $url);

		// Make request and get info about it
		$this->request_response->data = curl_exec($curl);
		$this->request_response->info = curl_getinfo($curl);

		curl_close($curl); // Close curl connection
		return $this->request_response;
	}


	/**
	 * @method SetUserAgent() - Set user agent by choose from those available or set an arbitrary one
	 * @param string $user_agent_key - The key of the choosed user agent, choice betwen: "chrome", "firefox" or NULL if an arbitrary user agent will be gived.
	 * @param string $user_agent - The arbitrary user agent to set or NULL OR NULL if has been already chosen between availables.
	 * @return bool - TRUE if success FALSE if fail
	 */
	public function SetUserAgent(string $user_agent_key = NULL, string $user_agent = NULL) : bool
	{
		if( $user_agent_key != NULL )
		{
			$this->current_user_agent = $this->user_agents[ $user_agent_key ];
			return TRUE;
		}

		if($user_agent != NULL)
		{
			$this->current_user_agent = $user_agent;
			return TRUE;
		}

		return FALSE;
	}


	/**
	 * [IsValidUrl - test gived url for validity]
	 * @param string $url [the url to test]
	 * @return bool [return true if url is valid, false if it's not]
	 */
	public function IsValidUrl (string $url) : bool
	{
		if(empty($url)) {
			return false; // Empty url
		}

		$url_parts = parse_url($url);

        if(!isset($url_parts['scheme'])) {
            return false; // No scheme
        }

		return true;
	}
}
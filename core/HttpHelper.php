<?php
/**
 * [HttpHelper - an helper to handle network comunications by HTTP/HTTPS protocol]
 */
class HttpHelper
{
	/**
	 * $_userAgents
	 * @var [array]
	 */
	private $_user_agents = [
		'chrome' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.77 Safari/537.36',
		'firefox' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:63.0) Gecko/20100101 Firefox/63.0'
	];


	/**
	 * The current user agent
	 * @var {string} $_user_agent - User agent string to use inside HTTP request
	 */
	private $_user_agent;
	/**
	 * @method SetUserAgent() - Set user agent by choose from those available or set an arbitrary one
	 * @param string $user_agent_key - The key of the choosed user agent, choice betwen: "chrome", "firefox" or NULL if an arbitrary user agent will be gived.
	 * @param string $user_agent - The arbitrary user agent to set or NULL OR NULL if has been already chosen between availables.
	 */
	public function SetUserAgent(string $user_agent_key = NULL, string $user_agent) {
		$this->_user_agent = $user_agent_key;
	}
	public function GetUserAgent() {
		return $this->_userAgent;
	}


	function __construct() {

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

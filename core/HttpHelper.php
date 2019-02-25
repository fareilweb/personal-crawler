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
	private $_userAgents = [
		'chrome' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.77 Safari/537.36',
		'firefox' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:63.0) Gecko/20100101 Firefox/63.0'
	];


	/**
	 * [The starting url]
	 * @var [string] - the url from wich start crawling
	 */
	private $_url;
	public function SetUrl($url) {
		$this->_url = $url;
	}
	public function GetUrl() {
		return $_url;
	}

	/**
	 * [The current user agent]
	 * @var [string] - user agent string to use inside http request
	 */
	private $_userAgent;
	public function SetUserAgent($user_agent_key) {
		$this->_userAgent = $user_agent_key;
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

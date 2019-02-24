<?php
/**
 * [HttpHelper - an helper to handle network comunications by HTTP/HTTPS protocol]
 */
class HttpHelper
{

	private $_userAgents = [
		'chrome' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.77 Safari/537.36',
		'firefox' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:63.0) Gecko/20100101 Firefox/63.0'
	];


	function __construct()
	{

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

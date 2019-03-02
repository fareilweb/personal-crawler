<?php

class UrlHelper
{

	/**
	 * Try to get the scheme of gived url
	 *
	 * @param string $url
	 * @return string
	 */
	public static function GetUrlScheme( string $url ) : string
	{
		$url_parts = parse_url($url);
		return $url_parts['scheme'];
	}


	/**
	 * Test gived url for validity
	 * @param string $url - the url to test
	 * @return bool - return TRUE if url is valid, FALSE otherwise
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


	/**
     * Analize and try to fix and build a valid url
     *
     * @param string $url
     * @param string $parent_url
     * @return string $valid_url
     */
    public static function FixUrl($url, $parent_url)
    {
        $parent_url_parts = parse_url($parent_url);
        $url_parts = parse_url($url);

        // Exclude Schemes for Emails, Phones, Ecc...
        $excluded_schemes = ['mailto', 'tel', 'skype'];
        if (isset($url_parts['scheme']) && isset($url_parts['path']) && in_array($url_parts['scheme'], $excluded_schemes))
        {
            return $url; // Return as is
        }

        // Should Be a Web url to a document
        $valid_url = "";
        $valid_url .= isset($url_parts['scheme'])   ? $url_parts['scheme'] . "://"  : ( isset($parent_url_parts['scheme']) ? $parent_url_parts['scheme'] . "://" : "" );
        $valid_url .= isset($url_parts['host'])     ? $url_parts['host']            : ( isset($parent_url_parts['host']) ? $parent_url_parts['host'] : "" );
        $valid_url .= isset($url_parts['path'])     ? $url_parts['path']            : ( isset($parent_url_parts['path']) ? $parent_url_parts['path'] : "" );
        $valid_url .= isset($url_parts['query'])    ? "?" . $url_parts['query']     : ""; // ?
        $valid_url .= isset($url_parts['fragment']) ? "#" . $url_parts['fragment']  : ""; // #

        return $valid_url;
    }
}


class UrlSchemes
{
	public const http = 'http';
	public const https = 'https';
	public const ftp = 'ftp';
	public const mailto = 'mailto';
	public const tel = 'tel';
	public const skype = 'skype';
}


